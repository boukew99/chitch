<article id="<?= $article ?>" lang="<?= $lang ?>" class="<?= $class ?>">
    <header>
        <h3><?= $title ?></h3>
        <p>@<?= $author ?> at <time datetime="<?= $date ?>"><?= $date ?></time></p>
        <figure>
            <img src="<?= $image ?>" alt="<?= $title ?>" />
            <figcaption><?= $caption ?></figcaption>
        </figure>
    </header>
    <?= $$content ?>
    <footer>
        <nav><?= $references ?></nav>
        <p>Share this article: <code><?= $permalink ?></code></p>
    </footer>
</article>
