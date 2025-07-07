<title><?= $title ?></title>
<header>
    <h1><?= $title ?></h1>
    <p><?= $description ?></p>
    <?= $session ? Chitch\authstate() : '' ?>
    <?php if ($toc) { ?>
    <details>
        <summary>Table of Contents</summary>
        <nav id="toc"></nav>
    </details>
    <?php } ?>
</header>
<?php
// compact('title', 'description', 'session', 'toc')
// This file is used to render the header of a page, including the title, description,
// authentication state, and optionally a table of contents.
// It is included in various pages to maintain a consistent header structure.
