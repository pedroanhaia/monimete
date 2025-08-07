<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Exception;

/**
 * MqttConnect command.
 * 
 * Conecta ao servidor MQTT Agrocity Living Lab para buscar mensagens dos dispositivos IoT
 */
class MqttConnectCommandFixed extends Command
{
    /**
     * The name of this command.
     *
     * @var string
     */
    protected string $name = 'mqtt_connect';

    // Configurações do servidor MQTT Agrocity
    private const MQTT_HOST_INTERNAL = '192.168.0.10';
    private const MQTT_HOST_EXTERNAL = 'mqtt.agrocitylivinglab.com.br';
    private const MQTT_PORT = 1983;
    private const MQTT_USERNAME = 'agrocity';
    private const MQTT_PASSWORD = '@grocity43';

    /**
     * Get the default command name.
     *
     * @return string
     */
    public static function defaultName(): string
    {
        return 'mqtt_connect';
    }

    /**
     * Get the command description.
     *
     * @return string
     */
    public static function getDescription(): string
    {
        return 'Conecta ao servidor MQTT Agrocity Living Lab para buscar mensagens dos dispositivos IoT';
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return parent::buildOptionParser($parser)
            ->setDescription(static::getDescription())
            ->addOption('host', [
                'short' => 'h',
                'help' => 'Endereço do servidor MQTT (interno ou externo)',
                'default' => 'external',
                'choices' => ['internal', 'external']
            ])
            ->addOption('topic', [
                'short' => 't',
                'help' => 'Tópico MQTT para escutar (use # para todos os tópicos)',
                'default' => 'agrocity/+/data'
            ])
            ->addOption('timeout', [
                'help' => 'Timeout em segundos para a conexão (0 = infinito)',
                'default' => 30
            ])
            ->addOption('verbose', [
                'short' => 'v',
                'help' => 'Mostrar mensagens detalhadas',
                'boolean' => true
            ])
            ->addOption('wildcard', [
                'short' => 'w',
                'help' => 'Usar wildcard # para assinar todos os tópicos',
                'boolean' => true
            ]);
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
        $io->out('<info>🌐 Conectando ao servidor MQTT Agrocity Living Lab...</info>');
        
        // Determinar host
        $hostOption = $args->getOption('host');
        $host = ($hostOption === 'internal') ? self::MQTT_HOST_INTERNAL : self::MQTT_HOST_EXTERNAL;
        
        $topic = $args->getOption('topic');
        $timeout = (int)$args->getOption('timeout');
        $verbose = $args->getOption('verbose');
        $wildcard = $args->getOption('wildcard');
        
        // Se wildcard foi solicitado, usar #
        if ($wildcard) {
            $topic = '#';
            $io->out('<info>🔍 Usando wildcard # para assinar TODOS os tópicos</info>');
        }
        
        $io->out("📡 Servidor: {$host}:" . self::MQTT_PORT);
        $io->out("👤 Usuário: " . self::MQTT_USERNAME);
        $io->out("📂 Tópico: {$topic}");
        $io->out("⏱️ Timeout: " . ($timeout === 0 ? 'infinito' : $timeout . 's'));
        $io->out('');
        
        try {
            // Tentar mosquitto_sub primeiro (método recomendado)
            if ($this->isMosquittoAvailable()) {
                return $this->connectWithMosquittoSub($host, $topic, $timeout, $verbose, $io);
            }
            
            // Tentar conexão com biblioteca php-mqtt/client
            if (class_exists('\PhpMqtt\Client\MqttClient')) {
                return $this->connectWithPhpMqttClient($host, $topic, $timeout, $verbose, $io);
            }
            
            // Modo simulação para demonstração
            $io->out('<info>⚠️ Nenhuma biblioteca MQTT encontrada. Iniciando modo simulação...</info>');
            return $this->runSimulationMode($host, $topic, $timeout, $verbose, $io);
            
        } catch (Exception $e) {
            $io->error("❌ Erro na conexão MQTT: " . $e->getMessage());
            Log::error('MQTT Connection Error: ' . $e->getMessage());
            return Command::CODE_ERROR;
        }
    }

    /**
     * Conecta usando mosquitto_sub (método recomendado pela Agrocity)
     */
    private function connectWithMosquittoSub(string $host, string $topic, int $timeout, bool $verbose, ConsoleIo $io): int
    {
        $io->out('<success>✅ Usando mosquitto_sub (especificação Agrocity Living Lab)</success>');
        
        // Comando mosquitto_sub seguindo formato do PDF
        // mosquitto_sub -h mqtt.agrocitylivinglab.com.br -p 1983 -u agrocity -t "topico" -P "@grocity43"
        $command = sprintf(
            'mosquitto_sub -h %s -p %d -u %s -t %s -P %s',
            escapeshellarg($host),
            self::MQTT_PORT,
            escapeshellarg(self::MQTT_USERNAME),
            escapeshellarg($topic),
            escapeshellarg(self::MQTT_PASSWORD)
        );
        
        // Adicionar timeout se especificado (diferente de 0)
        if ($timeout > 0) {
            $command .= " -W {$timeout}";
        }
        
        // Adicionar verbose se solicitado
        if ($verbose) {
            $command .= " -v";
        }
        
        $io->out("🔧 Comando: {$command}");
        $io->out("👂 Escutando mensagens MQTT...");
        
        if ($topic === '#') {
            $io->out('<warning>🔍 ATENÇÃO: Escutando TODOS os tópicos (wildcard #)</warning>');
        }
        
        $io->out('<info>💡 Pressione Ctrl+C para parar</info>');
        $io->out('');
        
        // Executar comando e processar output
        $messagesReceived = 0;
        $startTime = time();
        
        $handle = popen($command, 'r');
        if (!$handle) {
            $io->error("❌ Falha ao executar mosquitto_sub");
            return Command::CODE_ERROR;
        }
        
        try {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $messagesReceived++;
                $currentTime = date('Y-m-d H:i:s');
                
                // Se verbose estiver ativo, mosquitto_sub retorna: "topico payload"
                // Se não, retorna apenas: "payload"
                if ($verbose && strpos($line, ' ') !== false) {
                    list($receivedTopic, $payload) = explode(' ', $line, 2);
                    $io->out("📨 [{$currentTime}] Tópico: <info>{$receivedTopic}</info>");
                    $io->out("   📄 Payload: {$payload}");
                } else {
                    $io->out("📨 [{$currentTime}] Mensagem: {$line}");
                    $receivedTopic = $topic;
                    $payload = $line;
                }
                
                // Processar e salvar mensagem
                try {
                    $this->processMessage($receivedTopic, $payload, $io);
                } catch (Exception $e) {
                    $io->error("⚠️ Erro ao processar mensagem: " . $e->getMessage());
                }
                
                // Verificar timeout
                if ($timeout > 0 && (time() - $startTime) >= $timeout) {
                    $io->out("<info>⏱️ Timeout atingido ({$timeout}s)</info>");
                    break;
                }
            }
        } finally {
            pclose($handle);
        }
        
        $io->out('');
        $io->out("<success>📊 Conexão encerrada. Total de mensagens recebidas: {$messagesReceived}</success>");
        
        return Command::CODE_SUCCESS;
    }

    /**
     * Conecta usando a biblioteca php-mqtt/client
     */
    private function connectWithPhpMqttClient(string $host, string $topic, int $timeout, bool $verbose, ConsoleIo $io): int
    {
        $io->out('<info>🔄 Usando biblioteca php-mqtt/client...</info>');
        
        try {
            // Criar cliente MQTT
            $client = new \PhpMqtt\Client\MqttClient($host, self::MQTT_PORT, 'monimete_agrocity_' . uniqid());
            
            // Configurações de conexão
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
                ->setUsername(self::MQTT_USERNAME)
                ->setPassword(self::MQTT_PASSWORD)
                ->setKeepAliveInterval(60)
                ->setConnectTimeout(10);
            
            // Conectar
            $client->connect($connectionSettings, true);
            $io->out('<success>✅ Conectado ao servidor MQTT!</success>');
            
            $messagesReceived = 0;
            $startTime = time();
            
            // Assinar tópico
            $client->subscribe($topic, function ($topic, $message) use (&$messagesReceived, $verbose, $io) {
                $messagesReceived++;
                $currentTime = date('Y-m-d H:i:s');
                
                $io->out("📨 [{$currentTime}] Tópico: <info>{$topic}</info>");
                $io->out("   📄 Payload: {$message}");
                
                // Processar mensagem
                try {
                    $this->processMessage($topic, $message, $io);
                } catch (Exception $e) {
                    $io->error("⚠️ Erro ao processar mensagem: " . $e->getMessage());
                }
            }, 0);
            
            $io->out("👂 Escutando tópico: {$topic}");
            $io->out('<info>💡 Pressione Ctrl+C para parar</info>');
            
            // Loop de escuta
            if ($timeout > 0) {
                while ($client->loop(true) && (time() - $startTime) < $timeout) {
                    // Continue até timeout
                }
                $io->out("<info>⏱️ Timeout atingido ({$timeout}s)</info>");
            } else {
                $client->loop(true); // Loop infinito
            }
            
            $client->disconnect();
            
        } catch (Exception $e) {
            $io->error("❌ Erro com php-mqtt/client: " . $e->getMessage());
            throw $e;
        }
        
        $io->out("<success>📊 Total de mensagens processadas: {$messagesReceived}</success>");
        return Command::CODE_SUCCESS;
    }

    /**
     * Modo simulação para demonstração
     */
    private function runSimulationMode(string $host, string $topic, int $timeout, bool $verbose, ConsoleIo $io): int
    {
        $io->out('<warning>⚠️ MODO SIMULAÇÃO - Gerando dados fictícios para demonstração</warning>');
        $io->out("📡 Simulando conexão com: {$host}:" . self::MQTT_PORT);
        $io->out("📂 Simulando tópico: {$topic}");
        $io->out('');
        
        $messagesReceived = 0;
        $startTime = time();
        $maxMessages = 10; // Limitar para demonstração
        
        while ($messagesReceived < $maxMessages) {
            $messagesReceived++;
            $currentTime = date('Y-m-d H:i:s');
            
            // Gerar dados simulados
            $simulatedTopic = str_replace('+', 'device' . rand(1, 5), $topic);
            $simulatedData = [
                'datetime' => date('Y-m-d\TH:i:s'),
                'status' => 1,
                'datatype' => 'weather_simulation',
                'temperature' => round(20 + (rand(0, 150) / 10), 1),
                'humidity' => rand(40, 90),
                'wind_speed' => round(rand(0, 250) / 10, 1),
                'device_id' => 'sim_device_' . rand(1, 5)
            ];
            
            $payload = json_encode($simulatedData);
            
            $io->out("📨 [{$currentTime}] Tópico: <info>{$simulatedTopic}</info>");
            $io->out("   📄 Payload: {$payload}");
            
            // Processar mensagem simulada
            try {
                $this->processMessage($simulatedTopic, $payload, $io);
            } catch (Exception $e) {
                $io->error("⚠️ Erro ao processar mensagem simulada: " . $e->getMessage());
            }
            
            // Pausa entre mensagens
            sleep(2);
            
            // Verificar timeout
            if ($timeout > 0 && (time() - $startTime) >= $timeout) {
                $io->out("<info>⏱️ Timeout atingido ({$timeout}s)</info>");
                break;
            }
        }
        
        $io->out('');
        $io->out("<success>📊 Simulação encerrada. Total de mensagens geradas: {$messagesReceived}</success>");
        
        return Command::CODE_SUCCESS;
    }

    /**
     * Verifica se mosquitto está disponível
     */
    private function isMosquittoAvailable(): bool
    {
        $output = [];
        $returnVar = 0;
        exec('mosquitto_sub --help 2>nul', $output, $returnVar);
        return $returnVar === 0;
    }

    /**
     * Processa uma mensagem MQTT recebida
     */
    private function processMessage(string $topic, string $message, ConsoleIo $io): void
    {
        try {
            // Log da mensagem
            Log::info("MQTT Message Agrocity", [
                'topic' => $topic,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
            // Salvar log da mensagem
            $this->saveMessageLog($topic, $message, $io);
            
            // Tentar decodificar JSON e salvar dados meteorológicos
            $data = json_decode($message, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->saveMeterologicalData($topic, $data, $io);
            }
            
        } catch (Exception $e) {
            $io->error("   ❌ Erro ao processar mensagem: " . $e->getMessage());
            Log::error('Message processing error: ' . $e->getMessage());
        }
    }

    /**
     * Salva log da mensagem MQTT
     */
    private function saveMessageLog(string $topic, string $message, ConsoleIo $io): void
    {
        try {
            $logsTable = TableRegistry::getTableLocator()->get('Logs');
            
            $log = $logsTable->newEmptyEntity();
            $mqttMessage = "MQTT Topic: {$topic} | Message: {$message}";
            
            $log = $logsTable->patchEntity($log, [
                'type' => 3, // MQTT
                'message' => $mqttMessage,
                'status' => 'received_agrocity',
                'date_time' => date('Y-m-d H:i:s')
            ]);
            
            if ($logsTable->save($log)) {
                $io->out("   ✅ Log salvo (ID: {$log->id})");
            } else {
                $io->error("   ❌ Erro ao salvar log");
            }
            
        } catch (Exception $e) {
            $io->error("   ❌ Erro ao salvar log: " . $e->getMessage());
        }
    }

    /**
     * Salva dados meteorológicos no banco
     */
    private function saveMeterologicalData(string $topic, array $data, ConsoleIo $io): void
    {
        try {
            // Verificar se os dados contém informações meteorológicas
            $datetime = $data['datetime'] ?? null;
            $temperature = $data['temperature'] ?? null;
            $humidity = $data['humidity'] ?? null;
            
            if (!$datetime) {
                return; // Não há dados de tempo
            }
            
            // Buscar ou criar dispositivo
            $devicesTable = TableRegistry::getTableLocator()->get('Devices');
            $device = $devicesTable->find()
                ->where(['name' => $topic])
                ->first();
            
            if (!$device) {
                $device = $devicesTable->newEmptyEntity();
                $device = $devicesTable->patchEntity($device, [
                    'name' => $topic,
                    'description' => "Agrocity MQTT Device - {$topic}",
                    'type' => 'mqtt_agrocity',
                    'status' => 'active'
                ]);
                $devicesTable->save($device);
                $io->out("   📱 Novo dispositivo criado: {$topic}");
            }
            
            // Salvar dados meteorológicos
            if ($temperature !== null || $humidity !== null) {
                $dataMetTable = TableRegistry::getTableLocator()->get('DataMetereological');
                $weatherData = $dataMetTable->newEmptyEntity();
                
                $weatherData = $dataMetTable->patchEntity($weatherData, [
                    'device_id' => $device->id,
                    'type' => 2, // MQTT source
                    'date_time' => date('Y-m-d H:i:s', strtotime($datetime)),
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'wind_speed' => $data['wind_speed'] ?? null,
                    'wind_direction' => $data['wind_direction'] ?? null,
                    'precipitation' => $data['precipitation'] ?? null,
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null
                ]);
                
                if ($dataMetTable->save($weatherData)) {
                    $io->out("   🌡️ Dados meteorológicos salvos (ID: {$weatherData->id})");
                } else {
                    $io->error("   ❌ Erro ao salvar dados meteorológicos");
                }
            }
            
        } catch (Exception $e) {
            $io->error("   ❌ Erro ao salvar dados meteorológicos: " . $e->getMessage());
        }
    }
}
