<?php

# ini_set('open_basedir', dirname(__DIR__,2) ); // Restrict access to test for total containment🔒
ini_set("sys_temp_dir", dirname(__DIR__, 2) . "/temp/");
ini_set("session.save_path", dirname(__DIR__, 2) . "/temp/");

#error_reporting(E_ALL);

//https://www.php.net/manual/en/errorfunc.constants.php#123901
// Map error levels to their string representations
function error_type_string(int $errno): string {
    $map = [
        E_ERROR             => 'FatalError',
        E_WARNING           => 'Warning',
        E_PARSE             => 'ParseError',
        E_NOTICE            => 'Notice',
        E_CORE_ERROR        => 'CoreError',
        E_CORE_WARNING      => 'CoreWarning',
        E_COMPILE_ERROR     => 'CompileError',
        E_COMPILE_WARNING   => 'CompileWarning',
        E_USER_ERROR        => 'UserError',
        E_USER_WARNING      => 'UserWarning',
        E_USER_NOTICE       => 'UserNotice',
        E_RECOVERABLE_ERROR => 'RecoverableError',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'UserDeprecated',
    ];
    return $map[$errno] ?? 'UnknownError';
}

function meme_for_error(int $errno, string $errorMessage = ''): string {
    $memes = [
        E_ERROR             => 'https://i.imgflip.com/1ur9b0.jpg', // "This is fine"
        E_WARNING           => 'https://i.imgflip.com/2kbn1e.jpg', // Surprised Pikachu
        E_PARSE             => 'https://imgflip.com/i/9xr5xp', // Math Lady
        E_NOTICE            => 'https://i.imgflip.com/26br.jpg',   // John Travolta confused
        E_CORE_ERROR        => 'https://imgflip.com/i/9xr61s', // You Shall Not Pass
        E_CORE_WARNING      => 'https://i.imgflip.com/26am.jpg',   // Facepalm
        E_COMPILE_ERROR     => 'https://i.imgflip.com/1g8my4.jpg', // Spider-Man pointing
        E_COMPILE_WARNING   => 'https://i.imgflip.com/3lmzyx.jpg', // Big brain
        E_USER_ERROR        => 'https://i.imgflip.com/1bim.jpg',   // Dramatic chipmunk
        E_USER_WARNING      => 'https://i.imgflip.com/1ur9b0.jpg', // This is fine
        E_USER_NOTICE       => 'https://i.imgflip.com/2kbn1e.jpg', // Surprised Pikachu
        E_RECOVERABLE_ERROR => 'https://i.imgflip.com/26am.jpg',   // Facepalm
        E_DEPRECATED        => 'https://imgflip.com/i/9xr5u4', // Spider-Man pointing
        E_USER_DEPRECATED   => 'https://imgflip.com/i/9xr5u4',   // Dramatic chipmunk
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
