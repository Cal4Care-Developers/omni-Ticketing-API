<?php
//error_reporting(0);
$mysqli = new mysqli("assaabloy-do-user-7996996-0.b.db.ondigitalocean.com:25060","doadmin","aggibgqsor10xpdy","assaabloycc_new");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}else{
	echo "connected";
}
?>
