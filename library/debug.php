<?php
# © Chitch Contributors 2025, Licensed under the EUPL

#ini_set('open_basedir', dirname(__DIR__,2) ); // Restrict access to test for total containment🔒, breaks with /tmp usage
ini_set("session.save_path", dirname(__DIR__, 2) . "/temp/");

# Check where setting is changeable: https://www.php.net/manual/en/ini.list.php (INI_USER)
#error_reporting(E_ALL);

// Error codes meaning: https://www.php.net/manual/en/errorfunc.constants.php
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
         // Skip errors not included in error_reporting
        return false;
    }
    $type = match ($errno) {
        E_ERROR             => 'Fatal Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parse Error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_DEPRECATED        => 'Deprecated',
        default             => 'UnknownError',
    };
    $meme = match ($errno) {
        E_ERROR             => 'https://i.imgflip.com/a09jko.jpg', // Cool guys don't look at explosions
        E_WARNING           => 'https://i.imgflip.com/a09jrl.jpg', // This is fine
        E_PARSE             => 'https://i.imgflip.com/a09k3l.jpg', // Wat
        E_NOTICE            => 'https://i.imgflip.com/a09jb9.jpg', // Gru's Plan
        E_CORE_ERROR,
        E_CORE_WARNING,
        E_COMPILE_ERROR,
        E_COMPILE_WARNING   => 'https://i.imgflip.com/a09kv0.jpg', // elmo fire
        E_DEPRECATED        => 'https://i.imgflip.com/a09lh5.jpg', // Mr. Incredible uncanny
        default             => 'https://i.imgflip.com/a12u4y.jpg', //History guy
    };
    echo <<<HTML
<figure class="error">
    <figcaption><em>Error Type:</em> {$type} </figcaption>
    <p><strong>Error:</strong> {$errstr}
    <p>File {$errfile} on line {$errline}
    <img src="{$meme}" alt="Meme for {$type}" style="max-width:300px;">
</figure>
HTML;
    // Prevent default PHP error handling (turn off to test)
    return true;
});

// IIFE to capture start time and register shutdown function
(function () {
  $start = microtime(true);

  register_shutdown_function(function () use ($start) {
    $error = error_get_last();

    if ($error && $error["type"] & (E_ERROR | E_PARSE)) {
        echo <<<HTML
<figure class="error">
    <figcaption><strong>☠️ Fatal Error</strong></figcaption>
    <p><strong>Message:</strong> {$error["message"]}</p>
    <p><em>File:</em> {$error["file"]}, <em>Line:</em> {$error["line"]}</p>
</figure>
HTML;
    }

    $end = microtime(true);
    $time = round(($end - $start) * 1000, 2);
    $mem = memory_get_usage();
    $uri = $_SERVER['REQUEST_URI'] ?? 'unknown';
    $ts = date('c');

    $log = "$ts,$uri,$time,$mem\n";
    file_put_contents(dirname(__DIR__, 2) . "/performance.csv", $log, FILE_APPEND);
    echo "<p class='perf-log'>⏱ Time: {$time} ms, Memory: " . round($mem / 1024, 2) . " KB</p>";
  });
})();



set_exception_handler(function ($exception) {
    $trace = $exception->getTrace();
    $func = $trace[0]['function'] ?? null;

    // check if function is global (built-in or user-defined)
    $docLink = '#';
    if ($func && function_exists($func)) {
        $refFunc = new ReflectionFunction($func);
        if ($refFunc->isInternal()) {
            // builtin PHP function, safe to link 🧰
            $docLink = "https://www.php.net/manual/en/function." . str_replace('_', '-', strtolower($func)) . ".php";
        }
        else {
            $docLink =  '/tools/reference.php#' . str_replace('_', '-', strtolower($func));
        }
    }

    echo <<<HTML
<figure class="error">
    <figcaption><strong>Uncaught Exception 📢:</strong> {$exception->getMessage()}</figcaption>
    <p><em>File:</em> {$exception->getFile()}, <em>Line:</em> {$exception->getLine()}</p>
    <p><a href="$docLink" target="_blank">📘 PHP Manual: $func()</a></p>

    <details>
        <summary>Stack Trace</summary>
        <pre>{$exception->getTraceAsString()}</pre>
    </details>
</figure>
HTML;
});



// Test it out
// trigger_error("Test notice!", E_USER_NOTICE);
// throw new Exception("Test exception!");

// Call this instead of print_r for inline debugging
function chitch_debug($message)
{
    // Ensure the message is a string
    if (!is_string($message)) {
        $message = print_r($message, true);
    }
    # check if $message is a path and make it an acnhor with file://
    if (preg_match('/^(\/|[a-zA-Z]:\\\\)/', $message)) {
        $message = "<a href='file://" . $message . "'>" . $message . "</a>";
    }
    // if PHP datastructure format it
    if (is_array($message) || is_string($message) || is_object($message) || is_bool($message) || is_null($message) || is_int($message) || is_float($message)) {
        $message = print_r($message, true);
    }

    // Output the debug message
    echo "<pre class='debug'>Debug: " . $message . "</pre>";
}
