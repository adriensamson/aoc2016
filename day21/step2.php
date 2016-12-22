<?php

class Scramble {
    private $steps = [];

    function __construct($input) {
        foreach (explode("\n", trim($input)) as $row) {
            if (preg_match('/swap position (\d+) with position (\d+)/', $row, $matches)) {
                $this->steps[] = ['type' => 'swap-pos', 'x' => (int) $matches[1], 'y' => (int) $matches[2]];
            } elseif (preg_match('/swap letter (\w) with letter (\w)/', $row, $matches)) {
                $this->steps[] = ['type' => 'swap-let', 'x' => $matches[1], 'y' => $matches[2]];
            } elseif (preg_match('/rotate (left|right) (\d+) steps?/', $row, $matches)) {
                $this->steps[] = ['type' => 'rotate', 'dir' => $matches[1], 'steps' => (int)$matches[2]];
            } elseif (preg_match('/rotate based on position of letter (\w)/', $row, $matches)) {
                $this->steps[] = ['type' => 'rot-pos-let', 'x' => $matches[1]];
            } elseif (preg_match('/reverse positions (\d+) through (\d+)/', $row, $matches)) {
                $this->steps[] = ['type' => 'reverse', 'x' => (int)$matches[1], 'y' => (int)$matches[2]];
            } elseif (preg_match('/move position (\d+) to position (\d+)/', $row, $matches)) {
                $this->steps[] = ['type' => 'move', 'x' => (int)$matches[1], 'y' => (int)$matches[2]];
            } else {
                throw new \Exception('unkwnown command: '.$row);
            }
        }
    }

    function exec($step, $string) {
        switch ($step['type']) {
            case 'swap-pos':
                $l = $string[$step['x']];
                $string[$step['x']] = $string[$step['y']];
                $string[$step['y']] = $l;
                break;
            case 'swap-let':
                $string = str_replace($step['x'], '%', $string);
                $string = str_replace($step['y'], $step['x'], $string);
                $string = str_replace('%', $step['y'], $string);
                break;
            case 'rotate':
                if ($step['dir'] === 'left') {
                    $string = substr($string, $step['steps']) . substr($string, 0, $step['steps']);
                } else {
                    $string = substr($string, -$step['steps']) . substr($string, 0, -$step['steps']);
                }
                break;
            case 'rot-pos-let':
                $i = strpos($string, $step['x']);
                if ($i >= 4) {
                    $i += 2;
                } else {
                    $i += 1;
                }
                $i %= strlen($string);
                $string = substr($string, -$i) . substr($string, 0, -$i);
                break;
            case 'reverse':
                $rev = strrev(substr($string, $step['x'], $step['y'] + 1 - $step['x']));
                $string = substr($string, 0, $step['x']) . $rev . substr($string, $step['y'] + 1);
                break;
            case 'move':
                $l = $string[$step['x']];
                $string = substr($string, 0, $step['x']) . substr($string, $step['x'] + 1);
                $string = substr($string, 0, $step['y']) . $l . substr($string, $step['y']);
                break;
        }
        return $string;
    }

    function unexec($step, $string) {
        switch ($step['type']) {
            case 'swap-pos':
            case 'swap-let':
            case 'reverse':
                return $this->exec($step, $string);
                break;
            case 'rotate':
                return $this->exec([
                    'type' => 'rotate',
                    'dir' => $step['dir'] === 'left' ? 'right' : 'left',
                    'steps' => $step['steps'],
                ], $string);
            case 'rot-pos-let':
                $i = strpos($string, $step['x']);
                $j = 0;
                while (true) {
                    if ($i <= 7 && ($i % 2) === 1) {
                        $j = ($i + 1) / 2;
                        break;
                    } elseif ($i >= 10 && ($i % 2) === 0) {
                        $j = $i / 2 + 1;
                        break;
                    }
                    $i += strlen($string);
                    if ($i > 3* strlen($string)) {
                        throw new \Exception('...');
                    }
                }
                return $this->exec([
                    'type' => 'rotate',
                    'dir' => 'left',
                    'steps' => $j % strlen($string),
                ], $string);
                break;
            case 'move':
                return $this->exec([
                    'type' => 'move',
                    'x' => $step['y'],
                    'y' => $step['x'],
                ], $string);
                break;
        }
        return $string;
    }

    function unscramble($string) {
        foreach (array_reverse($this->steps) as $step) {
            $un = $this->unexec($step, $string);
            if ($this->exec($step, $un) !== $string) {
                var_dump($string, $un, $this->exec($step, $un), $step);
                die;
            }
            $string = $un;
        }
        return $string;
    }
}

$scrambler = new Scramble(file_get_contents(__DIR__.'/input'));

echo $scrambler->unscramble('fbgdceah'), "\n";
