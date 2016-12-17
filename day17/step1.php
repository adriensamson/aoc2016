<?php

class Vault {
    private $passcode;

    function __construct($passcode)
    {
        $this->passcode = $passcode;
    }

    function openDoors($path, $position) {
        $hash = md5($this->passcode . $path);
        $doors = [];
        if ($position[0] > 0 && in_array($hash[0], ['b', 'c', 'd', 'e', 'f'])) {
            $doors['U'] = [$position[0] - 1, $position[1]];
        }
        if ($position[0] < 3 && in_array($hash[1], ['b', 'c', 'd', 'e', 'f'])) {
            $doors['D'] = [$position[0] + 1, $position[1]];
        }
        if ($position[1] > 0 && in_array($hash[2], ['b', 'c', 'd', 'e', 'f'])) {
            $doors['L'] = [$position[0], $position[1] - 1];
        }
        if ($position[1] < 3 && in_array($hash[3], ['b', 'c', 'd', 'e', 'f'])) {
            $doors['R'] = [$position[0], $position[1] + 1];
        }
        return $doors;
    }

    function findPath() {
        $positions = ['' => [0, 0]];
        while (true) {
            $nexts = [];
            foreach ($positions as $path => $position) {
                foreach ($this->openDoors($path, $position) as $dir => $newPos) {
                    $nexts[$path.$dir] = $newPos;
                }
            }
            $found = array_search([3, 3], $nexts);
            if ($found) {
                echo $found, "\n";
                return;
            }
            $positions = $nexts;
        }
    }

    function findLongestPath()
    {
        $positions = ['' => [0, 0]];
        $longest = null;
        while (count($positions) > 0) {
            $nexts = [];
            foreach ($positions as $path => $position) {
                foreach ($this->openDoors($path, $position) as $dir => $newPos) {
                    $nexts[$path . $dir] = $newPos;
                }
            }
            $found = array_search([3, 3], $nexts);
            if ($found) {
                $longest = strlen($found);
            }
            $positions = array_filter($nexts, function ($p) { return $p != [3, 3]; });
        }
        echo $longest, "\n";
    }
}

(new Vault('ihgpwlah'))->findPath();
(new Vault('kglvqrro'))->findPath();
(new Vault('ulqzkmiv'))->findPath();

(new Vault('mmsxrhfx'))->findPath();


(new Vault('ihgpwlah'))->findLongestPath();
(new Vault('kglvqrro'))->findLongestPath();
(new Vault('ulqzkmiv'))->findLongestPath();

(new Vault('mmsxrhfx'))->findLongestPath();

