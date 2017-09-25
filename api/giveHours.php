<?php

//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);

$id = $_REQUEST['id'];
$admin_approval = $_REQUEST['admin_approval'];
$admin_comment = $_REQUEST['admin_comment'];

$dbQuery = $db->query("UPDATE `registered_users` SET `admin_approval`='".$admin_approval."', `admin_comment`='".$admin_comment."' where `id`= '".$id."' ");

?>
