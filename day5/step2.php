<?php

$input = 'cxdnnyjw';

$password = '________';

$i = 0;

while (true) {
    $hash = md5($input.$i);
    if (substr($hash, 0, 5) === '00000') {
        $pos = $hash[5];
        $c = $hash[6];
        if (base_convert($pos, 16, 10) >= 8 || $password[$pos] !== '_') {
            $i++;
            continue;
        }
        $password[$pos] = $c;
        printf("%s (%d)\n", $password, $i);
        if (strpos($password, '_') === false) {
            break;
        }
    }
    $i++;
}
