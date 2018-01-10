<?php
session_start();
define('__ROOT__', dirname(dirname(__FILE__))); 
if(isset($_SESSION["loggedIn"])){
	include_once "./web2/obavijesti.html";
}
else{
	header("Location: ./login.php");
}