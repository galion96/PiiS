<?php
 header("Access-Control-Allow-Origin: *");
include('./database.php');
include('./table.php');
include('./room.php');
include('./sensor.php');

/*$db = database::getInstance();
$db->connect('127.0.0.1', 'root', '', 'pis_project');*/

//dodamo kitchen u tablicu room, ispisemo iz tablice sobu s id-om 1 load(1)
/*$room = new room();
$data = array("room_name"=>"kitchen");
$room->bind($data);
$room->store();
$room->load('1');
echo $room->room_name. "<br />";
*/
//ucitamo u varijablu sensor onaj iz tablice koji ima id 1 i updateamo ga
/*$sensor = new sensor();
$sensor->load('1');
$data = array("sensor_name"=>"motion_sensor");
$sensor->bind($data);
$sensor->store();
echo $sensor->sensor_name. "<br/>";*/
if(isset($_GET['method'])) {
	 if(searchGet($_GET['method'])){
	require __DIR__.'/api/'.$_GET['method'] . '.php';
	 }
}
else if(isset($_POST["method"])){
	 if(searchPost($_POST['method'])) {
	require __DIR__.'/api/'.$_POST['method'].'.php';
	 }
}
function searchGet($method){
    $methods=['getSensorDataById','autoComplete','getSensorsFromBoard','getAllBoardData','getAllBoards','getAllRules','getLastBoardData','getSensorDataByDate','getUserInfo','getAllRooms','getBoardsFromRoom','getAllAvalibleBoards'];
    foreach ($methods as $m){
        if($m==$method){
            return true;
        }
    }
    return false;
}
function searchPost($method){
    $methods=['login','addNewRule','isLoggedIn','logout','setUserInfo','changePassword','addNewRoom','deleteRoom','saveBoardToRoom'];
    foreach ($methods as $m){
        if($m==$method){
            return true;
        }
    }
	
    return false;
}