<?php

$input = file_get_contents('input.txt');
$input = explode(";", $input);

$histogram = generateHistogram($input);

$gamma = "";
$epsilon = "";

for($i=0; $i<12; $i++)
{
    if($histogram[$i][0] > $histogram[$i][1]){
        $gamma .= "0";
        $epsilon .= "1";
    }else{
        $gamma .= "1";
        $epsilon .= "0";
    }
}

$gammaDec = bindec($gamma);
$epsilonDec = bindec($epsilon);

echo $gammaDec." * ".$epsilonDec." = ".$gammaDec*$epsilonDec;
echo "\r\n";

$o2 = $input;

for($i=0; $i<12; $i++){
    $histo = generateHistogram($o2);
    if($histo[$i][0]>$histo[$i][1]){
        $o2 = array_filter($o2, function($binary)use($i){
            return $binary[$i] == 0;
        });
    }else{
        $o2 = array_filter($o2, function($binary)use($i){
            return $binary[$i] == 1;
        });
    }
    if(sizeof($o2) == 1) break;
}

$co2 = $input;

for($i=0; $i<12; $i++){
    $histo = generateHistogram($co2);
    if($histo[$i][0]>$histo[$i][1]){
        $co2 = array_filter($co2, function($binary)use($i){
            return $binary[$i] == 1;
        });
    }else{
        $co2 = array_filter($co2, function($binary)use($i){
            return $binary[$i] == 0;
        });
    }
    if(sizeof($co2) == 1) break;
}

$o2 = array_pop($o2);
$co2 = array_pop($co2);

$o2Dec = bindec($o2);
$co2Dec = bindec($co2);

echo $o2Dec." * ".$co2Dec." = ".$o2Dec*$co2Dec;

function generateHistogram(Array $binaryList) : Array
{
    $histogram = [];
    for($i=0; $i<12; $i++){
        $histogram[$i] = [
            0=>0,
            1=>0
        ];
    }

    foreach($binaryList as $binary){
        for($i=0; $i<12; $i++){
            $binary[$i] == 0
            ? $histogram[$i][0] ++
            : $histogram[$i][1] ++;
        }
    }

    return $histogram;
}