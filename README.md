# PH Backup

> [!WARNING]
> **This plugin is discontinued and the repository is archived.** It receives no
> updates, fixes or security reviews.
>
> Why:
>
> - Our hosting already backs up the database. Hourly full dumps next to the site
>   add a copy of all personal data, unencrypted, without adding safety.
> - It runs `mysqldump` through `exec()`, which many hosts disable, and passes the
>   database password on the command line.
> - If `PH_BACKUPS_PATH` points into the web root, the dumps are publicly
>   downloadable under predictable file names.
>
> Instead, use your hosting's backups, or `wp db export` from a system cron job
> into a directory outside the web root. If you still run this plugin, check that
> `PH_BACKUPS_PATH` is outside the web root and delete old dumps you no longer need.

This plugin will use WordPress schedules to create backups of the database.

**You should definitely use wp cli for schedule execution! https://wp-cron.org**

## Configuration

There are some constants that can be overwritten in **wp-config.php** file. Defaults are as follows:

```php
define( 'PH_BACKUPS_PATH', ABSPATH . "../private/backups" );
define( 'PH_BACKUPS_SCHEDULE', "hourly" );
define( 'PH_BACKUPS_HISTORY_SIZE', "48" );
```

`PH_BACKUPS_PATH` must be a writable directory location. Sub-directories will be created if not existent.

`PH_BACKUPS_SCHEDULE` use a valid WordPress schedule string.

`PH_BACKUPS_HISTORY_SIZE` define the size of the history backstack. Backups over and above this are deleted.

## Multisite WordPress

With a multisite WordPress setup we only want a single site to do the backups because the whole database will be included in the backup anyway.

1. Set `PH_BACKUPS_SCHEDULE` to **"off"**
2. Use cli command `wp ph-backup create --url=domain.tld` in a separat cronjob
   
## CLI

This plugins bringt a new wp cli command with it.

`wp ph-backup create`

`wp ph-backup ls`

`wp ph-backup create`
