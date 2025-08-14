<?php
// © 2025 Chitch Contributors, Licensed under the EUPL

chdir(dirname($_SERVER['DOCUMENT_ROOT']));
require_once('library/bootstrap.php');

use function Chitch\{shelled, getfiles};

$toppath = './';
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

    </section>

<form method="get">
    <label>Block size (bytes):</label>
    <input type="number" name="bsize" value="<?= $blockSize ?>" min="512" step="512">
    <button>Refresh</button>
</form>


<style>
    .heat-table meter {
        width: 100%;
    }
</style>

<table class="heat-table">
    <thead>
        <tr>
            <th>File</th>
            <th>Content Size</th>
            <th>Disk Blocks</th>
            <th>Opportunity Space</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $blockSize = isset($_GET['bsize']) ? (int)$_GET['bsize'] : 4096;
        $files = array_filter(getfiles($toppath), 'is_file');

        if (empty($files)) {
            echo '<tr><td colspan="4">No files found.</td></tr>';
        } else {
            $fileDetails = array_map(function ($file) use ($blockSize) {
                $size = filesize($file);
                $blocks = ceil($size / $blockSize);
                $allocated = $blocks * $blockSize;
                $opportunity = $allocated - $size;
                return [
                    'name' => basename($file),
                    'size' => $size,
                    'blocks' => $blocks,
                    'allocated' => $allocated,
                    'opportunity' => $opportunity,
                ];
            }, $files);

            // calculate totals
            $totalAllocated = array_sum(array_column($fileDetails, 'allocated'));
            $totalSize = array_sum(array_column($fileDetails, 'size'));
            $totalOpportunity = array_sum(array_column($fileDetails, 'opportunity'));

            foreach ($fileDetails as $file) {
                $sizeRatio = $file['size'] / $totalSize;
                $oppRatio = $file['opportunity'] / $blockSize; // show leftover relative to 1 block
        ?>
                <tr>
                    <td><?= htmlspecialchars($file['name']) ?></td>

                    <!-- content contribution to total project size -->
                    <td>
                        <?= round($file['size'] / 1024, 1) ?> KiB
                        <meter value="<?= $sizeRatio ?>" min="0" max="1"></meter>
                    </td>

                    <!-- how many blocks this file uses -->
                    <td>
                        <?= $file['blocks'] ?> × <?= $blockSize ?>B
                        <meter value="<?= $file['blocks'] / $totalAllocated ?>" min="0" max="1" high="0.5" low="0.2"></meter>
                    </td>

                    <!-- leftover space in last block (can be reused) -->
                    <td>
                        <?= $file['opportunity'] ?> B
                        <meter value="<?= $oppRatio ?>" min="0" max="1" optimum="0" low="0.2" high="0.8"></meter>
                    </td>
                </tr>
        <?php
            }

            // summary row
            echo "<tr><td colspan='4'><strong>Total project size:</strong> " .
                round($totalSize / 1024, 2) . " KiB, using " .
                round($totalAllocated / 1024, 2) . " KiB of disk, with " .
                round($totalOpportunity / 1024, 2) . " KiB of 'opportunity space' 🍃</td></tr>";
        }
        ?>
    </tbody>
</table>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
