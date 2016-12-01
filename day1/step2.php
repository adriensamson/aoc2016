<?php

$input = 'R1, R1, R3, R1, R1, L2, R5, L2, R5, R1, R4, L2, R3, L3, R4, L5, R4, R4, R1, L5, L4, R5, R3, L1, R4, R3, L2, L1, R3, L4, R3, L2, R5, R190, R3, R5, L5, L1, R54, L3, L4, L1, R4, R1, R3, L1, L1, R2, L2, R2, R5, L3, R4, R76, L3, R4, R191, R5, R5, L5, L4, L5, L3, R1, R3, R2, L2, L2, L4, L5, L4, R5, R4, R4, R2, R3, R4, L3, L2, R5, R3, L2, L1, R2, L3, R2, L1, L1, R1, L3, R5, L5, L1, L2, R5, R3, L3, R3, R5, R2, R5, R5, L5, L5, R2, L3, L5, L2, L1, R2, R2, L2, R2, L3, L2, R3, L5, R4, L4, L5, R3, L4, R1, R3, R2, R4, L2, L3, R2, L5, R5, R4, L2, R4, L1, L3, L1, L3, R1, R2, R1, L5, R5, R3, L3, L3, L2, R4, R2, L5, L1, L1, L5, L4, L1, L1, R1';


$pos = [0, 0, 0];
$locations = [[0, 0]];

foreach (explode(', ', $input) as $step) {
    $dir = $step[0];
    $n = (int) substr($step, 1);
    if ($dir === 'L') {
        $pos[2] = ($pos[2] + 3) % 4;
    } else {
        $pos[2] = ($pos[2] + 1) % 4;
    }
    switch($pos[2]) {
        case 0:
            $i = 1;
            $s = -1;
            break;
        case 1:
            $i = 0;
            $s = 1;
            break;
        case 2:
            $i = 1;
            $s = 1;
            break;
        case 3:
            $i = 0;
            $s = -1;
            break;
    }
    for ($j = 0; $j < $n; $j++) {
        $pos[$i] = $pos[$i] + $s;
        if (array_search([$pos[0], $pos[1]], $locations) !== false) {
            echo abs($pos[0]) + abs($pos[1]) . PHP_EOL;
            exit;
        }
        $locations[] = [$pos[0], $pos[1]];
    }
}
