#!bin/php
<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

# used for local mail() testing, set in php.ini as sendmail_path
require('include/chitch.php');

ob_start();
?>
<article id="<?= uniqid() ?>">
    <time>post-time: <?= time() ?></time>
    <pre>
    <?= file_get_contents('php://stdin') ?>
    </pre>
</article>
<?php
$logMessage = ob_get_clean();
Chitch\write( 'messages', $logMessage);
