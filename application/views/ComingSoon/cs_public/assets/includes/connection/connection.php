<?php
require("constants.php");

// 1. Create a database connection
$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
if(!$connection){
	die("Database connection failed: " . mysql_error());
}

// 2. Select a database to use
$db_select = mysql_select_db(DB_NAME, $connection);
if(!$db_select) {
	die("Database connection failed: " . mysql_error());
}
?>