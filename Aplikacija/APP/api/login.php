<?php 
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
session_start();
//header("Content-type:application/json");
if(isset($_POST["username"]) && isset($_POST["password"])){	
	if($conn=$db->connect('127.0.0.1','root','','pis_project')){
$username=mysqli_real_escape_string($conn,$_POST["username"]);
$password=mysqli_real_escape_string($conn,$_POST["password"]);
$password=md5($password);

$db->doQuery("SELECT id from user where username='$username' AND password='$password'");
$obj=$db->loadObjectList();
if(mysqli_num_rows($obj)==1){
$_SESSION['loggedIn']   = 'true';
	  $obj = (object) [
    'loggedIn' =>true,
	'url'=>"window.location.href='/piis/web/dashboard.php'"];
//echo "window.location.href='/piis/web/dashboard.html'".json_encode($obj);
}
else{
		  $obj = (object) [
    'loggedIn' =>false,
	'url'=> "window.location.href='/piis/web/login.php'"];
	//echo "window.location.href='/piis/web/login.html'";
}
}
}
else{
			  $obj = (object) [
    'loggedIn' =>false,
	'url'=> "window.location.href='/piis/web/login.php'"];
//echo "window.location.href='/piis/web/login.php'";
}
echo json_encode($obj);