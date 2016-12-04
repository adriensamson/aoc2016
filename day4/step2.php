<?php

$input = file_get_contents(__DIR__.'/input');

function checksum($str) {
    $letters = [];
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        if ($str[$i] === '-') {
            continue;
        }
        if (!isset($letters[$str[$i]])) {
            $letters[$str[$i]] = [
                'n' => 0,
                'letter' => $str[$i],
            ];
        }
        $letters[$str[$i]]['n']++;
    }
    usort($letters, function ($l1, $l2) {
        return [-$l1['n'], $l1['letter']] <=> [-$l2['n'], $l2['letter']];
    });
    return implode('', array_map(function ($l) {return $l['letter'];}, array_slice($letters, 0, 5)));
}

function decrypt($str, $x) {
    $decrypted = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        if ($str[$i] === '-') {
            $decrypted .= ' ';
            continue;
        }
        $c = (ord($str[$i]) - ord('a') + $x) % 26;
        $decrypted .= chr(ord('a') + $c);
    }

    return $decrypted;
}

$ok = [];

foreach (explode("\n", $input) as $room) {
    if (!$room) {
        continue;
    }
    preg_match('/([-a-z]+)-(\d+)\[([a-z]{5})\]/', $room, $matches);
    if ($matches[3] === checksum($matches[1])) {
        $ok[] = $matches;
    }
}

foreach ($ok as $room) {
    $decrypted = decrypt($room[1], $room[2]);
    if (strpos($decrypted, 'north') !== false) {
        printf("%s %d\n", $decrypted, $room[2]);
    }
}

