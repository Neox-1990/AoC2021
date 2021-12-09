<?php

$input = file_get_contents('input.txt');

$example = file_get_contents('example.txt');

$test = explode("\n", trim($input));
file_put_contents('debug.txt', implode("\n", $test));

$map = array_map(function($row){
    return array_map(function($v){
        return intval($v);
    }, str_split(trim($row)));
},explode("\n", trim($input)));

$minimumsum = 0;
$minimalocations = [];
$maxX = sizeof($map)-1;
$maxY = sizeof($map[0])-1;

for( $x = 0 ; $x <= $maxX ; $x++ ){
    for( $y = 0 ; $y <= $maxY ; $y++ ){
        $value = $map[$x][$y];
        $tocheck = [$value];
        //up
        $y>0 ? $tocheck[] = $map[$x][$y-1] : null;
        //down
        $y<$maxY ? $tocheck[] = $map[$x][$y+1] : null;
        //left
        $x>0 ? $tocheck[] = $map[$x-1][$y] : null;
        //right
        $x<$maxX ? $tocheck[] = $map[$x+1][$y] : null;
        
        $histo = array_count_values($tocheck);
        
        if(min($tocheck) == $value && $histo[$value] == 1){
            $minimumsum += $value+1;
            $minimalocations[] = [
                'x' => $x,
                'y' => $y,
            ];
        }
    }
}

echo "Risklevel: ".$minimumsum."\n";

$map_copy = $map;
$basinsizes = array_map(function($l)use(&$map_copy){
    return growCount($l['x'], $l['y'], $map_copy);
}, $minimalocations);
rsort($basinsizes, SORT_NUMERIC);

echo "Product of the three larges basins: ".($basinsizes[0]*$basinsizes[1]*$basinsizes[2])."\n";

function growCount($x, $y, &$map) {
    $maxX = sizeof($map)-1;
    $maxY = sizeof($map[0])-1;
    $map[$x][$y] = 9;
    return 1 + 
    ($y > 0 && $map[$x][$y-1] != 9 ? growCount($x, $y-1, $map) : 0) +
    ($y < $maxY && $map[$x][$y+1] != 9 ? growCount($x, $y+1, $map) : 0) +
    ($x > 0 && $map[$x-1][$y] != 9 ? growCount($x-1, $y, $map) : 0) +
    ($x < $maxX && $map[$x+1][$y] != 9 ? growCount($x+1, $y, $map) : 0);
}