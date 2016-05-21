<?php


    include("db.php");
    	mysql_select_db("knoneaet_test_hot_cascad");
$q1= mysql_query("SELECT * FROM `sale_order_1`");
while ($r1=mysql_fetch_assoc($q1)) {
	$s_o_id=$r1['sale_order_id'];
$date=date("Y-m-d H:i:s",strtotime ( '-5 hour -30 minutes' , strtotime ( $r1['order_date'] ) ));
	$q2=mysql_query("UPDATE `sale_order_1` SET `order_date`='$date' WHERE `sale_order_id`='$s_o_id'");
}

?>