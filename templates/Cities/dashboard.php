<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Google Charts</title>
</head>
<body>
    <h1>Dashboard - Google Charts</h1>
    <div id="map" style="width: 100%; height: 500px;"></div>
    <div id="bar_chart" style="width: 100%; height: 500px;"></div>
    <div id="pie_chart" style="width: 100%; height: 500px;"></div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        
        // Carrega bibliotecas do Google Charts
        google.charts.load('current', {
            packages: ['corechart', 'geochart', 'bar']
        });

        // Inicializar o mapa centralizado no RS
        var map = L.map('map').setView([-30.0346, -51.2177], 7);

        // Adicionar camada de tiles (mapa base)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Carregar GeoJSON do Rio Grande do Sul
        $.getJSON('geojs-43-mun.geojson', function(data) {
            var geojsonLayer = L.geoJSON(data, {
                style: {
                    color: "#3388ff",
                    weight: 2,
                    fillOpacity: 0.1
                }
            }).addTo(map);
            map.fitBounds(geojsonLayer.getBounds()); // Ajusta o zoom para o RS
        });

        // Callback
        google.charts.setOnLoadCallback(drawDashboard);
        
        // Carrega bibliotecas do Google Charts
        google.charts.load('current', {
            packages: ['corechart', 'geochart', 'bar']
        });
        // Callback
        google.charts.setOnLoadCallback(drawDashboard);

        function drawDashboard() {
            // Dados simulados de temperatura (exemplo)
            var temperaturaData = [
                ['Cidade', 'Temperatura (°C)']
                <?php foreach ($cities as $city): ?>
                    , ['<?= h($city->name.", RS") ?>', <?= rand(15, 35) ?>]
                <?php endforeach; ?>
            ];

            var dataTable = google.visualization.arrayToDataTable(temperaturaData);

            // Gráfico GeoChart
            var geoOptions = {
                region: 'BR', // Brasil
                displayMode: 'markers',
                resolution: 'provinces',
                magnifyingGlass: { enable: true, zoomFactor: 7.5 }, // Ajusta o zoom
                
                colorAxis: { colors: ['#87CEFA', '#FF4500'] }
            };
            // var geoChart = new google.visualization.GeoChart(document.getElementById('geo_chart'));
            // geoChart.draw(dataTable, geoOptions);

            // Gráfico de barras
            var barOptions = {
                chart: {
                    title: 'Temperatura por Cidade',
                },
                bars: 'horizontal',
                hAxis: {
                    title: 'Temperatura (°C)'
                }
            };
            var barChart = new google.charts.Bar(document.getElementById('bar_chart'));
            barChart.draw(dataTable, google.charts.Bar.convertOptions(barOptions));

            // Gráfico de pizza
            var pieOptions = {
                title: 'Distribuição de Temperatura nas Cidades',
                is3D: true
            };
            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            pieChart.draw(dataTable, pieOptions);
        }
    });
</script>

</html>