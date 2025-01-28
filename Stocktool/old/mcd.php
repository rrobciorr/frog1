<?php
function send($tresc = "demo info "){

    $to = "rrobciorr@gmail.com";
    $temat = "Automatic Market Info";
     mail($to, $temat, $tresc , 'From: robcior.pl@robcior.pl');
}

function get_prices($tickr ="SPY"){     //$tickr = read("favorite.json");

    
    $url = "https://query1.finance.yahoo.com/v8/finance/chart/$tickr?region=US&lang=en-US&includePrePost=false&interval=1d&useYfid=true&range=1y";
    $stock_data = json_decode(file_get_contents($url), true);
    $ydata =  $stock_data['chart']['result'][0]['indicators']['quote']['0']['close'];
    return $ydata ;
}

function sma($data,$period){    //Simple Moving Average (SMA)   //$period =26; //12
    
    $arr = array_slice($data,- $period);
    $avr = array_sum($arr)/count($arr);
    return $avr; 
}

function sma_calculate($data){

    $period=26;
    $avr =sma($data,$period);
    $last_price = $data[sizeof($data)-1];
    $perc_gain = 100*($last_price - $avr) /$avr ;
    $perc_gain = round($perc_gain,1);

    $sma_info = "Last price is ". $perc_gain . " % ".["below ","above "][$perc_gain>0]. $period." days average.";
    return [$perc_gain, $sma_info];
}
function prettyprint($arr){
echo "<pre>";
print_r($arr);
echo "</pre>";
}
//////////////////  *MAIN*  //////////////////
$tick ="MCD"; 
$prices = get_prices($tick);
$smahistory=array();
for ($x = -180; $x < 0; $x++) {

$sma= sma_calculate(  $prices);
$info = $tick. " ". $sma[1];

array_push($smahistory,$sma[0]);
array_pop($prices);
}

prettyprint($smahistory);

asort($smahistory);
prettyprint($smahistory);

$smahistory = array_slice($smahistory, 0,10);
prettyprint($smahistory);

$H = date('H');     $M = date('i') ;    //time




if((abs($sma[0])>8 & $M<15)  | ($H==17 & $M<15)  |  (abs($sma[0])>10) |  ($sma[0]<0 & $M<15)){   

    //send($info);
    //print($info);
}
?>