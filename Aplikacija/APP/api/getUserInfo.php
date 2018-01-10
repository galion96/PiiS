<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");
session_start();
if(isset($_SESSION["loggedIn"])){
	
if($db->connect('127.0.0.1','root','','pis_project')){
    getUserInfo();

}
	else {
	$obj = (object) [
    'error' => 'There is no internet connection'];
echo json_encode(($obj));
}
}
else{
	header('Location: /piis/web/login.php');
}

function getUserInfo(){
    global $db;
	$db->doQuery("SELECT id,name,username,surname,email FROM user");
   $obj=$db->loadObjectList();
   $rows=[];
   while($r=mysqli_fetch_assoc(($obj))){
       $rows["data"][]=$r;
	  // $rows["data"]["password"]= rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $r["pasword"] ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
   }
 
   echo json_encode(($rows));
}