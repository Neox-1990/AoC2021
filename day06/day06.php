<?php

ini_set('memory_limit', '16G');

$input = file_get_contents('input.txt');
$input = explode(",", trim($input));

$swarm = array_map(function($val){
    return intval($val);
}, $input);

$days = 80;

for($i=$days; $i>0; $i--){
    $swarm_copy = $swarm;
    foreach($swarm_copy as $key => $fish){
        if($fish == 0){
            $swarm[$key] = 6;
            array_push($swarm, 8);
        }else{
            $swarm[$key]--;
        }
    }
    //$days--;
}

echo "Swarmsize after 80 days: ".sizeof($swarm)."\n";

$swarm = array_map(function($val){
    return intval($val);
}, $input);

$days = 256;

$swarmsize = offspring3($swarm, $days);

echo "Swarmsize after ".$days." days: ".$swarmsize."\n";

//Stupid
function offspring($fish, $days){
    $offspring = 0;
    if($fish>$days) return 1;
    for($i=$days; $i>0; $i--){
        if($fish <= 0){
            $fish = 6;
            $offspring += offspring(8, $i-1);
        }else{
            $fish--;
        }
    }

    return 1 + $offspring;
}

//Still Stupid
function offspring2($fish, $days){
    if($fish>$days) return 1;
    $offspring = 0;
    if($fish == 8){
        $days -= 8;
    }else{
        $days -= $fish%7;
    }
    for($i=$days; $i>0; $i -= 7){
        $offspring += offspring2(8, $i-1);
    }

    return 1 + $offspring;
}

//Facepalm that i didnt got this earlier
function offspring3($swarm, $days){
    $fishes = array_fill(0,9,0);
    foreach($swarm as $fish){
        $fishes[$fish]++;
    }
    
    for($i = 0; $i < $days; $i++){
        $fishes_next = [
            $fishes[1],
            $fishes[2],
            $fishes[3],
            $fishes[4],
            $fishes[5],
            $fishes[6],
            $fishes[0]+$fishes[7],
            $fishes[8],
            $fishes[0]
        ];

        $fishes = $fishes_next;
    }

    return array_sum($fishes);
}