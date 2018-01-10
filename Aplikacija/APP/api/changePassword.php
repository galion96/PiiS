<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
$conn;
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
	if(isset($_POST["password"])){
if($conn=$db->connect('127.0.0.1','root','','pis_project')){
	$password=mysqli_real_escape_string($conn,$_POST['password']);
    changePassword($password);

}
	else {
	$obj = (object) [
    'error' => 'No internet connection'];
echo json_encode(($obj));
}
}

	else {
	$obj = (object) [
    'error' => 'Please fill in your password'];
echo json_encode(($obj));
	}
}

else{
	header('Location: /piis/web/login.php');
}

function changePassword($password){
    global $db;
	global $conn;
	$password=md5($password);
	$db->doQuery("UPDATE user
				SET password = '$password'");
   $obj=$db->loadObjectList();;
   if(mysqli_affected_rows($conn)==1){
	   	$obj = (object) [
    'succes' => 'Password was updated'];
	echo json_encode(($obj));
   }
   else{
		   	$obj = (object) [
    'error' => 'You have entered same password'];
	echo json_encode(($obj));
   }

}