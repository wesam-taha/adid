<?php

//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);

$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'ar';


$myresult = [];
$hold = [];

$today = date('Y-m-d');
$query = $db->query("SELECT  activities.activity_hours   from  registered_users,activities where registered_users.user_id='" . $user_id . "' and  activities.activity_id =  registered_users.activity_id and registered_users.admin_approval='1' ");
$result = $db->returned_rows;
$i=0;
while($rows = $db->fetch_assoc($query)){
$myresult[] = $rows['activity_hours'];
$i++;
}


$queryHold = $db->query("SELECT  activities.activity_hours   from  registered_users,activities where registered_users.user_id='" . $user_id . "' and  activities.activity_id =  registered_users.activity_id and registered_users.admin_approval='0' ");
$resultHold = $db->returned_rows;
$i=0;
while($rowsHold = $db->fetch_assoc($queryHold)){
$hold[] = $rowsHold['activity_hours'];
$i++;
}


    echo '{ "approved":' . json_encode(array_sum($myresult)) . ',"onhold":' . json_encode(array_sum($hold)) . ' }';


?>
