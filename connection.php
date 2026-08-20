<?php
$SERVER="LocalHost";
$username="root";
$password="root";
$database="shopdatabase";

$connection = new mysqli($SERVER,$username,$password,$database);

if ($connection->connect_error){
	die("Connection Fsiled". $connection->connect_error);
}
//echo("Database connected succesfully")

?>