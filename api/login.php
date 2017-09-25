<?php

//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');

include_once("connect.php");


$email = @$_REQUEST['email'];
$password = @$_REQUEST['password'];
$type = $_REQUEST['type'];

$arr_admin = [];
if ($type == 'users') {
    $table = 'users';
    $fld = 'user_id';
} else {
    $table = 'institutions';
    $fld = 'institution_id';
}

if (!isset($_REQUEST['user_id'])) {


    $result = $db->query("SELECT * from $table where email='$email' and password='$password'   ");
    $totalNum = $db->returned_rows;
    if ($totalNum > 0) {

        $rows = $db->fetch_assoc($result);
        echo '{ "logged":' . json_encode($rows) . ' }';
    } else {

        echo "0";
        ;
    }
} else {

    $result = $db->query("SELECT * from $table where $fld ='" . $_REQUEST['user_id'] . "' ");
    $totalNum = $db->returned_rows;

    $rows = $db->fetch_assoc($result);
    echo '{ "logged":' . json_encode($rows) . ' }';
}
?>
