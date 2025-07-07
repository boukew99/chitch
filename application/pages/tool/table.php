<?php
// Grug PHP tokenizer-table maker

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['phpfile']['tmp_name'])) {
    $code = file_get_contents($_FILES['phpfile']['tmp_name']);
    $tokens = token_get_all($code);
} else {
    $code = '';
    $tokens = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PHP Tokenizer Table - Grug Version</title>
<style>
  table { border-collapse: collapse; width: 100%; }
  th, td { border: 1px solid #aaa; padding: 4px 8px; text-align: left; font-family: monospace; }
  th { background: #ddd; }
  tr:nth-child(even) { background: #f9f9f9; }
  .token-name { font-weight: bold; color: #0a0; }
  .token-text { color: #00a; }
</style>
</head>
<body>
<h1>PHP Tokenizer Table - Grug Cave Edition 🪨</h1>

<form method="post" enctype="multipart/form-data">
    <label for="phpfile">Upload PHP file to tokenize:</label><br>
    <input type="file" name="phpfile" id="phpfile" accept=".php" required>
    <button type="submit">Tokenize!</button>
</form>

<?php if ($code !== ''): ?>
<h2>Tokenized Output</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Token Name / ID</th>
            <th>Token Text</th>
            <th>Is String?</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($tokens as $i => $token): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <?php if (is_array($token)): ?>
                <td class="token-name"><?= token_name($token[0]) ?> (<?= $token[0] ?>)</td>
                <td class="token-text"><?= htmlspecialchars($token[1]) ?></td>
                <td>Yes</td>
            <?php else: ?>
                <td class="token-name">Character (<?= ord($token) ?>)</td>
                <td class="token-text"><?= htmlspecialchars($token) ?></td>
                <td>No</td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

</body>
</html>
