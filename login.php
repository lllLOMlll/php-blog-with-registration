<?php
session_start();
include "dbConnection.php";

if (isset($_POST['login'])) {
    $username = trim($_POST['usernameLogin']);
    $_SESSION['usernameLogin'] = $username;
    $password = trim($_POST['passwordLogin']);
    $_SESSION['passwordLogin'] = $password;

    // Using BINARY to make it case sensitive
    $sql = "SELECT * FROM users WHERE BINARY username = :username";
    $result = $db->prepare($sql);
    $result->bindParam(':username', $username);
    $result->execute();

    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $storedPassword = $row['password'];
        $storedStatus = $row['status'];

        if ($storedStatus == 0){
            $_SESSION['errorMessage'] = "You need to activate your account. We send you an email with the activation link.";
            header("Location: index.php");
            exit();
        }
        
         // SUCCESS
        if (password_verify($password, $storedPassword)) {  
            
            $query = $db->prepare("SELECT avatar FROM users WHERE username = :username");
$query->bindValue(':username', $_SESSION['usernameLogin']);
$query->execute();
$userData = $query->fetch(PDO::FETCH_ASSOC);
$_SESSION['avatar'] = $userData['avatar'];

            $_SESSION['successMessage'] = "Login successful!";
            $_SESSION['logged'] = true;

            

        // ERROR
        } else {
        	// Wrong password
            $_SESSION['errorMessage'] = "Incorrect username or password. Please try again.";
        }
    } else {
    		// User not found
        $_SESSION['errorMessage'] = "Incorrect username or password. Please try again.";
    }
}

header("Location: index.php");
exit();
?>