#!/usr/bin/env sh
cd "$(dirname "$0")"

ARCH="${ARCH:-$(uname -m)}"
OS="$(uname -s | tr '[:upper:]' '[:lower:]')"
PHP_CONFIG="./php.ini"
LOG_FILE="temp/server.log"
PID_FILE="temp/server.pid"
ADDRESS="${ADDRESS:-localhost:9000}"
# sudo ADDRESS=$(hostname -I | awk '{print $1}'):80 server/start.sh
# Local network server

find_php_bin() {
  for bin in "bin/$ARCH/$OS/php" "bin/php" "$(command -v php)"; do
    [ -x "$bin" ] && echo "$bin" && return
  done
  echo "No valid PHP binary found." >&2
  exit 1
}

open_browser() {
  url="http://$ADDRESS/tool/"
  for cmd in xdg-open open start; do
    command -v "$cmd" >/dev/null 2>&1 && "$cmd" "$url" &>/dev/null && return
  done
  echo "No suitable command to open the browser."
}

PHP_BIN="$(find_php_bin)"

echo "Using PHP binary: $PHP_BIN and config: $PHP_CONFIG"
echo "Development Server started at http://$ADDRESS"
echo "Outputting logs to $LOG_FILE"
echo

open_browser

export PHP_CLI_SERVER_WORKERS=4

"$PHP_BIN" --php-ini "$PHP_CONFIG" --server "$ADDRESS" --docroot ../application/pages > "$LOG_FILE" 2>&1 &
PHP_PID=$!
echo "$PHP_PID" > "$PID_FILE"

echo "PHP server started with PID $PHP_PID"
echo "To stop the server, run: kill $PHP_PID"
echo "Check server status at: http://$ADDRESS/tool/"
echo

sleep 1
if ! kill -0 "$PHP_PID" 2>/dev/null; then
  echo "Error: PHP server failed to start. See $LOG_FILE for details."
  exit 1
fi

[ -t 0 ] && printf "Press enter to continue...\n" && read dummy
# Don't auto-close window if stdin is terminal
