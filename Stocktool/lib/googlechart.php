<?php
// Function to generate Google Charts-compatible JSON data
function draw2chart($ydata = array(11,3,8,12,5,1,9,13,5,7), 
                    $y2data = array(354,200,265,99,111,91,198,225,293,751), 
                    $filename, $title) {

    // Labels for X-axis (time series)
    $labels = range(1, count($ydata));

    // Prepare the data structure
    $chartData = [
        "labels" => $labels,
        "datasets" => [
            [
                "label" => "Price",
                "data" => $ydata,
                "borderColor" => "blue",
                "backgroundColor" => "rgba(0, 0, 255, 0.1)",
                "fill" => false
            ],
            [
                "label" => "Stock",
                "data" => $y2data,
                "borderColor" => "orange",
                "backgroundColor" => "rgba(255, 165, 0, 0.1)",
                "fill" => false
            ]
        ]
    ];

    return json_encode($chartData);
}

// Generate chart data (replace with real data if needed)
$chartJSON = draw2chart();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Google Charts - Two Line Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>

    <h2>Chart: Price vs Stock</h2>
    <canvas id="chartCanvas"></canvas>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById("chartCanvas").getContext("2d");
            var chartData = <?php echo $chartJSON; ?>; // Get PHP-generated data

            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true },
                        y2: { beginAtZero: true }
                    }
                }
            });
        });
    </script>

</body>
</html>
