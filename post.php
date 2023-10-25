<?php 
session_start();
include "dbConnection.php";

if (isset($_POST['post'])){
	$newBlogPost = $_POST['blogPost'];
	$username = $_SESSION['usernameLogin'];
	$errorMessage = "";


	if (empty($newBlogPost)){
		$errorMessage .= "You cannot post an empty message.<br>";
	}

	if (strlen($newBlogPost) > 500) {
		$errorMessage .= "Your post cannot be over 500 characters.<Br>";
	}


    // ERROR
	if (!empty($errorMessage)) {
		$_SESSION['errorMessage'] = $errorMessage;
		header("Location: index.php");
		exit();
	}

    // SUCCESS REGISTRATION
  	// Check for the avatar path
	$sql = "SELECT * FROM users WHERE username = :username";
	$result = $db->prepare($sql);
	$result->bindParam(':username', $username);
	$result->execute();

	if ($result->rowCount() > 0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$avatarFilePath = $row['avatar'];

		if (empty($errorMessage)) {
  	// Add the post to the database
			$query = $db->prepare("INSERT INTO posts (username, avatar, comment) VALUES(:username, :avatar, :comment)");
			$query->bindValue(':username', $username);
			$query->bindValue(':avatar', $avatarFilePath);
			$query->bindValue(':comment', $newBlogPost);

			$query->execute();

		}


		unset($_SESSION['errorMessageRegister']);
		header("Location: index.php");
		exit();
	}
}
?>