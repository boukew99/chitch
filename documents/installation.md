# Installation

Chitch does not require system-wide installation. It is a portable web application that can be run from any directory. You can simply download Chitch and run it using PHP's built-in server or any web server that supports PHP.

## Steps to Install Chitch

1. **Download Chitch**: You can download the latest stable release from the [GitHub Releases Page](https://github.com/boukew99/chitch/releases).
2. **Extract the Files**: Unzip the downloaded file to your desired directory on your local machine.
3. Make a directory called `database` beside the `chitch` directory. This allows the site to store user generated data.

## Installing PHP for Chitch

Chitch requires PHP to run. You can check if PHP is installed by running `php -v` in your terminal. If yes then you are done.

If PHP is not globally installed, then you can use a local PHP binary specifically build for Chitch. This binary can be used locally without a global installation. Thus it is also easy to remove again. Follow these steps to use the PHP binary for Chitch:

1. **Download the PHP Binary**: Visit the [Chitch PHP Binary Releases Page](https://github.com/boukew99/chitch/releases/supplementary) to download the latest PHP binary for your operating system.
2. **Extract the Files**: Unzip the downloaded file beside the `chitch` directory.

The filesystem should look something like this at this point:

- chitch/
- database/
- php

On Windows `php` binary is called `php.exe`.

### MacOS user

On **MacOS** you need to [allow execution to run](https://support.apple.com/guide/mac-help/open-a-mac-app-from-an-unknown-developer-mh40616/mac) the local PHP binary.


## Uninstallation

To uninstall Chitch, simply delete the directory where you extracted the files. There are no system-wide changes made during installation, so no additional steps are necessary.
