<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Projeto de Monitoramento Da Bacia Do Rio Taquari</title>
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

    <!-- Chart.js para o gráfico de precipitação da bacia -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
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
            padding-top: 30px; /* Espaço apenas para a top-bar */
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

        .hydro-chart-container {
            display: none;
            background: rgba(255,255,255,0.97);
            border-radius: 10px;
            padding: 16px;
            margin: 0 0 15px;
            min-height: 310px;
            position: relative;
            z-index: 2;
            box-sizing: border-box;
        }

        .hydro-chart-container.visible {
            display: block;
        }

        .hydro-chart-title {
            color: #004080;
            font-size: 1.05rem;
            margin: 0 0 4px;
            text-align: center;
        }

        .hydro-chart-description {
            color: #555;
            font-size: .82rem;
            margin: 0 0 12px;
            text-align: center;
        }

        .hydro-chart-wrapper {
            height: 245px;
            position: relative;
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

            .hydro-chart-container {
                min-height: 285px;
                padding: 10px 6px;
            }

            .hydro-chart-wrapper {
                height: 225px;
            }

            .hydro-chart-title {
                font-size: .95rem;
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
            <?=$this->Html->image('favicon.png', ['alt' => 'favicon', 'width'=>'100em'])?>
            <span>Monitoramento da Bacia do Rio Taquari - <a href="https://www.adatecnologia.com" target="_blank">ADA Tecnologia LTDA</a></span>
        </div>
    </div>

    <main>
        <section id="introducao">
            <h2>Bacia do rio Taquari</h2>
            <p><strong>Confira abaixo nosso mapa interativo em tempo real com os dados meteorológicos</strong></p>
        </section>

        <section id="mapa-rs">
            <div class="map-container">
                <h2 class="map-title">Monitoramento Meteorológico em Tempo Real</h2>
                <p class="map-subtitle">Dados meteorológicos atualizados</p>

                <div id="hydro-summary" class="hydro-summary visible" aria-live="polite">
                    <div class="hydro-card">Municípios analisados<strong id="hydro-count">0</strong></div>
                    <div class="hydro-card">Média ponderada 24 h<strong id="hydro-rain-24">-- mm</strong></div>
                    <div class="hydro-card">Maior acumulado 24 h<strong id="hydro-max-24">-- mm</strong></div>
                    <div class="hydro-card">Previsão média 72 h<strong id="hydro-forecast-72">-- mm</strong></div>
                    <div class="hydro-card">Situação predominante<strong id="hydro-status">Calculando</strong></div>
                </div>

                <div id="hydro-chart-container" class="hydro-chart-container visible">
                    <h3 class="hydro-chart-title">Precipitação média na Bacia Taquari-Antas</h3>
                    <p class="hydro-chart-description">
                        Média ponderada dos municípios já carregados, em milímetros acumulados por janela.
                    </p>
                    <div class="hydro-chart-wrapper">
                        <canvas id="hydro-rain-chart" role="img" aria-label="Gráfico em linha da precipitação média observada e prevista para a Bacia Taquari-Antas"></canvas>
                    </div>
                </div>

                <div id="map"></div>


                <p style="margin-top: 20px; font-size: 0.9em; color: rgba(255,255,255,0.8); text-align: center; position: relative; z-index: 1;">
                    <strong>💡 Como usar:</strong> a página inicia na precipitação da Bacia Taquari-Antas. Clique em um município para consultar seus acumulados ou escolha “Clima em todo o RS” para mudar a visualização.
                </p>
                <div class="hydro-toolbar">
                    <button type="button" id="view-hydro" class="active">🌊 Precipitação da Bacia Taquari-Antas</button>
                    <button type="button" id="view-weather">🌦️ Clima em todo o RS</button>
                    <p class="hydro-note">
                        A análise hidroclimática utiliza chuva observada e prevista. Ela é indicativa e não substitui alertas da Defesa Civil, SEMA, ANA ou municípios.
                    </p>
                </div>
            </div>
        </section>

    </main>

    <footer>
        &copy; ADA Tecnologia Desenvolvimentos LTDA - Todos os direitos reservados.
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

                // Esta versão da página não possui o cabeçalho retrátil.
                if (!headerNavContainer || !toggleText) return;

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
            const weatherMarkers = L.layerGroup();
            const municipalityLayers = new Map();
            const hydroResults = new Map();
            let rainChart;
            let geojsonLayer;
            let riverLayer;
            let basinBounds;
            let basinMunicipalities = new Map();
            let basinFeatureCount = 0;
            let hydroMode = true;

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

            initializeRainChart();

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

                bindViewButtons();
                activateHydroView();
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
                    activateWeatherView();
                });

                $('#view-hydro').on('click', function() {
                    activateHydroView();
                });
            }

            function activateWeatherView() {
                hydroMode = false;
                $('#view-weather').addClass('active');
                $('#view-hydro').removeClass('active');
                $('#hydro-summary, #hydro-chart-container').removeClass('visible');
                if (!map.hasLayer(weatherMarkers)) weatherMarkers.addTo(map);
                if (riverLayer && map.hasLayer(riverLayer)) map.removeLayer(riverLayer);
                if (legend._map) legend.remove();
                geojsonLayer.setStyle(featureStyle);
                map.fitBounds(geojsonLayer.getBounds());
            }

            function activateHydroView() {
                hydroMode = true;
                $('#view-hydro').addClass('active');
                $('#view-weather').removeClass('active');
                $('#hydro-summary, #hydro-chart-container').addClass('visible');
                if (map.hasLayer(weatherMarkers)) map.removeLayer(weatherMarkers);
                if (riverLayer && !map.hasLayer(riverLayer)) riverLayer.addTo(map);
                if (!legend._map) legend.addTo(map);
                geojsonLayer.setStyle(featureStyle);
                if (basinBounds) map.fitBounds(basinBounds, { padding: [15, 15] });
                updateHydroSummary();
                if (rainChart) rainChart.resize();
            }

            function initializeRainChart() {
                const canvas = document.getElementById('hydro-rain-chart');
                if (!canvas || typeof Chart === 'undefined') {
                    console.warn('Chart.js não foi carregado; o gráfico não será exibido.');
                    return;
                }

                rainChart = new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: ['Observado 72 h', 'Observado 24 h', 'Previsão 24 h', 'Previsão 72 h'],
                        datasets: [{
                            label: 'Precipitação média',
                            data: [null, null, null, null],
                            borderColor: '#0066cc',
                            backgroundColor: 'rgba(0, 102, 204, 0.14)',
                            pointBackgroundColor: '#004080',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            borderWidth: 3,
                            tension: 0.28,
                            fill: true,
                            spanGaps: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: context => `${context.dataset.label}: ${Number(context.parsed.y).toFixed(1)} mm`
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Janela de acumulação'
                                },
                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: false,
                                    font: context => ({
                                        size: context.chart.width < 500 ? 10 : 12
                                    })
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Precipitação média (mm)'
                                },
                                ticks: {
                                    callback: value => `${value} mm`
                                }
                            }
                        }
                    }
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
                let observed24Weighted = 0;
                let observed72Weighted = 0;
                let forecast24Weighted = 0;
                let forecast72Weighted = 0;
                let max24 = -1;
                let maxCity = '';

                hydroResults.forEach((weatherData, key) => {
                    if (!weatherData.rain) return;
                    const weight = basinMunicipalities.get(key) || 0;
                    weightTotal += weight;
                    observed24Weighted += weatherData.rain.observed24h * weight;
                    observed72Weighted += weatherData.rain.observed72h * weight;
                    forecast24Weighted += weatherData.rain.forecast24h * weight;
                    forecast72Weighted += weatherData.rain.forecast72h * weight;
                    if (weatherData.rain.observed24h > max24) {
                        max24 = weatherData.rain.observed24h;
                        const layer = municipalityLayers.get(key);
                        maxCity = layer && layer.feature ? layer.feature.properties.name : '';
                    }
                });

                const avgObserved24 = weightTotal ? observed24Weighted / weightTotal : 0;
                const avgObserved72 = weightTotal ? observed72Weighted / weightTotal : 0;
                const avgForecast24 = weightTotal ? forecast24Weighted / weightTotal : 0;
                const avgForecast72 = weightTotal ? forecast72Weighted / weightTotal : 0;
                const status = getRainStatus(avgObserved24, avgForecast72);

                $('#hydro-count').text(`${hydroResults.size}/${basinFeatureCount}`);
                $('#hydro-rain-24').text(`${avgObserved24.toFixed(1)} mm`);
                $('#hydro-max-24').text(max24 >= 0 ? `${max24.toFixed(1)} mm${maxCity ? ` — ${maxCity}` : ''}` : '-- mm');
                $('#hydro-forecast-72').text(`${avgForecast72.toFixed(1)} mm`);
                $('#hydro-status').text(status.label).css('color', status.color);

                if (rainChart) {
                    rainChart.data.datasets[0].data = weightTotal
                        ? [avgObserved72, avgObserved24, avgForecast24, avgForecast72]
                        : [null, null, null, null];
                    rainChart.update('none');
                }
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
