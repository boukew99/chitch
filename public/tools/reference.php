<?php
// © 2025 Chitch Contributors, Licensed under the EUPL
declare(strict_types=1);

require_once("../../library/bootstrap.php");
session_start();

use function Chitch\{authorize, getfiles, write, read, tokenize, batch, log_path, tree, wrap};

$coverage = 8;

// Get all functions from Chitch namespace
$library = get_defined_functions()['user'];
$library = array_filter($library, fn($f) => str_starts_with($f, 'chitch\\'));

// get document https://www.php.net/manual/en/reflectionclass.getdoccomment.php
?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="styles/code.css" />
<title>Test Library of Chitch</title>

<header>
    <h1>Test Library of Chitch</h1>
    <p>Test code of <code>chitch.php</code>. Coverage <?= $coverage ?>/<?= sizeof($library) ?>
    <details>
        <summary>Table of Contents</summary>
        <nav id="toc"></nav>
    </details>
</header>
<main>

    <?php
    $test = function ($closure) {
        $startMem = memory_get_usage();
        $startTime = microtime(true);

        $success = print_r($closure) ? 'SUCCESS' : 'FAILURE';

        $endTime = microtime(true);
        $endMem = memory_get_usage();

        $time = $endTime - $startTime;
        $memUsed = $endMem - $startMem;
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        # https://www.php.net/manual/en/function.debug-backtrace.php

        return "<p>$success, time: $time sec, memory: $memUsed bytes, trace: <pre>" . print_r($trace, true) . "</pre></p>";
    };
    ?>

    <section id="getfiles">
        <header>
            <h2>getfiles</h2>
            <p>Will return a list of files in the given directory recursively. The precise handling of the different file types can be more clearly defined within this function, compared to the Iterator Classes.
        </header>
        <pre><?= $test(getfiles('../')) ?></pre>
    </section>

    <section id="authorize">
        <header>
            <h2>authorize</h2>
            <p>Will check if the user is authorized to access by testing against the submitted $group. The values for authorization are set in <code>login.php</code>.
        </header>
        <pre>Is administrator: <?= $test(authorize('administrator') ? 'YES' : 'NO') ?></pre>
    </section>

    <section id="write">
        <header>
            <h2>write</h2>
            <p>Writes a string to a file. If the file does not exist, it will be created. If the file exists, it will be appended to. The function returns true on success and false on failure. It appends markers for the read function to read the file concurrently. The markers is <code>&lt;!-- COMMIT --&gt;</code>.
            <p>If write is under the <code>BLOCKSIZE</code> then it will write atomically without locking the file, which is faster.</p>
        </header>
        <pre>Write <?= $test(write('test', 'test') ? 'successful' : 'failed') ?></pre>
    </section>

    <section id="read">
        <header>
            <h2>read</h2>
            <p>Reads a file and returns its content. If the file does not exist, it returns an empty string. This functions can read concurrently, due to the use of markers in the file. So if another process would be writing to the same file, and it the file write is not complete. Then this function will read to the latest marker, which indicates the last stable point within the file.
        </header>
        <pre><?= $test(implode('', read('test'))) ?></pre>
    </section>

    <section id="tokenize">
        <header>
            <h2>tokenize</h2>
            <p>Tokenizes a PHP code string and returns the code marked-up with its token identifier as its class. This class can then be styled with CSS for custom styles. This is more flexible than the default <code>highlight_string</code>, which hardcodes colors.
        </header>
        <pre><code><?= $test(tokenize('<?php echo "Hello, World!"; ?>')) ?></code></pre>
    </section>

    <section id="batch">
        <header>
            <h2>batch</h2>
            <p>Can be used to create a list of items from an array of value and an array of key and value pairs.</p>
        </header>
        <pre><?= ($test(htmlspecialchars(batch(['a' => '1', 'b' => '2'], fn($item, $key) => "<dt>$key</dt><dd>$item</dd>\n")))) ?></pre>
        <pre><?= $test(htmlspecialchars(batch(['1', '2'], fn($item) => "<li>$item\n"))) ?></pre>
    </section>

    <section id="log_path">
        <header>
            <h2>log_path</h2>
            <p>Echoes the path where the <code>logs</code> directory is located. This folder is used to store user input.
        </header>
        <pre><?= $test(log_path()) ?></pre>
    </section>

    <section id="tree">
        <header>
            <h2>tree</h2>
            <p>Used to build markup bottom-up
        </header>
        <?= $test(wrap('ul', tree("li", fn($x) => htmlspecialchars($x), "stick", "rock", "spark"))) ?>
        <?= $test(
            '<table>' .
            tree(
                "tr",
                fn($row) =>
                tree("td", fn($cell) => ($cell), $row),
                ["a", "b"], ["c", "d"]
            )
            . '</table>'
        )
        ?>
        </table>
        <?php
        function makeListFn(): callable {
            return function ($x) {
                if (is_array($x)) {
                    $label = htmlspecialchars($x[0]);
                    $sub = wrap('ul', tree("li", makeListFn(), $x[1]));
                    return $label . "</li>" . $sub;
                } else {
                    return htmlspecialchars($x) . "</li>";
                }
            };
        }
        ?>
        <?= $test(wrap('ul', tree("li", makeListFn(), [
            "stick",
            ["rock", ["small rock", ["big rock", ['fire-stick', 'deerskin']]]],
            "leaf"
        ])))
        ?>
    </section>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
