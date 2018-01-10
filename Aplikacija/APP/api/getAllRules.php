<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
	
	
if($db->connect('127.0.0.1','root','','pis_project')){
    getAllRules();

}
}
else{
	header('Location: /piis/web/login.php');
}




function getAllRules(){
    global $db;
	$db->doQuery("
	SELECT  a.*
		FROM    rules a
        INNER JOIN
        (
            SELECT  id_waspmote,id_sensor, MAX(id) max_ID
            FROM    rules
            GROUP BY id_waspmote,id_sensor
        ) b ON a.id_waspmote = b.id_waspmote AND
                a.ID = b.max_ID
				and a.id_sensor=b.id_sensor
			 ");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
   }
 
   echo json_encode(($rows));
}