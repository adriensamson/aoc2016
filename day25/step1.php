<?php

$a = 2;
$b = 1;
while ($a < 2534) {
    $a = 2 * $a + $b;
    $b = 1 - $b;
}
$a -= 2534;
echo $a, "\n";
sleep(1);

$b = $c = $d = 0;

$d = $a + 2534;

while (true) {
    $a = $d;
    while (true) {
        $b = $a % 2;
        $a = floor($a/2);
        echo $b;
        if ($a == 0) {
            break;
        }
    }
    sleep(1);
}
