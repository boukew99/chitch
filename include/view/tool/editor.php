<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

require('../../chitch.php');

use function Chitch\{getfiles, tokenize};

// Directory to scan for PHP files
$directory = '../../'; // Adjust as needed
$all_files = getfiles($directory, '*.php');

// Determine file to edit
$file = $_GET['edit'] ?? $all_files[0] ?? null; // Default to the first file if none selected

if (!$file || !is_readable($file)) {
    echo 'Invalid or unreadable file selected.';
    exit;
}

// Filter content to make it UNIX and VCS-compatible
function filter_content(string $content): string
{
    $lines = explode("\n", $content);
    $filtered_lines = array_map(fn($line) => rtrim($line), $lines); // Remove trailing whitespace
    return implode("\n", $filtered_lines) . "\n"; // Ensure LF line endings and a final newline
}

// Function to calculate metadata about the content
function get_content_metadata(string $content): array
{
    $lines = explode("\n", $content);
    $line_count = count($lines);
    $word_count = str_word_count($content);
    $character_count = strlen($content);
    return [
        'lines' => $line_count,
        'words' => $word_count,
        'characters' => $character_count,
    ];
}

// Handle save request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';

    if (!is_writable($file)) {
        http_response_code(400); // Bad Request
        echo 'Invalid or unwritable file path.';
        exit;
    }

    if ($content === null) {
        http_response_code(400); // Bad Request
        echo 'No content provided.';
        exit;
    }

    $filtered_content = filter_content($content); // Apply filtering
    //$syntax = `php --syntax-check $filtered_content`;
    file_put_contents($file, $filtered_content); // Save filtered content

    // Reload the page if the edited file is the current file
    if (realpath($file) === __FILE__) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=' . urlencode($file));
        exit;
    }

    // Use the posted content for further processing
    $content = $filtered_content;
}

// Read file content if not already set
$content ??= file_get_contents($file);
$highlighted = tokenize($content); // Generate syntax-highlighted markup
$metadata = get_content_metadata($content);

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="code.css" />
<script defer src="editor.js"></script>
<title>PHP File Editor</title>
<style>
    textarea {
        width: 100%;
        height: 300px;
    }

    button {
        margin-top: 10px;
    }

    form {
        margin-bottom: 20px;
    }

    .metadata {
        margin-top: 10px;
        font-size: 0.9em;
        color: #555;
    }
</style>

<main>

    <section>
        <h2>Select File to Edit</h2>
        <form method="get">
            <label>Choose a file:
                <input list="files" name="edit" value="<?= htmlspecialchars($file) ?>" />
                <datalist id="files">
                    <?php foreach ($all_files as $filepath): ?>
                        <option value="<?= htmlspecialchars($filepath) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </label>
            <button type="submit">Load</button>
        </form>
    </section>

    <section>
        <h2>Editing: <?= htmlspecialchars($file) ?></h2>
        <pre contenteditable=false spellcheck><code><?= $highlighted ?></code></pre>
        <p class="metadata">
            <?= $metadata['lines'] ?> lines,
            <?= $metadata['words'] ?> words,
            <?= $metadata['characters'] ?> characters
        </p>
        <form method="post">
            <label>Live code:
                <code><textarea name="content" id="editor"><?= htmlspecialchars($content) ?></textarea></code>
            </label>
            <button type="submit" id="save-button">Save</button>
        </form>

    </section>

    <section>
        <h3>Shortcuts</h3>
        <dl>
            <dt><kbd>Ctrl</kbd> + <kbd>/</kbd></dt>
            <dd>Select All</dd>
            <dt><kbd>Ctrl</kbd> + <kbd>z</kbd></dt>
            <dd>Undo</dd>
            <dt><kbd>Ctrl</kbd> + <kbd>y</kbd></dt>
            <dd>Redo</dd>
            <dt><kbd>Ctrl</kbd> + <kbd>ArrowUp</kbd> / <kbd>ArrowDown</kbd></dt>
            <dd>Navigate to the nearest whitespace line and scroll</dd>
        </dl>
    </section>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
