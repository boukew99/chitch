<?php
<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

$file = 'chatlog';

// Read messages
$messages = Chitch\read($file);
$messages = array_map(fn($m) => json_decode($m, true), $messages);

// Calculate dynamic retry interval
$interval = 3;
if (count($messages) > 1) {
    $timestamps = array_column($messages, 'timestamp');
    $diffs = array_map(
        fn($i) => $timestamps[$i] - $timestamps[$i - 1],
        range(1, count($timestamps) - 1)
    );
    $avg = array_sum($diffs) / count($diffs);
    $interval = max(1, min(10, round($avg)));
}

header("Retry-After: $interval");

// handle get
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $since = (int)($_GET['since'] ?? 0);
    $new = array_filter($messages, fn($m) => $m['timestamp'] > $since);
    echo json_encode(['messages' => array_values($new)]);
}
