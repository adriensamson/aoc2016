<?php

$input = file_get_contents(__DIR__.'/input');

$n = 0;

function preg_match_all_array($pattern, $array, &$matches) {
    $matches = [];
    foreach ($array as $source) {
        preg_match_all($pattern, $source, $m, PREG_SET_ORDER);
        $matches = array_merge($matches, $m);
    }
    return count($matches) > 0;
}

foreach (explode("\n", $input) as $addr) {
    if (!$addr) {
        continue;
    }
    $supernets = preg_split('/\[[a-z]*\]/', $addr);
    preg_match_all('/\[([a-z]*)\]/', $addr, $hypernets);
    $hypernets = $hypernets[1];

    if (preg_match_all_array('/([a-z])(?=([a-z])\1)/U', $supernets, $matches)) {
        $matches = array_filter($matches, function ($m) {
            return $m[1] !== $m[2];
        });
        if (count($matches) === 0) {
            continue;
        }

        foreach ($matches as $m) {
            foreach ($hypernets as $hypernet) {
                if (strpos($hypernet, $m[2].$m[1].$m[2]) !== false) {
                    $n++;
                    continue 3;
                }
            }
        }

    }
}

echo $n, "\n";
