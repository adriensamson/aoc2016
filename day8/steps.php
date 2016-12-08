<?php

$input = file_get_contents(__DIR__.'/input');
$screen = array_fill(0, 6, array_fill(0, 50, false));

function rect(&$screen, $a, $b) {
    for ($i = 0; $i < $b; $i++) {
        for ($j = 0; $j < $a; $j++) {
            $screen[$i][$j] = true;
        }
    }
}

function rotateRow(&$screen, $y, $num) {
    $row = $screen[$y];
    $row = array_merge(array_slice($row, -$num), array_slice($row, 0, -$num));
    $screen[$y] = $row;
}

function rotateCol(&$screen, $x, $num) {
    $col = array_map(function ($row) use ($x) {
        return $row[$x];
    }, $screen);
    $col = array_merge(array_slice($col, -$num), array_slice($col, 0, -$num));
    foreach ($col as $i => $b) {
        $screen[$i][$x] = $b;
    }
}

function printScreen($screen) {
    foreach ($screen as $row) {
        foreach ($row as $cell) {
            echo $cell ? "#" : '.';
        }
        echo "\n";
    }
}

function countOn($screen) {
    $n = 0;
    foreach ($screen as $row) {
        foreach ($row as $cell) {
            if ($cell) {
                $n++;
            }
        }
    }
    return $n;
}

foreach (explode("\n", $input) as $cmd) {
    if (!$cmd) {
        continue;
    }
    if (preg_match('/rect (\d+)x(\d+)/', $cmd, $matches)) {
        rect($screen, $matches[1], $matches[2]);
    } else if (preg_match('/rotate row y=(\d+) by (\d+)/', $cmd, $matches)) {
        rotateRow($screen, $matches[1], $matches[2]);
    } else if (preg_match('/rotate column x=(\d+) by (\d+)/', $cmd, $matches)) {
        rotateCol($screen, $matches[1], $matches[2]);
    } else {
        echo $cmd, "\n";
        die;
    }
}

printScreen($screen);

echo countOn($screen), "\n";
