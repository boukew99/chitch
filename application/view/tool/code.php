<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require('../../library/chitch.php');

use function Chitch\{getfiles, tokenize};

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="code.css" />
<title>Source Code</title>
<style>
    .T_INLINE_HTML {
        display: none;
    }
</style>
<header>
    <h1>Source Code</h1>
    <p>Read the entire server-side source code of Chitch.

    <form id="filterForm" class="controls">
        <label>
            <input type="checkbox" id="showHtml" name="showHtml">
            Show HTML Code (which sits between the PHP code)
        </label>
    </form>
    <script>
        document.getElementById('showHtml').addEventListener('change', function() {
            const htmlTokens = document.querySelectorAll('span.T_INLINE_HTML');
            htmlTokens.forEach(token => {
                token.style.display = this.checked ? 'inline' : 'none';
            });
        });
    </script>

</header>
<main id="main">
    <?php
    $files = getfiles(dirname(__DIR__, 2), '*.php');
    foreach ($files as $path) {
        ob_start();
    ?>
        <details id='<?= $id = pathinfo($path, PATHINFO_FILENAME) ?>'>
            <summary><?= basename(dirname($path)) ?>/<?= $id ?></summary>
            <pre><code class='php'><?= tokenize(file_get_contents($path)) ?></code></pre>
            <label>Comments: <textarea name="<?= $id ?>" ></textarea></label>
        </details>
    <?php
        echo ob_get_clean();
    }
    ?>

    <details id="tokenStats">
        <summary>Token Statistics</summary>
        <dl id="statsContent"></dl>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const stats = {};
                document.querySelectorAll('span[class^="T_"]').forEach(span => {
                    const tokenClass = span.className;
                    const length = span.textContent.length;
                    stats[tokenClass] = (stats[tokenClass] || 0) + length;
                });

                const statsHtml = Object.entries(stats)
                    .sort(([, a], [, b]) => b - a)
                    .map(([token, size]) =>
                        `<dt>${token}</dt><dd>${size} characters</dd>`
                    ).join('');

                document.getElementById('statsContent').innerHTML = statsHtml;
            });
        </script>
    </details>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
