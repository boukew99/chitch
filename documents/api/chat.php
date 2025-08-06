<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');
session_start();
?>
<?=\Chitch\head()?>
<script defer src="poll.js"></script>
<?=Chitch\header('Chat', 'A chat functionality on this site.');?>
<main>

<figure id="chat"></figure>
<div id="next-interval"></div>

<form id="form">
  <input value="<?= $_SESSION['username'] ?? 'anon' ?>" disabled>
  <input name="message" placeholder="Speak here..." required>
  <button>Send</button>
</form>

</main>
