<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);
$activities = [];
$locations = [];
$rating = [];
$ratingV = [];

if(isset($_REQUEST['id'])){
@$_REQUEST['id'] !='undefined' ? $where = " and activity_id = '" . @$_REQUEST['id'] . "'" : $where = "";
}else{
$where = "";
    
}

$genderWhere = "";
$activityTypeWhere = "";

if($_REQUEST['type'] == 'users'){
    $fld = 'user_id'; 
}else{
    $fld = 'institution_id'; 
}

if(isset($_REQUEST[$fld]) &&  @$_REQUEST[$fld] !='0'){
    $user_id = $_REQUEST[$fld];
$usersQ = $db->query("SELECT * From users where $fld ='$user_id'   ");
$res = $db->fetch_assoc($usersQ);
if(@$res['activity_gender_target'] !=''){ 
$genderWhere =  "and ( activity_gender_target='".$res['gender']."' or activity_gender_target='3' or activity_gender_target='4' )";
}else{
  $genderWhere ="";  
}

if(@$res['activity_type'] !=''){ 
$activityTypeWhere =  "and ( activity_type='".$res['volunteering_type']."' )";
}else { 
$activityTypeWhere =  "";
}
}



$today = date('Y-m-d');



$activitiesQ = $db->query("SELECT * From activities where activity_active ='1' $genderWhere $activityTypeWhere  $where and ( '$today' <  activity_start_date ) order by activity_id ASC  ");
$i = 0;

while ($activitiesR = $db->fetch_assoc($activitiesQ)) {

    $activities[] = $activitiesR;
    $aid = $activitiesR['activity_id'];
    $locations[$i]['activity_id'] = $activitiesR['activity_id'];
    $locations[$i]['activity_name_ar'] = $activitiesR['activity_name_ar'];
    $locations[$i]['activity_name_en'] = $activitiesR['activity_name_en'];
    $locations[$i]['activity_start_date'] = $activitiesR['activity_start_date'];
    $locations[$i]['activity_end_date'] = $activitiesR['activity_end_date'];
    $locations[$i]['lat'] = explode(',', $activitiesR['activity_location_map'])[0];
    $locations[$i]['long'] = explode(',', $activitiesR['activity_location_map'])[1];
    $locations[$i]['index'] = $i + 1;

    $q = $db->query("SELECT count(*) as total from registered_users where activity_id='$aid'");
    $to = $db->fetch_assoc($q);
    $activities[$i]['total'] = $activities[$i]['activity_persons'] - $to['total'];

    $i++;
}








//echo base64_encode(base64_encode('{ "total":'.ceil($totalNum/$pagesize).',"news": '.json_encode($arr).' }'));
if (@$_REQUEST['view'] == 'map') {

    echo '{ "locations": ' . json_encode($locations) . ' }';
} else {



if(isset($_REQUEST['id'])){

$checkQ = $db->query("SELECT * FROM registered_users where user_id='".$_REQUEST['user_id']."' and activity_id='".$_REQUEST['id']."' ");
$checkRes = $db->fetch_assoc($checkQ);


$status = $checkRes['security_approval'];
if($status == null ){ $status = 0; }
$check = $db->returned_rows;


$subscribeJSON =  ' , "subscribed": ' . $check . ', "status": ' . $status . '';





$teamQ = $db->query("SELECT registered_users.id ,registered_users.user_id, users.full_name_ar, users.full_name_en, registered_users.admin_approval, registered_users.admin_comment  From  registered_users , users where registered_users.user_id = users.user_id and registered_users.activity_id='".$_REQUEST['id']."' ");

$i=0;
while ($teamR = $db->fetch_assoc($teamQ)) {
$team[] = $teamR;
}


$teamQ = $db->query("SELECT *  From  rating_values where  event_id ='".$_REQUEST['id']."' ");
while($tt = $db->fetch_assoc($teamQ)){
 $ratingV[] = $tt;
}





@$teamJSON =  ' , "team": ' . json_encode($team) . '';

}else{
    @$teamJSON = "";
    @$subscribeJSON = "";
}


$ratingQ = $db->query("SELECT * from  `rating_criteria_list`  ");
while ($ratingR = $db->fetch_assoc($ratingQ)) {
    $rating[] = $ratingR;
}




    echo '{ "rating":'.json_encode($rating).',"ratingV":'.json_encode($ratingV).',"activities": ' . json_encode($activities) .''.$teamJSON.''.$subscribeJSON. '}';
}




?>


