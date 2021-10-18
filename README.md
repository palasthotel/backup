# PH Backup

This plugin will use WordPress schedules to create backups of the database.

**You should definitely use wp cli for cron execution! https://wp-cron.org**

## Configuration

There are some constants that can be overwritten in wp-config.php file. Defaults are as follows:

```php
define( 'PH_BACKUPS_PATH', ABSPATH . "../private/backups" );
define( 'PH_BACKUPS_SCHEDULE', "hourly" );
define( 'PH_BACKUPS_HISTORY_SIZE', "48" );
```

`PH_BACKUPS_PATH` must be a writable directory location. Sub-directories will be created if no existent.

`PH_BACKUPS_SCHEDULE` use a valid WordPress schedule string.

`PH_BACKUPS_HISTORY_SIZE` define the size of the history backstack. Backups over and above this are deleted.