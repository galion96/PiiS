<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
session_start();
header("Content-type:application/json");
if(isset($_SESSION["loggedIn"])){
if(isset($_GET["term"])){
	$term=$_GET["term"];
if($db->connect('127.0.0.1','root','','pis_project')){
    getBoardByName($term);
}
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

function getBoardByName($term){
    global $db;
    $db->doQuery("SELECT serial_id,id FROM waspmote  WHERE serial_id LIKE '%$term%'");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows[]=$r;
   }
   echo json_encode(($rows));

}