<?php

class NodeGrid {
    private $nodes = [];

    private $goalPos;
    private $emptyPos;

    private $bofs = 0;

    function __construct($input) {
        foreach (explode("\n", $input) as $row) {
            preg_match('@/dev/grid/node-x(\d+)-y(\d+)\s+(\d+)T\s+(\d+)T\s+(\d+)T\s+(\d+)%@', $row, $matches);
            $this->nodes[(int) $matches[1]][(int) $matches[2]] = [
                'size' => (int) $matches[3],
                'used' => (int) $matches[4],
                'avail' => (int) $matches[5],
                'perc' => (int) $matches[6],
            ];
            if ($matches[4] == 0) {
                $this->emptyPos = [(int)$matches[1], (int) $matches[2]];
            }
        }
        $this->goalPos = [count($this->nodes) - 1, 0];
        printf("empty : %d-%d\n", $this->emptyPos[0], $this->emptyPos[1]);
        printf("goal : %d-%d\n", $this->goalPos[0], $this->goalPos[1]);
    }

    function checkUnmovables() {
        foreach ($this->nodes as $ax => $arow) {
            foreach ($arow as $ay => $a) {
                foreach ($this->nodes as $bx => $brow) {
                    foreach ($brow as $by => $b) {
                        if ($a['used'] > $b['size']) {
                            printf("bof %d-%d (%d) -> %d-%d (%d)\n", $ax, $ay, $a['used'], $bx, $by, $b['used']);
                            continue 3;
                        }
                    }
                }
            }
        }
    }

    function minSteps() {
        $emptyMoves = abs($this->emptyPos[0] - ($this->goalPos[0] - 1)) + abs($this->emptyPos[1] - $this->goalPos[1]);
        $loopMoves = 5 * ($this->goalPos[0] - 1);
        $finalMove = 1;
        var_dump($emptyMoves, $loopMoves, $finalMove);
        return $emptyMoves + $loopMoves + $finalMove;
    }
}

$grid = new NodeGrid(trim(file_get_contents(__DIR__.'/input')));

/*$grid = new NodeGrid(<<<EOF
/dev/grid/node-x0-y0   10T    8T     2T   80%
/dev/grid/node-x0-y1   11T    6T     5T   54%
/dev/grid/node-x0-y2   32T   28T     4T   87%
/dev/grid/node-x1-y0    9T    7T     2T   77%
/dev/grid/node-x1-y1    8T    0T     8T    0%
/dev/grid/node-x1-y2   11T    7T     4T   63%
/dev/grid/node-x2-y0   10T    6T     4T   60%
/dev/grid/node-x2-y1    9T    8T     1T   88%
/dev/grid/node-x2-y2    9T    6T     3T   66%
EOF
);*/

$grid->checkUnmovables();

echo $grid->minSteps(), "\n";
