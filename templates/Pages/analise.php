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
        .filters { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 12px; align-items: end; }
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
        .chart-scroll-inner { position: relative; min-width: 720px; height: 410px; }

        .confidence-note {
            margin: 14px 0 0; padding: 12px; color: #5b4a10;
            background: #fff8db; border-left: 4px solid #eab308; border-radius: 6px;
            font-size: .85rem; line-height: 1.45;
        }

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
                    <label for="analysis-horizon">Comparação</label>
                    <select id="analysis-horizon">
                        <option value="24">Previsão 24 h</option>
                        <option value="72">Previsão 72 h</option>
                    </select>
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

        <section class="panel" aria-labelledby="comparison-title">
            <h2 id="comparison-title">Previsto × observado posterior</h2>
            <p class="panel-description">A previsão emitida em T é comparada ao acumulado observado no registro próximo de T+24 h ou T+72 h, conforme o horizonte escolhido.</p>
            <div class="confidence-cards">
                <div class="metric-card"><span>Janelas comparadas</span><strong id="confidence-count">--</strong></div>
                <div class="metric-card"><span>Erro absoluto médio (MAE)</span><strong id="confidence-mae">-- mm</strong></div>
                <div class="metric-card"><span>RMSE</span><strong id="confidence-rmse">-- mm</strong></div>
                <div class="metric-card"><span>Viés médio</span><strong id="confidence-bias">-- mm</strong></div>
                <div class="metric-card"><span>Margem 95% do erro médio</span><strong id="confidence-margin">-- mm</strong></div>
                <div class="metric-card"><span>Acerto da faixa de chuva</span><strong id="confidence-category">--%</strong></div>
            </div>
            <div class="chart-scroll">
                <div id="comparison-chart-inner" class="chart-scroll-inner">
                    <canvas id="comparison-chart"></canvas>
                </div>
            </div>
            <p class="confidence-note">
                A faixa sombreada é uma margem estatística preliminar de 95%, calculada a partir da dispersão dos erros municipais. O “observado” é o dado posterior armazenado da Open-Meteo; para validação hidrológica oficial, recomenda-se confrontar também com pluviômetros da ANA, INMET, CEMADEN ou rede estadual.
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
            const state = { weights: new Map(), snapshot: [], snapshotChart: null, comparisonChart: null };
            const elements = {
                time: document.getElementById('snapshot-time'),
                horizon: document.getElementById('analysis-horizon'),
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
                elements.time.innerHTML = '';
                times.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = `${localDate(item.value)} — ${item.municipalityCount} municípios salvos`;
                    elements.time.appendChild(option);
                });
            }

            async function loadAnalysis() {
                const at = elements.time.value;
                if (!at) return;
                elements.load.disabled = true;
                setStatus('Reconstruindo o histórico e pareando previsto com observado...', 'loading');
                try {
                    const params = new URLSearchParams({
                        at,
                        horizon: elements.horizon.value,
                        days: elements.days.value
                    });
                    const payload = await fetchJson(`${URLS.data}?${params.toString()}`);
                    state.snapshot = enrichSnapshot(payload.snapshot || []);
                    renderSnapshot(payload);
                    renderComparison(payload.comparisons || [], payload.horizon);
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

            function renderComparison(comparisons, horizon) {
                const enriched = comparisons.map(row => ({
                    ...row,
                    weight: state.weights.get(normalizeName(row.cityName)) || 0
                })).filter(row => row.weight > 0);
                const groups = new Map();
                enriched.forEach(row => {
                    const issued = new Date(String(row.issuedAt).replace(' ', 'T'));
                    issued.setMinutes(0, 0, 0);
                    const key = issued.toISOString();
                    if (!groups.has(key)) groups.set(key, []);
                    groups.get(key).push(row);
                });

                const points = [...groups.entries()].map(([key, rows]) => {
                    const weightTotal = rows.reduce((sum, row) => sum + row.weight, 0);
                    const forecast = rows.reduce((sum, row) => sum + row.forecast * row.weight, 0) / weightTotal;
                    const observed = rows.reduce((sum, row) => sum + row.observed * row.weight, 0) / weightTotal;
                    const meanError = rows.reduce((sum, row) => sum + row.error * row.weight, 0) / weightTotal;
                    const variance = rows.reduce((sum, row) => sum + row.weight * Math.pow(row.error - meanError, 2), 0) / weightTotal;
                    const sumWeightSquared = rows.reduce((sum, row) => sum + Math.pow(row.weight, 2), 0);
                    const effectiveN = sumWeightSquared ? Math.pow(weightTotal, 2) / sumWeightSquared : rows.length;
                    const margin = effectiveN > 1 ? 1.96 * Math.sqrt(variance / effectiveN) : 0;
                    return { key, forecast, observed, error: forecast - observed, margin, count: rows.length };
                }).sort((a, b) => a.key.localeCompare(b.key));

                // As métricas gerais usam as médias da bacia por horário, evitando
                // tratar municípios vizinhos como amostras meteorológicas independentes.
                renderConfidenceCards(points);
                const minimumWidth = Math.max(720, points.length * 28);
                document.getElementById('comparison-chart-inner').style.width = `${minimumWidth}px`;
                if (state.comparisonChart) state.comparisonChart.destroy();
                state.comparisonChart = new Chart(document.getElementById('comparison-chart'), {
                    type: 'line',
                    data: {
                        labels: points.map(point => new Date(point.key).toLocaleString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit' })),
                        datasets: [
                            { label: 'Limite inferior 95%', data: points.map(p => Math.max(0, p.forecast - p.margin)), borderWidth: 0, pointRadius: 0, backgroundColor: 'transparent' },
                            { label: 'Margem estatística 95%', data: points.map(p => p.forecast + p.margin), borderWidth: 0, pointRadius: 0, fill: '-1', backgroundColor: 'rgba(245, 158, 11, .18)' },
                            { label: `Previsto ${horizon} h`, data: points.map(p => p.forecast), borderColor: '#f59e0b', backgroundColor: '#f59e0b', pointRadius: 2, borderWidth: 2.5, tension: .2 },
                            { label: `Observado posterior ${horizon} h`, data: points.map(p => p.observed), borderColor: '#0066cc', backgroundColor: '#0066cc', pointRadius: 2, borderWidth: 2.5, tension: .2 }
                        ]
                    },
                    options: {
                        ...chartOptions('Precipitação acumulada (mm)'),
                        plugins: {
                            legend: { labels: { filter: item => !item.text.startsWith('Limite inferior') } },
                            tooltip: { callbacks: { label: context => `${context.dataset.label}: ${number(context.parsed.y).toFixed(1)} mm` } }
                        }
                    }
                });
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
            elements.horizon.addEventListener('change', loadAnalysis);
            elements.filter.addEventListener('input', () => renderTable(state.snapshot, elements.filter.value));
            initialize();
        })();
    </script>
</body>
</html>
