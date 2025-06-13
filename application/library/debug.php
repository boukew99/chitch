<?php

# ini_set('open_basedir', dirname(__DIR__,2) ); // Restrict access to test for total containment🔒
ini_set("sys_temp_dir", dirname(__DIR__, 2) . "/php/temp/");
ini_set("session.save_path", dirname(__DIR__, 2) . "/php/session/");

#error_reporting(E_ALL);

//https://www.php.net/manual/en/errorfunc.constants.php#123901
// Map error levels to their string representations
function getErrorLevelName($severity)
{
    $levels = [
        E_ERROR => "Error",
        E_WARNING => "Warning",
        E_PARSE => "Parsing Error",
        E_NOTICE => "Notice",
        E_CORE_ERROR => "Core Error",
        E_CORE_WARNING => "Core Warning",
        E_COMPILE_ERROR => "Compile Error",
        E_COMPILE_WARNING => "Compile Warning",
        E_USER_ERROR => "User Error",
        E_USER_WARNING => "User Warning",
        E_USER_NOTICE => "User Notice",
        E_RECOVERABLE_ERROR => "Catchable Fatal Error",
        E_DEPRECATED => "Deprecated",
        E_USER_DEPRECATED => "User Deprecated",
    ];

    return $levels[$severity] ?? "Unknown Error";
}

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && $error["type"] & (E_ERROR | E_PARSE)) {
        echo <<<HTML
<pre class='error'>
☠️ Fatal Error
Message: {$error["message"]}
File: {$error["file"]}
Line: {$error["line"]}
</pre>
HTML;
    }
});

// Custom error handler
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // Skip errors not included in error_reporting
        return false;
    }

    $levelName = getErrorLevelName($severity);

    echo <<<HTML
<pre class='error'>
⚠️ PHP Error: {$levelName}
Message: $message
File: $file
Line: $line
Severity: $severity
</pre>
HTML;

    // Prevent default PHP error handling (turn off to test)
    return true;
});

// Custom exception handler
set_exception_handler(function ($exception) {
    echo <<<HTML
<pre class='error'>
📢 Uncaught Exception
Message: {$exception->getMessage()}
File: {$exception->getFile()}
Line: {$exception->getLine()}
Trace:
{$exception->getTraceAsString()}
</pre>
HTML;
});

// Test it out
// trigger_error("Test notice!", E_USER_NOTICE);
// throw new Exception("Test exception!");


# © Chitch-Maintainers 2025, Licensed under the EUPL
