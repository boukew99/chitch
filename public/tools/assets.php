<?php

// © 2025 Chitch Contributors, Licensed under the EUPL
chdir(dirname($_SERVER['DOCUMENT_ROOT']));
require_once('library/bootstrap.php');

function read_resource_log($file = 'resource.log') {
    $lines = @file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!$lines) return [];
    $assets = [];
    foreach ($lines as $line) {
        $parts = preg_split('/\s+/', $line, 2);
        if (isset($parts[0], $parts[1])) {
            $assets[] = ['url' => $parts[0], 'hash' => $parts[1]];
        }
    }
    return $assets;
}

function asset_item($url, $hash) {
    $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
    $hash_html = "<small style=\"color:#888\">$hash</small>";
    return match ($ext) {
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg' =>
            "<img src=\"$url\" alt=\"$url\" loading=\"lazy\" /><br>$hash_html",
        'mp4', 'webm' =>
            "<video src=\"$url\" controls loading=\"lazy\" width=\"320\" height=\"180\"></video><br>$hash_html",
        'mp3', 'wav', 'ogg' =>
            "<audio src=\"$url\" controls loading=\"lazy\"></audio><br>$hash_html",
        default => "Asset: <a href=\"$url\">$url</a><br>$hash_html"
    };
}

$assets = read_resource_log();

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="/styles/code.css" />
<title>Assets Views</title>

<header>
    <h1>Assets Views</h1>
    <p>View and manage assets referenced in <code>resource.log</code>.</p>
</header>
<main>
    <?php foreach ($assets as $asset): ?>
        <figure>
            <figcaption><code><?= htmlspecialchars($asset['url']) ?></code></figcaption>
            <?= asset_item($asset['url'], $asset['hash']) ?>
        </figure>
    <?php endforeach; ?>
</main>
<footer>
    <?= Chitch\foot()?>
</footer>
