#!/usr/local/bin/php
<?php
$php = PHP_BINARY ?? "php";
$log = sys_get_temp_dir() . "/server.log";

$command = $argv[1] ?? "localhost";
$workers = 2;

function chitch_localhost(string $php_bin, string $log_file, int $workers)
{
    return chitch_run($php_bin, $log_file, $workers, "localhost", 9000);
}

function chitch_network(string $php_bin, string $log_file, int $workers)
{
    return chitch_run($php_bin, $log_file, $workers, "0.0.0.0", 80);
}

function chitch_log(string $log_file)
{
    if (file_exists($log_file)) {
        return file_get_contents($log_file);
    } else {
        return "Log file not found at " . $log_file . PHP_EOL;
    }
}

function chitch_run(
    string $php_bin,
    string $log_file,
    int $workers,
    string $address,
    int $port,
) {
    /**
    if (!file_exists("database")) {
        mkdir("database");
    }
    if (!file_exists("uploads")) {
        mkdir("uploads");
    }
    */

    $workers_env = (PHP_OS_FAMILY !== 'Windows') ? "PHP_CLI_SERVER_WORKERS=$workers" : "";
    $command = "{$workers_env} $php_bin --server $address:$port --docroot public 2> $log_file";

    echo "Server at http://$address:$port (log: $log_file)" . PHP_EOL;
    passthru($command);
    return "Server Stopped";
}

function buildDir($src, $dest)
{
    chdir(realpath($src));
    if (!is_dir($dest)) {
        mkdir($dest, 0777, true);
    }

    foreach (scandir($src) as $file) {
        if ($file === "." || $file === "..") {
            continue;
        }

        $srcPath = "$src/$file";
        $destPath = "$dest/$file";

        if (is_dir($srcPath)) {
            buildDir($srcPath, $destPath);
        } else {
            if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
                // run php file, capture output
                $out = shell_exec("php " . escapeshellarg($srcPath));
                $destPath = preg_replace('/\.php$/', ".html", $destPath);
                file_put_contents($destPath, $out);
            } else {
                copy($srcPath, $destPath);
            }
        }
    }

    return "✅ Site built at $dest\n";
    #open site in browser with xdg-open and php -S
    #`xdg-open "http://localhost:8000" & php -S localhost:8000 -t "$dest"`;
}

function halstead()
{
    require_once 'app/library/halstead.php';
    require_once 'app/library/chitch.php';

    return print_r(
        Halstead\halsteadProject(
            $files = Chitch\getfiles('app/', '*.php')
        )
        );
}

function chitch_help()
{
    return <<<EOF
    Available commands:
      localhost    - run PHP server at http://localhost:9000
      network      - run PHP server at http://0.0.0.0:80 (needs sudo)
      --- run in background with &
      log          - follow the server log
      help         - show this help

    EOF;
}

?>
<?= match ($command) {
    "localhost" => chitch_localhost($php, $log, $workers),
    "network" => chitch_network($php, $log, $workers),
    "log" => chitch_log($log),
    "static-generate" => buildDir("public", "/tmp/site-build"),
    "halstead" => halstead(),
    default => chitch_help(),
} ?>
<?php
# dnsmasq for local domain names
