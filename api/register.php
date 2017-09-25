<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");

$data['user_id'] = isset($_REQUEST['user_id']) ? $_REQUEST['usre_id'] : null;





if (@$_REQUEST['type'] == 'users') {

    $data['group_id'] = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : null;
    $data['full_name_ar'] = isset($_REQUEST['full_name_ar']) ? $_REQUEST['full_name_ar'] : null;
    $data['full_name_en'] = isset($_REQUEST['full_name_en']) ? $_REQUEST['full_name_en'] : null;
    $data['emirates_id_number'] = isset($_REQUEST['emirates_id_number']) ? $_REQUEST['emirates_id_number'] : null;
    $data['emirates_id_copy'] = isset($_REQUEST['emirates_id_copy']) ? $_REQUEST['emirates_id_copy'] : null;
    $data['passport_number'] = isset($_REQUEST['passport_number']) ? $_REQUEST['passport_number'] : null;
    $data['passport_ex_date'] = isset($_REQUEST['passport_ex_date']) ? $_REQUEST['passport_ex_date'] : null;
    $data['passport_copy'] = isset($_REQUEST['passport_copy']) ? $_REQUEST['passport_copy'] : null;
    $data['personal_photo'] = isset($_REQUEST['personal_photo']) ? $_REQUEST['personal_photo'] : null;
    $data['visaCopy'] = isset($_REQUEST['visaCopy']) ? $_REQUEST['visaCopy'] : null;
    $data['cvCopy'] = isset($_REQUEST['cvCopy']) ? $_REQUEST['cvCopy'] : null;
    $data['nationality_type'] = isset($_REQUEST['nationality_type']) ? $_REQUEST['nationality_type'] : null;
    $data['unid'] = isset($_REQUEST['unid']) ? $_REQUEST['unid'] : null;
    $data['eid_expiry_date'] = isset($_REQUEST['eid_expiry_date']) ? $_REQUEST['eid_expiry_date'] : null;
    $data['qualifications'] = isset($_REQUEST['qualifications']) ? $_REQUEST['qualifications'] : null;
    $data['nationality'] = isset($_REQUEST['nationality']) ? $_REQUEST['nationality'] : null;
    $data['current_emirate'] = isset($_REQUEST['current_emirate']) ? $_REQUEST['current_emirate'] : null;
    $data['date_of_birth'] = isset($_REQUEST['date_of_birth']) ? $_REQUEST['date_of_birth'] : null;
    $data['full_address'] = isset($_REQUEST['full_address']) ? $_REQUEST['full_address'] : null;
    $data['gender'] = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : null;
    $data['marital_status'] = isset($_REQUEST['marital_status']) ? $_REQUEST['marital_status'] : null;
    $data['blood_type'] = isset($_REQUEST['blood_type']) ? $_REQUEST['blood_type'] : null;
    $data['driving_licence'] = isset($_REQUEST['driving_licence']) ? $_REQUEST['driving_licence'] : null;
    $data['job'] = isset($_REQUEST['job']) ? $_REQUEST['job'] : null;
    $data['volunteering_type'] = isset($_REQUEST['volunteering_type']) ? $_REQUEST['volunteering_type'] : null;
    $data['place_of_work'] = isset($_REQUEST['place_of_work']) ? $_REQUEST['place_of_work'] : null;
    $data['home_phone'] = isset($_REQUEST['home_phone']) ? $_REQUEST['home_phone'] : null;
    $data['work_phone'] = isset($_REQUEST['work_phone']) ? $_REQUEST['work_phone'] : null;
    $data['mobile_phone'] = isset($_REQUEST['mobile_phone']) ? $_REQUEST['mobile_phone'] : null;
    $data['pobbox'] = isset($_REQUEST['pobbox']) ? $_REQUEST['pobbox'] : null;
    $data['fax'] = isset($_REQUEST['fax']) ? $_REQUEST['fax'] : null;
    $data['email'] = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $data['password'] = isset($_REQUEST['password']) ? $_REQUEST['password'] : null;
    $total_voluntary_hours = 0;
    $admin_approval = 0;
    $admin_comment = 0;
    $security_approval = 0;
    $security_comment = 0;

/*

            personalPhoto: [],
            visaCopy: [],
            cvCopy: [],
            nationality_type: "",
            unid: "",
            eid_expiry_date: "",
            qualifications: "",
 */

    if (!isset($_REQUEST['user_id'])) {
        $query = "INSERT INTO `users` (`group_id`, `full_name_ar`, `full_name_en`,  `emirates_id_number`,`eid_expiry_date`, `emirates_id_copy`, `passport_number`, `passport_ex_date`, `passport_copy`,`nationality_type`, `nationality`,`unid`,`visa_expiry_date`,`visa_copy`,`personal_photo`, `current_emirate`, `date_of_birth`, `full_address`,`qualifications`,`cv`, `gender`, `marital_status`, `blood_type`, `driving_licence`, `job`, `volunteering_type`, `place_of_work`, `home_phone`, `work_phone`, `mobile_phone`, `fax`, `pobbox`, `email`, `password`, `total_voluntary_hours`, `admin_approval`, `admin_comment`, `security_approval`, `security_comment`)  VALUES (
'" . $data['group_id'] . "',
'" . $data['full_name_ar'] . "',
'" . $data['full_name_en'] . "',
'" . $data['emirates_id_number'] . "',
'" . $data['eid_expiry_date'] . "',
'" . $data['emirates_id_copy'] . "',
'" . $data['passport_number'] . "',
'" . $data['passport_ex_date'] . "',
'" . $data['passport_copy'] . "',
'" . $data['nationality_type'] . "',
'" . $data['nationality'] . "',
'" . $data['visa_expiry_date'] . "',
'" . $data['visa_copy'] . "',
'" . $data['personal_photo'] . "',
'" . $data['current_emirate'] . "',
'" . $data['date_of_birth'] . "',
'" . $data['full_address'] . "',
'" . $data['qualifications'] . "',
'" . $data['gender'] . "',
'" . $data['marital_status'] . "',
'" . $data['blood_type'] . "',
'" . $data['driving_licence'] . "',
'" . $data['job'] . "',
'" . $data['volunteering_type'] . "',
'" . $data['place_of_work'] . "',
'" . $data['home_phone'] . "',
'" . $data['work_phone'] . "',
'" . $data['mobile_phone'] . "',
'" . $data['fax'] . "',
'" . $data['pobbox'] . "',
'" . $data['email'] . "',
'" . $data['password'] . "',
'0', '0','','0','')
";
    } else {

        $query = $db->query("SELECT * from  users where user_id='" . $data['user_id'] . "' ");
        $result = $db->fetch_assoc($result);

        $data['group_id'] = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : $result['group_id'];
        $data['full_name_ar'] = isset($_REQUEST['full_name_ar']) ? $_REQUEST['full_name_ar'] : $result['full_name_ar'];
        $data['full_name_en'] = isset($_REQUEST['full_name_en']) ? $_REQUEST['full_name_en'] : $result['full_name_en'];
        $data['emirates_id_number'] = isset($_REQUEST['emirates_id_number']) ? $_REQUEST['emirates_id_number'] : $result['emirates_id_number'];
        $data['eid_expiry_date'] = isset($_REQUEST['eid_expiry_date']) ? $_REQUEST['eid_expiry_date'] : $result['eid_expiry_date']; 
               
        $data['emirates_id_copy'] = isset($_REQUEST['emirates_id_copy']) ? $_REQUEST['emirates_id_copy'] : $result['emirates_id_copy'];
        $data['passport_number'] = isset($_REQUEST['passport_number']) ? $_REQUEST['passport_number'] : $result['passport_number'];
        $data['passport_ex_date'] = isset($_REQUEST['passport_ex_date']) ? $_REQUEST['passport_ex_date'] : $result['passport_ex_dates'];
        $data['passport_copy'] = isset($_REQUEST['passport_copy']) ? $_REQUEST['passport_copy'] : $result['passport_copy'];

        $data['nationality_type'] = isset($_REQUEST['nationality_type']) ? $_REQUEST['nationality_type'] : $result['nationality_type']; 
        $data['unid'] = isset($_REQUEST['unid']) ? $_REQUEST['unid'] : $result['unid']; 
        $data['visa_expiry_date'] = isset($_REQUEST['visa_expiry_date']) ? $_REQUEST['visa_expiry_date'] : $result['visa_expiry_date']; 
        $data['visa_copy'] = isset($_REQUEST['visa_copy']) ? $_REQUEST['visa_copy'] : $result['visa_copy']; 
        $data['personal_photo'] = isset($_REQUEST['personal_photo']) ? $_REQUEST['personal_photo'] : $result['personal_photo']; 


        $data['nationality'] = isset($_REQUEST['nationality']) ? $_REQUEST['nationality'] : $result['nationality'];
        $data['current_emirate'] = isset($_REQUEST['current_emirate']) ? $_REQUEST['current_emirate'] : $result['current_emirate'];
        $data['date_of_birth'] = isset($_REQUEST['date_of_birth']) ? $_REQUEST['date_of_birth'] : $result['date_of_birth'];
        $data['full_address'] = isset($_REQUEST['full_address']) ? $_REQUEST['full_address'] : $result['full_address'];

        $data['qualifications'] = isset($_REQUEST['qualifications']) ? $_REQUEST['qualifications'] : $result['qualifications']; 
        $data['cv'] = isset($_REQUEST['cv']) ? $_REQUEST['cv'] : $result['cv']; 


        $data['gender'] = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : $result['gender'];
        $data['marital_status'] = isset($_REQUEST['marital_status']) ? $_REQUEST['marital_status'] : $result['marital_status'];
        $data['blood_type'] = isset($_REQUEST['blood_type']) ? $_REQUEST['blood_type'] : $result['blood_type'];
        $data['driving_licence'] = isset($_REQUEST['driving_licence']) ? $_REQUEST['driving_licence'] : $result['driving_licence'];
        $data['job'] = isset($_REQUEST['job']) ? $_REQUEST['job'] : $result['job'];
        $data['volunteering_type'] = isset($_REQUEST['volunteering_type']) ? $_REQUEST['volunteering_type'] : $result['volunteering_type'];
        $data['place_of_work'] = isset($_REQUEST['place_of_work']) ? $_REQUEST['place_of_work'] : $result['place_of_work'];
        $data['home_phone'] = isset($_REQUEST['home_phone']) ? $_REQUEST['home_phone'] : $result['home_phone'];
        $data['work_phone'] = isset($_REQUEST['work_phone']) ? $_REQUEST['work_phone'] : $result['work_phone'];
        $data['mobile_phone'] = isset($_REQUEST['mobile_phone']) ? $_REQUEST['mobile_phone'] : $result['mobile_phone'];
        $data['pobbox'] = isset($_REQUEST['pobbox']) ? $_REQUEST['pobbox'] : $result['pobbox'];
        $data['fax'] = isset($_REQUEST['fax']) ? $_REQUEST['fax'] : $result['fax'];

        $data['email'] = isset($_REQUEST['email']) ? $_REQUEST['email'] : $result['email'];
        $data['password'] = isset($_REQUEST['password']) ? $_REQUEST['password'] : $result['password'];



        $query = "UPDATE `users` SET
`group_id` = '" . $data['group_id'] . "',
`full_name_ar` = '" . $data['full_name_ar'] . "',
`full_name_en` = '" . $data['full_name_en'] . "',
`emirates_id_number` = '" . $data['emirates_id_number'] . "',
`eid_expiry_date` = '" . $data['eid_expiry_date'] . "',

`emirates_id_copy` = '" . $data['emirates_id_copy'] . "',
`passport_number` = '" . $data['passport_number'] . "',
`passport_ex_date` = '" . $data['passport_ex_date'] . "',
`passport_copy` = '" . $data['passport_copy'] . "',

`nationality_type` = '" . $data['nationality_type'] . "',
`unid` = '" . $data['unid'] . "',
`visa_expiry_date` = '" . $data['visa_expiry_date'] . "',
`visa_copy` = '" . $data['visa_copy'] . "',
`personal_photo` = '" . $data['personal_photo'] . "',


`nationality` = '" . $data['nationality'] . "',
`current_emirate` = '" . $data['current_emirate'] . "',
`date_of_birth` = '" . $data['date_of_birth'] . "',
`full_address` = '" . $data['full_address'] . "',

`qualifications` = '" . $data['qualifications'] . "',
`cv` = '" . $data['cv'] . "',

`gender` = '" . $data['gender'] . "',
`marital_status` = '" . $data['marital_status'] . "',
`blood_type` = '" . $data['blood_type'] . "',
`driving_licence` = '" . $data['driving_licence'] . "',
`job` = '" . $data['job'] . "',
`volunteering_type` = '" . $data['volunteering_type'] . "',
`place_of_work` = '" . $data['place_of_work'] . "',
`home_phone` = '" . $data['home_phone'] . "',
`work_phone` = '" . $data['work_phone'] . "',
`mobile_phone` = '" . $data['mobile_phone'] . "',
`fax` = '" . $data['fax'] . "',
`pobbox` = '" . $data['pobbox'] . "',
`email` = '" . $data['email'] . "',
`password` = '" . $data['password'] . "', 
`total_voluntary_hours` = '" . $data['total_voluntary_hours'] . "'
WHERE `users`.`user_id` = '" . $_REQUEST['user_id'] . "'";
    }
}



$result = $db->query("SELECT * from  $table where email='" . $data['email'] . "' ");
$totalNum = $db->returned_rows;



if ($totalNum == 0) {



    $c = $db->query($query);
    $rows = $db->fetch_assoc($c);

    $result = $db->query("SELECT * from  $table where email='" . $data['email'] . "'   ");
    $rows = $db->fetch_assoc($result);
    echo '{ "logged":' . json_encode($rows) . ' }';
} else {


// $result = $db->query(" UPDATE users SET `area`='$area',`fullname`='$fullname',`address`='$address' where mobile='$mobile'   "); 
// $result = $db->query("SELECT * from users where mobile='$mobile'   "); 
// $rows=$db->fetch_assoc($result);
// echo '{ "user":'.json_encode($rows).' }';
}
?>
