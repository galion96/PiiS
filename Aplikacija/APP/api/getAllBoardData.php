<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
if(isset($_GET["id_waspmote"])){
$id_waspmote=$_GET["id_waspmote"];
if($db->connect('127.0.0.1','root','','pis_project')){
    getAllBoardData($id_waspmote);
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
function getAllBoardData($id_waspmote){

    global $db;
	$db->doQuery("SELECT name FROM waspmote WHERE id=$id_waspmote");
	$serial_id=mysqli_fetch_assoc($db->loadObjectList());
   $rows["name"]=$serial_id["name"];
	$sensors=getAllSensorsFromBoard($id_waspmote);
	foreach ($sensors as $sensor){

		$rows["data"][$sensor["name"]]["readings"]=getAllSensorData($sensor["id"],$id_waspmote);
        $rows["data"][$sensor["name"]]["measure_unit"]=$sensor["measure_unit"];
		$rows["data"][$sensor["name"]]["id_sensor"]=$sensor["id"];
}
	echo json_encode($rows);
}


function getAllSensorsFromBoard($id_waspmote){
	global $db;
	   $db->doQuery("SELECT sensor.id,sensor.name,measure_unit FROM sensor
					JOIN sensor_waspmote sw on sensor.id=sw.id_sensor
					JOIN  waspmote w on w.id=sw.id_waspmote
					WHERE id_waspmote=$id_waspmote
					GROUP BY sensor.id");

   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows[]=$r;
   }
  // var_dump($rows);
   return $rows;
}

function getAllSensorData($id_sensor,$id_waspmote){
	global $db;
	$db->doQuery("SELECT reading_time,reading_value
							FROM sensor_waspmote JOIN sensor s on s.id=sensor_waspmote.id_sensor 
							WHERE s.id=$id_sensor AND id_waspmote=$id_waspmote
							ORDER BY reading_time asc
							LIMIT 15");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
	
       $rows[]=$r;
   }

   // var_dump($rows);
   return $rows;
  
}