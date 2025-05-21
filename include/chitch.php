<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL.

declare(strict_types=1);

namespace Chitch;

const BLOCKSIZE = 4096;
const OWNER = "chitch.org"; // Ideally change per site
# Blocks Shared Session: https://stackoverflow.com/questions/1545357/how-can-i-check-if-a-user-is-logged-in-in-php

// Dev Mode
php_sapi_name() === "cli-server" &&
    (require_once __DIR__ . "/test.php");

function log_path(string $file = ""): string
{
    $base = dirname(__DIR__, 1) . "/database/";
    return $file ? $base . $file . ".html" : $base;
}

/**
 * Concurrent stream file read
 */
function read(string $file, int $cutoff = 0): string
{
    $file = log_path($file);

    if (!file_exists($file)) {
        return "";
    }

    $content = file_get_contents($file);

    // Find last commit marker
    $pos = strrpos($content, "<!-- COMMIT -->");
    if ($pos === false) {
        return "";
    }

    // Get content up to last commit
    $validContent = substr($content, 0, $pos + strlen("<!-- COMMIT -->"));

    // Split content by commit markers
    $entries = explode("<!-- COMMIT -->", $validContent);

    // Remove empty entries and trim
    $entries = array_filter(array_map("trim", $entries));

    // Get last $cutoff entries or all if $cutoff is 0
    if ($cutoff > 0) {
        $entries = array_slice($entries, -$cutoff);
    }

    return implode("\n", $entries);
}

function write(string $file, string $data): bool
{
    $file = log_path($file);

    // Create file if it doesn't exist
    if (!file_exists($file)) {
        touch($file);
        chmod($file, 0644);
    }

    $entry = $data . "\n<!-- COMMIT -->\n";

    // Check if content is small enough for an atomic write
    // No lock for fast atomic writes
    $flags = strlen($entry) < BLOCKSIZE ? FILE_APPEND : FILE_APPEND | LOCK_EX;
    $written = file_put_contents($file, $entry, $flags) ? true : false;

    // false = number_written = 0
    if ($written === false) {
        throw new \RuntimeException("Failed to write to file: $file");
        #error_log('Failed to write to ' . $file);
    }
    return $written;
}

function batch(array $array, callable $constructor, string $marker = ''): string
{
    return $marker . implode(
        $marker,
        array_map(
            function ($item, $key = null) use ($constructor) {
                // The constructor can optionally use $key and $item
                return $constructor($item, $key);
            },
            array_values($array), // Ensures indexed array handling
            array_keys($array)
        )
    );
}

function authorize(string ...$group): bool
{
    if (!isset($_SESSION['authorized'], $_SESSION['groups'])) {
        return false;
    }
    return $_SESSION['authorized'] === OWNER &&
        count(array_intersect($group, $_SESSION['groups'])) > 0;
}


function tokenize(string $code): string
{
    return implode(
        "",
        array_map(function ($token) {
            if (is_array($token)) {
                [$id, $text] = $token;
                $class = token_name($id);
                $escaped = htmlspecialchars($text);
                return "<span class='token-$class'>$escaped</span>";
            }
            return "<span class='token-char'>" .
                htmlspecialchars($token) .
                "</span>";
        }, token_get_all($code))
    );
}
# Class Markup equal variable names?

function getfiles(string $directory, ?string $filter = "*"): array
{
    $result = [];
    foreach (glob("$directory/*") as $path) {
        if (is_dir($path)) {
            if (is_link($path)) {
                $result[] = $path; // Include symlinked 🔗 dirs
            } else {
                $result = array_merge($result, getfiles($path, $filter)); // Dive deeper 🕳️
            }
        }
        elseif (fnmatch($filter, basename($path))) { // Include matching files
            if (!is_link($path)) { // exclude symlinked 🔗 files (double)
                $result[] = $path;
            }
        }
    }
    return $result;
}

function validate_int(int $type, string $key, ?int $min = null, ?int $max = null): ?int
{
    $val = filter_input($type, $key, FILTER_VALIDATE_INT, [
        'options' => array_filter([
            'min_range' => $min,
            'max_range' => $max,
        ]),
    ]);
    return $val === false ? null : $val;
}

function validate_float(int $type, string $key, ?float $min = null, ?float $max = null): ?float
{
    $val = filter_input($type, $key, FILTER_VALIDATE_FLOAT);
    if (!is_float($val)) return null;
    if (($min !== null && $val < $min) || ($max !== null && $val > $max)) return null;
    return $val;
}

function validate_bool(int $type, string $key): ?bool
{
    $val = filter_input($type, $key, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    return is_bool($val) ? $val : null;
}

function validate_string(int $type, string $key, ?int $minLength = null, ?int $maxLength = null, $unsafe = false): ?string
{
    $val = filter_input($type, $key, FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
    if (!is_string($val)) return null;
    $len = mb_strlen($val);
    if (($minLength !== null && $len < $minLength) || ($maxLength !== null && $len > $maxLength)) return null;
    return $unsafe ? $val : htmlspecialchars($val);
}

function p(string $content, string ...$attributes): string
{
    $attributes = implode(" ", $attributes);
    return $content ? "<p $attributes>" . $content : '';
}

function a(string $content, string $url, string $attributes = '') : string {
    return "<a href='$url' $attributes>$content</a>";
}

function pipe(mixed $value, callable ...$functions) : mixed
{
    return array_reduce(
        $functions,
        fn($carry, $fn) => $fn($carry),
        $value
    );
}

function textmarkup(string $text): string
{
    return pipe(
        $text,

        // 🛡️ Escape HTML
        fn($text) => htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),

        // 🧱 Inline-style multi-line code: ```...``` → <code>...</code> with <br>
        fn($text) => preg_replace_callback(
            '/```(.*?)```/s',
            fn($m) =>
            '<code>' . nl2br(trim($m[1])) . '</code>',
            $text
        ),

        // ⚙️ Inline code: `code`
        fn($text) => preg_replace('/`([^`\n]+)`/', '<code>$1</code>', $text),

        // 🌿 Bold **bold**
        fn($text) => preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $text),

        // 🌾 Italic *italic*
        fn($text) => preg_replace('/\*(.*?)\*/s', '<em>$1</em>', $text),

        // 🪵 Wrap each paragraph with <p>
        fn($text) => implode("\n", array_map(
            fn($p) => "<p>$p</p>",
            preg_split("/\n\s*\n/", trim($text))
        ))
    );
}

function wrap(string $wrap, string $content, string ...$attributes) {
    $attributes = implode(" ", $attributes);
    return "<$wrap $attributes>$content</$wrap>";
}

# reverse bread-depth split tag resolver
function tree(string $seperator, callable $fn, ...$data): string
{
    #Flatten
    $content = (count($data) === 1 && is_array($data[0])) ? $data[0] : $data;

    return "<$seperator>" . implode("<$seperator>",
        array_map($fn, $content)
    );
}

function head(): string
{
    return <<<HTML
    <!doctype html>
    <html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#c0ceed" />
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#10424b" />

    <link rel="stylesheet" href="/chitch.css?v=1" />
    <link rel="icon" href="/assets/icon.svg?1" />
    <script defer src="/ensue.js"></script>

    <meta name="generator" content="Chitch" />
    <script type="speculationrules">
    { "prerender": [{
        "source": "document",
        "where": {
            "and": [ {"href_matches": "/*"} ]},
            "eagerness": "moderate"
        }]
    }
    </script>
    HTML;
}

function foot(): string
{
    ob_start(); // 📦 grab all da echo'd stuff

    if (session_status() === PHP_SESSION_ACTIVE) {
        $username = $_SESSION["username"] ?? "anonymous";
        $permissions = implode(", ", $_SESSION["groups"] ?? []);
        $loggedin = ($_SESSION["authorized"] ?? "") === $_SERVER["HTTP_HOST"];
        echo p($loggedin
            ? "You are logged into a session as <mark>$username</mark> with $permissions permission."
            : a('Login to Chitch', '/login.php')
        );
    }
    echo <<<HTML
    <p>&copy; Copyright 2025. All rights reserved, Chitch.org
        <img src='/assets/icon.svg' alt='site logo' width='16' height='16' />. Rendered today on <?= date("g:i a") ?> in Germany. Hosted with green energy.
    </p>
    HTML;

    $online = count(glob(session_save_path() . "/sess_*"));
    echo "<p>~$online user(s) online!</p>";

    $nav = tree("li", fn($x) => "<a href='$x[1]'>$x[0]</a>", [
        ["Go to top", "#"],
        ["Home", "/"],
        ["Blog / Articles", "/news.php"],
        ["Guestbook", "/guestbook.php"],
        ["Contact", "/contact.php"],
        ["Site Traffic", "/traffic.php"],
    ]);

    echo "<nav><ul>$nav</ul>";

    if (php_sapi_name() === "cli-server") {
        $tools = tree("li", fn($p) => "<a href='/tool/$p.php'>/$p</a>",
            array_map(fn($f) => basename($f, ".php"),
                glob($_SERVER["DOCUMENT_ROOT"] . "/tool/*.php")
            )
        );
        echo "<ul><li>Tools:<ul>$tools</ul></li></ul>";
    }

    echo "</nav>";

    return ob_get_clean(); // 🎁 give back da string
}

# Spread Operator: https://stackoverflow.com/questions/41124015/what-is-the-meaning-of-three-dots-in-php
