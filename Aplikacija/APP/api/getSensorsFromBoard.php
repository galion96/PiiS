<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();

if(isset($_SESSION["loggedIn"])){
if(isset($_GET["id_waspmote"])){
	$id_waspmote=$_GET["id_waspmote"];
	$_SESSION["id_waspmote"]=$id_waspmote;
if($db->connect('127.0.0.1','root','','pis_project')){
    getSensorsFromBoard($id_waspmote);
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
function getSensorsFromBoard($id_waspmote){
    global $db;
	$db->doQuery("SELECT name FROM waspmote WHERE id=$id_waspmote");
	$serial_id=mysqli_fetch_assoc($db->loadObjectList());
    $db->doQuery("SELECT sensor.id,sensor.name FROM sensor
					JOIN sensor_waspmote sw on sensor.id=sw.id_sensor
					JOIN  waspmote w on w.id=sw.id_waspmote
					WHERE id_waspmote=$id_waspmote
					GROUP BY sensor.id");

   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
   }
   $rows["name"]=$serial_id["name"];
   $rows["method"]="getSensorDataById&id_waspmote=".$id_waspmote."&id_sensor=";
   $rows["callback"]="displayGraph";
   $rows["containerName"]="sensorContainer";
   echo json_encode(($rows));
}