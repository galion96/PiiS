<?php
session_start();
define('__ROOT__', dirname(dirname(__FILE__)));
if(isset($_SESSION["loggedIn"])){
	include_once "./web2/dashboard.html";
}
else{
	header("Location: ./login.php");
}