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

  $helpText = "\r\n--file [csv file name] - this is the name of the CSV to be parsed
  --create_table - this will cause the MySQL users table to be built (and no further
  action will be taken)
  --dry_run - this will be used with the --file directive in the instance that we want to run the
  script but not insert into the DB. All other functions will be executed, but the database won't
  be altered.
  -u - MySQL username
  -p - MySQL password
  -h - MySQL host
  --help – output this help \r\n";

  print $helpText;


}

function loadCSV ($filename) {

}

function connectDB ($username, $password, $host) {

}

function createTable() {

}

function run() {

  /*
  The main function of the script
  */

  $options = getopt("u:p:h:",  array("dry_run", "file:", "create_table", "help"));

  if (array_key_exists ("help", $options)) {
    // if help in command line options, display help then exit

    help();

    return;

  }

  if (array_key_exists  ("file", $options)) {
    $CSVfile = options("file");
  }
  else {
    $CSVfile = "users.csv"; // default file name to use if none specified
  }
}

?>
