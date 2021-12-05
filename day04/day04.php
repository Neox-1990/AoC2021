<?php

$input = file_get_contents('input.txt');
$input = explode("\n\n", trim($input));

$bingonumbers = array_shift($input);
$bingonumbers = explode(",", trim($bingonumbers));
$bingonumbers = array_map(function($val){
    return intval($val);
}, $bingonumbers);

$boards = array_map(function($b){
    $lines = explode("\n", trim($b));
    $lines = array_map(function($l){
        $rows = explode(" ", trim($l));
        $rows = array_filter($rows, function($val){
            return $val != "";
        });
        $rows = array_map(function($val){
            return intval($val);
        }, $rows);
        return array_values($rows);
    }, $lines);

    return $lines;
}, $input);

$winners = [];

for($i=5; $i<sizeof($bingonumbers); $i++){
    $numbers = array_values($bingonumbers);
    $numbers = array_splice($numbers,0,$i);
    //echo implode(',',$numbers)."\n";
    foreach($boards as $key => $board){
        if(checkForBingo($board, $numbers)){
            if(!array_key_exists($key, $winners)){
                $winners[$key] = [
                    'key' => $key,
                    'score' => calculateScore($board, $numbers)
                ];
            }
        }
    }
}
$first = array_shift($winners);
$last = array_pop($winners);
echo "\nFirst BINGO: Board ".$first['key']." with a score of ".$first['score'];
echo "\nLast BINGO: Board ".$last['key']." with a score of ".$last['score'];


function checkForBingo(Array $board, Array $numbers) : bool{
    $bingo = false;
    for($i=0; $i<5; $i++){
        $rowsum = 0;
        $colsum = 0;
        for($j=0; $j<5; $j++){
            in_array($board[$i][$j], $numbers) ? $rowsum++ : null;
            in_array($board[$j][$i], $numbers) ? $colsum++ : null;
        }
        //echo "\nRowsum: ".$rowsum." Colsum: ".$colsum;
        if($rowsum >= 5 || $colsum >= 5){
            return true;
        }
    }

    return false;
}

function calculateScore(Array $board, Array $numbers) : int
{
    $sum = 0;
    for($i=0; $i<5; $i++){
        for($j=0; $j<5; $j++){
            !in_array($board[$i][$j], $numbers) ? $sum += $board[$i][$j] : null;
        }
    }
    $multipier = array_pop($numbers);

    return $multipier*$sum;
}