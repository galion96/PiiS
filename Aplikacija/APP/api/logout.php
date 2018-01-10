<?php 
session_start();
unset($_SESSION["loggedIn"]);
header("Location: /PiiS/web/login.php");