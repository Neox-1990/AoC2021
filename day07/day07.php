<?php

$input = file_get_contents('input.txt');
$input = explode(",", trim($input));

$example = file_get_contents('example.txt');
$example = explode(",", trim($example));

$positions = $input;
$positions_sorted = $input;
sort($positions_sorted, SORT_NUMERIC);

$average = round(array_sum($positions)/sizeof($positions));
$median = $positions_sorted[abs(sizeof($positions_sorted)/2)];
$min = $positions_sorted[0];
$max = $positions_sorted[sizeof($positions_sorted)-1];

$fcAverage = fuelConsumption($positions, $average);
$fcMedian = fuelConsumption($positions, $median);
$fcBruteforce = bruteforce('fuelConsumption', $positions, $min, $max);

$cfcAverage = crabFuelConsumption($positions, $average);
$cfcMedian = crabFuelConsumption($positions, $median);
$cfcBruteforce = bruteforce('crabFuelConsumption', $positions, $min, $max);

echo "Average: ".$average." Fuel: ".$fcAverage." Crabfuel: ".$cfcAverage."\n";
echo "Median: ".$median." Fuel: ".$fcMedian." Crabfuel: ".$cfcMedian."\n";
echo "Bruteforce: ".$fcBruteforce[1]."|".$cfcBruteforce[1]." Fuel: ".$fcBruteforce[0]." Crabfuel: ".$cfcBruteforce[0]."\n";

function fuelConsumption(Array $positions, int $p) : int 
{
    $distances = array_map(function($position)use($p){
       return abs($position-$p); 
    }, $positions);
    
    return array_sum($distances);
}

function crabFuelConsumption(Array $positions, int $p) : int
{
    $distances = array_map(function($position)use($p){
        $d = abs($position-$p);
        $d = ($d*($d+1))/2;
        
        return $d;
    }, $positions);
        
        return array_sum($distances);
}

function bruteForce(callable $consumption, array $positions, int $min, int $max) : array
{
    $minFuel = $consumption($positions, $min);
    $minPosition = $min;
    for( $i=$min+1 ; $i<=$max ; $i++ ){
        $fuel = $consumption($positions, $i);
        if($minFuel > $fuel){
            $minFuel = $fuel;
            $minPosition = $i;
        }
    }
    return [$minFuel, $minPosition];
}