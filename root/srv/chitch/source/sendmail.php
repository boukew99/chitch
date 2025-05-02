#!/usr/bin/php
<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

# used for local mail() testing, set in php.ini as sendmail_path

$logFile = dirname(__DIR__, 1) . '/log/messages.html';

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
file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
