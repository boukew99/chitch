# Chitch

Chitch is a new **green dynamic website technology**, specifically build to increase website sustainability. This, in an effort to bring the web closer to [becoming climate neutral](https://climate.ec.europa.eu/eu-action/climate-strategies-targets/2050-long-term-strategy_en), get to Net-zero and increase to increase adoption of sustainability by the web. It uses custom techniques and patterns to bring down complexity and also increase the accessibility for easy adoption. It is most efficient for low-traffic websites. These websites can be considered the **long tail of the web**. Chitch can be considered '[Green Software](https://greensoftware.foundation/)'. It is an pragmatic project and thus is meant to be used by the community as is on the [Chitch Website](https://chitch.org).

The [Web Sustainability Guidelines by W3C](https://w3c.github.io/sustainableweb-wsg/) are taken as a guideline in this project and to measure success in the sustainability goal.

> [!IMPORTANT]
> This project in an **experimental phase**. Some areas could use more love and bugs are still amongst us!

## Features

Chitch code is made to be easily revised. Thus it can be relatively easily adjusted for a random web use case which requires sustainability. There are no limitations in place. Locality of information is tightly controlled and thus the context of code helps to inform about functionality of a piece of code. The project declares information in the file tree and is relatively oriented around it. The code is not *particularly* optimized, but it generally achieves more by *doing less work*. [Googles style guide for HTML & CSS](https://google.github.io/styleguide/htmlcssguide.html) is followed .
- Tiny source code (~35 kB as a brotli archive)
- VSCode Integrations
- Semantic HTML (User Accessibility)
- [Baseline browser](https://web.dev/baseline) functionality (PWA, View transitions, CSS Nesting)
- Internal developer tools (`tool/`)
- Saves users battery life (small client load)
- Concurrent File Stream Read & Write
- Build for Shared-hosting
- Authentication & Authorization & Installer
- Visitor Traffic Analytics
- Posts and Contact forms
- Run from an USB
- Backward compatibility with [IP over Avian Carriers](https://www.rfc-editor.org/rfc/rfc2549).


```
 ______________________________________
< Terminal usage is optional in Chitch >
 --------------------------------------
        \   ^__^
         \  (oo)\_______
            (__)\       )\/\
                ||--www |
                ||     ||

```

## Local Setup
Chitch is fully self-contained, and thus you only need to extract it to run it. You can get a Chitch build specifically for your operating system:

- [Windows](https://github.com/boukew99/chitch/releases/download/stable/chitch-windows.zip)
- [MacOS x86_64](https://github.com/boukew99/chitch/releases/download/stable/chitch-x86_64-darwin.tar.gz)
- [MacOS aarch64 (ARM)](https://github.com/boukew99/chitch/releases/download/stable/chitch-aarch64-darwin.tar.gz)
- [Linux x86_64](https://github.com/boukew99/chitch/releases/download/stable/chitch-x86_64-linux.tar.xz)
- [Linux aarch64](https://github.com/boukew99/chitch/releases/download/stable/chitch-aarch64-linux.tar.xz)

These bundles have a stable version of Chitch. For the latest changes run `git pull`.

## Run

Now, open the project in VSCode.

1. Select in the top menu bar: `Terminal` / `Run Build Task` or use the shortcut `Ctrl + Shift + B`.
2. Choose the `Test Server` option for your Operating System from the menu. This will start a testing server.
3. The echoed URL is a localhost address (like http://localhost:9000). Open this in your web browser to see the site!

MacOS:
You may need to [allow the binary to run](https://support.apple.com/guide/mac-help/open-a-mac-app-from-an-unknown-developer-mh40616/mac).

Windows:
for full compatibility, use the [officially supported Windows Subsystem for Linux](https://learn.microsoft.com/en-us/windows/wsl/about) or use [Github Codespaces](https://docs.github.com/en/codespaces/developing-in-a-codespace/creating-a-codespace-for-a-repository).

 You can also link to your global PHP version with `ln -s $(command -v php)` in `bin/`.

If not using VSCode, then you can also run `server.sh` or `server.bat` from the file browser or command line.

## Customize
You can **edit any file** in Chitch and make it your own. Chitch is an applied project, so you have to **replace some content** before publishing. The default location to start creating your application is in the `server/view/` directory. These hold the web pages which are visualized by the browser. You can try editing `chitch.css` for a new style or add a new `.php` page for new functionality. With the server running you can view the changes you make by refreshing the page in the web browser. _By default (in this projects) VSCode will save the changes once you switch to the web browser, so you don't have to save manually._

By editing Chitch you will have essentially **forked** your own version of Chitch. You can make it compatible to your own requirements. Optionally you can upstream the updates from the stem (Chitch-main) later.

## Publishing
Publish your own customized version to the world wide web! Chitch requires the following features from a hosting setup:

- SSL (Secure Sockets Layer) for usage of HTTPS (Hypertext Transfer Protocol Secure)
- Mail server, for mail() usage
- Configurable Webroot/Document-root
- Green energy (Otherwise it won't run)
- Linux Operating System

Chitch targets the Linux(Unix-like) system, which is the [dominant system on web servers](https://w3techs.com/technologies/overview/operating_system).

### Packaging
In order to publish Chitch you first need to package the website for exportation. We can use a **file browser** for that.

1. Copy the `server/` directory and post-fix the new directory name with the version number. For example `chitch-3-7`.
2. Delete the files in `chitch-3-7` which you don't want to publish.
3. Open the Context Menu (Right-click) on `chitch-3-7` and Select the option to make it into a ZIP archive.
    - Windows: `Send To > Compressed (zipped) folder`
    - Linux/MacOS: `Compress` as ZIP

### Shared hosting Setup
Shared hosting is an efficient, cheap and accessible way to host a website. However they are a relatively restricted setup, so in order to make the setup universal, most CMS's deploy into `public_html` directly. Chitch does this as wel, but it takes an more secure approach by changing the Webroot. The steps to install Chitch:

1. Upload and extract `chitch-3-7.zip` in `public_html/`.
2. Set the Webroot with your **hosts tools** to `public_html/chitch-3-7/view/`.
3. Go to the page `/install.php` on your domain and follow the instructions there.

#### Updating

We can an **atomic** update to the host with the same process. The only thing to keep in mind is that the new ZIP will need a new version number, as not to overlap the current code during extraction. If you keep the old code around you can also switch back to the previous version of the site if something goes wrong.

#### Assets (CDN)
Assets such as images, audio and video are not included in the code, since these remain static usually and do not need frequent updates. Therefore, these assets are hosted on a subdomain. In this case `cdn.chitch.org`. These can be managed easily with FTP.

For local development they can also be used, since the browser makes and cache of the assets which will work offline as well.

## Contributing
Note that [code is **not the only** contribution](https://github.com/readme/featured/open-source-non-code-contributions) that helps Chitch. Usability, graphic design, testing, outreach, accessibility, aesthetics, security, copywriting, documentation, legal, localization, organization, support tasks, these are all disciplines that enhance open source projects.
Since Chitch is an **open & distributed project**, it is probably worth it to [open an Issue](../../issues) specifying what you would like to see on and gather feedback as how to continue and find other interested contributors. Please use inclusive language, the simplest solutions are universal.


```
  ____________________________________________
 /                                            \
|  Legend has it a capybara will visit if you  |
|         if you contribute to Chitch!         |
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

### Project Aim
This project is foremost an working site. Thus it is just 1 specific tree of Chitch. Anyone can fork it and make there own tree. However,  the aim is that this tree packs the most functionality, in a sustainable manner. Then this tree can be the **superset** from which anyone can take a **subset** and customize that for their own specialized application.
Thus, this project is a living template for sustainable web app software. It’s not a framework you build on top of. You build within it. It’s a readable, modifiable codebase meant to be forked, studied, and evolved for your own use. It is a system with rules, no abstract translation layers, just direct straightforward code. That is the most efficient approach.
Eventually most user specific data is meant to be stored in `database/`, independent of the version controlled source code. However some *functionality* can also be user specific, so this separation is blurry.

## License
The code is made available with the EUPL, which is a weak copyleft license. By making this green software freely available, people can use it and minimize the climate footprint of the web. The [EUPL is included in text form](license.txt) for reference, but [the EUPL can be read as a web page](https://eupl.eu/1.2/en/) as well. The license was chosen to promote collaboration by allowing the contributions to remain open.

 "This product includes PHP software, freely available from
     <http://www.php.net/software/>".
