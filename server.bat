@echo off

echo.
echo Using PHP binary: bin/php.exe
echo Development Server started at http://localhost:9000
echo Stop server with **Ctrl + C**
echo.

start "" "http://localhost:9000"

bin\php.exe -S localhost:9000 --php-ini include\php.ini --docroot include\view 2> database/server.log

pause
REM Do not auto-close window
