<?php // © 2025 Chitch Contributors, Licensed under the EUPL
chdir(dirname($_SERVER['DOCUMENT_ROOT']));
require_once('library/bootstrap.php');

use function Chitch\{getfiles, tokenize};
use function HTMLCompose\{form, textlist, submit_button, textarea, checkbox, section, voidelement,p, table, tree };

$all_files = getfiles('.', '*.php');

// Determine file to edit
$file = $_GET['edit'] ?? '';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';
    $filtered_content = filter_content($content);

    $tmpfile = tempnam(sys_get_temp_dir(), 'chitch_');
    file_put_contents($tmpfile, $filtered_content);
    $cmd = escapeshellcmd(PHP_BINARY) . ' --syntax-check ' . escapeshellarg($tmpfile) . ' 2>&1';
    $syntax_output = shell_exec($cmd);

    unlink($tmpfile);

    if (strpos($syntax_output, 'No syntax errors detected') === false) {
        $error = htmlspecialchars($syntax_output);
    } else {
        $old_content = file_exists($file) ? file_get_contents($file) : '';
        file_put_contents($file, $filtered_content);

        if (!empty($_POST['ai_review'])) {
            $API_KEY = Chitch\read('gemini')[0];
            $MODEL = 'gemini-2.0-flash';

            $old_tmp = tempnam(sys_get_temp_dir(), 'chitch_old_');
            file_put_contents($old_tmp, $old_content);
            $new_tmp = tempnam(sys_get_temp_dir(), 'chitch_new_');
            file_put_contents($new_tmp, $filtered_content);

            $diff_cmd = 'diff -u ' . escapeshellarg($old_tmp) . ' ' . escapeshellarg($new_tmp);
            $diff = shell_exec($diff_cmd);

            unlink($old_tmp);
            unlink($new_tmp);

            $payload = json_encode([
                'contents' => [
                    ['parts' => [['text' => "Review this PHP code change:\n" . $diff]]]
                ]
            ]);
            $url = "https://generativelanguage.googleapis.com/v1/models/" . urlencode($MODEL) . ":generateContent?key=" . urlencode($API_KEY);
            $cmd = "curl -s -X POST " . escapeshellarg($url) .
                " -H 'Content-Type: application/json' -d " . escapeshellarg($payload);
            $ai_review = shell_exec($cmd);
            $ai_json = json_decode($ai_review, true);
            $ai_message = $ai_json['candidates'][0]['content']['parts'][0]['text'] ?? 'No review received.';
            $error = '<b>AI Review:</b><br>' . nl2br(htmlspecialchars($ai_message));
        }

        if (realpath($file) === __FILE__) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?edit=' . urlencode($file));
            exit;
        }
    }
}


// Read file content if not already set
$content ??= $file ? file_get_contents($file) : '';
$highlighted = tree('li',  ...explode(PHP_EOL, tokenize($content))); // Generate syntax-highlighted markup
$metadata = get_content_metadata($content);

?>
<?= Chitch\head('PHP File Editor'); ?>
<?= HTMLCompose\stylesheet('/styles/code.css'); ?>
<script defer src="editor.js"></script>
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
    height: 50em;
}

pre code ol li {
    list-style-position: inside;
    /* fix */
}

</style>

<?= Chitch\header('PHP File Editor', 'Use this tool to edit PHP files.') ?>

<main>

<?= section('select', 'Select File to Edit', form(false,
    textlist('Choose a file:', 'edit', ...getfiles('.', '*.php')),
    submit_button('Load')
)) ?>


<section>
    <h2>Current State</h2>
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

</section>

<section>

<figure>

<figcaption>Editing: <?= htmlspecialchars($file) ?></figcaption>
<pre contenteditable=true spellcheck><code><ol><?= $highlighted ?></ol></code></pre>


</figure>
<pre>
<?php
function getTokenCountsData(string $code): array
{
    $tokens = token_get_all($code);

    // Use array_reduce to build the counts array.
    $counts = array_reduce($tokens, function($carry, $token) {
        if (is_array($token)) {
            $tokenName = token_name($token[0]);
            $carry[$tokenName] = ($carry[$tokenName] ?? 0) + 1;
        } else {
            $carry[$token] = ($carry[$token] ?? 0) + 1;
        }
        return $carry;
    }, []);

    // Convert the associative array into a multi-dimensional array for the table function.
    $tableRows = [];
    foreach ($counts as $tokenName => $count) {
        $tableRows[] = [$tokenName, $count];
    }

    return $tableRows;
}

/**
 * Gets the values, counts, and line numbers for all T_VARIABLE tokens using array_reduce.
 *
 * @param string $code The PHP code to analyze.
 * @return array A multi-dimensional array suitable for the table function.
 */
function getVariableOccurrencesData(string $code): array
{
    $tokens = token_get_all($code);

    // Use array_reduce to build the variables data structure.
    $variables = array_reduce($tokens, function($carry, $token) {
        if (is_array($token) && $token[0] === T_VARIABLE) {
            $variableName = $token[1];
            $lineNumber = $token[2];

            if (!isset($carry[$variableName])) {
                $carry[$variableName] = ['count' => 0, 'lines' => []];
            }

            $carry[$variableName]['count']++;
            $carry[$variableName]['lines'][] = $lineNumber;
        }
        return $carry;
    }, []);

    // Convert the structured array into a multi-dimensional array for the table function.
    $tableRows = [];
    foreach ($variables as $variableName => $data) {
        $lines = implode(', ', array_unique($data['lines']));
        $tableRows[] = [$variableName, $data['count'], $lines];
    }

    return $tableRows;
}

// --- Token Counts Table ---
echo table(
    "Token Type Occurrences",
    ['Token', 'Count'],
    ...getTokenCountsData($content)
);

// --- Variable Occurrences Table ---
echo table(
    "Variable Occurrences and Line Numbers",
    ['Variable', 'Count', 'Lines'],
    ...getVariableOccurrencesData($content)
);

?>

?>
</pre>
<?= tree('p', implode(' ', [
    $metadata['lines'] . ' lines,',
    $metadata['words'] . ' words,',
    $metadata['characters'] . ' characters'
]))?>

<?= table('hi', ['1', 2], [true, 2.3], [[], 'hi']) ?>

<figure id="variable-table">
<?= table('Variables Used', ['Variable', 'Line(s)']) ?>
</figure>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const codeBlock = document.querySelector('pre code ol')

    // Map: variable name -> Set of line numbers
    const variables = {}

    codeBlock.querySelectorAll('li').forEach((li, idx) => {
        li.querySelectorAll('.T_VARIABLE').forEach(el => {
            const name = el.textContent
            if (!variables[name]) variables[name] = new Set()
            variables[name].add(idx + 1) // line numbers are 1-based
        })
    })

    // Build rows
    const tbody = document.createElement('tbody')
    tbody.innerHTML =
        Object.entries(variables)
        .map(([name, lines]) =>
            `<tr><td>${name}<td>${[...lines].join(', ')}`
        )
        .join('')

    const table = document.querySelector('#variable-table table')
    table.appendChild(tbody)
})
</script>


<?=
form(true,
    textarea('Live code:', 'content', $content) .
    checkbox('AI Review', 'ai_review') .
    submit_button('Save'),
    )
?>

</section>

<?= section('shortcuts', 'Extra Shortcuts', '
    <dl>
        <dt><kbd>Ctrl</kbd> + <kbd>ArrowUp</kbd> / <kbd>ArrowDown</kbd></dt>
        <dd>Navigate to the nearest whitespace line and scroll</dd>
    </dl>
') ?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>

<?php
# https://www.jsdelivr.com/package/npm/diff
