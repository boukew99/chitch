#!/usr/bin/env sh

# Set working directory to the script's location
cd "$(dirname "$0")"

ARCH="${ARCH:-$(uname -m)}"
OS="$(uname -s | tr '[:upper:]' '[:lower:]')"  # linux, darwin, etc

PHP_BIN="bin/php"         # Generic fallback
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php-$ARCH"   # Fallback without OS tag
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php-$ARCH-$OS"
[ -x "$PHP_BIN" ] || { echo "No valid PHP binary found."; exit 1; }

ADDRESS="${ADDRESS:-localhost:9000}"
PHP_CONFIG="./php.ini"

echo "Using PHP binary: $PHP_BIN" and config: $PHP_CONFIG
echo "Development Server started at http://$ADDRESS"
echo "Stop server with **Ctrl + C**"
echo

export PHP_CLI_SERVER_WORKERS=4
"$PHP_BIN" \
  --php-ini "$PHP_CONFIG" \
  --server "$ADDRESS" \
  --docroot ../server/view 2> server.log


# sudo ADDRESS=0.0.0.0:80 ./server.sh
# Localarea server for mobile access

# https://www.php.net/manual/en/features.commandline.webserver.php
