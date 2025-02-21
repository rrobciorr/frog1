<?php
function fetchYahooFinanceData($ticker)
{
    $url = "https://query1.finance.yahoo.com/v8/finance/chart/$ticker?region=US&lang=en-US&includePrePost=false&interval=1d&useYfid=true&range=10y";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0"); // Avoid bot detection

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 429) {
        die("Error: Too many requests. Try again later.");
    } elseif ($response === false) {
        die("Error: Failed to fetch data.");
    }

    return json_decode($response, true);
}

// $stock_data = fetchYahooFinanceData("AAPL"); //test 
// print_r($stock_data);