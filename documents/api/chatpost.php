<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');
session_start();

$file = 'chatlog';

// handle post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = [
        'author' => $_SESSION['username'] ?? 'anon',
        'message' => $_POST['message'] ?? '',
        'timestamp' => time(),
        'time' => date('H:i:s')
    ];

    Chitch\write($file, json_encode($message));
    echo json_encode(['ok' => true]);
}
