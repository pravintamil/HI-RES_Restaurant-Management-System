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
$file=fopen("maillist.php", "a");
include ("db.php");
$mail->setFrom('pravin@knonex.com', 'Pravin Knonex');

$query1="SELECT emp.email, el.db_key, res.restaurant_name FROM `event_list` as el join `restaurant` as res on el.db_key=res.db_key join `employee` as emp on emp.restaurant_id=res.restaurant_id group by el.db_key;";
$q1exec=mysql_query($query1);
$startDate=date("Y-m-d");
$endDate=date ( 'Y-m-d',strtotime ( '+1 day' , strtotime ( date("Y-m-d") ) ) );
$start_date=date("Y-m-d\TH:i:s\Z", strtotime($startDate));
$end_date=date("Y-m-d\TH:i:s\Z", strtotime($endDate));

$subject="To do list for $startDate";
fwrite($file, "$subject\n");
// $start_date=date("Y-m-d");
// $start_date=date_add($start_date,date_interval_create_from_date_string("1 days"));
// $start_date=date_format($start_date,"Y-m-d");
// $end_date=date("Y-m-d");
// $end_date=date_add($end_date,date_interval_create_from_date_string("2 days"));
// $end_date=date_format($end_date,"Y-m-d");
// fwrite($file, "start $start_date end $end_date");
while ($row1=mysql_fetch_assoc($q1exec)) {
	$email=$row1['email'];
	$db_key=$row1['db_key'];
	$restaurant_name=$row1['restaurant_name'];
	$query2="SELECT el.title,DATE_FORMAT( `start` , '%r' ) AS start , DATE_FORMAT(  `date_time` ,'%b %d %Y %h:%i %p' ) AS created_on FROM `calendar_events` as ce join `event_list` as el on ce.event_id=el.id WHERE el.db_key='$db_key' AND ce.`start` BETWEEN '$start_date' AND '$end_date';";
	$q2exec=mysql_query($query2);
	$message="<table>
			<th>
				<td>Title</td>
				<td>Event time</td>
				<td>created_on</td>
			</th>";
	while ($row2=mysql_fetch_assoc($q2exec)) {
		$title=$row2['title'];
		$time=$row2['start'];
		$created_on=$row2['created_on'];
		$message.="<tr>
				<td>$title</td>
				<td>$time</td>
				<td>$created_on</td>
			</tr>";
	}
	$message.="</table>";
	// $mail->addAddress("$email", 'Joe User');     // Add a recipient
	// $mail->addReplyTo('pravin@knonex.com', 'Pravin Knonex');
	// $mail->isHTML(true);                                  // Set email format to HTML
	// $mail->Subject = $subject;
	// $mail->Body    = $message;
	// if(!$mail->send()) {
	// 	$data['success']=false;
 //    	$error= 'Message could not be sent.';
	// } else {
 //    	$data['message']="verification email has been sent to your mail";
    	fwrite($file, $email."\n"."$message\n");
	// }
	// $mail->ClearAllRecipients( );
	$message="";
}

?>