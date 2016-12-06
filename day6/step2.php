<?php

$input = file_get_contents(__DIR__.'/input');

$chars = array_fill(0, 7, []);

foreach (explode("\n", $input) as $row) {
    if (!$row) {
        continue;
    }
    foreach (str_split($row) as $i => $c) {
        if (!isset($chars[$i][$c])) {
            $chars[$i][$c] = 0;
        }
        $chars[$i][$c]++;
    }
}

foreach ($chars as $possibilities) {
    asort($possibilities);
    reset($possibilities);
    echo key($possibilities);
}

echo "\n";
