<?php
require_once('./database.php');
header("Content-type:application/json");
$data = json_decode(file_get_contents('php://input'), true);
//print_r($data);
$db= database::getInstance();
$id_sensor=0;
$id_board=0;
date_default_timezone_set('Europe/Sarajevo');
if($db->connect('127.0.0.1','root','','pis_project')){
    if(checkForNewBoard($data["boardInfo"]["serial_id"])==true){
        addNewBoard($data["boardInfo"]["serial_id"]);
    }
    if(checkForNewSensor($data["boardInfo"]["sensor_name"])==true){
        addNewSensor($data["boardInfo"]["sensor_name"]);
    }
    $reading_value= $data["data"]["reading_value"];
    $reading_count=$data["data"]["reading_count"];
    $date = date("Y-m-d H:i:s");
    $db->doQuery("INSERT INTO sensor_waspmote(id_sensor,id_waspmote,reading_value,reading_count,reading_time)
                       VALUES ($id_sensor,$id_board,$reading_value,$reading_count,'$date')");
}
else{
	echo "error connecting to database"; //TODO generirati neku poruku na početnu stranicu možda
}



function checkForNewBoard($serial_id){
    global $db;
    $db->doQuery("SELECT * FROM waspmote 
                      WHERE name='$serial_id'");
    $object=mysqli_fetch_assoc($db->loadObjectList());
    if($object==NULL){
        return true;
    }
    else {
        global $id_board;
        $id_board = $object["id"];
        return false;
        }
}

function addNewBoard($serial_id){
    global $db;
    echo $serial_id;
    $db->doQuery("INSERT INTO waspmote(name)
                                  VALUES('$serial_id')");
    $db->doQuery("SELECT * FROM waspmote 
                      WHERE name='$serial_id'");
    $object=mysqli_fetch_assoc($db->loadObjectList());
    global $id_board;
    $id_board=$object["id"];
}

function checkForNewSensor($sensor_name){
    global $db;
    $db->doQuery("SELECT * FROM sensor 
                      WHERE name='$sensor_name'");
    $object=mysqli_fetch_assoc($db->loadObjectList());
    if($object==NULL){
        return true;
    }
    else {
        global $id_sensor;
        $id_sensor = $object["id"];
        return false;
    }
}
function addNewSensor($sensor_name){
    global $db;
    $db->doQuery("INSERT INTO sensor(name)
                                  VALUES('$sensor_name')");
    $db->doQuery("SELECT * FROM sensor 
                      WHERE name='$sensor_name'");
    $object=mysqli_fetch_assoc($db->loadObjectList());
    global $id_sensor;
    $id_sensor=$object["id"];
}