<?php
include "tablelib.php";

define("isbrowser", 1);


function prettyprint($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}
function prettyprint_n($arr)
{
    echo "<pre>";
    print($arr);
    echo "</pre>";
}



function printeffect2($effects)
{

    $rows = array();
    foreach ($effects as &$tactic) {

        $tacticname = $tactic[0];
        $money = $tactic[1];
        $stock = $tactic[2];
        $actualprice = $tactic[3];
        $normalmoney = $tactic[4];
        $end = $tactic[5];
        $start = $tactic[6];
        $description = $tactic[7];

        $value = ($actualprice * $stock) + $money;
        $gain = 100 * ($value - $normalmoney) / $normalmoney;
        $yeargain = $gain / (($end - $start) / 252);

        $row = array(
            'name' => $tacticname,
            'yeargain' => round($yeargain, 1) . "%",
            'gain' => round($gain, 1) . "%",
            'value' => round($value),
            " normalmoney" => round($normalmoney),
            " stock" => round($stock),
            'money' => round($money),
            'opis' => $description
        );
        array_push($rows, $row);

        if (isbrowser == 0) {
            print($tacticname .
                " yeargain: " . round($yeargain, 1) . "%" .
                " gain: " . round($gain, 1) . "%" .
                " value: " . round($value) .
                " normalmoney: " . round($normalmoney) .
                " stock: " . round($stock) .
                " money: " . round($money) .
                " " . $description .
                "\r\n");
        }

    }

    if (isbrowser) {
        echo build_table($rows);
        prettyprint("<br>\n");
    } else
        print("\r\n");
}


function printt($data)
{
    if (isbrowser) {

        prettyprint($data);

        prettyprint("<br>\n");
    } else {
        print($data);
        print("\r\n");
    }
}
?>>