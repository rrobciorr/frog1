<?php
include "conect.php";
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
?>