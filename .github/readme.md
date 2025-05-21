# Chitch
Chitch is a new **green dynamic website technology**, specifically build to increase website sustainability. This, in an effort to bring the web closer to [becoming climate neutral](https://climate.ec.europa.eu/eu-action/climate-strategies-targets/2050-long-term-strategy_en), get to Net-zero and increase to increase adoption of sustainability by the web. It uses custom techniques and patterns to bring down complexity and also increase the accessibility for easy adoption. It is most efficient for low-traffic websites. These websites can be considered the **long tail of the web**. Chitch can be considered '[Green Software](https://greensoftware.foundation/)'. It is an pragmatic project and thus is meant to be used by the community as is on the [Chitch Website](https://chitch.org).

The [Web Sustainability Guidelines by W3C](https://w3c.github.io/sustainableweb-wsg/) are taken as a guideline in this project and to measure success in the sustainability goal.

> [!IMPORTANT]
> This project in an **experimental phase**. Some areas could use more love and bugs are still amongst us!

## Features
Chitch code is made to be easily revised. Thus it can be relatively easily adjusted for a random web use case which requires sustainability. There are no limitations in place. Locality of information is tightly controlled and thus the context of code helps to inform about functionality of a piece of code. The project declares information in the file tree and is relatively oriented around it. The code is not *particularly* optimized, but it generally achieves more by *doing less work*. [Googles style guide for HTML & CSS](https://google.github.io/styleguide/htmlcssguide.html)is followed .
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

## Online Development Setup
The online setup is the easiest way to get started.

1. Open this project in a [Github Codespaces](https://docs.github.com/en/codespaces/developing-in-a-codespace/creating-a-codespace-for-a-repository) (with the green Code / Codespace Button). This opens the project in VSCode.

2. Then symlink  against the global PHP with `ln -s $(which php) bin/` in the terminal.

## Local Development Setup
Chitch targets the Linux(Unix-like) system, which is the [dominant system on web servers](https://w3techs.com/technologies/overview/operating_system). Development however happens on multiple Operating Systems. For development only a the programming language binary is needed, which in this case is PHP. You can download a static version of PHP for your operating system:

- [Windows PHP Binary](https://dl.static-php.dev/static-php-cli/windows/spc-max/php-8.4.6-cli-win.zip)
- [MacOS x86_64 architecture PHP binary](https://dl.static-php.dev/static-php-cli/common/php-8.4.6-cli-macos-x86_64.tar.gz)
- [MacOS aarch64 (ARM) architecture PHP binary](https://dl.static-php.dev/static-php-cli/common/php-8.4.6-cli-macos-aarch64.tar.gz)
- [Linux aarch64 architecture PHP binary (Custom)](https://github.com/boukew99/chitch/releases/download/supplementary/php-aarch64.tar.xz)
- [Linux x86_64 architecture PHP binary (Custom)](https://github.com/boukew99/chitch/releases/download/supplementary/php.tar.xz)

_Binaries are provided by the Static PHP CLI Project, [Sponsor Static PHP CLI ](https://github.com/sponsors/crazywhalecc)_

Extract the downloaded package and place the binary in `bin/`.

MacOS:
You may need to [enable the binary to allow it to run](https://support.apple.com/guide/mac-help/open-a-mac-app-from-an-unknown-developer-mh40616/mac).

## Run
Now, in VSCode for MacOS and Linux.

1. Select in the top menu bar: `Terminal` / `Run Build Task` or use the shortcut `Ctrl + Shift + B`.
2. Choose the `Test Server` option for your Operating System from the menu. This will start a testing server.
3. The echoed URL is a localhost address (something like http://localhost:9000). Open this in your web browser to see the site!

For full compatibility on Windows, use the [officially supported Windows Subsystem for Linux](https://learn.microsoft.com/en-us/windows/wsl/about).

## Customize
You can **edit any file** in Chitch and make it your own. Chitch is an applied project, so you have to **replace some content** before publishing. The default location to start creating your application is in the `include/view/` directory. These hold the web pages which are visualized by the browser. You can try editing `chitch.css` for a new style or add a new `.php` page for new functionality. With the server running you can view the changes you make by refreshing the page in the web browser. _By default (in this projects) VSCode will save the changes once you switch to the web browser, so you don't have to save manually._

By editing Chitch you will have essentially **forked** your own version of Chitch. You can make it compatible to your own requirements. Optionally you can upstream the updates from the stem (Chitch-main) later.

## Publishing
Publish your own customized version of Chitch. Chitch requires the following features from a hosting setup:

- SSL (Secure Sockets Layer) for usage of HTTPS (Hypertext Transfer Protocol Secure)
- Mail server, for mail() usage
- Configurable Webroot/Document-root
- Green energy (Otherwise it won't run)

### Packaging
In order to publish Chitch you first need to package the website for exportation. We can use a file browser for that.

1. Copy the `include/` directory and post-fix the new directory name with the version number. For example `chitch-3-7`.
2. Prune/Delete the files in `chitch-3-7` which you don't want to publish.
3. Open the Context Menu (Right-click) on `chitch-3-7` and Select the option to make it into a ZIP archive.
    - Windows: `Send To > Compressed (zipped) folder`
    - Linux/MacOS: `Compress`

### Shared hosting Installation
Shared hosting is an efficient, cheap and accessible way to host a website.

Shared hosts are a relatively restricted setup, so in order to make the setup universal, most CMS's deploy into `public_html` directly. Chitch does this as wel, but it takes an more secure approach by changing the Webroot. The steps to install Chitch:

1. Upload shared-host.zip in `public_html/`.
2. Extract shared-host.zip.
3. Set the Webroot with your **hosts tools** to `public_html/chitch/view/`.
4. Go to the page `/install.php` on your domain and follow the instructions there.

#### Shared hosting Updating
To make an update to the host **atomically* we follow the following steps:

1. Upload the new ZIP version besides the old active code in `public/html`.
1. Extract the ZIP (will not overlap with a new version number)
2. Set the Webroot with your **hosts tools** to the new `view` folder.
3. Move the old `view/upload` folder to the new `view` folder.

This will ensure a seamless switch to the new code. If you keep the old code around you can also switch back to the previous version of the site if something goes wrong.

## Contributing
Note that [code is **not the only** contribution](https://github.com/readme/featured/open-source-non-code-contributions) that helps Chitch. Usability, graphic design, testing, outreach, accessibility, aesthetics, security, copywriting, documentation, legal, localization, organization, support tasks, these are all disciplines that enhance open source projects.
Since Chitch is an **open & distributed project**, it is probably worth it to [open an Issue](../../../issues) specifying what you would like to see on and gather feedback as how to continue and find other interested contributors. Please use inclusive language, the simplest solutions are universal.


```
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
⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⠉⠉⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
```

_Legend has it a Capybara will visit, if you contribute._

### Project Aim
The aim of Chitch is that it supports the widest use case for sustainable websites. Then Chitch can be the **superset** from which anyone can take a **subset** to use in their own specialized application.

### Technical Constraints
In order reach the target of being sustainable, there are three constraints:

1. Chitch needs to completely boot-able from Disk. Meaning all of Chitch state should be able to be stored in Disk. This ensures Chitch can hibernate to disk, meaning it won't use any resources (RAM or CPU) if it is not active.
2. In order to take advantage of hibernation, the host needs to be able to share computing resources.
3. Chitch needs to be accessible.

Shared hosting satisfies this constraints. However, shared hosting has limitations, which means Chitch also needs to work with the **FTP protocol** to allow the user to upload the website.

## License
The code is made available with the EUPL, which is a weak copyleft license. By making this green software freely available, people can use it and minimize the climate footprint of the web. The [EUPL is included in text form](../license.txt) for reference, but [the EUPL can be read as a web page](https://eupl.eu/1.2/en/) as well. The license was chosen to promote collaboration by allowing the contributions to remain open.
