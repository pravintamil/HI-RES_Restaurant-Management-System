<?php
    include("db.php");
    $role_id=6;
    if (isset($_SESSION["loggedin"])) {

        $email=$_SESSION['email'];
        $q=mysql_query("SELECT t2.role_id FROM employee_role as t2 JOIN employee as t1 ON t1.employee_id = t2.employee_id WHERE email ='$email'");
        $r=mysql_fetch_array($q);
        if($r['role_id'])
            $role_id=$r['role_id'];
    }
    $sql = "SELECT t2.perm_desc FROM role_perm as t1 JOIN permissions as t2 ON t1.perm_id = t2.perm_id WHERE t1.role_id = '$role_id'";
    $q=mysql_query($sql);
    while ($r=mysql_fetch_assoc($q)) {
        $permissions[$r["perm_desc"]]=true;
    }
// echo var_dump($per);
?>