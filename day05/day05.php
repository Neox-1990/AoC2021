<?php

$input = file_get_contents('input.txt');
$input = explode("\n", trim($input));

$matrix = array_fill(0,1000,array_fill(0,1000,0));

$lines = array_map(function($line){
    $points = explode(" -> ", trim($line));
    $points = array_map(function($point){
        $p = explode(",", trim($point));
        return [
            'x' => intval($p[0]),
            'y' => intval($p[1])
        ];
    }, $points);
    return $points;
}, $input);

//Filter out all non horizontal or vertical lines
$vhlines = array_filter($lines, function($points){
    return $points[0]['x'] == $points[1]['x'] || $points[0]['y'] == $points[1]['y'];
});

$dlines = array_filter($lines, function($points){
    return $points[0]['x'] != $points[1]['x'] && $points[0]['y'] != $points[1]['y'];
});

$crossings = 0;

foreach($vhlines as $line){
    for( $x = min($line[0]['x'],$line[1]['x']) ; $x <= max($line[0]['x'],$line[1]['x']) ; $x++ ){
        for( $y = min($line[0]['y'],$line[1]['y']) ; $y <= max($line[0]['y'],$line[1]['y']) ; $y++ ){
            $matrix[$x][$y]++;
            if($matrix[$x][$y] == 2){
                $crossings++;
            }
        }
    }
}

echo "\nVH Crossings: ".$crossings;

foreach($dlines as $line){
    $distance = max($line[0]['x'],$line[1]['x']) - min($line[0]['x'],$line[1]['x']);
    $xDirection = ($line[1]['x'] - $line[0]['x']) / $distance;
    $yDirection = ($line[1]['y'] - $line[0]['y']) / $distance;

    for( $i = 0 ; $i <= $distance ; $i++ ){
        $x = $line[0]['x'] + $i * $xDirection;
        $y = $line[0]['y'] + $i * $yDirection;

        $matrix[$x][$y]++;
        if($matrix[$x][$y] == 2){
            $crossings++;
        }
    }
}

echo "\nAll Crossings: ".$crossings;

function printMatrix(Array $matrix) : string
{
    $str = "";

    $matrix = array_map(function($row){
        return implode(" ", $row);
    }, $matrix);

    $str = implode("\n", $matrix);

    return $str;
}