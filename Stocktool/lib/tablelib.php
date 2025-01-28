<?php
    function build_table($array){
    // start table
    $html = '<table>';
    // header row
    $html .= '<tr>';
    foreach($array[0] as $key=>$value){
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
    $html .= '</tr>';

    // data rows
    foreach( $array as $key=>$value){
        $html .= '<tr>';
        foreach($value as $key2=>$value2){
            $html .= '<td>' . htmlspecialchars($value2) . '</td>';
        }
        $html .= '</tr>';
    }

    // finish table and return it

    $html .= '</table>';
    return $html;
}

$array = array(
    array('first'=>'tom', 'last'=>'smith', 'email'=>'tom@example.org', 'company'=>'example ltd'),
    array('first'=>'hugh', 'last'=>'blogs', 'email'=>'hugh@example.org', 'company'=>'example ltd'),
    array('first'=>'steph', 'last'=>'brown', 'email'=>'steph@example.org', 'company'=>'example ltd')
);

// echo build_table($array);
?>