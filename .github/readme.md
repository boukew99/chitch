# Chitch
Chitch is a new **green dynamic website**, specifically build to increase website sustainability by reducing redundancy. This, in an effort to bring the web closer to [becoming climate neutral](https://climate.ec.europa.eu/eu-action/climate-strategies-targets/2050-long-term-strategy_en), get to Net-zero and increase to increase adoption of sustainability by the web. It uses custom techniques and patterns to bring down complexity, but also increase the usability of the software. It is most efficient for low-traffic websites ranging around 1000 to 50000 visits per month. This kind of software can be referred to as '[Green Software](https://greensoftware.foundation/)'.

> Redundancy is the enemy of sustainability - [Web Sustainability Guidelines](https://w3c.github.io/sustainableweb-wsg/)

Chitch is an pragmatic project and thus is meant to be used as is on the [Chitch Website](https://chitch.org).

> [!IMPORTANT]
> This project in an **experimental phase**. Some areas could use more love and bugs are still amongst us!

## Features
Chitch code is made to be easily revised. Thus it can be relatively easily adjusted for an random web use case which requires sustainability. There is no limitations in place. Locality of information is tightly controlled and thus the context of code helps to inform about functionality of a piece of code. The project declares information in the file tree and is relatively oriented around it. The code is not *particularly* optimized, but it generally achieves more by *doing less work*. [Googles style guide](https://google.github.io/styleguide/htmlcssguide.html) is followed for HTML & CSS.
- Tiny source code (~35 kB as a brotli archive)
- Integrated Dependencies (self-contained)
- VSCode Integration
- Semantic HTML (User Accessibility)
- [Baseline browser](https://web.dev/baseline) functionality (PWA, View transitions, CSS Nesting)
- Internal developer tools (`tool/`)
  - Change Tracking System
  - Function Reference
- Saves users battery life (small client load)
- Concurrent File Stream Read & Write
- Incremental Builds for Shared-hosting &  Debian (self-host)
- Authentication & Authorization & Installer
- Traffic Analytics
- Posts and Contact
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

Open this project in a [Github Codespaces](https://docs.github.com/en/codespaces/developing-in-a-codespace/creating-a-codespace-for-a-repository) (with the green Code / Codespace Button). This opens the project in VSCode.

## Local Development Setup
Chitch targets the Linux(Unix-like) system, which is the [dominant system on web servers](https://w3techs.com/technologies/overview/operating_system). There are binaries included (`bin/`) for an normalized development setup, but these only work on Linux. So we need some other way to run these programs locally on other Operating Systems:

- On **Windows**, the [officially supported Windows Subsystem for Linux](https://learn.microsoft.com/en-us/windows/wsl/about) can be used to run the project in. In there the Linux setup steps can be emulated.
- On **MacOS** you can use [Homebrew](https://brew.sh/) to install a global version of `php` and `brotli`. You can do that with `brew install php brotli`. If you then remove `bin/php` and `bin/brotli`, then it will automatically link to the system installed binaries in `/usr/bin/`.

## Run
Now, in VSCode.

1. Select in the top menu bar: `Terminal` / `Run Build Task` or use the shortcut `Ctrl + Shift + B`.
2. Choose `Test Server` from the menu. This will start a testing server.
3. The echoed URL is a localhost address (something like http://localhost:9000). Open this in your web browser to see a preview of the site!

_You can also in the terminal run `make`_

## Customize
You can **edit any file** in Chitch and make it your own. Chitch is an applied project, so you have to **replace some content** before publishing. The default location to start creating your application is in the `source/public/` directory. These hold the web pages which are visualized by the browser. You can try editing `chitch.css` for a new style or add a new `.php` page for new functionality for example. With the server running you can view the changes you make by refreshing the page in the web browser. _By default (in this projects) VSCode will save the changes once you switch to the web browser, so you don't have to save manually._

By editing Chitch you will have essentially **forked** your own version of Chitch to make it compatible for your own requirements. You can keep your own repo and upstream the updates from the stem (Chitch) later.

## Publishing
If you you can publish your own customized version of Chitch. Chitch requires the following features from a hosting setup:

- SSL (Secure Sockets Layer) for usage of HTTPS (Hypertext Transfer Protocol Secure)
- Mail server, for mail() usage
- Configurable Webroot/Document-root
- Green energy (Otherwise it won't run)

### Shared hosting Installation
Shared hosts are a relatively restricted setup, so in order to make the setup universal, most CMS's deploy into `public_html` directly. Chitch does this as wel, but it takes an more secure approach by changing the Webroot. The steps to install Chitch:

0. Build `Shared Host ZIP` from your source code.
1. Upload shared-host.zip in `public_html/chitch/`.
2. Extract shared-host.zip.
3. Set the Webroot with your **hosts tools** to `public_html/chitch/source/public/`.
4. Go to the page `install.php` on your domain and follow the instructions there.

### Self-Host
Chitch can also easily be installed on your own setup. The self-host setup is specifically built for an [Debian distrubution or derivative](https://www.debian.org/derivatives/). This includes for example Raspberry Pi OS or most Virtual Private Server setups. There is an build option: `Debian Setup for Apache Web Server`. It creates an `chitch.deb` package which can be installed with `sudo apt install ./chitch.deb`. You can also try to install it by double-clicking the file in a file browser locally.

The DEB package sets up the Apache Web Server and install Chitch. For now it is only tested locally and can be accessed at http://localhost/. If you want to use it in production you have to configure Apache more for your setup, connect your domain and setup security measures.

Chitch can be removed with `sudo apt remove chitch`.

## Contributing
Note that [code is **not the only** contribution](https://github.com/readme/featured/open-source-non-code-contributions) that helps Chitch. Usability, graphic design, testing, outreach, accessibility, aesthetics, security, copywriting, documentation, legal, localization, organization, support tasks, these are all disciplines that enhance open source projects.
Since Chitch is an **open & distributed project**, it is probably worth it to [open an Issue](../../../issues) specifying what you would like to work on and gather feedback as how to continue and find other interested contributors. Please use inclusive language, the simplest solutions are universal.


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
The aim of Chitch is that it supports the widest use case for sustainable websites. Then Chitch can be the **superset** from which anyone can take a **subset** to use in their own specialized application. *One example* of such a fork would be Chitch but with binaries compiled for aarch64 architecture, which is generally associated with better power efficiency. However its is not as widely used for development machines and thus is a more specialized use case. *Note, the web code of Chitch runs on both architectures*

### Technical Constraints
In order reach the target of being sustainable, there are three constraints:

1. Chitch needs to completely boot-able from Disk. Meaning all of Chitch state should be able to be stored in Disk. This ensures Chitch can hibernate to disk, meaning it won't use any resources (RAM or CPU) if it is not active.
2. In order to take advantage of hibernation it needs to run in a shared hosting setup. There CPU and RAM can be easily shared since Chitch can live fully on the disk.
3. In order to make the shared hosting setup accessible, Chitch needs to work with the FTP protocol. This puts restrictions on the filesystem functionality, such as not being able to use symlinks.

## License
The code is made available with the EUPL, which is a weak copyleft license. By making this green software freely available, people can use it and minimize the climate footprint of the web. The [EUPL is included in text form](../license.txt) for reference, but [the EUPL can be read as a web page](https://eupl.eu/1.2/en/) as well. The license was chosen to promote collaboration by allowing the contributions to remain open.

### Licenses
"This product includes PHP software, freely available from
     <http://www.php.net/software/>"

PHP binary build with [static-php-cli](https://github.com/crazywhalecc/static-php-cli).

See `bin/license` for text versions of licenses.
