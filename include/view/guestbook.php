<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

declare(strict_types=1);

require('../chitch.php');

use function Chitch\{read, write};

session_start();

?>
<?= Chitch\head(); ?>
<title>Guestbook</title>
<script defer src="/visit.js"></script>
<style>
    .quote {
        font-style: italic;
        transform: rotate(-2deg);
        margin: 1em 0;
        padding: 1em;
        border-left: 4px solid #ccc;
        background: #f9f9f9;
    }

    .quote cite {
        display: block;
        text-align: right;
        font-style: normal;
        font-weight: bold;
    }
</style>

<header>
    <h1>Guestbook 📖</h1>
    <p>Leave a memorable quote for others to see. Your alias will be displayed alongside your quote.</p>
</header>

<main>

    <section id="guestbook-form">
        <h2>Leave a Quote</h2>

        <form method="post" action='#feedback' id="form">

            <label>Quote:
                <?php
                $quote = trim(
                    substr(
                        filter_input(INPUT_POST, 'quote', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                        0,
                        500
                    )
                );
                ?>
                <textarea name="quote" required rows="5" cols="50" maxlength="500"><?= $quote ?></textarea>
            </label>

            <label>Alias:
                <?php
                $alias = trim(
                    substr(
                        filter_input(INPUT_POST, 'alias', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '',
                        0,
                        50
                    )
                );
                ?>
                <input type="text" name="alias" required value="<?= $alias ?>" maxlength="50">
            </label>

            <label aria-hidden="true" class="user-cannot-see" style="display:none;">Nickname:
                <?php $ishuman = empty($_POST['nickname'] ?? null); ?>
                <input type="text" name="nickname" autocomplete="off" tabindex="-1" />
            </label>
            <label>
                <input type="checkbox" name="public" required>
                I understand this is a public posting.
            </label>
            <button>Submit</button>
        </form>

        <p><ins>
            <?php

            if ($_SESSION['guestbook_posted'] ?? false) {
                $error = 'You can only post once per session.';
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($error)) {
                if ($ishuman && $quote && $alias) {
                    $content = <<<HTML
            <blockquote class="quote">
                <p>{$quote}</p>
                <cite>- {$alias}</cite>
            </blockquote>
            HTML;
                    write('guestbook', $content);
                    $_SESSION['guestbook_posted'] = true;
                    echo '<p id="feedback">Thank you for your contribution! Your quote has been added.</p>';
                } else {
                    echo '<p id="feedback">All fields are required, and you must confirm public posting.</p>';
                }
            }
            ?>
        </ins>

        <?php if (isset($error)): ?>
            <p class="id"><ins><?= $error ?></ins></p>
        <?php endif; ?>
    </section>

    <section id="guestbook-quotes">
        <h2>Quotes</h2>
        <?= read('guestbook') ?>
    </section>
</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
