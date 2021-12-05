<?php

$input = file_get_contents('input.txt');
$input = explode(";", $input);
$input = array_map(function($val){
    return intval($val);
}, $input);

$increases = 0;

for($i=0; $i<(sizeof($input)-1); $i++){
    if($input[$i] < $input[$i+1]){
        $increases++;
    }
}
echo "Increases: ".$increases."\r\n";

$slidingIncreases = 0;
for($i=2; $i<sizeof($input)-1; $i++){
    $sumA = $input[$i-2] + $input[$i-1] + $input[$i];
    $sumB = $input[$i-1] + $input[$i] + $input[$i+1];
    if($sumA < $sumB){
        $slidingIncreases++;
    }
}
echo "Sliding Increases: ".$slidingIncreases;