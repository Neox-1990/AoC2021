<?php

$input = file_get_contents('input.txt');
$example = file_get_contents('example.txt');

$data = array_map(function($line){
    return array_map(function($val){
        return intval($val);
    }, str_split(trim($line)));
},explode("\n", trim($input)));

//$minX = $minY = 0;
//$maxX = sizeof($data);
//$maxY = sizeof($data[0]);


//Part 1
$part1data = $data;
$steps = 100;
$flashes = 0;

file_put_contents('debug.txt', printMatrix($part1data,0));

for($i=0 ; $i<$steps ; $i++){
    $flashes += step($part1data);
    file_put_contents('debug.txt', printMatrix($part1data,$i+1), FILE_APPEND);
}
echo "Flashes after ".$steps." Steps: ".$flashes."\n";

//Part2
$part2data = $data;
$stepcount = 0;
file_put_contents('debug2.txt', printMatrix($part2data,0));
do{
    step($part2data);
    $stepcount++;
    file_put_contents('debug2.txt', printMatrix($part2data,$stepcount), FILE_APPEND);
}while(!checkForSuperflash($part2data) && $stepcount < 1000);
echo "First superflash at step ".$stepcount."\n";

function step(&$data){
    for($x=0 ; $x<10 ; $x++){
        for($y=0 ; $y<10 ; $y++){
            $octo  = $data[$x][$y];
            if($octo < 9){
                $data[$x][$y]++;
            }elseif($octo == 9){
                $data[$x][$y]++;
                flash($data, $x, $y);
            }
        }
    }

    return resetAndCount($data);
}

function flash(&$data, $x, $y){
    //N
    if($y>0 && $data[$x][$y-1] < 10){
        if(++$data[$x][$y-1] > 9){
            flash($data, $x, $y-1);
        }
    }
    //S
    if($y<9 && $data[$x][$y+1] < 10){
        if(++$data[$x][$y+1] > 9){
            flash($data, $x, $y+1);
        }
    }
    //W
    if($x>0 && $data[$x-1][$y] < 10){
        if(++$data[$x-1][$y] > 9){
            flash($data, $x-1, $y);
        }
    }
    //E
    if($x<9 && $data[$x+1][$y] < 10){
        if(++$data[$x+1][$y] > 9){
            flash($data, $x+1, $y);
        }
    }
    //NW
    if($x>0 && $y>0 && $data[$x-1][$y-1] < 10){
        if(++$data[$x-1][$y-1] > 9){
            flash($data, $x-1, $y-1);
        }
    }
    //NE
    if($x<9 && $y>0 && $data[$x+1][$y-1] < 10){
        if(++$data[$x+1][$y-1] > 9){
            flash($data, $x+1, $y-1);
        }
    }    
    //SE
    if($x<9 && $y<9 && $data[$x+1][$y+1] < 10){
        if(++$data[$x+1][$y+1] > 9){
            flash($data, $x+1, $y+1);
        }
    }    
    //SW
    if($x>0 && $y<9 && $data[$x-1][$y+1] < 10){
        if(++$data[$x-1][$y+1] > 9){
            flash($data, $x-1, $y+1);
        }
    }
}

function resetAndCount(&$data){
    $flashes = 0;
    for($x=0 ; $x<10 ; $x++){
        for($y=0 ; $y<10 ; $y++){
            if($data[$x][$y] > 9){
                $flashes++;
                $data[$x][$y] = 0;
            }
        }
    }

    return $flashes;
}

function printMatrix($data, int $stepcount) : string
{
    $matrix = array_reduce($data, function($carry, $line){
        return $carry."\n".implode("", $line);
    },"\nAfter Step ".$stepcount);

    return $matrix."\n";
}

function checkForSuperflash($data) : bool
{
    for($x=0 ; $x<10 ; $x++){
        for($y=0 ; $y<10 ; $y++){
            if($data[$x][$y] != 0){
                return false;
            }
        }
    }
    return true;
}