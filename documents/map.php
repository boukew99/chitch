<?php
// © 2025 Chitch Contributors, Licensed under the EUPL

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{getfiles, tokenize, tree};
?>
<?= Chitch\head(); ?>

<?= Chitch\header('Site Map', 'A list of all pages on this site.', false) ?>


<main>


<h2>Pages</h2>
<ul>
<?php foreach (glob('*.php') as $file): ?>

<li><a href="<?= htmlspecialchars($file) ?>"><?= htmlspecialchars(basename($file, '.php')) ?></a></li>

<?php endforeach; ?>
</ul>

<?php if (php_sapi_name() === "cli-server"): ?>
<ul>
<?php foreach (glob('tools/*.php') as $file): ?>

<li><a href="<?= htmlspecialchars($file) ?>"><?= htmlspecialchars(basename($file, '.php')) ?></a></li>

<?php endforeach; ?>
</ul>
<?php endif; ?>

</main>

<footer>

<?= Chitch\foot() ?>

</footer>
