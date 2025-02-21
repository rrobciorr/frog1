<?php
include "conect2.php";
use PHPUnit\Framework\TestCase;

class MarketFunctionsTest extends TestCase
{


    public function testfetchYahooFinanceData()
    {
        // Sprawdzamy, czy funkcja zwraca tablicę cen
        $prices = fetchYahooFinanceData("AAPL"); // Testujemy na popularnym tickerze

        $this->assertIsArray($prices, "Funkcja fetchYahooFinanceData() powinna zwracać tablicę");
        $this->assertNotEmpty($prices, "Tablica cen nie powinna być pusta");

        // Sprawdzamy, czy elementy tablicy są liczbami
        foreach ($prices as $price) {
            $this->assertIsFloat((float) $price, "Elementy tablicy powinny być liczbami zmiennoprzecinkowymi");
        }
    }
}
?>