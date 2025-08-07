<?php
/**
 * @var \App\View\AppView $this
 * @var array $settings
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('MQTT Actions') ?></h4>
            <?= $this->Html->link(__('🔙 Voltar'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(
                __('🔧 Testar Conexão'),
                ['action' => 'testConnection'],
                ['confirm' => __('Deseja testar a conexão MQTT?'), 'class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="mqtt settings content">
            <h3><?= __('⚙️ Configurações MQTT') ?></h3>
            
            <?= $this->Form->create(null) ?>
            <fieldset>
                <legend><?= __('Configurações do Servidor MQTT') ?></legend>
                
                <div class="server-config" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                    <div class="internal-server" style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #17a2b8;">
                        <h4 style="margin-top: 0; color: #17a2b8;">🏠 Servidor Interno</h4>
                        <?= $this->Form->control('mqtt_host_internal', [
                            'label' => 'Host Interno',
                            'value' => $settings['mqtt_host_internal'],
                            'readonly' => true
                        ]) ?>
                        
                        <p><strong>Uso:</strong> Para dispositivos na rede local</p>
                        <p><strong>Vantagens:</strong> Baixa latência, sem dependência de internet</p>
                    </div>
                    
                    <div class="external-server" style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #28a745;">
                        <h4 style="margin-top: 0; color: #28a745;">🌐 Servidor Externo</h4>
                        <?= $this->Form->control('mqtt_host_external', [
                            'label' => 'Host Externo',
                            'value' => $settings['mqtt_host_external'],
                            'readonly' => true
                        ]) ?>
                        
                        <p><strong>Uso:</strong> Para dispositivos remotos via internet</p>
                        <p><strong>Vantagens:</strong> Acessível de qualquer lugar</p>
                    </div>
                </div>
                
                <?= $this->Form->control('mqtt_port', [
                    'label' => 'Porta MQTT',
                    'value' => $settings['mqtt_port'],
                    'readonly' => true,
                    'help' => 'Porta padrão para comunicação MQTT'
                ]) ?>
                
                <?= $this->Form->control('mqtt_username', [
                    'label' => 'Usuário',
                    'value' => $settings['mqtt_username'],
                    'readonly' => true,
                    'help' => 'Nome de usuário para autenticação'
                ]) ?>
                
                <?= $this->Form->control('mqtt_password', [
                    'type' => 'password',
                    'label' => 'Senha',
                    'value' => $settings['mqtt_password'],
                    'readonly' => true,
                    'help' => 'Senha para autenticação no broker MQTT'
                ]) ?>
                
                <?= $this->Form->control('default_topic', [
                    'label' => 'Tópico Padrão',
                    'value' => $settings['default_topic'],
                    'help' => 'Tópico MQTT padrão para escutar mensagens. Use + como wildcard para um nível, # para múltiplos níveis'
                ]) ?>
            </fieldset>
            
            <fieldset style="margin-top: 2rem;">
                <legend><?= __('Estrutura de Tópicos Recomendada') ?></legend>
                
                <div class="topic-structure" style="background: #e9ecef; padding: 1.5rem; border-radius: 8px;">
                    <h5>📂 Hierarquia de Tópicos Sugerida:</h5>
                    
                    <div style="font-family: monospace; background: #fff; padding: 1rem; border-radius: 4px; margin: 1rem 0;">
                        <div style="margin: 0.5rem 0;">📁 <strong>agrocity/</strong> (Namespace principal)</div>
                        <div style="margin: 0.5rem 0; margin-left: 1rem;">├── 📁 <strong>station001/</strong> (ID da estação meteorológica)</div>
                        <div style="margin: 0.5rem 0; margin-left: 2rem;">│   ├── 📄 <strong>data</strong> (Dados completos em JSON)</div>
                        <div style="margin: 0.5rem 0; margin-left: 2rem;">│   ├── 📄 <strong>temperature</strong> (Apenas temperatura)</div>
                        <div style="margin: 0.5rem 0; margin-left: 2rem;">│   ├── 📄 <strong>humidity</strong> (Apenas umidade)</div>
                        <div style="margin: 0.5rem 0; margin-left: 2rem;">│   ├── 📄 <strong>pressure</strong> (Apenas pressão)</div>
                        <div style="margin: 0.5rem 0; margin-left: 2rem;">│   └── 📄 <strong>status</strong> (Status do dispositivo)</div>
                        <div style="margin: 0.5rem 0; margin-left: 1rem;">├── 📁 <strong>sensor002/</strong></div>
                        <div style="margin: 0.5rem 0; margin-left: 1rem;">└── 📁 <strong>camera003/</strong></div>
                    </div>
                    
                    <h5>🔍 Exemplos de Filtros (Wildcards):</h5>
                    <ul>
                        <li><code>agrocity/+/data</code> - Todos os dados de qualquer dispositivo</li>
                        <li><code>agrocity/station001/#</code> - Todos os tópicos da station001</li>
                        <li><code>agrocity/+/temperature</code> - Apenas temperatura de todos os dispositivos</li>
                        <li><code>agrocity/#</code> - Todos os tópicos do projeto Agrocity</li>
                    </ul>
                </div>
            </fieldset>
            
            <fieldset style="margin-top: 2rem;">
                <legend><?= __('Formato de Dados JSON Esperado') ?></legend>
                
                <div class="json-format" style="background: #263238; color: #fff; padding: 1.5rem; border-radius: 8px;">
                    <h5 style="color: #4caf50;">📄 Exemplo de Payload JSON:</h5>
                    <pre style="margin: 1rem 0; overflow-x: auto;"><code>{
  "device_id": "station001",
  "timestamp": "2024-08-05T14:30:00Z",
  "location": {
    "latitude": -30.0346,
    "longitude": -51.2177,
    "altitude": 10.5
  },
  "weather": {
    "temperature": 22.5,
    "humidity": 65.2,
    "pressure": 1013.25,
    "wind_speed": 12.8,
    "wind_direction": 180,
    "precipitation": 0.0
  },
  "device_status": {
    "battery": 85,
    "signal_strength": -65,
    "uptime": 86400
  }
}</code></pre>
                    
                    <h5 style="color: #4caf50;">📋 Campos Reconhecidos pelo Sistema:</h5>
                    <ul style="margin: 1rem 0;">
                        <li><strong>temperature</strong> - Temperatura em °C</li>
                        <li><strong>humidity</strong> - Umidade relativa em %</li>
                        <li><strong>pressure</strong> - Pressão atmosférica em hPa</li>
                        <li><strong>wind_speed</strong> - Velocidade do vento em km/h</li>
                        <li><strong>wind_direction</strong> - Direção do vento em graus</li>
                        <li><strong>precipitation</strong> - Precipitação em mm</li>
                        <li><strong>latitude/longitude</strong> - Coordenadas GPS</li>
                    </ul>
                </div>
            </fieldset>
            
            <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #ddd;">
                <?= $this->Form->button(__('💾 Salvar Configurações'), ['type' => 'submit', 'class' => 'button']) ?>
                <?= $this->Html->link(__('🔙 Cancelar'), ['action' => 'index'], ['class' => 'button secondary']) ?>
            </div>
            <?= $this->Form->end() ?>
            
            <!-- Informações de Segurança -->
            <div class="security-info" style="background: #fff3cd; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #ffc107; margin-top: 2rem;">
                <h5 style="margin-top: 0; color: #856404;">🔒 Informações de Segurança</h5>
                <ul style="margin: 0;">
                    <li>As credenciais MQTT são armazenadas de forma segura</li>
                    <li>A comunicação pode ser criptografada usando TLS/SSL</li>
                    <li>Recomenda-se usar tópicos específicos para cada tipo de dispositivo</li>
                    <li>Monitore regularmente os logs de conexão para atividades suspeitas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.server-config {
    margin-bottom: 2rem;
}

.topic-structure {
    font-size: 0.9em;
}

.json-format pre {
    background: #1e1e1e;
    padding: 1rem;
    border-radius: 4px;
    font-size: 0.9em;
    line-height: 1.4;
}

.security-info ul {
    list-style-type: none;
    padding-left: 0;
}

.security-info li {
    padding: 0.25rem 0;
}

.security-info li:before {
    content: "🔐 ";
    margin-right: 0.5rem;
}

@media (max-width: 768px) {
    .server-config {
        grid-template-columns: 1fr;
    }
    
    .json-format pre {
        font-size: 0.8em;
    }
}
</style>
