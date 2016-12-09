<?php

function decompressLength($string) {
    $length = 0;
    $offset = 0;
    while (preg_match('/\((\d+)x(\d+)\)/', $string, $matches, PREG_OFFSET_CAPTURE, $offset)) {
        $markerOffset = $matches[0][1];
        $repeatedOffset = $markerOffset + strlen($matches[0][0]);
        $repeatedLength = $matches[1][0];
        $repeatedTimes = $matches[2][0];

        $repeatedString = substr($string, $repeatedOffset, $repeatedLength);

        $length += $markerOffset - $offset;
        $length += decompressLength($repeatedString) * $repeatedTimes;
        $offset = $repeatedOffset + $repeatedLength;
    }
    $length += strlen($string) - $offset;
    return $length;
}

echo decompressLength('(27x12)(20x12)(13x14)(7x10)(1x12)A'), "\n";

echo decompressLength('(25x3)(3x3)ABC(2x3)XY(5x2)PQRSTX(18x9)(3x2)TWO(5x7)SEVEN'), "\n";

$input = trim(file_get_contents(__DIR__.'/input'));

echo decompressLength($input), "\n";
