<?php
// © 2025 Chitch Contributors, Licensed under the EUPL
chdir(dirname($_SERVER['DOCUMENT_ROOT']));
require_once('library/bootstrap.php');

use function Chitch\{getfiles, tokenize, tree};

// Directory to scan for PHP files
$directory = '.'; // Adjust as needed
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
    while (count($filtered_lines) > 1 && $filtered_lines[count($filtered_lines) - 1] === '') {
        array_pop($filtered_lines);
    } // Remove any trailing empty lines
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

$error = null; // Add this at the top, before POST handling

// Handle save request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';

    if (!is_writable($file)) {
        http_response_code(400);
        $error = 'Invalid or unwritable file path.';
    } elseif ($content === null) {
        http_response_code(400);
        $error = 'No content provided.';
    } else {
        $filtered_content = filter_content($content);

        // Syntax check using php -l
        $tmpfile = tempnam(sys_get_temp_dir(), 'chitch_');
        file_put_contents($tmpfile, $filtered_content);
        $cmd = escapeshellcmd(PHP_BINARY) . ' --syntax-check ' . escapeshellarg($tmpfile) . ' 2>&1';
        $syntax_output = shell_exec($cmd);
        unlink($tmpfile);

        if (strpos($syntax_output, 'No syntax errors detected') === false) {
            $error = htmlspecialchars($syntax_output);
        } else {
            file_put_contents($file, $filtered_content);

    if (realpath($file) === __FILE__) {
        // If editing the editor itself, reload the editor
        header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=' . urlencode($file));
        exit;
    }

    // Redirect to the file just edited (relative to web root)
    $relative = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($file));
    //header('Location: ' . $relative);
    //exit;
        }
    }
}


// Read file content if not already set
$content ??= file_get_contents($file);
$highlighted = tree('li', fn($x) => ($x),  explode(PHP_EOL, tokenize($content))); // Generate syntax-highlighted markup
$metadata = get_content_metadata($content);

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="/styles/code.css" />
<script defer src="editor.js"></script>
<title>PHP File Editor</title>
<style>
    .error {
    color: #b00;
    background: #fee;
    border: 1px solid #b00;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 4px;
    font-weight: bold;
}
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

    pre code ol li {
        list-style-position: inside;
        /* fix */
    }

    textarea {
        font-feature-settings: "calt" 1;
        /* Enable ligatures for IE 10+, Edge */
        text-rendering: optimizeLegibility;
        /* Force ligatures for Webkit, Blink, Gecko */
        font-family: 'Fira Code VF', monospace;
    }
</style>

<main>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <?php
        // Compute the relative URL for the iframe preview, only if within document root
        $iframe_src = '';
        $realpath = realpath($file);
        $docroot = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
        if ($realpath && strpos($realpath, $docroot) === 0) {
            $iframe_src = substr($realpath, strlen($docroot));
            if ($iframe_src === '' || $iframe_src[0] !== '/') {
                $iframe_src = '/' . $iframe_src;
            }
        }
    ?>

    <?php if ($iframe_src): ?>
    <div style="margin-top:20px; resize:vertical; overflow:auto; min-height:100px; max-height:90vh; border:1px solid #ccc; padding:0; background:#fafbfc">
        <b>Preview:</b>
        <a href="<?= htmlspecialchars($iframe_src) ?>" target="_blank" rel="noopener" style="float:right;font-size:0.9em">Open in new tab</a>
        <iframe src="<?= htmlspecialchars($iframe_src) ?>" style="width:100%;height:50em;border:none;display:block"></iframe>
    </div>
    <?php endif; ?>
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
        <figure>
            <figcaption>Editing: <?= htmlspecialchars($file) ?></figcaption>
            <pre contenteditable=false spellcheck><code><ol><?= $highlighted ?></ol></code></pre>
            <p class="metadata">
                <?= $metadata['lines'] ?> lines,
                <?= $metadata['words'] ?> words,
                <?= $metadata['characters'] ?> characters
            </p>

        </figure>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const codeBlock = document.querySelector('pre code ol')
  if (!codeBlock) return

  // Map: variable name -> Set of line numbers
  const variables = {}

  codeBlock.querySelectorAll('li').forEach((li, idx) => {
    li.querySelectorAll('.T_VARIABLE').forEach(el => {
      const name = el.textContent
      if (!variables[name]) variables[name] = new Set()
      variables[name].add(idx + 1) // line numbers are 1-based
    })
  })

  // Build table
  const table = document.createElement('table')
  table.style.marginTop = '1em'
  table.innerHTML =
    '<tr><th>Variable</th><th>Line(s)</th></tr>' +
    Object.entries(variables)
      .map(([name, lines]) =>
        `<tr><td>${name}</td><td>${[...lines].join(', ')}</td></tr>`
      )
      .join('')

  codeBlock.parentNode.appendChild(table)
})
</script>

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
