<?php
session_start();


// Unsetting variables
unset($_SESSION['logged']);
unset($_SESSION['usernameLogin']);
unset($_SESSION['passwordLogin']);
unset($_SESSION['avatar']);

header("Location: index.php");
exit();

?>


