# GPS Data Keeper

## Installation

 1. Clone repository or download zip.
 2. Rename config/web-sample.php to config/web.php.
 3. Rename config/db-sample.php to config/db.php.
 4. Mofidy renamed files for your server.
 5. Install depends packages via composer. Run `composer install` in your shell.
 6. Configure your web server and mysql.
 7. Import into your mysql sql/init.sql.
 8. If you are want use feature "auto delete oldest data" add into your crontab `0 * * * * /var/www/map/yii cron/remove-old-data`

## Roadmap

~~**07/18** - 0.1.0~~

**08/18**  - 0.2.0

 - improve UI/UX;
 - downalod GPS data from user profile;
 - ~~my location button;~~
 - google maps;
 - thunderforest maps;
 - upload gpx;
 - upload kml.

**10/18** - 0.3.0

 - auth via google;
 - google drive support;
 - russian locale;
 - geo calculators.

## Our service online

[GPS Data Keeper](https://gpsdatakeeper.org)
