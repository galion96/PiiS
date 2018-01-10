<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
//if(isset($_SESSION["loggedIn"])){
	if(isset($_GET["id_room"])){
		$id_room=$_GET["id_room"];
if($db->connect('127.0.0.1','root','','pis_project')){
    getBoardsFromRoom($id_room);

}
	else {
	$obj = (object) [
    'error' => 'No internet connection'];
echo json_encode(($obj));
}
}
else{
	$obj = (object) [
    'error' => 'Please select your room'];
echo json_encode(($obj));
}//}
/*else{
	header('Location: /piis/web/login.php');
}*/

function  getBoardsFromRoom($id_room){
    global $db;
	$db->doQuery("SELECT *FROM waspmote WHERE id_room=$id_room");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
   }
   $rows["callback"]="displayItems";
   $rows["method"]="getSensorsFromBoard&id_waspmote=";
   $rows["containerName"]="boardContainer";
   echo json_encode(($rows));
}