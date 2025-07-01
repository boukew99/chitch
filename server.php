Opening server at http://<?= $address = 'localhost:9000' ?>/tool/

<?php

shell_exec(
    'PHP_CLI_SERVER_WORKERS=4 ' . PHP_BINARY . ' --php-ini "application/setting/php.ini" --server ' . $address . ' --docroot application/pages > "temp/server.log" 2>&1 &'
);

sleep(1); // Give the server some time to start
echo file_get_contents('temp/server.log'); // Display the server log

