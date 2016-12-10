<?php

$input = trim(file_get_contents(__DIR__.'/input'));

$bots = [];
$outputs = [];

foreach (explode("\n", $input) as $row) {
    if (preg_match('/bot (\d+) gives low to (bot|output) (\d+) and high to (bot|output) (\d+)/', $row, $matches)) {
        if (!isset($bots[$matches[1]])) {
            $bots[$matches[1]]['chips'] = [];
        }
        $bots[$matches[1]]['low'] = [$matches[2], $matches[3]];
        $bots[$matches[1]]['high'] = [$matches[4], $matches[5]];
    } else if (preg_match('/value (\d+) goes to bot (\d+)/', $row, $matches)) {
        $bots[$matches[2]]['chips'][] = $matches[1];
    } else {
        echo $row, "\n";
        die;
    }
}

while (true) {
    checkStop($outputs);
    $newBots = turn($bots);
    if ($newBots == $bots) {
        echo "no movement\n";
        die;
    }
    $bots = $newBots;
}

function checkStop($outputs) {
    if (isset($outputs[0]) && isset($outputs[1]) && isset($outputs[2])) {
        echo 'mult :', $outputs[0] * $outputs[1] * $outputs[2], "\n";
        die;
    }
}

function turn($bots) {
    global $outputs;
    foreach ($bots as $id => $bot) {
        if (count($bot['chips']) > 2) {
            echo "oups\n";
            die;
        }
        if (count($bot['chips']) === 2) {
            if ($bot['low'][0] === 'bot' && count($bots[$bot['low'][1]]['chips']) === 2) {
                continue;
            }
            if ($bot['high'][0] === 'bot' && count($bots[$bot['high'][1]]['chips']) === 2) {
                continue;
            }
            if ($bot['low'][0] === 'bot') {
                $bots[$bot['low'][1]]['chips'][] = min($bot['chips']);
            } else {
                $outputs[$bot['low'][1]] = min($bot['chips']);
            }
            if ($bot['high'][0] === 'bot') {
                $bots[$bot['high'][1]]['chips'][] = max($bot['chips']);
            } else {
                $outputs[$bot['high'][1]] = max($bot['chips']);
            }
            $bots[$id]['chips'] = [];
        }
    }
    return $bots;
}
