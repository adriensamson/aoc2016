<?php

$input = file_get_contents(__DIR__ . '/input');

$button = 5;

function chars($string)
{
    $len = strlen($string);
    for ($i = 0; $i < $len; $i++) {
        yield $string[$i];
    }
}

foreach (explode("\n", $input) as $line) {
    if (!$line) {
        continue;
    }
    foreach (chars($line) as $direction) {
        switch ($direction) {
            case 'U':
                if ($button == 3) {
                    $button -= 2;
                } elseif (6<= $button && $button <= 8) {
                    $button -= 4;
                } elseif (10 <= $button && $button <= 12) {
                    $button -= 4;
                } elseif ($button === 13) {
                    $button -= 2;
                }
                break;
            case 'D':
                if ($button == 1) {
                    $button += 2;
                } elseif (2 <= $button && $button <= 4) {
                    $button += 4;
                } elseif (6 <= $button && $button <= 8) {
                    $button += 4;
                } elseif ($button === 11) {
                    $button += 2;
                }
                break;
            case 'L':
                if (!in_array($button, [1, 2, 5, 10, 13])) {
                    $button--;
                }
                break;
            case 'R':
                if (!in_array($button, [1, 4, 9, 12, 13])) {
                    $button++;
                }
                break;
        }
        //printf("%s %X\n", $direction, $button);
    }
    printf('%X', $button);
}

echo PHP_EOL;
