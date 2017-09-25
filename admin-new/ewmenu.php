<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(2, "mi_global_settings", $Language->MenuPhrase("2", "MenuText"), "global_settingslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}global_settings'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(6, "mi_users", $Language->MenuPhrase("6", "MenuText"), "userslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}users'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(9, "mi_institutions", $Language->MenuPhrase("9", "MenuText"), "institutionslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}institutions'), FALSE, FALSE, "");

$RootMenu->AddMenuItem(1, "mi_activities", $Language->MenuPhrase("1", "MenuText"), "activitieslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}activities'), FALSE, FALSE, "");

$RootMenu->AddMenuItem(4, "mi_registered_users", $Language->MenuPhrase("4", "MenuText"), "registered_userslist.php?cmd=resetall", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}registered_users'), FALSE, FALSE, "");

$RootMenu->AddMenuItem(23, "mi_expired_users", $Language->MenuPhrase("23", "MenuText"), "expired_userslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}expired users'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(45, "mi_expired_institutions", $Language->MenuPhrase("45", "MenuText"), "expired_institutionslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}expired_institutions'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(10, "mi_institutions_requests", $Language->MenuPhrase("10", "MenuText"), "institutions_requestslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}institutions_requests'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(20, "mi_rating_criteria_list", $Language->MenuPhrase("20", "MenuText"), "rating_criteria_listlist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}rating_criteria_list'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(17, "mi_members_stars_criteria", $Language->MenuPhrase("17", "MenuText"), "members_stars_criterialist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}members_stars_criteria'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(18, "mi_members_titles_criteria", $Language->MenuPhrase("18", "MenuText"), "members_titles_criterialist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}members_titles_criteria'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(21, "mi_rating_values", $Language->MenuPhrase("21", "MenuText"), "rating_valueslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}rating_values'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(15, "mi_admin_notifications", $Language->MenuPhrase("15", "MenuText"), "admin_notificationslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}admin_notifications'), FALSE, FALSE, "");


if($_SESSION['adid_status_UserLevel'] == '-2'){

$RootMenu->AddMenuItem(3, "mi_secure_management", $Language->MenuPhrase("3", "MenuText"), "secure_managementlist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}management'), FALSE, FALSE, "");

}else{
$RootMenu->AddMenuItem(12, "mi_userlevels", $Language->MenuPhrase("12", "MenuText"), "userlevelslist.php", -1, "", IsAdmin(), FALSE, FALSE, "");
	
$RootMenu->AddMenuItem(3, "mi_management", $Language->MenuPhrase("3", "MenuText"), "managementlist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}management'), FALSE, FALSE, "");
	
}



$RootMenu->AddMenuItem(5, "mi_static_texts", $Language->MenuPhrase("5", "MenuText"), "static_textslist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}static_texts'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(7, "mi_audittrail", $Language->MenuPhrase("7", "MenuText"), "audittraillist.php", -1, "", AllowListMenu('{6A6E587E-A14E-4B9E-9243-D8812A8F089D}audittrail'), FALSE, FALSE, "");

if($_SESSION['adid_status_UserLevel'] == '-2'){
$RootMenu->AddMenuItem(44, "mci_62a63362c64a644_62764462e63164862c", $Language->MenuPhrase("44", "MenuText"), "logoutSecure.php", -1, "", IsLoggedIn(), FALSE, TRUE, "");
}else{
$RootMenu->AddMenuItem(44, "mci_62a63362c64a644_62764462e63164862c", $Language->MenuPhrase("44", "MenuText"), "logout.php", -1, "", IsLoggedIn(), FALSE, TRUE, "");
	
}


echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
