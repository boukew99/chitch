<?php

require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/halstead.php');

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
<tr>

<?php
$files = Chitch\getfiles('../../', '*.php');

// Get columns from the first file's metrics
$firstMetrics = halstead_metrics(file_get_contents(reset($files)));
$columns = array_keys($firstMetrics);

echo "<th>file</th>" . Chitch\tree("th", fn($col) => htmlspecialchars($col), $columns) . "</tr>";
?>

</tr>

<?php
echo Chitch\tree("tr", function($file) use ($columns) {
    $metrics = halstead_metrics(file_get_contents($file));
    $cells = array_merge(
        ["<td>" . htmlspecialchars($file) . "</td>"],
        array_map(
            fn($col) => "<td>" . htmlspecialchars($metrics[$col]) . "</td>",
            $columns
        )
    );
    return implode("", $cells);
}, $files);
?>
</table>
<pre>
<?= print_r(
    halsteadProject(
        $files = Chitch\getfiles('../../', '*.php')
    )
    );
?>
</pre>

</main>

<footer>

<?= Chitch\foot() ?>

</footer>
