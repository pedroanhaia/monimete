<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;

/**
 * ImportaCoordenadas command.
 */
class ImportaCoordenadasCommand extends Command
{
    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'importa_coordenadas';

    /**
     * Get the default command name.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        return 'importa_coordenadas';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Command description here.';
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/5/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->setDescription(static::getDescription());
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null|void The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $filePath = ROOT . '/templates/cities/geojs-43-mun.json'; // ajuste conforme seu caminho
        $content = file_get_contents($filePath);
        $geojson = json_decode($content, true);

        $CidadesTable = TableRegistry::getTableLocator()->get('Cities');

        $LocalizacoesTable = TableRegistry::getTableLocator()->get('Locations');

        foreach ($geojson['features'] as $feature) {
            $nome = $feature['properties']['name'] ?? null;
            $geometry = $feature['geometry'];

            if (!$nome || !$geometry) {
                continue;
            }

            // Calcula o centro do polígono se for o caso
            if ($geometry['type'] === 'Polygon') {
                $coords = $geometry['coordinates'][0]; // pega o primeiro anel do polígono
                $latSum = 0;
                $lngSum = 0;
                $count = count($coords);
                foreach ($coords as $coord) {
                    $lngSum += $coord[0];
                    $latSum += $coord[1];
                }
                $lng = $lngSum / $count;
                $lat = $latSum / $count;
            } elseif ($geometry['type'] === 'Point') {
                [$lng, $lat] = $geometry['coordinates'];
            } else {
                $io->out("Tipo de geometria não suportado: " . $geometry['type']);
                continue;
            }

            $cidade = $CidadesTable->find()->where(['name' => $nome])->first();
            if (!$cidade) {
                $cidade = $CidadesTable->newEntity(['name' => $nome]);
                if ($CidadesTable->save($cidade)) {
                    $io->out("Salvo: $nome ($lat, $lng)");
                } else {
                    $io->err("Erro ao salvar: $nome");
                    continue;
                }
            }
            $location = $LocalizacoesTable->find()->where(['city_id' => $cidade->id])->first();
            if (!$location) {
                $location = $LocalizacoesTable->newEntity(['name'=>('cidade '.$nome), 'description'=>('localização da cidade '.$nome),'city_id' => $cidade->id, 'latitude' => $lat, 'longitude' => $lng]);
                if ($LocalizacoesTable->save($location)) {
                    $io->out("Salvo: $nome ($lat, $lng)");
                } else {
                    $io->err("Erro ao salvar localização: $nome");
                    continue;
                }
            }
        }

        $io->success('Importação concluída.');
    }
}
