<?php

// Include necessary libraries
include "lib/conect.php";
include "lib/view.php";
include "lib/example6.1.php";

/**
 * Calculate the average of the last $period elements in $data.
 */
function average($data, $period = 0)
{
    if (!$period) {
        $period = count($data);
    }

    $subset = array_slice($data, -$period);
    return array_sum($subset) / count($subset);
}

/**
 * Calculate Simple Moving Average (SMA).
 */
function sma_calculate($data, $period = 26)
{
    $sma_value = average($data, $period);
    $last_price = end($data);
    $percentage_change = round(100 * ($last_price - $sma_value) / $sma_value, 1);

    $status = $percentage_change > 0 ? "above" : "below";
    $sma_info = "Last price is {$percentage_change}% {$status} the {$period}-day average.";

    return [$percentage_change, $sma_info];
}

/**
 * Class representing different trading tactics.
 */
class Tactic
{
    public $id;
    public static array $available_tactics = [4];
    public ?string $description = null;
    
    public $money = 10000;
    public $stock = 0;
    public array $stock_history = [];
    public array $price_history = [];
    
    public $tactic_value = 12; // 0 - 100% 
    public $max_price = 0;
    public $income = 2000;
    public $income_growth = 1.01;
    public $normal_money = 10000;
    
    public $buy_size = 3000;
    public $buy_period = 1; // Important value 
    public $sell_size = 15;
    public $sell_period = 30;
    
    public $drop_history = [];
    public int $start;
    public int $end;
    public $price;
    
    public $tactic2_value = 3;
    public $sma_period = 26;
    public $tactic_period = 580;
    
    public $sma_value = 1;
    public $sma_history = [];
    public $year_gain_history = [];
    public $today_proposition;

    public function __construct($id, $start, $end)
    {
        $this->id = $id;
        $this->reset($start, $end);
    }

    /**
     * Reset tactic parameters.
     */
    public function reset($start, $end)
    {
        $this->money = 10000;
        $this->stock = 0;
        $this->max_price = 0;
        $this->income = 2000;
        $this->normal_money = 10000;
        $this->drop_history = [];
        $this->sma_history = [];
        $this->start = $start;
        $this->end = $end;
        $this->stock_history = [];
        $this->price_history = [];
    }

    /**
     * Execute a buy action if conditions are met.
     */
    public function buy()
    {
        if ($this->money > 0 && $this->day % $this->buy_period === 0) {
            $this->stock += $this->buy_size / $this->price;
            $this->money -= $this->buy_size;
        }
    }

    /**
     * Executes the tactic's actions for a given day.
     */
    public function execute_day($price, $day, $prices)
    {
        $this->price = $price;
        $this->day = $day;

        switch ($this->id) {
            case 1: // SMA Strategy
                $this->description = "SMA Strategy";
                $recent_prices = array_slice($prices, $day - $this->sma_period, $this->sma_period);
                $current_sma = sma_calculate($recent_prices)[0];
                $this->sma_history[] = $current_sma;

                if ($day > $this->start) {
                    $sma_history_recent = array_slice($this->sma_history, -100);
                    asort($sma_history_recent);

                    if ($current_sma < $sma_history_recent[$this->sma_value]) {
                        $this->buy();
                    }
                }
                break;

            case 2: // Buy at Relative Drop
                $this->description = "Buy at Relative Drop";
                $this->max_price = max($this->max_price, $price);
                $drop_value = ( ($price / $this->max_price) - 1 ) * 100;
                $this->drop_history[] = $drop_value;
                asort($this->drop_history);

                if ($day > $this->start && $drop_value < $this->drop_history[$this->tactic2_value]) {
                    $this->buy();
                }
                break;

            case 3: // Buy Every Month
                $this->description = "Buy Every Month";
                if ($day > $this->start) {
                    $this->buy();
                }
                break;

            case 4: // Buy at 10% Drop
                $this->description = "Buy at 10% Drop";
                $this->max_price = max($this->max_price, $price);

                if ($day > $this->start) {
                    if ($price < $this->max_price * ((100 - $this->tactic_value) / 100)) {
                        $this->buy();
                        $this->today_proposition = "Today you should buy";
                    } else {
                        $this->today_proposition = "Today you should sell";

                        if ($this->stock > 0 && $day % $this->sell_period === 0) {
                            $this->money += $this->sell_size * $price;
                            $this->stock -= $this->sell_size;
                        }
                    }
                }
                break;
        }

        $this->stock_history[] = $this->stock;
        $this->price_history[] = $this->price;

        if ($day > $this->start && $day % 30 === 0) {
            $this->money += $this->income;
            $this->normal_money += $this->income;
            $this->income *= $this->income_growth;
        }
    }

    /**
     * Calculate the final effect of the tactic.
     */
    public function get_effect()
    {
        $total_value = ($this->price * $this->stock) + $this->money;
        $gain = 100 * ($total_value - $this->normal_money) / $this->normal_money;
        $year_gain = $gain / (($this->end + 1 - $this->start) / 252);
        $this->year_gain_history[] = $year_gain;

        return [
            "Tactic " . $this->id,
            $this->money,
            $this->stock,
            $this->price,
            $this->normal_money,
            $this->end,
            $this->start,
            $this->description
        ];
    }
}

//////////////////  *MAIN SCRIPT*  //////////////////

foreach (["AAPL", "SPY", "MCD", "PEP", "PFE", "WMT", "MSFT", "MAR"] as $ticker) {
    $prices = get_prices($ticker);
    $end = count($prices) - 1;
    $tactic_period = 580;

    $tactics = [];
    foreach (Tactic::$available_tactics as $id) {
        $tactics[] = new Tactic($id, $end, $end);
    }

    foreach ($tactics as $tactic) {
        for ($day = $end - $tactic_period; $day <= $end; $day++) {
            $tactic->execute_day($prices[$day], $day, $prices);
        }
    }

    draw2chart($tactics[0]->price_history, $tactics[0]->stock_history, "tmp/{$ticker}.png", $tactics[0]->today_proposition);
    echo "<img src='/Stocktool/tmp/{$ticker}.png' />";
}
?>
