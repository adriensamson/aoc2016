<?php

$input = file_get_contents(__DIR__.'/input');
$bads = 0;
$goods = 0;

$triangles = [[], [], []];
$i = 0;
foreach (explode("\n", $input) as $row) {
    if (!$row) {
        continue;
    }
    $cols = preg_split('/\s+/', trim($row));
    $cols = array_map(function($s) {return (int)$s;}, $cols);
    $triangles[0][$i] = $cols[0];
    $triangles[1][$i] = $cols[1];
    $triangles[2][$i] = $cols[2];
    $i++;
    if ($i === 3) {
        foreach ($triangles as $sides) {
            if ($sides[0] + $sides[1] <= $sides[2]
                || $sides[0] + $sides[2] <= $sides[1]
                || $sides[1] + $sides[2] <= $sides[0]
            ) {
                $bads++;
            } else {
                $goods++;
            }
        }

        $triangles = [[], [], []];
        $i = 0;
    }
}

echo $goods, PHP_EOL;
