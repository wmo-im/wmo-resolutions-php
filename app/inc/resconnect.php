<?php

//********************************Connection DB*********************************
/*connection*/


//$username = getenv('WMORESOLUTIONS_USER');
//$host     = getenv('WMORESOLUTIONS_HOST');
//$password = getenv('WMORESOLUTIONS_PWD');
//$database = getenv('WMORESOLUTIONS_DB');

$username = $_ENV['WMORESOLUTIONS_USER'];
$host     = $_ENV['WMORESOLUTIONS_HOST'];
$password = $_ENV['WMORESOLUTIONS_PWD'];
$database = $_ENV['WMORESOLUTIONS_DB'];

// Opens a connection to a MySQL server
$mysqli = new mysqli($host,$username,$password,$database);

// Check connection
if ($mysqli -> connect_errno) {
  echo " MYSQL-ERRNO ".$mysqli -> connect_errno;
  echo " username: ".$username;
  echo " host: ".$host;
  echo " database: ".$database;
  echo " Failed to connect to MySQL: " . $mysqli -> connect_error;
  echo getenv('SHELL');
  foreach (getenv() as $key => $value) {
    echo $key . ' - ' . $value;
  }
  exit();
}

/* end connection*/
?>
