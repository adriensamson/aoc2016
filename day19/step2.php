<?php

function createCircle($n) {
    $circle = [];
    for ($i = 1; $i <= $n; $i++) {
        $circle[] = $i;
    }
    return $circle;
}

function whichEndsFast($circle)
{
    $count = count($circle);
    $i = 0;
    $removeCount = 0;
    while ($count > 1) {
        $circle = array_values(array_filter($circle, function ($val, $idx) use (&$i, &$count, &$removeCount) {
            $stolen = floor(($removeCount + $count) / 2) + $i;
            if ($idx == $stolen && $removeCount < $count - 1) {
                $i++;
                $removeCount++;
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH));
        $count = count($circle);
        $removeCount = 0;
        $circle = array_merge(array_slice($circle, $i), array_slice($circle, 0, $i));
        $i = 0;
    }
    return $circle[0];
}

echo whichEndsFast(createCircle(5)), "\n\n";

echo whichEndsFast(createCircle(3014603)), "\n";
