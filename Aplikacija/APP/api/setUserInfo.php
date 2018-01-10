<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
$conn;
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
	if(isset($_POST['id']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST["name"]) && isset($_POST["surname"])){
if($conn=$db->connect('127.0.0.1','root','','pis_project')){
	$id=mysqli_real_escape_string($conn,$_POST['id']);
	$username=mysqli_real_escape_string($conn,$_POST['username']);
	$email=mysqli_real_escape_string($conn,$_POST['email']);
	$name=mysqli_real_escape_string($conn,$_POST['name']);
	$surname=mysqli_real_escape_string($conn,$_POST["surname"]);
    setUserInfo($id,$username,$email,$name,$surname);

}
	else {
	$obj = (object) [
    'error' => 'No internet connection'];
echo json_encode(($obj));
}
}

	else {
	$obj = (object) [
    'error' => 'Please fill all data'];
echo json_encode(($obj));
	}
}

else{
	header('Location: /piis/web/login.php');
}

function setUserInfo($id,$username,$email,$name,$surname){
    global $db;
	global $conn;
	$db->doQuery("UPDATE user
				SET username = '$username', email = '$email',name='$name',surname='$surname' WHERE id=$id");
   $obj=$db->loadObjectList();;
   if(mysqli_affected_rows($conn)==1){
	   	$obj = (object) [
    'succes' => 'Info was updated'];
	echo json_encode(($obj));
   }
   else{
		   	$obj = (object) [
    'error' => 'You have entered same info'];
	echo json_encode(($obj));
   }

}