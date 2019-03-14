<?php 
session_start();

setcookie('email', "", time() - 200, "/");
setcookie('password', "", time() - 200, "/");

session_destroy();
header("Location: ../../register.php");

?>