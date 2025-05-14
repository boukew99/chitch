#!bin/php

<?= 'port:' . $port = 9000;?>
\nOpen Development Server at (http://localhost:<?=$port?>) started
**Stop server with 'Ctrl + C'**\n

<?=
# https://www.php.net/manual/en/features.commandline.webserver.php
`PHP_CLI_SERVER_WORKERS=4 bin/php \
	--php-ini include/php.ini \
	--server localhost:$port \
	--docroot include/view 2> build/php.log
`;
?>
