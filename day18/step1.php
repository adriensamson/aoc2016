<?php

class Tiles {
    private $rows = [];

    function __construct($firstRow, $nbRows)
    {
        $this->rows[] = $firstRow;
        while (count($this->rows) < $nbRows) {
            $this->computeNextRow();
        }
    }

    function computeNextRow() {
        $lastRow = $this->rows[count($this->rows) - 1];
        $newRow = [];
        foreach (str_split($lastRow) as $i => $_) {
            $newRow[$i] = $this->computeSafe($lastRow, $i) ? '.' : '^';
        }
        $this->rows[] = implode($newRow);
    }

    function computeSafe($lastRow, $i) {
        $left = isset($lastRow[$i-1]) ? $lastRow[$i - 1] : '.';
        $center = $lastRow[$i];
        $right = isset($lastRow[$i + 1]) ? $lastRow[$i + 1] : '.';

        if ($left === '^' && $center === '^' && $right === '.'
            || $left === '.' && $center === '^' && $right === '^'
            || $left === '^' && $center === '.' && $right === '.'
            || $left === '.' && $center === '.' && $right === '^'
        ) {
            return false;
        }
        return true;
    }

    function countSafe() {
        return array_sum(array_map(function ($row) {
            return array_reduce(str_split($row), function ($acc, $tile) {
                if ($tile === '.') {
                    return $acc + 1;
                }
                return $acc;
            }, 0);
        }, $this->rows));
    }
}

echo (new Tiles('.^^.^.^^^^', 10))->countSafe(), "\n";

echo (new Tiles('......^.^^.....^^^^^^^^^...^.^..^^.^^^..^.^..^.^^^.^^^^..^^.^.^.....^^^^^..^..^^^..^^.^.^..^^..^^^..', 40))->countSafe(), "\n";

echo (new Tiles('......^.^^.....^^^^^^^^^...^.^..^^.^^^..^.^..^.^^^.^^^^..^^.^.^.....^^^^^..^..^^^..^^.^.^..^^..^^^..', 400000))->countSafe(), "\n";
