<?php
    session_start();
	$db_key=$_SESSION['db_key'];
	include("../db.php");
	$data['success']=false;
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
	$img_src=$request->image;
	if(isset($img_src)){
		$db_key=$_SESSION['db_key'];
		$q1="SELECT * FROM `rest_logo` WHERE `db_key` ='$db_key' ORDER BY `s.no` DESC ";
		$row=mysql_fetch_array(mysql_query($q1));
		if($row['s.no']){
			$q2="UPDATE `rest_logo` SET `image`='$img_src' WHERE `db_key` ='$db_key'";
		}
		else{
			$q2="INSERT INTO `rest_logo`(`db_key`, `image`) VALUES ('$db_key','$img_src')";	
		}
		if(mysql_query($q2)){
      		$data['success']=true;
		}
		else{
			$data['err']="error in insert";
		}
	}
	else{
		$data['err']="post is not set";
	}
	echo json_encode($data);
	if($_POST['db_key']){
		$db_key=$_POST['db_key'];
		$q="SELECT * FROM `rest_logo` WHERE `db_key` ='$db_key' ORDER BY `s.no` DESC ";
		echo "\n $q";
		if($row=mysql_fetch_array(mysql_query($q))){
			echo "<img src='".$row["image"]."'>";
		}
	}