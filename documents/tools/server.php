<?php
chdir(dirname($_SERVER['DOCUMENT_ROOT'] ) ?? '../..');

require_once('library/bootstrap.php');

session_start();
require_once('library/halstead.php');

function last_dir(string $path): string {
    $path = ltrim($path, './');              // remove leading . and /
    $path = rtrim($path, '/');               // remove trailing /
    $dir  = basename(dirname($path));
    $file = basename($path);

    return ($dir === '.' ? '' : "$dir/") . $file;
}

?>
<?= Chitch\head() ?>
<title>Cognitive Code Complexity Measure</title>

<header>

<h1>Server Logs</h1>


</header>

<main>

<section>
</ul>
    <h2>Server Log</h2>

    <p><?= "🧠 mem use: " . round(memory_get_usage() / 1024, 1) . " KB"; ?>
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
$logText = file_get_contents("../server.log");

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
</section>

</main>

<footer>

<?= Chitch\foot() ?>

</footer>
