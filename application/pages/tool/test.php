<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once("../../library/bootstrap.php");

use function Chitch\{read, write, authorize, validate_string, p};

?>
<?= Chitch\head(analytics: true); ?>
<title>Form Test</title>

<header>
    <h1>Form Test Suite 🧪</h1>
    <p>Test various form input types and validation scenarios. Just follow the anchor or submit a form and check the visuals and database/ to see if it operates as expected.</p>
</header>
<main>
<h2>Apache Server Shortcut</h2>
<a href="/?edit=public/index.php">Edit Shortcut</a>

<figure>
    <figcaption>Bot Flagging</figcaption>
    <form method="post" action="/contact.php">
        <label>
            Bot Detector:
            <input name="nickname" value="nickname-test">
        </label>
        <label>Subject:
        <input name="subject" value="This is a Subject">
        </label>
        <label>Message:
        <input name="message" value="This is a message">
        </label>
        <label>Name:
        <input name="name" value="Robot">
        </label>
        <label>Email:
        <input name="email" value="a@email.com">
        </label>
        <button>Submit</button>
    </form>

</figure>
    <figure>
        <figcaption>Guestbook Dummy</figcaption>
        <form method="post" action="/guestbook.php">
            <label>
                Name:
                <input name="alias" value="Dummy User" readonly>
            </label>
            <label>
                Quote:
                <input name="quote" value="This is a dummy quote" readonly>
            </label>
            <button>Submit</button>
        </form>
        <p>Trigger twice to see if <em>posting once per session</em> works.
    </figure>
</main>

<footer>
    <?= Chitch\foot(); ?>
</footer>
