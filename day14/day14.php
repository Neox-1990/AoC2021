<?php

$input = str_replace("\r\n", "\n", file_get_contents('input.txt'));
$example = str_replace("\r\n", "\n", file_get_contents('example.txt'));

$data = explode("\n\n", trim($input));

$template = $data[0];
$data[1] = explode("\n", trim($data[1]));
$rules;

foreach($data[1] as $rule){
    $rule = explode("->", trim($rule));
    $rules[trim($rule[0])] = trim($rule[1]);
}

$part1 = $template;
for($i=0 ; $i<10 ; $i++){
    $part1 = patterInsert($part1, $rules);
}

$part1 = array_count_values(str_split($part1));
sort($part1);
$part1 = $part1[sizeof($part1)-1] - $part1[0];
echo "Part1: ".$part1."\n";

$part2 = $template;
$part2 = patternInsert2($part2, $rules, 40);
echo "Part2: ".$part2."\n";

function patterInsert($template, $rules){
    $template = str_split($template);
    $new = [];

    for($i=0 ; $i<sizeof($template)-1 ; $i++){
        $snip = implode("", [$template[$i], $template[$i+1]]);
        $add = $rules[$snip];
        $new[] = $template[$i];
        $new[] = $add;

        if(sizeof($template)-2 == $i){
            $new[] = $template[$i+1];
        }
    }

    return implode("", $new);
}

function patternInsert2($template, $rules, $steps){
    $snipHistogram = array_map(function($rule){
        return 0;
    }, $rules);

    //initialize Histograms
    $template = str_split($template);
    for($i=0 ; $i<sizeof($template)-1 ; $i++){
        $snipHistogram[$template[$i].$template[$i+1]]++;
    }

    $charHistogram = array_map(function($val){
        return 0;
    },array_flip(array_unique(str_split(implode("",array_keys($rules))))));
    $charcount = array_count_values($template);
    foreach($charcount as $c => $n){
        $charHistogram[$c] = $n;
    }

    //calculate step
    for($i=0 ; $i<$steps ; $i++){
        $snipHistogramTemp = array_map(function($val){return 0;},$snipHistogram);
        foreach($snipHistogram as $snip => $n){
            $new_c = $rules[$snip];
            $charHistogram[$new_c] += $n;
            $snip = str_split($snip);
            $snipHistogramTemp[$snip[0].$new_c] += $n;
            $snipHistogramTemp[$new_c.$snip[1]] += $n;
        }
        $snipHistogram = $snipHistogramTemp;
    }
    sort($charHistogram);

    return $charHistogram[sizeof($charHistogram)-1] - $charHistogram[0];
}