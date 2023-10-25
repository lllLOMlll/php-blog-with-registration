<?php
session_start();
include "dbConnection.php";


// Query to get all users
$query = $db->query("SELECT * FROM users ORDER BY id ASC");
$users = $query->fetchAll(PDO::FETCH_ASSOC);

// Quere to get all posts
$query = $db->query("SELECT * FROM posts ORDER BY id DESC");
$posts = $query->fetchAll(PDO::FETCH_ASSOC);	


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lab 2</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
   <?php 
   $email = urldecode($_GET['email']);
   $_SESSION['email'] = $email;
   
   
   ?>
	<div class="containter">
	    
		<!-- ********************************* TOP IMAGE *********************************** -->
		<div class="text-center">
			<img class="image-logo" src="images/dragon.jpg" alt="dragon">
		</div>
		
	    <!--Mail button-->
	    <a href="validate.php" class="btn btn-primary col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-4 offset-lg-4">Confirm your email address</a>
</div>



		<!-- Closing tag of the <div class="row">. I need that to have the Sending message and blog displayed correctly -->
		</div>



		<!-- Closing tag for the <div class="containter"> -->
		</div>
		
		
		<script src="js/bootstrap.bundle.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/jquery.js"></script>
		<script src="js/custom.js"></script>
	</body>
	</html>