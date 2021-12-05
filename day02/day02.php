<?php

require_once 'Submarine.php';

$input = file_get_contents('input.txt');
$input = explode(";", $input);

$submarine = new Submarine(0,0);

foreach($input as $command){
    $command = explode(" ", $command);
    $instruction = $command[0];
    $parameter = intval($command[1]);
    call_user_func([$submarine, $instruction], $parameter);
}

echo "Positions multiplied: ".$submarine->getDepth()*$submarine->getHorizontalPosition();