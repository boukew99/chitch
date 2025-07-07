# Chitch Software Development Kit
This is the Chitch Software Development Kit (SDK), used to build the Chitch Website. It is build as a new sustainable Content Management System (CMS). It integrates with the filesystem to achieve transparent data flows and low resource usage. It can be forked and edited for your own blog or application, running locally, on your network or on the world wide web.

Visit <chitch.org> for more information about Chitch.

> [!IMPORTANT]
> This project is in an **experimental phase** and is not yet consistent!

## Setup
Get a stable build for Windows, MacOS or Linux from [Github Releases Page](https://github.com/boukew99/chitch/releases). It includes a PHP runtime.

To get the latest changes use `git pull`.

### Run
Each platform has a [custom PHP command](https://www.php.net/manual/en/features.commandline.options.php) to run the server, taking the limitations in mind.

**Windows x86_64:**
```
bin\windows\php.exe --server localhost:9000 --php-ini application\setting\php.ini --docroot application\pages > temp\server.log 2>&1
```
**MacOS x86_64:**
```
bin/darwin/php.x86_64 --server localhost:9000 --php-ini application/setting/php.ini --docroot application/pages > temp/server.log 2>&1 &
```
**MacOS ARM64:**
```
bin/darwin/php.aarch64 --server localhost:9000 --php-ini application/setting/php.ini --docroot application/pages > temp/server.log 2>&1 &
```
**Linux x86_64:**
```
bin/linux/php.x86_64 --server localhost:9000 --php-ini application/setting/php.ini --docroot application/pages > temp/server.log 2>&1 &
```
**Linux ARM64:**
```
bin/linux/php.aarch64 --server localhost:9000 --php-ini application/setting/php.ini --docroot application/pages > temp/server.log 2>&1 &
```
Once the server is running, open http://localhost:9000 in your browser.

On Linux and MacOS the server runs in the background, so you can close the terminal. They also output their logs to `temp/server.log`, which you can check for errors. On Windows the server runs in the foreground and you can stop it with `Ctrl+C`.

*MacOS*:
You may need to [allow the binary to run](https://support.apple.com/guide/mac-help/open-a-mac-app-from-an-unknown-developer-mh40616/mac).

Note that the target System for deployment is Linux, so there might be minor inconsistencies on other systems.

*In VSCode you can use the shortcut `Ctrl+Shift+B` to build the project.*

## Issue reporting
Issues with the software can be reported at https://github.com/boukew99/chitch/issues. Please use inclusive language. Note that Chitch [accepts also non-code contributions](https://github.com/readme/featured/open-source-non-code-contributions).


### License
The code is made available with the EUPL, which is a reciprocal (or copyleft) license. The [EUPL is included in text form](license.txt) for reference, but [the EUPL can be read as a web page in different languages](https://eupl.eu/1.2/en/) as well. The license was chosen to promote collaboration and allow equal contributions.

 "This product includes PHP software, freely available from
     <http://www.php.net/software/>".


```
  ____________________________________________
 /                                            \
|  Legend has it, a capybara will visit if you |
|             contribute to Chitch!            |
 \______________________________\    /________/
                                 \  /
                                  \/
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣄⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣶⣶⣶⣾⣿⠛⢿⣧⣤⣤⣤⣤⣤⣤⣤⣦⣤⣄⣀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢰⣿⠃⠀⠀⠈⠻⠿⠿⠛⠋⠉⠉⠉⠉⠉⠉⢉⣽⣿⠿⣷⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⢿⣷⣄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢠⣾⠟⢁⣤⣬⣿⣧⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣽⡿⠀⠀⠀⠀⠀⣠⣴⣶⠀⠀⠀⠀⢼⣿⠀⠈⠉⣿⡏⣿⡇
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣸⣿⠁⠀⠀⠀⠀⠘⠟⠉⠀⠀⠀⠀⠀⣾⣿⠀⠀⠀⣿⡇⣿⣇
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣴⡿⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠸⣿⡄⠀⠀⠈⠀⣿⡏
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣴⡿⠟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠹⣿⣦⣀⡀⣰⣿⠃
⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⣴⣿⠟⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⠉⣻⣿⡿⠃⠀
⠀⠀⠀⠀⢀⣤⣶⡿⠿⠟⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣴⣾⠟⠋⠀⠀⠀
⠀⠀⠀⣰⣿⠏⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣿⡏⠀⠀⠀⠀⠀⠀
⠀⠀⣼⡿⠃⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣿⠀⠀⠀⠀⠀⠀⠀
⠀⣼⡿⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠘⣿⡇⠀⠀⠀⠀⠀⠀
⢀⣿⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣿⣇⠀⠀⠀⠀⠀⠀
⢸⣿⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢹⣿⠀⠀⠀⠀⠀⠀
⠈⣿⡇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢸⣿⡆⠀⠀⠀⠀⠀
⠀⣿⣇⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣠⡄⠀⢸⣿⡅⠀⠀⠀⠀⠀
⣰⣾⣿⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⣰⣿⠃⠀⢸⣿⠆⠀⠀⠀⠀⠀
⣿⣧⣽⣿⣦⣀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣠⣾⣿⠃⠀⠀⢸⣿⠇⠀⠀⠀⠀⠀
⠈⠉⠋⠉⠙⠿⢿⣶⣤⣀⣀⡀⠀⠀⠀⠀⠀⣀⣠⣦⡀⠀⠀⠀⢰⣿⡟⠙⢿⣦⣄⣀⣸⣿⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠈⠙⠛⠛⠿⠿⠿⠿⠿⠿⠟⠛⠻⢿⣦⣤⣤⣾⠟⠀⠀⠀⠉⠛⠛⠛⠋⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⠉⠉⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
```
