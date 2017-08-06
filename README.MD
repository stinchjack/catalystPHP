# user_upload.php

## Author: Jack Stinchcombe
### Email: stinchjack@gmail.com
### Updated: 6 August 2017

## Requirements:
- PHP 7.0, mysqli module

## Installation:

- Install PHP MySQL module if not already installed (e.g. sudo apt-get install php7.0-mysqli )
- Create a MySQL database.

## Example usage:
- php user_upload.php -u john -p password


## Command line options:
<pre>
--file [csv file name] - this is the name of the CSV to be parsed (default users.csv if not specified)
--create_table - this will cause the MySQL users table to be built (and no further
action will be taken)
--dry_run - this will be used with the --file directive in the instance that we want to run the
script but not insert into the DB. All other functions will be executed, but the database won't
be altered.
-u - MySQL username
-p - MySQL password
-h - MySQL host (default localhost if not specified)
-- dbname - specify a DB name (default catalystUsers if not specified)
--help – output this help
</pre>
