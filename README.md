# GPS Data Keeper

## Installation

 1. Clone repository or download zip.
 2. Rename config/web-sample.php to config/web.php.
 3. Rename config/db-sample.php to config/db.php.
 4. Rename config/keys-sample.php to config/keys.php.
 5. Mofidy renamed files for your server.
 6. Modify config/params.php.
 7. Install depends packages via composer. Run `composer install` in your shell.
 8. Configure your web server and mysql.
 9. Import into your mysql sql/init.sql.
 10. If you are want use feature "auto delete oldest data" add into your crontab `0 * * * * /var/www/map/yii cron/remove-old-data`
 
## Upgrade
 1. Update code by run `git pull`.
 2. Update dependencies by run `composer install`.
 3. Update database by run `./yii migrate`.

## Roadmap

~~**07/18** - 0.1.0~~

~~**08/18**  - 0.2.0~~

**10/18** - 0.3.0

 - ~~auth via google;~~
 - google drive support;
 - choose color for track and marker;
 - set name for track and marker;
 - geo calculators.

## Our service online

[GPS Data Keeper](https://gpsdatakeeper.org)
