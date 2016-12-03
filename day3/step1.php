<?php

$input = file_get_contents(__DIR__.'/input');
$bads = 0;
$goods = 0;

foreach (explode("\n", $input) as $triangle) {
    if (!$triangle) {
        continue;
    }
    $sides = preg_split('/\s+/', trim($triangle));
    $sides = array_map(function($s) {return (int)$s;}, $sides);
    if ($sides[0] + $sides[1] <= $sides[2]
        || $sides[0] + $sides[2] <= $sides[1]
        || $sides[1] + $sides[2] <= $sides[0]
    ) {
        $bads++;
    } else {
        $goods++;
    }
}

echo $goods, PHP_EOL;
