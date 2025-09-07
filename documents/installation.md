# Installation
Chitch does not require system-wide installation. It is a portable web application that can be run from any directory. You can simply download Chitch and run it using PHP's built-in server or any web server that supports PHP.

## Steps to Install Chitch
1. **Download Chitch**: You can download the latest stable release from the [GitHub Releases Page](https://github.com/boukew99/chitch/releases).
2. **Extract the Files**: Unzip the downloaded file to your desired directory on your local machine.
3. **Start the Server**: If you have PHP installed, run `command/server.php` from the command line in the project root directory.
4. **Access Chitch**: Open your web browser and go to the server address to access the website.

## Installing PHP for Chitch
Chitch requires PHP to run. You can check if PHP is installed by running `php -v` in your terminal. You can use a statically compiled PHP binary specifically build for Chitch, which uses the exact extensions needed for Chitch. This binary can be used locally without global installation. Follow these steps to use the PHP binary for Chitch:

1. **Download the PHP Binary**: Visit the [Chitch PHP Binary Releases Page](https://github.com/boukew99/chitch/releases/supplementary) to download the latest PHP binary for your operating system.
2. **Extract the Files**: Unzip the downloaded file to the `bin` directory.
3. **Run the Server**: Using the binary with `bin/php command/server.php`.

Alternatively you can also [do a global PHP install as elaborated on php.net](https://www.php.net/downloads).

## Windows Users
If using Windows change the command to `command\server.php` and use backslashes in paths. The PHP binary is also called `php.exe` on Windows. Thus use `bin\php.exe command\server.php` to start the server with the included PHP binary.

## Troubleshooting
If you encounter issues, check the error logs for more information.
Ensure that all required PHP extensions are installed and enabled.
For community support, consider reaching out on the GitHub issues page.

## Uninstallation
To uninstall Chitch, simply delete the directory where you extracted the files. There are no system-wide changes made during installation, so no additional steps are necessary.


