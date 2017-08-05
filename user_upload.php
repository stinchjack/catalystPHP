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
  -- dbname - specify a DB name
  --help – output this help \r\n";

  print $helpText;


}

function loadCSV ($filename) {

  if (!$filename) {
    return false;
  }

  /*Load data from CSV*/
  $file = fopen ($filename, "r");
  if (!$file) {
    return false;
  }

  $rows = array();

  while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

      array_push ($rows, $data);

  }

  fclose ($file);

  return $rows;
}

function cleanData ($rows) {
  // Cleans and validates data from CSV - assumes items in each row is first
  // name, surname and email address

  // Assumes first row is column headers
  array_shift ($rows);

  $cleanedRows = array();

  foreach ($rows as $row) {

    $row[2] = trim ($row[2]); // trim spaces so filter_var can do its job

    // Check for email address and skip row if not valid
    if (filter_var($row[2], FILTER_VALIDATE_EMAIL)) {

      // Make sure first name and surname fields have first letter capital
      $row[0] =  ucfirst (trim(strtolower($row[0])));
      $row[1] =  ucfirst (trim(strtolower($row[1])));


      array_push ($cleanedRows, Array ($row));
    }

    else {
      print "\r\n Email address $row[2] not valid - this row will not be inserted into table  \r\n";
    }
  }

  return $cleanedRows;
}

function connectDB ($username, $password, $host, $dbname) {
  /*Connect to Database*/

  $link = mysqli_connect($host, $username, $password, $dbname);

  //Display error info on failure
  if (!$link) {

      echo "Error: Unable to connect to MySQL." . PHP_EOL;
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;

      return false;
  }

  return $link;

}

function checkTable($link, $DBtable) {
  //check specified DB table exists
  $result = mysqli_query ($link,  "SELECT 1 FROM testtable LIMIT 1;");

  if (!$result) {
    print "\r\n Table $DBtable does not exist\r\n";
    return false;
  }
  else {
    return true;
  }
}

function createTable() {


  $sql =  "
    CREATE TABLE users IF NOT EXISTS
  ";

  $result = mysqli_query ($link,  $sql);

}

function run() {

  /*
  The main function of the script
  */

  //get options
  $options = getopt("u:p:h:",  array("dry_run", "file:", "create_table", "help", "dbname:"));

  if (array_key_exists ("help", $options)) {
    // if help in command line options, display help then exit
    help();
    return;
  }


  //process file name from command line
  if (array_key_exists  ("file", $options)) {
    $CSVfile = options["file"];
  }
  else {
    $CSVfile = "users.csv"; // default file name to use if none specified
  }

  // get MYSQL user name from command line
  if (array_key_exists  ("u", $options)) {
    $DBuser = $options["u"];
  }


  // get MYSQL user name from command line
  if (array_key_exists  ("p", $options)) {
    $DBpassword = $options["p"];
  }


  // get MYSQL hostname from command line
  if (array_key_exists  ("h", $options)) {
    $DBhost= $options["h"];
  }
  else {
    $DBhost= "localhost"; // default if no host specified
  }

  // get MYSQL Database from command line
  if (array_key_exists  ("dbname", $options)) {
    $DBname= $options["dbname"];
  }
  else {
    $DBname= "catalystUsers"; // default if no DB name specified
  }


  // get dry_run flag from
  $dry_run = array_key_exists  ("dry_run", $options);


  // get create_table flag from
  $create_table = array_key_exists  ("create_table", $options);

  if (!$DBuser || !$DBpassword) {
    print "\r\n MySQL username or password not set \r\n";
    help();
    return;
  }


  // Connect to MySQL
  $DBconn = connectDB ($DBuser, $DBpassword, $DBhost, $DBname);

  if (!$DBconn) {
    print "Could not connect to DB\r\n";

    return;
  }

  //Check DB table exists
  $tableExists = checkTable ($DBconn, "users");


  // fail if table does not exist and table if not to be created
  if (!$tableExists && !$create_table) {
    help();
    return;
  }

  //if --create_table specified, create the table if it doesn't exist
  if (!$tableExists && $create_table) {

    $result = createTable();
    if ($result) {
      print "\r\nTable created - no data inserted\r\n";
    }
    else {
      print "\r\nCould not create table\r\n";
      echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
      echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    }
    return;
  }

  // Load CSV data
  $data = loadCSV ($CSVfile);

  if (!$CSVfile) {
    print "Could not load CSV $CSVfile \r\n";
    return;
  }

  // Clean CSV data
  $data = cleanData ($data);



}

?>
