<?php
	set_time_limit(0);
	session_start();
	$data['success']=false;
	// $postdata = file_get_contents("php://input");
 //    $request = json_decode($postdata);
 //    var_dump($request);
    $time=$_GET['time'];
 //    $mode = $request->mode;
	// $time= $request->time;
	if(!isset($_SESSION['email'])||!isset($_SESSION['db_key'])){
    	header('Location: ../index.php');
    	exit();
	}
	$email=$_SESSION['email'];
    $db_key=$_SESSION['db_key'];
    $branch_id=$_SESSION['branch_id'];
    $restaurant_name=$_SESSION['restaurant_name'];
    $tables = array('category','customer','driver','product','sale_order','sale_order_line','table_category','table_details');
    foreach ($tables as $key => $value) {
        $tables[$key]=$value."_".$branch_id;
    }
    include ("../db.php");
	mysql_select_db("$db_key");
	$filename="../api/test.php";
	$fil=fopen("er1.txt", "a");
    $iteration=0;
// if ($mode=="dashboard-details"){
	while (true) {

		clearstatcache();
		// if (file_exists($filename)) {
 		   $last_access= fileatime($filename);
 		   $data['time']=$last_access;
		// }
 		   // fwrite($fil, "\n$last_access");
		// fwrite($fil, "\n$time");
		if($time==0){
			fwrite($fil, "\n0");
		}
		if($last_access != $time){
			fwrite($fil, "\n1");
		}
		if(($time==0)||($last_access != $time)||$iteration==6){
            $iteration=0;
			fwrite($fil, "\ntime $time\nlast $last_access\nlast access ".fileatime($filename));
			
	        
            $startDate=date("Y-m-d H:i:s",strtotime("today 00:00:00"));
            $endDate=date("Y-m-d H:i:s",strtotime("today 23:59:59"));
        	$cat_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[0]`"));
	        $prod_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[3]`"));
    	    $order_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[4]` WHERE `status` NOT LIKE 'm(%)' AND `order_date` BETWEEN '$startDate' AND '$endDate'ORDER BY `order_date` DESC "));
	        $cus_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[1]` WHERE `customer_id`!=1"));
    	    $data['success']=true;
        	$data['cat_count']=$cat_count;
	        $data['prod_count']=$prod_count;
    	    $data['order_count']=$order_count;
        	$data['cus_count']=$cus_count;

            //latest orders
	        $q1=mysql_query("SELECT * FROM  `$tables[4]` WHERE `status` NOT LIKE 'm(%)' ORDER BY `order_date` DESC LIMIT 0 , 6");
    	    while ($r1=mysql_fetch_assoc($q1)) {
        	    $s_o_id = $r1['sale_order_id'];
	            $order['order_id'] = $r1['sale_order_id'];
    	        $order['status']=$r1['status'];
                $order['order_type']=$r1['order_type'];
                $order['time']=date("h:i:s a",strtotime($r1['order_date']));
        	    $order['price']=0;
            	$order['prod_name']="";
	            $q2=mysql_query("SELECT `product_name` , `price` FROM `$tables[5]` WHERE `order_id` ='$s_o_id'");
    	        while ($r2=mysql_fetch_assoc($q2)) {
        	        $order['prod_name'].=$r2['product_name'].", ";
            	    $order['price']+=$r2['price']*0.95*1.12;
	            }
    	        $order['prod_name']=rtrim($order['prod_name'], ", ");
        	    $data['last_order'][]=$order;
	        }
            
            //today orders
            $startDate=date("Y-m-d H:i:s",strtotime("today 00:00:00"));
            $endDate=date("Y-m-d H:i:s",strtotime("today 23:59:59"));
            $q1=mysql_query("SELECT * FROM  `$tables[4]` WHERE `status` NOT LIKE 'm(%)' AND `order_date` BETWEEN '$startDate' AND '$endDate' ORDER BY `order_date` DESC");
            while ($r1=mysql_fetch_assoc($q1)) {
                $s_o_id = $r1['sale_order_id'];
                $order['order_id'] = $r1['sale_order_id'];
                $order['status']=$r1['status'];
                $order['order_type']=$r1['order_type'];
                $order['time']=date("h:i:s a",strtotime($r1['order_date']));
                $order['bill_paid']=$r1['bill_paid'];
                $order['price']=0;
                $order['prod_name']="";
                $q2=mysql_query("SELECT `product_name` , `price` FROM `$tables[5]` WHERE `order_id` ='$s_o_id'");
                while ($r2=mysql_fetch_assoc($q2)) {
                    $order['prod_name'].=$r2['product_name'].", ";
                    $order['price']+=$r2['price']*0.95*1.12;
                }
                $order['prod_name']=rtrim($order['prod_name'], ", ");
                $driver_id=$r1['driver_id'];
                $q3="SELECT * FROM `$tables[2]` WHERE  `driver_id` =$driver_id";
                $r3=mysql_fetch_assoc(mysql_query($q3));
                $order['driver_name']=$r3['name']?$r3['name']:"Unassigned";
                $data['today_order'][]=$order;
            }

            //recently added products
    	    $q3=mysql_query("SELECT `item_name`, `item_desc`, `price` FROM `$tables[3]` ORDER BY `item_id` DESC LIMIT 0 , 5");
	        while ($r3=mysql_fetch_assoc($q3)) {
    	        $data['last_product'][]=$r3;
	        }

            $i=0;
            $startDate=date("Y-m-d H:i:s",strtotime("today 00:00:00"));
            $endDate=date("Y-m-d H:i:s",strtotime("today 11:00:00"));
            $sq1="SELECT * FROM `$tables[4]` WHERE `order_date` BETWEEN '$startDate' AND '$endDate' ORDER BY DATE( `order_date` ) ASC";
            $se1=mysql_query($sq1);
            $count[$i]['day']='breakfast';
            $count1[$i]['label']='breakfast';
            $count[$i]['a']=mysql_num_rows($se1);
            $count1[$i]['value']=mysql_num_rows($se1);
            $i++;
            $startDate=date("Y-m-d H:i:s",strtotime("today 11:00:01"));
            $endDate=date("Y-m-d H:i:s",strtotime("today 18:00:00"));
            $sq2="SELECT * FROM `$tables[4]` WHERE `order_date` BETWEEN '$startDate' AND '$endDate' ORDER BY DATE( `order_date` ) ASC";
            $se2=mysql_query($sq2);
            $count[$i]['day']='lunch';
            $count1[$i]['label']='lunch';
            $count[$i]['a']=mysql_num_rows($se2);
            $count1[$i]['value']=mysql_num_rows($se2);
            $i++;
            $startDate=date("Y-m-d H:i:s",strtotime("today 18:00:01"));
            $endDate=date("Y-m-d H:i:s",strtotime("today 23:59:59"));
            $sq3="SELECT * FROM `$tables[4]` WHERE `order_date` BETWEEN '$startDate' AND '$endDate' ORDER BY DATE( `order_date` ) ASC";
            $se3=mysql_query($sq3);
            $count[$i]['day']='dinner';
            $count1[$i]['label']='dinner';
            $count[$i]['a']=mysql_num_rows($se3);
            $count1[$i]['value']=mysql_num_rows($se3);
            $i++;
            $startDate=date("Y-m-d H:i:s",strtotime("today 00:00:00"));
            $endDate=date("Y-m-d H:i:s",strtotime("today 23:59:59"));
            $sq4="SELECT DATE_FORMAT( `order_date` , '%Y-%m-%d %H' ) AS date,  DATE_FORMAT( `order_date` , '%Y-%m-%d %H:%i:%s' ) AS date1 FROM `sale_order_1` WHERE `order_date` BETWEEN '$startDate' AND '$endDate' group by date ORDER BY DATE( `order_date` ) DESC";
            $se4=mysql_query($sq4);
            while ($sr4=mysql_fetch_array($se4)) {
                $date=$sr4['date'];
                $date1=$sr4['date1'];
                $sq5 ="SELECT `sale_order_id` FROM `$tables[4]` WHERE `order_date` LIKE '%$date%'";
                $se5=mysql_query($sq5);
                $count[$i]['day']=date('ha D',strtotime($date1));
                // $count1[$i]['label']=date('ha D',strtotime($date1));
                $count[$i]['a']=mysql_num_rows($se5);
                // $count1[$i]['value']=mysql_num_rows($se5);
                $i++;
            }
            $report['data1']=$count;
            $report['data2']=$count1;

	        $data['sale_report'] = $report;
            $tq1="SELECT * FROM $tables[7]";
            $te1=mysql_query($tq1);
            while ($tr1=mysql_fetch_assoc($te1)) {
                $tbl_det=$tr1;
                $tbl_id=$tr1['table_id'];
                $tbl_det['status']='0';
                $tq2="SELECT * FROM `$tables[4]` WHERE `order_type` like 'dine_in' and `status` LIKE 'draft' and `table_id`LIKE '$tbl_id'";
                $te2=mysql_query($tq2);
                if(mysql_num_rows($te2)){
                    $tbl_det['status']='1';
                }
                $table_dets[]=$tbl_det;
            }
            $data['table_status']=$table_dets;
    	    include("../db.php");
        	$q4=mysql_query("SELECT ce.*,el.title FROM `event_list` as el join `calendar_events` as ce on el.id=ce.event_id WHERE el.db_key='$db_key' ORDER BY  ce.id  DESC LIMIT 0 , 10");
	        while ($row4=mysql_fetch_assoc($q4)) {
    	        $endDate=date ( 'Y-m-d H:i:s',strtotime ( date("Y-m-d H:i:s") ) );
        	    date_default_timezone_set('UTC');
            	$startDate=date("Y-m-d H:i:s", strtotime($row4['start']));
	            $a = new DateTime($startDate);
    	        $b = new DateTime($endDate);
        	    if ($a->diff($b)->format('%y')!=0) {
            	    $row4['diff']=$a->diff($b)->format('%y')." year";
	                $row4['format']="year";
    	        }
        	    elseif ($a->diff($b)->format('%m')!=0) {
            	    $row4['diff']=$a->diff($b)->format('%m month');
                	$row4['format']="month";
	            }
    	        elseif ($a->diff($b)->format('%d')!=0) {
        	        $row4['diff']=$a->diff($b)->format('%d days');
            	    $row4['format']="day";
	            }
    	        elseif ($a->diff($b)->format('%H')!=0) {
        	        $row4['diff']=$a->diff($b)->format('%H hours');
            	    $row4['format']="hour";
		        }
        	    elseif ($a->diff($b)->format('%i')!=0) {
            	    $row4['diff']=$a->diff($b)->format('%i minutes');
                	$row4['format']="minute";
	            }

    	        $todo[]=$row4;
        	}
	        $data['todo_data']=$todo;
    		echo json_encode($data);
    		$time=$last_access;
    		break;
    		exit();
    	}	
    	else {
        	fwrite($fil, "\nsleep");
            $iteration++;
        	sleep( 5 );
        	continue;
    	}
    }
// }

function sales_data_date($pattern,$i,$startD,$endD)
{
    global $tables;
    // echo "$pattern<br>";
    // echo "$pattern1<br>";
    // echo "$startD<br>";
    // echo "$endD<br>";
    $q1="SELECT * FROM `$tables[4]` WHERE `order_date` BETWEEN '$startD' AND '$endD' ORDER BY DATE( `order_date` ) ASC";
    $e1=mysql_query($q1);
    $count[$i]['day']=$pattern;
    $count1[$i]['label']=$pattern;
    $count[$i]['a']=mysql_num_rows($e1);
    $count1[$i]['value']=mysql_num_rows($e1);
   // echo "price month: $price_final <br>";
   // // print_r($price);
   // fwrite($f, print_r($price));
   $data['data1']=$count;
   $data['data2']=$count1;
   return $data;
}
?>