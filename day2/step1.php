<?php

$input = file_get_contents(__DIR__.'/input');

$button = 5;

function chars($string) {
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
                if ($button > 3) {
                    $button -= 3;
                }
                break;
            case 'D':
                if ($button < 7) {
                    $button += 3;
                }
                break;
            case 'L':
                if ($button % 3 !== 1) {
                    $button--;
                }
                break;
            case 'R':
                if ($button % 3 !== 0) {
                    $button++;
                }
                break;
        }
    }
    echo $button;
}

echo PHP_EOL;
