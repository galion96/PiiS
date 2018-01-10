<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
	
if($db->connect('127.0.0.1','root','','pis_project')){
    getAllBoards();

}
	else {
	$obj = (object) [
    'error' => 'Please type in board name'];
echo json_encode(($obj));
}
}
else{
	header('Location: /piis/web/login.php');
}

function getAllBoards(){
    global $db;
	$db->doQuery("SELECT *FROM waspmote");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
   }
 
   echo json_encode(($rows));
}