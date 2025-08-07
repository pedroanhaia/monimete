<?php
/**
 * @var \App\View\AppView $this
 * @var bool $liveMode
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('MQTT Actions') ?></h4>
            <?= $this->Form->postLink(
                __('🔧 Testar Conexão'),
                ['action' => 'testConnection'],
                ['confirm' => __('Deseja testar a conexão MQTT?'), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('⚙️ Configurações'), ['action' => 'settings'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('📊 API Status'), ['action' => 'status'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('📨 API Messages'), ['action' => 'messages'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="mqtt index content">
            <h3><?= __('🌐 Monitoramento MQTT - Agrocity') ?> 
                <span id="connection-status" class="status-indicator">🔴 Carregando...</span>
            </h3>
            
            <!-- Indicador de Status de Conexão -->
            <div id="mqtt-status-bar" class="status-bar" style="padding: 0.5rem; margin-bottom: 1rem; border-radius: 4px; text-align: center; background: #f8f9fa; border: 1px solid #dee2e6;">
                <span id="status-text">🔄 Verificando status da conexão MQTT...</span>
            </div>
            
            <!-- Estatísticas Dinâmicas -->
            <div class="stats-grid" id="stats-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                <!-- Estatísticas serão carregadas via JavaScript -->
            </div>

            <!-- Controles de Conexão -->
            <div class="connection-controls" style="background: #f5f5f5; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                <h4>🔌 Controles de Conexão MQTT</h4>
                
                <?= $this->Form->create(null, ['url' => ['action' => 'startListening']]) ?>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                    <?= $this->Form->control('host', [
                        'type' => 'select',
                        'options' => [
                            'external' => '🌐 Externo (mqtt.agrocitylivinglab.com.br)',
                            'internal' => '🏠 Interno (192.168.0.10)'
                        ],
                        'default' => 'external',
                        'label' => 'Servidor'
                    ]) ?>
                    
                    <?= $this->Form->control('topic', [
                        'default' => 'agrocity/+/data',
                        'label' => 'Tópico MQTT'
                    ]) ?>
                    
                    <?= $this->Form->control('timeout', [
                        'type' => 'number',
                        'min' => 0,
                        'default' => 0,
                        'label' => 'Timeout (0 = infinito)'
                    ]) ?>
                    
                    <?= $this->Form->button('🚀 Iniciar Escuta', ['type' => 'submit', 'class' => 'button']) ?>
                </div>
                <?= $this->Form->end() ?>
                
                <div style="margin-top: 1rem;">
                    <p><strong>ℹ️ Informações de Conexão:</strong></p>
                    <ul>
                        <li><strong>Servidor Interno:</strong> 192.168.0.10:1983</li>
                        <li><strong>Servidor Externo:</strong> mqtt.agrocitylivinglab.com.br:1983</li>
                        <li><strong>Usuário:</strong> agrocity</li>
                        <li><strong>Senha:</strong> @grocity43</li>
                    </ul>
                    
                    <div style="background: #e8f5e9; padding: 1rem; border-radius: 4px; margin-top: 1rem; border-left: 4px solid #4caf50;">
                        <h5 style="margin: 0; color: #2e7d32;">🚀 API Agrocity Living Lab</h5>
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.9em;">
                            <strong>Dispositivos IoT podem enviar dados via HTTP POST:</strong><br>
                            • <strong>Login:</strong> POST /api/login<br>
                            • <strong>Envio:</strong> POST /api/mqtt_data<br>
                            • <strong>Consulta:</strong> GET /api/mqtt_data<br>
                            <small>Ver documentação completa em <code>API_AGROCITY.md</code></small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Abas para diferentes visualizações -->
            <div class="tab-container" style="margin-bottom: 2rem;">
                <div class="tab-buttons" style="display: flex; border-bottom: 2px solid #ddd;">
                    <button class="tab-button active" onclick="switchTab('messages')" style="padding: 10px 20px; border: none; background: #007bff; color: white; cursor: pointer;">
                        📨 Mensagens Recentes
                    </button>
                    <button class="tab-button" onclick="switchTab('weather')" style="padding: 10px 20px; border: none; background: #6c757d; color: white; cursor: pointer; margin-left: 2px;">
                        🌡️ Dados Meteorológicos
                    </button>
                    <button class="tab-button" onclick="switchTab('live')" style="padding: 10px 20px; border: none; background: #6c757d; color: white; cursor: pointer; margin-left: 2px;">
                        🔴 Dados ao Vivo
                    </button>
                </div>
                
                <!-- Conteúdo das Abas -->
                <div id="tab-messages" class="tab-content active" style="padding: 1rem; border: 1px solid #ddd; border-top: none;">
                    <h4>📨 Últimas Mensagens MQTT (Histórico)</h4>
                    <div id="messages-container">
                        <div class="loading">🔄 Carregando mensagens...</div>
                    </div>
                    <div class="pagination-controls" style="margin-top: 1rem; text-align: center;">
                        <button onclick="loadMessages(0)" class="button small">⏮️ Primeira</button>
                        <button onclick="loadMessages(currentOffset - 20)" class="button small" id="prev-btn">⬅️ Anterior</button>
                        <span id="page-info">Página 1</span>
                        <button onclick="loadMessages(currentOffset + 20)" class="button small" id="next-btn">➡️ Próxima</button>
                    </div>
                </div>
                
                <div id="tab-weather" class="tab-content" style="padding: 1rem; border: 1px solid #ddd; border-top: none; display: none;">
                    <h4>🌡️ Dados Meteorológicos (Histórico)</h4>
                    <div id="weather-container">
                        <div class="loading">🔄 Carregando dados meteorológicos...</div>
                    </div>
                </div>
                
                <div id="tab-live" class="tab-content" style="padding: 1rem; border: 1px solid #ddd; border-top: none; display: none;">
                    <h4>🔴 Monitoramento ao Vivo</h4>
                    <div id="live-container">
                        <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
                            <h5>📡 Simulação de Dados ao Vivo</h5>
                            <p>Esta seção simula dados MQTT em tempo real.</p>
                            <button onclick="startLiveSimulation()" id="live-btn" class="button">▶️ Iniciar Simulação</button>
                            <div id="live-data" style="margin-top: 1rem;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comandos para Terminal -->
            <div class="terminal-commands" style="background: #263238; color: #fff; padding: 1.5rem; border-radius: 8px; margin-top: 2rem;">
                <h4 style="color: #4caf50;">💻 Comandos de Terminal</h4>
                <p>Para executar a conexão MQTT via linha de comando:</p>
                
                <div style="background: #1e1e1e; padding: 1rem; border-radius: 4px; margin: 1rem 0;">
                    <code style="color: #4caf50;">
                        # Conectar ao servidor Agrocity Living Lab<br>
                        php -d xdebug.mode=off bin\cake.php mqtt_connect --host=external --topic="agrocity/+/data" --verbose<br><br>
                        
                        # Conectar ao servidor interno<br>
                        php -d xdebug.mode=off bin\cake.php mqtt_connect --host=internal --topic="agrocity/device1/data" --timeout=30<br><br>
                        
                        # Ver ajuda do comando<br>
                        php -d xdebug.mode=off bin\cake.php mqtt_connect --help<br><br>
                        
                        # Conectar com timeout específico<br>
                        php -d xdebug.mode=off bin\cake.php mqtt_connect --timeout=60 --verbose
                    </code>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variáveis globais
let currentOffset = 0;
let refreshInterval;
let liveSimulationInterval;
let isLiveMode = false;

// Inicializar quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    loadInitialData();
    
    // Atualizar status a cada 30 segundos
    refreshInterval = setInterval(updateMqttStatus, 30000);
});

// Carregar dados iniciais
function loadInitialData() {
    updateMqttStatus();
    loadStats();
    loadMessages(0);
}

// Atualizar status da conexão MQTT
async function updateMqttStatus() {
    try {
        const response = await fetch('/mqtt/status');
        const result = await response.json();
        
        const statusBar = document.getElementById('mqtt-status-bar');
        const statusIndicator = document.getElementById('connection-status');
        const statusText = document.getElementById('status-text');
        
        if (result.data.mqtt_connected) {
            statusBar.style.background = '#d4edda';
            statusBar.style.borderColor = '#c3e6cb';
            statusBar.style.color = '#155724';
            statusIndicator.textContent = '🟢 Online';
            statusText.textContent = `✅ Conectado - Última mensagem: ${result.data.last_message_time || 'N/A'}`;
        } else {
            statusBar.style.background = '#f8d7da';
            statusBar.style.borderColor = '#f5c6cb';
            statusBar.style.color = '#721c24';
            statusIndicator.textContent = '🔴 Offline';
            statusText.textContent = '❌ Desconectado - Nenhuma mensagem recente';
        }
        
    } catch (error) {
        console.error('Erro ao verificar status MQTT:', error);
        document.getElementById('status-text').textContent = '⚠️ Erro ao verificar status';
    }
}

// Carregar estatísticas
async function loadStats() {
    try {
        const response = await fetch('/mqtt/stats');
        const result = await response.json();
        
        if (result.status === 'success') {
            renderStats(result.stats);
        } else {
            console.error('Erro ao carregar estatísticas:', result.message);
        }
        
    } catch (error) {
        console.error('Erro ao carregar estatísticas:', error);
    }
}

// Renderizar estatísticas
function renderStats(stats) {
    const container = document.getElementById('stats-container');
    container.innerHTML = `
        <div class="stat-card" style="background: #e8f5e9; padding: 1rem; border-radius: 8px; border-left: 4px solid #4caf50;">
            <h4 style="margin: 0; color: #2e7d32;">📨 Total de Mensagens</h4>
            <p style="font-size: 1.5em; margin: 0; font-weight: bold;">${stats.total_mqtt_messages.toLocaleString()}</p>
            <small>Última hora: ${stats.messages_last_hour}</small>
        </div>
        
        <div class="stat-card" style="background: #e3f2fd; padding: 1rem; border-radius: 8px; border-left: 4px solid #2196f3;">
            <h4 style="margin: 0; color: #1565c0;">🌡️ Dados Meteorológicos</h4>
            <p style="font-size: 1.5em; margin: 0; font-weight: bold;">${stats.mqtt_weather_records.toLocaleString()}</p>
            <small>Hoje: ${stats.weather_data_today}</small>
        </div>
        
        <div class="stat-card" style="background: #fff3e0; padding: 1rem; border-radius: 8px; border-left: 4px solid #ff9800;">
            <h4 style="margin: 0; color: #ef6c00;">📡 Dispositivos Ativos</h4>
            <p style="font-size: 1.5em; margin: 0; font-weight: bold;">${stats.active_devices}</p>
            <small>Última hora</small>
        </div>
        
        <div class="stat-card" style="background: ${stats.last_message_time ? '#e8f5e9' : '#ffebee'}; padding: 1rem; border-radius: 8px; border-left: 4px solid ${stats.last_message_time ? '#4caf50' : '#f44336'};">
            <h4 style="margin: 0; color: ${stats.last_message_time ? '#2e7d32' : '#c62828'};">🕐 Última Mensagem</h4>
            <p style="font-size: 1.2em; margin: 0; font-weight: bold;">
                ${stats.last_message_time ? formatTimeAgo(stats.last_message_time) : 'Nenhuma'}
            </p>
        </div>
    `;
}

// Carregar mensagens
async function loadMessages(offset = 0) {
    try {
        const response = await fetch(`/mqtt/messages?limit=20&offset=${offset}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            renderMessages(result.messages);
            updatePagination(result.pagination);
            currentOffset = offset;
        } else {
            console.error('Erro ao carregar mensagens:', result.message);
        }
        
    } catch (error) {
        console.error('Erro ao carregar mensagens:', error);
    }
}

// Renderizar mensagens
function renderMessages(messages) {
    const container = document.getElementById('messages-container');
    
    if (messages.length === 0) {
        container.innerHTML = `
            <div class="empty-state" style="text-align: center; padding: 2rem; background: #f9f9f9; border-radius: 8px;">
                <p>📭 Nenhuma mensagem MQTT encontrada.</p>
                <p>Use os controles acima para iniciar a escuta de mensagens.</p>
            </div>
        `;
        return;
    }
    
    const messagesHtml = messages.map(message => `
        <div class="message-item" style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 1rem; margin-bottom: 0.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <strong>🕐 ${formatDateTime(message.date_time)}</strong>
                    <span class="status-badge" style="background: #4caf50; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; margin-left: 1rem;">
                        ${message.status}
                    </span>
                </div>
                <div style="color: #666; font-size: 0.9em;">ID: ${message.id}</div>
            </div>
            <div style="margin-top: 0.5rem; color: #555; word-break: break-word;">
                ${formatMqttMessage(message.message)}
            </div>
        </div>
    `).join('');
    
    container.innerHTML = messagesHtml;
}

// Carregar dados meteorológicos
async function loadWeatherData() {
    try {
        const response = await fetch('/mqtt/weather-data?limit=20');
        const result = await response.json();
        
        if (result.status === 'success') {
            renderWeatherData(result.weather_data);
        } else {
            console.error('Erro ao carregar dados meteorológicos:', result.message);
        }
        
    } catch (error) {
        console.error('Erro ao carregar dados meteorológicos:', error);
    }
}

// Renderizar dados meteorológicos
function renderWeatherData(weatherData) {
    const container = document.getElementById('weather-container');
    
    if (weatherData.length === 0) {
        container.innerHTML = `
            <div class="empty-state" style="text-align: center; padding: 2rem; background: #f9f9f9; border-radius: 8px;">
                <p>🌡️ Nenhum dado meteorológico encontrado.</p>
            </div>
        `;
        return;
    }
    
    const weatherHtml = weatherData.map(data => `
        <div class="weather-item" style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 1rem; margin-bottom: 0.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
                <div>
                    <strong>🕐 ${formatDateTime(data.date_time)}</strong>
                    <br><small>Dispositivo: ${data.device ? data.device.name : 'N/A'}</small>
                </div>
                <div>
                    <strong>🌡️ ${data.temperature ? data.temperature.toFixed(1) + '°C' : 'N/A'}</strong>
                    <br><small>💧 ${data.humidity ? data.humidity.toFixed(1) + '%' : 'N/A'}</small>
                </div>
                <div>
                    <strong>💨 ${data.wind_speed ? data.wind_speed.toFixed(1) + ' km/h' : 'N/A'}</strong>
                    <br><small>🧭 ${data.wind_direction ? data.wind_direction + '°' : 'N/A'}</small>
                </div>
                <div>
                    <strong>📍 ${data.latitude && data.longitude ? 
                        data.latitude.toFixed(4) + ', ' + data.longitude.toFixed(4) : 
                        (data.location ? data.location.name : 'N/A')}</strong>
                    <br><small>🌧️ ${data.precipitation ? data.precipitation.toFixed(1) + ' mm' : 'N/A'}</small>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = weatherHtml;
}

// Alternar entre abas
function switchTab(tabName) {
    // Atualizar botões
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
        btn.style.background = '#6c757d';
    });
    event.target.classList.add('active');
    event.target.style.background = '#007bff';
    
    // Atualizar conteúdo
    document.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
    });
    document.getElementById(`tab-${tabName}`).style.display = 'block';
    
    // Carregar dados específicos da aba
    if (tabName === 'weather') {
        loadWeatherData();
    } else if (tabName === 'live') {
        // Aba ao vivo já está configurada
    }
}

// Simulação de dados ao vivo
function startLiveSimulation() {
    const button = document.getElementById('live-btn');
    const container = document.getElementById('live-data');
    
    if (isLiveMode) {
        // Parar simulação
        clearInterval(liveSimulationInterval);
        isLiveMode = false;
        button.textContent = '▶️ Iniciar Simulação';
        button.style.background = '#28a745';
        container.innerHTML = '';
    } else {
        // Iniciar simulação
        isLiveMode = true;
        button.textContent = '⏸️ Parar Simulação';
        button.style.background = '#dc3545';
        
        container.innerHTML = '<div style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 1rem;"><h5>📡 Dados ao Vivo Simulados</h5><div id="live-messages"></div></div>';
        
        // Simular dados a cada 3 segundos
        liveSimulationInterval = setInterval(generateLiveData, 3000);
        generateLiveData(); // Primeira execução imediata
    }
}

// Gerar dados ao vivo simulados
function generateLiveData() {
    const devices = ['station001', 'sensor002', 'weather003', 'iot004'];
    const device = devices[Math.floor(Math.random() * devices.length)];
    
    const liveData = {
        device_id: device,
        timestamp: new Date().toLocaleString('pt-BR'),
        temperature: (Math.random() * 20 + 15).toFixed(1), // 15-35°C
        humidity: (Math.random() * 60 + 30).toFixed(1),    // 30-90%
        wind_speed: (Math.random() * 30).toFixed(1),       // 0-30 km/h
        wind_direction: Math.floor(Math.random() * 360),   // 0-359°
        precipitation: (Math.random() * 5).toFixed(2)      // 0-5mm
    };
    
    const liveContainer = document.getElementById('live-messages');
    if (liveContainer) {
        const messageHtml = `
            <div class="live-message" style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 0.5rem; margin-bottom: 0.5rem; animation: fadeIn 0.3s;">
                <strong>📨 ${liveData.timestamp} - ${liveData.device_id}</strong><br>
                🌡️ ${liveData.temperature}°C | 💧 ${liveData.humidity}% | 💨 ${liveData.wind_speed} km/h | 🧭 ${liveData.wind_direction}° | 🌧️ ${liveData.precipitation} mm
            </div>
        `;
        
        liveContainer.insertAdjacentHTML('afterbegin', messageHtml);
        
        // Manter apenas as últimas 10 mensagens
        const messages = liveContainer.querySelectorAll('.live-message');
        if (messages.length > 10) {
            messages[messages.length - 1].remove();
        }
    }
}

// Atualizar paginação
function updatePagination(pagination) {
    const pageInfo = document.getElementById('page-info');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    
    const currentPage = Math.floor(pagination.offset / pagination.limit) + 1;
    const totalPages = Math.ceil(pagination.total / pagination.limit);
    
    pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
    
    prevBtn.style.display = pagination.offset > 0 ? 'inline-block' : 'none';
    nextBtn.style.display = (pagination.offset + pagination.limit) < pagination.total ? 'inline-block' : 'none';
}

// Funções utilitárias
function formatDateTime(dateTime) {
    return new Date(dateTime).toLocaleString('pt-BR');
}

function formatTimeAgo(dateTime) {
    const now = new Date();
    const past = new Date(dateTime);
    const diffMs = now - past;
    const diffMins = Math.floor(diffMs / 60000);
    
    if (diffMins < 1) return 'agora';
    if (diffMins < 60) return `${diffMins}m atrás`;
    
    const diffHours = Math.floor(diffMins / 60);
    if (diffHours < 24) return `${diffHours}h atrás`;
    
    const diffDays = Math.floor(diffHours / 24);
    return `${diffDays}d atrás`;
}

function formatMqttMessage(message) {
    // Tentar extrair tópico e payload da mensagem
    const match = message.match(/MQTT Topic: (.+?) \| Message: (.+)/);
    if (match) {
        const topic = match[1];
        const payload = match[2];
        
        return `
            <div><strong>📂 Tópico:</strong> <code>${topic}</code></div>
            <div style="margin-top: 0.5rem;"><strong>📄 Payload:</strong></div>
            <div style="background: #f8f9fa; padding: 0.5rem; border-radius: 4px; font-family: monospace; font-size: 0.9em; margin-top: 0.25rem;">
                ${payload}
            </div>
        `;
    }
    
    return message;
}

// CSS para animações
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-button.active {
        background: #007bff !important;
    }
    
    .stat-card {
        transition: transform 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
    }
`;
document.head.appendChild(style);
</script>
