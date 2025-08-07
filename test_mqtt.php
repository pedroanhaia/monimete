<?php
/**
 * Script simples para testar conexão MQTT
 * Execute: php test_mqtt.php
 */

// Configurações MQTT
$mqttHost = 'mqtt.agrocitylivinglab.com.br';
$mqttPort = 1983;
$mqttUsername = 'agrocity';
$mqttPassword = '@grocity43';
$mqttTopic = 'agrocity/test/connection';

echo "🌐 Teste de Conexão MQTT - MoniMete\n";
echo "=====================================\n\n";

echo "📡 Servidor: {$mqttHost}:{$mqttPort}\n";
echo "👤 Usuário: {$mqttUsername}\n";
echo "🔑 Senha: " . str_repeat('*', strlen($mqttPassword)) . "\n";
echo "📂 Tópico: {$mqttTopic}\n\n";

// Teste 1: Verificar se a biblioteca php-mqtt/client está disponível
echo "🔍 Verificando biblioteca php-mqtt/client...\n";
if (class_exists('\PhpMqtt\Client\MqttClient')) {
    echo "✅ Biblioteca php-mqtt/client encontrada!\n\n";
    
    try {
        echo "🔌 Tentando conectar ao broker MQTT...\n";
        
        $client = new \PhpMqtt\Client\MqttClient($mqttHost, $mqttPort, 'monimete_test_' . uniqid());
        
        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setUsername($mqttUsername)
            ->setPassword($mqttPassword)
            ->setConnectTimeout(10)
            ->setUseTls(false);
        
        $client->connect($connectionSettings, true);
        echo "✅ Conectado com sucesso!\n";
        
        // Publicar uma mensagem de teste
        $testMessage = json_encode([
            'timestamp' => date('Y-m-d H:i:s'),
            'message' => 'Teste de conexão MoniMete',
            'temperature' => 22.5,
            'humidity' => 65.0
        ]);
        
        echo "📤 Publicando mensagem de teste...\n";
        $client->publish($mqttTopic, $testMessage, 0);
        echo "✅ Mensagem publicada com sucesso!\n";
        
        $client->disconnect();
        echo "🔌 Desconectado do broker\n\n";
        
        echo "🎉 Teste CONCLUÍDO COM SUCESSO!\n";
        
    } catch (Exception $e) {
        echo "❌ Erro na conexão: " . $e->getMessage() . "\n\n";
        echo "💡 Possíveis soluções:\n";
        echo "   - Verifique se o servidor MQTT está rodando\n";
        echo "   - Verifique as credenciais\n";
        echo "   - Verifique a conectividade de rede\n";
    }
    
} else {
    echo "❌ Biblioteca php-mqtt/client não encontrada!\n\n";
    
    // Teste 2: Verificar se mosquitto_sub está disponível
    echo "🔍 Verificando mosquitto_sub como alternativa...\n";
    $output = [];
    $returnVar = 0;
    exec('mosquitto_sub --help 2>&1', $output, $returnVar);
    
    if ($returnVar === 0) {
        echo "✅ mosquitto_sub encontrado!\n";
        echo "💡 Você pode usar: mosquitto_sub -h {$mqttHost} -p {$mqttPort} -u {$mqttUsername} -P {$mqttPassword} -t \"{$mqttTopic}\"\n";
    } else {
        echo "❌ mosquitto_sub não encontrado!\n\n";
        echo "💡 Para instalar a biblioteca PHP MQTT:\n";
        echo "   composer require php-mqtt/client\n\n";
        echo "💡 Para instalar mosquitto (Windows):\n";
        echo "   Baixe de: https://mosquitto.org/download/\n\n";
        echo "💡 Para instalar mosquitto (Ubuntu/Debian):\n";
        echo "   sudo apt-get install mosquitto-clients\n";
    }
}

echo "\n🔚 Fim do teste\n";
?>
