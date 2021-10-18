# PH Backup

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
