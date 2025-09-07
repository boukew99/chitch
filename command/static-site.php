<?php
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
