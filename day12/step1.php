<?php

$input = trim(file_get_contents(__DIR__.'/input'));

$instructions = explode("\n", $input);

$i = 0;
$registers = [
    'a' => 0,
    'b' => 0,
    'c' => 0,
    'd' => 0
];

while(isset($instructions[$i])) {
    if (preg_match('/cpy ([abcd]) ([abcd])/', $instructions[$i], $matches)) {
        $registers[$matches[2]] = $registers[$matches[1]];
        $i++;
    } elseif (preg_match('/cpy (-?\d+) ([abcd])/', $instructions[$i], $matches)) {
        $registers[$matches[2]] = (int) $matches[1];
        $i++;
    } elseif (preg_match('/inc ([abcd])/', $instructions[$i], $matches)) {
        $registers[$matches[1]]++;
        $i++;
    } elseif (preg_match('/dec ([abcd])/', $instructions[$i], $matches)) {
        $registers[$matches[1]]--;
        $i++;
    } elseif (preg_match('/jnz (-?\d+) (-?\d+)/', $instructions[$i], $matches)) {
        if ((int)$matches[1]) {
            $i += (int)$matches[2];
        } else {
            $i++;
        }
    } elseif (preg_match('/jnz ([abcd]) (-?\d+)/', $instructions[$i], $matches)) {
        if ($registers[$matches[1]]) {
            $i += (int)$matches[2];
        } else {
            $i++;
        }
    } else {
        echo $instructions[$i], "\n";
        die;
    }
}

echo $registers['a'], "\n";
