<?php
// © 2025 Chitch Contributors, Licensed under the EUPL

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{read, write, getfiles, monitor, a};

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
<article>

<?php
$article = Chitch\validate_string(INPUT_GET, 'article', 1, 200)

?>

</article>

</main>

<aside id="articles">
<h3>Articles</h3>
<ul>
<?php
$articles = '../../assets/articles/';
$files = glob("$articles/*.md");
$link = fn($x) => a(htmlspecialchars($x), '?article=' . basename($x, '.html'));
echo Chitch\tree('li', $link, $files);

?>
</ul>

</aside>

<footer>
    <?= Chitch\foot() ?>
</footer>
