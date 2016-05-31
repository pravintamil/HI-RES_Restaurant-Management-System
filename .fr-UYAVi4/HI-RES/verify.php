<?php
$mode= $_GET["mode"];
if($mode=="e_verify"){
	$mail= $_GET["mail"];
	$mail=urldecode($mail);
	$key= $_GET["key"];
	include('db.php');
	$q="SELECT * FROM `restaurant` WHERE `email` LIKE '$mail'";
	$row=mysql_fetch_array(mysql_query($q));
	if($row){
		if($key===md5($row['db_key']))
			if(mysql_query("UPDATE `employee` SET `email_verify` = '1' WHERE `email` LIKE '$mail'"))
				header('Location: index.php');
	}
}
elseif ($mode=="forgot") {
	$mail= $_GET["mail"];
	$mail=urldecode($mail);
	include('db.php');
	$q="SELECT * FROM `employee` WHERE `email` LIKE '$mail'";
	if($row=mysql_fetch_array(mysql_query($q))){
		$str=$row['email'].$row['employee_id'].$row['password'];
		$str=md5($str);
		$key=$_GET['key'];
		if($key==$str){
			$key=md5($key);
			echo '<form name="frm" action="forgot.php" method="post">
				<input type="text" name="email" value="'.$mail.'" hidden>
				<input type="text" name="key" value="'.$key.'" hidden>
			</form>
			<script language="JavaScript">
document.frm.submit();
</script>';
			
			
		}
	}
	
}


?>