<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Projeto de Monitoramento Meteorológico</title>
    <link rel="icon" type="image/png" href="img/leaf.png">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
            color: #333;
            padding-top: 10px; /* Espaço para o header fixo */
        }
        
        /* Header fixo no topo com logo */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, #004080 0%, #0059b3 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            color: white;
            font-weight: bold;
            font-size: 1.1em;
        }
        
        .logo-section img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            border-radius: 50%;
        }
        
        /* Botão toggle redesenhado */
        .header-toggle {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 1.3em;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 15px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
        }
        
        .header-toggle:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: scale(1.05);
        }
        
        /* Header principal + Nav (inicialmente escondidos) */
        .header-nav-container {
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            z-index: 1000;
            transform: translateY(-100%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s ease;
        }
        
        .header-nav-container.visible {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        header {
            background-color: #004080;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            margin: 0;
        }
        
        header h1 {
            margin: 0;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        nav {
            background-color: #0059b3;
            overflow: hidden;
            margin: 0;
        }
        
        /* Ajustar body quando header+nav estão visíveis */
        body.header-visible {
            padding-top: 260px; /* Espaço para top-bar + header + nav */
        }
        nav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        nav a:hover {
            background-color: #0073e6;
            color: white;
        }
        main {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
            padding-top: 80px; /* Espaço apenas para a top-bar */
            transition: all 0.5s ease;
        }
        
        body.header-visible main {
            /* padding-top: 280px; /* Mais espaço quando header+nav estão visíveis */
        }
        h2, h3 {
            color: #004080;
        }
        h1{
            color:#FFFFFF;
        }
        section {
            margin-bottom: 30px;
        }
        ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
        }
        table, th, td {
            border: 1px solid #cccccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #e6f0ff;
        }

        .rotate-text {
            text-align: center;
            vertical-align: middle;
            writing-mode: vertical-rl; /* Rota o texto verticalmente */
            transform: rotate(180deg); /* Ajusta a direção */
            font-weight: bold;
        }
        footer {
            background-color: #004080;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .stakeholder {
            background-color: #e6f0ff;
            padding: 10px;
            margin-bottom: 10px;
        }
        .code-block {
            background-color: #e9ecef;
            padding: 10px;
            overflow-x: auto;
        }
        .code-block pre {
            margin: 0;
        }
        img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
            transition: transform 0.2s; /* Efeito suave ao ampliar */
        }
        img:hover {
            transform: scale(1.05); /* Amplia a imagem em 5% ao passar o mouse */
            cursor: pointer;
        }
        .collaboration {
            background-color: #e6f7ff;
            border: 1px solid #cceeff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 40px;
        }
        .collaboration a {
            color: #0056b3;
            text-decoration: none;
        }
        .collaboration a:hover {
            text-decoration: underline;
        }
        
        /* Estilos para o mapa - DESTAQUE */
        .map-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 30px;
            margin: 0 0 40px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }
        
        .map-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            pointer-events: none;
        }
        
        #map {
            width: 100%;
            height: 600px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .map-info {
            background: rgba(255,255,255,0.95);
            border: 2px solid rgba(255,255,255,0.5);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }
        
        .map-title {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        .map-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
            text-align: center;
            margin-bottom: 25px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }
        
        /* Estilos para indicadores meteorológicos */
        .weather-indicator {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #0066cc;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: #0066cc;
            text-align: center;
            line-height: 1;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .weather-indicator:hover {
            transform: scale(1.1);
            border-width: 3px;
        }
        
        .weather-popup {
            max-width: 300px;
            font-family: Arial, sans-serif;
        }
        
        .weather-popup h3 {
            margin: 0 0 10px 0;
            color: #0066cc;
            font-size: 16px;
        }
        
        .weather-current {
            background: #f0f8ff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        
        .weather-metric {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .weather-value {
            font-weight: bold;
        }
        
        .weather-loading {
            text-align: center;
            color: #666;
            font-style: italic;
        }
        
        .weather-error {
            background: #ffe6e6;
            border: 1px solid #ffcccc;
            padding: 10px;
            border-radius: 5px;
            color: #cc0000;
        }
        
        .api-status {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 1000;
        }
        
        .api-status.warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        
        .api-status.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Painel hidroclimático da Bacia Taquari-Antas */
        .hydro-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            margin: 0 0 15px;
            padding: 12px;
            background: rgba(255,255,255,0.96);
            border-radius: 8px;
            position: relative;
            z-index: 2;
        }

        .hydro-toolbar button {
            border: 1px solid #0066cc;
            border-radius: 6px;
            background: #fff;
            color: #004080;
            padding: 9px 14px;
            cursor: pointer;
            font-weight: bold;
        }

        .hydro-toolbar button.active {
            background: #004080;
            color: #fff;
        }

        .hydro-summary {
            display: none;
            grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
            gap: 10px;
            margin: 0 0 15px;
            position: relative;
            z-index: 2;
        }

        .hydro-summary.visible {
            display: grid;
        }

        .hydro-card {
            background: rgba(255,255,255,0.96);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .hydro-card strong {
            display: block;
            color: #004080;
            font-size: 1.25rem;
            margin-top: 4px;
        }

        .hydro-note {
            flex: 1 1 100%;
            margin: 0;
            color: #555;
            font-size: 0.86rem;
        }

        .rain-legend {
            line-height: 1.5;
            color: #333;
            background: rgba(255,255,255,0.95);
            padding: 8px 10px;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0,0,0,.25);
        }

        .rain-legend i {
            width: 14px;
            height: 14px;
            float: left;
            margin: 3px 7px 0 0;
            opacity: .85;
        }

        .river-label {
            border: 0;
            background: rgba(255,255,255,.82);
            box-shadow: none;
            color: #005b96;
            font-size: 11px;
            font-weight: bold;
            padding: 1px 4px;
            text-shadow:
                -1px -1px 0 #fff,
                 1px -1px 0 #fff,
                -1px  1px 0 #fff,
                 1px  1px 0 #fff;
        }

        .river-label::before {
            display: none;
        }

        .leaflet-popup-content-wrapper,
        .leaflet-popup-content {
            box-sizing: border-box;
        }

        .leaflet-popup-content {
            max-width: min(320px, calc(100vw - 70px));
        }

        @media (max-width: 700px) {
            html, body {
                max-width: 100%;
                overflow-x: hidden;
            }

            main {
                width: 100%;
                padding-left: 8px;
                padding-right: 8px;
                box-sizing: border-box;
            }

            #map {
                width: 100%;
                height: 560px;
                box-sizing: border-box;
            }

            .map-container {
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
            }

            .hydro-toolbar button { flex: 1 1 100%; }

            .hydro-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 7px;
            }

            .hydro-card {
                min-width: 0;
                padding: 9px 5px;
                font-size: .8rem;
                overflow-wrap: anywhere;
            }

            .hydro-card strong {
                font-size: .92rem;
            }

            .leaflet-popup-content {
                width: calc(100vw - 82px) !important;
                max-width: 300px;
                margin: 12px;
                line-height: 1.3;
            }

            .weather-popup {
                width: 100%;
                max-width: 100%;
                overflow: hidden;
            }

            .weather-metric {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 8px;
                align-items: start;
            }

            .weather-value {
                max-width: 120px;
                text-align: right;
                overflow-wrap: anywhere;
            }

            .rain-legend {
                max-width: 145px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Barra superior fixa com logo e toggle -->
    <div class="top-bar">
        <div class="logo-section">
            <?=$this->Html->image('AGROCITY_LIVING_LABicon.jpg', ['alt' => 'Agrocity Logo', 'width'=>'100em'])?>
            <span>MoniMete - Agrocity</span>
        </div>
        <button class="header-toggle" id="header-toggle" onclick="toggleHeader()" aria-label="Mostrar/Esconder informações do projeto">
            <span id="toggle-text">📋 Sobre o Projeto</span>
        </button>
    </div>

    <!-- Container para Header + Nav (inicialmente escondidos) -->
    <div class="header-nav-container" id="header-nav-container">
        <!-- Header principal -->
        <header id="main-header">
            <h1>Projeto de Monitoramento Meteorológico - Agrocity</h1>
        </header>

        <!-- Navegação -->
        <nav>
            <a href="#introducao">Introdução</a>
            <a href="#mapa-rs">Mapa Meteorológico</a>
            <a href="#objetivo">Objetivo</a>
            <a href="#colaborativo">Colaborativo</a>
            <a href="#esforcos">Esforços Necessários</a>
            <a href="#materiais">Materiais Necessários</a>
            <a href="#software">Ferramentas de Software</a>
            <a href="#cronograma">Cronograma</a>
            <a href="#stakeholders">Stakeholders</a>
            <a href="#requisitos">Requisitos do Sistema</a>
            <a href="#coleta-requisitos">Coleta Visual de Requisitos</a>
            <a href="#user-stories">Histórias de Usuário</a>
            <a href="#infraestrutura">Infraestrutura</a>
            <a href="#modelo-er">Modelo ER</a>
            <a href="#mysql">Comandos MySQL</a>
            <?= $this->Html->link(__('Acesso restrito'), ['controller' => 'users'],['action' => 'login'], ['class' => 'button float-right']) ?>
        </nav>
    </div>

    <main>
        <section id="introducao">
            <h2>Introdução</h2>
            <p>O seguinte projeto tem como objetivo criar um sistema de acompanhamento meteorológico com comunicação direta a satélites existentes com essa finalidade e APIs de fontes confiáveis. Seu objetivo principal é permitir a independência do Agrocity quanto ao monitoramento climático.</p>
            <p><strong>Confira abaixo nosso mapa interativo em tempo real com dados meteorológicos de todos os municípios do Rio Grande do Sul! 🌦️</strong></p>
        </section>

        <section id="mapa-rs">
            <div class="map-container">
                <h2 class="map-title">🌦️ Monitoramento Meteorológico em Tempo Real</h2>
                <p class="map-subtitle">Dados meteorológicos atualizados de todos os municípios do Rio Grande do Sul</p>
                
                <div class="map-info">
                    <p><strong>🎯 Visualização Inteligente:</strong> Este mapa apresenta dados meteorológicos dos municípios presentes na base geográfica do Rio Grande do Sul, integrando temperatura, precipitação e vento através da API Open-Meteo.</p>
                    <p><strong>📊 Dados Exibidos:</strong> 🌡️ Temperatura (°C) | 🌧️ Precipitação (mm) | 💨 Velocidade do Vento (km/h) | 🧭 Direção do Vento (°)</p>
                    <p><strong>🎨 Indicadores Visuais:</strong> As cores dos marcadores variam conforme a temperatura - do azul (frio) ao vermelho (quente).</p>
                </div>

                <div class="hydro-toolbar">
                    <button type="button" id="view-weather" class="active">🌦️ Clima no RS</button>
                    <button type="button" id="view-hydro">🌊 Bacia Taquari-Antas</button>
                    <p class="hydro-note">
                        A análise hidroclimática utiliza chuva observada e prevista. Ela é indicativa e não substitui alertas da Defesa Civil, SEMA, ANA ou municípios.
                    </p>
                </div>

                <div id="hydro-summary" class="hydro-summary" aria-live="polite">
                    <div class="hydro-card">Municípios analisados<strong id="hydro-count">0</strong></div>
                    <div class="hydro-card">Média ponderada 24 h<strong id="hydro-rain-24">-- mm</strong></div>
                    <div class="hydro-card">Maior acumulado 24 h<strong id="hydro-max-24">-- mm</strong></div>
                    <div class="hydro-card">Previsão média 72 h<strong id="hydro-forecast-72">-- mm</strong></div>
                    <div class="hydro-card">Situação predominante<strong id="hydro-status">Calculando</strong></div>
                </div>
                
                <div id="map"></div>
                
                <p style="margin-top: 20px; font-size: 0.9em; color: rgba(255,255,255,0.8); text-align: center; position: relative; z-index: 1;">
                    <strong>💡 Como usar:</strong> escolha “Bacia Taquari-Antas” para destacar os municípios contribuintes e clique em um município para consultar seus acumulados de chuva.
                </p>
            </div>
        </section>

        <section id="objetivo">
            <h2>Objetivo</h2>
            <p>Criar plataforma centralizada de dados meteorológicos, sendo capaz de receber dados oriundos de APIs e satélites de monitoramento. Sendo a entrega proposta uma plataforma web com recepção de dados via satélite por meio de antena APT e comunicação com outras plataformas via internet. Espera-se que a plataforma permita visualização histórica dos dados por região e independência nas análises.</p>
        </section>
        <section id="colaborativo">
            <h2>Colaboração no Projeto</h2>
            <div class="collaboration">
                <p>Este projeto é <strong>colaborativo</strong> e aberto à contribuição de desenvolvedores e pesquisadores interessados. Todas as alterações podem ser feitas via <em>push</em> no repositório GitHub do projeto e serão revisadas antes de serem integradas à versão final.</p>
                <p>Além disso, utilizamos o Trello para organização de tarefas, backlog e progresso do projeto.</p>
                <ul>
                    <li><strong>GitHub do Projeto:</strong> <a href="https://github.com/pedroanhaia/monimete" target="_blank">https://github.com/pedroanhaia/monimete</a></li>
                    <li><strong>Trello:</strong> <a href="https://trello.com/invite/b/670f5d357789eb16b37381d1/ATTI7dff1f21b9adc9c15bf1b4865dc87ab104F119AE/tarefas-sistema-metereologico" target="_blank">https://trello.com/invite/b/670f5d357789eb16b37381d1/ATTI7dff1f21b9adc9c15bf1b4865dc87ab104F119AE/tarefas-sistema-metereologico</a></li>
                </ul>
                <p>Sinta-se à vontade para contribuir com melhorias, correções de bugs e novas funcionalidades! 🚀</p>
            </div>
        </section>

        <section id="esforcos">
            <h2>Esforços Necessários</h2>
            <ul>
                <li>✅ Levantamento de requisitos;</li>
                <li>✅ Prototipação do sistema;</li>
                <li>✅ Modelagem do banco de dados utilizando <a href="https://www.lucidchart.com/" target="_blank">LucidChart</a>;</li>
                <li>✅ Criação de ambiente colaborativo de desenvolvimento no <a href="https://github.com/" target="_blank">GitHub</a>;</li>
                <li>✅ Configuração de ambiente de desenvolvimento (<a href="https://www.apachefriends.org/pt_br/index.html" target="_blank">XAMPP</a>, <a href="https://code.visualstudio.com/download" target="_blank">VS Code</a>);</li>
                <li>✅ Criação de server-side e backoffice com o framework <a href="https://book.cakephp.org/4/en/installation.html" target="_blank">CakePHP</a>;</li>
                <li>🟡 Desenvolvimento de integração com API de dados meteorológicos (<a href="http://servicos.cptec.inpe.br/XML/" target="_blank">CPTEC</a>) - <em>70% concluído</em>;</li>
                <li>❌ Desenvolvimento de integração do sistema com a Raspberry Pi;</li>
                <li>🟡 Desenvolvimento de interfaces para visualização dos dados (recomenda-se o uso de JavaScript com comunicação via AJAX) - <em>30% concluído</em>;</li>
                <li>❌ Aquisição de materiais necessários;</li>
                <li>❌ Desenvolvimento de antena de acordo com o manual (<a href="https://www.raspberrypi.com/tutorials/build-your-own-weather-satellite-receiving-station/" target="_blank">Tutorial</a>);</li>
                <li>❌ Configuração da Raspberry Pi de acordo com o manual;</li>
                <li>❌ Posicionamento da antena;</li>
                <li>❌ Testes de verificação de integridade.</li>
            </ul>
            
            <div style="margin: 20px 0; padding: 15px; background: #f1f8e9; border-left: 4px solid #4caf50; border-radius: 4px;">
                <h4 style="margin-top: 0;">✅ Concluído (58% dos esforços)</h4>
                <p>Base sólida do sistema estabelecida com back-office funcional, modelo de dados robusto e integração básica com APIs.</p>
            </div>
            
            <div style="margin: 20px 0; padding: 15px; background: #fff8e1; border-left: 4px solid #ffc107; border-radius: 4px;">
                <h4 style="margin-top: 0;">🟡 Em Progresso (14% dos esforços)</h4>
                <p>Integração com CPTEC e interfaces de visualização em desenvolvimento ativo.</p>
            </div>
            
            <div style="margin: 20px 0; padding: 15px; background: #ffebee; border-left: 4px solid #f44336; border-radius: 4px;">
                <h4 style="margin-top: 0;">❌ Pendente (28% dos esforços)</h4>
                <p>Sistema de hardware (antena e Raspberry Pi) e testes de integração aguardando implementação.</p>
            </div>
        </section>

        <section id="materiais">
            <h2>Materiais Necessários</h2>
            <ul>
                <li>Trena;</li>
                <li>Paquímetro;</li>
                <li>Raspberry Pi 4 ou superior;</li>
                <li>Furadeira;</li>
                <li>Brocas de 8 mm e XX mm;</li>
                <li>Receptor USB SDR (<a href="https://www.amazon.co.uk/NooElec-NESDR-Mini-Previously-Compatible/dp/B009U7WZCA/" target="_blank">Modelo</a>);</li>
                <li>Gabinete Raspberry Pi;</li>
                <li>5 metros de tubo de cobre 8mm (<a href="https://www.amazon.co.uk/Metre-Coil-Table-Microbore-Copper/dp/B00KJCTIAW/" target="_blank">Modelo</a>);</li>
                <li>Adaptador rabo de porco MCX para BNC (<a href="https://www.amazon.co.uk/sourcingmap%C2%AE-Female-Pigtail-Adapter-23-5cm-Black/dp/B00K85HHTE" target="_blank">Modelo</a>);</li>
                <li>Cabo coaxial BNC macho para BNC fêmea (<a href="https://www.amazon.co.uk/BOOBRIE-Female-Extension-Broadcast-Security/dp/B09F2KHF8B/" target="_blank">Modelo</a>);</li>
                <li>Tubo de resíduo de plástico branco 40mm, 1,5 metros de comprimento (<a href="https://www.screwfix.com/p/floplast-solvent-weld-waste-pipe-white-40mm-x-3m/44310" target="_blank">Modelo</a>);</li>
                <li>Lixa;</li>
                <li>Estanho;</li>
                <li>Parafusos auto-roscantes de 4 x 2 mm por 8 mm;</li>
                <li>Computador para uso;</li>
                <li>Impressora 3D para criação de peças componentes da antena;</li>
                <li>Rede de internet;</li>
                <li>Serra de cortar cano;</li>
                <li>Cola para plástico e madeira;</li>
                <li>Chave Phillips de tamanho médio;</li>
                <li>Lápis.</li>
            </ul>
        </section>

        <section id="software">
            <h2>Ferramentas de Software Utilizadas</h2>
            <ul>
                <li>XAMPP V5.2 (PHP VXX, MySQL VXX);</li>
                <li>Composer VXX;</li>
                <li>LucidChart;</li>
                <li>GitHub;</li>
                <li>Visual Studio Code;</li>
                <li>CakePHP Framework.</li>
            </ul>
        </section>

        <section id="cronograma">
            <h2>Cronograma Previsto</h2>
            <table>
                <tr>
                    <th>Data</th>
                    <th>Tarefa</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>24/10/2024</td>
                    <td>Levantamento de requisitos, arquitetar infraestrutura.</td>
                    <td>✅ Concluído</td>
                </tr>
                <tr>
                    <td>31/10/2024</td>
                    <td>Modelar banco de dados.</td>
                    <td>✅ Concluído</td>
                </tr>
                <tr>
                    <td>07/11/2024</td>
                    <td>Criação de ambiente de desenvolvimento.</td>
                    <td>✅ Concluído</td>
                </tr>
                <tr>
                    <td>14/11/2024</td>
                    <td>Geração de banco de dados e esqueleto do backoffice.</td>
                    <td>✅ Concluído</td>
                </tr>
                <tr>
                    <td>21/11/2024</td>
                    <td>Ajustes server-side e desenvolvimento de API.</td>
                    <td>✅ Concluído</td>
                </tr>
                <tr style="background-color: #e8f5e8;">
                    <td>25/11/2024</td>
                    <td><strong>Implementação de mapa interativo com dados meteorológicos em tempo real (Open-Meteo API)</strong></td>
                    <td>✅ <strong>Concluído</strong></td>
                </tr>
                <tr>
                    <td>28/11/2024</td>
                    <td>Desenvolvimento de antena.</td>
                    <td>🟡 Em andamento</td>
                </tr>
                <tr>
                    <td>05/12/2024</td>
                    <td>Continuação do desenvolvimento de antena.</td>
                    <td>❌ Pendente</td>
                </tr>
                <tr>
                    <td>12/12/2024</td>
                    <td>Posicionamento de antena.</td>
                    <td>❌ Pendente</td>
                </tr>
                <tr>
                    <td>19/12/2024</td>
                    <td>Teste do sistema e ajustes.</td>
                    <td>❌ Pendente</td>
                </tr>
                <tr>
                    <td>26/12/2024</td>
                    <td>Teste do sistema e ajustes finais.</td>
                    <td>❌ Pendente</td>
                </tr>
            </table>
            
            <div style="margin: 20px 0; padding: 15px; background: #e8f5e8; border-left: 4px solid #4caf50; border-radius: 4px;">
                <h4 style="margin-top: 0;">🎉 Última Implementação Concluída (25/11/2024)</h4>
                <p><strong>Mapa Meteorológico Interativo:</strong> Sistema completo implementado com:</p>
                <ul>
                    <li>✅ Integração em tempo real com API Open-Meteo</li>
                    <li>✅ Visualização de 497 municípios do Rio Grande do Sul</li>
                    <li>✅ Dados de temperatura, precipitação, vento e direção</li>
                    <li>✅ Sistema inteligente de cache (30 minutos)</li>
                    <li>✅ Gerenciamento de limites de API (10.000 calls/dia)</li>
                    <li>✅ Interface responsiva com indicadores visuais por temperatura</li>
                    <li>✅ Popups informativos com dados detalhados</li>
                </ul>
            </div>
        </section>

        <section id="stakeholders">
            <h2>Stakeholders</h2>
            <div class="stakeholder">
                <h3>Produtores Rurais</h3>
                <p><strong>Objetivo:</strong> Garantir que os produtores rurais tenham acesso a dados meteorológicos precisos e históricos para facilitar a tomada de decisões sobre plantio e colheita.</p>
                <p><strong>Métrica:</strong> Aumento na produtividade agrícola devido à previsibilidade climática mais acurada.</p>
            </div>
            <div class="stakeholder">
                <h3>Equipe de Pesquisa Agrocity</h3>
                <p><strong>Objetivo:</strong> Independência do Agrocity em relação a fontes externas de dados climáticos, utilizando dados próprios e APIs integradas.</p>
                <p><strong>Métrica:</strong> Redução de dependência de serviços externos de monitoramento meteorológico.</p>
            </div>
        </section>

        <section id="requisitos">
            <h2>Requisitos do Sistema</h2>
            <ul>
                <li>Receber dados via satélite;</li>
                <li>Integrar com API de dados meteorológicos;</li>
                <li>Integrar com sistema de mapas;</li>
                <li>Permitir acesso não autenticado aos dashboards;</li>
                <li>Armazenar dados históricos;</li>
                <li>Saídas dos dados com dashboards e mapas de calor.</li>
            </ul>
        </section>
        <section id="coleta-requisitos">
            <h2>Coleta Visual de Requisitos</h2>
            <table>
                <tr>
                    <th>Floresta</th>
                    <th>Árvore</th>
                    <th>Galhos</th>
                    <th>Folhas</th>
                </tr>
                <tr>
                    <td class="rotate-text" rowspan="12">MoniMete</td>
                    <td rowspan="2">Receber dados via satélite</td>
                    <td rowspan="2">Criação de sistema de recepção via satélite</td>
                    <td>Montar antena</td>
                </tr>
                <tr>
                    <td>Configurar Raspberry Pi</td>
                </tr>
                <tr>
                    <td rowspan="3">Armazenar dados históricos</td>
                    <td rowspan="3">Criação de CRUD</td>
                    <td>Modelagem do banco de dados</td>
                </tr>
                <tr>
                    <td>Desenvolvimento backoffice</td>
                </tr>
                <tr>
                    <td>Ajustes de navegação</td>
                </tr>
                <tr>
                    <td>Integrar com sistema de mapas</td>
                    <td>Desenvolver chamadas do serviço</td>
                    <td>Desenvolver chamadas do serviço</td>
                </tr>
                <tr>
                    <td rowspan="2">Permitir acesso não autenticado aos dashboards</td>
                    <td rowspan="2">Realizar liberações</td>
                    <td>Mapear riscos</td>
                </tr>
                <tr>
                    <td>Executar ajustes de sistema</td>
                </tr>
                <tr>
                    <td>Integrar com API de dados meteorológicos</td>
                    <td>Desenvolver chamadas do serviço</td>
                    <td>Desenvolver chamadas do serviço</td>
                </tr>
                <tr>
                    <td rowspan="3">Saídas dos dados com dashboards e mapas de calor</td>
                    <td rowspan="3">Saídas dos dados com dashboards e mapas de calor</td>
                    <td>Definição de gráficos a serem utilizados</td>
                </tr>
                <tr>
                    <td>Desenvolvimento de templates das views</td>
                </tr>
                <tr>
                    <td>Desenvolvimento de código para alimentação dos gráficos</td>
                </tr>
            </table>
        </section>
        
        <section id="status-projeto">
            <h2>Status Atual do Projeto 📊</h2>
            <div style="margin: 20px 0;">
                <h3>Progresso Geral</h3>
                <div style="background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 10px 0;">
                    <div style="background: #4CAF50; height: 20px; border-radius: 10px; width: 75%;">
                        <span style="color: white; font-weight: bold; padding-left: 10px; line-height: 20px;">75% Concluído</span>
                    </div>
                </div>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr style="background-color: #f2f2f2;">
                    <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Componente</th>
                    <th style="border: 1px solid #ddd; padding: 12px; text-align: center;">Status</th>
                    <th style="border: 1px solid #ddd; padding: 12px; text-align: center;">% Completo</th>
                    <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Observações</th>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Back-office CRUD</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">✅ Concluído</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">100%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Sistema completo de gerenciamento</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Modelo de Dados</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">✅ Concluído</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">100%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Entidades e relacionamentos definidos</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">API Endpoints</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">🟡 Parcial</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">60%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Funcionalidades básicas, lógica real pendente</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Integração CPTEC/INPE</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">🟡 Parcial</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">70%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Importação funcional, relacionamentos pendentes</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Dashboard/Visualização</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">🟡 Básico</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">30%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Interface criada com dados simulados</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Sistema Satélite/Antena</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">❌ Pendente</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">0%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Não iniciado</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Testes Unitários</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">❌ Pendente</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">0%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Marcados como incompletos</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">Mapas Interativos</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">✅ Concluído</td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">95%</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Sistema completo com dados em tempo real da API Open-Meteo</td>
                </tr>
            </table>
            
            <div style="margin: 20px 0; padding: 15px; background: #e3f2fd; border-left: 4px solid #2196F3; border-radius: 4px;">
                <h4 style="margin-top: 0;">🎯 Próximos Passos Prioritários:</h4>
                <ul>
                    <li><strong>Implementar lógica real na API de devices</strong> - Substituir dados simulados</li>
                    <li><strong>Conectar dados reais aos dashboards</strong> - Integrar com banco de dados</li>
                    <li><strong>Finalizar relacionamentos CPTEC</strong> - Conectar services, devices e locations</li>
                    <li><strong>Desenvolver testes unitários</strong> - Garantir qualidade do código</li>
                    <li><strong>Implementar sistema de antena</strong> - Hardware e integração Raspberry Pi</li>
                </ul>
            </div>
            
            <div style="margin: 20px 0; padding: 15px; background: #e8f5e8; border-left: 4px solid #4caf50; border-radius: 4px;">
                <h4 style="margin-top: 0;">🎉 Últimas Conquistas:</h4>
                <ul>
                    <li><strong>✅ Sistema de Mapas Meteorológicos Completo</strong> - Implementado com sucesso</li>
                    <li><strong>✅ Integração Open-Meteo API</strong> - Dados em tempo real de 497 municípios</li>
                    <li><strong>✅ Interface Responsiva</strong> - Design otimizado e user-friendly</li>
                    <li><strong>✅ Sistema de Cache Inteligente</strong> - Performance otimizada</li>
                    <li><strong>✅ Gerenciamento de Limites de API</strong> - Controle automático de uso</li>
                </ul>
            </div>
            
            <div style="margin: 20px 0; padding: 15px; background: #fff3e0; border-left: 4px solid #ff9800; border-radius: 4px;">
                <h4 style="margin-top: 0;">⚠️ Questões Identificadas:</h4>
                <ul>
                    <li>Dados simulados em dashboards precisam ser substituídos por dados reais</li>
                    <li>Relacionamentos de entidades não estão completamente conectados</li>
                    <li>API endpoints retornam placeholders em vez de dados reais</li>
                    <li>Sistema de recepção via satélite ainda não implementado</li>
                </ul>
            </div>
        </section>
        
        <section id="user-stories">
            <h2>Histórias de Usuário</h2>
            <h3>Cadastro e Integração de APIs</h3>
            <p><strong>Título:</strong> Integração de APIs Meteorológicas</p>
            <p><strong>Descrição:</strong> O sistema deve ser capaz de integrar e realizar requisições a APIs confiáveis de dados meteorológicos, como o CPTEC.</p>
            <p><strong>Critérios de Aceitação:</strong> As requisições são realizadas com sucesso, e os dados meteorológicos são armazenados na plataforma.</p>

            <h3>Desenvolvimento de Antena</h3>
            <p><strong>Título:</strong> Montagem e Configuração de Antena</p>
            <p><strong>Descrição:</strong> O sistema deve permitir a configuração de uma antena meteorológica baseada em Raspberry Pi, que se comunica com satélites para receber dados meteorológicos.</p>
            <p><strong>Critérios de Aceitação:</strong> A antena deve estar corretamente posicionada, capturando dados e enviando-os para o sistema.</p>

            <h3>Visualização de Dados Meteorológicos</h3>
            <p><strong>Título:</strong> Exibição de Dados Meteorológicos</p>
            <p><strong>Descrição:</strong> O usuário pode visualizar dados climáticos em tempo real e históricos por região, exibidos através de gráficos e mapas interativos.</p>
            <p><strong>Critérios de Aceitação:</strong> Os dados são exibidos de forma clara e filtrável, permitindo análises por data e região.</p>

            <h3>Criação de Ambiente Colaborativo</h3>
            <p><strong>Título:</strong> Criação de Ambiente Colaborativo de Desenvolvimento</p>
            <p><strong>Descrição:</strong> Criação e configuração de um ambiente colaborativo utilizando GitHub para gerenciar o código e progresso do projeto.</p>
            <p><strong>Critérios de Aceitação:</strong> O ambiente está funcionando corretamente se todos os desenvolvedores têm acesso ao repositório.</p>
        </section>
        <section id="infraestrutura">
            <h2>Esquema de Infraestrutura feito por gabriela</h2>
            <img src="webroot/img/InfraestruturaMonimete.png" alt="Esquema de Infraestrutura do Sistema MoniMete">
        </section>

        <section id="modelo-er">
            <h2>Modelo ER do Sistema MoniMete</h2>
            <img src="webroot/img/ModeloERMonimete.png" alt="Modelo ER do Sistema MoniMete">
        </section>
        <section id="mysql">
            <h2>Comandos MySQL</h2>
            <div class="code-block">
                <pre>
CREATE TABLE `platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `type` int(2),
  `url` varchar(255),
  `last_update` datetime,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `powered` varchar(255),
  PRIMARY KEY (`id`)
);

CREATE TABLE `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `obs` text,
  `cod_ibge` varchar(255),
  `description` text,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  PRIMARY KEY (`id`)
);

CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `latitude` float,
  `longitude` float,
  `description` text,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `city_id` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`)
);

CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `type` int(2),
  `model` varchar(100),
  `producer` varchar(100),
  `description` text,
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `location_id` int(11),
  `img` varchar(255),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`)
);

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11),
  `name` varchar(100),
  `value` varchar(200),
  `description` text,
  `created` datetime,
  `modified` datetime,
  `type` int(2),
  `role` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`)
);

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `email` varchar(200),
  `password` varchar(255),
  `type` int(2),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `city_id` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`)
);

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime,
  `message` text,
  `status` varchar(50),
  `type` int(2),
  `device_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `platform_id` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`),
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`)
);

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200),
  `type` int(2),
  `endpoint` varchar(255),
  `platform_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`platform_id`) REFERENCES `platforms`(`id`)
);

CREATE TABLE `data_metereological` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime,
  `temperature` float,
  `humidity` float,
  `precipitation` float,
  `wind_direction` varchar(50),
  `wind_speed` float,
  `latitude` float,
  `longitude` float,
  `location_id` int(11),
  `service_id` int(11),
  `device_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  `type` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`),
  FOREIGN KEY (`location_id`) REFERENCES `locations`(`id`)
);

CREATE TABLE `data_satellite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` datetime,
  `quality_signal` float,
  `latitude` float,
  `longitude` float,
  `type` int(2),
  `device_id` int(11),
  `created` datetime,
  `modified` datetime,
  `role` int(2),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`)
);
                </pre>
            </div>
        </section>
    </main>

    <footer>
        &copy; 2024 Projeto MoniMete - Todos os direitos reservados.
    </footer>

    <script>
        // Função para alternar cabeçalho retrátil
        function toggleHeader() {
            const headerNavContainer = document.getElementById('header-nav-container');
            const body = document.body;
            const toggleText = document.getElementById('toggle-text');
            
            // Alternar classes
            headerNavContainer.classList.toggle('visible');
            body.classList.toggle('header-visible');
            
            // Alterar texto do botão baseado no estado
            if (headerNavContainer.classList.contains('visible')) {
                toggleText.textContent = '✖️ Fechar';
            } else {
                toggleText.textContent = '📋 Sobre o Projeto';
            }
            
            // Salvar estado no localStorage
            const isVisible = headerNavContainer.classList.contains('visible');
            localStorage.setItem('header_visible', isVisible);
        }
        
        // Restaurar estado do cabeçalho ao carregar a página
        function restoreHeaderState() {
            const isVisible = localStorage.getItem('header_visible') === 'true';
            if (isVisible) {
                const headerNavContainer = document.getElementById('header-nav-container');
                const body = document.body;
                const toggleText = document.getElementById('toggle-text');
                
                headerNavContainer.classList.add('visible');
                body.classList.add('header-visible');
                toggleText.textContent = '✖️ Fechar';
            }
        }

        function calculateRainMetrics(hourly) {
            if (!hourly || !Array.isArray(hourly.time) || !Array.isArray(hourly.precipitation)) {
                return null;
            }

            const now = Date.now();
            const hour = 60 * 60 * 1000;
            let observed24h = 0;
            let observed72h = 0;
            let forecast24h = 0;
            let forecast72h = 0;

            hourly.time.forEach((time, index) => {
                const timestamp = new Date(time).getTime();
                const value = Number(hourly.precipitation[index]) || 0;
                if (timestamp <= now && timestamp > now - (24 * hour)) observed24h += value;
                if (timestamp <= now && timestamp > now - (72 * hour)) observed72h += value;
                if (timestamp > now && timestamp <= now + (24 * hour)) forecast24h += value;
                if (timestamp > now && timestamp <= now + (72 * hour)) forecast72h += value;
            });

            return { observed24h, observed72h, forecast24h, forecast72h };
        }

        function normalizeMunicipalityName(value) {
            return String(value || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim()
                .toLowerCase();
        }

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = String(value ?? '');
            return div.innerHTML;
        }

        function getRainStatus(rain24h, forecast72h) {
            const combined = Number(rain24h || 0) + Number(forecast72h || 0);
            if (rain24h >= 100 || combined >= 180) return { label: 'Muito alta', color: '#7b1fa2' };
            if (rain24h >= 60 || combined >= 120) return { label: 'Alta', color: '#d73027' };
            if (rain24h >= 30 || combined >= 70) return { label: 'Atenção', color: '#fc8d59' };
            if (rain24h >= 10 || combined >= 35) return { label: 'Moderada', color: '#fee08b' };
            return { label: 'Baixa', color: '#1a9850' };
        }
        
        // Sistema de gerenciamento de APIs e cache
        class WeatherManager {
            constructor() {
                this.apiCallCount = parseInt(localStorage.getItem('openmeteo_calls') || '0');
                this.lastResetDate = localStorage.getItem('openmeteo_reset') || new Date().toDateString();
                this.weatherCache = new Map();
                this.CACHE_DURATION = 30 * 60 * 1000; // 30 minutos
                this.API_LIMIT = 9500; // Limite seguro (500 calls de margem)
                this.BATCH_SIZE = 50; // Processa 50 municípios por vez
                this.DELAY_BETWEEN_BATCHES = 2000; // 2 segundos entre lotes
                
                this.resetDailyCountIfNeeded();
                this.updateApiStatus();
            }
            
            resetDailyCountIfNeeded() {
                const today = new Date().toDateString();
                if (this.lastResetDate !== today) {
                    this.apiCallCount = 0;
                    this.lastResetDate = today;
                    localStorage.setItem('openmeteo_calls', '0');
                    localStorage.setItem('openmeteo_reset', today);
                }
            }
            
            canMakeApiCall() {
                return this.apiCallCount < this.API_LIMIT;
            }
            
            incrementApiCall() {
                this.apiCallCount++;
                localStorage.setItem('openmeteo_calls', this.apiCallCount.toString());
                this.updateApiStatus();
            }
            
            updateApiStatus() {
                let statusEl = document.getElementById('api-status');
                if (!statusEl) {
                    statusEl = document.createElement('div');
                    statusEl.id = 'api-status';
                    statusEl.className = 'api-status';
                    document.body.appendChild(statusEl);
                }
                
                const remaining = this.API_LIMIT - this.apiCallCount;
                if (remaining < 500) {
                    statusEl.className = 'api-status error';
                    statusEl.textContent = `API: ${remaining} calls restantes`;
                } else if (remaining < 1000) {
                    statusEl.className = 'api-status warning';
                    statusEl.textContent = `API: ${remaining} calls restantes`;
                } else {
                    statusEl.style.display = 'none';
                }
            }
            
            getCachedWeather(lat, lng, includeHydrology = false) {
                const key = `${lat.toFixed(3)}_${lng.toFixed(3)}_${includeHydrology ? 'hydro' : 'current'}`;
                const cached = this.weatherCache.get(key);
                if (cached && (Date.now() - cached.timestamp) < this.CACHE_DURATION) {
                    return cached.data;
                }
                return null;
            }
            
            setCachedWeather(lat, lng, data, includeHydrology = false) {
                const key = `${lat.toFixed(3)}_${lng.toFixed(3)}_${includeHydrology ? 'hydro' : 'current'}`;
                this.weatherCache.set(key, {
                    data: data,
                    timestamp: Date.now()
                });
            }
            
            async fetchWeatherData(lat, lng, includeHydrology = false) {
                // Verificar cache primeiro
                const cached = this.getCachedWeather(lat, lng, includeHydrology);
                if (cached) {
                    return cached;
                }
                
                // Verificar limite de API
                if (!this.canMakeApiCall()) {
                    throw new Error('Limite diário de API excedido');
                }
                
                let url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,precipitation,wind_speed_10m,wind_direction_10m&timezone=America/Sao_Paulo`;
                if (includeHydrology) {
                    url += '&hourly=precipitation&past_days=3&forecast_days=3';
                }
                
                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    
                    const data = await response.json();
                    this.incrementApiCall();
                    
                    const weatherData = {
                        temperature: data.current.temperature_2m,
                        precipitation: data.current.precipitation,
                        windSpeed: data.current.wind_speed_10m,
                        windDirection: data.current.wind_direction_10m,
                        rain: includeHydrology ? calculateRainMetrics(data.hourly) : null
                    };
                    
                    this.setCachedWeather(lat, lng, weatherData, includeHydrology);
                    return weatherData;
                    
                } catch (error) {
                    console.error('Erro ao buscar dados meteorológicos:', error);
                    throw error;
                }
            }
        }
        
        // Função para calcular centroide de um polígono
        function calculateCentroid(coordinates) {
            if (!coordinates || !coordinates[0]) return null;
            
            const polygon = coordinates[0]; // Primeiro anel do polígono
            let totalLat = 0, totalLng = 0;
            
            for (let i = 0; i < polygon.length; i++) {
                totalLng += polygon[i][0];
                totalLat += polygon[i][1];
            }
            
            return [
                totalLat / polygon.length,
                totalLng / polygon.length
            ];
        }
        
        // Função para determinar cor baseada na temperatura
        function getTemperatureColor(temp) {
            if (temp < 10) return '#0066ff'; // Azul (frio)
            if (temp < 20) return '#00ccff'; // Azul claro
            if (temp < 25) return '#00ff66'; // Verde
            if (temp < 30) return '#ffcc00'; // Amarelo
            if (temp < 35) return '#ff6600'; // Laranja
            return '#ff0000'; // Vermelho (quente)
        }
        
        // Função para criar marcador meteorológico
        function createWeatherMarker(lat, lng, weatherData, cityName) {
            const temp = weatherData.temperature;
            const color = getTemperatureColor(temp);
            
            const icon = L.divIcon({
                className: '',
                html: `<div class="weather-indicator" style="border-color:${color};color:${color}">${Math.round(temp)}°</div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
            
            const marker = L.marker([lat, lng], { icon: icon });
            
            const popupContent = `
                <div class="weather-popup">
                    <h3>${escapeHtml(cityName)}</h3>
                    <div class="weather-current">
                        <div class="weather-metric">
                            <span>🌡️ Temperatura:</span>
                            <span class="weather-value">${temp}°C</span>
                        </div>
                        <div class="weather-metric">
                            <span>🌧️ Precipitação:</span>
                            <span class="weather-value">${weatherData.precipitation || 0} mm</span>
                        </div>
                        <div class="weather-metric">
                            <span>💨 Vento:</span>
                            <span class="weather-value">${weatherData.windSpeed} km/h</span>
                        </div>
                        <div class="weather-metric">
                            <span>🧭 Direção:</span>
                            <span class="weather-value">${weatherData.windDirection}°</span>
                        </div>
                    </div>
                    <small style="color: #666;">Dados: Open-Meteo API</small>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            return marker;
        }

        function createHydroPopup(cityName, basinPercentage, weatherData) {
            const rain = weatherData && weatherData.rain;
            if (!rain) {
                return `<div class="weather-popup"><h3>${escapeHtml(cityName)}</h3>
                    <p><strong>Bacia Taquari-Antas:</strong> ${basinPercentage}% da área municipal</p>
                    <p>Dados hidroclimáticos ainda estão sendo carregados.</p></div>`;
            }

            const status = getRainStatus(rain.observed24h, rain.forecast72h);
            return `
                <div class="weather-popup">
                    <h3>${escapeHtml(cityName)}</h3>
                    <div class="weather-current">
                        <div class="weather-metric"><span>Área inserida na bacia:</span><span class="weather-value">${basinPercentage}%</span></div>
                        <div class="weather-metric"><span>Chuva observada 24 h:</span><span class="weather-value">${rain.observed24h.toFixed(1)} mm</span></div>
                        <div class="weather-metric"><span>Chuva observada 72 h:</span><span class="weather-value">${rain.observed72h.toFixed(1)} mm</span></div>
                        <div class="weather-metric"><span>Previsão 24 h:</span><span class="weather-value">${rain.forecast24h.toFixed(1)} mm</span></div>
                        <div class="weather-metric"><span>Previsão 72 h:</span><span class="weather-value">${rain.forecast72h.toFixed(1)} mm</span></div>
                        <div class="weather-metric"><span>Situação meteorológica:</span><span class="weather-value" style="color:${status.color}">${status.label}</span></div>
                    </div>
                    <small>Estimativa Open-Meteo no ponto central do município. Não representa nível ou vazão do rio.</small>
                </div>`;
        }
        
        $(document).ready(async function() {
            restoreHeaderState();

            const weatherManager = new WeatherManager();
            const map = L.map('map').setView([-30.0346, -51.2177], 7);
            const weatherMarkers = L.layerGroup().addTo(map);
            const municipalityLayers = new Map();
            const hydroResults = new Map();
            let geojsonLayer;
            let riverLayer;
            let basinBounds;
            let basinMunicipalities = new Map();
            let basinFeatureCount = 0;
            let hydroMode = false;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            const legend = L.control({ position: 'bottomleft' });
            legend.onAdd = function() {
                const div = L.DomUtil.create('div', 'rain-legend');
                div.innerHTML = '<strong>Chuva observada 24 h</strong><br>' +
                    '<i style="background:#1a9850"></i>Baixa (&lt; 10 mm)<br>' +
                    '<i style="background:#fee08b"></i>Moderada (10–30 mm)<br>' +
                    '<i style="background:#fc8d59"></i>Atenção (30–60 mm)<br>' +
                    '<i style="background:#d73027"></i>Alta (60–100 mm)<br>' +
                    '<i style="background:#7b1fa2"></i>Muito alta (≥ 100 mm)';
                return div;
            };

            try {
                const [municipalityData, basinData, riverData] = await Promise.all([
                    $.getJSON('geojs-43-mun.json'),
                    $.getJSON('taquari-antas-municipios.json'),
                    $.getJSON('rios-taquari-antas.geojson')
                ]);

                Object.entries(basinData.municipios).forEach(([name, percentage]) => {
                    basinMunicipalities.set(normalizeMunicipalityName(name), Number(percentage));
                });
                const availableNames = new Set(
                    municipalityData.features.map(feature => normalizeMunicipalityName(feature.properties.name))
                );
                basinFeatureCount = [...basinMunicipalities.keys()].filter(key => availableNames.has(key)).length;

                geojsonLayer = L.geoJSON(municipalityData, {
                    style: featureStyle,
                    onEachFeature: function(feature, layer) {
                        const cityName = feature.properties && feature.properties.name;
                        if (!cityName) return;
                        const key = normalizeMunicipalityName(cityName);
                        const basinPercentage = basinMunicipalities.get(key);
                        municipalityLayers.set(key, layer);

                        layer.bindTooltip(cityName, { permanent: false, direction: 'center' });
                        if (basinPercentage !== undefined) {
                            layer.bindPopup(createHydroPopup(cityName, basinPercentage, null), {
                                maxWidth: 320,
                                autoPanPaddingTopLeft: [45, 85],
                                autoPanPaddingBottomRight: [25, 45]
                            });
                            if (!basinBounds) basinBounds = layer.getBounds();
                            else basinBounds.extend(layer.getBounds());
                        }
                    }
                }).addTo(map);

                map.createPane('rivers');
                map.getPane('rivers').style.zIndex = 450;
                riverLayer = L.geoJSON(riverData, {
                    pane: 'rivers',
                    style: feature => ({
                        color: feature.properties.name === 'Rio Taquari' ||
                               feature.properties.name === 'Rio das Antas'
                            ? '#003f88'
                            : '#0077b6',
                        weight: feature.properties.name === 'Rio Taquari' ||
                                feature.properties.name === 'Rio das Antas'
                            ? 4
                            : 2.5,
                        opacity: 0.95
                    }),
                    onEachFeature: function(feature, layer) {
                        const riverName = feature.properties && feature.properties.name;
                        if (!riverName) return;
                        layer.bindTooltip(escapeHtml(riverName), {
                            permanent: true,
                            direction: 'center',
                            className: 'river-label'
                        });
                        layer.bindPopup(`<div class="weather-popup"><h3>${escapeHtml(riverName)}</h3>
                            <p>Curso d’água integrante da Bacia Hidrográfica Taquari-Antas.</p>
                            <small>Fonte cartográfica: SEMA/FEPAM, escala 1:25.000.</small></div>`, {
                            maxWidth: 300
                        });
                    }
                });

                map.fitBounds(geojsonLayer.getBounds());
                bindViewButtons();
                loadWeatherDataForMunicipalities(municipalityData.features);
            } catch (error) {
                console.error('Erro ao carregar os arquivos geográficos:', error);
                showMessage('Não foi possível carregar os arquivos do mapa.', '#b71c1c');
            }

            function featureStyle(feature) {
                const key = normalizeMunicipalityName(feature.properties && feature.properties.name);
                const inBasin = basinMunicipalities.has(key);
                const result = hydroResults.get(key);

                if (!hydroMode) {
                    return { color: '#0066cc', weight: 1, fillOpacity: 0.1, fillColor: '#4da6ff' };
                }
                if (!inBasin) {
                    return { color: '#b0bec5', weight: 0.5, fillOpacity: 0.02, fillColor: '#eceff1' };
                }

                const rain = result && result.rain;
                if (!rain) {
                    return { color: '#003b5c', weight: 1.5, fillOpacity: 0.5, fillColor: '#90a4ae' };
                }
                const status = getRainStatus(rain ? rain.observed24h : 0, rain ? rain.forecast72h : 0);
                return { color: '#003b5c', weight: 1.5, fillOpacity: 0.72, fillColor: status.color };
            }

            function bindViewButtons() {
                $('#view-weather').on('click', function() {
                    hydroMode = false;
                    $(this).addClass('active');
                    $('#view-hydro').removeClass('active');
                    $('#hydro-summary').removeClass('visible');
                    if (!map.hasLayer(weatherMarkers)) weatherMarkers.addTo(map);
                    if (riverLayer && map.hasLayer(riverLayer)) map.removeLayer(riverLayer);
                    if (legend._map) legend.remove();
                    geojsonLayer.setStyle(featureStyle);
                    map.fitBounds(geojsonLayer.getBounds());
                });

                $('#view-hydro').on('click', function() {
                    hydroMode = true;
                    $(this).addClass('active');
                    $('#view-weather').removeClass('active');
                    $('#hydro-summary').addClass('visible');
                    if (map.hasLayer(weatherMarkers)) map.removeLayer(weatherMarkers);
                    if (riverLayer && !map.hasLayer(riverLayer)) riverLayer.addTo(map);
                    if (!legend._map) legend.addTo(map);
                    geojsonLayer.setStyle(featureStyle);
                    if (basinBounds) map.fitBounds(basinBounds, { padding: [15, 15] });
                    updateHydroSummary();
                });
            }

            async function loadWeatherDataForMunicipalities(features) {
                const loadingIndicator = document.createElement('div');
                loadingIndicator.id = 'loading-indicator';
                loadingIndicator.style.cssText = 'position:fixed;top:70px;right:10px;background:#0066cc;color:white;padding:10px;border-radius:5px;z-index:1100';
                document.body.appendChild(loadingIndicator);

                let processedCount = 0;
                let successCount = 0;

                // Prioriza municípios da bacia para disponibilizar a análise mais cedo.
                const orderedFeatures = [...features].sort((a, b) => {
                    const aBasin = basinMunicipalities.has(normalizeMunicipalityName(a.properties.name)) ? 1 : 0;
                    const bBasin = basinMunicipalities.has(normalizeMunicipalityName(b.properties.name)) ? 1 : 0;
                    return bBasin - aBasin;
                });

                for (let i = 0; i < orderedFeatures.length; i += weatherManager.BATCH_SIZE) {
                    const batch = orderedFeatures.slice(i, i + weatherManager.BATCH_SIZE);
                    if (!weatherManager.canMakeApiCall()) break;

                    await Promise.allSettled(batch.map(async feature => {
                        const cityName = feature.properties.name;
                        const key = normalizeMunicipalityName(cityName);
                        const basinPercentage = basinMunicipalities.get(key);
                        const includeHydrology = basinPercentage !== undefined;

                        try {
                            // O centro dos limites funciona para Polygon e MultiPolygon.
                            const center = L.geoJSON(feature).getBounds().getCenter();
                            const weatherData = await weatherManager.fetchWeatherData(
                                center.lat,
                                center.lng,
                                includeHydrology
                            );

                            const marker = createWeatherMarker(center.lat, center.lng, weatherData, cityName);
                            weatherMarkers.addLayer(marker);

                            if (includeHydrology) {
                                hydroResults.set(key, weatherData);
                                const layer = municipalityLayers.get(key);
                                if (layer) {
                                    layer.bindPopup(createHydroPopup(cityName, basinPercentage, weatherData), {
                                        maxWidth: 320,
                                        autoPanPaddingTopLeft: [45, 85],
                                        autoPanPaddingBottomRight: [25, 45]
                                    });
                                    if (hydroMode) layer.setStyle(featureStyle(feature));
                                }
                                updateHydroSummary();
                            }
                            successCount++;
                        } catch (error) {
                            console.warn(`Erro ao carregar dados para ${cityName}:`, error.message);
                        } finally {
                            processedCount++;
                            loadingIndicator.textContent =
                                `Carregando ${processedCount}/${orderedFeatures.length} — ${successCount} disponíveis`;
                        }
                    }));

                    if (i + weatherManager.BATCH_SIZE < orderedFeatures.length) {
                        await new Promise(resolve => setTimeout(resolve, weatherManager.DELAY_BETWEEN_BATCHES));
                    }
                }

                loadingIndicator.remove();
                showMessage(`✅ ${successCount} municípios carregados`, '#1b5e20');
            }

            function updateHydroSummary() {
                let weightTotal = 0;
                let observedWeighted = 0;
                let forecastWeighted = 0;
                let max24 = -1;
                let maxCity = '';

                hydroResults.forEach((weatherData, key) => {
                    if (!weatherData.rain) return;
                    const weight = basinMunicipalities.get(key) || 0;
                    weightTotal += weight;
                    observedWeighted += weatherData.rain.observed24h * weight;
                    forecastWeighted += weatherData.rain.forecast72h * weight;
                    if (weatherData.rain.observed24h > max24) {
                        max24 = weatherData.rain.observed24h;
                        const layer = municipalityLayers.get(key);
                        maxCity = layer && layer.feature ? layer.feature.properties.name : '';
                    }
                });

                const avg24 = weightTotal ? observedWeighted / weightTotal : 0;
                const avgForecast = weightTotal ? forecastWeighted / weightTotal : 0;
                const status = getRainStatus(avg24, avgForecast);

                $('#hydro-count').text(`${hydroResults.size}/${basinFeatureCount}`);
                $('#hydro-rain-24').text(`${avg24.toFixed(1)} mm`);
                $('#hydro-max-24').text(max24 >= 0 ? `${max24.toFixed(1)} mm${maxCity ? ` — ${maxCity}` : ''}` : '-- mm');
                $('#hydro-forecast-72').text(`${avgForecast.toFixed(1)} mm`);
                $('#hydro-status').text(status.label).css('color', status.color);
            }

            function showMessage(text, color) {
                const element = document.createElement('div');
                element.style.cssText = `position:fixed;bottom:10px;right:10px;background:${color};color:white;padding:10px;border-radius:5px;z-index:1100`;
                element.textContent = text;
                document.body.appendChild(element);
                setTimeout(() => element.remove(), 5000);
            }
        });
    </script>
</body>
</html>
