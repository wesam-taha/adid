<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");

$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
$activity_id = isset($_REQUEST['activity_id']) ? $_REQUEST['activity_id'] : null;
$myresult = [];
$lang = $_REQUEST['lang'];
function secondsToTime($seconds,$lang) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    if($lang=='ar'){
    return $dtF->diff($dtT)->format('%a يوم - %h ساعة - %i دقيقة');
    }else{
    return $dtF->diff($dtT)->format('%a days -  %h hours - %i minutes');

    }
}

$today = date('Y-m-d');
$query = $db->query("SELECT registered_users.admin_approval, registered_users.security_approval, registered_users.user_id, activities.activity_start_date, activities.activity_hours, activities.activity_location_map, activities.activity_id, activities.activity_location_ar, activities.activity_location_en  from  registered_users,activities where registered_users.activity_id='" . $activity_id . "' and registered_users.user_id='" . $user_id . "' and  ('$today' BETWEEN activities.activity_start_date AND activities.activity_end_date OR '$today' <  activities.activity_start_date ) and activities.activity_id =  registered_users.activity_id ");
$result = $db->returned_rows;
$rows = $db->fetch_assoc($query);
if ($result == 0) {
    $result = $db->query("INSERT INTO `registered_users` (`activity_id`,`user_id`,`admin_approval`,`admin_comment`) VALUES ('$activity_id','$user_id','0','')  ");
    echo 1;
} else {


 
        $myresult = $rows;
        $myresult['timetostart'] = time() - strtotime($rows['activity_start_date']);
        $myresult['timetostartatring'] = secondsToTime(time() - strtotime($rows['activity_start_date']),$lang);

 


    echo '{ "result":' . json_encode($myresult) . ' }';
}
?>
