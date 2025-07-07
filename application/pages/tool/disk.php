<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once('../../library/bootstrap.php');

use function Chitch\{shelled, getfiles};

$toppath = '../../';
?>
<?= Chitch\head(); ?>
<link rel="stylesheet" href="styles/code.css" />
<title>Chitch Disk View</title>
<header>
    <h1>Chitch Disk View</h1>
    <p>See the disk usage of the files.
</header>
<main>
    <section id="tree">
        <h2>Current Directory Tree</h2>
            <?php
            //child items count
            function listDirectory($path)
            {
                $output = '<ul>';
                $items = array_filter(
                    glob($path . '/*'),
                    'is_dir'
                );

                foreach ($items as $item) {
                    $childCount = count(array_filter(glob($item . '/*'), 'is_file'));
                    $dirName = basename($item);
                    $linkIcon = is_link($item) ? ' 🔗' : '';
                    $countBadge = " <small>($childCount files)</small>";

                    $output .= '<li>' . $dirName . $linkIcon . $countBadge;
                    $output .= !is_link($item) ? listDirectory($item) : '';
                }

                return $output . '</ul>';
            }

            echo listDirectory($toppath);
            ?>

    </section>
    <section id="disk">
        <h2>Chitch Disk Analysis</h2>
        <style>
            .heat-table tr[data-heat] {
                transition: background 0.3s;
            }

            .heat-table tr[data-heat="high"] {
                background: #ffebee;
            }

            .heat-table tr[data-heat="medium"] {
                background: #fff3e0;
            }

            .heat-table tr[data-heat="low"] {
                background: #f1f8e9;
            }
        </style>
        <table class="heat-table">
            <thead>
                <tr>
                    <th>File</th>
                    <th>Blocks</th>
                    <th>Usage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // sort on size
                // highlight last modified
                $analyzeBlocks = function ($path) {
                    $blockSize = 4096; // Standard 4KB block size
                    $files = array_filter(getfiles($path), 'is_file');

                    if (empty($files)) {
                        return '<p>No files found.</p>';
                    }

                    $fileDetails = array_map(function ($file) use ($blockSize) {
                        $size = filesize($file);
                        $blocks = ceil($size / $blockSize);
                        return [
                            'name' => basename($file),
                            'blocks' => $blocks,
                            'size' => $size,
                            'allocated' => $blocks * $blockSize,
                            'mtime' => filemtime($file)
                        ];
                    }, $files);

                    usort($fileDetails, fn($a, $b) => $b['size'] - $a['size']);

                    $totals = array_reduce($fileDetails, function ($acc, $file) {
                        return [
                            'size' => $acc['size'] + $file['size'],
                            'blocks' => $acc['blocks'] + $file['blocks'],
                            'wasted' => $acc['wasted'] + ($file['allocated'] - $file['size'])
                        ];
                    }, ['size' => 0, 'blocks' => 0, 'wasted' => 0]);
                ?>

                    <?php foreach ($fileDetails as $file):
                        $usage = $file['size'] / max($file['allocated'], 1);
                        $age = time() - $file['mtime'];
                        $heat = $age < 86400 ? 'high' : ($age < 604800 ? 'medium' : 'low');
                    ?>
                        <tr data-heat="<?= $heat ?>">
                            <td><?= $file['name'] ?></td>
                            <td><?= $file['blocks'] ?></td>
                            <td><meter value="<?= $usage ?>" min="0" max="1" optimum="1" high="0.8" low="0.4"></meter></td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <td colspan="3">
                            Total size: <?= round($totals['size'] / 1024, 2) ?> KiB<br>
                            Total blocks: <?= $totals['blocks'] ?><br>
                            Wasted space: <?= round($totals['wasted'] / 1024, 2) ?> KiB
                        </td>
                    </tr>
        </table>
    <?php
                };

                echo $analyzeBlocks($toppath);
    ?>
    </section>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
