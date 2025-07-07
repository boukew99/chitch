<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{read, write, authorize, validate_string, p};

session_start();
?>
<?= Chitch\head(analytics: true); ?>
<title>Inbox</title>
<header>
    <h1>Inbox 📬📨</h1>
    <p>Read messages from the contact form here and reply with your email of choice.</p>
</header>
<main>

    <section id="contact">
        <h2>Contact</h2>
        <p>Leave a message and get a reply if you leave your address.</p>

        <form method="post" action="#received">

            <label>Subject:
                <?php $subject = validate_string(
                    INPUT_POST,
                    "subject",
                    10,
                    50
                ); ?>
                <input name="subject" required value="<?= $subject ?>" minlength="10" maxlength="50" />
            </label>

            <label>Message:
                <textarea name="message" required rows="10" cols="78" minlength="2" maxlength="500">
                <?= $message = validate_string(
                    INPUT_POST,
                    "message",
                    2,
                    500
                ) ?? ""
                ?>
                </textarea>
            </label>

            <label>Name, which to address to:
                <input type="text" name="name"
                    value="<?=
                            $name = validate_string(INPUT_POST, "name", 0, 80) ?? ''
                            ?>"
                    maxlength="500" />
            </label>

            <label>Email address, to reply to:

                <input type="email" name="email"
                    value="<?= $email = htmlspecialchars(
                                filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? ''
                            ) ?>" />
            </label>

            <fieldset>
                <?php $urgency = 'low'; ?>
                <legend>Urgency:</legend>
                <label>
                    <input type="radio" name="urgency" value="low" checked />
                    Low
                </label>
                <label>
                    <input type="radio" name="urgency" value="medium" />
                    Medium
                </label>
                <label>
                    <input type="radio" name="urgency" value="high" />
                    High
            </fieldset>

            <label aria-hidden="true" class="user-cannot-see" style="display:none;">
                Nickname:
                <?php $bot =
                    filter_input(INPUT_POST, "nickname", FILTER_DEFAULT) ===
                    "";
                // bot honeypoy input, which a human won't see
                ?>
                <input type="text" name="nickname" autocomplete="off" tabindex="-1" />
            </label>

            <button>Submit</button>
        </form>


        <?php // Handle POST requests for submitting messages

        if ($_SERVER["REQUEST_METHOD"] === "POST" && $subject && $message && $email && $name && $urgency) {

            write("messages", $article = Chitch\templatepass('mail', compact('subject', 'message', 'email', 'name', 'urgency', 'bot')));
            //notification?

            echo '<ins id="received">Message successfully received 📮. Thank you. </ins>';
        } else {
            echo p('No Valid Input provided.', 'class="error"');
        }
        ?>

    </section>
    <?php if (authorize("administrator")): ?>
        <style>
            .bot::after {
                content: '🤖';
            }
        </style>
        <section id="messages">
            <?= implode('', read("messages")) ?>
        </section>
    <?php endif; ?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
