<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require('../../library/chitch.php');

use function Chitch\{read, tree, wrap};
echo Chitch\head();
?>
<header>
    <h1>Chitch Users</h1>
    <p>A list of all Chitch users.
</header>
<main>
    <?= wrap('ul',
            tree('li', fn($x) => htmlspecialchars($x),
                array_keys(unserialize(read('members')[0])) )
        );?>

</main>
<footer>
    <?= Chitch\foot() ?>
</footer>
