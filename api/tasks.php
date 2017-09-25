<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");

$user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
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
$query = $db->query("SELECT registered_users.admin_approval, registered_users.user_id, activities.activity_time_ar ,activities.activity_start_date, activities.activity_end_date, activities.activity_image, activities.activity_hours , activities.activity_id , activities.activity_name_en ,activities.activity_name_ar, activities.activity_time_en,activities.leader_username, activities.activity_location_map, activities.activity_location_ar, activities.activity_location_en  from  registered_users,activities where  registered_users.user_id='" . $user_id . "' and  ('$today' BETWEEN activities.activity_start_date AND activities.activity_end_date OR '$today' <  activities.activity_start_date ) and activities.activity_id =  registered_users.activity_id ");
$result = $db->returned_rows;
$i=0;
while($rows = $db->fetch_assoc($query)){


$q = $db->query("select user_id from users where full_name_ar='".$rows['leader_username']."' ");
$r = $db->fetch_assoc($q);



$myresult[] = $rows;
$myresult[$i]['timetostart'] = time() - strtotime($rows['activity_start_date']);
$myresult[$i]['timetostartatring'] = secondsToTime(time() - strtotime($rows['activity_start_date']),$lang);
$myresult[$i]['leaderID'] = $r['user_id'];

if($r['user_id'] == null ){ $myresult[$i]['leaderID'] =  $myresult[$i]['leader_username']; }

$i++;
}


    echo '{ "result":' . json_encode($myresult) . ' }';

?>
