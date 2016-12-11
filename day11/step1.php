<?php

$input = trim(file_get_contents(__DIR__.'/input'));

$positions = ['elev' => 1];

foreach (explode("\n", $input) as $row) {
    preg_match('/The (\w+) floor contains (.*)./', $row, $matches);
    $pos = ['first' => 1, 'second' => 2, 'third' => 3, 'fourth' => 4][$matches[1]];
    if ($matches[2] === 'nothing relevant') {
        continue;
    }
    if (substr($matches[2], 0, 2) === 'a ') {
        $elements = preg_split('/\s?(, )?(and )?a /', substr($matches[2], 2));
        foreach ($elements as $el) {
            list ($mat, $type) = explode(' ', $el);
            $positions[substr($mat, 0, 2).'-'.$type[0]] = $pos;
        }
    }
}

function display($positions) {
    for ($i = 4; $i > 0; $i--) {
        foreach ($positions as $el => $p) {
            printf('%5s', $i === $p ? $el : '');
        }
        echo "\n";
    }
}

function ended($positions) {
    foreach ($positions as $p) {
        if ($p !== 4) {
            return false;
        }
    }
    return true;
}

function possible($positions) {
    foreach ($positions as $el => $p) {
        if (substr($el, -1) === 'm' && $positions[substr($el, 0, -1) . 'g'] !== $p) {
            if (count(array_filter($positions, function ($pos, $key) use ($p) {
                return $pos === $p && substr($key, -1) === 'g';
            }, ARRAY_FILTER_USE_BOTH)) > 0) {
                return false;
            }
        }
    }
    return true;
}

function actions($positions, &$seen) {
    $nexts = [];
    $addNext = function ($next) use (&$nexts, &$seen) {
        if (!isset($seen[hashPos($next)])) {
            $seen[hashPos($next)] = true;
            if (possible($next)) {
                $nexts[] = $next;
            }
        }
    };
    $p = $positions['elev'];
    $elements = array_keys(array_filter($positions, function ($pos, $key) use ($p) {
        return $pos === $p && $key !== 'elev';
    }, ARRAY_FILTER_USE_BOTH));
    if ($p > 1) {
        // down
        foreach ($elements as $i => $element) {
            $addNext(array_merge($positions, ['elev' => $p - 1, $element => $p - 1]));
            foreach (array_slice($elements, $i+1) as $element2) {
                $addNext(array_merge($positions, ['elev' => $p-1, $element => $p-1, $element2=>$p-1]));
            }
        }
    }
    if ($p < 4) {
        // up
        foreach ($elements as $i => $element) {
            $addNext(array_merge($positions, ['elev' => $p + 1, $element => $p + 1]));
            foreach (array_slice($elements, $i + 1) as $element2) {
                $addNext(array_merge($positions, ['elev' => $p + 1, $element => $p + 1, $element2 => $p + 1]));
            }
        }
    }
    return $nexts;
}

function hashPos($positions) {
    $h = '';
    foreach($positions as $el => $pos) {
        $h .= $el . $pos;
    }
    return $h;
}

$seen = [hashPos($positions) => true];
$currents = [$positions];
$i = 0;
while (count($currents) > 0) {
    $i++;
    printf("%d (%d - %d)\n", $i, count($currents), count($seen));
    $nexts = [];
    foreach ($currents as $current) {
        $nexts = array_merge($nexts, actions($current, $seen));
    }
    $currents = $nexts;
    foreach ($currents as $current) {
        if (ended($current)) {
            display($current);
            die;
        }
    }
}
