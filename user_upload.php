<?php
/*

Author: Jack Stinchcombe
email: stinchjack@gmail.com

*/

run();

function help() {
  /*
    Help output function
  */

  $helpText = "--file [csv file name] - this is the name of the CSV to be parsed
  --create_table - this will cause the MySQL users table to be built (and no further
  action will be taken)
  --dry_run - this will be used with the --file directive in the instance that we want to run the
  script but not insert into the DB. All other functions will be executed, but the database won't
  be altered.
  -u - MySQL username
  -p - MySQL password
  -h - MySQL host
  --help – output this help ";

  print $helpText;


}

function loadCSV ($filename) {

}

function connectDB ($username, $password, $host) {

}

function createTable() {

}

function run() {

  $options = getopt("u:p:h:",  array("dry_run", "file:", "create_table"));

  print_r ($options);
}

?>
