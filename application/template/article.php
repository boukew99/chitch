<article>
    <header>
        <h3><?= $title ?></h3>
        <p>@<?= $author ?> at <?= $date ?></p>
        <figure>
            <img src="<?= $image ?>" alt="<?= $title ?>" />
            <figcaption><?= $caption ?></figcaption>
        </figure>
    </header>
    <?= $$content ?>
    <nav><?= $references ?></nav>
</article>
