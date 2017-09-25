<?php

include('Zebra_Database.php');
error_reporting(E_ALL);
/*
  if(!isset($_REQUEST['hash'])){ echo "Bad Request !!"; die(); }
  $checkHash=  base64_decode(base64_decode($_REQUEST['hash']));

  $checkHash= explode("&time=", $checkHash, 2);
  $c = time();
  $r = intval(substr($checkHash[1],0,-3));
  $diff = $c - $r;
  if($diff > 1000 ){ echo "RequestTimeOut !!"; die();  }

  $arrcey = explode("&hash",$checkHash[0], 2);
  $first = $arrcey[0];
  $arr32 = explode("&hash",$_SERVER['QUERY_STRING'], 2);
  $second = $arr32[0];
  //echo $first."<br>".$second;
  if($first != $second ){ echo "Bad Request !!"; die(); }else{  }
 */

// database connect
if ($_SERVER['SERVER_NAME'] == '192.168.1.112' || $_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
    $dbname = 'adid';
    $dbuname = 'root';
    $dbpw = '';
    $dbhost = 'localhost';
} else {
    $dbname = 'adid_newdb';
    $dbuname = 'adid_newdb';
    $dbpw = 'newdb123!@#';
    $dbhost = 'localhost';
}

// create a new database wrapper object
$db = new Zebra_Database();


// turn debugging on
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $db->debug = false;
} else {
    $db->debug = false;
}



// connect to the MySQL server and select the database
$db->connect(
        $dbhost, // host
        $dbuname, // user name
        $dbpw, // password
        $dbname         // database
);


$db->set_charset();


//if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']=="http://127.0.0.1/support/"){ if (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) != 'xmlhttprequest') { die(); } }else{ die(); }
?>