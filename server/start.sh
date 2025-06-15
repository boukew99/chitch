#!/usr/bin/env sh

# Set working directory to the script's location
cd "$(dirname "$0")"

ARCH="${ARCH:-$(uname -m)}"
OS="$(uname -s | tr '[:upper:]' '[:lower:]')"  # linux, darwin, etc

PHP_BIN="bin/php-$ARCH-$OS"
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php-$ARCH"
[ -x "$PHP_BIN" ] || PHP_BIN="bin/php"         # Generic fallback
[ -x "$PHP_BIN" ] || {
  command -v php >/dev/null && PHP_BIN=$(command -v php)
  # global PHP
}
[ -x "$PHP_BIN" ] || { echo "No valid PHP binary found."; exit 1; }

ADDRESS="${ADDRESS:-localhost:9000}"
PHP_CONFIG="./php.ini"

echo "Using PHP binary: $PHP_BIN" and config: $PHP_CONFIG
echo "Development Server started at http://$ADDRESS"
echo "Outputting logs to server/server.log"
echo

# Open the server in the default web browser
if command -v xdg-open >/dev/null 2>&1; then
  xdg-open "http://$ADDRESS" &>/dev/null || true
elif command -v open >/dev/null 2>&1; then
  open "http://$ADDRESS" &>/dev/null || true
elif command -v start >/dev/null 2>&1; then
  start "http://$ADDRESS" &>/dev/null || true
else
  echo "No suitable command to open the browser."
fi

export PHP_CLI_SERVER_WORKERS=4
"$PHP_BIN" \
  --php-ini "$PHP_CONFIG" \
  --server "$ADDRESS" \
  --docroot ../application/view

# sudo ADDRESS=$(hostname -I | awk '{print $1}'):80 server/start.sh
# Local network server

# server/start.sh 2> server/temp/server.log &
# Redirect all output to server.log and run it in the background

# https://www.php.net/manual/en/features.commandline.webserver.php


if [ -t 0 ]; then
    printf "Press enter to continue...\n"
    read dummy
fi

# Don't auto-close window if stdin is terminal
