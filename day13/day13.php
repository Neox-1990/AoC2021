<?php

$input = str_replace("\r\n", "\n", file_get_contents('input.txt'));
$example = str_replace("\r\n", "\n", file_get_contents('example.txt'));

$data = explode("\n\n", trim($input));

//echo sizeof($data);
//die();

$dots = array_map(function($dot){
    $dot = explode(",", trim($dot));
    return [
        'x' => intval($dot[0]),
        'y' => intval($dot[1])
    ];
} ,explode("\n" ,trim($data[0])));

$folds = array_map(function($f){
    $f = explode("=", $f);
    return [
        'direction' => substr($f[0], -1, 1),
        'line' => intval($f[1])
    ];
},explode("\n", $data[1]));

$maxX = array_reduce($dots, function($carry, $dot){
    return max($carry, $dot['x']);
}, 0);
$maxY = array_reduce($dots, function($carry, $dot){
    return max($carry, $dot['y']);
}, 0);

$dotpaper = array_fill(0, $maxY+1, array_fill(0, $maxX+1, 0));

foreach($dots as $dot){
    $dotpaper[$dot['y']][$dot['x']] = 1;
}

printPaper($dotpaper);

$fold1paper = fold($dotpaper, $folds[0]);
//fold($dotpaper, $folds[1]);

echo "Dots after first fold (part1): ".countDots($fold1paper)."\n";

$fold2paper = $dotpaper;
foreach($folds as $fold){
    $fold2paper = fold($fold2paper, $fold);
}
$code = printPaper($fold2paper);
$code = str_replace(['0', '1'], [' ', '#'], $code);
echo "Paper after all folds: \n\n".$code;

function fold($paper, $fold){
    $newpaper = $paper;
    if($fold['direction'] == 'x'){
        $newpaper = array_map(function($line)use($fold){
            $line = array_splice($line, 0, $fold['line']);
            return $line;
        }, $newpaper);
    }else{
        $newpaper = array_splice($newpaper, 0, $fold['line']);
    }

    for($x=0 ; $x < sizeof($newpaper[0]); $x++){
        for($y=0 ; $y < sizeof($newpaper); $y++){
            if($fold['direction'] == 'x'){
                $mirrorX = ($fold['line'] - $x) + $fold['line'];
                $mirrorY = $y;
            }else{
                $mirrorX = $x;
                $mirrorY = ($fold['line'] - $y) + $fold['line'];
            }

            $newpaper[$y][$x] = intval($newpaper[$y][$x] || $paper[$mirrorY][$mirrorX]);
        }
    }



    printPaper($newpaper, true);

    return $newpaper;
}

function printPaper($paper, $append = false){
    $paper = implode("\r\n", array_map(function($line){
        return implode("", $line);
    }, $paper));

    file_put_contents("debug.txt", "\r\n".$paper."\r\n", $append ? FILE_APPEND : 0);

    return $paper;
}

function countDots($paper){
    $paper = array_map(function($line){
        return array_sum($line);
    }, $paper);

    return array_sum($paper);
}