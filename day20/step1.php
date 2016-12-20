<?php

$input = trim(file_get_contents(__DIR__.'/input'));

$ranges = [];

foreach (explode("\n", $input) as $row) {
    $ranges[] = explode('-', $row);
}

usort($ranges, function ($r1, $r2) {
    return $r1[0] <=> $r2[0];
});

$try = 0;
foreach ($ranges as list($from, $to)) {
    if ($from <= $try) {
        $try = max($try, $to+1);
    }
    if ($try < $from) {
        echo $try, "\n";die;
    }
}
