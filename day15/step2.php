<?php

/*
Disc #1 has 13 positions; at time=0, it is at position 1.
Disc #2 has 19 positions; at time=0, it is at position 10.
Disc #3 has 3 positions; at time=0, it is at position 2.
Disc #4 has 7 positions; at time=0, it is at position 1.
Disc #5 has 5 positions; at time=0, it is at position 3.
Disc #6 has 17 positions; at time=0, it is at position 5.
 */

$discs = [
    ['count' => 13, 'initial' => 1],
    ['count' => 19, 'initial' => 10],
    ['count' => 3, 'initial' => 2],
    ['count' => 7, 'initial' => 1],
    ['count' => 5, 'initial' => 3],
    ['count' => 17, 'initial' => 5],
    ['count' => 11, 'initial' => 0],
];

$t = 0;

while (true) {
    foreach ($discs as $d => $disc) {
        if ((($disc['initial']+$d+1+$t)%$disc['count']) !== 0) {
            $t++;
            continue 2;
        }
    }
    echo $t, "\n";
    break;
}
