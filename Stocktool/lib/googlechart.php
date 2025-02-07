<?php
// Generate random data for two lines
$data1 = [];
$data2 = [];
$labels = [];

for ($i = 1; $i <= 10; $i++) {
    $labels[] = "Point " . $i;
    $data1[] = rand(10, 100);
    $data2[] = rand(50, 150);
}

// Encode as JSON for JavaScript
$chartData = json_encode([$labels, $data1, $data2]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Google Charts - Line Chart</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var rawData = <?php echo $chartData; ?>;
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Label');
            data.addColumn('number', 'Line 1');
            data.addColumn('number', 'Line 2');

            for (var i = 0; i < rawData[0].length; i++) {
                data.addRow([rawData[0][i], rawData[1][i], rawData[2][i]]);
            }

            var options = { title: 'Simple Line Chart', curveType: 'function', legend: { position: 'bottom' } };
            var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="chart_div" style="width: 800px; height: 500px;"></div>
</body>
</html>
