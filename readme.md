# Chitch
Chitch is a live template and is build as a new sustainable web application. It integrates with the filesystem to achieve transparent data flows and low resource usage. It can be forked and edited for your own blog or application, running locally, on your network or on the world wide web.

> [!IMPORTANT]
> This project in an **experimental phase** and is not yet consistent!

## Setup
Chitch is fully **self-contained** and **cross-platform**, and thus you only need to extract it to run it. You can get a Chitch build specifically for your operating system from the [Github Releases Page](https://github.com/boukew99/chitch/releases).

These bundles have a stable version of Chitch. To get the latest changes use `git pull`.

### Run
In your file browser run `php/server.sh` on MacOS/Linux or `php/server.bat` on Windows. Open http://localhost:9000 in your browser.

*MacOS*:
You may need to [allow the binary to run](https://support.apple.com/guide/mac-help/open-a-mac-app-from-an-unknown-developer-mh40616/mac).

*Windows*:
for full compatibility, use the [officially supported Windows Subsystem for Linux](https://learn.microsoft.com/en-us/windows/wsl/about) or use [Github Codespaces](https://docs.github.com/en/codespaces/developing-in-a-codespace/creating-a-codespace-for-a-repository).


## Deployment
Deploy to the world wide web! Chitch requires the following features from a hosting setup:

- SSL (Secure Sockets Layer) for usage of HTTPS (Hypertext Transfer Protocol Secure)
- Mail server, for mail() usage, used in login.
- Configurable Webroot/Document-root
- Green energy (Otherwise it won't run)
- Linux Operating System (the [most used system on web servers](https://w3techs.com/technologies/overview/operating_system))


### Packaging
In order to publish Chitch you first need to package the website for exportation. We can use a **file browser** for that.

1. Copy the `application/` directory and post-fix the new directory name with the version number. For example `chitch-3-7`.
2. Delete pages from `chitch-3-7/view/` which you don't require (superset).
3. Open the Context Menu (Right-click) on `chitch-3-7` and Select the option to make it into a ZIP archive.
    - Windows: `Send To > Compressed (zipped) folder`
    - Linux/MacOS: `Compress` as ZIP

### Shared hosting
Shared hosting is an efficient, cheap and accessible way to host a website. However, they are a relatively restricted setups, so in order to make the setup universal, we need to deploy into `public_html` directly.The steps to install Chitch:

1. Upload and extract `chitch-3-7.zip` in `public_html/`.
2. Set the Webroot with your **hosts tools** to `public_html/chitch-3-7/view/`.
3. Go to the page `/install.php` on your domain and follow the instructions there.

User specific data is meant to be stored in `database/`. On the shared hosting a `database` will be automatically created with the `install.php` process.

#### Updating

We can do an **atomic** update to the host with the same process. The only thing to keep in mind is that the new ZIP will need a new version number, as not to overlap the current code during extraction. If you keep the old code around you can also switch back to the previous version of the site if something goes wrong. The database information should remain, so `/install.php` is not necessary and should be forbidden to access.

#### Assets (CDN)
Assets such as images, audio and video are not included in the code, since these remain static usually and do not need frequent updates. Therefore, these assets are hosted on a subdomain. In this case `cdn.chitch.org`. These can be managed easily with FTP.

For local development they can also be used, since the browser makes a cache of the assets, which will work offline as well.

## Issue reporting
Issues with the software can be reported at https://github.com/boukew99/chitch/issues. Please use inclusive language. Note that Chitch accepts also [non-code contributions](https://github.com/readme/featured/open-source-non-code-contributions).


```
  ____________________________________________
 /                                            \
|  Legend has it a capybara will visit, if you |
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

### License
The code is made available with the EUPL, which is a reciprocal (or copyleft) license. The [EUPL is included in text form](license.txt) for reference, but [the EUPL can be read as a web page in different languages](https://eupl.eu/1.2/en/) as well. The license was chosen to promote collaboration and allow contributions to remain open.

 "This product includes PHP software, freely available from
     <http://www.php.net/software/>".
