<?php

$input = file_get_contents('input.txt');
$input = explode("\n", trim($input));

$example = file_get_contents('example.txt');
$example = explode("\n", trim($example));

$decoder = [
    0 => 'abcefg',
    1 => 'cf',
    2 => 'acdeg',
    3 => 'acdfg',
    4 => 'bcdf',
    5 => 'abdfg',
    6 => 'abdefg',
    7 => 'acf',
    8 => 'abcdefg',
    9 => 'abcdfg',
];

$data = array_map(function($line){
    $line = explode("|", trim($line));
    $in = explode(" ", trim($line[0]));
    $out = explode(" ", trim($line[1]));

    return [$in, $out];
}, $input);

$part1 = array_reduce($data, function($carry, $line){
    $out = array_filter($line[1], function(string $str){
        return (strlen($str) == 7 || strlen($str) <= 4);
    });

    return $carry + sizeof($out);
},0);

echo "Number of 1,4,7 an 8: ".$part1."\n";

$sample = [
    ['acedgfb', 'cdfbe', 'gcdfa', 'fbcad', 'dab', 'cefabd', 'cdfgeb', 'eafb', 'cagedb', 'ab'],
    ['cdfeb', 'fcadb', 'cdfeb', 'cdbaf']
];

decode($sample[0], $sample[1]);

$part2 = array_map(function($line){
    return decode($line[0], $line[1]);
}, $data);

$part2 = array_sum($part2);

echo "Sum of the outputs: ".$part2."\n";

function decode(array $in, array $out){
    $samples = [];
    foreach($in as $s){
        $samples[strlen($s)][] = $s;
    }

    $multiplex = [];
    //Known
    $multiplex[orderString($samples[2][0])] = 1;
    $multiplex[orderString($samples[3][0])] = 7;
    $multiplex[orderString($samples[4][0])] = 4;
    $multiplex[orderString($samples[7][0])] = 8;

    //Zero, Six and Nine
    foreach($samples[6] as $key => $str){
        $intersect1 = array_intersect(
            str_split($samples[2][0]),
            str_split($str)
        );
        $intersect4 = array_intersect(
            str_split($samples[4][0]),
            str_split($str)
        );

        if(sizeof($intersect1) == 1){
            $multiplex[orderString($str)] = 6;
        }elseif(sizeof($intersect4) == 4){
            $multiplex[orderString($str)] = 9;
        }else{
            $multiplex[orderString($str)] = 0;
        }
    }

    //Two, Three and Five
    foreach($samples[5] as $key => $str){
        $intersect1 = array_intersect(
            str_split($samples[2][0]),
            str_split($str)
        );
        $intersect4 = array_intersect(
            str_split($samples[4][0]),
            str_split($str)
        );

        if(sizeof($intersect1) == 2){
            $multiplex[orderString($str)] = 3;
        }elseif(sizeof($intersect4) == 3){
            $multiplex[orderString($str)] = 5;
        }else{
            $multiplex[orderString($str)] = 2;
        }
    }

    $number = intval(
        implode(
            "",
            array_map(function($str)use($multiplex){
                return $multiplex[orderString($str)];
            },$out)
            )
    );

    return $number;
}

function orderString(string $str) : string
{
    $str = str_split($str);
    sort($str);
    return implode("", $str);
}