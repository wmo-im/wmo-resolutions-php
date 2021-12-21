<?php

//********************************Connection DB*********************************
/*connection*/


$username = getenv('WMORESOLUTIONS_USER');
$host     = getenv('WMORESOLUTIONS_HOST');
$password = getenv('WMORESOLUTIONS_PWD');
$database = getenv('WMORESOLUTIONS_DB');


// Opens a connection to a MySQL server
$mysqli = new mysqli($host,$username,$password,$database);

// Check connection
if ($mysqli -> connect_errno) {
  echo " MYSQL-ERRNO ".$mysqli -> connect_errno;
  echo " Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

/* end connection*/
?>
