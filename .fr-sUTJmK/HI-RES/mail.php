<?php
require 'PHPMailer-master/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'webmail.knonex.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'pravin@knonex.com';                 // SMTP username
$mail->Password = 'pravin1995';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('pravin@knonex.com', 'Pravin Knonex');
$mail->addAddress("$email", 'Joe User');     // Add a recipient
$mail->addReplyTo('pravin@knonex.com', 'Pravin Knonex');

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $subject;
$mail->Body    = $message;

if(!$mail->send()) {
	$data['success']=false;
    $error= 'Message could not be sent.';
} else {
    $data['message']="verification email has been sent to your mail";
}?>