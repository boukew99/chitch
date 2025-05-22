<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require('../../chitch.php');

use function Chitch\log_path;

function escape_html(string $content): string
{
  return htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

$log_dir = log_path();
$files = array_filter(scandir($log_dir), fn($file) => is_file("$log_dir/$file") && pathinfo($file, PATHINFO_EXTENSION) === 'html');

$selected_file = $_POST['file'] ?? null;
$file_content = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $selected_file) {
  $file_path = "$log_dir/$selected_file";

  if (isset($_POST['save']) && isset($_POST['content'])) {
    // Save the updated content back to the file
    file_put_contents($file_path, $_POST['content']);
    $message = "File '$selected_file' saved successfully.";
  }

  // Read the file content
  $file_content = file_exists($file_path) ? file_get_contents($file_path) : '';
}
?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="code.css" />
<title>Log Editor</title>
<main>
  <h1>Log Editor</h1>

  <?php if ($message): ?>
    <p><?= escape_html($message) ?></p>
  <?php endif; ?>

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

  <?php if ($selected_file): ?>
    <form method="post">
      <input type="hidden" name="file" value="<?= escape_html($selected_file) ?>">
      <label for="content">Edit content of <?= escape_html($selected_file) ?>:</label>
      <textarea name="content" id="content" rows="20" cols="80"><?= escape_html($file_content) ?></textarea>
      <br>
      <button type="submit" name="save">Save</button>
    </form>
  <?php endif; ?>
</main>

<footer>
  <?= Chitch\foot() ?>
</footer>
