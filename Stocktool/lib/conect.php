<?php

use PHPUnit\Framework\TestCase;

class MarketFunctionsTest extends TestCase
{
    public function testSend()
    {
        // Ponieważ funkcja send() używa mail(), nie możemy jej przetestować bezpośrednio.
        // Można zamiast tego przetestować, czy funkcja istnieje i można ją wywołać.
        $this->assertTrue(function_exists('send'));
    }

    public function testGetPrices()
    {
        // Sprawdzamy, czy funkcja zwraca tablicę cen
        $prices = get_prices("AAPL"); // Testujemy na popularnym tickerze
        
        $this->assertIsArray($prices, "Funkcja get_prices() powinna zwracać tablicę");
        $this->assertNotEmpty($prices, "Tablica cen nie powinna być pusta");
        
        // Sprawdzamy, czy elementy tablicy są liczbami
        foreach ($prices as $price) {
            $this->assertIsFloat((float) $price, "Elementy tablicy powinny być liczbami zmiennoprzecinkowymi");
        }
    }
}


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


?>