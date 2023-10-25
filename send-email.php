<?php
session_start();

if (isset($_SESSION['username'], $_SESSION['email']) && !empty($_SESSION['username']) && !empty($_SESSION["email"])) {

    try {
        $email = $_SESSION['email'];
        $username = $_SESSION['username'];

        $to = $email;
        $subject = 'Confirmation Email';
        $message =
            "<html>
                <head>
                    <title>Document</title>
                </head>
                <body>
                    <h1>Hello $username</h1>
                    <pre>
                        <p>Thank you for registration in our website. </p>
                        <p>Click this link to visit our website <a href='http://5326425.herzingmontreal.ca/Lab2/validation.php'>Lab 3 Validation Page</a></p>
                        <p>Regards,</br> From: Admin</p>
                        <?php
                            $email = $_SESSION['email'];
                            $username = $_SESSION['username'];
                        ?>
                    </pre>
                </body>
            </html>";

        $headers = 'From: h5326425@5326425.herzingmontreal.ca' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';

        mail($to, $subject, $message, $headers);

        echo "<script>alert('Your registration was completed and your account is active to start chat. Log in and send your comment.');</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    } catch (Exception $e) {
        echo "<script>alert('Confirmation Email failed');</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    }
}
?>
