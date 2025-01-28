<?php
include "lib/conect.php";
include "lib/view.php";
include "lib/example6.1.php";
function average($data, $period = 0)
{
    if (!$period)
        $period = sizeof($data);

    $arr = array_slice($data, -$period);
    $avr = array_sum($arr) / count($arr);
    return $avr;
}

function sma_calculate($data)
{ //Simple Moving Average (SMA)   //$period =26; //12

    $period = 26;
    $avr = average($data, $period);
    $last_price = $data[sizeof($data) - 1];
    $perc_gain = 100 * ($last_price - $avr) / $avr;
    $perc_gain = round($perc_gain, 1);

    $sma_info = "Last price is " . $perc_gain . " % " . ["below ", "above "][$perc_gain > 0] . $period . " days average.";
    return [$perc_gain, $sma_info];
}

class Tactic
{
    public $id;
    public static $tacticsdisponible = array(4);
    public ?string $description;
    public $day;

    public $money;
    public $stock;
    public $stockhistory = array();
    public $pricehistory = array();
    public $tacticvalue = 12; // 0 -100 % 
    public $maxprice;
    public $income;
    public $incomegrow = 1.01;
    public $normalmoney;
    public $buysize = 3000;
    public $buyperiod = 1; //important value 
    public $sellsize = 15;
    public $sellperiod = 30;
    public $drophistory;
    public int $start;
    public int $end;
    public $price;
    public $tactic2value = 3;
    public $smaperiod = 26;
    public $tacticperiod = 580;
    public $smavalue = 1;
    public $smahistory;

    public $yeargainhistory = array();
    public $todayproposition;

    public function __construct($id, $start, $end)
    {
        $this->id = $id;
        $this->reset($start, $end);
    }

    public function reset($start, $end)
    {
        $this->money = 10000;
        $this->stock = 0;
        $this->maxprice = 0;
        $this->income = 2000;
        $this->normalmoney = 10000;
        $this->drophistory = array();
        $this->smahistory = array();
        $this->start = $start;
        $this->end = $end;
        $this->stockhistory = array();
        $this->pricehistory = array();
    }

    public function buy()
    {
        if ($this->money > 0 && $this->day % $this->buyperiod == 0) {
            $this->stock += $this->buysize / $this->price;
            $this->money -= $this->buysize;
        }
    }


    public function everydayturn($price, $day, $prices)
    {

        $this->price = $price;
        $this->day = $day;


        if ($this->id == 1) {
            $this->description = "sma";
            $data = array_slice($prices, ($day - $this->smaperiod), $this->smaperiod);
            $actualsma = sma_calculate($data)[0];
            array_push($this->smahistory, $actualsma);


            if ($day > $this->start) { //start trading
                $smahistory2 = array_slice($this->smahistory, -100);
                asort($smahistory2);

                if ($actualsma < $smahistory2[$this->smavalue]) //if sma is low
                    $this->buy();

            }
        }


        if ($this->id == 2) {
            $this->description = "buy at relative drop ";
            if ($price > $this->maxprice) //calculations
                $this->maxprice = $price;
            $dropvalue = (($price / $this->maxprice) - 1) * 100;
            array_push($this->drophistory, $dropvalue);
            asort($this->drophistory);

            if ($day > $this->start) { //start trading
                if ($dropvalue < $this->drophistory[$this->tactic2value])
                    $this->buy();
            }
        }


        if ($this->id == 3) {
            $this->description = "buy every month ";
            if ($day > $this->start) //start trading
                $this->buy();
        }



        if ($this->id == 4) {
            $this->description = "buy at 10% drop";
            if ($price > $this->maxprice) //calculations
                $this->maxprice = $price;

            if ($day > $this->start) { //start trading
                if ($price < ($this->maxprice * ((100 - $this->tacticvalue) / 100))) {
                    $this->buy();
                    if ($day == $this->end)
                        $this->todayproposition = "today you should buy";
                } else {
                    if ($day == $this->end)
                        $this->todayproposition = "today you should sell" . "\r\n";
                    if ($this->stock > 0 && $day % $this->sellperiod == 0) {
                        $this->money += $this->sellsize * $price;
                        $this->stock -= $this->sellsize;
                    }
                }
            }
        }
        array_push($this->stockhistory, $this->stock);
        array_push($this->pricehistory, $this->price);

        if ($day > $this->start) {
            if ($day % 30 == 0) { //wypłata
                $this->money += $this->income;
                $this->normalmoney += $this->income;
                $this->income *= $this->incomegrow;
            }

        }
    }
    public function geteffect()
    {
        $value = ($this->price * $this->stock) + $this->money;
        $gain = 100 * ($value - $this->normalmoney) / $this->normalmoney;
        $yeargain = $gain / (($this->end + 1 - $this->start) / 252);
        array_push($this->yeargainhistory, $yeargain);

        $efect = array("tactic" . $this->id, $this->money, $this->stock, $this->price, $this->normalmoney, $this->end, $this->start, $this->description);

        return $efect;
    }



}



//////////////////  *MAIN*  //////////////////
foreach (["AAPL", "SPY", "MCD","PEP","PFE","WMT","MSFT","MAR"] as $tick) {
    $prices = get_prices($tick);
    $end = sizeof($prices) - 1; //2516
    $tacticperiod = 580;

    foreach (range(1, 1, 1) as $botparameter) {
        $tactics = array();
        foreach ((Tactic::$tacticsdisponible) as $id)
            array_push($tactics, new Tactic($id, $start = $end, $end));

        $tactics[0]->buyperiod = $botparameter;
        for ($bot = 710; $bot > 700; $bot -= 70) {

            $start = $bot; //trading start day

            foreach ($tactics as $tactic)
                $tactic->reset($start, $end);

            for ($day = $start - $tacticperiod; $day <= $end; $day++) foreach ($tactics as $tactic)
                    $tactic->everydayturn($prices[$day], $day, $prices);


            $effects = array(); foreach ($tactics as $tactic)
                array_push($effects, $tactic->geteffect());


            // printeffect2($effects);


        } //end bot
        $data = $tactics[0]->yeargainhistory;

        $data = array_filter($data);

        draw2chart($tactics[0]->pricehistory,$tactics[0]->stockhistory,"tmp/".$tick.".png",$tactics[0]->todayproposition);
        print '<img src="/Stocktool/tmp/'.$tick.'.png" />';

        printt($tick . " " .
            $botparameter . "  " .
            average($data) . "  " .
            min($data) . "  " .
            max($data) . "  " .
            $tactics[0]->todayproposition);
        print("\r\n\r\n");
        


    }
    
}

?>

