<?php

$input = trim(file_get_contents(__DIR__.'/input'));

$bots = [];

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
    checkStop($bots);
    $newBots = turn($bots);
    if ($newBots == $bots) {
        echo "no movement\n";
        die;
    }
    $bots = $newBots;
}

function checkStop($bots) {
    foreach ($bots as $id => $bot) {
        if (in_array(61, $bot['chips']) && in_array(17, $bot['chips'])) {
            echo 'winner ', $id, "\n";
            die;
        }
    }
}

function turn($bots) {
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
            $bots[$bot['low'][1]]['chips'][] = min($bot['chips']);
            $bots[$bot['high'][1]]['chips'][] = max($bot['chips']);
            $bots[$id]['chips'] = [];
        }
    }
    return $bots;
}
