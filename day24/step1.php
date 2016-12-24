<?php

class Map {
    private $map = [];
    private $goals = [];

    function __construct($input)
    {
        foreach (explode("\n", $input) as $i => $row) {
            foreach (str_split($row) as $j => $cell) {
                if (is_numeric($cell)) {
                    $this->goals[(int)$cell] = [$i, $j];
                    $this->map[$i][$j] = '.';
                } else {
                    $this->map[$i][$j] = $cell;
                }
            }
        }
    }

    function findShortestPathBetween($from, $to) {
        $currents = [$from];
        $seen = [$from];
        $i = 0;
        while (true) {
            $i++;
            $nexts = [];
            foreach ($currents as $current) {
                foreach ([[0, 1], [0, -1], [1, 0], [-1, 0]] as list($dx, $dy)) {
                    $nx = $current[0] + $dx;
                    $ny = $current[1] + $dy;
                    if ([$nx, $ny] === $to) {
                        return $i;
                    }
                    if (in_array([$nx, $ny], $this->goals, true)) {
                        continue;
                    }
                    if ($this->map[$nx][$ny] !== '#' && !in_array([$nx, $ny], $seen, true)) {
                        $nexts[] = [$nx, $ny];
                        $seen[] = [$nx, $ny];
                    }
                }
            }
            $currents = $nexts;
            if (count($currents) === 0) {
                return null;
            }
        }
    }

    function findAllShortestPaths() {
        $paths = [];
        foreach ($this->goals as $i => $from) {
            foreach ($this->goals as $j => $to) {
                if ($j <= $i) {
                    continue;
                }
                $n = $this->findShortestPathBetween($from, $to);
                if ($n !== null) {
                    $paths[$i][$j] = $n;
                    $paths[$j][$i] = $n;
                }
            }
        }
        return $paths;
    }

    function findMetaShortestPath() {
        $paths = $this->findAllShortestPaths();

        $metas = [
            ['at' => 0, 'n' => 0, 'seen' => [0 => true]],
        ];
        $i = 0;

        while (true) {
            $i++;
            foreach ($metas as $idx => $meta) {
                $possibles = array_filter($paths[$meta['at']], function ($n) use ($meta, $i) {
                    return $n + $meta['n'] === $i;
                });
                foreach ($possibles as $to => $n) {
                    $newSeen = $meta['seen'];
                    $newSeen[$to] = true;
                    if (count($newSeen) === count($this->goals)) {
                        return $i;
                    }
                    $metas[] = [
                        'at' => $to,
                        'n' => $n + $meta['n'],
                        'seen' => $newSeen,
                    ];
                }
            }
        }
    }
}

$map = new Map(trim(file_get_contents(__DIR__.'/input')));

echo $map->findMetaShortestPath(), "\n";
