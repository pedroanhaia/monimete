<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Google Charts</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carregar as bibliotecas necessárias
        google.charts.load('current', {
            packages: ['corechart', 'geochart', 'bar']
        });

        // Callback para desenhar os gráficos
        google.charts.setOnLoadCallback(drawDashboard);

        function drawDashboard() {
            // Dados para o mapa geolocalizado
            var geoData = google.visualization.arrayToDataTable([
                ['City', 'Population'],
                ['Porto Alegre', 1484941],
                ['Caxias do Sul', 517451],
                ['Pelotas', 343132]
            ]);

            var geoOptions = {
                region: 'BR', // Região do Brasil
                displayMode: 'markers',
                colorAxis: { colors: ['green', 'blue'] }
            };

            var geoChart = new google.visualization.GeoChart(document.getElementById('geo_chart'));
            geoChart.draw(geoData, geoOptions);

            // Dados para o gráfico de barras
            var barData = google.visualization.arrayToDataTable([
                ['City', 'Population'],
                ['Porto Alegre', 1484941],
                ['Caxias do Sul', 517451],
                ['Pelotas', 343132]
            ]);

            var barOptions = {
                chart: {
                    title: 'População por Cidade',
                },
                bars: 'horizontal'
            };

            var barChart = new google.charts.Bar(document.getElementById('bar_chart'));
            barChart.draw(barData, google.charts.Bar.convertOptions(barOptions));

            // Dados para o gráfico de distribuição
            var pieData = google.visualization.arrayToDataTable([
                ['City', 'Population'],
                ['Porto Alegre', 1484941],
                ['Caxias do Sul', 517451],
                ['Pelotas', 343132]
            ]);

            var pieOptions = {
                title: 'Distribuição Populacional',
                is3D: true
            };

            var pieChart = new google.visualization.PieChart(document.getElementById('pie_chart'));
            pieChart.draw(pieData, pieOptions);
        }
    </script>
</head>
<body>
    <h1>Dashboard - Google Charts</h1>
    <div id="geo_chart" style="width: 100%; height: 500px;"></div>
    <div id="bar_chart" style="width: 100%; height: 500px;"></div>
    <div id="pie_chart" style="width: 100%; height: 500px;"></div>
</body>
</html>