<?php

//header("content-type: application/json; charset=utf-8");
header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');


include_once("connect.php");
error_reporting(E_ALL);

$a = 0;
$groups = [];
$global = [];
$translations = [];

$globalQ = $db->query("SELECT * From  global_settings    ");
$global = $db->fetch_assoc($globalQ);




$translationsQ = $db->query("SELECT * From  static_texts  order by id ASC  ");
while ($translationsR = $db->fetch_assoc($translationsQ)) {
    $translations[] = $translationsR;
}


$groupsQ = $db->query("SELECT institution_id,institutes_name From  `institutions`  order by institution_id ASC  ");
while ($groupsR = $db->fetch_assoc($groupsQ)) {
    $groups[] = $groupsR;
}






//echo base64_encode(base64_encode('{ "total":'.ceil($totalNum/$pagesize).',"news": '.json_encode($arr).' }'));

echo '{"global": ' . json_encode($global) . ', "translations": ' . json_encode($translations) . ', "groups": ' . json_encode($groups) . ' }';
?>
