<?php

class AssemBunny {
    private $instructions = [];
    private $i = 0;
    public $registers = [
        'a' => 0,
        'b' => 0,
        'c' => 0,
        'd' => 0
    ];

    function __construct($input) {
        foreach (explode("\n", $input) as $row) {
            if (preg_match('/cpy ([abcd]|-?\d+) ([abcd])/', $row, $matches)) {
                $this->instructions[] = [
                    'type' => 'cpy',
                    'from' => $matches[1],
                    'to' => $matches[2],
                ];
            } elseif (preg_match('/inc ([abcd])/', $row, $matches)) {
                $this->instructions[] = [
                    'type' => 'inc',
                    'what' => $matches[1],
                ];
            } elseif (preg_match('/dec ([abcd])/', $row, $matches)) {
                $this->instructions[] = [
                    'type' => 'dec',
                    'what' => $matches[1],
                ];
            } elseif (preg_match('/jnz ([abcd]|-?\d+) ([abcd]|-?\d+)/', $row, $matches)) {
                $this->instructions[] = [
                    'type' => 'jnz',
                    'cond' => $matches[1],
                    'to' => $matches[2]
                ];
            } elseif (preg_match('/tgl ([abcd]|-?\d+)/', $row, $matches)) {
                $this->instructions[] = [
                    'type' => 'tgl',
                    'to' => $matches[1]
                ];
            } else {
                echo $row, "\n";
                die;
            }
        }
    }

    function exec() {
        $inst = $this->instructions[$this->i];
        switch ($inst['type']) {
            case 'cpy':
                $from = is_numeric($inst['from']) ? (int) $inst['from'] : $this->registers[$inst['from']];
                if (!is_numeric($inst['to'])) {
                    $this->registers[$inst['to']] = $from;
                }
                $this->i++;
                break;
            case 'inc':
                if (!is_numeric($inst['what'])) {
                    $this->registers[$inst['what']]++;
                }
                $this->i++;
                break;
            case 'dec':
                if (!is_numeric($inst['what'])) {
                    $this->registers[$inst['what']]--;
                }
                $this->i++;
                break;
            case 'jnz':
                // optim
                if ($inst['to'] == -2
                    && $this->instructions[$this->i - 1]['type'] === 'dec' && $this->instructions[$this->i - 1]['what'] === $inst['cond']
                ) {
                    $repeatInst = $this->instructions[$this->i - 2];
                    $cond = is_numeric($inst['cond']) ? (int)$inst['cond'] : $this->registers[$inst['cond']];
                    if ($repeatInst['type'] === 'inc') {
                        $this->registers[$repeatInst['what']] += $cond;
                    } else if ($repeatInst['type'] === 'dec') {
                        $this->registers[$repeatInst['what']] -= $cond;
                    }
                    $this->registers[$inst['cond']] = 0;
                    $this->i++;
                    break;
                }
                if ($inst['to'] == -2
                    && $this->instructions[$this->i - 2]['type'] === 'dec' && $this->instructions[$this->i - 2]['what'] === $inst['cond']
                ) {
                    $repeatInst = $this->instructions[$this->i - 1];
                    $cond = is_numeric($inst['cond']) ? (int)$inst['cond'] : $this->registers[$inst['cond']];
                    if ($repeatInst['type'] === 'inc') {
                        $this->registers[$repeatInst['what']] += $cond;
                    } else if ($repeatInst['type'] === 'dec') {
                        $this->registers[$repeatInst['what']] -= $cond;
                    }
                    $this->registers[$inst['cond']] = 0;
                    $this->i++;
                    break;
                }
                if ($inst['to'] == -2
                    && $this->instructions[$this->i - 1]['type'] === 'inc' && $this->instructions[$this->i - 1]['what'] === $inst['cond']
                ) {
                    $repeatInst = $this->instructions[$this->i - 2];
                    $cond = is_numeric($inst['cond']) ? (int)$inst['cond'] : $this->registers[$inst['cond']];
                    if ($repeatInst['type'] === 'inc') {
                        $this->registers[$repeatInst['what']] -= $cond;
                    } else if ($repeatInst['type'] === 'dec') {
                        $this->registers[$repeatInst['what']] += $cond;
                    }
                    $this->registers[$inst['cond']] = 0;
                    $this->i++;
                    break;
                }
                if ($inst['to'] == -5
                    && $this->instructions[$this->i - 1]['type'] === 'dec' && $this->instructions[$this->i - 1]['what'] === $inst['cond']
                    && $this->instructions[$this->i - 2]['type'] === 'jnz' && $this->instructions[$this->i - 2]['to'] == -2
                    && $this->instructions[$this->i - 3]['type'] === 'dec' && $this->instructions[$this->i - 3]['what'] === $this->instructions[$this->i - 2]['cond']
                    && $this->instructions[$this->i - 5]['type'] === 'cpy' && $this->instructions[$this->i - 5]['to'] === $this->instructions[$this->i - 2]['cond']
                ) {
                    $repeatInst = $this->instructions[$this->i - 4];
                    $cond1 = is_numeric($inst['cond']) ? (int)$inst['cond'] : $this->registers[$inst['cond']];
                    $cond2 = is_numeric($this->instructions[$this->i - 5]['from']) ? (int)$this->instructions[$this->i - 5]['from'] : $this->registers[$this->instructions[$this->i - 5]['from']];
                    if ($repeatInst['type'] === 'inc') {
                        $this->registers[$repeatInst['what']] += $cond1 * $cond2;
                    } else if ($repeatInst['type'] === 'dec') {
                        $this->registers[$repeatInst['what']] -= $cond1 * $cond2;
                    }
                    $this->registers[$inst['cond']] = 0;
                    $this->registers[$this->instructions[$this->i - 2]['cond']] = 0;
                    $this->i++;
                    break;
                }

                $cond = is_numeric($inst['cond']) ? (int)$inst['cond'] : $this->registers[$inst['cond']];
                $to = is_numeric($inst['to']) ? (int)$inst['to'] : $this->registers[$inst['to']];
                if ($cond) {
                    $this->i += $to;
                } else {
                    $this->i++;
                }
                break;
            case 'tgl':
                $to = is_numeric($inst['to']) ? (int)$inst['to'] : $this->registers[$inst['to']];
                $this->toggle($this->i + $to);
                $this->i++;
                break;
            default:
                var_dump($inst);
                die;
        }
    }

    function execAll() {
        while (isset($this->instructions[$this->i])) {
            printf("%d %d %d %d [%d] %s\n", $this->registers['a'], $this->registers['b'], $this->registers['c'], $this->registers['d'], $this->i, $this->instructions[$this->i]['type']);
            $this->exec();
        }
    }

    function toggle($i) {
        if (!isset($this->instructions[$i])) {
            return;
        }
        $inst = $this->instructions[$i];
        switch ($inst['type']) {
            case 'inc':
                $inst = [
                    'type' => 'dec',
                    'what' => $inst['what'],
                ];
                break;
            case 'dec':
                $inst = [
                    'type' => 'inc',
                    'what' => $inst['what'],
                ];
                break;
            case 'tgl':
                $inst = [
                    'type' => 'inc',
                    'what' => $inst['to'],
                ];
                break;
            case 'jnz':
                $inst = [
                    'type' => 'cpy',
                    'from' => $inst['cond'],
                    'to' => $inst['to'],
                ];
                break;
            case 'cpy':
                $inst = [
                    'type' => 'jnz',
                    'cond' => $inst['from'],
                    'to' => $inst['to'],
                ];
                break;
            default:
                var_dump($inst);
                die;
        }
        $this->instructions[$i] = $inst;
    }
}

$b = new AssemBunny(trim(file_get_contents(__DIR__ . '/input')));
$b->registers['a'] = 12;
$b->execAll();
echo $b->registers['a'], "\n";
