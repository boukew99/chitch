<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{tokenize};

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="/styles/code.css" />
<title>Recursive Diff</title>
<style>
    .added {
        background-color: color-mix(in srgb,
                light-dark(#d4f8d4, #143214) 100%,
                transparent);
        color: light-dark(#008000, #4dff4d);
    }

    .deleted {
        background-color: color-mix(in srgb,
                light-dark(#f8d4d4, #321414) 100%,
                transparent);
        color: light-dark(#800000, #ff4d4d);
    }

    .moved {
        background-color: color-mix(in srgb,
                light-dark(#fff5cc, #332b00) 100%,
                transparent);
        color: light-dark(#806600, #ffdb4d);
        font-weight: bold;
    }

    .syntax-added {
        background-color: color-mix(in srgb,
                light-dark(rgb(194, 240, 228), #0f2924) 100%,
                transparent);
        color: light-dark(#006700, #33ff99);
    }

    .syntax-deleted {
        background-color: color-mix(in srgb,
                light-dark(rgb(240, 194, 234), #290f24) 100%,
                transparent);
        color: light-dark(#670000, #ff33cc);
    }

    .file-change {
        background-color: color-mix(in srgb,
                light-dark(rgb(194, 220, 240), #0f1f24) 100%,
                transparent);
        color: light-dark(#003367, #3399ff);
        font-weight: bold;
    }

    li:target:after {
        content: " 👈";
    }

    pre ol li {
        list-style-position: inside;
        /* fix */
    }

    .file-change-item {
        display: flex;
        align-items: center;
        gap: 1em;
        margin: 0.5em 0;
    }

    .arrow {
        color: #666;
    }
</style>
<header>
    <h1>Directory Content Delta</h1>
    <p>This tools marks up a patch file to show the content deltas.


</header>
<main>
    <form method="GET">
        <label>Patch Name:
            <select name="patch" id="patch">
                <?php foreach (glob("../../../sources/*.patch") as $patch) {
                    $base = basename($patch);
                    $selected = $patch === ($selectedPatch ?? '') ? ' selected' : '';
                    echo "<option value='" . ($patch) . "'$selected>"
                        . ($base)
                        . "</option>";
                } ?>
            </select>
        </label>
        <label>Skip Deletions: <input type="checkbox" name="skip_deletions" checked></label>
        <button type="submit">Compare</button>
    </form>
    <p>Hash:<?= isset($_GET['patch']) && hash_file('sha256', $_GET['patch']); ?>

    <pre><code><ol class='diff-output'>
        <?php

        function generateDiff($diffOutput)
        {
            $lines = explode("\n", $diffOutput);
            $output = '';

            foreach ($lines as $line) {
                $escapedLine = (
                    substr($line, 0, 1) .
                    tokenize(
                        (substr($line, 1))
                    )
                );
                // Trim whitespace and remove first char only for hash calculation
                $contentHash = md5(trim(substr($line, 1)));

                if (strpos($line, '+++') === 0 || strpos($line, '---') === 0) {
                    $class = strpos($line, '+++') === 0 ? 'added' : 'deleted';
                    $output .= "<li class='file-change $class'>" . $escapedLine;
                } elseif (strpos($line, '+') === 0 && strpos($line, '+++') !== 0) {
                    $output .= "<li class='added' data-hash='$contentHash'>" . $escapedLine;
                } elseif (strpos($line, '-') === 0 && strpos($line, '---') !== 0) {
                    $output .= "<li class='deleted' data-hash='$contentHash'>" . $escapedLine;
                } else {
                    $output .= "<li>$escapedLine\n";
                }
            }

            return $output;
        }

        // If a patch file is selected in the GET request
        if (isset($_GET['patch'])) {
            // 1. Read the patch file
            $patchContent = file_get_contents($_GET['patch']);
            // 2. Remove hunks with only deletions if checkbox is checked
            $cleanedPatch = isset($_GET['skip_deletions'])
                ? preg_replace(
                    '/^@@ -\d+,\d+ \+0,0 @@\n(?:-.*\n)+/m',
                    '',
                    $patchContent
                )
                : $patchContent;

            // 3. Generate and output the HTML diff
            echo trim(generateDiff($cleanedPatch));
        } ?>
        </ol></code></pre>

    <p class="diff-stats"></p>
    <div class="affected-files">
        <h3>File Changes:</h3>
        <ul id="file-changes"></ul>
    </div>

    <script>
        // mark duplicate hashes in deleted and added lines separately
        // then mark leftovers as moved (hash appears once in deleted and once in added)
        document.addEventListener("DOMContentLoaded", function() {
            // First check repeating lines in added and deleted separately
            function markRepeatingElements(elements, syntaxClass) {
                const hashCount = new Map();
                const elementsByHash = new Map();

                // Count hashes and group elements
                elements.forEach(el => {
                    const hash = el.getAttribute("data-hash");
                    hashCount.set(hash, (hashCount.get(hash) || 0) + 1);
                    if (!elementsByHash.has(hash)) {
                        elementsByHash.set(hash, []);
                    }
                    elementsByHash.get(hash).push(el);
                });

                // Mark repeating elements with appropriate syntax class
                hashCount.forEach((count, hash) => {
                    if (count > 1) {
                        elementsByHash.get(hash).forEach(el => el.classList.add(syntaxClass));
                    }
                });

                // Return elements that weren't marked with syntax class
                return Array.from(elements).filter(el => !el.classList.contains(syntaxClass));
            }

            // Check remaining elements for moves
            function markMovedElements(remainingDeleted, remainingAdded) {
                const deletedHashes = new Map();
                remainingDeleted.forEach(el => {
                    deletedHashes.set(el.getAttribute("data-hash"), el);
                });

                remainingAdded.forEach(el => {
                    const hash = el.getAttribute("data-hash");
                    if (deletedHashes.has(hash)) {
                        el.classList.add("moved");
                        deletedHashes.get(hash).classList.add("moved");
                    }
                });
            }

            // Process deleted and added elements with specific syntax classes
            const remainingDeleted = markRepeatingElements(document.querySelectorAll(".deleted"), "syntax-deleted");
            const remainingAdded = markRepeatingElements(document.querySelectorAll(".added"), "syntax-added");
            // Check for moved lines among remaining elements
            markMovedElements(remainingDeleted, remainingAdded);

            const lines = document.querySelectorAll('.diff-output li');
            lines.forEach((line, index) => {
                line.setAttribute('id', `${index + 1}`);
            });

            // Count and display all statistics
            const fileChanges = document.querySelectorAll('.file-change').length;
            const addedLines = document.querySelectorAll('.added').length;
            const deletedLines = document.querySelectorAll('.deleted').length;

            const statsElement = document.querySelector('.diff-stats');
            statsElement.innerHTML = `${fileChanges} file${fileChanges !== 1 ? 's' : ''} changed, ${addedLines} insertion${addedLines !== 1 ? 's' : ''}(+), ${deletedLines} deletion${deletedLines !== 1 ? 's' : ''}(-)`;

            // Create and display affected files list
            const addedFiles = Array.from(document.querySelectorAll('.file-change.added')).map(el => ({
                path: el.textContent.trim().substring(3).trim(),
                basename: el.textContent.trim().substring(3).trim().split('/').pop()
            }));

            const deletedFiles = Array.from(document.querySelectorAll('.file-change.deleted')).map(el => ({
                path: el.textContent.trim().substring(3).trim(),
                basename: el.textContent.trim().substring(3).trim().split('/').pop()
            }));

            const fileChangesList = document.getElementById('file-changes');

            // Match files with same basename to detect renames
            deletedFiles.forEach(oldFile => {
                const matchingNew = addedFiles.find(newFile => newFile.basename === oldFile.basename);
                const li = document.createElement('li');
                li.className = 'file-change-item';

                if (matchingNew) {
                    // File was renamed
                    if (oldFile.path !== matchingNew.path) {
                        li.classList.add('moved');
                        li.innerHTML = `
                            <span>${oldFile.path}</span>
                            <span class="arrow">→</span>
                            <span>${matchingNew.path}</span>
                        `;
                    }
                    // Remove matched file from addedFiles
                    addedFiles.splice(addedFiles.indexOf(matchingNew), 1);
                } else {
                    // File was deleted
                    li.classList.add('deleted');
                    li.innerHTML = `
                        <span>${oldFile.path}</span>
                        <span class="arrow">→</span>
                        <span>(deleted)</span>
                    `;
                }
                fileChangesList.appendChild(li);
            });

            // Remaining files in addedFiles are new
            addedFiles.forEach(newFile => {
                const li = document.createElement('li');
                li.className = 'file-change-item added';
                li.innerHTML = `
                    <span>(new)</span>
                    <span class="arrow">→</span>
                    <span>${newFile.path}</span>
                `;
                fileChangesList.appendChild(li);
            });
        });
    </script>
    <p><?= gmdate("Y-m-d\TH:i:s\Z") // AKA ISO 8601 w/ Z
        ?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
