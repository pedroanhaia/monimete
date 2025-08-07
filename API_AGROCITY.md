# API Agrocity Living Lab - Integração MQTT

Este documento descreve como usar as APIs implementadas no sistema MoniMete para integração com o servidor MQTT da Agrocity Living Lab.

## 📡 Configurações do Servidor MQTT

- **Endereço**: `mqtt.agrocitylivinglab.com.br`
- **Porta**: `1983`
- **Usuário**: `agrocity`
- **Senha**: `@grocity43`

## 🔐 Autenticação

### 1. Login para obter token JWT

**Endpoint**: `POST /api/login`

**Credenciais**:
```json
{
  "email": "admin@admin.com",
  "password": "agro2admin"
}
```

**Exemplo com curl**:
```bash
curl -X POST http://localhost/monimete/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@admin.com","password":"agro2admin"}'
```

**Resposta**:
```json
{
  "status": "success",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expires_in": 86400,
  "timestamp": "2025-08-05 10:30:00"
}
```

## 📨 Envio de Dados MQTT via HTTP

### 2. Enviar dados MQTT

**Endpoint**: `POST /api/mqtt_data`

**Headers**:
- `Content-Type: application/json`
- `Authorization: Bearer {token}`

**Formato dos dados**:
```json
{
  "topic": "agrocity/device001/data",
  "payload": "{\"datetime\":\"2025-08-05T10:30:00\",\"status\":1,\"datatype\":\"weather\",\"temperature\":25.5,\"humidity\":65.2}"
}
```

**Exemplo com curl**:
```bash
curl -X POST http://localhost/monimete/api/mqtt_data \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -d '{
    "topic": "agrocity/weather01",
    "payload": "{\"datetime\":\"2025-08-05T10:30:00\",\"status\":1,\"datatype\":\"temperature\",\"temperature\":25.5,\"humidity\":65.2}"
  }'
```

**Resposta**:
```json
{
  "id": 123,
  "topic": "agrocity/weather01",
  "payload": "{\"datetime\":\"2025-08-05T10:30:00\",\"status\":1,\"datatype\":\"temperature\",\"temperature\":25.5,\"humidity\":65.2}",
  "received_at": "2025-08-05T10:30:00Z",
  "created_at": "2025-08-05T10:30:00Z",
  "status": "success"
}
```

## 📋 Consulta de Dados MQTT

### 3. Consultar dados MQTT armazenados

**Endpoint**: `GET /api/mqtt_data`

**Parâmetros de consulta (opcionais)**:
- `search` (string): Buscar por conteúdo no topic ou payload
- `topic` (string): Filtrar por tópico específico
- `limit` (integer): Limitar número de resultados (padrão: 20)
- `offset` (integer): Deslocar início dos resultados (paginação)

**Exemplo com curl**:
```bash
curl -X GET "http://localhost/monimete/api/mqtt_data?topic=agrocity/weather01&limit=10" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
```

**Resposta**:
```json
{
  "data": [
    {
      "id": 123,
      "topic": "agrocity/weather01",
      "payload": "{\"datetime\":\"2025-08-05T10:30:00\",\"temperature\":25.5}",
      "received_at": "2025-08-05T10:30:00Z",
      "created_at": "2025-08-05T10:30:00Z"
    }
  ]
}
```

## 🌡️ Dados Meteorológicos

O sistema processa automaticamente dados meteorológicos dos seguintes campos no payload:

- `temperature` - Temperatura em °C
- `humidity` - Umidade relativa em %
- `wind_speed` - Velocidade do vento em km/h
- `wind_direction` - Direção do vento em graus (0-359)
- `precipitation` - Precipitação em mm
- `latitude` - Latitude GPS
- `longitude` - Longitude GPS
- `datetime` - Data/hora no formato ISO 8601

## 🔧 Código Arduino Compatível

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* serverUrl = "http://localhost/monimete/api/mqtt_data";
const char* tokenUrl = "http://localhost/monimete/api/login";

// Função para obter token
String getToken() {
  HTTPClient http;
  http.begin(tokenUrl);
  http.addHeader("Content-Type", "application/json");
  
  String body = "{\"email\":\"admin@admin.com\",\"password\":\"agro2admin\"}";
  int httpCode = http.POST(body);
  
  if (httpCode == 200) {
    String payload = http.getString();
    StaticJsonDocument<1000> doc;
    deserializeJson(doc, payload);
    return doc["token"];
  }
  return "";
}

// Função para enviar dados
void sendMqttData(String topic, String payload) {
  HTTPClient http;
  http.begin(serverUrl);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Authorization", "Bearer " + token);
  
  StaticJsonDocument<1000> doc;
  doc["topic"] = topic;
  doc["payload"] = payload;
  
  String postData;
  serializeJson(doc, postData);
  
  int httpCode = http.POST(postData);
  Serial.println("Response: " + String(httpCode));
}
```

## 🚀 APIs Internas do Sistema

### Status da conexão MQTT
- `GET /api/mqtt/status` - Status atual da conexão

### Mensagens MQTT
- `GET /api/mqtt/messages` - Lista mensagens recentes

### Estatísticas
- `GET /api/mqtt/stats` - Estatísticas do sistema MQTT

### Dados meteorológicos
- `GET /api/mqtt/weather-data` - Dados meteorológicos processados

## 📝 Interface Web

Acesse `http://localhost/monimete/mqtt` para ver a interface web com:

- 📊 Estatísticas em tempo real
- 📨 Mensagens MQTT recentes
- 🌡️ Dados meteorológicos
- 🔴 Simulação ao vivo
- ⚙️ Controles de conexão

## 🛠️ Comandos de Terminal

```bash
# Conectar ao servidor MQTT externo
bin/cake mqtt_connect --host=external --topic="agrocity/+/data" --verbose

# Conectar ao servidor interno
bin/cake mqtt_connect --host=internal --topic="agrocity/device1/data" --timeout=30

# Ver ajuda
bin/cake mqtt_connect --help
```

## 🔍 Logs

Todos os dados MQTT são armazenados na tabela `logs` com `type = 3` e processados automaticamente para dados meteorológicos na tabela `data_metereological` com `type = 2`.
