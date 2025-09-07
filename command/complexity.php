#!/usr/bin/env php
<?php

require_once 'library/halstead.php';
require_once 'library/chitch.php';

?>
<?= print_r(
    Halstead\halsteadProject(
        $files = Chitch\getfiles('./', '*.php')
    )
)
?>
