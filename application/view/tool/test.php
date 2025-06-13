<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require("../../library/chitch.php");

use function Chitch\{read, write, authorize, validate_string, p};

?>
<?= Chitch\head(); ?>
<title>Form Test</title>
<script defer src="/api/visit.js"></script>

<header>
    <h1>Form Test Suite 🧪</h1>
    <p>Test various form input types and validation scenarios. Just follow the anchor or submit a form and check the visuals and database/ to see if it operates as expected.</p>
</header>
<main>
<h2>Apache Server Shortcut</h2>
<a href="/?edit=public/index.php">Edit Shortcut</a>

<h3>Bot Flagging</h3>
    <form method="post" action="/contact.php">
        <label>
            Bot Detector:
            <input name="nickname" value="nickname-test">
        </label>
        <input name="subject" value="This is a Subject">
        <input name="message" value="This is a message">
        <input name="name" value="Robot">
        <input name="email" value="a@email.com">
        <button>Submit</button>
    </form>

</main>

<footer>
    <?= Chitch\foot(); ?>
</footer>
