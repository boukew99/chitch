# Upgrading Chitch

Chitch, like any software project, evolves over time, and new versions may introduce changes that require users to upgrade their installations. This document provides guidelines and instructions for upgrading Chitch to ensure a smooth transition to the latest version.


First the following structure is assumed in the file structure:

- chitch
- database

Here the database is separate from the code.

## Backup Your Data

Before upgrading, it's recommended to back up your `database`. This ensures that you can restore your data to a correct state if the upgrade is not compatible. You can simply ZIP the `database` folder. [For more details see backup](backup.md).

## Check Compatibility

Review the `changelog.md` for the new version of Chitch to understand the changes and improvements made. Pay attention to any breaking changes or deprecated features that may affect your current setup.

### Upgrading to a new version

 What we will do is place the new version beside these folders. To do so we need it have a name other than `chitch`. For example

- chitch
- database
- chitch-2.0

As a rule of thumb, you can suffix the current version.

The next step is to change the docroot from the old code to the new code. From `chitch` to `chitch-2.0` in this case. This can be done with the tools of your hosting provider. The new site should be visible on your domain after changing the webroot.

By changing the docroot, none to minimal downtime to site is incurred. If you keep the old code around you can also switch back to the previous version of the site if something goes wrong. It would be as simple as changing the docroot back.

Test the upgrade thoroughly to ensure everything is functioning as expected.
