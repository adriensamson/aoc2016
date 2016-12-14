<?php

$salt = 'jlmsuwbz';

$i = 0;
$n = 0;

function getHash($i) {
    global $salt;
    static $hashes = [];
    if (!isset($hashes[$i])) {
        $hashes[$i] = md5($salt . $i);
    }
    return $hashes[$i];
}

while ($n < 64) {
    $hash = getHash($i);
    if (preg_match('/(.)\1\1/', $hash, $matches)) {
        $search = str_repeat($matches[1], 5);
        $j = 1;
        while ($j <= 1000) {
            if (strpos(getHash($i + $j), $search) !== false) {
                $n++;
                break;
            }
            $j++;
        }
    }
    $i++;
}

echo $i-1, "\n";
