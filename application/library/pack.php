<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL
header('Content-Type: text/plain; charset=utf-8');
chdir('../../../'); # Sets project root as dir

function build(string $output, callable $recipe, string ...$dependencies): string {
    if (!needsBuild($output, $dependencies)) {
        return $output;
    }

    $ok = $recipe($output, ...$dependencies);

    return ($ok && file_exists($output)) ? $output : '';
}

function needsBuild(string $output, array $dependencies): bool {
    if (!file_exists($output)) return true;

    $outputTime = filemtime($output);
    foreach ($dependencies as $dep) {
        if (!file_exists($dep)) return true;
        if (is_dir($dep)) {
            if (!allOlderRecursive($outputTime, $dep)) return true;
        } else {
            if (filemtime($dep) > $outputTime) return true;
        }
    }
    return false;
}

function allOlderRecursive(int $referenceTime, string $dir): bool {
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($items as $file) {
        if ($file->getMTime() > $referenceTime) return false;
    }

    return true;
}



# $zip = build('bin/chitch.zip', fn($out, $in) => `(zip --recurse-paths --quiet "$out" $in)` ,'include');
# $project = build('bin/chitch.tar.zst' , fn($out, $in) => `tar -caf  $out $in`, 'include license.txt', 'bin');

# Block size of average file size
# $image = build('bin/chitch.sqsh', fn($out, $in) => `mksquashfs $in $out -comp zstd -b 256K`, 'include');

# Build graph example
# $lower = build('build/lower.txt', fn($out) => file_put_contents($out, "ok\n"));
# $copy = build('build/copy.txt', fn($out, $in) => copy($in, $out), $lower);
# build('build/upper.txt', fn($out, $in) => file_put_contents($out, strtoupper(file_get_contents($in))), $copy);

# Benchmark with `time` in front of command
# dependency graph image?
