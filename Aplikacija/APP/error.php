<?php
header("Content-type:application/json"); 
$data = json_decode(file_get_contents('php://input'), true);
print_r($data);

require 'PHPMailer/PHPMailerAutoload.php';  // ur library location
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

$mail->MsgHTML('Your XBee modul may be unpluged. Please check your Xbee modul conection');

$address = "filip.galic@student.fsr.ba";
$mail->AddAddress($address, "Error!");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
  
?>
