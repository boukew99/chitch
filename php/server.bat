@echo off

:: Set working directory to the script's location
cd /d "%~dp0"

echo.
echo Using PHP binary: bin/php.exe
echo Development Server started at http://localhost:9000
echo Stop server with **Ctrl + C**
echo.

start "" "http://localhost:9000"

bin\x86_64.exe -S localhost:9000 --php-ini .\php.ini --docroot ..\application\view

pause
REM Do not auto-close window
