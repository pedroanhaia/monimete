<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análise histórica — Bacia Taquari-Antas</title>
    <link rel="icon" type="image/png" href="<?= $this->Url->image('favicon.png') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --blue: #004080;
            --blue-light: #0066cc;
            --purple: #746ed6;
            --background: #f4f6fb;
            --card: #ffffff;
            --text: #263238;
            --muted: #64748b;
            --border: #dbe3ef;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: var(--text);
            background: var(--background);
        }

        .analysis-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px max(16px, calc((100% - 1200px) / 2));
            color: #fff;
            background: linear-gradient(135deg, #004080, #4939a8);
        }

        .analysis-brand { display: flex; align-items: center; gap: 12px; }
        .analysis-brand img { width: 52px; height: 52px; border-radius: 50%; }
        .analysis-brand h1 { margin: 0; font-size: clamp(1.1rem, 3vw, 1.65rem); }
        .analysis-brand small { display: block; margin-top: 3px; opacity: .82; }
        .home-link {
            color: #fff;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.6);
            border-radius: 999px;
            padding: 9px 15px;
            white-space: nowrap;
        }

        .analysis-main { max-width: 1200px; margin: 0 auto; padding: 22px 16px 40px; }
        .panel {
            margin-bottom: 18px;
            padding: 18px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 7px 22px rgba(23, 43, 77, .07);
        }

        .panel h2 { margin: 0 0 7px; color: var(--blue); font-size: 1.2rem; }
        .panel-description { margin: 0 0 16px; color: var(--muted); line-height: 1.45; }
        .filters { display: grid; grid-template-columns: minmax(260px, 2fr) minmax(150px, 1fr) auto; gap: 12px; align-items: end; }
        .field label { display: block; margin-bottom: 5px; color: #475569; font-size: .82rem; font-weight: 700; }
        .field select, .field input {
            width: 100%; min-height: 42px; padding: 8px 10px;
            border: 1px solid #b8c7da; border-radius: 8px; background: #fff;
        }
        .load-button {
            min-height: 42px; padding: 9px 18px; border: 0; border-radius: 8px;
            color: #fff; background: var(--blue-light); font-weight: 700; cursor: pointer;
        }

        .status-message { display: none; margin: 0 0 18px; padding: 12px; border-radius: 9px; }
        .status-message.visible { display: block; }
        .status-message.loading { color: #164e63; background: #cffafe; }
        .status-message.error { color: #991b1b; background: #fee2e2; }

        .snapshot-cards, .confidence-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        .metric-card { padding: 13px 10px; text-align: center; border: 1px solid #e1e7f0; border-radius: 10px; background: #f8fafc; }
        .metric-card span { display: block; min-height: 2.1em; color: #64748b; font-size: .78rem; }
        .metric-card strong { display: block; margin-top: 5px; color: var(--blue); font-size: 1.08rem; }

        .chart-wrap { position: relative; height: 360px; width: 100%; }
        .chart-wrap.comparison { height: 410px; }
        .chart-scroll { width: 100%; overflow-x: auto; }
        .chart-scroll-inner { position: relative; width: 100%; min-width: 720px; height: 410px; }
        .comparison-summary { margin: 0 0 9px; color: var(--muted); font-size: .8rem; }
        .chart-empty { display: none; padding: 26px 16px; color: var(--muted); text-align: center; border: 1px dashed var(--border); border-radius: 9px; background: #f8fafc; }
        .chart-empty.visible { display: block; }

        .confidence-note {
            margin: 14px 0 0; padding: 12px; color: #5b4a10;
            background: #fff8db; border-left: 4px solid #eab308; border-radius: 6px;
            font-size: .85rem; line-height: 1.45;
        }

        .timeline-box { margin-top: 14px; padding: 15px 14px 10px; border: 1px solid var(--border); border-radius: 12px; background: #f8fafc; }
        .timeline-labels { display: flex; justify-content: space-between; gap: 12px; color: var(--muted); font-size: .78rem; }
        .timeline-labels strong { color: var(--blue); text-align: center; }
        .timeline-range {
            width: 100%; height: 34px; margin: 8px 0 2px; padding: 0;
            accent-color: var(--blue-light); cursor: pointer;
        }
        .timeline-hint { margin: 4px 0 0; color: var(--muted); font-size: .76rem; text-align: center; }
        .historical-bars { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .historical-bars .panel { min-width: 0; }
        .historical-chart { position: relative; height: 350px; }

        .table-tools { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 10px; }
        .table-tools input { min-height: 38px; width: min(320px, 100%); padding: 8px 10px; border: 1px solid #b8c7da; border-radius: 8px; }
        .table-wrap { overflow: auto; max-height: 650px; border: 1px solid var(--border); border-radius: 9px; }
        table { width: 100%; border-collapse: collapse; min-width: 1050px; font-size: .82rem; }
        th, td { padding: 9px 10px; border-bottom: 1px solid #e8edf4; text-align: right; white-space: nowrap; }
        th { position: sticky; top: 0; z-index: 1; color: #fff; background: var(--blue); }
        th:first-child, td:first-child, th:nth-child(2), td:nth-child(2) { text-align: left; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tfoot td { position: sticky; bottom: 0; font-weight: 700; background: #e7f1ff; }

        @media (max-width: 760px) {
            .analysis-header { align-items: flex-start; }
            .analysis-brand img { width: 44px; height: 44px; }
            .analysis-brand small { display: none; }
            .home-link { padding: 8px 11px; font-size: .8rem; }
            .analysis-main { padding: 14px 8px 30px; }
            .panel { padding: 13px 9px; }
            .filters { grid-template-columns: 1fr 1fr; }
            .field:first-child { grid-column: 1 / -1; }
            .load-button { width: 100%; }
            .chart-wrap { height: 330px; }
            .historical-bars { grid-template-columns: 1fr; gap: 0; }
            .table-tools { align-items: stretch; flex-direction: column; }
            .table-tools input { width: 100%; max-width: none; }
        }
    </style>
</head>
<body>
    <header class="analysis-header">
        <div class="analysis-brand">
            <?= $this->Html->image('favicon.png', ['alt' => 'ADA Tecnologia']) ?>
            <div>
                <h1>Análise histórica da Bacia Taquari-Antas</h1>
                <small>Precipitação armazenada, previsão, observado posterior e margem de confiança</small>
            </div>
        </div>
        <a class="home-link" href="<?= $this->Url->build('/') ?>">← Voltar ao mapa</a>
    </header>

    <main class="analysis-main">
        <section class="panel" aria-labelledby="filters-title">
            <h2 id="filters-title">Período de análise</h2>
            <p class="panel-description">Selecione uma data e hora realmente existentes no histórico. O primeiro gráfico e a tabela serão reconstruídos como se aquele fosse o momento atual.</p>
            <div class="filters">
                <div class="field">
                    <label for="snapshot-time">Data e hora dos dados salvos</label>
                    <select id="snapshot-time"><option>Carregando histórico...</option></select>
                </div>
                <div class="field">
                    <label for="history-days">Janela histórica</label>
                    <select id="history-days">
                        <option value="7">7 dias</option>
                        <option value="14">14 dias</option>
                        <option value="3">3 dias</option>
                    </select>
                </div>
                <button type="button" id="load-analysis" class="load-button">Atualizar análise</button>
            </div>
            <div class="timeline-box">
                <div class="timeline-labels">
                    <span id="timeline-first">Mais antigo</span>
                    <strong id="timeline-current">--</strong>
                    <span id="timeline-last">Mais recente</span>
                </div>
                <input id="history-timeline" class="timeline-range" type="range" min="0" max="0" value="0" step="1" list="timeline-points" aria-label="Linha histórica dos registros salvos">
                <datalist id="timeline-points"></datalist>
                <p class="timeline-hint">Deslize pela linha histórica para escolher outro registro salvo.</p>
            </div>
        </section>

        <div id="analysis-status" class="status-message" role="status" aria-live="polite"></div>

        <section class="panel" aria-labelledby="snapshot-title">
            <h2 id="snapshot-title">Precipitação da bacia no instante selecionado</h2>
            <p id="snapshot-description" class="panel-description">Aguardando seleção do histórico.</p>
            <div class="snapshot-cards">
                <div class="metric-card"><span>Municípios encontrados</span><strong id="snapshot-count">--</strong></div>
                <div class="metric-card"><span>Peso total utilizado</span><strong id="snapshot-weight">--</strong></div>
                <div class="metric-card"><span>Precipitação atual média</span><strong id="snapshot-current">-- mm</strong></div>
            </div>
            <div class="chart-wrap"><canvas id="snapshot-chart"></canvas></div>
        </section>

        <div class="historical-bars">
            <section class="panel" aria-labelledby="previous24-title">
                <h2 id="previous24-title">Registro anterior mais próximo de 24 horas</h2>
                <p id="previous24-description" class="panel-description">Aguardando seleção do histórico.</p>
                <div class="historical-chart"><canvas id="previous24-chart"></canvas></div>
            </section>
            <section class="panel" aria-labelledby="previous72-title">
                <h2 id="previous72-title">Registro anterior mais próximo de 72 horas</h2>
                <p id="previous72-description" class="panel-description">Aguardando seleção do histórico.</p>
                <div class="historical-chart"><canvas id="previous72-chart"></canvas></div>
            </section>
        </div>

        <section class="panel" aria-labelledby="comparison-title">
            <h2 id="comparison-title">Previsões anteriores × observado na data selecionada</h2>
            <p class="panel-description">Compara a previsão de 24 h do registro próximo de T−24 h e a previsão de 72 h do registro próximo de T−72 h com os respectivos acumulados observados no instante selecionado.</p>
            <div class="confidence-cards">
                <div class="metric-card"><span>Municípios pareados (72 h)</span><strong id="confidence-count">--</strong></div>
                <div class="metric-card"><span>Erro absoluto médio (MAE)</span><strong id="confidence-mae">-- mm</strong></div>
                <div class="metric-card"><span>RMSE</span><strong id="confidence-rmse">-- mm</strong></div>
                <div class="metric-card"><span>Viés médio</span><strong id="confidence-bias">-- mm</strong></div>
                <div class="metric-card"><span>Margem 95% do erro médio</span><strong id="confidence-margin">-- mm</strong></div>
                <div class="metric-card"><span>Acerto da faixa de chuva</span><strong id="confidence-category">--%</strong></div>
            </div>
            <p id="comparison-summary" class="comparison-summary">Aguardando dados da série.</p>
            <div class="chart-scroll">
                <div id="comparison-chart-inner" class="chart-scroll-inner">
                    <canvas id="comparison-chart"></canvas>
                </div>
            </div>
            <div id="comparison-empty" class="chart-empty" role="status"></div>
            <p class="confidence-note">
                Os indicadores de confiança utilizam o pareamento de 72 horas entre os mesmos municípios. O “observado” é o acumulado armazenado da Open-Meteo na data selecionada; para validação hidrológica oficial, recomenda-se confrontar também com pluviômetros da ANA, INMET, CEMADEN ou rede estadual.
            </p>
        </section>

        <section class="panel" aria-labelledby="municipality-title">
            <h2 id="municipality-title">Municípios utilizados no cálculo da precipitação atual</h2>
            <p class="panel-description">A contribuição é calculada por <code>precipitação atual × percentual da área municipal na bacia ÷ soma dos pesos</code>.</p>
            <div class="table-tools">
                <strong id="table-summary">Nenhum dado carregado</strong>
                <input id="municipality-filter" type="search" placeholder="Filtrar município..." autocomplete="off">
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Município</th>
                            <th>Registro</th>
                            <th>Área na bacia</th>
                            <th>Atual</th>
                            <th>Observado 24 h</th>
                            <th>Observado 72 h</th>
                            <th>Previsão 24 h</th>
                            <th>Previsão 72 h</th>
                            <th>Contribuição atual</th>
                        </tr>
                    </thead>
                    <tbody id="municipality-body"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Média ponderada / total</td>
                            <td id="table-weight">--</td>
                            <td id="table-current">--</td>
                            <td id="table-observed24">--</td>
                            <td id="table-observed72">--</td>
                            <td id="table-forecast24">--</td>
                            <td id="table-forecast72">--</td>
                            <td id="table-contribution">--</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </main>

    <script>
        (() => {
            const URLS = {
                times: <?= json_encode($this->Url->build(['controller' => 'WeatherCache', 'action' => 'analysisTimes']), JSON_UNESCAPED_SLASHES) ?>,
                data: <?= json_encode($this->Url->build(['controller' => 'WeatherCache', 'action' => 'analysisData']), JSON_UNESCAPED_SLASHES) ?>,
                weights: <?= json_encode($this->Url->build('/taquari-antas-municipios.json'), JSON_UNESCAPED_SLASHES) ?>
            };
            const state = {
                weights: new Map(), times: [], snapshot: [], snapshotChart: null,
                previous24Chart: null, previous72Chart: null, comparisonChart: null,
                timelineTimer: null
            };
            const elements = {
                time: document.getElementById('snapshot-time'),
                timeline: document.getElementById('history-timeline'),
                days: document.getElementById('history-days'),
                load: document.getElementById('load-analysis'),
                status: document.getElementById('analysis-status'),
                filter: document.getElementById('municipality-filter')
            };

            function normalizeName(value) {
                return String(value || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim().toLowerCase();
            }
            function number(value) { return Number.isFinite(Number(value)) ? Number(value) : 0; }
            function mm(value) { return `${number(value).toFixed(1)} mm`; }
            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = String(value ?? '');
                return div.innerHTML;
            }
            function localDate(value) {
                if (!value) return '--';
                const normalized = String(value).includes('T') ? String(value) : String(value).replace(' ', 'T');
                const date = new Date(normalized);
                return Number.isNaN(date.getTime()) ? value : date.toLocaleString('pt-BR');
            }
            function setStatus(message = '', type = '') {
                elements.status.textContent = message;
                elements.status.className = `status-message${message ? ` visible ${type}` : ''}`;
            }
            function rainCategory(value) {
                const rain = number(value);
                if (rain >= 100) return 4;
                if (rain >= 60) return 3;
                if (rain >= 30) return 2;
                if (rain >= 10) return 1;
                return 0;
            }

            async function fetchJson(url) {
                const response = await fetch(url, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok || payload.success === false) throw new Error(payload.message || `HTTP ${response.status}`);
                return payload;
            }

            async function initialize() {
                setStatus('Carregando datas e pesos da bacia...', 'loading');
                try {
                    const [weightPayload, timePayload] = await Promise.all([
                        fetchJson(URLS.weights),
                        fetchJson(`${URLS.times}?days=180`)
                    ]);
                    Object.entries(weightPayload.municipios || {}).forEach(([name, weight]) => {
                        state.weights.set(normalizeName(name), number(weight));
                    });
                    populateTimes(timePayload.times || []);
                    if (!elements.time.value) throw new Error('Ainda não existem horários hidroclimáticos disponíveis.');
                    await loadAnalysis();
                } catch (error) {
                    setStatus(`Não foi possível iniciar a análise: ${error.message}`, 'error');
                }
            }

            function populateTimes(times) {
                state.times = times.map(item => ({
                    ...item,
                    timestamp: new Date(String(item.value).replace(' ', 'T')).getTime()
                })).filter(item => Number.isFinite(item.timestamp)).sort((a, b) => a.timestamp - b.timestamp);
                elements.time.innerHTML = '';
                state.times.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = `${localDate(item.value)} — ${item.municipalityCount} municípios salvos`;
                    elements.time.appendChild(option);
                });
                const lastIndex = Math.max(0, state.times.length - 1);
                elements.timeline.max = String(lastIndex);
                elements.timeline.value = String(lastIndex);
                const pointList = document.getElementById('timeline-points');
                pointList.innerHTML = '';
                const markerStep = Math.max(1, Math.ceil(state.times.length / 40));
                state.times.forEach((item, index) => {
                    if (index % markerStep !== 0 && index !== lastIndex) return;
                    const option = document.createElement('option');
                    option.value = String(index);
                    option.label = localDate(item.value);
                    pointList.appendChild(option);
                });
                if (state.times[lastIndex]) elements.time.value = state.times[lastIndex].value;
                updateTimelineLabels();
            }

            function updateTimelineLabels() {
                const index = number(elements.timeline.value);
                const current = state.times[index];
                document.getElementById('timeline-first').textContent = state.times.length ? localDate(state.times[0].value) : '--';
                document.getElementById('timeline-current').textContent = current ? localDate(current.value) : '--';
                document.getElementById('timeline-last').textContent = state.times.length ? localDate(state.times[state.times.length - 1].value) : '--';
            }

            function closestSavedTime(targetMs, selectedMs) {
                const previous = state.times.filter(item => item.timestamp < selectedMs);
                if (!previous.length) return null;
                return previous.reduce((best, item) =>
                    Math.abs(item.timestamp - targetMs) < Math.abs(best.timestamp - targetMs) ? item : best
                , previous[0]);
            }

            function analysisUrl(at) {
                const params = new URLSearchParams({ at, days: elements.days.value, horizon: '72' });
                return `${URLS.data}?${params.toString()}`;
            }

            async function loadAnalysis() {
                const at = elements.time.value;
                if (!at) return;
                elements.load.disabled = true;
                setStatus('Reconstruindo o histórico e pareando previsto com observado...', 'loading');
                try {
                    const selectedMs = new Date(String(at).replace(' ', 'T')).getTime();
                    const previous24 = closestSavedTime(selectedMs - 24 * 60 * 60 * 1000, selectedMs);
                    const previous72 = closestSavedTime(selectedMs - 72 * 60 * 60 * 1000, selectedMs);
                    const [payload72, payloadPrevious24, payloadPrevious72] = await Promise.all([
                        fetchJson(analysisUrl(at)),
                        previous24 ? fetchJson(analysisUrl(previous24.value)) : Promise.resolve(null),
                        previous72 ? fetchJson(analysisUrl(previous72.value)) : Promise.resolve(null)
                    ]);
                    state.snapshot = enrichSnapshot(payload72.snapshot || []);
                    renderSnapshot(payload72);
                    renderHistoricalSnapshot(payloadPrevious24, 'previous24', previous24, 24, selectedMs);
                    renderHistoricalSnapshot(payloadPrevious72, 'previous72', previous72, 72, selectedMs);
                    renderSelectedComparison(
                        state.snapshot,
                        enrichSnapshot(payloadPrevious24?.snapshot || []),
                        enrichSnapshot(payloadPrevious72?.snapshot || []),
                        at,
                        previous24,
                        previous72
                    );
                    renderTable(state.snapshot, elements.filter.value);
                    setStatus('');
                } catch (error) {
                    setStatus(`Falha ao carregar a análise: ${error.message}`, 'error');
                } finally {
                    elements.load.disabled = false;
                }
            }

            function enrichSnapshot(rows) {
                const matched = rows.map(row => ({
                    ...row,
                    weight: state.weights.get(normalizeName(row.cityName)) || 0
                })).filter(row => row.weight > 0);
                const totalWeight = matched.reduce((sum, row) => sum + row.weight, 0);
                return matched.map(row => ({
                    ...row,
                    contributionCurrent: totalWeight ? row.current * row.weight / totalWeight : 0
                }));
            }

            function weightedAverage(rows, field) {
                const totalWeight = rows.reduce((sum, row) => sum + row.weight, 0);
                return totalWeight
                    ? rows.reduce((sum, row) => sum + number(row[field]) * row.weight, 0) / totalWeight
                    : 0;
            }

            function renderSnapshot(payload) {
                const totalWeight = state.snapshot.reduce((sum, row) => sum + row.weight, 0);
                const values = [
                    weightedAverage(state.snapshot, 'observed72h'),
                    weightedAverage(state.snapshot, 'observed24h'),
                    weightedAverage(state.snapshot, 'current'),
                    weightedAverage(state.snapshot, 'forecast24h'),
                    weightedAverage(state.snapshot, 'forecast72h')
                ];
                document.getElementById('snapshot-description').textContent =
                    `Instantâneo reconstruído em ${localDate(payload.selectedAt)}. Cada município usa o registro mais recente encontrado nas três horas anteriores.`;
                document.getElementById('snapshot-count').textContent = state.snapshot.length;
                document.getElementById('snapshot-weight').textContent = totalWeight.toFixed(1);
                document.getElementById('snapshot-current').textContent = mm(values[2]);

                if (state.snapshotChart) state.snapshotChart.destroy();
                state.snapshotChart = new Chart(document.getElementById('snapshot-chart'), {
                    type: 'bar',
                    data: {
                        labels: [['Observado', '72 h'], ['Observado', '24 h'], ['Atual'], ['Previsão', '24 h'], ['Previsão', '72 h']],
                        datasets: [{
                            label: 'Precipitação média ponderada',
                            data: values,
                            backgroundColor: ['#4c78a8', '#72a4d4', '#00a878', '#f59e0b', '#ef6c57'],
                            borderColor: ['#315d87', '#4c78a8', '#007a57', '#b86e00', '#b53f2f'],
                            borderWidth: 1,
                            borderRadius: 7
                        }]
                    },
                    options: chartOptions('Precipitação média ponderada (mm)')
                });
            }

            function renderHistoricalSnapshot(payload, prefix, timeItem, hours, selectedMs) {
                const canvas = document.getElementById(`${prefix}-chart`);
                const description = document.getElementById(`${prefix}-description`);
                if (state[`${prefix}Chart`]) state[`${prefix}Chart`].destroy();
                if (!payload || !timeItem) {
                    description.textContent = `Não existe registro salvo próximo de ${hours} horas antes.`;
                    state[`${prefix}Chart`] = null;
                    return false;
                }
                const rows = enrichSnapshot(payload.snapshot || []);
                const differenceHours = Math.abs(selectedMs - timeItem.timestamp) / (60 * 60 * 1000);
                const targetDeviation = Math.abs(differenceHours - hours);
                description.textContent = `${localDate(timeItem.value)} — ${differenceHours.toFixed(1)} h antes do selecionado; desvio de ${targetDeviation.toFixed(1)} h do alvo de ${hours} h.`;
                state[`${prefix}Chart`] = createRainBarChart(canvas, rows);
            }

            function createRainBarChart(canvas, rows) {
                const values = [
                    weightedAverage(rows, 'observed72h'), weightedAverage(rows, 'observed24h'),
                    weightedAverage(rows, 'current'), weightedAverage(rows, 'forecast24h'),
                    weightedAverage(rows, 'forecast72h')
                ];
                return new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: [['Observado', '72 h'], ['Observado', '24 h'], ['Atual'], ['Previsão', '24 h'], ['Previsão', '72 h']],
                        datasets: [{
                            label: 'Precipitação média ponderada', data: values,
                            backgroundColor: ['#4c78a8', '#72a4d4', '#00a878', '#f59e0b', '#ef6c57'],
                            borderColor: ['#315d87', '#4c78a8', '#007a57', '#b86e00', '#b53f2f'],
                            borderWidth: 1, borderRadius: 7
                        }]
                    },
                    options: chartOptions('Precipitação média ponderada (mm)')
                });
            }

            function chartOptions(yTitle) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        tooltip: { callbacks: { label: context => `${context.dataset.label}: ${number(context.parsed.y).toFixed(1)} mm` } }
                    },
                    scales: {
                        x: { ticks: { autoSkip: true, maxRotation: 45, minRotation: 0 } },
                        y: { beginAtZero: true, title: { display: true, text: yTitle }, ticks: { callback: value => `${value} mm` } }
                    }
                };
            }

            function pairedMunicipalRows(currentRows, previousRows, forecastField, observedField) {
                const previousByCity = new Map(previousRows.map(row => [normalizeName(row.cityName), row]));
                return currentRows.map(current => {
                    const previous = previousByCity.get(normalizeName(current.cityName));
                    if (!previous) return null;
                    const forecast = number(previous[forecastField]);
                    const observed = number(current[observedField]);
                    return {
                        cityName: current.cityName,
                        forecast,
                        observed,
                        error: forecast - observed,
                        weight: current.weight
                    };
                }).filter(Boolean);
            }

            function weightedPairAverage(rows, field) {
                const weightTotal = rows.reduce((sum, row) => sum + row.weight, 0);
                return weightTotal
                    ? rows.reduce((sum, row) => sum + number(row[field]) * row.weight, 0) / weightTotal
                    : 0;
            }

            function renderSelectedComparison(currentRows, previous24Rows, previous72Rows, selectedAt, previous24, previous72) {
                const emptyMessage = document.getElementById('comparison-empty');
                const chartInner = document.getElementById('comparison-chart-inner');
                const comparisonSummary = document.getElementById('comparison-summary');
                emptyMessage.classList.remove('visible');
                emptyMessage.textContent = '';
                chartInner.style.display = 'block';
                const pairs24 = pairedMunicipalRows(currentRows, previous24Rows, 'forecast24h', 'observed24h');
                const pairs72 = pairedMunicipalRows(currentRows, previous72Rows, 'forecast72h', 'observed72h');
                renderConfidenceCards(pairs72);
                const minimumWidth = 720;
                chartInner.style.width = '100%';
                chartInner.style.minWidth = `${minimumWidth}px`;
                if (state.comparisonChart) state.comparisonChart.destroy();
                if (!pairs24.length && !pairs72.length) {
                    state.comparisonChart = null;
                    chartInner.style.display = 'none';
                    comparisonSummary.textContent = 'Nenhum município comum encontrado entre os instantâneos selecionados.';
                    emptyMessage.textContent = 'Não foi possível cruzar a data selecionada com os registros anteriores de 24 e 72 horas.';
                    emptyMessage.classList.add('visible');
                    return false;
                }
                const forecast24 = weightedPairAverage(pairs24, 'forecast');
                const observed24 = weightedPairAverage(pairs24, 'observed');
                const forecast72 = weightedPairAverage(pairs72, 'forecast');
                const observed72 = weightedPairAverage(pairs72, 'observed');
                const maximumValue = Math.max(forecast24, observed24, forecast72, observed72);
                const allValuesZero = maximumValue === 0;
                comparisonSummary.textContent = allValuesZero
                    ? `24 h: ${pairs24.length} municípios; 72 h: ${pairs72.length} municípios. Todos os valores são 0 mm.`
                    : `24 h: ${pairs24.length} municípios pareados; 72 h: ${pairs72.length} municípios pareados.`;
                // Garante que o Chart.js leia a largura após um estado anterior
                // em que o contêiner tenha ficado oculto por falta de dados.
                void chartInner.offsetWidth;
                try {
                    const lineOptions = chartOptions('Precipitação acumulada (mm)');
                    lineOptions.scales.y.suggestedMax = allValuesZero ? 1 : undefined;
                    state.comparisonChart = new Chart(document.getElementById('comparison-chart'), {
                    type: 'line',
                    data: {
                        labels: [
                            previous72 ? `Previsão em ${localDate(previous72.value)}` : 'Previsão −72 h indisponível',
                            previous24 ? `Previsão em ${localDate(previous24.value)}` : 'Previsão −24 h indisponível',
                            `Observado em ${localDate(selectedAt)}`
                        ],
                        datasets: [
                            {
                                label: 'Janela de 72 h', data: [pairs72.length ? forecast72 : null, null, pairs72.length ? observed72 : null],
                                borderColor: '#746ed6', backgroundColor: '#746ed6', pointStyle: 'circle', pointRadius: 5,
                                borderWidth: 3, tension: .15, spanGaps: true
                            },
                            {
                                label: 'Janela de 24 h', data: [null, pairs24.length ? forecast24 : null, pairs24.length ? observed24 : null],
                                borderColor: '#f59e0b', backgroundColor: '#f59e0b', pointStyle: 'rectRot', pointRadius: 5,
                                borderWidth: 3, borderDash: [7, 4], tension: .15, spanGaps: true
                            }
                        ]
                    },
                    options: {
                        ...lineOptions,
                        plugins: {
                            legend: { display: true },
                            tooltip: { callbacks: { label: context => `${context.dataset.label}: ${number(context.parsed.y).toFixed(1)} mm` } }
                        }
                    }
                    });
                    requestAnimationFrame(() => state.comparisonChart?.resize());
                    return true;
                } catch (error) {
                    state.comparisonChart = null;
                    chartInner.style.display = 'none';
                    emptyMessage.textContent = `Não foi possível desenhar o gráfico: ${error.message}`;
                    emptyMessage.classList.add('visible');
                    return false;
                }
            }

            function renderConfidenceCards(rows) {
                const sampleSize = rows.length;
                const divisor = sampleSize || 1;
                const bias = rows.reduce((sum, row) => sum + row.error, 0) / divisor;
                const mae = rows.reduce((sum, row) => sum + Math.abs(row.error), 0) / divisor;
                const rmse = Math.sqrt(rows.reduce((sum, row) => sum + Math.pow(row.error, 2), 0) / divisor);
                const variance = rows.reduce((sum, row) => sum + Math.pow(row.error - bias, 2), 0) / divisor;
                const margin = sampleSize > 1 ? 1.96 * Math.sqrt(variance / sampleSize) : 0;
                const categoryHits = rows.filter(row => rainCategory(row.forecast) === rainCategory(row.observed)).length;
                const categoryAccuracy = sampleSize ? 100 * categoryHits / sampleSize : 0;
                document.getElementById('confidence-count').textContent = sampleSize;
                document.getElementById('confidence-mae').textContent = mm(mae);
                document.getElementById('confidence-rmse').textContent = mm(rmse);
                document.getElementById('confidence-bias').textContent = `${bias >= 0 ? '+' : ''}${bias.toFixed(1)} mm`;
                document.getElementById('confidence-margin').textContent = `± ${margin.toFixed(1)} mm`;
                document.getElementById('confidence-category').textContent = `${categoryAccuracy.toFixed(1)}%`;
            }

            function renderTable(rows, filterValue = '') {
                const filter = normalizeName(filterValue);
                const filtered = rows.filter(row => normalizeName(row.cityName).includes(filter));
                const body = document.getElementById('municipality-body');
                body.innerHTML = filtered.map(row => `
                    <tr>
                        <td>${escapeHtml(row.cityName)}</td>
                        <td>${escapeHtml(localDate(row.createdAt))}</td>
                        <td>${row.weight.toFixed(1)}%</td>
                        <td>${mm(row.current)}</td>
                        <td>${mm(row.observed24h)}</td>
                        <td>${mm(row.observed72h)}</td>
                        <td>${mm(row.forecast24h)}</td>
                        <td>${mm(row.forecast72h)}</td>
                        <td>${mm(row.contributionCurrent)}</td>
                    </tr>`).join('');
                const totalWeight = rows.reduce((sum, row) => sum + row.weight, 0);
                document.getElementById('table-summary').textContent = `${filtered.length} de ${rows.length} municípios exibidos`;
                document.getElementById('table-weight').textContent = totalWeight.toFixed(1);
                document.getElementById('table-current').textContent = mm(weightedAverage(rows, 'current'));
                document.getElementById('table-observed24').textContent = mm(weightedAverage(rows, 'observed24h'));
                document.getElementById('table-observed72').textContent = mm(weightedAverage(rows, 'observed72h'));
                document.getElementById('table-forecast24').textContent = mm(weightedAverage(rows, 'forecast24h'));
                document.getElementById('table-forecast72').textContent = mm(weightedAverage(rows, 'forecast72h'));
                document.getElementById('table-contribution').textContent = mm(rows.reduce((sum, row) => sum + row.contributionCurrent, 0));
            }

            elements.load.addEventListener('click', loadAnalysis);
            elements.time.addEventListener('change', loadAnalysis);
            elements.time.addEventListener('change', () => {
                const index = state.times.findIndex(item => item.value === elements.time.value);
                if (index >= 0) elements.timeline.value = String(index);
                updateTimelineLabels();
            });
            elements.timeline.addEventListener('input', () => {
                const item = state.times[number(elements.timeline.value)];
                if (!item) return;
                elements.time.value = item.value;
                updateTimelineLabels();
            });
            elements.timeline.addEventListener('change', loadAnalysis);
            elements.filter.addEventListener('input', () => renderTable(state.snapshot, elements.filter.value));
            initialize();
        })();
    </script>
</body>
</html>
