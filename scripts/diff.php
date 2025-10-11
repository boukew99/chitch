#!/usr/bin/env php
<?php

// © 2025 Bouke Westra
// Licensed under the EUPL

require_once '../library/diff.php';

use function Diff\diff_arrays;
use function Diff\unified_diff;

$options = getopt('', ['from:', 'to:']);
$fromfile = (string)($options['from'] ?? '');
$tofile = (string)($options['to'] ?? '');

[$from, $to, $label_from, $label_to] = match (true) {
    $fromfile !== '' && $tofile !== '' => [
        file_get_contents($fromfile),
        file_get_contents($tofile),
        $fromfile,
        $tofile
    ],
    $fromfile !== '' => [
        file_get_contents($fromfile),
        stream_get_contents(STDIN),
        $fromfile,
        'STDIN'
    ],
    $tofile !== '' => [
        stream_get_contents(STDIN),
        file_get_contents($tofile),
        'STDIN',
        $tofile
    ],
    default => [
        '',
        stream_get_contents(STDIN),
        'empty',
        'STDIN'
    ]
};

$diff = diff_arrays($from, $to);
echo unified_diff($diff, $label_from, $label_to);
