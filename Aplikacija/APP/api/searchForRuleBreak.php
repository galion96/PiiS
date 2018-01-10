<?php
require_once(__DIR__.'/../database.php');
$db= database::getInstance();
header("Content-type:application/json");


if($db->connect('127.0.0.1','root','','pis_project')){
	$rows=getAllRules();
	//echo json_encode($rows);
    searchForRuleBreaks($rows);
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
       $rows[$r['id_waspmote']][$r['id_sensor']]=$r;
   }
   return $rows;
	}
function searchForRuleBreaks($rows){
	global $db;
	 $flag=0;
	 $message="Warning!!!";
	// var_dump($rows);

	//echo $keys;
	var_dump($rows);
	foreach ($rows as $index=>$row){
	//var_dump($row);
		foreach($row as $index2=>$data){
	
		 $sensor_name=getSensorName($index2);
		 $sensorName=$sensor_name['name'];
		 $serial_id=getSerialId($index);
		 $serialId=$serial_id['name'];
		 $results=getLastFiveRowsForSensorBoard($index2,$index);
		// var_dump ($results);
		for($j=0;$j<COUNT($results);$j++){
			$errVal=$rows[$index][$index2]["value"];
			$val=$results[$j]["reading_value"];
			if($val>$errVal){
				$flag=1;
				$message.="\nLooks like sensor $sensorName from board $serialId trigered error by sending value larger than $errVal.\nTrigered value was  $val ";
				
				break;
		 }
	 }
		
}
	}
if($flag==1){
	sendMail($message);
}
echo "$message";
}
		 
function getLastFiveRowsForSensorBoard($id_sensor,$id_waspmote) {
		global $db;
		$db->doQuery("SELECT reading_value,reading_time,id_sensor,id_waspmote
					FROM sensor_waspmote sw
					WHERE sw.id_sensor=$id_sensor AND sw.id_waspmote=$id_waspmote
					ORDER BY reading_time desc
					LIMIT 5
					");
					   $obj=$db->loadObjectList();
				       $rows=[];
					  while($r=mysqli_fetch_assoc(($obj))){
							$rows[]=$r;
						}
   return $rows;
}
	function getSensorName($sensor_id){
		global $db;
		$db->doQuery("SELECT name 
					  FROM sensor
					  WHERE id=$sensor_id
					  ");
		$obj=$db->loadObjectList();
	    return mysqli_fetch_assoc($obj);
		}
		
	function getSerialId($id_waspmote){
		global $db;
		$db->doQuery("SELECT name 
					  FROM waspmote
					  WHERE id=$id_waspmote
					  ");
		$obj=$db->loadObjectList();
	    return mysqli_fetch_assoc($obj);
		}
		
function sendMail($message){
	global $db;
	$db->doQuery("SELECT email from user");
$email=mysqli_fetch_assoc($db->loadObjectList());
	require '/../PHPMailer/PHPMailerAutoload.php';  // ur library location
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail = new PHPMailer(true);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mail.yourdomain.com"; // SMTP server
$mail->SMTPDebug = 2;                  // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 
$mail->Host       = "smtp.gmail.com";      // SMTP server
$mail->Port       = 465;                   // SMTP port
$mail->Username   = "skolaprogramiranjafm@gmail.com";  // username
$mail->Password   = "skolaprogramiranja2017.";            // password

$mail->SetFrom('no-reply@gmail.com', 'Error!');

$mail->Subject    = "Error!";

$mail->MsgHTML($message);

$address = $email['email'];
$mail->AddAddress($address, "Error!");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
}