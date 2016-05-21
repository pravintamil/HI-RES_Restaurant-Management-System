<?php
    include('../db.php');
    $mode=san_char(urldecode($_POST['mode']));
    $error= fopen("error.txt", "a");
    $employee_id=san_int(urldecode($_POST['employee_id']));
    if($employee_id<=0){
        if ($mode=="login") {
            $email = san_email(urldecode($_POST['email']));
            $pass = md5(urldecode($_POST['pass']));
            $q="SELECT emp.employee_id FROM employee as emp JOIN restaurant as res ON emp.restaurant_id=res.restaurant_id WHERE emp.email='$email' AND emp.password='$pass'";
            $r=mysql_fetch_array(mysql_query($q));
            $id=$r['employee_id'];
            if ($id) {
                echo "$id";
                exit(); 
            }
            else{
                echo "sorry can't login";
                exit();
            }
        }
        else{
            echo "please send data with employee_id";
            exit();
        }

    }
    else{
        $q="SELECT res.db_key,emp.branch_id FROM employee as emp JOIN restaurant as res ON emp.restaurant_id=res.restaurant_id WHERE emp.employee_id='$employee_id'";
        $r=mysql_fetch_array(mysql_query($q));
        $db_key=$r['db_key'];
        mysql_select_db("$db_key");
        $branch_id = $r['branch_id'];
    }
    $tables = array('address','category','customer','driver','product','sale_order','sale_order_line','table_category','table_details');
    foreach ($tables as $key => $value) {
        if($value!="address")
        $tables[$key]=$value."_".$branch_id;
    }
    //create the if non empty of name and phone
    if($mode=="customer_create"){                       /*testing passed*/
        $name = san_char(urldecode($_POST['name']));
        $email = san_email(urldecode($_POST['email']));
        $pincode = san_int(urldecode($_POST['pin']));
        $phone = san_int(urldecode($_POST['phone']));
        if($pincode!=""){
            $url = "https://www.whizapi.com/api/v2/util/ui/in/indian-city-by-postal-code?pin=$pincode&project-app-key=az21s4wavyyxl698cocjzpio";
            $json = json_decode(file_get_contents($url), true);
        if($json['ResponseMessage']=="OK"){
                $address = $json['Data'][0]['Address'];
                $city = $json['Data'][0]['City'];
                $state = $json['Data'][0]['State'];
                $qa=mysql_query("INSERT INTO `$tables[0]`(`pincode`, `address`, `city`, `state`) VALUES ('$pincode','$address','$city','$state')");
            }
        }
        $rand_id=random_id();
        $address=urldecode($_POST['addr']);
        if(!empty($name)&&!empty($phone))
            $q="INSERT INTO `$tables[2]`(`name`, `email`, `phone`, `address`, `pincode`, `rand_id`) VALUES ('$name','$email','$phone','$address','$pincode','$rand_id')";
        if(mysql_query($q)){
            $id=mysql_insert_id();
            /*$row=mysql_fetch_array(mysql_query("SELECT * FROM `customer` WHERE binary `rand_id` LIKE '$rand_id'"));
            $id=$row['customer_id'];*/
            echo $id;
        }
        elseif(mysql_error()){
            $q1=mysql_query("UPDATE `$tables[2]` SET `name`='name',`email`='email',`address`='$address',`pincode`='$pincode' WHERE `phone`='$phone'");
            $q2=mysql_fetch_assoc(mysql_query("SELECT * FROM `$tables[2]` WHERE `phone`='$phone'"));
            fwrite($error,"customer_create\t".mysql_errno()."\n");
            if($q2){
                echo $q2['customer_id'];
            }
            else{
                echo "error";
            }
        }
        else
            echo "false";
    }
    //update customer table if non empty of all field
    elseif($mode=="customer_update"){   //testing passed
        $name = san_char(urldecode($_POST['name']));
        $email = san_email(urldecode($_POST['email']));
        $pincode = san_int(urldecode($_POST['pin']));
        $phone = san_int(urldecode($_POST['phone']));
        $url = "https://www.whizapi.com/api/v2/util/ui/in/indian-city-by-postal-code?pin=$pincode&project-app-key=az21s4wavyyxl698cocjzpio";
        $json = json_decode(file_get_contents($url), true);
        $address = $json['Data'][0]['Address'];
        $city = $json['Data'][0]['City'];
        $state = $json['Data'][0]['State'];
        $qa=mysql_query("INSERT INTO `$tables[0]`(`pincode`, `address`, `city`, `state`) VALUES ('$pincode','$address','$city','$state')");
        $address = urldecode($_POST['addr']);
        $customer_id = san_int(urldecode($_POST['customer_id']));
        if(!empty($customer_id)&&!empty($name)&&!empty($phone))
        $q="UPDATE `$tables[2]` SET `name`='$name',`email`='$email',`phone`='$phone',`address`='$address',`pincode`='$pincode' WHERE `customer_id` = '$customer_id'";
        if(mysql_query($q)){
            echo "true";
        }
        elseif(mysql_error()){
            fwrite($error,"customer_update\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    //create both sale_order and sale_order_line 
    //if nonempty of customer_id, order_type, product_name, product_id ,unit_price, qty
    elseif ($mode=="sale_order_create") {           //testing passed
        $customer_id = san_int(urldecode($_POST['customer_id']));
        $status = san_char(urldecode($_POST['status']));
        $order_date = date("d-m-Y h:i:sa");
        $is_driver = san_char(urldecode($_POST['is_driver']));
        $portal = san_char(urldecode($_POST['portal']));
        $driver_id = san_int(urldecode($_POST['driver_id']));
        $bill_paid =san_int(urldecode($_POST['bill_paid']));
        $order_special_notes = san_char(urldecode($_POST['order_special_notes']));
        $table_id = san_int(urldecode($_POST['table_id']));
        $order_type = san_char(urldecode($_POST['order_type']));
        $rand_id=random_id();
        
        $product_name = san_arr_char( json_decode($_POST['product_name']));
        $product_id = san_arr_int( json_decode($_POST['product_id']));
        $product_template_id = san_arr_int( json_decode($_POST['product_template_id']));
        $qty = san_arr_int( json_decode($_POST['qty']));
        $uom ="1";
        $price = san_arr_float( json_decode($_POST['unit_price']));
        $discount_percentage= san_arr_float( json_decode($_POST['discount_percentage']));
        $special_notes = san_arr_char( json_decode($_POST['special_notes']));
        $extra_ids = san_arr_int( json_decode($_POST['extra_ids']));
        if(empty($customer_id))
            $customer_id=1;
        if (empty($driver_id)) {
            mysql_query("SET foreign_key_checks = 0;");
        }
        if(!empty($customer_id)&&!empty($order_type)&&!empty($product_name)&&!empty($product_id)&&!empty($qty)&&!empty($price))
        $q="INSERT INTO `$tables[5]`(`customer_id`, `status`, `is_driver`, `portal`, `driver_id`, `bill_paid`, `special_notes`, `table_id`, `order_type`, `rand_id`,`server_id`) VALUES ('$customer_id','$status','$is_driver','$portal','$driver_id','$bill_paid','$order_special_notes','$table_id','$order_type','$rand_id','$employee_id')";
        if(mysql_query($q)){

            $order_id=mysql_insert_id();
            if (empty($driver_id)) {
                mysql_query("SET foreign_key_checks = 1;");
            }
            $jsondata['order_id']=$order_id;
            
            for($i=0;$i<sizeof($product_name);$i++){
                $q1="INSERT INTO `$tables[6]`(`order_id`, `product_id`,`product_name`, `product_template_id`, `qty`, `uom`, `price`, `discount`, `special_notes`, `extra_ids`) 
                VALUES ('$order_id','$product_id[$i]','$product_name[$i]',
                    '$product_template_id[$i]','$qty[$i]','$uom','$price[$i]',
                    '$discount_percentage[$i]',
                    '$special_notes[$i]',
                    '$extra_ids[$i]')";
                if(mysql_query($q1)){
                    $order_line_id[]=mysql_insert_id();
                }
            }
                $sale_order_id=$order_id;
                $dir="kitchen";
                include("pdf gen.php");
            $jsondata['order_line_id'] = $order_line_id;
        }
        if(!empty($jsondata)){
            echo json_encode($jsondata);
        }
        elseif(mysql_error()){
            fwrite($error,"sale_order_create\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
        // fwrite($myfile, json_encode($jsondata));
    }
    //remove sale order and sale_order line if nonempty of order_id
    elseif ($mode=="sale_order_delete") {           // testing passed
        $order_id = san_int( urldecode($_POST['order_id']));
        if(!empty($order_id))
            $q="DELETE FROM `$tables[5]` WHERE `sale_order_id` = '$order_id'";
        if(mysql_query($q))
        {
            echo "true";
        }
        elseif(mysql_error()){
            fwrite($error,"sale_order_delete\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }

    //add order items to the order if nonempty of order_id product_id product_name qty price 
    elseif($mode=="sale_order_line_insert"){            //testing passed
        $order_id = san_int( urldecode($_POST['sale_order_id']));
        $product_name = san_arr_char( json_decode($_POST['product_name']));
        $product_id = san_arr_int( json_decode($_POST['product_id']));
        $product_template_id = san_arr_int( json_decode($_POST['product_template_id']));
        $qty = san_arr_int( json_decode($_POST['qty']));
        $uom ="1";
        $price = san_arr_float( json_decode($_POST['unit_price']));
        $discount_percentage= san_arr_float( json_decode($_POST['discount_percentage']));
        $special_notes = san_arr_char( json_decode($_POST['special_notes']));
        $extra_ids = san_arr_int( json_decode($_POST['extra_ids']));
        // echo "order_id: $order_id \n product_id: $product_id \n product_name:$product_name \n qty:$qty \n price:$price";
        if(!empty($order_id)&&!empty($product_name)&&!empty($product_id)&&!empty($qty)&&!empty($price))
        for($i=0;$i<sizeof($product_name);$i++){
            $q1="INSERT INTO `$tables[6]`(`order_id`, `product_id`,`product_name`, `product_template_id`, `qty`, `uom`, `price`, `discount`, `special_notes`, `extra_ids`) 
                VALUES ('$order_id','$product_id[$i]','$product_name[$i]','$product_template_id[$i]','$qty[$i]','$uom','$price[$i]','$discount_percentage[$i]','$special_notes[$i]','$extra_ids[$i]')";
            if(mysql_query($q1)){
                $order_line_id[]=mysql_insert_id();
            }
        }
        if($order_line_id){
            echo json_encode($order_line_id);
        }
        elseif(mysql_error()){
            fwrite($error,"sale_order_line_insert\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    //update multiple sale order line if nonempty of order_line_id, product_name, $product_id, $qty, $price
    elseif($mode=="sale_order_line_update"){        //test passed
        $order_line_id =san_arr_int( json_decode($_POST['order_line_id']));
        $product_name =san_arr_char( json_decode($_POST['product_name']));
        $product_id =san_arr_int( json_decode($_POST['product_id']));
        $product_template_id =san_arr_int( json_decode($_POST['product_template_id']));
        $qty =san_arr_int( json_decode($_POST['qty']));
        $uom ="1";
        $price =san_arr_float( json_decode($_POST['unit_price']));
        $discount_percentage=san_arr_float( json_decode($_POST['discount_percentage']));
        $special_notes = san_arr_char( json_decode($_POST['special_notes']));
        $extra_ids = san_arr_int( json_decode($_POST['extra_ids']));
        if(!empty($order_line_id)&&!empty($product_name)&&!empty($product_id)&&!empty($qty)&&!empty($price))
        for($i=0;$i<sizeof($order_line_id);$i++){
            $q1="UPDATE `$tables[6]` SET `product_id`='$product_id[$i]',
                `product_name`='$product_name[$i]',`product_template_id`='$product_template_id[$i]',`qty`='$qty[$i]',
                `uom`='$uom',`price`='$price[$i]',`discount`='$discount[$i]',`special_notes`='$special_notes[$i]',
                `extra_ids`='$extra_ids[$i]' WHERE `order_line_id`='$order_line_id[$i]'";
            if(mysql_query($q1)){
                $line_id[]=mysql_insert_id();
                $myfile=fopen("1.txt", "w");
                fwrite($myfile, "$q1 \n");
            }
        }
        if(!empty($line_id)){
            echo "true";
        }
        elseif(mysql_error()){
            fwrite($error,"sale_order_line_update\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    elseif($mode=="sale_order_line_merge") {
        $order_line_id =san_arr_int( json_decode($_POST['order_line_id']));
        $order_id = san_int( urldecode($_POST['order_id']));
        mysql_query("SET foreign_key_checks = 0;");
        foreach ($order_line_id as $value) {
            $q1=mysql_query("SELECT  `order_id` FROM `$tables[6]` WHERE `order_line_id`='$value';");
            if($q1){
                $r1=mysql_fetch_assoc($q1);
                $order_id1=$r1['order_id'];
                mysql_query("UPDATE `$tables[5]` SET `status`='m($order_id)' WHERE `sale_order_id`='$order_id1'");
            }
            $q2[]="UPDATE `$tables[6]` SET `order_id`='$order_id' WHERE `order_line_id`='$value';";
        }
        foreach ($q2 as $q3) {
            if(mysql_query($q3)){
                $status=true;
            }
            elseif(mysql_error()){
                fwrite($error,"sale_order_line_merge\t".mysql_error()."\n");
                $status="error";
            }
            else
                $status=false;
        }
                $sale_order_id=$order_id;
                $dir="counter";
                include("pdf gen.php");
        echo "$status";  
        mysql_query("SET foreign_key_checks = 1;");
    }
    //delete multiple sale_order_line if non empty of order_line_id
    elseif($mode=="sale_order_line_delete"){        //testing passed
        $order_line_id = san_arr_int(json_decode($_POST['order_line_id']));
        if(!empty($order_line_id))
        for($i=0;$i<sizeof($order_line_id);$i++){
            $q="DELETE FROM `$tables[6]` WHERE `order_line_id`='$order_line_id[$i]'";
            if(mysql_query($q)){
                $result="true";
            }
        }
        if($result)
            echo "true";
        elseif(mysql_error()){
            fwrite($error,"sale_order_line_delete\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    //get all driver details
    elseif ($mode=="get_driver") {          //testing passed
        $q=mysql_query("SELECT * FROM `$tables[3]`");
        // $myfile = fopen("get_driver.txt", "w");
        while ($row=mysql_fetch_assoc($q)) {
            $driver[]=$row;
            // fwrite($myfile, "$row \n");
        }
        if(!empty($driver))
            echo json_encode($driver);
        elseif(mysql_error()){
            fwrite($error,"get_driver\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
        // fclose($myfile); 
    }
    //allocate order to driver if nonempty of driver_id, sale_order_id
    elseif ($mode=="allot_driver") {        //testing passed
        $driver_id = san_int( urldecode($_POST['driver_id']));
        $sale_order_id=san_arr_int( json_decode($_POST['sale_order_id']));
        $result="false";
        if(!empty($driver_id)&&!empty($sale_order_id))
        foreach ($sale_order_id as $id) {
            if(mysql_query("UPDATE `$tables[5]` SET `driver_id`='$driver_id',`is_driver`='1',`status`='delivered' WHERE `sale_order_id`='$id'  ")){
                $result="true";
            }
        }
        if($result)
            echo "true";
        elseif(mysql_error()){
            fwrite($error,"allot_driver\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
        // echo "sale_order_id :$sale_order_id driver_id:$driver_id $result";
    }
    elseif ($mode=="product_insert") {
        
        $item_name =san_arr_char( json_decode($_POST['item_name']));
        $item_desc =san_arr_char( json_decode($_POST['item_desc']));
        $priority =san_arr_int( json_decode($_POST['priority']));
        $max_order =san_arr_int( json_decode($_POST['max_order']));
        $price =san_arr_float( json_decode($_POST['price']));
        $category_id =san_arr_int( json_decode($_POST['category_id']));
        $tax_id =san_arr_char( json_decode($_POST['tax_id']));
        $meal_type =san_arr_char( json_decode($_POST['meal_type']));
        $food_type =san_arr_char( json_decode($_POST['food_type']));
        $is_option_available = san_arr_int( json_decode($_POST['is_option_available']));
        $availability =san_arr_int( json_decode($_POST['availability']));
        if(!empty($item_name)&&!empty($price)&&(!empty($availability)||$availability==='0'))
        for ($i=0; $i <sizeof($item_name) ; $i++) { 
            $p_i_q1="INSERT INTO `$tables[4]`(`item_name`, `item_desc`, `priority`, `max_order`, `price`, `category_id`, `tax_id`, `meal_type`, `food_type`, `is_option_available`, `availability`) 
            VALUES ('$item_name[$i]', 'item_desc[$i]', '$priority[$i]', '$max_order[$i]', '$price[$i]','$category_id[$i]', '$tax_id[$i]','$meal_type[$i]', '$food_type[$i]','$is_option_available[$i]','$availability[$i]')";
            $p_i_q1_e=mysql_query($p_i_q1);
            if($p_i_q1_e)
                $item_id[]=mysql_insert_id();
        }
        if($item_id)
            echo json_encode($item_id);
        elseif(mysql_error()){
            fwrite($error,"product_insert\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    // Chage status in sale order to confirm for single  if sale_order_id
    elseif($mode=="sale_order_confirm"){        //testing passed
        $sale_order_id = san_int(urldecode($_POST['order_id']));
        if(!empty($sale_order_id))
            $s_o_c_q1="UPDATE `$tables[5]` SET `status`='confirm' WHERE `sale_order_id`='$sale_order_id'";
        if(mysql_query($s_o_c_q1)){
            echo "true";
            $dir="counter";
            include("pdf gen.php");
        }
        elseif(mysql_error()){
            fwrite($error,"sale_order_confirm\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    // Chage status in sale order to cancel for single  if sale_order_id
    elseif($mode=="sale_order_cancel"){        //testing passed
        $sale_order_id = san_int(urldecode($_POST['order_id']));
        if(!empty($sale_order_id))
            $s_o_c_q1="UPDATE `$tables[5]` SET `status`='cancel' WHERE `sale_order_id`='$sale_order_id'";
        if(mysql_query($s_o_c_q1))
            echo "true";
        elseif(mysql_error()){
            fwrite($error,"sale_order_confirm\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    //fetch all detail from category table
    elseif($mode=="category_get"){         //testing passed
        $q=mysql_query("SELECT * FROM `$tables[1]` ");
        while ($row=mysql_fetch_assoc($q)) {
            $category[]=$row;
        }
        if(!empty($category))
            echo json_encode($category);
        elseif(mysql_error()){
            fwrite($error,"category_get\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";

    }
    // fetch product details send the all product details
    elseif ($mode=="product_get") {             // testing passed
        $p_g_q1="SELECT * FROM `$tables[4]`";
        $p_g_e1=mysql_query($p_g_q1);
        while ($p_g_r1 = mysql_fetch_assoc($p_g_e1)) {
            $products[]=$p_g_r1;
        }
        if(!empty($products))
            echo json_encode($products);
        elseif(mysql_error()){
            fwrite($error,"product_get\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    // fetch availability details send the product_id,category_id,product_name,availability fields only
    elseif ($mode=="product_avail_get") {       //testing passed
        $p_a_g_q1="SELECT `item_id`, `item_name`, `category_id`, `availability` FROM `$tables[4]`";
        $p_a_g_e1=mysql_query($p_a_g_q1);
        while ($p_a_g_r1 = mysql_fetch_assoc($p_a_g_e1)) {
            $product_avail[]= $p_a_g_r1;

        }
        if(!empty($product_avail))
            echo json_encode($product_avail);
        elseif(mysql_error()){
            fwrite($error,"product_avail_get\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }
    // update availability of the product by item_id
    elseif ($mode=="product_avail_update") {        //testing passed
        $item_id = san_int(urldecode($_POST['item_id']));
        $availability = san_int(urldecode($_POST['availability']));
        if(!empty($item_id)&&(!empty($availability))||$availability==="0")
        $p_a_u_q1="UPDATE `$tables[4]` SET `availability`='$availability' WHERE `item_id` = '$item_id' ";
        if(mysql_query($p_a_u_q1))
            echo "true";
        elseif(mysql_error()){
            fwrite($error,"product_avail_update".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
    }//get all driver details
    elseif ($mode=="get_table") {          //testing passed
        $q=mysql_query("SELECT * FROM `$tables[8]`");
        // $myfile = fopen("get_driver.txt", "w");
        while ($row=mysql_fetch_assoc($q)) {
            $driver[]=$row;
            // fwrite($myfile, "$row \n");
        }
        if(!empty($driver))
            echo json_encode($driver);
        elseif(mysql_error()){
            fwrite($error,"get_driver\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
        // fclose($myfile); 
    }
    //get all table category details
    elseif ($mode=="get_table_cat") {          //testing passed
        $q=mysql_query("SELECT * FROM `$tables[7]`");
        // $myfile = fopen("get_driver.txt", "w");
        while ($row=mysql_fetch_assoc($q)) {
            $driver[]=$row;
            // fwrite($myfile, "$row \n");
        }
        if(!empty($driver))
            echo json_encode($driver);
        elseif(mysql_error()){
            fwrite($error,"get_driver\t".mysql_error()."\n");
            echo "error";
        }
        else
            echo "false";
        // fclose($myfile); 
    }
    elseif ($mode=="get_portal"){
        $q1=mysql_query("SELECT * FROM `portal`");
        while ($r1=mysql_fetch_assoc($q1)) {
            $js_data[]=$r1;
        }
        if(empty($js_data)){
            echo "false";
        }
        else{
            echo json_encode($js_data);
        }

    }
    else{
        echo "mode is not enabled";
    }

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
$time = time();
fopen("test.php", "w");
touch("test.php", $time);
clearstatcache();
?>