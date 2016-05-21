<?php
    mysql_connect("127.0.0.1","root","root");
    mysql_select_db("knoneaet_ranjith1");
    $mode=san_char(urldecode($_POST['mode']));
    //create the if non empty of name and phone
    if($mode == "customer_create"){                       /*testing passed*/
        $name = san_char(urldecode($_POST['name']));
        $email = san_email(urldecode($_POST['email']));
        $pincode = san_int(urldecode($_POST['pin']));
        $phone = san_int(urldecode($_POST['phone']));
        $address = urldecode($_POST['addr']);
        $rand_id = random_id();
        if(!empty($name)&&!empty($phone))
            $q="INSERT INTO `customer`(`name`, `email`, `phone`, `address`, `pincode`, `rand_id`) VALUES ('$name','$email','$phone','$address','$pincode','$rand_id')";
        if(mysql_query($q)){
            $id=mysql_insert_id();
            /*$row=mysql_fetch_array(mysql_query("SELECT * FROM `customer` WHERE binary `rand_id` LIKE '$rand_id'"));
            $id=$row['customer_id'];*/
            echo $id;
        }
        else{
            echo "false";
        }
    }
    //update customer table if non empty of all field
    elseif($mode == "customer_update"){   //testing passed
        $name = san_char(urldecode($_POST['name']));
        $email = san_email(urldecode($_POST['email']));
        $pincode = san_int(urldecode($_POST['pin']));
        $phone = san_int(urldecode($_POST['phone']));
        $address = urldecode($_POST['addr']);
        $customer_id = san_int(urldecode($_POST['customer_id']));
        if(!empty($customer_id)&&!empty($name)&&!empty($phone))
        $q="UPDATE `customer` SET `name`='$name',`email`='$email',`phone`='$phone',`address`='$address',`pincode`='$pincode' WHERE `customer_id` = '$customer_id'";
        if(mysql_query($q)){
            echo "true";
        }
        else{
            echo "false";
        }
    }
    //create both sale_order and sale_order_line 
    //if none empty of customer_id, order_type, product_name, product_id ,unit_price, qty

    elseif ($mode=="sale_order_create") {           //testing passed
        $customer_id = san_int(urldecode($_POST['customer_id']));
        $status = san_char(urldecode($_POST['status']));
        $order_date = date("d-m-Y h:i:sa");
        $is_driver = san_int(urldecode($_POST['is_driver']));
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
        $q="INSERT INTO `sale_order`(`customer_id`, `status`, `order_date`, `is_driver`, `portal`, `driver_id`, `bill_paid`, `special_notes`, `table_id`, `order_type`, `rand_id`) VALUES ('$customer_id','$status','$order_date','$is_driver','$portal','$driver_id','$bill_paid','$order_special_notes','$table_id','$order_type','$rand_id')";
        if(mysql_query($q)){

            $order_id=mysql_insert_id();
            if (empty($driver_id)) {
                mysql_query("SET foreign_key_checks = 1;");
            }
            $jsondata['order_id']=$order_id;
            
            for($i=0;$i<sizeof($product_name);$i++){
                $q1="INSERT INTO `sale_order_line`(`order_id`, `product_id`,`product_name`, `product_template_id`, `qty`, `uom`, `price`, `discount`, `special_notes`, `extra_ids`) 
                VALUES ('$order_id','$product_id[$i]','$product_name[$i]','$product_template_id[$i]','$qty[$i]','$uom','$price[$i]','$discount_percentage[$i]','$special_notes[$i]','$extra_ids[$i]')";
                if(mysql_query($q1)){
                    $order_line_id[]=mysql_insert_id();
                }
            }
           
            $jsondata['order_line_id'] = $order_line_id;
        }

        echo json_encode($jsondata);
    }
    //remove sale order and sale_order line if none empty of order_id
    elseif ($mode=="sale_order_delete") {           // testing passed
        $order_id = san_int( urldecode($_POST['order_id']));
        if(!empty($order_id))
            $q="DELETE FROM `sale_order` WHERE `sale_order_id` = '$order_id'";
        if(mysql_query($q))
        {
            echo "true";
        }
        else{
            echo "false";
        }
    }

    //add order items to the order if none empty of order_id product_id product_name qty price 
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
        if(!empty($order_id)&&!empty($product_name)&&!empty($product_id)&&!empty($qty)&&!empty($price))
        for($i=0;$i<sizeof($product_name);$i++){
            $q1="INSERT INTO `sale_order_line`(`order_id`, `product_id`,`product_name`, `product_template_id`, `qty`, `uom`, `price`, `discount`, `special_notes`, `extra_ids`) 
                VALUES ('$order_id','$product_id[$i]','$product_name[$i]','$product_template_id[$i]','$qty[$i]','$uom','$price[$i]','$discount_percentage[$i]','$special_notes[$i]','$extra_ids[$i]')";
            if(mysql_query($q1)){
                $order_line_id[]=mysql_insert_id();
            }
        }
        if($order_line_id){
            echo json_encode($order_line_id);
        }
        else{
            echo "false";
        }
    }
    //update multiple sale order line if none empty of order_line_id, product_name, $product_id, $qty, $price
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
        for($i=0;$i<sizeof($product_name);$i++){
            $q1="UPDATE `sale_order_line` SET `product_id`='$product_id[$i]',
                `product_name`='$product_name[$i]',`product_template_id`='$product_template_id[$i]',`qty`='$qty[$i]',
                `uom`='$uom',`price`='$price[$i]',`discount`='$discount[$i]',`special_notes`='$special_notes[$i]',
                `extra_ids`='$extra_ids[i]' WHERE `order_line_id`='$order_line_id[$i]'";
            if(mysql_query($q1)){
                $order_line_id[]=mysql_insert_id();
            }
        }
        if($order_line_id){
            echo "true";
        }
        else{
            echo "false";
        }
    }
    //get all driver details
    elseif ($mode=="get_driver") {          //testing passed
        $q=mysql_query("SELECT * FROM `driver`");
        // $myfile = fopen("get_driver.txt", "w");
        while ($row=mysql_fetch_assoc($q)) {
            $driver[]=$row;
            // fwrite($myfile, "$row \n");
        }
        echo json_encode($driver);
        // fclose($myfile); 
    }
    //allocate order to driver if nonempty of driver_id, sale_order_id
    elseif ($mode=="allot_driver") {        //testing passed
        $driver_id = san_int( urldecode($_POST['driver_id']));
        $sale_order_id=san_arr_int( json_decode($_POST['sale_order_id']));
        $result="false";
        if(!empty($driver_id)&&!empty($sale_order_id))
        foreach ($sale_order_id as $id) {
            if(mysql_query("UPDATE `sale_order` SET `driver_id`='$driver_id' WHERE `sale_order_id`='$id'  ")){
                $result="true";
            }
        }
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
        
        for ($i=0; $i <sizeof($item_name) ; $i++) { 
            $p_i_q1="INSERT INTO `product`(`item_name`, `item_desc`, `priority`, `max_order`, `price`, `category_id`, `tax_id`, `meal_type`, `food_type`, `is_option_available`, `availability`) 
            VALUES ('$item_name[$i]', '$item_desc[$i]', '$priority[$i]', '$max_order[$i]', '$price[$i]','$category_id[$i]', '$tax_id[$i]','$meal_type[$i]', '$food_type[$i]','$is_option_available[$i]','$availability[$i]')";
            $p_i_q1_e=mysql_query($p_i_q1);
            if($p_i_q1_e)
                $item_id[]=mysql_insert_id();
        }
        if($item_id)
            echo json_encode($item_id);
        else
            echo "false";
    }
    // Chage status in sale order to confirm for single  if sale_order_id
    elseif($mode=="sale_order_confirm"){        //passed
        $sale_order_id = san_int(urldecode($_POST['sale_order_id']));
        if(!empty($sale_order_id))
            $s_o_c_q1="UPDATE `sale_order` SET `status`='confirm' WHERE `sale_order_id`='$sale_order_id'";
        if(mysql_query($s_o_c_q1))
            echo "true";
        else
            echo "false";
    }
    // fetch product details send the all product details
    elseif ($mode=="product_get") {             //passed
        $p_g_q1="SELECT * FROM `product`";
        $p_g_e1=mysql_query($p_g_q1);
        while ($p_g_r1 = mysql_fetch_assoc($p_g_e1)) {
            $products[]=$p_g_r1;
        }
        if(!empty($products))
            echo json_encode($products);
        else
            echo "false";
    }
    // fetch availability details send the product_id,product_name,availability fields only
    elseif ($mode=="product_avail_get") {       //passed
        $p_a_g_q1="SELECT `item_id`, `item_name`, `availability` FROM `product`";
        $p_a_g_e1=mysql_query($p_a_g_q1);
        while ($p_a_g_r1 = mysql_fetch_assoc($p_a_g_e1)) {
            $product_avail[]=$p_a_g_r1;
        }
        if(!empty($product_avail))
            echo json_encode($product_avail);
        else
            echo "false";
    }
    // update availability of the product by item_id
    elseif ($mode=="product_avail_update") {        //passed
        $item_id = san_int(urldecode($_POST['item_id']));
        $availability = san_int(urldecode($_POST['availability']));
        if(!empty($item_id)&&(!empty($availability))||$availability==="0")
        $p_a_u_q1="UPDATE `product` SET `availability`='$availability' WHERE `item_id` = '$item_id' ";
        if(mysql_query($p_a_u_q1))
            echo "true";
        else
            echo "false";
    }
    else{
        echo "$mode is not enabled";
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
            if(!preg_match('/^[a-zA-Z0-9\s\_]*$/', $value)){
                echo "false arr char ";
                exit();
            }
        }
        return $string;
    }
?>