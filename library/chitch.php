<?php
# © Chitch Contributors 2025, Licensed under the EUPL.

declare(strict_types=1);

namespace Chitch;

const BLOCKSIZE = 4096;
const OWNER = "chitch.org"; // Ideally change per site
# Blocks Shared Session: https://stackoverflow.com/questions/1545357/how-can-i-check-if-a-user-is-logged-in-in-php

/**
 * Send an email using the built-in mail function.
 * In development mode, it will log the email instead.
 */
function chitchmail(string $email, string $subject, string $message, string $lang = 'en') {
    if (php_sapi_name() === "cli-server") {
        // In dev mode, use a debug function to log emails
        $bot = empty($bot);
        $urgency =  'normal';
        $nickname = "";
        $urgency = "normal";
        $name = "Chitch Mailer";

        write("messages", message(
            $subject,
            $lang,
            $message,
            $email,
            $name,
            $urgency,
            $bot
        ), true);
    }
    else {
        mail(
                $email,
                $subject,
                $message,
                "From: no-reply@" . OWNER,
                "-f no-reply@" . OWNER
            );
    }
}

/**
 * Get the path to the log file in the database directory.
 * If $file is provided, it appends ".html" to the file name.
 */
function log_path(string $file = ""): string
{
    $base = dirname(__DIR__, 2) . "/database/";
    return $file ? $base . $file . ".html" : $base;
}

/**
 * Concurrent stream file read with commit markers.
 */
function read(string $file): array
{
    $file = log_path($file);

    if (!file_exists($file)) {
        return [];
    }

    $content = file_get_contents($file);

    // Find last commit marker
    $pos = strrpos($content, "<!-- COMMIT -->");
    if ($pos === false) {
        return [];
    }

    // Get content up to last commit
    $validContent = substr($content, 0, $pos + strlen("<!-- COMMIT -->"));
    $blocks = explode("<!-- COMMIT -->", $validContent);
    // Remove empty entries and trim
    $blocks = array_filter(array_map("trim", $blocks));

    $entries = [];

    foreach ($blocks as $block) {
        if (preg_match('/<!-- HASH: ([a-f0-9]{6,64}) -->/', $block, $match)) {
            $hash = $match[1];
            $cleaned = preg_replace('/<!-- HASH: [a-f0-9]{6,64} -->\s*/', '', $block, 1);
            $entries[$hash] = $cleaned;
        } else {
            $entries[] = $block;
        }
    }

    return $entries;
}

/**
 * Write data to a file with commit markers.
 * If the file does not exist, it will be created.
 * If $withHash is true, a hash comment will be added.
 */
function write(string $file, string $data, bool $withHash = true): bool
{
    $file = log_path($file);

    if (!file_exists($file)) {
        touch($file);
        chmod($file, 0644);
    }

    $entry = $withHash
        ? "<!-- HASH: " . sha1($data) . " -->\n$data\n<!-- COMMIT -->\n"
        : "$data\n<!-- COMMIT -->\n";

    // Check if content is small enough for an atomic write
    // No lock for fast atomic writes
    $flags = strlen($entry) < BLOCKSIZE ? FILE_APPEND : FILE_APPEND | LOCK_EX;

    $written = file_put_contents($file, $entry, $flags) ? true : false;

    if ($written === false) {
        throw new \RuntimeException("Failed to write to file: $file");
    }

    return $written;
}


function overwrite(string $file, string $targetHash, string $newData): bool
{
    $file = log_path($file);

    if (!file_exists($file)) {
        throw new \RuntimeException("File not found: $file");
    }

    $content = file_get_contents($file);

    // Find all commit blocks with hash
    preg_match_all('/<!-- HASH: ([a-f0-9]{40,64}) -->\s*(.*?)\s*<!-- COMMIT -->/s', $content, $matches, PREG_SET_ORDER);

    $newEntries = [];
    $found = false;

    foreach ($matches as $match) {
        $hash = $match[1];
        $block = $match[2];

        if ($hash === $targetHash) {
            // Replace this block
            $newHash = sha1($newData);
            $entry = "<!-- HASH: $newHash -->\n$newData\n<!-- COMMIT -->";
            $newEntries[] = $entry;
            $found = true;
        } else {
            // Keep original
            $newEntries[] = $match[0]; // full match, unmodified
        }
    }

    if (!$found) {
        throw new \RuntimeException("Commit with hash $targetHash not found.");
    }

    // Join all entries
    $newContent = implode("\n\n", $newEntries) . "\n";

    // Write full file (lock for safety)
    $written = file_put_contents($file, $newContent, LOCK_EX);

    return $written !== false;
}


/**
 * Batch process an array with a constructor function.
 * The constructor can optionally use the key and item.
 */
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

/**
 * Check if the current session is authorized for the input groups.
 */
function authorize(string ...$group): bool
{
    return match (true) {
        !isset($_SESSION['authorized'], $_SESSION['groups']) => false,
        $_SESSION['authorized'] === OWNER && array_intersect($group, $_SESSION['groups']) => true,
        default => false
    };
}

/**
 * Markup PHP code with token class names.
 */
function tokenize(string $code): string
{
    return implode(
        "",
        array_map(function ($token) {
            return match (true) {
                is_array($token) =>
                    "<span class='" . token_name($token[0]) . "'>" . htmlspecialchars($token[1]) . "</span>",
                default => "<span class='T_token-char'>" . htmlspecialchars($token) . "</span>"
            };
        }, token_get_all($code))
    );
}
# Class Markup equal variable names?

/**
 * Recursively get all files in a directory, optionally filtered by a glob pattern.
 * Symlinked directories are included, but symlinked files are skipped.
 */
function getfiles(string $directory, ?string $filter = "*"): array
{
    $paths = glob("$directory/*");
    return array_reduce($paths, function ($result, $path) use ($filter) {
        return match (true) {
            is_link($path) => $result, // Skip symlinks
            is_dir($path) =>
                array_merge($result, getfiles($path, $filter)), // Dive deeper
            fnmatch($filter, basename($path)) && !is_link($path) =>
                array_merge($result, [$path]), // Include matching files, skip symlinked files
            default => $result
        };
    }, []);
}

/**
 * Validate input which is expected to be an integer.
 */
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

/**
 * Validate input which is expected to be a float.
 */
function validate_float(int $type, string $key, ?float $min = null, ?float $max = null): ?float
{
    $val = filter_input($type, $key, FILTER_VALIDATE_FLOAT);
    return match (true) {
        !is_float($val) => null,
        ($min !== null && $val < $min) => null,
        ($max !== null && $val > $max) => null,
        default => $val
    };
}

/**
 * Echoes the slider element and returns its value.
 */
function slider(int $type, string $name, int $min = 0, int $max = 1) : float {
    $value = filter_input($type, $name, FILTER_VALIDATE_FLOAT) ?? $min; // Default to min if not set or invalid
    echo "<input type='range' name='$name' min='$min' max='$max' value='$value' class='slider' />";
    return $value;
}

/**
 * Validate input which is expected to be a boolean.
 */
function validate_bool(int $type, string $key): ?bool
{
    $val = filter_input($type, $key, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    return is_bool($val) ? $val : null;
}

/**
 * Validate input which is expected to be a string.
 */
function validate_string(int $type, string $key, ?int $minLength = null, ?int $maxLength = null, $unsafe = false): ?string
{
    $val = filter_input($type, $key, FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE);
    $len = is_string($val) ? mb_strlen($val) : 0;
    return match (true) {
        !is_string($val) => null,
        ($minLength !== null && $len < $minLength) => null,
        ($maxLength !== null && $len > $maxLength) => null,
        $unsafe => $val,
        default => htmlspecialchars($val)
    };
}

function p(string $content, string ...$attributes): string
{
    $attributes = implode(" ", $attributes);
    return $content ? "<p $attributes>" . $content : '';
}

function a(string $content, string $url, string ...$attributes): string {
    return wrap('a', $content, "href='$url'", ...$attributes);
}

function ul(string ...$items): string
{
    return wrap('ul', tree('li', fn($item) => $item, $items));
}

function ol(string ...$items): string
{
    return wrap('ol', tree('li', fn($item) => $item, $items));
}

function table(string $caption, string $headrow, string ...$rows): string
{
    return wrap('table',
        wrap('caption', $caption) . wrap('thead', $headrow)  . tbody(...$rows),
    );
}

function tbody(array ...$rows): string
{
    return wrap('tbody', tree('tr', fn($row) => tree("td", fn($cell) => ($cell), $row), $rows));
}

function tr(string ...$cells): string
{
    return '<tr>' . tree("td", fn($cell) => ($cell), $cells);
}

function pre(string $content): string
{
    // Use <<<TXT to preserve newlines and indentation in $content
    return wrap('pre', $content);
}

function code(string $content, string $language): string
{
    return wrap('code', $content, "class='$language'");
}

function img(string $src, ?string $alt = "", ?int $height = 0, ?int $width = 0, ?string $filter = ''): string
{
    return "<img src='$src' loading='lazy' alt='$alt' height='$height' width='$width' class='$filter' />";
}

function article(string $id, string $lang, string $content): string
{
    return wrap('article', $content, "id='$id'", "lang='$lang'");
}

function details(string $summary, string $content): string
{
    return wrap('details', wrap('summary', $summary) . $content);
}

function blockquote(string $content, string $cite =''): string
{
    return wrap('blockquote', $content, $cite ? "cite='$cite'" : '');
}
function message( string $subject, string $lang, string $message, string $email, string $name, string $urgency, bool $bot = false): string {
        return details(
                $subject,
                article(
                    uniqid(),
                    $lang,
                    wrap('h3', $subject) .
                    wrap('time', strval($nowtime = time()), "post-time: " . date('Y-m-d H:i:s', $nowtime)) .
                    wrap('pre', $message) .
                    wrap('address',
                        a($name, "mailto:$email")
                    )
                )
                );
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

/**
 * reverse bread-depth split tag resolver
 */
function tree(string $seperator, callable $fn, ...$data): string
{
    #Flatten
    $content = (count($data) === 1 && is_array($data[0])) ? $data[0] : $data;

    return "<$seperator>" . implode("<$seperator>",
        array_map($fn, $content)
    );
}

function head(string $title ='', bool $analytics = false): string
{
    return <<<HTML
<!doctype html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" media="(prefers-color-scheme: light)" content="#c0ceed">
<meta name="theme-color" media="(prefers-color-scheme: dark)" content="#10424b">

<link rel="stylesheet" href="/styles/chitch.css?v=1">
<link rel="icon" href="/app/icon.svg?1">

<script defer src="/script/ensue.js"></script>

<title>$title</title>
<meta name="generator" content="Chitch">
<script type="speculationrules">
{ "prerender": [{
    "source": "document",
    "where": {
        "and": [ {"href_matches": "/*"} ]},
        "eagerness": "moderate"
    }]
}
</script>
HTML .
    ($analytics ? '<script defer src="/script/visit.js"></script>' : '') .
    (php_sapi_name() === "cli-server" ? "<script defer src='/tools/check.js'></script>" : '');

//https://caniuse.com/?search=svg%20favicon
}

function header(string $title = 'Chitch', string $description = 'A sustainable website', $toc = false): string
{
    $session = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    ob_start();
    ?>
<title><?= $title ?></title>
<header>
    <h1><?= $title ?></h1>
    <p><?= $description ?></p>
    <?php if ($toc) { ?>
    <details>
        <summary>Table of Contents</summary>
        <nav id="toc"></nav>
    </details>
    <?php } ?>
</header>
<?php
    return ob_get_clean();
}

function docroot_path(string $file): string
{
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($file));
}

function foot(): string
{
    return p(
        "&copy; Copyright 2025. All rights reserved, Chitch.org" .
        img('/styles/icon.svg', 'site logo', 16, 16) .
        " Rendered today on " . date("g:i a") . " in Germany. Hosted with green energy."
    ) .
    p (
        "~" . count(glob(session_save_path() . "/sess_*")) . " user(s) have connected!"
    ) .
    p( a("Site Map", "/map.php"));
}

const cdn_address = "https://cdn.chitch.org/";
function cdn($path) {
    return cdn_address . ltrim($path, '/');
}

function authstate() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $username = $_SESSION["username"] ?? "anonymous";
        $permissions = implode(", ", $_SESSION["groups"] ?? []);
        $loggedin = ($_SESSION["authorized"] ?? "") === $_SERVER["HTTP_HOST"];
        echo p("You are logged into a session as <mark>$username</mark> with $permissions permission." );
    }
    else {
        echo p('No session.');
    }
}

# Spread Operator: https://stackoverflow.com/questions/41124015/what-is-the-meaning-of-three-dots-in-php
# Handling User input https://stackoverflow.com/questions/129677/how-can-i-sanitize-user-input-with-php/130323#130323

