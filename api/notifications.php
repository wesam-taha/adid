<?php

//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);

$a = 0;
$notifications = [];

if(isset($_REQUEST['user_id'])){
$whereuser = "where FIND_IN_SET(".$_REQUEST['user_id'].", user) > 0 or (user is null and institution is null ) ";
} else{  $whereuser=""; }


if(isset($_REQUEST['institution_id'])){
$whereinstitution = "where FIND_IN_SET(".$_REQUEST['institution_id'].", institution) > 0 or (user is null and institution is null ) ";
} else{  $whereinstitution=""; }



$notificationsQ = $db->query("SELECT * From  admin_notifications  $whereuser $whereinstitution order by id ASC  ");
while ($notificationsR = $db->fetch_assoc($notificationsQ)) {
    $notifications[] = $notificationsR;
}



echo '{"notifications": ' . json_encode($notifications) . ' }';
?>
