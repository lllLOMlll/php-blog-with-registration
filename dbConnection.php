<?php
// PDO Php Data Object
// PDO is an interface. We have instruction (not function)
// We have other methods we can use

$host = "localhost";
$dbname = "module2lab4";
$user = "root";
$password = "";
$db = null;

// Try to connect the database using the provided credentials
// if the connections works, it will keep the presitence, else it will throw the error


try {
	#PDO_<MYSQL is a driver that implements the PHP DATA OBJECT (PDO) interface to enable access from PHP to MYSQL database
	$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
	$db->exec("set names utf8");
} catch (Exception $e) {
	die("Database Connection Error : " . $e->getmessage());
}



?>