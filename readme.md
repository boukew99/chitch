# Chitch Software Development Kit
This is the Chitch Software Development Kit (SDK), used to build the [Chitch Website](https://chitch.org). It includes website builder functionality at a low-level, integrating deeply with the Operating System (Linux). This results in low resource usage, low complexity high portability and fast response times. Chitch can be forked and easily customized for your own wesbite.

Visit [chitch.org](https://chitch.org) for more information about Chitch. It also doubles as a demo of Chitch.

---

## Getting started
Download a stable build from the [Github Releases Page](https://github.com/boukew99/chitch/releases).

If you have PHP installed run `./chitch.php` to start the Chitch server.

Open `docs/index.html` in your browser for more options.

## Issue reporting
Issues with the software can be reported at https://github.com/boukew99/chitch/issues. This is a volunteering effort.

### Forking
The code is made available with the EUPL, which is a reciprocal license. The [EUPL is included in text form](license.txt) for reference, but [the EUPL can be read as a web page in different languages](https://eupl.eu/1.2/en/) as well. The license was chosen for equal contributing rights and longevity of the software. You can fork Chitch under this license and use it for your own projects.

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
