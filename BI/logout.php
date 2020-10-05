<?php
session_start();
unset($_SESSION['logged_in']);
unset($_SESSION['UserName']);
// kill session variables
setcookie('JOBPORTAL', $_SESSION["UserFullNames"], time() - 10);
header('Location: index.php');
?>

