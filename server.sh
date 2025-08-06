#!/bin/bash

#PHP_CLI_WORKERS=4

PORT=${PORT:-9000}

echo "Output redirected to ../server.log. Server starting at http://localhost:$PORT."
../php --server localhost:$PORT --docroot documents 2> ../server.log

# call `./server.sh &` to run in the background
# call `sudo PORT=80 ./server.sh` to run on port 80 (publicly accessible, use with dnsmasq)
