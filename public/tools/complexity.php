<?php
chdir(dirname($_SERVER['DOCUMENT_ROOT']));
require_once('library/bootstrap.php');
require_once('library/halstead.php');

function last_dir(string $path): string {
    $path = ltrim($path, './');              // remove leading . and /
    $path = rtrim($path, '/');               // remove trailing /
    $dir  = basename(dirname($path));
    $file = basename($path);

    return ($dir === '.' ? '' : "$dir/") . $file;
}


?>
<?= Chitch\head() ?>
<title>Cognitive Code Complexity Measure</title>

<header>

<h1>Cognitive Code Complexity Measure</h1>
<p>Measure the complexity of files and functions of Chitch with <a href="https://en.wikipedia.org/wiki/Halstead_complexity_measures">Halstead Measure</a>.

</header>

<main>

<h2>Halstead Table</h2>
<table>

<thead>
<tr>

<?php
$files = Chitch\getfiles('.', '*.php');

// Get columns from the first file's metrics
$firstMetrics = Halstead\halstead_metrics(file_get_contents(reset($files)));
$columns = array_keys($firstMetrics);

echo "<th>file</th>" . Chitch\tree("th", fn($col) => htmlspecialchars($col), $columns) . "</tr>";
?>

</tr>
</thead>

<tbody>
<?php
echo Chitch\tree("tr", function($file) use ($columns) {
    $metrics = Halstead\halstead_metrics(file_get_contents($file));
    $cells = array_merge(
        ["<td>" . htmlspecialchars(last_dir($file)) . "</td>"],
        array_map(
            fn($col) => "<td>" . htmlspecialchars(round($metrics[$col])) . "</td>",
            $columns
        )
    );
    return implode("", $cells);
}, $files);
?>
</tbody>

</table>

<pre>
<?= print_r(
    Halstead\halsteadProject(
        $files = Chitch\getfiles('.', '*.php')
    )
    );
?>
</pre>

</main>

<footer>

<?= Chitch\foot() ?>

</footer>
