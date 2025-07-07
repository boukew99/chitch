<article id="<?=$id?>">
    <h3><?=$title?></h3>
    <p class="date"><?=date("Y-m-d H:i:s", $date)?></p>
    <p class="author">by <?=$author?></p>
    <div class="content">
        <?=$content?>
    </div>
</article>
<?php
/// compact('id', 'title', 'date', 'author', 'content')
