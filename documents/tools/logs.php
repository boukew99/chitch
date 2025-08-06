<?php
// © 2025 Chitch Contributors, Licensed under the EUPL

require_once('../../library/bootstrap.php');

use function Chitch\{log_path, read, getfiles};

  $message = '';

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="styles/code.css" />
<title>Log Editor</title>
<main>
  <h1>Log Editor</h1>

  <?php if ($message): ?>
    <p><?= escape_html($message) ?></p>
  <?php endif; ?>

  <?php
  function escape_html(string $content): string
  {
    return htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  }

  $log_dir = log_path();
  $files = array_map(
    fn($path) => basename($path, '.html'),
    getfiles(log_path(), '*.html')
  );

  $selected_file = $_POST['file'] ?? null;
  $file_content = '';
  ?>

  <form method="post">
    <label for="file">Select a file:</label>
    <select name="file" id="file">
      <option value="">-- Choose a file --</option>
      <?php foreach ($files as $file): ?>
        <option value="<?= escape_html($file) ?>" <?= $file === $selected_file ? 'selected' : '' ?>>
          <?= escape_html($file) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="submit" name="select">Select File</button>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && $selected_file) {
    $file_path = "$selected_file";
    if (isset($_POST['save']) && isset($_POST['content']) && is_array($_POST['content'])) {
      // Join the edited chunks and save back to the file
      $joined = implode("<!-- COMMIT -->", $_POST['content']);
      file_put_contents($file_path, $joined);
      $message = "File '$selected_file' saved successfully.";
    }

    chitch_debug($file_path); // Debugging output
    // Read the file content as segments
    $segments = Chitch\read($file_path);
    chitch_debug($segments); // Debugging output
  } else {
    $segments = [];
  }
  ?>

  <?php if ($selected_file): ?>
    <form method="post">
      <input type="hidden" name="file" value="<?= escape_html($selected_file) ?>">

      <label>Edit segments of <?= escape_html($selected_file) ?>:</label>
      <?php foreach ($segments as $i => $segment): ?>
        <?php $rows = min(20, max(3, ceil(strlen($segment) / 80))); ?>
        <fieldset style="margin-bottom:1em;">
          <legend>Segment <?= $i + 1 ?></legend>
          <textarea name="content[<?= $i ?>]" rows="<?= $rows ?>" cols="80"><?= escape_html($segment) ?></textarea>
        </fieldset>
      <?php endforeach; ?>
      <button type="submit" name="save">Save</button>
    </form>
  <?php endif; ?>
</main>

<footer>
  <?= Chitch\foot() ?>
</footer>
