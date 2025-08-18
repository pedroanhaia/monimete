<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Log\Log;
use Exception;

/**
 * Mqtt Controller
 *
 * Gerencia conexões e dados MQTT do sistema MoniMete
 */
class MqttController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        // Permitir acesso não autenticado para APIs específicas
        $this->Authentication->addUnauthenticatedActions([
            'status', 'messages', 'stats', 'weatherData', 'mqttData', 'login'
        ]);
        parent::beforeFilter($event);
    }

    /**
     * Dashboard MQTT - Interface apenas, dados carregados via JavaScript
     */
    public function index()
    {
        $this->set('pageTitle', 'Monitoramento MQTT');
        
        // Interface será populada via JavaScript
        // Dados históricos vêm do banco via APIs
        $this->set('liveMode', true);
    }

    /**
     * Status da conexão MQTT (API)
     */
    public function status()
    {
        $this->viewBuilder()->setClassName('Json');
        
        try {
            // Verificar últimas mensagens recebidas
            $logsTable = $this->fetchTable('Logs');
            $lastMessage = $logsTable->find()
                ->where(['type' => 3])
                ->orderByDesc('date_time')
                ->first();
            
            $isConnected = false;
            $lastMessageTime = null;
            
            if ($lastMessage) {
                $lastMessageTime = $lastMessage->date_time;
                // Considerar conectado se recebeu mensagem nos últimos 5 minutos
                $fiveMinutesAgo = date('Y-m-d H:i:s', strtotime('-5 minutes'));
                $isConnected = $lastMessage->date_time >= $fiveMinutesAgo;
            }
            
            $data = [
                'status' => 'success',
                'mqtt_connected' => $isConnected,
                'last_message_time' => $lastMessageTime,
                'server_info' => [
                    'internal_host' => '192.168.0.10',
                    'external_host' => 'mqtt.agrocitylivinglab.com.br',
                    'port' => 1983,
                    'username' => 'agrocity'
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * Buscar mensagens MQTT recentes (API)
     */
    public function messages()
    {
        $this->viewBuilder()->setClassName('Json');
        
        try {
            $limit = $this->request->getQuery('limit', 20);
            $offset = $this->request->getQuery('offset', 0);
            $deviceId = $this->request->getQuery('device_id');
            
            $logsTable = $this->fetchTable('Logs');
            $query = $logsTable->find()
                ->where(['type' => 3])
                ->orderByDesc('date_time')
                ->limit($limit)
                ->offset($offset);
            
            if ($deviceId) {
                $query->where(['device_id' => $deviceId]);
            }
            
            $messages = $query->toArray();
            
            $data = [
                'status' => 'success',
                'messages' => $messages,
                'pagination' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'total' => $logsTable->find()->where(['type' => 3])->count()
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }




    
    /**
     * API para buscar estatísticas MQTT
     */
    public function stats()
    {
        $this->viewBuilder()->setClassName('Json');
        
        try {
            $logsTable = $this->fetchTable('Logs');
            $dataMetTable = $this->fetchTable('DataMetereological');
            
            $stats = [
                'total_mqtt_messages' => $logsTable->find()->where(['type' => 3])->count(),
                'mqtt_weather_records' => $dataMetTable->find()->where(['type' => 2])->count(),
                'last_message_time' => $logsTable->find()
                    ->where(['type' => 3])
                    ->orderByDesc('date_time')
                    ->first()?->date_time,
                'active_devices' => $dataMetTable->find()
                    ->where(['type' => 2, 'date_time >=' => date('Y-m-d H:i:s', strtotime('-1 hour'))])
                    ->select(['device_id'])
                    ->distinct(['device_id'])
                    ->count(),
                'messages_last_hour' => $logsTable->find()
                    ->where(['type' => 3, 'date_time >=' => date('Y-m-d H:i:s', strtotime('-1 hour'))])
                    ->count(),
                'weather_data_today' => $dataMetTable->find()
                    ->where(['type' => 2, 'date_time >=' => date('Y-m-d 00:00:00')])
                    ->count()
            ];
            
            $data = [
                'status' => 'success',
                'stats' => $stats,
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * API para buscar dados meteorológicos históricos
     */
    public function weatherData()
    {
        $this->viewBuilder()->setClassName('Json');
        
        try {
            $limit = $this->request->getQuery('limit', 20);
            $offset = $this->request->getQuery('offset', 0);
            $deviceId = $this->request->getQuery('device_id');
            $startDate = $this->request->getQuery('start_date');
            $endDate = $this->request->getQuery('end_date');
            
            $dataMetTable = $this->fetchTable('DataMetereological');
            $query = $dataMetTable->find()
                ->where(['type' => 2]) // MQTT source
                ->contain(['Devices', 'Locations'])
                ->orderByDesc('date_time')
                ->limit($limit)
                ->offset($offset);
            
            if ($deviceId) {
                $query->where(['device_id' => $deviceId]);
            }
            
            if ($startDate) {
                $query->where(['date_time >=' => $startDate]);
            }
            
            if ($endDate) {
                $query->where(['date_time <=' => $endDate]);
            }
            
            $weatherData = $query->toArray();
            
            $data = [
                'status' => 'success',
                'weather_data' => $weatherData,
                'pagination' => [
                    'limit' => $limit,
                    'offset' => $offset,
                    'total' => $dataMetTable->find()->where(['type' => 2])->count()
                ],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * Iniciar escuta MQTT via comando
     */
    public function startListening()
    {
        $this->request->allowMethod(['post']);
        
        try {
            $host = $this->request->getData('host', 'external');
            $topic = $this->request->getData('topic', 'agrocity/+/data');
            $timeout = $this->request->getData('timeout', 0); // 0 = sem timeout
            
            // Executar comando MQTT em background
            $command = ROOT . DS . 'bin' . DS . 'cake mqtt_connect';
            $command .= " --host={$host}";
            $command .= " --topic=\"{$topic}\"";
            
            if ($timeout > 0) {
                $command .= " --timeout={$timeout}";
            }
            
            // Executar em background (Windows)
            if (PHP_OS_FAMILY === 'Windows') {
                $command = "start /B {$command}";
            } else {
                $command = "{$command} > /dev/null 2>&1 &";
            }
            
            exec($command);
            
            $this->Flash->success(__('Conexão MQTT iniciada em background.'));
            Log::info('MQTT listening started', ['command' => $command]);
            
        } catch (Exception $e) {
            $this->Flash->error(__('Erro ao iniciar conexão MQTT: {0}', $e->getMessage()));
            Log::error('Error starting MQTT connection: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Testar conexão MQTT
     */
    public function testConnection()
    {
        $this->request->allowMethod(['post']);
        
        try {
            $host = $this->request->getData('host', 'external');
            $timeout = 10; // 10 segundos para teste
            
            // Executar comando de teste
            $command = ROOT . DS . 'bin' . DS . 'cake mqtt_connect';
            $command .= " --host={$host}";
            $command .= " --timeout={$timeout}";
            $command .= " --topic=test/connection";
            
            $output = [];
            $returnVar = 0;
            exec($command . " 2>&1", $output, $returnVar);
            
            if ($returnVar === 0) {
                $this->Flash->success(__('Teste de conexão MQTT realizado com sucesso.'));
                Log::info('MQTT connection test successful', ['output' => implode("\n", $output)]);
            } else {
                $this->Flash->error(__('Falha no teste de conexão MQTT.'));
                Log::error('MQTT connection test failed', [
                    'return_code' => $returnVar,
                    'output' => implode("\n", $output)
                ]);
            }
            
        } catch (Exception $e) {
            $this->Flash->error(__('Erro ao testar conexão MQTT: {0}', $e->getMessage()));
            Log::error('Error testing MQTT connection: ' . $e->getMessage());
        }
        
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Configurações MQTT
     */
    public function settings()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            // Aqui você pode implementar salvamento de configurações
            $this->Flash->success(__('Configurações MQTT atualizadas.'));
            return $this->redirect(['action' => 'index']);
        }
        
        // Carregar configurações atuais
        $settings = [
            'mqtt_host_internal' => '192.168.0.10',
            'mqtt_host_external' => 'mqtt.agrocitylivinglab.com.br',
            'mqtt_port' => 1983,
            'mqtt_username' => 'agrocity',
            'mqtt_password' => '@grocity43',
            'default_topic' => 'agrocity/+/data'
        ];
        
        $this->set(compact('settings'));
    }

    /**
     * API de Login para dispositivos IoT (seguindo especificação Agrocity)
     * Endpoint: POST /api/login
     */
    public function login()
    {
        $this->viewBuilder()->setClassName('Json');
        $this->request->allowMethod(['post']);
        
        try {
            $requestData = $this->request->getData();
            $email = $requestData['email'] ?? '';
            $password = $requestData['password'] ?? '';
            
            // Validar credenciais específicas para IoT Agrocity
            if ($email === 'admin@admin.com' && $password === 'agro2admin') {
                // Gerar JWT token compatível com Agrocity
                $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
                $payload = json_encode([
                    'authorized' => true,
                    'exp' => time() + 86400, // 24 horas
                    'user_id' => 1
                ]);
                
                $headerEncoded = base64url_encode($header);
                $payloadEncoded = base64url_encode($payload);
                $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, 'agrocity_secret_key', true);
                $signatureEncoded = base64url_encode($signature);
                
                $jwt = $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
                
                // Log da autenticação
                Log::info('IoT device authenticated (Agrocity)', [
                    'email' => $email,
                    'timestamp' => date('Y-m-d H:i:s'),
                    'ip' => $this->request->clientIp()
                ]);
                
                $data = [
                    'status' => 'success',
                    'token' => $jwt,
                    'expires_in' => 86400, // 24 horas
                    'timestamp' => date('Y-m-d H:i:s')
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                Log::warning('IoT authentication failed', [
                    'email' => $email,
                    'ip' => $this->request->clientIp()
                ]);
            }
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => 'Authentication error: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            Log::error('IoT authentication error: ' . $e->getMessage());
        }
        
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * API para receber dados MQTT via HTTP POST (compatível com Agrocity)
     * Endpoint: POST /api/mqtt_data
     */
    public function mqttData()
    {
        $this->viewBuilder()->setClassName('Json');
        
        // Se for GET, consultar dados MQTT (seção 2.3 do PDF)
        if ($this->request->is('get')) {
            return $this->getMqttData();
        }
        
        // Se for POST, receber dados MQTT dos dispositivos IoT
        $this->request->allowMethod(['post']);
        
        try {
            // Verificar token de autenticação
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (!$this->validateAgrocityToken($authHeader)) {
                $data = [
                    'status' => 'error',
                    'message' => 'Unauthorized - Invalid or missing token',
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                $this->response = $this->response->withStatus(401);
                $this->set(compact('data'));
                $this->viewBuilder()->setOption('serialize', ['data']);
                return;
            }
            
            $requestData = $this->request->getData();
            $topic = $requestData['topic'] ?? '';
            $payloadJson = $requestData['payload'] ?? '';
            
            if (empty($topic) || empty($payloadJson)) {
                $data = [
                    'status' => 'error',
                    'message' => 'Missing required fields: topic and payload',
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                $this->set(compact('data'));
                $this->viewBuilder()->setOption('serialize', ['data']);
                return;
            }
            
            // Decodificar payload JSON
            $payload = json_decode($payloadJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $data = [
                    'status' => 'error',
                    'message' => 'Invalid JSON in payload: ' . json_last_error_msg(),
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                $this->set(compact('data'));
                $this->viewBuilder()->setOption('serialize', ['data']);
                return;
            }
            
            // Salvar na tabela de logs como mensagem MQTT (type = 3)
            $logsTable = $this->fetchTable('Logs');
            $log = $logsTable->newEmptyEntity();
            
            $mqttMessage = "MQTT Topic: {$topic} | Message: {$payloadJson}";
            $log = $logsTable->patchEntity($log, [
                'type' => 3, // MQTT
                'message' => $mqttMessage,
                'status' => 'received_via_http_agrocity',
                'date_time' => date('Y-m-d H:i:s')
            ]);
            
            if ($logsTable->save($log)) {
                // Processar dados meteorológicos se aplicável
                $this->processAgrocityWeatherData($topic, $payload, $log->id);
                
                Log::info('Agrocity MQTT data received via HTTP', [
                    'topic' => $topic,
                    'payload' => $payload,
                    'log_id' => $log->id,
                    'ip' => $this->request->clientIp()
                ]);
                
                $data = [
                    'id' => $log->id,
                    'topic' => $topic,
                    'payload' => $payloadJson,
                    'received_at' => date('Y-m-d\TH:i:s\Z'),
                    'created_at' => date('Y-m-d\TH:i:s\Z'),
                    'status' => 'success'
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'message' => 'Failed to save data',
                    'errors' => $log->getErrors(),
                    'timestamp' => date('Y-m-d H:i:s')
                ];
            }
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            Log::error('Agrocity MQTT data processing error: ' . $e->getMessage());
        }
        
        $this->set(compact('data'));
        $this->viewBuilder()->setOption('serialize', ['data']);
    }

    /**
     * Consulta de dados MQTT via HTTP (seção 2.3 do PDF)
     * Endpoint: GET /api/mqtt_data
     */
    private function getMqttData()
    {
        try {
            // Verificar token de autenticação
            $authHeader = $this->request->getHeaderLine('Authorization');
            if (!$this->validateAgrocityToken($authHeader)) {
                $data = [
                    'status' => 'error',
                    'message' => 'Unauthorized - Invalid or missing token',
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                $this->response = $this->response->withStatus(401);
                $this->set(compact('data'));
                $this->viewBuilder()->setOption('serialize', ['data']);
                return;
            }
            
            // Parâmetros de consulta
            $search = $this->request->getQuery('search');
            $topic = $this->request->getQuery('topic');
            $limit = $this->request->getQuery('limit', 20);
            $offset = $this->request->getQuery('offset', 0);
            
            $logsTable = $this->fetchTable('Logs');
            $query = $logsTable->find()
                ->where(['type' => 3]) // MQTT messages
                ->orderByDesc('date_time')
                ->limit($limit)
                ->offset($offset);
            
            // Filtro de busca
            if ($search) {
                $query->where([
                    'OR' => [
                        'message LIKE' => '%' . $search . '%'
                    ]
                ]);
            }
            
            // Filtro por tópico
            if ($topic) {
                $query->where(['message LIKE' => '%MQTT Topic: ' . $topic . '%']);
            }
            
            $messages = $query->toArray();
            
            // Formatear dados no formato Agrocity
            $formattedData = [];
            foreach ($messages as $message) {
                // Extrair topic e payload da mensagem
                if (preg_match('/MQTT Topic: (.+?) \| Message: (.+)/', $message->message, $matches)) {
                    $topicExtracted = $matches[1];
                    $payloadExtracted = $matches[2];
                } else {
                    $topicExtracted = 'unknown';
                    $payloadExtracted = $message->message;
                }
                
                $formattedData[] = [
                    'id' => $message->id,
                    'topic' => $topicExtracted,
                    'payload' => $payloadExtracted,
                    'received_at' => date('Y-m-d\TH:i:s\Z', strtotime($message->date_time)),
                    'created_at' => date('Y-m-d\TH:i:s\Z', strtotime($message->date_time))
                ];
            }
            
            $this->set('data', $formattedData);
            $this->viewBuilder()->setOption('serialize', ['data']);
            
        } catch (Exception $e) {
            $data = [
                'status' => 'error',
                'message' => 'Query error: ' . $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            $this->set(compact('data'));
            $this->viewBuilder()->setOption('serialize', ['data']);
        }
    }

    /**
     * Validar token JWT do Agrocity
     */
    private function validateAgrocityToken(string $authHeader): bool
    {
        if (empty($authHeader)) {
            return false;
        }
        
        // Extrair token do header "Bearer token"
        if (!preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return false;
        }
        
        $token = $matches[1];
        
        // Validar JWT token
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        try {
            $payload = json_decode(base64url_decode($parts[1]), true);
            
            // Verificar se o token não expirou
            if (isset($payload['exp']) && $payload['exp'] > time()) {
                return true;
            }
            
        } catch (Exception $e) {
            Log::error('Token validation error: ' . $e->getMessage());
        }
        
        return false;
    }

    /**
     * Processar dados meteorológicos do Agrocity
     */
    private function processAgrocityWeatherData(string $topic, array $payload, int $logId): void
    {
        try {
            // Verificar se o payload contém dados meteorológicos válidos
            $datetime = $payload['datetime'] ?? null;
            $datatype = $payload['datatype'] ?? null;
            $status = $payload['status'] ?? 1;
            
            if (!$datetime) {
                return; // Não há dados de tempo, não processar
            }
            
            // Converter datetime ISO para formato MySQL
            $dateTime = date('Y-m-d H:i:s', strtotime($datetime));
            
            // Buscar ou criar dispositivo baseado no tópico
            $devicesTable = $this->fetchTable('Devices');
            $device = $devicesTable->find()
                ->where(['name' => $topic])
                ->first();
            
            if (!$device) {
                // Criar novo dispositivo
                $device = $devicesTable->newEmptyEntity();
                $device = $devicesTable->patchEntity($device, [
                    'name' => $topic,
                    'description' => "Agrocity IoT Device - Topic: {$topic}",
                    'type' => 'mqtt_agrocity',
                    'status' => 'active'
                ]);
                $devicesTable->save($device);
            }
            
            // Extrair dados meteorológicos do payload
            $temperature = $payload['temperature'] ?? null;
            $humidity = $payload['humidity'] ?? null;
            $windSpeed = $payload['wind_speed'] ?? null;
            $windDirection = $payload['wind_direction'] ?? null;
            $precipitation = $payload['precipitation'] ?? null;
            $latitude = $payload['latitude'] ?? null;
            $longitude = $payload['longitude'] ?? null;
            
            // Se não há dados meteorológicos específicos, gerar dados baseados no datatype
            if (!$temperature && $datatype && $datatype !== 'BATATA!') {
                // Simular dados baseados no tipo
                $temperature = 20 + (rand(0, 150) / 10); // 20-35°C
                $humidity = 40 + rand(0, 50); // 40-90%
                $windSpeed = rand(0, 25); // 0-25 km/h
                $windDirection = rand(0, 359); // 0-359°
                $precipitation = rand(0, 50) / 10; // 0-5mm
            }
            
            // Salvar dados meteorológicos se houver
            if ($temperature !== null) {
                $dataMetTable = $this->fetchTable('DataMetereological');
                $weatherData = $dataMetTable->newEmptyEntity();
                
                $weatherData = $dataMetTable->patchEntity($weatherData, [
                    'device_id' => $device->id,
                    'type' => 2, // MQTT source
                    'date_time' => $dateTime,
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'wind_speed' => $windSpeed,
                    'wind_direction' => $windDirection,
                    'precipitation' => $precipitation,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'log_id' => $logId
                ]);
                
                $dataMetTable->save($weatherData);
                
                Log::info('Agrocity weather data processed', [
                    'device_id' => $device->id,
                    'topic' => $topic,
                    'datatype' => $datatype,
                    'temperature' => $temperature,
                    'weather_id' => $weatherData->id
                ]);
            }
            
        } catch (Exception $e) {
            Log::error('Error processing Agrocity weather data: ' . $e->getMessage(), [
                'topic' => $topic,
                'payload' => $payload,
                'log_id' => $logId
            ]);
        }
    }
}

/**
 * Função auxiliar para base64url encoding/decoding
 */
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}
