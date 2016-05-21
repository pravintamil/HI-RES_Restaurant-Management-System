<?php
	session_start();
	include("../db.php");
	$db_key=$_SESSION['db_key'];
	$query="SELECT ce.id,el.title,ce.start,ce.end as 'end',el.color as backgroundColor,el.color as borderColor,'false' as allDay FROM `calendar_events` as ce JOIN `event_list` as el on ce.event_id=el.id;";
    $r1=mysql_query($query);
    if($r1){
        $data['success']=true;
    }
    while ($row1=mysql_fetch_assoc($r1)) {
    	$row1['allDay']=false;
    // 	$start = $row1['start'];
    // $start_obj = new \DateTime($start);
    // $row1['start'] = $start_obj->format('Y-m-d H:i:s');
        $result[]=$row1;
    }
    echo json_encode($result);
    ?>