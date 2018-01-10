<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
session_start();
header("Content-type:application/json");
$conn;

if(isset($_SESSION["loggedIn"])){
if(isset($_POST["id_waspmote"]) && isset($_POST["id_room"])){	

if($conn=$db->connect('127.0.0.1','root','','pis_project')){
	$id_waspmote=$_POST["id_waspmote"];
	$id_room=$_POST["id_room"];
    $flag=saveBoardToRoom($id_waspmote,$id_room);
	unset($_POST);
  if($flag!=1){
	  global $conn;
	$obj = (object) [
    'error' => "error while saving board to room".mysqli_error ( $conn )];
  }
  else{
	  $obj = (object) [
	  
    'success' =>"Successfully saved board to room"];
  }
}
}
else{
	  $obj = (object) [
	  
    'error' => "error, please fill in all the data required"];
}
echo json_encode(($obj));
}
else{
	header('Location: /piis/web/login.php');
}

function saveBoardToRoom($id_waspmote,$id_room){
    global $db;
	global $conn;
	//echo mysqli_error($conn);
	$db->doQuery("UPDATE waspmote 
				  SET id_room=$id_room 
				  WHERE id=$id_waspmote");
   $obj=$db->loadObjectList();
   return $db->getNumRows();
}