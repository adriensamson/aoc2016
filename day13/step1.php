<?php

$number = 1352;

function isWall($x, $y) {
    global $number;
    $n = $x * $x + 3 * $x + 2 * $x * $y + $y + $y * $y + $number;
    $b = base_convert($n, 10, 2);
    return (substr_count($b, '1') % 2) === 1;
}

$currents = [[1,1]];
$seen = $currents;
$i = 0;

function nextPositions($currents, &$seen) {
    $nexts = [];
    $addNext = function ($next) use (&$seen, &$nexts) {
        if ($next[0] < 0 || $next[1] < 0) {
            return;
        }
        if (in_array($next, $seen)) {
            return;
        }
        list($x, $y) = $next;
        if (isWall($x, $y)) {
            return;
        }
        $nexts[] = $next;
        $seen[] = $next;
    };
    foreach ($currents as list($x, $y)) {
        $addNext([$x + 1, $y]);
        $addNext([$x - 1, $y]);
        $addNext([$x, $y + 1]);
        $addNext([$x, $y - 1]);
    }

    return $nexts;
}

while (true) {
    $i++;
    $nexts = nextPositions($currents, $seen);
    foreach ($nexts as list($x, $y)) {
        if ($x === 31 && $y === 39) {
            echo $i, "\n";
            die;
        }
    }
    $currents = $nexts;
    printf("%d (%d - %d)\n", $i, count($currents), count($seen));
}
