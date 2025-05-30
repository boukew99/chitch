#!/usr/bin/env sh

ARCH="${ARCH:-$(uname -m)}"
OS="$(uname -s | tr '[:upper:]' '[:lower:]')"  # linux, darwin, etc

PHP_BIN="bin/php"         # Generic fallback
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php-$ARCH"   # Fallback without OS tag
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php-$ARCH-$OS"
[ -x "$PHP_BIN" ] || { echo "No valid PHP binary found."; exit 1; }

ADDRESS="${ADDRESS:-localhost:9000}"

echo "Using PHP binary: $PHP_BIN"
echo "Development Server started at http://$ADDRESS"
echo "Stop server with **Ctrl + C**"
echo

export PHP_CLI_SERVER_WORKERS=4
"$PHP_BIN" \
  --php-ini include/php.ini \
  --server "$ADDRESS" \
  --docroot include/view 2> database/server.log


# sudo ADDRESS=0.0.0.0:80 ./server.sh
# Localarea server for mobile access

# https://www.php.net/manual/en/features.commandline.webserver.php
