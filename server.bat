@echo off

echo.
echo PHP Dev Server at: http://localhost:9000
echo Stop server with Ctrl + C
echo Terminal stay open after stop...
echo.

bin\php.exe -S localhost:9000 --php-ini include\php.ini --docroot include\view 2> bin/server.log
