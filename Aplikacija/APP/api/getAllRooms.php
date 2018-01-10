<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
	
if($db->connect('127.0.0.1','root','','pis_project')){
    getAllRooms();

}
	else {
	$obj = (object) [
    'error' => 'No internet connection'];
echo json_encode(($obj));
}
}
else{
	header('Location: /piis/web/login.php');
}

function getAllRooms(){
    global $db;
	$db->doQuery("SELECT *FROM room");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
   }
 
   echo json_encode(($rows));
}