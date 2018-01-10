<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
session_start();
header("Content-type:application/json");
$conn;

if(isset($_SESSION["loggedIn"])){
if(isset($_POST["id_waspmote"]) && isset($_POST["id_sensor"])&&isset($_POST["value"])){	

if($conn=$db->connect('127.0.0.1','root','','pis_project')){
	$id_waspmote=$_POST["id_waspmote"];
	$id_sensor=$_POST["id_sensor"];
	$value=$_POST["value"];
    $flag=addNewRule($id_waspmote,$id_sensor,$value);
	unset($_POST);
  if($flag!=1){
	  global $conn;
	$obj = (object) [
    'error' => "error while adding new rule".mysqli_error ( $conn )];
  }
  else{
	  $obj = (object) [
	  
    'success' =>"Successfully added new rule"];
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

function addNewRule($id_waspmote,$id_sensor,$value){
    global $db;
	global $conn;
	//echo mysqli_error($conn);
	$db->doQuery("INSERT INTO rules (id_sensor,id_waspmote,value)
				  VALUES($id_sensor,$id_waspmote,$value)");
   $obj=$db->loadObjectList();
   return $db->getNumRows();
}