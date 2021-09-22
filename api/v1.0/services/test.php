<?php
//error_reporting(0);
$mysqli = new mysqli("db-mysql-sgp1-11187-do-user-7996996-0.b.db.ondigitalocean.com:25060","admin_usa1","mntsdc3krnstilcc","omni_ticketing");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}else{
	echo "connected";
}
?>