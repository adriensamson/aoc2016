<?php

class NodeGrid {
    private $nodes = [];

    function __construct($input) {
        foreach (explode("\n", $input) as $row) {
            preg_match('@/dev/grid/node-x(\d+)-y(\d+)\s+(\d+)T\s+(\d+)T\s+(\d+)T\s+(\d+)%@', $row, $matches);
            $this->nodes[(int) $matches[1]][(int) $matches[2]] = [
                'size' => (int) $matches[3],
                'used' => (int) $matches[4],
                'avail' => (int) $matches[5],
                'perc' => (int) $matches[6],
            ];
        }
    }

    function countViablePairs() {
        $count = 0;
        foreach ($this->nodes as $ax => $arow) {
            foreach ($arow as $ay => $a) {
                if ($a['used'] === 0) {
                    continue;
                }
                foreach ($this->nodes as $bx => $brow) {
                    foreach ($brow as $by => $b) {
                        if ($ax === $bx && $ay === $by) {
                            continue;
                        }
                        if ($a['used'] <= $b['avail']) {
                            $count++;
                        }
                    }
                }
            }
        }

        return $count;
    }
}

$grid = new NodeGrid(trim(file_get_contents(__DIR__.'/input')));

echo $grid->countViablePairs(), "\n";
