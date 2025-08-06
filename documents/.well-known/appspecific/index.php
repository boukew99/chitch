<?php

if (basename($_SERVER['REQUEST_URI']) === 'com.chrome.devtools.json') {
    handleChromeDevTools();
} else {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    exit;
}

function handleChromeDevTools() {
    header('Content-Type: application/json; charset=utf-8');

    $rootPath = dirname(__DIR__,2);

    echo json_encode([
        "workspace" => [
            "root" => $rootPath,
            "uuid" => "1bc56aca-0bb2-4b85-a19d-1563d65ffb5f"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
