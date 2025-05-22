<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require("../chitch.php");

use function Chitch\{read, write, getfiles, monitor};

if (isset($_GET['write'])) {
    session_start();
    $authorized = (bool) $_SESSION['authorized'] ?? false;
    $permitted = (bool) in_array('administrator', $_SESSION['groups'] ?? []);
    $author = $_SESSION['username'] ?? 'anonymous';
}

?>
<?= Chitch\head(); ?>
<title>Chitch Articles</title>
<script defer src="/visit.js"></script>
<style>
    article[lang="nl"]::after {
        content: "🇳🇱";
    }
</style>
<header>
    <h1>Articles</h1>
    <p>Articles about technology,
        software,
        accessibility,
        usability and interaction between human and machine. Mostly written from the perspective of usability and designing for humans.
    <details>
        <summary>Table of Contents</summary>
        <nav id="toc"></nav>
    </details>
</header>
<main id="main">
    <section id="article-filter">
        <h2>Filter Articles</h2>
        <p>Filter articles by series or view them in chronological order.</p>
        <form id="filter-form">
            <div id="filter-options"></div><label><input type="checkbox" id="chronological" name="chronological">Chronological Order </label>
        </form>
    </section>
    <section id="articles">
        <style>
            #parent {
                display: flex;
                flex-direction: column-reverse;
            }
        </style>

        <?php
        echo read('news');
        ?><script>
            // Reverse articles on load

            document.addEventListener('DOMContentLoaded', () => {
                const articles = document.getElementById('articles');
                const articleElements = Array.from(articles.getElementsByTagName('article'));
                articleElements.reverse().forEach(article => articles.appendChild(article));
            });
        </script>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const articlesSection = document.getElementById('articles');
            const filterOptionsDiv = document.getElementById('filter-options');
            const chronologicalCheckbox = document.getElementById('chronological');
            const filterForm = document.getElementById('filter-form');
            const articleElements = Array.from(articlesSection.querySelectorAll('article'));
            const articleClasses = new Set();

            articleElements.forEach(article => {
                article.classList.forEach(className => articleClasses.add(className));
            });

            articleClasses.forEach(className => {
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = className;
                checkbox.name = 'series';
                checkbox.value = className;
                checkbox.checked = true;

                const label = document.createElement('label');
                label.htmlFor = className;
                label.textContent = className;

                filterOptionsDiv.appendChild(checkbox);
                filterOptionsDiv.appendChild(label);
            });

            const applyFilters = () => {
                const selectedClasses = Array.from(filterForm.querySelectorAll('input[name="series"]:checked')).map(checkbox => checkbox.value);
                const showAll = selectedClasses.length === articleClasses.size;

                articleElements.forEach(article => {
                    const hasSelectedClass = showAll || article.classList.hasAny(...selectedClasses);
                    article.style.display = hasSelectedClass ? 'block' : 'none';
                });
            }

            ;
            filterForm.addEventListener('change', applyFilters);

            chronologicalCheckbox.addEventListener('change', () => {
                const isChronological = chronologicalCheckbox.checked;
                const orderedArticles = isChronological ? articleElements : articleElements.slice().reverse();
                articlesSection.innerHTML = '';
                orderedArticles.forEach(article => articlesSection.appendChild(article));
            });
            applyFilters();

        });
    </script>

    <?php
    if ($permitted ?? false):
    ?>

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
                            <?=
                            implode('', array_map(
                                fn($dir) => "<option value='$dir'>$dir</option>",
                                getfiles('assets/*')
                            ))
                            ?>
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
                $new_article = <<<HTML
            <article id="{$article_id}" lang="{$filtered_input['lang']}" class="{$filtered_input['class']}">
                <header>
                    <h2>
                        <a href="#{$article_id}">{$filtered_input['title']}</a>
                    </h2>
                    <img src="../assets/{$filtered_input['image']}" alt="{$filtered_input['alt-text']}">
                    <p>{$filtered_input['description']}</p>
                    <time datetime="{$filtered_input['publish-date']}">Published on: {$filtered_input['publish-date']}</time>
                </header>
                <section>
                    <p>{$filtered_input['content']}</p>
                </section>
                <footer>
                    <p>Series: {$filtered_input['series']}</p>
                    <p><a href="{$filtered_input['resource']}">Resource Link</a></p>
                </footer>
            </article>
            HTML;

                write('news', $new_article);
                echo '<p>Posted:</p>';
                echo $new_article;
            } else {
                echo '<p>Error: Missing required fields or invalid input.</p>';
            }
        }

    endif;
    ?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>
