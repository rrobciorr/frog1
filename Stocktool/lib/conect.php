<?php


function send($tresc = "demo info ")
{

    $to = "rrobciorr@gmail.com";
    $temat = "Automatic Market Info";
    mail($to, $temat, $tresc, 'From: robcior.pl@robcior.pl');

}
function get_prices($tickr = "SPY")
{ //$tickr = read("favorite.json");


    $url = "https://query1.finance.yahoo.com/v8/finance/chart/$tickr?region=US&lang=en-US&includePrePost=false&interval=1d&useYfid=true&range=10y";
    $stock_data = json_decode(file_get_contents($url), true);
    $ydata = $stock_data['chart']['result'][0]['indicators']['quote']['0']['close'];
    return $ydata;
}

print (get_prices("AAPL"))

    ?>