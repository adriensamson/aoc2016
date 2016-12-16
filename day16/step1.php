<?php

function dragon($a) {
    $b = implode(array_reverse(str_split($a)));
    $b = str_replace('1', 'x', $b);
    $b = str_replace('0', '1', $b);
    $b = str_replace('x', '0', $b);
    return $a . '0' . $b;
}

function checksum($str) {
    $checksum = '';
    preg_match_all('/(.)(.)/', $str, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        if ($match[1] === $match[2]) {
            $checksum .= '1';
        } else {
            $checksum .= '0';
        }
    }
    if ((strlen($checksum) % 2) === 0) {
        return checksum($checksum);
    }
    return $checksum;
}

function fill($initial, $length) {
    if (strlen($initial) >= $length) {
        return substr($initial, 0, $length);
    }
    return fill(dragon($initial), $length);
}

echo checksum(fill('10010000000110000', 272)), "\n";
