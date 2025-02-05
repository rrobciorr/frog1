<?php
function send($tresc = "demo info "){

    $to = "rrobciorr@gmail.com";
    $temat = "Automatic Market Info";
     mail($to, $temat, $tresc , 'From: robcior.pl@robcior.pl');
}

function get_prices($tickr ="SPY"){     //$tickr = read("favorite.json");

    
    $url = "https://query1.finance.yahoo.com/v8/finance/chart/$tickr?region=US&lang=en-US&includePrePost=false&interval=1d&useYfid=true&range=10y";
    $stock_data = json_decode(file_get_contents($url), true);
    $ydata =  $stock_data['chart']['result'][0]['indicators']['quote']['0']['close'];
    return $ydata ;
}

function average($data,$period){    
    
    $arr = array_slice($data,- $period);
    $avr = array_sum($arr)/count($arr);
    return $avr; 
}

function sma_calculate($data){//Simple Moving Average (SMA)   //$period =26; //12

    $period=26;
    $avr =average($data,$period);
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

for($bot = 2518 ;$bot>200;$bot-=20 ){

$smaperiod = 26;
$tacticperiod = 100;
$tacticvalue = 1 ;
$start= $bot; //trading start day
$end=sizeof($prices);//2518
$money = 10000;
$divide = 80;
$buysize = $money / $divide;
$stock = 0;


$smahistory=array();
for($day = $start-$tacticperiod;     $day<$end;    $day++){
    $data = array_slice($prices,($day-$smaperiod) ,$smaperiod);
    $actualsma = sma_calculate($data)[0];
    array_push($smahistory,$actualsma );
    if ($day > $start){
        $smahistory2 = array_slice($smahistory,-100);
        asort($smahistory2);
        
        if($actualsma<$smahistory2[$tacticvalue]){
            //print($actualsma ."\r\n");
            
            //buy
            if($money>0){
                //print("b");
            $stock += $buysize / $prices[$day];
            $money -= $buysize ;
            }
        }

    }
}
//print(" value: " .($prices[$day-1] * $stock)+$money ." stock: ". $stock. " money: " . $money  .  "\r\n");

prettyprint(" value: " .(($prices[$day-1] * $stock)+$money )." stock: ". $stock. " money: " . $money);

}//end bot
// print($sma[1]);

 //prettyprint($smahistory);





// $smahistory=array();
// for ($x = -180; $x < 0; $x++) {

// $sma= sma_calculate(  $prices);
// $info = $tick. " ". $sma[1];

// array_push($smahistory,$sma[0]);
// array_pop($prices);
// }

// prettyprint($smahistory);

// asort($smahistory);
// prettyprint($smahistory);

// $smahistory = array_slice($smahistory, 0,10);
// prettyprint($smahistory);


// $H = date('H');     $M = date('i') ;    //time
// if((abs($sma[0])>8 & $M<15)  | ($H==17 & $M<15)  |  (abs($sma[0])>10) |  ($sma[0]<0 & $M<15)){   

//     //send($info);
//     //print($info);
// }
?>