<?php

#ini_set('open_basedir', dirname(__DIR__,2) ); // Restrict access to test for total containment🔒, breaks with /tmp usage
ini_set("session.save_path", dirname(__DIR__, 2) . "/temp/");

# Check where setting is changeable: https://www.php.net/manual/en/ini.list.php (INI_USER)
#error_reporting(E_ALL);

// Error codes meaning: https://www.php.net/manual/en/errorfunc.constants.php
// Map error levels to their string representations
function error_type_string(int $errno): string {
    $map = [
        E_ERROR             => 'Fatal Error',
        E_WARNING           => 'Warning',
        E_PARSE             => 'Parse Error',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'Core Error',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_ERROR     => 'Compile Error',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_DEPRECATED        => 'Deprecated',
    ];
    return $map[$errno] ?? 'UnknownError';
}

function meme_for_error(int $errno, string $errorMessage = ''): string {
    $memes = [
        E_ERROR             => 'https://imgflip.com/i/a09jko', // Cool guys don't look at explosions
        E_WARNING           => 'https://imgflip.com/i/a09jrl', // This is fine
        E_PARSE             => 'https://imgflip.com/i/a09k3l', // Wat
        E_NOTICE            => 'https://imgflip.com/i/a09jb9', // Gru's Plan  // John Travolta confused
        E_CORE_ERROR        => 'https://imgflip.com/i/a09kv0', // elmo fire
        E_CORE_WARNING      => 'https://imgflip.com/i/a09kv0',
        E_COMPILE_ERROR     => 'https://imgflip.com/i/a09kv0',
        E_COMPILE_WARNING   => 'https://imgflip.com/i/a09kv0',
        E_DEPRECATED        => 'https://imgflip.com/i/a09lh5', // Mr. Incredible uncanny
    ];
    // Extra specificity by message
    if (stripos($errorMessage, 'token') !== false) {
        return 'https://i.imgflip.com/1ur9b0.jpg'; // This is fine
    }
    if (stripos($errorMessage, 'permission') !== false) {
        return 'https://i.imgflip.com/26am.jpg'; // Facepalm
    }
    return $memes[$errno] ?? 'https://i.imgflip.com/26am.jpg'; // Dramatic chipmunk as default
}


// Example error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // Skip errors not included in error_reporting
        return false;
    }
    $type = error_type_string($errno);
    $meme = meme_for_error($errno, $errstr);
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

// Test with trigger_error()

register_shutdown_function(function () {
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
});

// Custom exception handler
set_exception_handler(function ($exception) {
    echo <<<HTML
<figure class="error">
    <figcaption><strong>Uncaught Exception 📢:</strong> {$exception->getMessage()}
    <p><em>File:</em> {$exception->getFile()}, <em>Line:</em> {$exception->getLine()}

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
# © Chitch-Maintainers 2025, Licensed under the EUPL
