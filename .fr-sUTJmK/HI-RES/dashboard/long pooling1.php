<?php
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
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
    $timezone=date_default_timezone_get();
	while (true) {

		clearstatcache();
		if($iteration==1){
            die();
        }
           
			fwrite($fil, "\ntime $time\nlast $last_access\nlast access ".fileatime($filename));
			date_default_timezone_set($timezone);
            include ("../db.php");
            mysql_select_db("$db_key");
	        $startDate=date("Y-m-d G:i:s", strtotime("-1 days"));
    	    $endDate=date("Y-m-d G:i:s");
        	$cat_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[0]`"));
	        $prod_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[3]`"));
    	    $order_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[4]` WHERE `status` NOT LIKE 'm(%)' AND `order_date` BETWEEN '$startDate' AND '$endDate'ORDER BY `order_date` DESC ;"));

            $data['time']=$timezone;
	        $cus_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[1]` WHERE `customer_id`!=1"));
    	    $data['success']=true;
        	$data['cat_count']=$cat_count;
	        $data['prod_count']=$prod_count;
    	    $data['order_count']=$order_count;
        	$data['cus_count']=$cus_count;
	        $q1=mysql_query("SELECT * FROM  `$tables[4]` WHERE `status` NOT LIKE 'm(%)' ORDER BY `order_date` DESC LIMIT 0 , 6");
    	    while ($r1=mysql_fetch_assoc($q1)) {
        	    $s_o_id = $r1['sale_order_id'];
	            $order['order_id'] = $r1['sale_order_id'];
    	        $order['status']=$r1['status'];
        	    $order['price']=0;
            	$order['prod_name']="";
	            $q2=mysql_query("SELECT `product_name` , `price` FROM `$tables[5]` WHERE `order_id` ='$s_o_id'");
    	        while ($r2=mysql_fetch_assoc($q2)) {
        	        $order['prod_name'].=$r2['product_name'].", ";
            	    $order['price']+=$r2['price'];
	            }
    	        $order['prod_name']=rtrim($order['prod_name'], ", ");
        	    $data['last_order'][]=$order;
	        }
    	    $q3=mysql_query("SELECT `item_name`, `item_desc`, `price` FROM `$tables[3]` ORDER BY `item_id` DESC LIMIT 0 , 5");
	        while ($r3=mysql_fetch_assoc($q3)) {
    	        $data['last_product'][]=$r3;
	        }
    	    $startDate=date ( 'Y-m-j',strtotime ( '-7 day' , strtotime ( date("Y-m-d") ) ) );
        	$endDate=date("Y-m-d");
	        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
    	    $endDate=date("Y-m-d G:i:s", strtotime($endDate));
        	$a = new DateTime($startDate);
	        $b = new DateTime($endDate);

    	    if ($a->diff($b)->format('%y')!=0) {
        	    $report = sales_data_date('%Y','Y',$startDate,$endDate);
	        }
    	    elseif ($a->diff($b)->format('%m')!=0) {
        	    $report = sales_data_date('%Y-%m','F',$startDate,$endDate);
        	}
    	    elseif ($a->diff($b)->format('%d')>=8) {
	            $report = sales_data_date('%Y-%m-%d','jS F',$startDate,$endDate);
	        }
    	    elseif ($a->diff($b)->format('%d')!=0) {
        	    $report = sales_data_date('%Y-%m-%d','l',$startDate,$endDate);
	        }
    	    elseif ($a->diff($b)->format('%d')==0) {
        	    $report = sales_data_date('%Y-%m-%d %H','ha D',$startDate,$endDate);
        	}
	        $data['sale_report'] = $report;
    	    include("../db.php");
        	$q4=mysql_query("SELECT ce.*,el.title FROM `event_list` as el join `calendar_events` as ce on el.id=ce.event_id WHERE el.db_key='$db_key'");
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
            echo 'data: {"data": '.json_encode($data);
            echo '}'."\n\n";
            echo PHP_EOL;
            ob_flush();
            flush();
    		$time=$last_access;
        	fwrite($fil, "\nsleep");
            $iteration++;
            unset($data);
            unset($todo);
            unset($report);
            unset($row4);
            unset($cat_count);
            unset($prod_count);
            unset($order_count);
            unset($cus_count);
            unset($endDate);
            unset($startDate);
        	sleep(1);
    }

function sales_data_date($pattern,$pattern1,$startD,$endD)
{
    global $tables;
    $price_line=0;
    $price_order=0;
    $price_each=0;
    $price_final=0;
    $i=0;
    // echo "$pattern<br>";
    // echo "$pattern1<br>";
    // echo "$startD<br>";
    // echo "$endD<br>";
    $q1="SELECT DISTINCT DATE_FORMAT( `order_date` , '$pattern' ) AS date FROM `$tables[4]` WHERE `order_date` BETWEEN '$startD' AND '$endD' ORDER BY DATE( `order_date` ) ASC LIMIT 0 , 30";
    $e1=mysql_query($q1);
    $f=fopen("2.txt", "a");
    fwrite($f, "1: $q1\n");
    fwrite($f, "p1 $pattern\n p2 $pattern1\n start $startD \n end $endD");
    // echo "$q1 <br>";
    while ($r1=mysql_fetch_array($e1)) {
        $date=$r1['date'];
        // echo "date :$date <br>";
        $q2 =mysql_query( "SELECT `sale_order_id` FROM `$tables[4]` WHERE `order_date` LIKE '%$date%'");
        fwrite($f, "2: $q2\n");
        while ($r1=mysql_fetch_array($q2)) {
            $s_o_id=$r1['sale_order_id'];
            // echo " id: $s_o_id ";
            $total_order=0;
            $q3=mysql_query("SELECT `qty` , `price` , `discount` FROM `$tables[5]` WHERE `order_id` ='$s_o_id'");
            fwrite($f, "3: $q3\n");
            while ($r4=mysql_fetch_array($q3)) {
                $price_line=$r4['qty']*$r4['price']*(100-$r4['discount'])/100;
                // echo " price: $price_line";
                $price_order+=$price_line;
            }
            // echo " o_price: $price_order";
            $price_each+=$price_order;
            $price_order=0;
        }
        // echo "d_price: $price_each <br>";
        $price_final+=$price_each;
        if ($pattern1!='Y'){
            $price[$i]['day']=date($pattern1,strtotime($date));
            $price1[$i]['label']=date($pattern1,strtotime($date));
        }
        else {
            $price[$i]['day']=$date;
            $price1[$i]['label']=$date;
        }
        $price[$i]['a']=$price_each;
        $price1[$i]['value']=$price_each;
        $i++;
        $price_each=0;
   }
   // echo "price month: $price_final <br>";
   // // print_r($price);
   // fwrite($f, print_r($price));
   $data['data1']=$price;
   $data['data2']=$price1;
   return $data;
}
?>