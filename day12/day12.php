<?php

$input = file_get_contents('input.txt');
$example1 = file_get_contents('example1.txt');
$example2 = file_get_contents('example2.txt');
$example3 = file_get_contents('example3.txt');

$data = explode("\n", trim($input));

$connections = [];
foreach($data as $c){
    $c = explode("-", trim($c));
    if($c[0] == 'start' || $c[1] == 'end'){
        $connections[$c[0]][] = $c[1];
    }else{
        $connections[$c[0]][] = $c[1];
        $connections[$c[1]][] = $c[0];
    }
}
$connections = array_map(function($c){
    return array_unique($c);
}, $connections);

$routes = [];
$routes2 = [];

routeBuilding($routes, 'start', $connections, []);
routeBuilding($routes2, 'start', $connections, [], true);

$routes = array_unique($routes);
$routes2 = array_unique($routes2);

echo print_r($routes, true)."\n"."Amount of Paths Part 1: ".sizeof($routes)."\n";
echo print_r($routes2, true)."\n"."Amount of Paths Part 2: ".sizeof($routes2)."\n";

function routeBuilding(&$routes, $cave, $connections, $current_route, $smallCaveTwice = false)
{
    $current_route[] = $cave;
    if($cave == 'end'){
        $routes[] = printRoute($current_route);
        return;
    }

    $visitedSmallCaves = array_filter($current_route, function($cave){
        return ctype_lower($cave);
    });

    $visitedSmallCavesHisto = array_count_values($visitedSmallCaves);
    $twiceVisit = array_reduce($visitedSmallCavesHisto, function($carry, $n){
        return $carry || ($n > 1);
    }, false);

    $nextCaves = array_filter($connections[$cave], function($cave)use($visitedSmallCaves, $smallCaveTwice, $twiceVisit){
        if($smallCaveTwice && !$twiceVisit){
            return $cave != 'start';
        }else{
            return !in_array($cave, $visitedSmallCaves) && $cave != 'start';
        }
    });

    foreach($nextCaves as $cave){
        routeBuilding($routes, $cave, $connections, $current_route, $smallCaveTwice);
    }

    return;
}

function printRoute(array $route) : string
{
    return implode("-", $route);
}