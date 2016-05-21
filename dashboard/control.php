<?php
	// 
        session_start();
    require_once("../role.php");
    // date_default_timezone_set('UTC');
	$data['success']=false;
	$postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $mode = $request->mode;
    $timezone=date_default_timezone_get();
    if(!isset($_SESSION['email'])||!isset($_SESSION['db_key'])){
        header('Location: ../index.php');
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
    $f=fopen("1.txt", "w");
    fwrite($f,"mode : ".$mode."\ndb_key : "."$db_key"."\nbranch_id : ".$branch_id."\nres_name : ".$restaurant_name."");
    if($mode=="change_password"){
        $pass=md5($request->pass);
        $password=md5($request->password);
        $r_password=md5($request->r_password);
        include("../db.php");
        $q1="SELECT * FROM `employee` WHERE `email` LIKE '$email' and `password`='$pass'";
        $r1=mysql_fetch_array(mysql_query($q1));
        if($r1['employee_id']){
            if($password==$r_password)            {
                $q2="UPDATE `employee` SET `password`='$password' WHERE `email`='$email'";
                if(mysql_query($q2)){
                    $data['success']=true;
                    $data['message']="Password changed successfully";
                }
            }
            else{
                $data['err']="Password mismatch";
            }
        }
        else{
            $data['err']="Enter a vaild current password";
        }
    }
    elseif ($mode=="get_working_time_of_restaurant") {
        include("../db.php");
        $q1="SELECT ti.* FROM `res_time` as ti JOIN employee as em ON em.restaurant_id=ti.restaurant_id WHERE em.email='$email'";
        $data['err']=$q1;
        $q1=mysql_query($q1);
        $r1=mysql_fetch_assoc($q1);
        foreach ($r1 as $key => $value) {
            if(($key!="s.no")&&($key!="restaurant_id")&&($key!="branch_id")){
                $timestamp = strtotime($value);
                $r1[$key] = date("h:i:s a", $timestamp);
                
            }
        }
        $timing=$r1;
        $data['success']=true;
        
        $data['data']=$timing;
    }
    elseif ($mode=="check_table") {
        $tables1 = array();
        $result = mysql_list_tables("$db_key");
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $tables1[$i]= mysql_tablename($result, $i);
        }
        $tables2=array_intersect($tables, $tables1);
        if($tables2==$tables){
            $data['success']=true;
            fwrite($f, " success");
        }
        else {
            $data['success']=false;
            fwrite($f, " error");
        }
    }
    elseif ($mode=="create_first_entry") {
        $c_name = $request->c_name;
        $c_descp = $request->c_descp;
        $c_prior = $request->c_prior;
        $d_name = $request->d_name;
        $d_mobile = $request->d_mobile;
        $p_name = $request->p_descp;
        $p_descp = $request->p_descp;
        $p_prior = $request->p_prior;
        $p_price = $request->p_price;
        $f_type = $request->f_type;
        $p_avail = $request->p_avail;
        $r_name = $request->r_name;
        $t_name = $request->t_name;
        $t_num = $request->t_num;
        $t_chairs = $request->t_chairs;
        $m_type = $request->m_type;
        foreach ($m_type as $key => $value) {
            if($value){
                fwrite($f, $key."\n");
                $p_m_type[]=$key;
            }
        }
        $m_type=implode(", ", $p_m_type);
        if($p_avail=="avail")
            $p_avail=1;
        elseif($p_avail=="medium")
            $p_avail=2;
        else
            $p_avail=0;
        fwrite($f, "\n c_name $c_name \n c_descp $c_descp \n c_prior $c_prior \n d_name $d_name \n d_mobile $d_mobile \n p_name $p_name \n p_descp $p_descp \n p_prior $p_prior \n p_price $p_price \n f_type $f_type \n p_avail $p_avail \n r_name $r_name \n t_name $t_name \n t_num $t_num \n t_chairs $t_chairs \n");
        foreach ($m_type as $key => $value) {
            if($value)
            fwrite($f, "$key\n");
        }
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[0]` (`category_id` int(10) NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL,`desc` varchar(300) NOT NULL,`priority` int(20) NOT NULL,PRIMARY KEY (`category_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[1]` ( `customer_id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `email` varchar(100) DEFAULT NULL,`phone` varchar(100) NOT NULL,`address` varchar(200) DEFAULT NULL,`pincode` varchar(10) DEFAULT NULL,`rand_id` varchar(20) DEFAULT NULL,PRIMARY KEY (`customer_id`),UNIQUE KEY `rand_id` (`rand_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[2]` ( `driver_id` int(10) NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, `phone` varchar(100) NOT NULL, PRIMARY KEY (`driver_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[3]` ( `item_id` int(10) NOT NULL AUTO_INCREMENT, `item_name` varchar(100) NOT NULL, `item_desc` varchar(300) DEFAULT NULL, `priority` int(11) NOT NULL, `max_order` int(11) NOT NULL, `price` double NOT NULL, `category_id` int(11) NOT NULL, `tax_id` int(11) NOT NULL, `meal_type` varchar(100) NOT NULL, `food_type` varchar(100) NOT NULL, `is_option_available` int(11) NOT NULL DEFAULT '0', `availability` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`item_id`), KEY `categoryID` (`category_id`), KEY `tax_id` (`tax_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[4]` ( `sale_order_id` int(10) NOT NULL AUTO_INCREMENT, `customer_id` int(10) DEFAULT NULL, `status` varchar(20) NOT NULL DEFAULT 'preparation', `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP, `is_driver` tinyint(1) NOT NULL, `portal` varchar(100) DEFAULT 'own', `driver_id` int(10) DEFAULT NULL, `bill_paid` tinyint(1) DEFAULT '0', `special_notes` varchar(100) DEFAULT NULL, `table_id` int(11) DEFAULT NULL, `order_type` varchar(20) NOT NULL, `rand_id` varchar(20) DEFAULT NULL,`server_id` varchar(4) DEFAULT '1', PRIMARY KEY (`sale_order_id`), UNIQUE KEY `rand_id` (`rand_id`), KEY `customer_id` (`customer_id`), KEY `customer_id_2` (`customer_id`), KEY `customer_id_3` (`customer_id`), KEY `driver_id` (`driver_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[5]` ( `order_line_id` int(10) NOT NULL AUTO_INCREMENT, `order_id` int(10) NOT NULL, `product_id` int(10) NOT NULL, `product_name` varchar(100) NOT NULL, `product_template_id` int(10) NOT NULL, `qty` int(10) NOT NULL, `uom` int(10) NOT NULL, `price` float NOT NULL, `discount` float DEFAULT NULL, `special_notes` varchar(100) DEFAULT NULL, `extra_ids` varchar(100) DEFAULT NULL, `TEST` varchar(255) NOT NULL, PRIMARY KEY (`order_line_id`), KEY `sale_order_line_ibfk_1` (`order_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[6]` ( `table_category_id` int(10) NOT NULL AUTO_INCREMENT, `table_category_name` varchar(100) NOT NULL, `no_of_tables` int(10) NOT NULL, PRIMARY KEY (`table_category_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[7]` ( `table_id` int(10) NOT NULL AUTO_INCREMENT, `table_category_id` int(10) DEFAULT NULL, `table_name` varchar(100) NOT NULL, `capacity` int(100) NOT NULL, PRIMARY KEY (`table_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `$tables[8]` ( `tax_id` int(20) NOT NULL AUTO_INCREMENT, `tax_name` varchar(100) NOT NULL, `tax_percentage` double NOT NULL, `is_tax_on_final_bill` int(11) NOT NULL DEFAULT '1', `priority` int(11) NOT NULL, `tax_amount` double NOT NULL, PRIMARY KEY (`tax_id`));";
            $q1[]="CREATE TABLE IF NOT EXISTS `address` (`pincode` int(6) NOT NULL, `address` varchar(100) NOT NULL, `city` varchar(100) NOT NULL, `state` varchar(100) NOT NULL, PRIMARY KEY (`pincode`)) ;";
            
            $q1[]="ALTER TABLE `$tables[3]` ADD CONSTRAINT `category_id_fk` FOREIGN KEY (`category_id`) REFERENCES `$tables[0]` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $q1[]="ALTER TABLE `$tables[4]` ADD CONSTRAINT `driver_id_fk` FOREIGN KEY (`driver_id`) REFERENCES `$tables[2]` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE, ADD CONSTRAINT `customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `$tables[1]` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $q1[]="ALTER TABLE `$tables[5]` ADD CONSTRAINT `sale_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `$tables[4]` (`sale_order_id`) ON DELETE CASCADE ON UPDATE CASCADE;";
            $q1[]="ALTER TABLE `$tables[7]` ADD CONSTRAINT `table_category_id_fk` FOREIGN KEY ( `table_category_id` ) REFERENCES `$tables[6]` (`table_category_id`) ON DELETE RESTRICT ON UPDATE RESTRICT ;";
            
            $q1[]="INSERT INTO `$tables[0]` (`name`, `desc`, `priority`) VALUES ('$c_name','$c_descp','$c_prior');";
            $q1[]="INSERT INTO `$tables[1]` (`name`, `email`, `phone`, `address`, `pincode`, `rand_id`) VALUES ('default','$email','null','default address','000000','00000');";
            $q1[]="INSERT INTO `$tables[2]` (`name`, `phone`) VALUES ('$d_name','$d_mobile');";
            $q1[]="INSERT INTO `$tables[3]` (`item_name`, `item_desc`, `priority`, `max_order`, `price`, `category_id`, `tax_id`, `meal_type`, `food_type`, `is_option_available`, `availability`) VALUES ('$p_name','$p_descp','$p_prior','$p_max','$p_price','1','null','$m_type','$f_type','0','$p_avail');";
            $q1[]="INSERT INTO `$tables[6]` (`table_category_name`, `no_of_tables`) VALUES ('$r_name','$t_num');";
            $q1[]="INSERT INTO `$tables[7]` (`table_category_id`, `table_name`, `capacity`) VALUES ('1','$t_name','$t_num');";
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
    }

    elseif ($mode=="dashboard-details"){
        $startDate=date("Y-m-d G:i:s", strtotime("-1 days"));
        $endDate=date("Y-m-d G:i:s");
        $cat_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[0]`"));
        $prod_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[3]`"));
        $order_count=mysql_num_rows(mysql_query("SELECT * FROM `$tables[4]` WHERE `status` NOT LIKE 'm(%)' AND `order_date` BETWEEN '$startDate' AND '$endDate'ORDER BY `order_date` DESC "));
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
        date_default_timezone_set($timezone);
        $data['todo_data']=$todo;
    }
    elseif ($mode=="category_get_all") {
        $q1=mysql_query("SELECT * FROM `$tables[0]`");
        while ($r1=mysql_fetch_assoc($q1)) {
            $category_all[]=$r1;
            $data['success']=true;
        }
        $data['data']=$category_all;
    }
    elseif ($mode=="category_insert"){
        $c_name = $request->c_name;
        $c_descp = $request->c_descp;
        $c_prior = $request->c_prior;
        $q1="INSERT INTO `$tables[0]` (`name`, `desc`, `priority`) VALUES ('$c_name','$c_descp','$c_prior');";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in inserting into DB";
    }
    elseif ($mode=="category_update"){
        $c_id = $request->c_id;
        $c_name = $request->c_name;
        $c_descp = $request->c_descp;
        $c_prior = $request->c_prior;
        $q1="UPDATE `$tables[0]` SET `name`='$c_name',`desc`='$c_descp',`priority`='$c_prior' WHERE `category_id`='$c_id';";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in updating into DB";
    }
    elseif ($mode=="category_delete") {
        $c_id = $request->c_id;
        $q1="DELETE FROM `$tables[0]` WHERE `category_id`='$c_id'";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error on deleting in DB";
    }
    elseif ($mode=="product_cat_get_all"){
        $q1=mysql_query("SELECT * FROM `$tables[3]`");
        if($q1)
        while ($r1=mysql_fetch_assoc($q1)) {
            $product_all[]=$r1;
            $data['success']=true;
        }
        $data['data']=$product_all;
        $q2=mysql_query("SELECT * FROM `$tables[0]`");
        while ($r2=mysql_fetch_assoc($q2)) {
            $category_all[]=$r2;
            $data['success']=true;
        }
        $data['data1']=$category_all;
    }
    elseif ($mode=="product_insert"){
        $p_name = $request->p_name;
        $p_descp = $request->p_descp;
        $p_prior = $request->p_prior;
        $p_max = $request->p_max;
        $p_price = $request->p_price;
        $c_id = $request->c_id;
        $m_type = $request->m_type;
        $f_type = $request->f_type;
        $p_avail = $request->p_avail;
        foreach ($m_type as $key => $value) {
            if($value){
                fwrite($f, $key."\n");
                $p_m_type[]=$key;
            }
        }
        $m_type=implode(", ", $p_m_type);
        if($p_avail=="avail")
            $p_avail=1;
        elseif($p_avail=="medium")
            $p_avail=2;
        else
            $p_avail=0;
        $q1="INSERT INTO `$tables[3]` (`item_name`, `item_desc`, `priority`, `max_order`, `price`, `category_id`, `tax_id`, `meal_type`, `food_type`, `is_option_available`, `availability`) VALUES ('$p_name','$p_descp','$p_prior','$p_max','$p_price','$c_id','null','$m_type','$f_type','0','$p_avail');";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in inserting into DB $q1";
    }
    elseif ($mode=="product_update"){
        $p_id = $request->p_id;
        $p_name = $request->p_name;
        $p_descp = $request->p_descp;
        $p_prior = $request->p_prior;
        $p_max = $request->p_max;
        $p_price = $request->p_price;
        $c_id = $request->c_id;
        $m_type = $request->m_type;
        $f_type = $request->f_type;
        $p_avail = $request->p_avail;
        foreach ($m_type as $key => $value) {
            if($value){
                fwrite($f, $key."\n");
                $p_m_type[]=$key;
            }
        }
        $m_type=implode(", ", $p_m_type);
        if($p_avail=="avail")
            $p_avail=1;
        elseif($p_avail=="medium")
            $p_avail=2;
        else
            $p_avail=0;
        $q1="UPDATE `$tables[3]` SET `item_name`='$p_name',`item_desc`='$p_descp',`priority`='$p_prior',`max_order`='$p_max',`price`='$p_price',`category_id`='$c_id',`tax_id`='null',`meal_type`='$m_type',`food_type`='$f_type',`is_option_available`='0',`availability`='$p_avail' WHERE `item_id`='$p_id';";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in inserting into DB $q1";
    }
    elseif ($mode=="product_delete") {
        $p_id = $request->p_id;
        $q1="DELETE FROM `$tables[3]` WHERE `item_id`='$p_id'";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error on deleting in DB";
    }
    elseif ($mode=="user_get_all_from_admin") {
        include("../db.php");
        $q1=mysql_query("SELECT em.email,em.employee_id,er.role_id FROM employee as em JOIN restaurant as res  ON em.restaurant_id=res.restaurant_id JOIN employee_role as er ON em.employee_id=er.employee_id JOIN roles as rol ON er.role_id=rol.role_id WHERE res.db_key='$db_key' AND rol.role_name != 'super admin' AND rol.role_name != 'customer'");
        while ($r1=mysql_fetch_assoc($q1)) {
            $users[]=$r1;
            // $q2=mysql_query("SELECT p.perm_id,p.perm_desc FROM permissions as p JOIN role_perm as rp ")
        }
        $q2=mysql_query("SELECT * FROM `roles` WHERE `role_name` != 'super admin' AND `role_name` != 'customer'");
        while ($r2=mysql_fetch_assoc($q2)) {
            $roles[]=$r2;
        }
        $data['success']=true;
        $data['users']=$users;
        $data['roles']=$roles;
    }
    elseif ($mode=="user_insert_from_admin") {
        include("../db.php");
        $email=$request->email;
        $password=md5($request->password);
        $role_id=$request->r_id;
        $q=mysql_fetch_assoc(mysql_query("SELECT * FROM `employee` WHERE `email` LIKE '$email'"));
        if(!$q['employee_id']){
            $q1=mysql_fetch_array(mysql_query("SELECT `restaurant_id` FROM `restaurant` WHERE `db_key`='$db_key'"));
            $restaurant_id=$q1['restaurant_id'];
            $q1=mysql_query("INSERT INTO `employee`(`email`, `password`, `email_verify`, `restaurant_id`, `branch_id`, `status`) VALUES ('$email','$password','1','$restaurant_id','$branch_id','1')");
            $employee_id=mysql_insert_id();
            $q2="INSERT INTO `employee_role`(`employee_id`, `role_id`) VALUES ('$employee_id','$role_id')";
            $q3=mysql_query($q2);
            if($q3)
            $data['success']=true;
        }$data['err']="Sorry email id already exists";
    }
    elseif ($mode=="user_update_from_admin") {
        include("../db.php");
        $email=$request->email;
        $password=$request->password;
        $role_id=$request->r_id;
        $employee_id=$request->user_id;
        if(empty($password))
            $q="UPDATE `employee` SET `email`='$email' WHERE `employee_id`='$employee_id'";
        else{
            $password=md5($password);
            $q="UPDATE `employee` SET `email`='$email',`password`='$password' WHERE `employee_id`='$employee_id'";
        }
        if(mysql_query($q)){
            $q="UPDATE `employee_role` SET `role_id`='$role_id' WHERE  `employee_id`='$employee_id'";
            if(mysql_query($q))
                $data['success']=true;
        }
    }
    elseif ($mode=="user_delete_from_admin") {
        include("../db.php");
        $employee_id=$request->e_id;
        $q1="DELETE FROM `employee` WHERE `employee_id`='$employee_id'";
        if(mysql_query($q1))
            $data['success']=true;
        $data['err']="$q1 ".mysql_error();
    }
    elseif ($mode=="driver_get_all") {
        $q1=mysql_query("SELECT * FROM `$tables[2]`");
        while ($r1=mysql_fetch_assoc($q1)) {
            $category_all[]=$r1;
            $data['success']=true;
        }
        $data['data']=$category_all;
    }
    elseif ($mode=="order_get_all") {
        // $q1=mysql_query("SELECT * FROM `$tables[4]` WHERE `status`='confirm' OR `status`='delivered' OR `order_type`='collection' ORDER BY `order_date` DESC ");
        $q1=mysql_query("SELECT * FROM `$tables[4]` WHERE `status` NOT LIKE 'm(%)' ORDER BY `order_date` DESC ");
        while ($r1=mysql_fetch_assoc($q1)) {
            if ($r1['driver_id']) {
                $q2=mysql_fetch_assoc(mysql_query("SELECT `name` FROM `$tables[2]` WHERE `driver_id`=".$r1['driver_id'].";"));
                $r1['driver_name']=$q2['name'];
            }
            else{
                $r1['driver_name']="--";
            }
            $order_all[]=$r1;
            $data['success']=true;
        }
        $data['err']="Error on get all order from DB";
        $data['data']=$order_all;
    }
    elseif ($mode=="order_get_pdf") {
        $order_id=$request->order_id;
        $q1="SELECT `sale_order_id` , `order_date`,`order_type` FROM `$tables[4]` WHERE `sale_order_id`='$order_id'";
        
        if($q1=mysql_query($q1)){
            $r1=mysql_fetch_assoc($q1);
            $data['success']=true;
            // if($r1['order_type']=="dine_in"){
                $loc1="$db_key/counter/$branch_id"."_"."$order_id"."_".$r1['order_date'].".pdf";
            // }
            // else{
                $loc2="$db_key/kitchen/$branch_id"."_"."$order_id"."_".$r1['order_date'].".pdf";
            // }
            $data['pdffile1']=$loc1;
            $data['pdffile2']=$loc2;
        }
        $data['err']="$q1";
    }
    elseif ($mode=="order_pay"){
        $sale_order_id = $request->order_id;
        $q1=mysql_fetch_assoc(mysql_query("SELECT * FROM `$tables[4]` WHERE `sale_order_id`='$sale_order_id'"));
        if($q1['order_type']=="collection"){
            mysql_query("UPDATE `$tables[4]` SET `status`='delivered' WHERE `sale_order_id`='$sale_order_id';");
        }
        $q2="UPDATE `$tables[4]` SET `bill_paid`='1' ,`status`='confirm' WHERE `sale_order_id`='$sale_order_id';";
        if(mysql_query($q2)){
            $data['success']=true;
            $dir="counter";
            include("pdf gen.php");
        }
        else
            $data['err']="Error in updating into DB";
    }
    elseif ($mode=="driver_insert"){
        $d_name = $request->d_name;
        $d_mobile = $request->d_mobile;
        $q=mysql_fetch_assoc(mysql_query("SELECT * FROM `$tables[2]` WHERE `phone`='$d_mobile'"));
        if(!$q['driver_id']){
            $q1="INSERT INTO `$tables[2]` (`name`, `phone`) VALUES ('$d_name','$d_mobile');";
            if(mysql_query($q1)){
                $data['success']=true;
            }
        }
        else
            $data['err']="Driver phone number already exists please try with different phone number";
    }
    elseif ($mode=="driver_update"){
        $d_id = $request->d_id;
        $d_name = $request->d_name;
        $d_mobile = $request->d_mobile;
        $q1="UPDATE `$tables[2]` SET `name`='$d_name',`phone`='$d_mobile' WHERE `driver_id`='$d_id';";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in updating into DB";
    }
    elseif ($mode=="driver_delete") {
        $d_id = $request->d_id;
        $q1="DELETE FROM `$tables[2]` WHERE `driver_id`='$d_id'";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error on deleting in DB $q1";
    }
    elseif ($mode=="room_get_all") {
        $q1=mysql_query("SELECT * FROM `$tables[6]`");
        while ($r1=mysql_fetch_assoc($q1)) {
            $room_all[]=$r1;
            $data['success']=true;
        }
        $data['data']=$room_all;
    }
    elseif ($mode=="room_insert") {
        $r_name = $request->r_name;
        $t_num = $request->t_num;
        $q=mysql_fetch_assoc(mysql_query("SELECT * FROM `$tables[6]` WHERE `table_category_name`='$r_name'"));
        if(!$q['table_category_id']){
            $q1="INSERT INTO `$tables[6]` (`table_category_name`, `no_of_tables`) VALUES ('$r_name','$t_num');";
            if(mysql_query($q1)){
                $data['success']=true;
            }
        }
        else
            $data['err']="Sorry room name already exists";    
    }
    elseif ($mode=="room_update"){
        $r_id = $request->r_id;
        $r_name = $request->r_name;
        $t_num = $request->t_num;
        $q1="UPDATE `$tables[6]` SET `table_category_name`='$r_name',`no_of_tables`='$t_num' WHERE `table_category_id`='$r_id';";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in updating into DB";
    }
    elseif ($mode=="room_delete") {
        $r_id = $request->r_id;
        $q1="DELETE FROM `$tables[6]` WHERE `table_category_id`='$r_id'";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error on deleting in DB";
    }
    elseif ($mode=="table_get_all") {
        $q1=mysql_query("SELECT * FROM `$tables[7]`");
        while ($r1=mysql_fetch_assoc($q1)) {
            $table_all[]=$r1;
            $data['success']=true;
        }
        $data['data']=$table_all;
        $q2=mysql_query("SELECT * FROM `$tables[6]`");
        while ($r2=mysql_fetch_assoc($q2)) {
            $room_all[]=$r2;
            $data['success']=true;
        }
        $data['data1']=$room_all;
    }
    elseif ($mode=="table_insert") {
        $r_id = $request->r_id;
        $t_name = $request->t_name;
        $t_chairs = $request->t_chairs;
        $q=mysql_fetch_assoc(mysql_query("SELECT * FROM `$tables[7]` WHERE `table_name`='$t_name' AND `table_category_id`='$r_id' "));
        if(!$q['table_id']){
            $q1="INSERT INTO `$tables[7]` (`table_category_id`, `table_name`, `capacity`) VALUES ('$r_id','$t_name','$t_chairs');";
            if(mysql_query($q1)){
                $data['success']=true;
            }
        }
        else
            $data['err']="Sorry table name already exists";    
    }
    elseif ($mode=="table_update"){
        $r_id = $request->r_id;
        $t_id = $request->t_id;
        $t_name = $request->t_name;
        $t_chairs = $request->t_chairs;
        $q1="UPDATE `$tables[7]` SET `table_category_id`='$r_id',`table_name`='$t_name',`capacity`='$t_chairs' WHERE `table_id`='$t_id';";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error in updating into DB";
    }
    elseif ($mode=="table_delete") {
        $t_id = $request->t_id;
        $q1="DELETE FROM `$tables[7]` WHERE `table_id`='$t_id'";
        if(mysql_query($q1)){
            $data['success']=true;
        }
        else
            $data['err']="Error on deleting in DB";
    }
    elseif ($mode=="calendar_create_event") {
        include ("../db.php");
        $title = $request->title;
        $color = $request->color;
        $q1="INSERT INTO `event_list`(`title`, `color`, `status`,`db_key`) VALUES ('$title','$color',1,'$db_key')";
        if(mysql_query($q1)){
            $data['success']=true;
            $data['id']=mysql_insert_id();
        }
    }
    elseif ($mode=="calendar_insert_event") {
        $id = $request->id;
        $start = $request->start;
        $end = $request->end;
        include ("../db.php");
        $q1="INSERT INTO `calendar_events`(`event_id`, `start`, `end`) VALUES ('$id','$start','$end')";
        if(mysql_query($q1)){
            $data['success']=true;
            $data['id']=mysql_insert_id();
        }
    }
    elseif ($mode=="calendar_update_event") {
        $id = $request->id;
        $start = $request->start;
        $end = $request->end;
        include ("../db.php");
        $q1="UPDATE `calendar_events` SET `start`='$start',`end`='$end' WHERE `id`='$id'";
        if(mysql_query($q1)){
            $data['success']=true;
            $data['rows']=mysql_affected_rows();
        }
    }
    elseif ($mode=="calendar_deact_event") {
        $id = $request->id;
        include ("../db.php");
        $q1="UPDATE `event_list` SET `status`=0 WHERE `id`='$id'";
        if(mysql_query($q1)){
            $data['success']=true;
            $data['rows']=mysql_affected_rows();
        }
    }
    elseif ($mode=="calender_get_all_event_list") {
        include ("../db.php");
        $query="SELECT * FROM `event_list` WHERE `status`!=0 AND `db_key`='$db_key'";
        $r1=mysql_query($query);
        if($r1){
            $data['success']=true;
        }
        while ($row1=mysql_fetch_assoc($r1)) {
            $result[]=$row1;
        }
        $data['event_list']=$result;
    }
    elseif ($mode=="event_delete") {
        $id = $request->event_id;
        include ("../db.php");
        $q1="DELETE FROM `calendar_events` WHERE `id`='$id'";
        if(mysql_query($q1)){
            $data['success']=true;
            $data['rows']=mysql_affected_rows();
        }
    }
    elseif ($mode=="sales_report_by_date") {

        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));
        $a = new DateTime($startDate);
        $b = new DateTime($endDate);
        $s_r_b_d=fopen("s_r_b_d.txt", "w");
        fwrite($s_r_b_d, $startDate."  \n ".$endDate);
        // $timezone=date_default_timezone_get();
        // date_default_timezone_set('UTC');

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
        }
        // date_default_timezone_set($timezone);
        $jsdata=$report;
        $data['success'] = true;
        $data['report'] = $jsdata;
    }
    elseif ($mode=="sales_product_report_by_date") {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));
        $a = new DateTime($startDate);
        $b = new DateTime($endDate);
        if ($a->diff($b)->format('%d')==0) {
            $startDate=date("Y-m-d", strtotime($startDate));
        }

        $q1="SELECT * FROM `$tables[4]` WHERE `order_date` BETWEEN '$startDate' AND '$endDate';";
        $q1=mysql_query($q1);
        while ($r1=mysql_fetch_array($q1)) {
            $order_id=$r1['sale_order_id'];
            $q2="SELECT * FROM `$tables[5]` WHERE `order_id` ='$order_id'";
            $q2=mysql_query($q2);
            while ($r2=mysql_fetch_array($q2)) {
                $report[$r2['product_name']]+=$r2['qty'];
            }
        }
        $i=0;
        foreach ($report as $key => $value) {
            $prod_report1[$i]['day']=$key;
            $prod_report1[$i]['a']=$value;
            $prod_report2[$i]['label']=$key;
            $prod_report2[$i]['value']=$value;
            $i++;
        }
        $jsdata['data1']=$prod_report1;
        $jsdata['data2']=$prod_report2;

        $data['success'] = true;
        $data['report'] = $jsdata;
    }
    elseif ($mode=="sales_area_report_by_date") {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));

        $q1="SELECT * FROM `$tables[4]` WHERE `order_type`='delivery' AND `order_date` BETWEEN '$startDate' AND '$endDate';";
        $q1=mysql_query($q1);
        while ($r1=mysql_fetch_array($q1)) {
            $order_id=$r1['sale_order_id'];
            $customer_id=$r1['customer_id'];
            $q2="SELECT ad.address,ad.city FROM address as ad JOIN $tables[1] as cus ON cus.pincode=ad.pincode WHERE cus.customer_id='$customer_id'";
            $q2=mysql_query($q2);
            while ($r2=mysql_fetch_array($q2)) {
                $report[$r2['address']]+=1;
            }
        }
            $col1["id"]="A";$col1["label"]="Product name";$col1["type"]="string";
            $col2["id"]="B";$col2["label"]="";$col2["type"]="number";
            $cols=array($col1,$col2);
            $rows=array();
            foreach ($report as $key => $value) {
                $cell1["v"]=$key;
                $cell2["v"]=$value;
                $cell2["f"]="orders:$value";
                $row["c"]=array($cell1,$cell2);
                array_push($rows,$row);
            }
            $jsdata=array("cols"=>$cols,"rows"=>$rows);

        $data['success'] = true;
        $data['report'] = $jsdata;
    }
    elseif ($mode=="sales_type_report_by_date") {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));

        $q1="SELECT * FROM `$tables[4]` WHERE `order_date` BETWEEN '$startDate' AND '$endDate';";
        $q1=mysql_query($q1);
        while ($r1=mysql_fetch_array($q1)) {
                $report[$r1['order_type']]+=1;
        }
            $col1["id"]="A";$col1["label"]="order type";$col1["type"]="string";
            $col2["id"]="B";$col2["label"]="";$col2["type"]="number";
            $cols=array($col1,$col2);
            $rows=array();
            foreach ($report as $key => $value) {
                $cell1["v"]=$key;
                $cell2["v"]=$value;
                $cell2["f"]="orders:$value";
                $row["c"]=array($cell1,$cell2);
                array_push($rows,$row);
            }
            $jsdata=array("cols"=>$cols,"rows"=>$rows);

        $data['success'] = true;
        $data['report'] = $jsdata;
    }
    elseif ($mode=="cancel_report_by_date") {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));

        $q1="SELECT * FROM `$tables[4]` WHERE  `status` LIKE 'cancel' AND `order_date` BETWEEN '$startDate' AND '$endDate';";
        $q1=mysql_query($q1);
        while ($r1=mysql_fetch_array($q1)) {
            $order_id=$r1['sale_order_id'];
            $server_id=$r1['server_id'];
            include ("../db.php");
            $q2=mysql_fetch_assoc(mysql_query("SELECT `email` FROM employee WHERE `employee_id`='$server_id'"));
            $server_name=$q2['email'];
            $report[$server_name]+=1;
            
        }
            $i=0;
            foreach ($report as $key => $value) {
                $jsdata1[$i]["email"]=$key;
                $jsdata1[$i]['count']=$value;
                $jsdata2[$i]['label']=$key;
                $jsdata2[$i]['value']=$value;
                $i++;
            }
        $jsdata['data1']=$jsdata1;
        $jsdata['data2']=$jsdata2;
        $data['success'] = true;
        $data['report'] = $jsdata;
    }
    elseif ($mode=="delivery_report_by_date") {
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));
        // $ew=fopen("err.txt", "a");
        // fwrite($ew,"$startDate  $endDate");
        $q1="SELECT * FROM `$tables[4]` WHERE  `status` LIKE 'delivered' AND `order_date` BETWEEN '$startDate' AND '$endDate';";
        $q1=mysql_query($q1);
        while ($r1=mysql_fetch_array($q1)) {
            $order_id=$r1['sale_order_id'];
            $driver_id=$r1['driver_id'];
            $report[$driver_id]+=1;
        }
        $i=0;
        foreach ($report as $key => $value) {
            $q2=mysql_fetch_array(mysql_query("SELECT * FROM `$tables[2]` WHERE `driver_id`=$key"));
            $jsdata1[$i]["name"]=$q2['name'];
            $jsdata1[$i]['count']=$value;
            $jsdata2[$i]['label']=$q2['name'];
            $jsdata2[$i]['value']=$value;
            $i++;
        }
        $jsdata['data1']=$jsdata1;
        $jsdata['data2']=$jsdata2;
        $data['success'] = true;
        $data['report'] = $jsdata;
    }
    elseif ($mode=="customer_report_by_date") {

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $customer_id = $request->id;
        $startDate=date("Y-m-d G:i:s", strtotime($startDate));
        $endDate=date("Y-m-d G:i:s", strtotime($endDate));
        $a = new DateTime($startDate);
        $b = new DateTime($endDate);

        if ($a->diff($b)->format('%y')!=0) {
            $report = customer_data_date('%Y','Y',$startDate,$endDate,$customer_id);
        }
        elseif ($a->diff($b)->format('%m')!=0) {
            $report = customer_data_date('%Y-%m','F',$startDate,$endDate,$customer_id);
        }
        elseif ($a->diff($b)->format('%d')>=8) {
            $report = customer_data_date('%Y-%m-%d','jS F',$startDate,$endDate,$customer_id);
        }
        elseif ($a->diff($b)->format('%d')!=0) {
            $report = customer_data_date('%Y-%m-%d','l',$startDate,$endDate,$customer_id);
        }
        elseif ($a->diff($b)->format('%d')==0) {
            $report = customer_data_date('%Y-%m-%d %H','ha D',$startDate,$endDate,$customer_id);
        }
        
        $jsdata=$report;
        $data['success'] = true;
        $data['report'] = $jsdata;
        $q1=mysql_query("SELECT * FROM `$tables[1]` WHERE `customer_id`!=1");
        while ($r1=mysql_fetch_assoc($q1)) {
            $customer_all[]=$r1;
            $data['success']=true;
        }
        $data['customer']=$customer_all;
    }
    else{
        $data['err']="mode is not enabled";
    }
    echo json_encode($data);
    fclose($f);
    function random_id()
    {
        $random_id_length = 10; 
        $rnd_id = crypt(uniqid(rand(),1)); 
        $rnd_id = strip_tags(stripslashes($rnd_id)); 
        $rnd_id = str_replace(".","",$rnd_id); 
        $rnd_id = strrev(str_replace("/","",$rnd_id)); 
        $rnd_id = substr($rnd_id,0,$random_id_length); 
        return $rnd_id;

    }
    function sanitize($string)
    {
        return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    function san_char($string)
    {
        if($string != preg_replace('/[^A-Za-z\s\_]/', '', $string)){
            echo "false char";
            exit();
        }
        else
            return $string;
    }
    function san_charnum($string)
    {
        if($string != preg_replace('/[^A-Za-z0-9\s\_]/', '', $string)){
            echo "false charnum";
            exit();
        }
        else
            return $string;
    }
    function san_int($string)
    {
        if($string !=filter_var($string, FILTER_SANITIZE_NUMBER_INT)){
            echo "false int";
            exit();
        }
        else
            return $string;
    }
    function san_float($string)
    {
        if($string !=filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION)){
            echo "false float";
            exit();
        }
        else
            return $string;
    }
    function san_email($string)
    {
        if($string != filter_var($string, FILTER_SANITIZE_EMAIL)){
            echo "false email";
            exit();
        }
        else
            return $string;
    }
    function san_arr_int($string)
    {
        if($string !== filter_var_array($string,FILTER_SANITIZE_NUMBER_INT)){
            echo "false arr int";
            exit();
        }
        else
            return $string;
    }
    function san_arr_float($string)
    {
        if($string !=filter_var_array($string,FILTER_VALIDATE_FLOAT)){
            echo "false arr float\n";
            exit();
        }
        else
            return $string;
    }
    function san_arr_char($string)
    {   
        foreach ($string as $key => $value) {

            if(!preg_match('/^[a-zA-Z0-9().,-\/\:\s\&\_\"]*$/', $value)){
                echo "false arr char ";
                exit();
            }
        }
        return $string;
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
    $f=fopen("2.txt", "w");
    fwrite($f, "1: $q1\n");
    fwrite($f, "p1 $pattern\n p2 $pattern1\n start $startD \n end $endD");
    // echo "$q1 <br>";
    while ($r1=mysql_fetch_array($e1)) {
        $date=$r1['date'];

    fwrite($f, "date $date \n".date_default_timezone_get()."\n");
        // echo "date :$date <br>";
        $q2 =mysql_query( "SELECT * FROM `$tables[4]` WHERE `order_date` LIKE '%$date%'");
        // fwrite($f, "2: $q2\n");
        while ($r2=mysql_fetch_array($q2)) {
            $s_o_id=$r2['sale_order_id'];
            $order_ids.=" ".$r2['sale_order_id'];
            $date1=$r2['order_date'];
            // echo " id: $s_o_id ";
            $total_order=0;
            $q3=mysql_query("SELECT `qty` , `price` , `discount` FROM `$tables[5]` WHERE `order_id` ='$s_o_id'");
            // fwrite($f, "3: $q3\n");
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
        if ($pattern1=='Y'){
            $price[$i]['day']=$date;
            $price1[$i]['label']=$date;
        }
        elseif($pattern1=='ha D') {
            $price[$i]['day']=date($pattern1,strtotime($date1))."";
            // $price[$i]['day']=date($pattern1,strtotime($date1))."$date $date1 $s_o_id ".date("Y-m-d H:i:s");
            $price1[$i]['label']=date($pattern1,strtotime($date1));
        }
        else {
            $price[$i]['day']=date($pattern1,strtotime($date))."";
            $price1[$i]['label']=date($pattern1,strtotime($date));
        }
        $price[$i]['a']=$price_each*0.95*1.12;
        $price1[$i]['value']=$price_each*0.95*1.12;
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

    function customer_data_date($pattern,$pattern1,$startD,$endD,$id)
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
    $q1="SELECT DISTINCT DATE_FORMAT( `order_date` , '$pattern' ) AS date FROM `$tables[4]` WHERE  `customer_id`='$id' AND `order_date` BETWEEN '$startD' AND '$endD' ORDER BY DATE( `order_date` ) DESC LIMIT 0 , 30";
    // $we=fopen("2.txt","w");
    // fwrite($we, "$q1");
    $e1=mysql_query($q1);
    // $f=fopen("2.txt", "w");
    // fwrite($f, "1: $q1\n");
    // echo "$q1 <br>";
    while ($r1=mysql_fetch_array($e1)) {
        $date=$r1['date'];
        // echo "date :$date <br>";
        $q2 =mysql_query( "SELECT `sale_order_id` FROM `$tables[4]` WHERE `order_date` LIKE '%$date%' AND `customer_id`='$id'");
        // fwrite($f, "2: $q2\n");
        // $r1=mysql_num_rows($q2);
        if ($pattern1!='Y'){
            $report1[$i]['day']=date($pattern1,strtotime($date));
            $report2[$i]['label']=date($pattern1,strtotime($date));
        }
        else {
            $report1[$i]['day']=$date;
            $report2[$i]['label']=$date;
        }
        $report1[$i]['count']=mysql_num_rows($q2);
        $report2[$i]['value']=mysql_num_rows($q2);
        $i++;
   }
   // echo "price month: $price_final <br>";
   // // print_r($price);
   // fwrite($f, print_r($price));
   $report['data1']=$report1;
   $report['data2']=$report2;
   return $report;
}

?>