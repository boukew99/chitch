<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{read, write, getfiles, monitor};

?>
<?= Chitch\head(analytics: true); ?>
<title>Chitch Articles</title>
<style>
    article[lang="nl"]::after {
        content: "🇳🇱";
    }
</style>
<header>
    <h1>Blog</h1>
    <p>Updates on Chitch, its development, and related topics in sustainability, accessibility and web.</p>
    <details>
        <summary>Table of Contents</summary>
        <nav id="toc"></nav>
    </details>
</header>
<main id="main">
    <section id="articles">
        <style>
            #parent {
                display: flex;
                flex-direction: column-reverse;
            }
        </style>

        <?php
        $cutoff = 0; // Set to 0 to show all articles, or set to a positive integer to limit the number of articles shown
        $entries = array_slice(read('news'), -$cutoff);
        $entries = array_reverse($entries); // Reverse the order of articles
        echo implode('', $entries);
        ?>
        </script>
    </section>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
