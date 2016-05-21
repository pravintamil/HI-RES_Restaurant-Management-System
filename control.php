<?php
	$data['success']=false;
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $mode = $request->mode;
    $data['message']=$mode;
    include("db.php");
    if($mode=="signup"){
		$restaurant_name=$request->restaurant_name;
		$branches=$request->branch;
		$email=$request->email;
		$password=$request->password;
		$r_password=$request->r_password;
		$landline=$request->landline;
		$mobile=$request->mobile;
		$address=$request->address;
		$city=$request->city;
		$state=$request->state;
		$pincode=$request->pincode;
		$access_code="00";

		if(mysql_fetch_row(mysql_query("SELECT * FROM `employee` WHERE `email` LIKE '$email'"))){
			$data['error']="Sorry the email id is already registered";	
		}
		else{
			$restaurant_name_char=preg_replace("/[^a-zA-Z0-9]+/", "", $restaurant_name);
			$key="knx_hot_".substr($restaurant_name_char, 0,6);
			$db_key="";
			if (mysql_select_db($key)) {
	    		for ($i=1; $i <100 ; $i++) { 
    				if (!mysql_select_db($key.$i)) {
    					$db_key=$i;
    					$i=100;
	    			}
    			}
			}
			$db_key=$key.$db_key;
			$sql = "CREATE Database $db_key";
    		$sql_e = mysql_query($sql);

			include("db.php");
			$q1="INSERT INTO `restaurant`(`restaurant_name`, `branches`, `email`, `landline`, `mobile`, `address`, `city`, `state`, `pincode`, `access_code`, `db_key`) VALUES ('$restaurant_name','$branches','$email','$landline','$mobile','$address','$city','$state','$pincode','$access_code','$db_key')";
			if(mysql_query($q1)){
				$restaurant_id=mysql_insert_id();
				$password=md5($password);
				$q2=mysql_query("INSERT INTO `employee`(`email`, `password`, `email_verify`, `restaurant_id`, `branch_id`, `status`) 
					VALUES ('$email','$password',0,'$restaurant_id','1','1')");
                $employee_id=mysql_insert_id();
                $q2="INSERT INTO `employee_role`(`employee_id`, `role_id`) VALUES ('$employee_id','1')";
                if (mysql_query($q2)) {
                    $q3="INSERT INTO `res_time` (`restaurant_id`, `branche_id`, `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday_cls`, `monday_cls`, `tuesday_cls`, `wednesday_cls`, `thursday_cls`, `friday_cls`, `saturday_cls`) VALUES('$restaurant_id', 1, '06:00:00', '06:00:00', '06:00:00', '06:00:00', '06:00:00', '06:00:00', '06:00:00', '22:00:00', '22:00:00', '22:00:00', '22:00:00', '22:00:00', '22:00:00', '22:00:00');";
                }
				if(mysql_query($q3)){
                    $tables = array('category','customer','driver','product','sale_order','sale_order_line','table_category','table_details');
                    foreach ($tables as $key => $value) {
                        $tables[$key]=$value."_"."1";
                    }
                    mysql_select_db($db_key);
                    $q1=array();
                    $q1[]="CREATE TABLE IF NOT EXISTS `address` (`pincode` int(6) NOT NULL, `address` varchar(100) NOT NULL, `city` varchar(100) NOT NULL, `state` varchar(100) NOT NULL, PRIMARY KEY (`pincode`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[0]` (`category_id` int(10) NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL,`desc` varchar(300) NOT NULL,`priority` int(20) NOT NULL,PRIMARY KEY (`category_id`),UNIQUE KEY `name` (`name`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[1]` ( `customer_id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `email` varchar(100) DEFAULT NULL,`phone` varchar(100) NOT NULL,`address` varchar(200) DEFAULT NULL,`pincode` varchar(10) DEFAULT NULL,`rand_id` varchar(20) DEFAULT NULL,PRIMARY KEY (`customer_id`),UNIQUE KEY `rand_id` (`rand_id`),UNIQUE KEY `phone` (`phone`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[2]` ( `driver_id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `phone` varchar(100) NOT NULL, PRIMARY KEY (`driver_id`),UNIQUE KEY `phone` (`phone`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[3]` ( `item_id` int(10) NOT NULL AUTO_INCREMENT, `item_name` varchar(100) NOT NULL, `item_desc` varchar(300) DEFAULT NULL, `priority` int(11) NOT NULL, `max_order` int(11) NOT NULL, `price` double NOT NULL, `category_id` int(11) NOT NULL, `tax_id` int(11) NOT NULL, `meal_type` varchar(100) NOT NULL, `food_type` varchar(100) NOT NULL, `is_option_available` int(11) NOT NULL DEFAULT '0', `availability` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`item_id`), KEY `categoryID` (`category_id`), KEY `tax_id` (`tax_id`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[4]` ( `sale_order_id` int(10) NOT NULL AUTO_INCREMENT, `customer_id` int(10) DEFAULT NULL, `status` varchar(20) NOT NULL DEFAULT 'preparation', `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP, `is_driver` tinyint(1) NOT NULL, `portal` varchar(100) DEFAULT 'own', `driver_id` int(10) DEFAULT NULL, `bill_paid` tinyint(1) DEFAULT '0', `special_notes` varchar(100) DEFAULT NULL, `table_id` int(11) DEFAULT NULL, `order_type` varchar(20) NOT NULL, `rand_id` varchar(20) DEFAULT NULL,`server_id` varchar(4) DEFAULT '1', PRIMARY KEY (`sale_order_id`), UNIQUE KEY `rand_id` (`rand_id`), KEY `customer_id` (`customer_id`), KEY `customer_id_2` (`customer_id`), KEY `customer_id_3` (`customer_id`), KEY `driver_id` (`driver_id`));";
                     $q1[]="CREATE TABLE IF NOT EXISTS `$tables[5]` ( `order_line_id` int(10) NOT NULL AUTO_INCREMENT, `order_id` int(10) NOT NULL, `product_id` int(10) NOT NULL, `product_name` varchar(100) NOT NULL, `product_template_id` int(10) NOT NULL, `qty` int(10) NOT NULL, `uom` int(10) NOT NULL, `price` float NOT NULL, `discount` float DEFAULT NULL, `special_notes` varchar(100) DEFAULT NULL, `extra_ids` varchar(100) DEFAULT NULL, `TEST` varchar(255) NOT NULL, PRIMARY KEY (`order_line_id`), KEY `sale_order_line_ibfk_1` (`order_id`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[6]` ( `table_category_id` int(10) NOT NULL AUTO_INCREMENT, `table_category_name` varchar(100) NOT NULL, `no_of_tables` int(10) NOT NULL, PRIMARY KEY (`table_category_id`),UNIQUE KEY `table_category_name` (`table_category_name`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[7]` ( `table_id` int(10) NOT NULL AUTO_INCREMENT, `table_category_id` int(10) DEFAULT NULL, `table_name` varchar(100) NOT NULL, `capacity` int(100) NOT NULL, PRIMARY KEY (`table_id`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `$tables[8]` ( `tax_id` int(20) NOT NULL AUTO_INCREMENT, `tax_name` varchar(100) NOT NULL, `tax_percentage` double NOT NULL, `is_tax_on_final_bill` int(11) NOT NULL DEFAULT '1', `priority` int(11) NOT NULL, `tax_amount` double NOT NULL, PRIMARY KEY (`tax_id`));";
                    $q1[]="CREATE TABLE IF NOT EXISTS `address` (`pincode` int(6) NOT NULL, `address` varchar(100) NOT NULL, `city` varchar(100) NOT NULL, `state` varchar(100) NOT NULL, PRIMARY KEY (`pincode`)) ;";
            
                    $q1[]="ALTER TABLE `$tables[3]` ADD CONSTRAINT `category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `$tables[0]` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
                    $q1[]="ALTER TABLE `$tables[4]` ADD CONSTRAINT `driver_id_fk` FOREIGN KEY (`driver_id`) REFERENCES `$tables[2]` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE, ADD CONSTRAINT `customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `$tables[1]` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
                    $q1[]="ALTER TABLE `$tables[5]` ADD CONSTRAINT `sale_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `$tables[4]` (`sale_order_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
                    $q1[]="ALTER TABLE `$tables[7]` ADD CONSTRAINT `table_category_id_fk` FOREIGN KEY ( `table_category_id` ) REFERENCES `$tables[6]` (`table_category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;";
                    foreach ($q1 as $query) {
                        if(mysql_query($query)){
                            $data['success']=true;
                        }
                        else{
                            $data['message'].="\n".mysql_error();
                            $data['message'].="\n".mysql_errno();
                            $data['message'].="\n$query";
                        }
                    }
					$data['success']=true;
					$data['message']="DB : $db_key";
					$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";$str=substr($actual_link, 0, strrpos($actual_link, '/'));
                    $subject='User confirmation mail';
                    $message="This is the confirmation mail from knx_hotapp<br/>
                    Please click below link to verify your mail address<br>";
                    $email1=urlencode($email);
                    $md=md5($db_key);
                    $url="$str"."/verify.php?"."mail="."$email1"."&key=$md"."&mode=e_verify";
                    $message=$message."<a href='$url'>$url</a>";
                    include 'mail.php';
				}
			}
		}
        $data['error'].=mysql_error();

    }
    elseif($mode=="login"){
    	$email=$request->email;
    	$password=$request->password;
    	if(empty($email)){
    		$error="mail is required ";
    	}
    	elseif(filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false){
   			$error="$email is not a valid email address ";
    	}
    	elseif (empty($password)) {
    		$error="password is required ";
    	}
    	if (!empty($error)) {
    		$data['success']=false;
    		$data['err']=$error;
    	}
    	else{
    		$data['success']=false;
      		include("db.php");
        	if(!$con){
            	$error = 'There was error in connection to DataBase';        
	        }
    	    else{
        	    $email=strtolower($email);
            	$pass=md5($password);
            	$q="SELECT * FROM `employee` WHERE `email` LIKE '$email';";
            	$qr=mysql_fetch_array( mysql_query($q));
            	if($qr['employee_id']){
	                if($qr['email_verify']==0){
    	                $error="Please verify your mail";
        	        }
            	    elseif($qr['status']==0){
                	    $error="Access denied";
                	}
                    
                	else{
                    	$query=mysql_query("SELECT * FROM employee as em JOIN employee_role as er ON em.employee_id=er.employee_id JOIN roles as rol ON er.role_id=rol.role_id WHERE `email` LIKE '$email' AND binary `password` LIKE '$pass'");
                    	$row=mysql_fetch_array($query);
                    	if(($row['role_name']=="super admin")||($row['role_name']=="admin")){
                        	$data['success']=true;
                        	$data['message']="you logged in success fully ";
                            $restaurant_id=$row['restaurant_id'];
                            $query1=mysql_query("SELECT * FROM `restaurant` WHERE `restaurant_id` = '$restaurant_id'");
                            $row1=mysql_fetch_array($query1);
                        	session_start();
                        	$_SESSION["email"]=$email;
                            $_SESSION['db_key']=$row1['db_key'];
                            $_SESSION['branch_id']=$row['branch_id'];
                            $_SESSION['restaurant_name']=$row1['restaurant_name'];
                            $_SESSION['loggedin']=true;
                            // $data['message'].="\n".$email."\n".$row1['db_key']."\n".$row['branch_id'];
                    	}
                        elseif ($row) {
                            $error="sorry you can't access admin page";
                        }
                    	else{
                        	$error="please check password";
                    	}
                	}
            	}
            	else{
              		$error="Email id not exitst ";
            	}
        	}
    		if ( ! empty($error)) {
        		$data['success'] = false;
        		$data['err']  = $error;
    		}
		}
    }
    elseif ($mode=="forgot") {
    	$email = $request->email;
    	$q="SELECT * FROM `employee` WHERE `email`='$email' ";
    	if($row=mysql_fetch_array(mysql_query($q))){
    		$key=$row['email'].$row['employee_id'].$row['password'];
    		$key=md5($key);
			$data['success']=true;
    		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";$str=substr($actual_link, 0, strrpos($actual_link, '/'));
            $subject="Password reset mail";
            $message="This is the password reset mail from knx_hotapp<br/>
             Please click below link to change your password<br>";
            $email1=urlencode($email);
            $url="$str"."/verify.php?"."mail="."$email1"."&key=$key"."&mode=forgot";
            $message=$message."<a href='$url'>$url</a>";
            include 'mail.php';
    	}
    }
    elseif ($mode=="password_reset") {
    	$email=$request->email;
    	$password=$request->password;
    	$key=$request->key;
    	$key=md5($key);
    	$q="SELECT * FROM `employee` WHERE `email`='$email' ";
    	$data['error']=$q;
    	if($row=mysql_fetch_array(mysql_query($q))){
    		$key1=$row['email'].$row['employee_id'].$row['password'];
    		$key2=md5(md5(md5($key1)));
    		$password=md5($password);
    		if($key2==$key){
                if($password==$row['password']){
                    $data['success']=false;
                    $data['error']="The new password should not match with previous password";
                }
    			else{
                    $q2="UPDATE `employee` SET `password`='$password' WHERE `email` = '$email'";
                    if(mysql_query($q2)){
    				    $data['success']=true;
    				    $data['message']="Password reseted successfully";
                    }
                }
            }
    	}
    }
    echo json_encode($data);
    ?>