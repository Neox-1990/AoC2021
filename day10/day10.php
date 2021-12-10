<?php

$input = file_get_contents('input.txt');

$example = file_get_contents('example.txt');

$data = explode("\n", $input);
$data = array_map(function($line){
    return str_split(trim($line));
}, $data);

$chunk = [
    '(' => ')',
    '[' => ']',
    '{' => '}',
    '<' => '>',
];
$chunk_flip = array_flip($chunk);
$scores = [
    ')' => 3,
    ']' => 57,
    '}' => 1197,
    '>' => 25137,
];
$scores2 = [
    ')' => 1,
    ']' => 2,
    '}' => 3,
    '>' => 4,
];

$errorscore = 0;
$autocompletescore = [];

foreach ($data as $line){
    $lifo = [];
    $error = false;
    for($i=0 ; $i<sizeof($line) ; $i++){
        $c = $line[$i];
        if(in_array($c, $chunk_flip)){
            array_push($lifo, $c);
        }else{
            if($chunk_flip[$c] == $lifo[max(sizeof($lifo)-1,0)] ?? ""){
                array_pop($lifo);
            }else{
                $errorscore += $scores[$c];
                $error = true;
                break;
            }
        }
    }
    //Part 2
    if(sizeof($lifo) > 0 && !$error){
        $lifo = array_map(function($c)use($chunk, $scores2){
            return $scores2[$chunk[$c]];
        }, array_reverse($lifo));
            $autocompletescore[] = array_reduce($lifo, function($carry, $score){
                return $carry * 5 + $score;
            },0);
    }
}

sort($autocompletescore);
$autocompletescore = $autocompletescore[(sizeof($autocompletescore)-1)/2];

echo "Errorscore: ".$errorscore."\n";
echo "Autocompletescore: ".$autocompletescore."\n";