<?php

header('Cache-Control: private');
header('Access-Control-Allow-Origin:*');
header('Alternate-Protocol:80:quic,80:quic');
header('Content-Type: text/html; charset=utf-8');
include_once("connect.php");

//error_reporting(0);




$REQUEST = json_decode(file_get_contents('php://input'), true)[0];




$data['user_id'] = isset($REQUEST['user_id']) ? $REQUEST['user_id'] : null;
$data['institution_id'] = isset($REQUEST['institution_id']) ? $REQUEST['institution_id'] : null;


/* if we pass user_id in parameters the Query Will UPDATE the current user_id  */
if (@$REQUEST['type'] == 'users') {




    /* in case indivisual User */
    $data['group_id'] = isset($REQUEST['group_id']) ? $REQUEST['group_id'] : null;
    $data['full_name_ar'] = isset($REQUEST['full_name_ar']) ? $REQUEST['full_name_ar'] : null;
    $data['full_name_en'] = isset($REQUEST['full_name_en']) ? $REQUEST['full_name_en'] : null;
    $data['emirates_id_number'] = isset($REQUEST['emirates_id_number']) ? $REQUEST['emirates_id_number'] : null;

    $data['eid_expiry_date'] = isset($REQUEST['eid_expiry_date']) ? $REQUEST['eid_expiry_date'] : null;


    $data['emiratesIDs'] = isset($REQUEST['emiratesIDs']) ? $REQUEST['emiratesIDs'] : null;
    $data['passport_number'] = isset($REQUEST['passport_number']) ? $REQUEST['passport_number'] : null;
    $data['passport_ex_date'] = isset($REQUEST['passport_ex_date']) ? $REQUEST['passport_ex_date'] : null;
    $data['passports'] = isset($REQUEST['passports']) ? $REQUEST['passports'] : null;

    $data['nationality_type'] = isset($REQUEST['nationality_type']) ? $REQUEST['nationality_type'] : null;
    $data['unid'] = isset($REQUEST['unid']) ? $REQUEST['unid'] : null;
    $data['visa_expiry_date'] = isset($REQUEST['visa_expiry_date']) ? $REQUEST['visa_expiry_date'] : null;
    $data['visa_copy'] = isset($REQUEST['visa_copy']) ? $REQUEST['visa_copy'] : null;
    $data['personal_photo'] = isset($REQUEST['personal_photo']) ? $REQUEST['personal_photo'] : null;



    $data['nationality'] = isset($REQUEST['nationality']) ? $REQUEST['nationality'] : null;
    $data['current_emirate'] = isset($REQUEST['current_emirate']) ? $REQUEST['current_emirate'] : null;
    $data['date_of_birth'] = isset($REQUEST['date_of_birth']) ? $REQUEST['date_of_birth'] : null;
    $data['full_address'] = isset($REQUEST['full_address']) ? $REQUEST['full_address'] : null;
    $data['qualifications'] = isset($REQUEST['qualifications']) ? $REQUEST['qualifications'] : null;
    $data['cv'] = isset($REQUEST['cv']) ? $REQUEST['cv'] : null;

    $data['gender'] = isset($REQUEST['gender']) ? $REQUEST['gender'] : null;
    $data['marital_status'] = isset($REQUEST['marital_status']) ? $REQUEST['marital_status'] : null;
    $data['blood_type'] = isset($REQUEST['blood_type']) ? $REQUEST['blood_type'] : null;
    $data['driving_licence'] = isset($REQUEST['driving_licence']) ? $REQUEST['driving_licence'] : null;
    $data['job'] = isset($REQUEST['job']) ? $REQUEST['job'] : null;
    $data['volunteering_type'] = isset($REQUEST['volunteering_type']) ? $REQUEST['volunteering_type'] : null;
    $data['place_of_work'] = isset($REQUEST['place_of_work']) ? $REQUEST['place_of_work'] : null;
    $data['home_phone'] = isset($REQUEST['home_phone']) ? $REQUEST['home_phone'] : null;
    $data['work_phone'] = isset($REQUEST['work_phone']) ? $REQUEST['work_phone'] : null;
    $data['mobile_phone'] = isset($REQUEST['mobile_phone']) ? $REQUEST['mobile_phone'] : null;
    $data['pobbox'] = isset($REQUEST['pobbox']) ? $REQUEST['pobbox'] : null;
    $data['fax'] = isset($REQUEST['fax']) ? $REQUEST['fax'] : null;
    $data['email'] = isset($REQUEST['email']) ? $REQUEST['email'] : null;
    $data['password'] = isset($REQUEST['password']) ? $REQUEST['password'] : null;
    $total_voluntary_hours = 0;
    $admin_approval = 0;
    $admin_comment = 0;
    $security_approval = 0;
    $security_comment = 0;
    $table = 'users';



    if (isset($REQUEST['emiratesIDs'])) {
        $arrr = [];
        $eidJSON = $REQUEST['emiratesIDs'];
        for ($i = 0; $i < count($eidJSON); $i++) {
            $arrr[] = $eidJSON[$i]['filename'];
        }
        $uploadedIEDS = implode(',', $arrr);
    } else {
        $uploadedIEDS = "";
    }


    if (isset($REQUEST['personalPhoto'])) {
        $arrr = [];
        $personalPhotoJSON = $REQUEST['personalPhoto'];
        for ($i = 0; $i < count($personalPhotoJSON); $i++) {
            $arrr[] = $personalPhotoJSON[$i]['filename'];
        }
        $uploadedPersonalPhoto = implode(',', $arrr);
    } else {
        $uploadedPersonalPhoto = "";
    }


    if (isset($REQUEST['cvCopy'])) {
        $arrr = [];
        $cvCopyJSON = $REQUEST['cvCopy'];
        for ($i = 0; $i < count($cvCopyJSON); $i++) {
            $arrr[] = $cvCopyJSON[$i]['filename'];
        }
        $uploadedCvCopy = implode(',', $arrr);
    } else {
        $uploadedCvCopy = "";
    }


    if (isset($REQUEST['visaCopy'])) {
        $arrr = [];
        $visaCopyJSON = $REQUEST['visaCopy'];
        for ($i = 0; $i < count($visaCopyJSON); $i++) {
            $arrr[] = $visaCopyJSON[$i]['filename'];
        }
        $uploadedVisaCopy = implode(',', $arrr);
    } else {
        $uploadedVisaCopy = "";
    }        



    if (isset($REQUEST['passports'])) {
        $passportsJSON = $REQUEST['passports'];
        $arrw = [];
        for ($i = 0; $i < count($passportsJSON); $i++) {
             $arrw[] = $passportsJSON[$i]['filename'];
        }
        $uploadedPASS = implode(',', $arrw);
    } else {
        $uploadedPASS = "";
    }


    /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    if ($REQUEST['user_id'] == 0) {
        /* build Insert Query */
        $query = "INSERT INTO `users` (`group_id`, `full_name_ar`, `full_name_en`,  `emirates_id_number`,`eid_expiry_date`, `emirates_id_copy`, `passport_number`, `passport_ex_date`, `passport_copy`,`nationality_type`, `nationality`, `unid`, `visa_expiry_date`, `visa_copy`, `personal_photo`, `current_emirate`, `date_of_birth`, `full_address`,`qualifications`,`cv`, `gender`, `marital_status`, `blood_type`, `driving_licence`, `job`, `volunteering_type`, `place_of_work`, `home_phone`, `work_phone`, `mobile_phone`, `fax`, `pobbox`, `email`, `password`, `total_voluntary_hours`, `admin_approval`, `admin_comment`, `security_approval`, `security_comment`)  VALUES (
					'" . $data['group_id'] . "',
					'" . $data['full_name_ar'] . "',
					'" . $data['full_name_en'] . "',
                    '" . $data['eid_expiry_date'] . "',
					'" . $data['emirates_id_number'] . "',
					'" . $uploadedIEDS . "',
					'" . $data['passport_number'] . "',
					'" . $data['passport_ex_date'] . "',
					'" . $uploadedPASS . "',
                    '" . $data['nationality_type'] . "',
					'" . $data['nationality'] . "',
                    '" . $data['unid'] . "',
                    '" . $data['visa_expiry_date'] . "',
                    '" . $uploadedVisaCopy . "',
                    '" . $uploadedPersonalPhoto . "',
					'" . $data['current_emirate'] . "',
					'" . $data['date_of_birth'] . "',
					'" . $data['full_address'] . "',
                    '" . $data['qualifications'] . "',
                    '" . $uploadedCvCopy . "',
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
        /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    } else {
        /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
        /* Analyze Update Query Default Variables */
        $query = $db->query("SELECT * from  users where user_id='" . $data['user_id'] . "' ");
        $result = $db->fetch_assoc($query);

        $data['group_id'] = isset($REQUEST['group_id']) ? $REQUEST['group_id'] : $result['group_id'];
        $data['full_name_ar'] = isset($REQUEST['full_name_ar']) ? $REQUEST['full_name_ar'] : $result['full_name_ar'];
        $data['full_name_en'] = isset($REQUEST['full_name_en']) ? $REQUEST['full_name_en'] : $result['full_name_en'];
        $data['eid_expiry_date'] = isset($REQUEST['eid_expiry_date']) ? $REQUEST['eid_expiry_date'] : $result['eid_expiry_date'];
        $data['emirates_id_number'] = isset($REQUEST['emirates_id_number']) ? $REQUEST['emirates_id_number'] : $result['emirates_id_number'];
        $data['emirates_id_copy'] = $uploadedIEDS != "" ? $uploadedIEDS : $result['emirates_id_copy'];
        $data['passport_number'] = isset($REQUEST['passport_number']) ? $REQUEST['passport_number'] : $result['passport_number'];
        $data['passport_ex_date'] = isset($REQUEST['passport_ex_date']) ? $REQUEST['passport_ex_date'] : $result['passport_ex_date'];
        $data['passport_copy'] = $uploadedPASS != "" ? $uploadedPASS : $result['passport_copy'];
        $data['nationality_type'] = isset($REQUEST['nationality_type']) ? $REQUEST['nationality_type'] : $result['nationality_type'];
        $data['nationality'] = isset($REQUEST['nationality']) ? $REQUEST['nationality'] : $result['nationality'];
        $data['unid'] = isset($REQUEST['unid']) ? $REQUEST['unid'] : $result['unid'];
        $data['visa_expiry_date'] = isset($REQUEST['visa_expiry_date']) ? $REQUEST['visa_expiry_date'] : $result['visa_expiry_date'];
        $data['visa_copy'] = $uploadedVisaCopy != "" ? $uploadedVisaCopy : $result['visa_copy'];
        $data['personal_photo'] = $uploadedPersonalPhoto != "" ? $uploadedPersonalPhoto : $result['personal_photo'];
        $data['current_emirate'] = isset($REQUEST['current_emirate']) ? $REQUEST['current_emirate'] : $result['current_emirate'];
        $data['date_of_birth'] = isset($REQUEST['date_of_birth']) ? $REQUEST['date_of_birth'] : $result['date_of_birth'];
        $data['full_address'] = isset($REQUEST['full_address']) ? $REQUEST['full_address'] : $result['full_address'];
        $data['qualifications'] = isset($REQUEST['qualifications']) ? $REQUEST['qualifications'] : $result['qualifications'];
        $data['cv'] = $uploadedCvCopy != "" ? $uploadedCvCopy : $result['cv'];
        $data['gender'] = isset($REQUEST['gender']) ? $REQUEST['gender'] : $result['gender'];
        $data['marital_status'] = isset($REQUEST['marital_status']) ? $REQUEST['marital_status'] : $result['marital_status'];
        $data['blood_type'] = isset($REQUEST['blood_type']) ? $REQUEST['blood_type'] : $result['blood_type'];
        $data['driving_licence'] = isset($REQUEST['driving_licence']) ? $REQUEST['driving_licence'] : $result['driving_licence'];
        $data['job'] = isset($REQUEST['job']) ? $REQUEST['job'] : $result['job'];
        $data['volunteering_type'] = isset($REQUEST['volunteering_type']) ? $REQUEST['volunteering_type'] : $result['volunteering_type'];
        $data['place_of_work'] = isset($REQUEST['place_of_work']) ? $REQUEST['place_of_work'] : $result['place_of_work'];
        $data['home_phone'] = isset($REQUEST['home_phone']) ? $REQUEST['home_phone'] : $result['home_phone'];
        $data['work_phone'] = isset($REQUEST['work_phone']) ? $REQUEST['work_phone'] : $result['work_phone'];
        $data['mobile_phone'] = isset($REQUEST['mobile_phone']) ? $REQUEST['mobile_phone'] : $result['mobile_phone'];
        $data['pobbox'] = isset($REQUEST['pobbox']) ? $REQUEST['pobbox'] : $result['pobbox'];
        $data['fax'] = isset($REQUEST['fax']) ? $REQUEST['fax'] : $result['fax'];
        $data['total_voluntary_hours'] = isset($REQUEST['total_voluntary_hours']) ? $REQUEST['total_voluntary_hours'] : $result['total_voluntary_hours'];
        $data['email'] = isset($REQUEST['email']) ? $REQUEST['email'] : $result['email'];
        $data['password'] = isset($REQUEST['password']) ? $REQUEST['password'] : $result['password'];
        /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
        /* build UPDATE Query */

        $query = "UPDATE `users` SET
			 `group_id` = '" . $data['group_id'] . "',
			 `full_name_ar` = '" . $data['full_name_ar'] . "',
			 `full_name_en` = '" . $data['full_name_en'] . "',
             `eid_expiry_date` = '" . $data['eid_expiry_date'] . "',
			 `emirates_id_number` = '" . $data['emirates_id_number'] . "',
			 `emirates_id_copy` = '" . $data['emirates_id_copy'] . "',
			 `passport_number` = '" . $data['passport_number'] . "',
			 `passport_ex_date` = '" . $data['passport_ex_date'] . "',
			 `passport_copy` = '" . $data['passport_copy'] . "',
			 `nationality_type` = '" . $data['nationality_type'] . "',
             `nationality` = '" . $data['nationality'] . "',
             `unid` = '" . $data['unid'] . "',
             `visa_expiry_date` = '" . $data['visa_expiry_date'] . "',
             `visa_copy` = '" . $data['visa_copy'] . "',
             `personal_photo` = '" . $data['personal_photo'] . "',
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
			  WHERE `users`.`user_id` = '" . $REQUEST['user_id'] . "'";
    }
}


/* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* In Case the Inistitution Login */


if (@$REQUEST['type'] == 'institutions') {
    /* in case indivisual User */

    $data['institution_type'] = isset($REQUEST['institution_type']) ? $REQUEST['institution_type'] : null;
    $data['institutes_name'] = isset($REQUEST['institutes_name']) ? $REQUEST['institutes_name'] : null;
    $data['trade_licence'] = isset($REQUEST['trade_licence']) ? $REQUEST['trade_licence'] : null;
    $data['licence_no'] = isset($REQUEST['licence_no']) ? $REQUEST['licence_no'] : null;
    $data['full_name_ar'] = isset($REQUEST['full_name_ar']) ? $REQUEST['full_name_ar'] : null;
    $data['full_name_en'] = isset($REQUEST['full_name_en']) ? $REQUEST['full_name_en'] : null;

    $data['emirates_id_number'] = isset($REQUEST['emirates_id_number']) ? $REQUEST['emirates_id_number'] : null;
    $data['emiratesIDs'] = isset($REQUEST['emiratesIDs']) ? $REQUEST['emiratesIDs'] : null;
    $data['passport_number'] = isset($REQUEST['passport_number']) ? $REQUEST['passport_number'] : null;
    $data['passport_ex_date'] = isset($REQUEST['passport_ex_date']) ? $REQUEST['passport_ex_date'] : null;
    $data['passport_numbers'] = isset($REQUEST['passports']) ? $REQUEST['passports'] : null;
    
    $data['eid_expiry_date'] = isset($REQUEST['eid_expiry_date']) ? $REQUEST['eid_expiry_date'] : null;
    $data['tl_expiry_date'] = isset($REQUEST['tl_expiry_date']) ? $REQUEST['tl_expiry_date'] : null;
    $data['nationality_type'] = isset($REQUEST['nationality_type']) ? $REQUEST['nationality_type'] : null;
    $data['visa_expiry_date'] = isset($REQUEST['visa_expiry_date']) ? $REQUEST['visa_expiry_date'] : null;
    $data['unid'] = isset($REQUEST['unid']) ? $REQUEST['unid'] : null;
    $data['visa_copy'] = isset($REQUEST['visa_copy']) ? $REQUEST['visa_copy'] : null;

    $data['nationality'] = isset($REQUEST['nationality']) ? $REQUEST['nationality'] : null;
    $data['current_emirate'] = isset($REQUEST['current_emirate']) ? $REQUEST['current_emirate'] : null;
    $data['full_address'] = isset($REQUEST['full_address']) ? $REQUEST['full_address'] : null;
    $data['volunteering_type'] = isset($REQUEST['volunteering_type']) ? $REQUEST['volunteering_type'] : null;
    $data['place_of_work'] = isset($REQUEST['place_of_work']) ? $REQUEST['place_of_work'] : null;
    $data['work_phone'] = isset($REQUEST['work_phone']) ? $REQUEST['work_phone'] : null;
    $data['mobile_phone'] = isset($REQUEST['mobile_phone']) ? $REQUEST['mobile_phone'] : null;
    $data['fax'] = isset($REQUEST['fax']) ? $REQUEST['fax'] : null;
    $data['pobbox'] = isset($REQUEST['pobbox']) ? $REQUEST['pobbox'] : null;
    $data['email'] = isset($REQUEST['email']) ? $REQUEST['email'] : null;
    $data['password'] = isset($REQUEST['password']) ? $REQUEST['password'] : null;
    $admin_approval = 0;
    $admin_comment = 0;
    $eco_department_approval = 0;
    $eco_departmnet_comment = 0;
    $security_approval = 0;
    $security_comment = 0;
    $table = 'institutions';



    if (isset($REQUEST['emiratesIDs'])) {
        $arrr = [];
        $eidJSON = $REQUEST['emiratesIDs'];
        for ($i = 0; $i < count($eidJSON); $i++) {
            $arrr[] = $eidJSON[$i]['filename'];
        }
        $uploadedIEDS = implode(',', $arrr);
    } else {
        $uploadedIEDS = "";
    }


    if (isset($REQUEST['passports'])) {
        $passportsJSON = $REQUEST['passports'];
        $arrw = [];
        for ($i = 0; $i < count($passportsJSON); $i++) {
             $arrw[] = $passportsJSON[$i]['filename'];
        }
        $uploadedPASS = implode(',', $arrw);
    } else {
        $uploadedPASS = "";
    }


    if (isset($REQUEST['trade_licence'])) {
        $tradeLicenceJSON = $REQUEST['trade_licence'];
        $arrw = [];
        for ($i = 0; $i < count($tradeLicenceJSON); $i++) {
             $arrw[] = $tradeLicenceJSON[$i]['filename'];
        }
        $uploadedTradeLicence = implode(',', $arrw);
    } else {
        $uploadedTradeLicence = "";
    }

    if (isset($REQUEST['visaCopy'])) {
        $arrr = [];
        $visaCopyJSON = $REQUEST['visaCopy'];
        for ($i = 0; $i < count($visaCopyJSON); $i++) {
            $arrr[] = $visaCopyJSON[$i]['filename'];
        }
        $uploadedVisaCopy = implode(',', $arrr);
    } else {
        $uploadedVisaCopy = "";
    }        


    /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    if ($REQUEST['institution_id'] == 0) {
        /* build Insert Query */
        $query = "INSERT INTO `institutions` (`institution_type`, `institutes_name`, `trade_licence`, `licence_no`,`tl_expiry_date`, `full_name_ar`, `full_name_en`,  `emirates_id_number`,`eid_expiry_date`, `emirates_id_copy`, `passport_ex_date`, `passport_copy`, `nationality`,`nationality_type`,`visa_expiry_date`,`unid`,`visa_copy`, `current_emirate`, `full_address`, `volunteering_type`, `place_of_work`, `work_phone`, `mobile_phone`, `fax`, `pobbox`, `email`, `password`, `admin_approval`, `admin_comment`, `eco_department_approval`, `eco_departmnet_comment`, `security_approval`, `security_comment`)  VALUES (
					'" . $data['institution_type'] . "',
					'" . $data['institutes_name'] . "',
					'" . $uploadedTradeLicence . "',
					'" . $data['licence_no'] . "',
                    '" . $data['tl_expiry_date'] . "',                    
					'" . $data['full_name_ar'] . "',
					'" . $data['full_name_en'] . "',
					'" . $data['emirates_id_number'] . "',
                    '" . $data['eid_expiry_date'] . "',
					'" . $uploadedIEDS . "',
					'" . $data['passport_ex_date'] . "',
					'" . $uploadedPASS . "',
					'" . $data['nationality'] . "',
                    '" . $data['nationality_type'] . "',
                    '" . $data['visa_expiry_date'] . "',
                    '" . $data['unid'] . "',
                    '" . $uploadedVisaCopy . "',
					'" . $data['current_emirate'] . "',
					'" . $data['full_address'] . "',
					'" . $data['volunteering_type'] . "',
					'" . $data['place_of_work'] . "',
					'" . $data['work_phone'] . "',
					'" . $data['mobile_phone'] . "',
					'" . $data['fax'] . "',
					'" . $data['pobbox'] . "',
					'" . $data['email'] . "',
					'" . $data['password'] . "',
					'0', '', '0', '','0','');
					";

/* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
    } else {
        /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
        /* Analyze Update Query Default Variables */
        $query = $db->query("SELECT * from  institutions where institution_id='" . $data['institution_id'] . "' ");
        $result = $db->fetch_assoc($query);



        $data['institution_type'] = isset($REQUEST['institution_type']) ? $REQUEST['institution_type'] : $result['institution_type'];
        $data['institutes_name'] = isset($REQUEST['institutes_name']) ? $REQUEST['institutes_name'] : $result['institutes_name'];
        $data['trade_licence'] = $uploadedTradeLicence != "" ? $uploadedTradeLicence : $result['trade_licence'];
        $data['tl_expiry_date'] = isset($REQUEST['tl_expiry_date']) ? $REQUEST['tl_expiry_date'] : $result['tl_expiry_date'];
        $data['eid_expiry_date'] = isset($REQUEST['eid_expiry_date']) ? $REQUEST['eid_expiry_date'] : $result['eid_expiry_date'];
        $data['nationality_type'] = isset($REQUEST['nationality_type']) ? $REQUEST['nationality_type'] : $result['nationality_type'];
        $data['visa_expiry_date'] = isset($REQUEST['visa_expiry_date']) ? $REQUEST['visa_expiry_date'] : $result['visa_expiry_date'];
        $data['unid'] = isset($REQUEST['unid']) ? $REQUEST['unid'] : $result['unid'];
        $data['visa_copy'] = $uploadedTradeLicence != "" ? $uploadedVisaCopy : $result['visa_copy'];

        $data['licence_no'] = isset($REQUEST['licence_no']) ? $REQUEST['licence_no'] : $result['licence_no'];
        $data['full_name_ar'] = isset($REQUEST['full_name_ar']) ? $REQUEST['full_name_ar'] : $result['full_name_ar'];
        $data['full_name_en'] = isset($REQUEST['full_name_en']) ? $REQUEST['full_name_en'] : $result['full_name_en'];

        $data['emirates_id_number'] = isset($REQUEST['emirates_id_number']) ? $REQUEST['emirates_id_number'] : $result['emirates_id_number'];
        $data['emirates_id_copy'] = $uploadedIEDS != "" ? $uploadedIEDS : $result['emirates_id_copy'];
        $data['passport_number'] = isset($REQUEST['passport_number']) ? $REQUEST['passport_number'] : $result['passport_number'];
        $data['passport_ex_date'] = isset($REQUEST['passport_ex_date']) ? $REQUEST['passport_ex_date'] : $result['passport_ex_date'];
        $data['passport_copy'] = $uploadedPASS != "" ? $uploadedPASS : $result['passport_copy'];
        $data['nationality'] = isset($REQUEST['nationality']) ? $REQUEST['nationality'] : $result['nationality'];
        $data['current_emirate'] = isset($REQUEST['current_emirate']) ? $REQUEST['current_emirate'] : $result['current_emirate'];
        $data['full_address'] = isset($REQUEST['full_address']) ? $REQUEST['full_address'] : $result['full_address'];
        $data['volunteering_type'] = isset($REQUEST['volunteering_type']) ? $REQUEST['volunteering_type'] : $result['volunteering_type'];
        $data['place_of_work'] = isset($REQUEST['place_of_work']) ? $REQUEST['place_of_work'] : $result['place_of_work'];
        $data['work_phone'] = isset($REQUEST['work_phone']) ? $REQUEST['work_phone'] : $result['work_phone'];
        $data['mobile_phone'] = isset($REQUEST['mobile_phone']) ? $REQUEST['mobile_phone'] : $result['mobile_phone'];
        $data['fax'] = isset($REQUEST['fax']) ? $REQUEST['fax'] : $result['fax'];
        $data['pobbox'] = isset($REQUEST['pobbox']) ? $REQUEST['pobbox'] : $result['pobbox'];
        $data['email'] = isset($REQUEST['email']) ? $REQUEST['email'] : $result['email'];
        $data['password'] = isset($REQUEST['password']) ? $REQUEST['password'] : $result['password'];
        /* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
        /* build UPDATE Query */
        $query = "UPDATE `institutions` SET
			 `institution_type` = '" . $data['institution_type'] . "',
			 `institutes_name` = '" . $data['institutes_name'] . "',
             `tl_expiry_date` = '" . $data['tl_expiry_date'] . "',
             `eid_expiry_date` = '" . $data['eid_expiry_date'] . "',
             `nationality_type` = '" . $data['nationality_type'] . "',
             `visa_expiry_date` = '" . $data['visa_expiry_date'] . "',
             `unid` = '" . $data['unid'] . "',
             `visa_copy` = '" . $data['visa_copy'] . "',

			 `trade_licence` = '" . $data['trade_licence'] . "',
			 `licence_no` = '" . $data['licence_no'] . "',
			 `full_name_ar` = '" . $data['full_name_ar'] . "',
			 `full_name_en` = '" . $data['full_name_en'] . "',
			 `emirates_id_number` = '" . $data['emirates_id_number'] . "',
			 `emirates_id_copy` = '" . $data['emirates_id_copy'] . "',
			 `passport_number` = '" . $data['passport_number'] . "',
			 `passport_ex_date` = '" . $data['passport_ex_date'] . "',
			 `passport_copy` = '" . $data['passport_copy'] . "',
			 `nationality` = '" . $data['nationality'] . "',
			 `current_emirate` = '" . $data['current_emirate'] . "',
			 `full_address` = '" . $data['full_address'] . "',
			 `volunteering_type` = '" . $data['volunteering_type'] . "',
			 `place_of_work` = '" . $data['place_of_work'] . "',
			 `work_phone` = '" . $data['work_phone'] . "',
			 `mobile_phone` = '" . $data['mobile_phone'] . "',
			 `fax` = '" . $data['fax'] . "',
			 `pobbox` = '" . $data['pobbox'] . "',
			 `email` = '" . $data['email'] . "',
			 `password` = '" . $data['password'] . "'
			  WHERE `institutions`.`institution_id` = '" . $REQUEST['institution_id'] . "'";
    }
}



/* ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
/* EXECUTE RETURN QUERY */
$c = $db->query($query);
$rows = $db->fetch_assoc($c);

$result = $db->query("SELECT * from  $table where email='" . $data['email'] . "'   ");
$rows = $db->fetch_assoc($result);

$files = @explode(",", $rows['emirates_id_copy']);
$arr = [];
for ($i = 0; $i < count(@$files); $i++) {
    $arr[] = '{"filename":"' . @$files[$i] . '"}';
}


$pfiles = @explode(",", $rows['passport_copy']);
$parr = [];
for ($i = 0; $i < count(@$pfiles); $i++) {
    $parr[] = '{"filename":"' . @$pfiles[$i] . '"}';
}


$tfiles = @explode(",", $rows['trade_licence']);
$tarr = [];
for ($i = 0; $i < count(@$tfiles); $i++) {
    $tarr[] = '{"filename":"' . @$tfiles[$i] . '"}';
}


//print_r($arr);

if (@$rows['emirates_id_copy']) {
    @$rows['emiratesIDs'] = json_decode('[' . implode(",", $arr) . ']');
} else {
   @$rows['emiratesIDs'] = [];
}



if (@$rows['passport_copy']) {
    @$rows['passports'] = json_decode('[' . implode(",", $parr) . ']');
} else {
    @$rows['passports'] = [];
}
if (@$rows['trade_licence']) {
    @$rows['tradeLicence'] = json_decode('[' . implode(",", $tarr) . ']');
}

if (@$rows['visaCopy']) {
    @$rows['visaCopy'] = json_decode('[' . implode(",", $tarr) . ']');
}



echo '{ "record":' . json_encode($rows) . ' }';
?>
