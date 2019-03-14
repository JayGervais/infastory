<?php
ob_start(); // turns on output buffering
session_start();

$timezone = date_default_timezone_set("Canada/Mountain");

$con = mysqli_connect("localhost", "root", "root", "infastory"); // connection to db

if(mysqli_connect_errno()) {
	echo "failed connection: " . mysqli_connect_errno(); // check for connection errors
}

?>