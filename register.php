<?php
session_start();
include "dbConnection.php";


if (isset($_POST['register'])) {

    // FORM
    $username = trim($_POST['usernameRegister']);
    $_SESSION['usernameRegister'] = $username;
    $email = trim($_POST['emailRegister']);
    $_SESSION['emailRegister'] = $email;
    $passwordRegistration = trim($_POST['passwordRegister']);
    $_SESSION['passwordRegister'] = $passwordRegistration;
    $confirmPassword = trim($_POST['passwordConfirmRegister']);
    $_SESSION['passwordConfirmRegister'] = $confirmPassword;
    $avatarUpload = $_FILES['avatarUploadRegister'];

    // REGISTRATION IN PROGRESS
    $_SESSION['registrationInProgress'] = true;
    
    // VALIDATION
    $errorMessageRegister = "";

    // All fields empty
if (empty($username) && empty($email) && empty($passwordRegistration) && empty($confirmPassword) && empty($avatarUpload['name'])) {
    $errorMessageRegister .= "You must complete all fields to register";
    $_SESSION['errorMessageRegister'] = $errorMessageRegister;
    header("Location:index.php");
    exit();
}

    
    // Username
    if (strlen($username) < 4 || strlen($username) > 20) {
        $errorMessageRegister .= "Username : must be between 4 and 20 characters long. <br>";
    } else {
        $query = $db->prepare("SELECT * FROM users WHERE username = :username");
        $query->bindValue(':username', $username);
        $query->execute();
    
        if ($query->rowCount() > 0) {
            $errorMessageRegister .= "Username : this username is already registered. Please choose another. <br>";
        }
    }

    // Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $errorMessageRegister .= "Email : please enter a valid email address that is less than 100 characters long. <br>";
    } else {
        $query = $db->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindValue(':email', $email);
        $query->execute();
        
        if ($query->rowCount() > 0) {
            $errorMessageRegister .= "Email : this email is already registered. Please choose another. <br>";
        }
    }

    // Password
    if (strlen($passwordRegistration) < 8 || !preg_match("/[A-Z]/", $passwordRegistration) || !preg_match("/[!@#\$%\^&*()]/", $passwordRegistration)) {
        $errorMessageRegister .= "Password must be at least 8 characters long, contain at least one uppercase letter, and at least one special character. <br>";
    }

    // Confirm password
    if ($passwordRegistration != $confirmPassword) {
        $errorMessageRegister .= "Confirm Password : your password and your confirmation password do not match. <br>";
    }

    // Avatar
    if ($avatarUpload['error'] != 0) {
        $errorMessageRegister .= "Avatar : there was an error uploading your file. <br>";
    } else {
        $extensionsAllowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $avatarExtension = pathinfo($avatarUpload['name'], PATHINFO_EXTENSION);
        if (!array_key_exists($avatarExtension, $extensionsAllowed)) {
            $errorMessageRegister .= "Invalid file format. Please upload a valid JPG, JPEG, GIF, or PNG file. <br>";
        }

        // Verify file size (1MB max)
        $maxsize = 1048576;
        if ($avatarUpload['size'] > $maxsize) {
            $errorMessageRegister .= "File size is larger than the allowed limit (Max = 1MB). <br>";
        }
    }

// ERROR for each field (RED RECTANGLES)
$_SESSION['fieldInputFeedbackUsername'] = strpos($errorMessageRegister, 'Username') !== false ? 'error-field' : 'success-field';
$_SESSION['fieldInputFeedbackEmail'] = strpos($errorMessageRegister, 'Email') !== false ? 'error-field' : 'success-field';
$_SESSION['fieldInputFeedbackPassword'] = strpos($errorMessageRegister, 'Password') !== false ? 'error-field' : 'success-field';
$_SESSION['fieldInputFeedbackConfirmPassword'] = strpos($errorMessageRegister, 'Confirm Password') !== false ? 'error-field' : 'success-field';
$_SESSION['fieldInputFeedbackAvatar'] = strpos($errorMessageRegister, 'Avatar') !== false ? 'error-field' : 'success-field';




    // ERROR!
    if (!empty($errorMessageRegister)) {
        $_SESSION['errorMessageRegister'] = $errorMessageRegister;
        header("Location: index.php");
        exit();
    }


    // SUCCESS!
    // Save the avatar in the 'avatars' folder
    $avatarUsername = strtolower($username); // Converting $username to lowercase
    $avatarDirectory = "avatars/";
    $avatarFilenamePath = $avatarDirectory . $avatarUsername . "." . $avatarExtension;
    move_uploaded_file($avatarUpload['tmp_name'], $avatarFilenamePath);

    // Add the user to the database
    $query = $db->prepare("INSERT INTO users (username, email, password, avatar) VALUES (:username, :email, :password, :avatar)");
    $query->bindValue(':username', $username);
    $query->bindValue(':email', $email);
    $passwordHash = password_hash($passwordRegistration, PASSWORD_DEFAULT);
    $query->bindValue(':password', $passwordHash);
    $query->bindValue(':avatar', $avatarFilenamePath);
    $query->execute();
    
    // EMAIL
    //   Using .urlencode to pass the value of the email t the validation.php page
    // Source : https://www.php.net/manual/en/function.urlencode.php
    if (isset($_POST['usernameRegister'], $_POST['emailRegister']) && !empty($_POST['usernameRegister']) && !empty($_POST["emailRegister"])) {

    try {
        $email =  $_SESSION['emailRegister'];
        $username = $_SESSION['usernameRegister'];

        $to = $email;
        $subject = 'Please confirm your Email address';
        $message =
            "<html>
                <head>
                    <title>Document</title>
                </head>
                <body>
                    <h1>Hello $username</h1>
                    <pre>
                        <p>Thank you for registration in our website. </p>
                      <p>Click this link to validate your account <a href='//http://localhost/Herzing/BlockB/Module4/Labs/Lab4/validation.php?email=".urlencode($email)."'>Lab 3 - Validate Account</a></p>
                        <p>Regards,</br> From: Admin</p>
                    </pre>
                </body>
            </html>";

        $headers = 'From: h5326425@5326425.herzingmontreal.ca' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';

        mail($to, $subject, $message, $headers);

        echo "<script>alert('Your registration is completed but you need to activate your account. We send an activation link to your email address.');</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    } catch (Exception $e) {
        echo "<script>alert('Confirmation Email failed');</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    }
}

    $_SESSION['successMessage'] = "Successful registration!";
    unset($_SESSION['registrationInProgress']);
    unset($_SESSION['errorUsername']);
    unset($_SESSION['errorEmail']);
    unset($_SESSION['errorPassword']); 
    unset($_SESSION['errorAvatar']);
    unset($_SESSION['fieldInputFeedbackUsername']);
    unset($_SESSION['fieldInputFeedbackEmail']); 
    unset($_SESSION['fieldInputFeedbackPassword']);
    unset($_SESSION['fieldInputFeedbackConfirmPassword']); 
    unset($_SESSION['fieldInputFeedbackAvatar']);

    
    error_log("Redirecting to index.php after successful registration");
    header("Location: index.php");
    exit();
}
?>
