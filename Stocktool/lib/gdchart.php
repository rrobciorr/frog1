<?php
function saveChartImage($filename = "chart.png") {
    // Image dimensions
    $width = 900;
    $height = 440;

    // Create blank image
    $image = imagecreatetruecolor($width, $height);

    // Colors
    $white = imagecolorallocate($image, 255, 255, 255);
    $black = imagecolorallocate($image, 0, 0, 0);
    $blue = imagecolorallocate($image, 0, 0, 255);
    $orange = imagecolorallocate($image, 255, 165, 0);

    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $white);

    // Sample data
    $ydata = [11, 3, 8, 12, 5, 1, 9, 13, 5, 7];
    $y2data = [354, 200, 265, 99, 111, 91, 198, 225, 293, 751];
    $data_count = count($ydata);

    // Define chart area
    $margin = 50;
    $chart_width = $width - 2 * $margin;
    $chart_height = $height - 2 * $margin;

    // Find max values for scaling
    $maxY = max(max($ydata), max($y2data));
    $scaleY = ($chart_height - 20) / $maxY;
    $scaleX = $chart_width / ($data_count - 1);

    // Draw axes
    imageline($image, $margin, $margin, $margin, $height - $margin, $black); // Y-axis
    imageline($image, $margin, $height - $margin, $width - $margin, $height - $margin, $black); // X-axis

    // Draw first line (Price - Blue)
    for ($i = 0; $i < $data_count - 1; $i++) {
        $x1 = $margin + $i * $scaleX;
        $y1 = $height - $margin - ($ydata[$i] * $scaleY);
        $x2 = $margin + ($i + 1) * $scaleX;
        $y2 = $height - $margin - ($ydata[$i + 1] * $scaleY);
        imageline($image, $x1, $y1, $x2, $y2, $blue);
    }

    // Draw second line (Stock - Orange)
    for ($i = 0; $i < $data_count - 1; $i++) {
        $x1 = $margin + $i * $scaleX;
        $y1 = $height - $margin - ($y2data[$i] * $scaleY);
        $x2 = $margin + ($i + 1) * $scaleX;
        $y2 = $height - $margin - ($y2data[$i + 1] * $scaleY);
        imageline($image, $x1, $y1, $x2, $y2, $orange);
    }

    // Save image
    imagepng($image, $filename);
    imagedestroy($image);

    return "Chart saved as " . $filename;
}

// Call function to save chart
echo saveChartImage("chart.png");
?>
