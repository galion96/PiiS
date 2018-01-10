<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
session_start();
header("Content-type:application/json");
$conn;

//if(isset($_SESSION["loggedIn"])){
if(isset($_POST["id"])){	

if($conn=$db->connect('127.0.0.1','root','','pis_project')){
	$id=$_POST["id"];
    $flag=deleteRoom($id);
	unset($_POST);
  if($flag!=1){
	  global $conn;
	$obj = (object) [
    'error' => "error while adding deleting room".mysqli_error ( $conn )];
  }
  else{
	  $obj = (object) [
	  
    'success' =>"Successfully deleted room"];
  }
}
}
else{
	  $obj = (object) [
	  
    'error' => "error, please fill in all the data required"];
}
echo json_encode(($obj));
/*}
else{
	header('Location: /piis/web/login.php');
}*/

function deleteRoom($id){
    global $db;
	global $conn;
	//echo mysqli_error($conn);
	$db->doQuery("DELETE FROM room 
				  WHERE id=$id");
   $obj=$db->loadObjectList();
   return $db->getNumRows();
}