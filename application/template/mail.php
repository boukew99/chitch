<details id=<?= uniqid() ?> class=<?= empty($nickname) ? "human" : "bot" ?> >
    <summary class="<?= $urgency ?>"><?= $subject ?></summary>
    <article>
        <h3><?= $subject ?></h3>
        <time>post-time: <?= time() ?></time>
        <pre><?= $message ?></pre>
        <address>
            <a href='mailto:<?= $email ?>'><?= $name ?></a>
        </address>
    </article>
</details>
