#!/usr/bin/env php
See the server log:
<?php
$log_file = sys_get_temp_dir() . "/access.log";

(file_exists($log_file)) ? file_get_contents($log_file) : "Log file not found at " . $log_file . PHP_EOL;
