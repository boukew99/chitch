<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require('../../chitch.php');

use function Chitch\{getfiles, tree};

?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="code.css" />
<title>Assets Views</title>

<header>
    <h1>Assets Views</h1>
    <p>View and manage assets stored on the server. This includes adding new assets, caching files, and viewing existing assets.
</header>
<main>

    <ol>
        <?= tree(
            'li',
            function($asset) {
                $path = $asset;
                $id = basename($asset);
                $ext = strtolower(pathinfo($asset, PATHINFO_EXTENSION));

                return match ($ext) {
                    'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg' =>
                    "<img src='{$path}' alt='{$asset}' loading='lazy' />",
                    'mp4', 'webm' =>
                    "<video src='{$path}' controls loading='lazy' width='640' height='360'></video>",
                    'mp3', 'wav', 'ogg' =>
                    "<audio src='{$path}' controls loading='lazy'></audio>",
                    default => "Asset: {$path}"
                };
            },
            array_map(
                fn($path) => '/assets/' . basename($path),
                getfiles('../assets/')
            )
        )
        ?>
    </ol>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
