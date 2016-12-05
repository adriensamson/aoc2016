<?php

$input = 'cxdnnyjw';

$password = '';

$i = 0;

while (true) {
    $hash = md5($input.$i);
    if (substr($hash, 0, 5) === '00000') {
        $password .= $hash[5];
        printf("%s (%d)\n", $password, $i);
        if (strlen($password) === 8) {
            break;
        }
    }
    $i++;
}
