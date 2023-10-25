<?php
session_start();
include "dbConnection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    
    // Changing the status of the user (from 0 to 1)
    $query = $db->prepare("UPDATE users SET status = 1 WHERE email = ?");
    $query->bindParam(1, $email);
    $query->execute();
    
    // Get the username from the database
    $query = $db->prepare("SELECT username FROM users WHERE email = ?");
    $query->bindParam(1, $email);
    $query->execute();
    $username = $query->fetchColumn();
    
    echo "Username: " . $username;
    

    // EMAIL
    try {
        $to = $email;
        $subject = 'Welcome to Major Blog';
        $message =
            "<html>
                <head>
                    <title>Document</title>
                </head>
                <body>
                    <h1>Hello $username</h1>
                    <pre>
                        <p>Welcome to Major Blog. You can now login and post on our website</p>
                      <p>Click this link to login to your account <a href='http://5326425.herzingmontreal.ca/Lab2/index.php'>Lab 3 - Login Page</a></p>
                        <p>Regards,</br> From: Admin</p>
                    </pre>
                </body>
            </html>";

        $headers = 'From: h5326425@5326425.herzingmontreal.ca' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';

        mail($to, $subject, $message, $headers);

        echo "<script>alert('Your registration is completed and your account is active. You can now to start chat. Log in and send your comment.');</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    } catch (Exception $e) {
        echo "<script>alert('Confirmation Email failed');</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    }

    $_SESSION['successMessage'] = "You just activated your account!!";
    error_log("Redirecting to index.php after successful registration");
    
} else {
    echo "No email found in session";
    header("Location: index.php");
    exit();
}

?>
