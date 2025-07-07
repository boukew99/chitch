<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{getfiles, tokenize, tree};
?>
<?= Chitch\head(); ?>

<?= Chitch\templatepass('header', ['title' => 'Site Map', 'description' => 'A list of all pages on this site.', 'session' => false, 'toc' => false]) ?>


<main>

<h2>Pages</h2>
<ul>
<?php foreach (glob('*.php') as $file): ?>

<li><a href="<?= htmlspecialchars($file) ?>"><?= htmlspecialchars(basename($file, '.php')) ?></a></li>

<?php endforeach; ?>
</ul>

</main>

<footer>

<?= Chitch\foot() ?>

</footer>
