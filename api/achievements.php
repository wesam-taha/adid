<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");

$user_id = $_GET['user_id'];

if(isset($_GET['activity_id'])){
 $WhereActivityCondition = "and activities.activity_id = '".$_GET['activity_id']."'";
}else{
 $WhereActivityCondition = '';
}

$all = $db->query("SELECT
registered_users.user_id,
registered_users.activity_id,
activities.activity_name_en,
activities.activity_name_ar,
activities.activity_id,
activities.activity_hours,
activities.activity_image,
activities.activity_end_date,
activities.leader_username,
registered_users.evaluation_rate,
registered_users.admin_approval
FROM
activities ,
registered_users
where registered_users.activity_id = activities.activity_id and registered_users.user_id = '$user_id'

$WhereActivityCondition

 ");


while($res = $db->fetch_assoc($all)){
$i = 0;

$userInfo = $res;


$today = date('Y-m-d');

$userInfoQ  = $db->query("SELECT
* FROM
users
WHERE
user_id='$user_id'
");




$query = $db->query(" SELECT 
round((sum(rating_values.rating_value) ) / ((COUNT(rating_values.rating_value) * 5) ) * 100)
as evalutaion
from rating_values where `rating_values`.`user_id`='" . $user_id . "' and `rating_values`.`event_id`='" . $res['activity_id'] . "'
");
$userSecondInfo = $db->fetch_assoc($query);



$userInfo['evalutaion'] = $userSecondInfo['evalutaion'];

$evaluation = $userSecondInfo['evalutaion'];
$sql = $db->query("UPDATE registered_users SET `evaluation_rate`=".$evaluation." where user_id = '$user_id' and activity_id='".$res['activity_id']."' ");


$rateQSQL= $db->query("SELECT * FROM members_stars_criteria where ".$evaluation." between rate_from and rate_to");
$rateQR = $db->fetch_assoc($rateQSQL);
$userInfo['stars'] = $rateQR['number_of_stars'];




$i++;

$activity_id = $userInfo['activity_id'];

$rateSQL = $db->query("SELECT rating_values.rating_value as stars, rating_criteria_list.name as category_ar,  rating_criteria_list.name_en as category_en, users.full_name_ar AS leader_ar, users.full_name_en AS leader_en FROM rating_values,users, rating_criteria_list
	where rating_values.user_id='$user_id' and rating_values.rated_by=users.user_id and rating_values.event_id='$activity_id' and rating_values.rating_type = rating_criteria_list.id ");


while($rateRES = $db->fetch_assoc($rateSQL)){

$array['evaluations'][] = $rateRES;

}


$array['achievements'][] = $userInfo;

}

    echo '{ "achievements": ' . json_encode($array).'}';


// $array[] =  '{"userInfo":'.json_encode($userInfo).',"approved":' . json_encode(array_sum($myresult)) . ',"onhold":' . json_encode(array_sum($hold)) . ',"slogan":' . json_encode($rowsSlogan) . ' }';

?>


