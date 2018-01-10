<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
if(isset($_GET["id_waspmote"]) && isset($_GET["start_date"]) && isset($_GET["end_date"]) && isset($_GET["id_sensor"])){
$id_waspmote=$_GET["id_waspmote"];
$start_date=$_GET["start_date"];
$end_date=$_GET["end_date"];
$id_sensor=$_GET["id_sensor"];
if($db->connect('127.0.0.1','root','','pis_project')){
	/*$db->doQuery("SELECT serial_id FROM waspmote WHERE id=$id_waspmote");
	$serial_id=mysqli_fetch_assoc($db->loadObjectList());
    $rows["serial_id"]=$serial_id["serial_id"];
	$db->doQuery("SELECT measure_unit,sensor_name FROM sensor where id=$id_sensor");
	$measure_unit=mysqli_fetch_assoc($db->loadObjectList());
	$rows["measure_unit"]=$measure_unit["measure_unit"];
	$rows["sensor_name"]=$measure_unit["sensor_name"];*/
    $rows["data"]=getAllSensorData($id_sensor,$id_waspmote,$start_date,$end_date);
	echo json_encode ($rows);
}
}
else {
	$obj = (object) [
    'error' => 'Please board name'];
echo json_encode(($obj));
}
}
else{
	header('Location: /piis/web/login.php');
}


function getAllSensorData($id_sensor,$id_waspmote,$start_date,$end_date){
	global $db;

	$db->doQuery("SELECT reading_time,reading_value
							FROM sensor_waspmote JOIN sensor s on s.id=sensor_waspmote.id_sensor 
							WHERE s.id=$id_sensor AND id_waspmote=$id_waspmote and reading_time>='$start_date' AND reading_time<DATE_ADD('$end_date', INTERVAL 1 DAY)
							ORDER BY reading_time asc
							");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
	
       $rows[]=$r;
   }

   // var_dump($rows);
   return $rows;
  
}