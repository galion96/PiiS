<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
if(isset($_SESSION["id_waspmote"]) && isset($_GET["id_sensor"])){
$id_waspmote=$_SESSION["id_waspmote"];
$id_sensor=$_GET["id_sensor"];
if($db->connect('127.0.0.1','root','','pis_project')){
    getSensorDataById($id_waspmote,$id_sensor);
}
}
else {
	$obj = (object) [
    'error' => 'Please select sensor name and board name and sensor data'];
echo json_encode(($obj));
}
}
else{
	header('Location: /piis/web/login.php');
}
function getSensorDataById($id_waspmote,$id_sensor){

    global $db;
	$db->doQuery("SELECT name FROM waspmote WHERE id=$id_waspmote");
	$serial_id=mysqli_fetch_assoc($db->loadObjectList());
    $db->doQuery("SELECT name,reading_count,reading_time,reading_value 
							FROM sensor_waspmote JOIN sensor s on s.id=sensor_waspmote.id_sensor 
							WHERE s.id=$id_sensor AND id_waspmote=$id_waspmote
							ORDER BY reading_time");
   $obj=$db->loadObjectList();
   $rows=[];
   $rows["name"]=$serial_id["name"];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
   }

   echo json_encode(($rows));
}

