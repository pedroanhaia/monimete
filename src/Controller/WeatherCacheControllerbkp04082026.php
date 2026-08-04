<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\I18n\DateTime;

class WeatherCacheController extends AppController
{
    private const MAX_LOAD_POINTS = 700;
    private const MAX_SAVE_RECORDS = 60;
    private const CACHE_MINUTES = 60;
    private const REFRESH_LOCK_MINUTES = 2;
    private const TYPE_CURRENT = 90;
    private const TYPE_HYDROLOGY = 91;

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['load', 'save']);
    }

    /**
     * Carrega, em uma única consulta, o registro meteorológico mais recente
     * de cada cidade/escopo. A validade é calculada exclusivamente por created.
     */
    public function load()
    {
        $this->request->allowMethod(['post']);
        $points = $this->normalizePoints((array)$this->request->getData('points'));
        if ($points === []) {
            return $this->jsonResponse(['success' => true, 'records' => []]);
        }

        $citiesTable = $this->fetchTable('Cities');
        $locationsTable = $this->fetchTable('Locations');
        $dataTable = $this->fetchTable('DataMetereological');
        $cityNames = array_values(array_unique(array_column($points, 'cityName')));

        $citiesByName = [];
        foreach ($citiesTable->find()->where(['name IN' => $cityNames])->all() as $city) {
            $citiesByName[$this->normalizeCityName((string)$city->name)] = $city;
        }

        $cityIds = array_values(array_map(
            static fn($city) => (int)$city->id,
            $citiesByName
        ));
        $locationsByCity = [];
        if ($cityIds !== []) {
            foreach ($locationsTable->find()->where(['city_id IN' => $cityIds])->orderByAsc('id')->all() as $location) {
                $locationsByCity[(int)$location->city_id] ??= $location;
            }
        }

        $locationIds = array_values(array_map(
            static fn($location) => (int)$location->id,
            $locationsByCity
        ));
        $latestByLocationType = $this->latestMeteorologicalRows($dataTable, $locationIds);
        $now = DateTime::now();
        $validSince = new DateTime('-' . self::CACHE_MINUTES . ' minutes');
        $refreshThreshold = new DateTime('-' . self::REFRESH_LOCK_MINUTES . ' minutes');
        $records = [];

        foreach ($points as $point) {
            $normalizedName = $this->normalizeCityName($point['cityName']);
            $city = $citiesByName[$normalizedName] ?? null;
            $location = $city ? ($locationsByCity[(int)$city->id] ?? null) : null;
            $type = $point['scope'] === 'hydrology' ? self::TYPE_HYDROLOGY : self::TYPE_CURRENT;
            $row = $location ? ($latestByLocationType[(int)$location->id . ':' . $type] ?? null) : null;

            if (!$row) {
                $records[$point['cacheKey']] = [
                    'data' => null,
                    'fresh' => false,
                    'refresh' => $city ? $this->claimRefresh($citiesTable, (int)$city->id, $now, $refreshThreshold) : true,
                    'fetchedAt' => null,
                ];
                continue;
            }

            $data = json_decode((string)$row['weather'], true);
            if (!is_array($data)) {
                continue;
            }
            $created = new DateTime((string)$row['created']);
            $fresh = $created >= $validSince;
            $records[$point['cacheKey']] = [
                'data' => $data,
                'fresh' => $fresh,
                'refresh' => !$fresh && $city
                    ? $this->claimRefresh($citiesTable, (int)$city->id, $now, $refreshThreshold)
                    : false,
                'fetchedAt' => $created->format(DATE_ATOM),
            ];
        }

        return $this->jsonResponse(['success' => true, 'records' => $records]);
    }

    /**
     * Persiste somente respostas já validadas recebidas da Open-Meteo pelo cliente.
     * Cada atualização gera um registro histórico em data_metereological.
     */
    public function save()
    {
        $this->request->allowMethod(['post']);
        if (!$this->isSameOrigin()) {
            return $this->jsonResponse(['success' => false, 'message' => 'Origem não autorizada.'], 403);
        }

        $records = array_slice((array)$this->request->getData('records'), 0, self::MAX_SAVE_RECORDS);
        $citiesTable = $this->fetchTable('Cities');
        $locationsTable = $this->fetchTable('Locations');
        $dataTable = $this->fetchTable('DataMetereological');
        $connection = $dataTable->getConnection();
        $saved = 0;
        $rejected = 0;
        $duplicated = 0;

        // Evita cidades/localizações duplicadas quando dois clientes salvam juntos.
        $lock = $connection->execute("SELECT GET_LOCK('monimete_weather_cache_save', 5) AS acquired")
            ->fetch('assoc');
        if ((int)($lock['acquired'] ?? 0) !== 1) {
            return $this->jsonResponse(['success' => false, 'message' => 'Cache ocupado. Tente novamente.'], 503);
        }

        try {
            foreach ($records as $record) {
                if (!is_array($record) || !$this->validWeatherRecord($record)) {
                    $rejected++;
                    continue;
                }

                $now = DateTime::now();
                $cityName = $this->cleanCityName((string)$record['cityName']);
                $latitude = round((float)$record['latitude'], 4);
                $longitude = round((float)$record['longitude'], 4);
                $type = $record['scope'] === 'hydrology'
                    ? self::TYPE_HYDROLOGY
                    : self::TYPE_CURRENT;

                $city = $citiesTable->find()->where(['name' => $cityName])->first();
                if (!$city) {
                    $city = $citiesTable->newEntity([
                        'name' => $cityName,
                        'description' => 'Município cadastrado automaticamente pelo cache meteorológico.',
                        'role' => 0,
                        'datelastsearch' => $now,
                    ]);
                    if (!$citiesTable->save($city)) {
                        $rejected++;
                        continue;
                    }
                } else {
                    $city->datelastsearch = $now;
                    $citiesTable->save($city);
                }

                $location = $locationsTable->find()
                    ->where(['city_id' => (int)$city->id])
                    ->orderByAsc('id')
                    ->first();
                if (!$location) {
                    $location = $locationsTable->newEntity([
                        'name' => $cityName,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'description' => 'Ponto central usado para consulta meteorológica.',
                        'role' => 0,
                        'city_id' => (int)$city->id,
                    ]);
                    if (!$locationsTable->save($location)) {
                        $rejected++;
                        continue;
                    }
                }

                // Uma segunda resposta concorrente dentro de cinco minutos não vira histórico duplicado.
                $recentDuplicate = $dataTable->find()
                    ->where([
                        'location_id' => (int)$location->id,
                        'type' => $type,
                        'created >=' => new DateTime('-5 minutes'),
                    ])
                    ->first();
                if ($recentDuplicate) {
                    $duplicated++;
                    continue;
                }

                $data = $record['data'];
                $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($payload === false || strlen($payload) > 16384) {
                    $rejected++;
                    continue;
                }
                $temperature = (float)$data['temperature'];
                $entity = $dataTable->newEntity([
                    'date_time' => $now,
                    'temperature' => $temperature,
                    'humidity' => null,
                    'precipitation' => (float)$data['precipitation'],
                    'wind_direction' => (string)$data['windDirection'],
                    'wind_speed' => (float)$data['windSpeed'],
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'location_id' => (int)$location->id,
                    'service_id' => null,
                    'device_id' => null,
                    'role' => 0,
                    'type' => $type,
                    'tempmin' => $temperature,
                    'tempmax' => $temperature,
                    'weather' => $payload,
                ]);

                if ($dataTable->save($entity)) {
                    $saved++;
                } else {
                    $rejected++;
                }
            }
        } finally {
            $connection->execute("SELECT RELEASE_LOCK('monimete_weather_cache_save')");
        }

        return $this->jsonResponse([
            'success' => true,
            'saved' => $saved,
            'duplicated' => $duplicated,
            'rejected' => $rejected,
        ]);
    }

    private function latestMeteorologicalRows(object $dataTable, array $locationIds): array
    {
        if ($locationIds === []) {
            return [];
        }
        $ids = implode(',', array_map('intval', $locationIds));
        $sql = "
            SELECT dm.id, dm.location_id, dm.type, dm.weather, dm.created
            FROM data_metereological dm
            INNER JOIN (
                SELECT location_id, type, MAX(created) AS latest_created
                FROM data_metereological
                WHERE location_id IN ($ids)
                  AND type IN (" . self::TYPE_CURRENT . ',' . self::TYPE_HYDROLOGY . ")
                GROUP BY location_id, type
            ) latest
                ON latest.location_id = dm.location_id
               AND latest.type = dm.type
               AND latest.latest_created = dm.created
        ";
        $rows = $dataTable->getConnection()->execute($sql)->fetchAll('assoc');
        $indexed = [];
        foreach ($rows as $row) {
            $indexed[(int)$row['location_id'] . ':' . (int)$row['type']] = $row;
        }
        return $indexed;
    }

    private function claimRefresh(object $citiesTable, int $cityId, DateTime $now, DateTime $threshold): bool
    {
        $statement = $citiesTable->getConnection()->execute(
            'UPDATE cities
             SET datelastsearch = :now
             WHERE id = :id
               AND (datelastsearch IS NULL OR datelastsearch <= :threshold)',
            [
                'now' => $now->format('Y-m-d H:i:s'),
                'id' => $cityId,
                'threshold' => $threshold->format('Y-m-d H:i:s'),
            ]
        );
        return $statement->rowCount() === 1;
    }

    private function normalizePoints(array $points): array
    {
        $normalized = [];
        foreach (array_slice($points, 0, self::MAX_LOAD_POINTS) as $point) {
            if (!is_array($point) || !isset($point['cityName'], $point['latitude'], $point['longitude'], $point['scope'])) {
                continue;
            }
            $cityName = $this->cleanCityName((string)$point['cityName']);
            $latitude = filter_var($point['latitude'], FILTER_VALIDATE_FLOAT);
            $longitude = filter_var($point['longitude'], FILTER_VALIDATE_FLOAT);
            $scope = $point['scope'] === 'hydrology' ? 'hydrology' : 'current';
            if ($cityName === '' || $latitude === false || $longitude === false ||
                $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                continue;
            }
            $normalized[] = [
                'cityName' => $cityName,
                'latitude' => round((float)$latitude, 4),
                'longitude' => round((float)$longitude, 4),
                'scope' => $scope,
                'cacheKey' => $this->cacheKey((float)$latitude, (float)$longitude, $scope),
            ];
        }
        return $normalized;
    }

    private function cacheKey(float $latitude, float $longitude, string $scope): string
    {
        return number_format($latitude, 4, '.', '') . '_' .
            number_format($longitude, 4, '.', '') . '_' . $scope;
    }

    private function normalizeCityName(string $name): string
    {
        return mb_strtolower(trim(preg_replace('/\s+/', ' ', $name) ?? ''));
    }

    private function cleanCityName(string $name): string
    {
        return mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($name)) ?? ''), 0, 200);
    }

    private function isSameOrigin(): bool
    {
        $expectedHost = strtolower($this->request->getUri()->getHost());
        $origin = $this->request->getHeaderLine('Origin') ?: $this->request->getHeaderLine('Referer');
        $originHost = strtolower((string)parse_url($origin, PHP_URL_HOST));
        return $expectedHost !== '' && $originHost === $expectedHost;
    }

    private function validWeatherRecord(array $record): bool
    {
        if (!isset($record['cityName'], $record['latitude'], $record['longitude'], $record['scope'], $record['data'])) {
            return false;
        }
        $cityName = $this->cleanCityName((string)$record['cityName']);
        $latitude = filter_var($record['latitude'], FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($record['longitude'], FILTER_VALIDATE_FLOAT);
        if ($cityName === '' || $latitude === false || $longitude === false ||
            $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return false;
        }
        if (!in_array($record['scope'], ['current', 'hydrology'], true) || !is_array($record['data'])) {
            return false;
        }

        $data = $record['data'];
        if (!$this->numberInRange($data['temperature'] ?? null, -90, 65) ||
            !$this->numberInRange($data['precipitation'] ?? null, 0, 1000) ||
            !$this->numberInRange($data['windSpeed'] ?? null, 0, 500) ||
            !$this->numberInRange($data['windDirection'] ?? null, 0, 360)) {
            return false;
        }

        if ($record['scope'] === 'hydrology') {
            $rain = $data['rain'] ?? null;
            if (!is_array($rain)) {
                return false;
            }
            foreach (['observed24h', 'observed72h', 'forecast24h', 'forecast72h'] as $field) {
                if (!$this->numberInRange($rain[$field] ?? null, 0, 5000)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function numberInRange(mixed $value, float $min, float $max): bool
    {
        return is_numeric($value) && is_finite((float)$value) &&
            (float)$value >= $min && (float)$value <= $max;
    }
}
