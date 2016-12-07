<?php

$input = file_get_contents(__DIR__.'/input');

$n = 0;

foreach (explode("\n", $input) as $addr) {
    if (!$addr) {
        continue;
    }
    if (preg_match_all('/(?:^|\])[a-z]*([a-z])([a-z])\2\1/U', $addr, $matches, PREG_SET_ORDER)) {
        $matches = array_filter($matches, function ($m) {
            return $m[1] !== $m[2];
        });
        if (count($matches) === 0) {
            continue;
        }
        if (preg_match_all('/\[[a-z]*([a-z])([a-z])\2\1/U', $addr, $matches, PREG_SET_ORDER)) {
            $matches = array_filter($matches, function ($m) {
                return $m[1] !== $m[2];
            });
            if (count($matches) > 0) {
                continue;
            }
        }
        $n++;
    }
}

echo $n, "\n";
