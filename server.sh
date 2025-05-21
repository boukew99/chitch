#!/usr/bin/env sh

ARCH="${ARCH:-$(uname -m)}"
PHP_BIN="bin/php-$ARCH"

# PHP binary fallback
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php"
[ -x "$PHP_BIN" ] || { echo "No valid PHP binary found."; exit 1; }

ADDRESS="${ADDRESS:-localhost:9000}"

echo "Using PHP binary: $PHP_BIN"
echo "Address: $ADDRESS"
echo "Development Server started at http://$ADDRESS"
echo "**Stop server with 'Ctrl + C'**"
echo

# https://www.php.net/manual/en/features.commandline.webserver.php
export PHP_CLI_SERVER_WORKERS=4
"$PHP_BIN" \
  --php-ini include/php.ini \
  --server "$ADDRESS" \
  --docroot include/view 2> build/server.log

# sudo ADDRESS=0.0.0.0:80 ./server.sh
# Localarea server for mobile access
