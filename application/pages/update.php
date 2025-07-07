<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once("../../library/bootstrap.php");

use function Chitch\{read, write, getfiles, monitor};

session_start();
$authorized = (bool) $_SESSION['authorized'] ?? false;
$permitted = (bool) in_array('administrator', $_SESSION['groups'] ?? []);
$author = $_SESSION['username'] ?? 'anonymous';

?>
<?= Chitch\head(analytics: true); ?>
<title>Chitch Articles</title>

<style>
    article[lang="nl"]::after {
        content: "🇳🇱";
    }
</style>

<header>
    <h1>Blog Update</h1>
    <p>Updates on Chitch, its development, and related topics in sustainability, accessibility and web.</p>

<?= Chitch\authstate() ?>
</header>

<main id="main">
    <section id="write">
        <h2>Add Article</h2>

        <form method="post">
            <p><label>
                    <input type='checkbox' disabled <?= $permitted ? 'checked' : '' ?>>
                    Authorized to post an article.
                </label>
                <label>Author/Signature:
                    <input value="<?= $author ?>" disabled>
                </label>

            <p><label>Title: <input type="text" name="title" required></label>
            <p><label>Description: <textarea name="description" rows=5></textarea></label>

            <p><label>Language:
                    <input name="lang" type="text"
                        pattern="^[a-z]{2}-[A-Z]{2}$"
                        title="Please use format: xx-XX (e.g., en-US, nl-NL)"
                        list="languages"
                        required>
                </label>
                <datalist id="languages">
                    <option value="en-US">English (US)</option>
                    <option value="nl-NL">Dutch</option>
                    <option value="en-GB">English (UK)</option>
                    <option value="de-DE">German</option>
                </datalist>

            <p><label>Class(es) (seperated by spaces): <input list="classes" name="class"></label>
            <p><label>Publish Date: <input type="date" name="publish-date"></label>
                <label>Content: <textarea name="content"></textarea></label>
            <p><label>Series: <input name="series"></label></p>

            <p><label>Image:
                    <select name="image">
<?= implode('', array_map(fn($dir) => "<option value='$dir'>$dir</option>", getfiles('assets/*'))) ?>
                    </select>
                </label>
            <p><label>Alt Text: <input type="text" name="alt-text"></label></p>
            <p><label>Second Content after image: <textarea name="content"></textarea></label>
                <datalist>
                    <?php
                    #$series = get_xml_series
                    ?>
                </datalist>
            <p><label>Resource Link: <input type="url" name="resource" placeholder="https://"></label></p>
            <input type="submit" name="add" value="Add">
        </form>

    </section>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $permitted ?? false) {
    $filtered_input = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'lang' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'class' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'publish-date' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'series' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'resource' => FILTER_VALIDATE_URL,
        'image' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'alt-text' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    ]);

    if ($filtered_input && $filtered_input['title'] && $filtered_input['content']) {
        $article_id = strtolower(str_replace(' ', '-', $filtered_input['title']));
        ob_start();
        include('../template/article.php');
        $new_article = ob_get_clean();

        write('news', $new_article);
        echo '<p>Posted:</p>';
        echo $new_article;
    } else {
        echo '<p>Error: Missing required fields or invalid input.</p>';
    }
}

?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
