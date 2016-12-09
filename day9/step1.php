<?php

function decompress($string) {
    $result = '';
    $offset = 0;
    while (preg_match('/\((\d+)x(\d+)\)/', $string, $matches, PREG_OFFSET_CAPTURE, $offset)) {
        $markerOffset = $matches[0][1];
        $repeatedOffset = $markerOffset + strlen($matches[0][0]);
        $repeatedLength = $matches[1][0];
        $repeatedString = substr($string, $repeatedOffset, $repeatedLength);
        $repeatedTimes = $matches[2][0];

        $result .= substr($string, $offset, $markerOffset - $offset);
        for ($i = 0; $i < $repeatedTimes; $i++) {
            $result .= $repeatedString;
        }
        $offset = $repeatedOffset + $repeatedLength;
    }
    $result .= substr($string, $offset);
    return $result;
}

$input = trim(file_get_contents(__DIR__.'/input'));

echo strlen(decompress($input)), "\n";
