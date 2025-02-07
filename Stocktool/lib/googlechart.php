<?php

function draw2chart($data1 = array(11,3,8,12,5,1,9,13,5,7),$data2 = array(354,200,265,99,111,91,198,225,293,751),$filename,$labels){
  

// Encode as JSON for JavaScript
$chartData = json_encode([$labels, $data1, $data2]);

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

    <div id="chart_div" style="width: 800px; height: 500px;"></div>

}
