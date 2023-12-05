<?php
session_start();

//Fjerner alle session varaibler
$_SESSION = array();

//Ender session
session_destroy();

//Redirecter til login page
header('Location: login.php');
exit();
?>
