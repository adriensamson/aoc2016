<?php

function createCircle($n) {
    $circle = [];
    for ($i = 1; $i <= $n; $i++) {
        $circle[] = $i;
    }
    return $circle;
}

function doTurn($circle) {
    $filtered = array_values(array_filter($circle, function ($v, $idx) {
        return ($idx % 2) === 0;
    }, ARRAY_FILTER_USE_BOTH));
    if ((count($circle) % 2) !== 0) {
        return array_slice($filtered, 1);
    }
    return $filtered;
}

function whichEnds($circle) {
    if (count($circle) <= 1) {
        return $circle[0];
    }
    return whichEnds(doTurn($circle));
}

echo whichEnds(createCircle(5)), "\n";
echo whichEnds(createCircle(3014603)), "\n";
