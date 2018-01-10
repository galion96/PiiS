<?php
session_start();
define('__ROOT__', dirname(dirname(__FILE__))); 
if(isset($_SESSION["loggedIn"])){
	header("Location: ./dashboard.php");
}
else{
include_once "./web2/login.html";
}