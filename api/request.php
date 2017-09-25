<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");

//error_reporting(0);




$data = json_decode(file_get_contents('php://input'), true)[0];







        /* build Insert Query */
       echo $query = "INSERT INTO `institutions_requests` (`institutions_id`, `event_name`, `event_emirate`, `event_location`, `activity_start_date`, `activity_end_date`, `activity_time`, `activity_description`, `activity_gender_target`, `no_of_persons_needed`, `no_of_hours`, `mobile_phone`, `fax`, `pobox`, `admin_approval`, `admin_comment`)  VALUES (
					'" . $data['institutions_id'] . "',
					'" . $data['event_name'] . "',
					'" . $data['event_emirate'] . "',
					'" . $data['event_location'] . "',
					'" . $data['activity_start_date'] . "',
					'" . $data['activity_end_date'] . "',
					'" . $data['activity_time'] . "',
					'" . $data['activity_description'] . "',
					'" . $data['activity_gender_target'] . "',
					'" . $data['no_of_persons_needed'] . "',
					'" . $data['no_of_hours'] . "',
					'" . $data['mobile_phone'] . "',
					'" . $data['fax'] . "',
					'" . $data['pobox'] . "',
					'0',
					'')
					";
        /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    $do = $db->query($query);


echo '1';
?>
