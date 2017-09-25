<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");
$all = $db->query("SELECT * FROM `users` order by `overall_evaluation` DESC, `title_number` DESC LIMIT 5");
while($res = $db->fetch_assoc($all)){
$i = 0;
$user_id = $res['user_id'];
$myresult = [];
$hold = [];



$today = date('Y-m-d');

$userInfoQ  = $db->query("SELECT
users.full_name_ar,
users.full_name_en,
users.personal_photo,
users.title_number,
users.gender,
users.overall_evaluation,
users.total_voluntary_hours
FROM
users
WHERE
user_id='$user_id'
");

$userInfo = $db->fetch_assoc($userInfoQ);

if($userInfo['personal_photo'] == ''){  $userInfo['personal_photo'] = 'profile-pictures-'.$userInfo['gender'].'.png'; }


$query = $db->query(" SELECT 
round((sum(rating_values.rating_value) ) / ((COUNT(rating_values.rating_value) * 5) ) * 100)
as evalutaion
from rating_values where `rating_values`.`user_id`='" . $user_id . "'
");
$userSecondInfo = $db->fetch_assoc($query);

$evaluation = $userSecondInfo['evalutaion'];


$rateQSQL= $db->query("SELECT * FROM members_stars_criteria where '".$evaluation."' between rate_from and rate_to");
$rateQR = $db->fetch_assoc($rateQSQL);
$userInfo['stars'] = $rateQR['number_of_stars'];



if($userInfo['stars'] == null){ $userInfo['stars'] = 1;}

$sql = $db->query("UPDATE users SET `overall_evaluation`='$evaluation' where user_id = '$user_id' ");



$query = $db->query("SELECT  activities.activity_hours   from  registered_users,activities where registered_users.user_id='" . $user_id . "' and  activities.activity_id =  registered_users.activity_id and registered_users.admin_approval='1' ");
$result = $db->returned_rows;
$i=0;
while($rows = $db->fetch_assoc($query)){
$userInfo['active'] = $rows['activity_hours'];
$i++;
}





$queryHold = $db->query("SELECT  activities.activity_hours   from  registered_users,activities where registered_users.user_id='" . $user_id . "' and  activities.activity_id =  registered_users.activity_id and registered_users.admin_approval='0' ");
$resultHold = $db->returned_rows;
$i=0;
while($rowsHold = $db->fetch_assoc($queryHold)){
$userInfo['onhold'] =  $rowsHold['activity_hours'];
$i++;
}

if(!isset($userInfo['onhold'])){ $userInfo['onhold'] = 0; }

$sum = array_sum($myresult);

$slogan = $db->query("SELECT title,title_en,level FROM members_titles_criteria where $sum between hours_from and hours_to");
$rowsSlogan = $db->fetch_assoc($slogan);
$evaluation_level = $rowsSlogan['level'];

$userInfo['slogan_title'] = $rowsSlogan['title'];
$userInfo['slogan_title_en'] = $rowsSlogan['title_en'];


$sql = $db->query("UPDATE users SET `title_number`='$evaluation_level' where user_id = '$user_id' ");

$i++;
$array[] = $userInfo;
// $array[] =  '{"userInfo":'.json_encode($userInfo).',"approved":' . json_encode(array_sum($myresult)) . ',"onhold":' . json_encode(array_sum($hold)) . ',"slogan":' . json_encode($rowsSlogan) . ' }';

}

    echo '{ "honor": ' . json_encode($array).'}';


// $array[] =  '{"userInfo":'.json_encode($userInfo).',"approved":' . json_encode(array_sum($myresult)) . ',"onhold":' . json_encode(array_sum($hold)) . ',"slogan":' . json_encode($rowsSlogan) . ' }';

?>


