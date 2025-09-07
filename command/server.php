#!/usr/bin/env php
<?php
$php = PHP_BINARY ?? "php";
$log = implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), "access.log"]);
$workers = 2;

// Default values
$default_docroot = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__, 1), 'public']);
$default_port = 9000;
$default_address = "localhost";

// Get args or use defaults
$docroot = $argv[1] ?? $default_docroot;
$port = $argv[2] ?? $default_port;
$address = $argv[3] ?? $default_address;

$workers_env = PHP_OS_FAMILY !== "Windows" ? "PHP_CLI_SERVER_WORKERS=$workers" : "";
$command = "{$workers_env} $php --server $address:$port --docroot $docroot --php-ini ./ 2> $log";
?>
<?= "Server at http://$address:$port (docroot: $docroot, log: $log)" . PHP_EOL ?>
<?= passthru($command) ?>

  Error Log:
<?= file_get_contents($log) ?>
<?php
# run in background with &
# run on network with `sudo command/server.php public 80 0.0.0.0`
# dnsmasq for local domain names
# https://www.php.net/manual/en/features.commandline.options.php
