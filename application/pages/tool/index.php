<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

?>
<?=Chitch\head() ?>
<title>Chitch Server Status</title>
<header>
    <h1>Chitch Server Status</h1>
    <p>Using PHP binary: <code><?= PHP_BINARY ?></code> and config <code><?= php_ini_loaded_file() ?></code></p>
    <p>Document root <code><?= $_SERVER['DOCUMENT_ROOT'] ?></code> at address <code>http://<?= $_SERVER['HTTP_HOST'] ?>/</code></p>
    <p>My PID is <code><?= $pid = getmypid() ?></code></p>
    <p>Use your <strong>System Monitor</strong> or <strong>Task Manager</strong> to find and stop the server process, under the name <code>php*</code>(* can be anything).
    <figure>
        <figcaption>Included Files</figcaption>
        <ul>
            <?= Chitch\tree('li',
                fn($x) => basename($x),
                get_included_files())
            ?>
        </ul>
    </figure>
    <p>PHP Version: <code><?= PHP_VERSION ?></code></p>
    <p>PHP SAPI: <code><?= PHP_SAPI ?></code></p>
    <p>PHP Extensions: <code><?= implode(', ', get_loaded_extensions()) ?></code></p>
</header>

<main>
    <h2>More tools</h2>
<ul>
    <?= Chitch\tree('li',
        fn($x) => "<a href='$x'>$x</a>",
        glob('*.php'))
    ?>
</ul>
    <h2>Server Log</h2>
<style>
.log-block { font-family: monospace; padding: 1em; border-radius: 8px; }
.log-line {
    padding: 0.3em 0.6em;
    margin-bottom: 0.3em;
    border-left: 5px solid #555;
}
.time { color: #aaa; display: inline-block;  }

.start     { border-color: #0f0; color: #8f8; }
.accept    { border-color: #0af; }
.close     { border-color: #f88; }
.request   { border-color: #ff0; }
.status-2xx { border-color: #4f4; }
.status-4xx { border-color: #f44; }
.status-5xx { border-color: #f44; }

.status { font-weight: bold; }
.method { color: #fc0; }
.path { color: #fff; }
.ip { color: #8cf; }
</style>

<?php
$logText = file_get_contents("../../../temp/server.log");

function log_line_to_html($line) {
    if (!preg_match('/^\[(.*?)\] (.*)$/', $line, $match)) return '';
    $timestamp = $match[1];
    $message = $match[2];
    $class = "log-line";
    $content = "";

    if (strpos($message, 'Development Server') !== false) {
        $class .= ' start';
        $content = "<strong>Server Started</strong>: " . htmlspecialchars($message);
    } elseif (preg_match('/\[([^\]]+)\]:(\d+) Accepted/', $message, $ip)) {
        $class .= ' accept';
        $content = "<span class=\"ip\">Client <strong>{$ip[1]}</strong>:{$ip[2]}</span> connected 🧍";
    } elseif (preg_match('/\[([^\]]+)\]:(\d+) Closing/', $message, $ip)) {
        $class .= ' close';
        $content = "<span class=\"ip\">Client <strong>{$ip[1]}</strong>:{$ip[2]}</span> disconnected 🏃";
    } elseif (preg_match('/\[([^\]]+)\]:(\d+) \[(\d{3})\]: (\w+) (.+)/', $message, $req)) {
        $ipAddr = htmlspecialchars($req[1]);
        $port = htmlspecialchars($req[2]);
        $status = (int)$req[3];
        $method = htmlspecialchars($req[4]);
        $path = htmlspecialchars($req[5]);

        $class .= ' request';
        if ($status >= 200 && $status < 300) $class .= ' status-2xx';
        elseif ($status >= 400 && $status < 500) $class .= ' status-4xx';
        elseif ($status >= 500) $class .= ' status-5xx';

        $content = "<span class=\"ip\">$ipAddr:$port</span> → " .
            "<span class=\"status\">$status</span> " .
            "<span class=\"method\">$method</span> " .
            "<span class=\"path\">$path</span>";
    } else {
        $content = htmlspecialchars($message);
    }

    return "<article class=\"$class\">
        <time class=\"time\">$timestamp</time> — $content
    </article>";
}

$lines = array_filter(explode("\n", trim($logText)));
$htmlLines = array_map('log_line_to_html', $lines);
echo '<section class="log-block">' . implode('', $htmlLines) . '</section>';
?>
</main>

<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

# https://www.php.net/manual/en/book.info.php
