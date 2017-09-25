<?php

//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);

$mobile = $_REQUEST['mobile'];
$area = $_REQUEST['area'];

$arr_admin = [];
$result = $db->query(" UPDATE users SET `area`='$area' where mobile='$mobile'   ");
?>
