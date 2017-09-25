<? include_once('connect.php'); 

$userLevel = $_SESSION['adid_status_UserLevel'];

$form = '';
$form2 = '';

$table = '';


if(isset($_POST['assignToUser'])) {


$employee = @$_POST['assignToUser']; 

$array = implode(",",$_POST['selecteduserid']);
$array = explode(",",$array);

print_r($array);

 for($i=0; $i < count($array); $i++){
  $query = $db->query("Update `registered_users` SET security_owner='$employee' where id='".$array[$i]."'  ");

 }

?>
<script type="text/javascript"> location = 'registered_userslist.php'; </script>
<?

}


if(isset($_POST['approveOrReject'])){

$status = @$_POST['approveOrReject']; 

$array = implode(",",$_POST['selecteduserid']);
$array = explode(",",$array);

 for($i=0; $i < count($array); $i++){

  $query = $db->query("Update `registered_users` SET security_approval='".$status."', approved_by='".$_SESSION['adid_status_UserProfile_UserName']."' where id='".$array[$i]."'  ");

 }

?>
<script type="text/javascript"> location = 'registered_userslist.php'; </script>
<?

}




if(isset($_POST['rejectSelected'])){



$array = implode(",",$_POST['selecteduserid']);
$array = explode(",",$array);

 for($i=0; $i < count($array); $i++){

  $query = $db->query("Update `registered_users` SET security_approval='0', approved_by='".$_SESSION['adid_status_UserProfile_UserName']."' where id='".$array[$i]."'  ");

 }

?>
<script type="text/javascript"> location = 'registered_userslist.php'; </script>
<?

}





$groupURL = "";

$fk_activity_id=  '';
 
 isset($_REQUEST['showType']) ? $_SESSION['showType'] = $_REQUEST['showType'] : $_SESSION['showType'] = 'transferred_to_secure'  ;
 isset($_SESSION['showType']) ? $showType = $_SESSION['showType'] : $showType ='transferred_to_secure'  ;

 isset($_REQUEST['fk_activity_id']) ? @$_SESSION['fk_activity_id'] = @$_REQUEST['fk_activity_id'] : @$_SESSION['fk_activity_id'] = @$_SESSION['fk_activity_id']  ;
 isset($_SESSION['fk_activity_id']) ? $fk_activity_id = $_SESSION['fk_activity_id'] : $fk_activity_id =''  ;


 isset($_REQUEST['id']) ? $id = $_REQUEST['id'] : $id ='';


if($fk_activity_id){ $activitiesWhere = " and activity_id='".$fk_activity_id."' "; }else{ $activitiesWhere = ""; } 



if(@$_SESSION['adid_UserLevel'] == '1' || @$_SESSION['adid_UserLevel'] == '2' ){ 

if($userLevel == '-2'){
	if($_SESSION['adid_UserLevel'] == '1' ){
		$SecureWhere = " and (security_owner IS NULL or security_owner ='0') ";
	}else{
		$SecureWhere = " and security_owner='".$_SESSION['adid_UserID']."'";
	}
}else{
	$SecureWhere = "";
}

}else{
	$SecureWhere = "";
}





$queryAll = $db->query("SELECT * FROM registered_users where 1 = 1   $SecureWhere $activitiesWhere");
$allCount = $db->returned_rows;

$queryNew = $db->query("SELECT * FROM registered_users where  and  security_approval = '0'   $SecureWhere $activitiesWhere order by id desc");
$newCount = $db->returned_rows;


$queryRejected_by_wataniCount = $db->query("SELECT * FROM registered_users where admin_approval = '0'  $SecureWhere $activitiesWhere order by id desc");
$rejected_by_wataniCount = $db->returned_rows;


$query_transferred_to_secure = $db->query("SELECT * FROM registered_users where  security_approval = '0' $SecureWhere $activitiesWhere order by id desc");
$transferred_to_secureCount = $db->returned_rows;

$query_rejected_by_security = $db->query("SELECT * FROM registered_users where   security_approval = '2'  $SecureWhere $activitiesWhere order by id desc");
$rejected_by_securityCount = $db->returned_rows;


$query_approved_by_security = $db->query("SELECT * FROM registered_users where   security_approval = '1'  $SecureWhere $activitiesWhere order by id desc");
$approved_by_securityCount = $db->returned_rows;
$results = '';

function getFld($table, $fld, $whereFld ,$value){ 
global $db;
$dbQyery = $db->query("Select ".$fld." from ".$table." where ".$whereFld." = '".$value."' ");
$fetch = $db->fetch_assoc($dbQyery);
return $fetch[$fld];

}

function checkAproval($value){
if ($value == 0 || $value == null ){ return "بالإنتظار"; }
if ($value == 1  ){ return "موافقة"; }
}







switch($_SESSION['showType']){ 
			case 'all':	

			while($res = $db->fetch_assoc($queryAll)){  
				$results.= '<tr>
							<td><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value="'.$res['id'].'"></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("users", "full_name_ar", "user_id" ,$res['user_id']).'</span></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("activities", "activity_name_ar", "activity_id" ,$res['activity_id']).'</span></td>
							<td> <span class="ewTableHeaderCaption"> '.checkAproval($res['security_approval']).' </span></td>
							</tr>';
			}
			break;

			case 'new':	
			while($res = $db->fetch_assoc($queryNew)){  
				$results.= '<tr>
							<td><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value="'.$res['id'].'"></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("users", "full_name_ar", "user_id" ,$res['user_id']).'</span></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("activities", "activity_name_ar", "activity_id" ,$res['activity_id']).'</span></td>
							<td> <span class="ewTableHeaderCaption"> '.checkAproval($res['security_approval']).' </span></td>
							</tr>';
			}
			break;

			case 'rejected_by_watani':	
			while($res = $db->fetch_assoc($queryRejected_by_wataniCount)){  
				$results.= '<tr>
							<td><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value="'.$res['id'].'"></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("users", "full_name_ar", "user_id" ,$res['user_id']).'</span></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("activities", "activity_name_ar", "activity_id" ,$res['activity_id']).'</span></td>
							<td> <span class="ewTableHeaderCaption"> '.checkAproval($res['security_approval']).' </span></td>
							</tr>';
			}
			break;

			case 'transferred_to_secure':	
			while($res = $db->fetch_assoc($query_transferred_to_secure)){  
				$results.= '<tr>
							<td><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value="'.$res['id'].'"></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("users", "full_name_ar", "user_id" ,$res['user_id']).'</span></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("activities", "activity_name_ar", "activity_id" ,$res['activity_id']).'</span></td>
							<td> <span class="ewTableHeaderCaption"> '.checkAproval($res['security_approval']).' </span></td>
							</tr>';
			}
			break;			

			case 'rejected_by_security':	
			while($res = $db->fetch_assoc($query_rejected_by_security)){  
				$results.= '<tr>
							<td><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value="'.$res['id'].'"></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("users", "full_name_ar", "user_id" ,$res['user_id']).'</span></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("activities", "activity_name_ar", "activity_id" ,$res['activity_id']).'</span></td>
							<td> <span class="ewTableHeaderCaption"> '.checkAproval($res['security_approval']).' </span></td>
							</tr>';
			}
			break;

			case 'approved_by_security':	
			while($res = $db->fetch_assoc($query_approved_by_security)){  
				$results.= '<tr>
							<td><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value="'.$res['id'].'"></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("users", "full_name_ar", "user_id" ,$res['user_id']).'</span></td>
							<td><span class="ewTableHeaderCaption"> '.getFld("activities", "activity_name_ar", "activity_id" ,$res['activity_id']).'</span></td>
							<td> <span class="ewTableHeaderCaption"> '.checkAproval($res['security_approval']).' </span></td>
							</tr>';
			}
			break;
			}







 if($_SESSION['adid_status_UserLevel'] == "-1" || $_SESSION['adid_status_UserLevel'] == "3" ){ ?>
فرز النتائج المعروضة <br> <br> 

 <? } 


$ActivitiesOptions = '';

$distinctQuery = $db->query("SELECT DISTINCT registered_users.activity_id, activities.activity_name_ar FROM registered_users,activities where registered_users.activity_id = activities.activity_id ");


?>
<select  onchange="location='?fk_activity_id='+this.value" class="form-control" style="
    font-size: 12px;
    margin-bottom: 20px;
    font-weight: 700;
" name="assignToUser">
	<option value="" > فلتر بحسب الفعالية</option>
	<? 
while ($res = $db->fetch_assoc($distinctQuery)) {
	?>
	<option <? if($fk_activity_id == $res['activity_id']){ ?> selected="selected" <? } ?> value="<? echo $res['activity_id']; ?>" > <? echo $res['activity_name_ar'] ?> </option>
	<?
}
?>
</select><br> <br>







فرز النتائج المعروضة <br> <br> 
<select onchange="location='?showType='+this.value" style="margin: 20px auto; width: 350px; padding: 6px; margin-top: 0px; background-color: #ebf7fd; font-weight: 700;  " name="showType">
	<option <? if($showType == 'transferred_to_secure'){ ?> selected="selected" <? } ?> value="transferred_to_secure" > بانتظار الموافقة (<? echo $transferred_to_secureCount; ?>)</option>
	<option <? if($showType == 'rejected_by_security'){ ?> selected="selected" <? } ?> value="rejected_by_security" > الطلبات التي تم رفضها  (<? echo $rejected_by_securityCount; ?>)</option>
	<option <? if($showType == 'approved_by_security'){ ?> selected="selected" <? } ?> value="approved_by_security" > الطلبات التي تمت الموافقة عليها  (<? echo $approved_by_securityCount; ?>)</option>
</select>

<? 	if(@$_SESSION['adid_UserLevel'] == '1' ){ ?>

<br><br>

<? 
$options = '';
$query = $db->query("SELECT * FROM secure_management where type = '2' ");
	   while($res = $db->fetch_assoc($query)){  	
	$options.= '<option  value="'.$res['id'].'" >'.$res['username'].'</option>';
?>



	
<? } ?>

<? $form = '
<select   class="form-control" style="
    font-size: 12px;
    margin-bottom: 20px;
    font-weight: 700;
" name="assignToUser">
	<option value="" > اختر الموظف </option>
	'.$options.'
</select>
<input type="submit" style="  margin-bottom: 20px;" name="assign" value="حفظ وتعيين " class="btn btn-success">



';



 } ?>
		






<?
if(@$_SESSION['adid_UserLevel'] == '1' || @$_SESSION['adid_UserLevel'] == '2' ){ 
$form2 = '
<select style="
    font-size: 12px;
    margin-bottom: 20px;
    font-weight: 700;
    margin-right:40px;

" class="form-control" name="approveOrReject">
	<option value="" > اختر الأمر </option>
	<option value="1" > موافقة  </option>
	<option value="2" > رفض </option>
</select>

<input type="submit" style="  margin-bottom: 20px;" class="btn btn-success" name="applySelected" value="تنفيذ" >

';
}

$table = '
<table class="table ewTable">
<tr>
<th><input style="margin-left:20px;" type="checkbox" name="selecteduserid[]" value=""></th>
<th><span class="ewTableHeaderCaption"> المستخدم</span></th>
<th> <span class="ewTableHeaderCaption"> الفعالية </span> </th>
<th> <span class="ewTableHeaderCaption"> الموافقة الإدارية </span></th>
</tr>
'.$results.'
</table></form>';

?>






<script type="text/javascript">

setTimeout(function(){



var total = '<? echo $allCount; ?>';



for(var c=1; c <= total ;c++){

var item = "#el"+c+"_institutions_admin_approval span";
var itemParent = "#el"+c+"_institutions_admin_approval ";
var item2 = "#el"+c+"_institutions_admin_comment span";
var item2Parent = "#el"+c+"_institutions_admin_comment ";


var Sitem = "#el"+c+"_institutions_security_approval span";
var SitemParent = "#el"+c+"_institutions_security_approval ";
var Sitem2 = "#el"+c+"_institutions_security_comment span";
var Sitem2Parent = "#el"+c+"_institutions_security_comment ";


<?php if($_SESSION['adid_status_UserLevel'] != "-1" && $_SESSION['adid_status_UserLevel'] != "-2" ){ ?>


$("td[data-name='security_approval']").hide();
$("th[data-name='security_approval']").hide();

$("td[data-name='security_comment']").hide();
$("th[data-name='security_comment']").hide();

$("a[href='#tab_users7']").hide();


<?php } ?>

<?php if(  $_SESSION['adid_status_UserLevel'] == "-2" ){ ?>
$("#tbl_registered_userslist").hide();

<?php } ?>


if($(item).html().indexOf("موافقة") > -1 ) { $(itemParent).parent().addClass('ok'); $(item2Parent).parent().addClass('ok'); };
if($(item).html().indexOf("رفض") > -1  ) { $(itemParent).parent().addClass('reject'); $(item2Parent).parent().addClass('reject'); };
if($(item).html().indexOf("بالإنتظار") > -1  ) { $(itemParent).parent().addClass('wait'); $(item2Parent).parent().addClass('wait'); };


if($(Sitem).html().indexOf("موافقة") > -1 ) { $(SitemParent).parent().addClass('ok'); $(Sitem2Parent).parent().addClass('ok'); };
if($(Sitem).html().indexOf("رفض") > -1  ) { $(SitemParent).parent().addClass('reject'); $(Sitem2Parent).parent().addClass('reject'); };
if($(Sitem).html().indexOf("بالإنتظار") > -1  ) { $(SitemParent).parent().addClass('wait'); $(Sitem2Parent).parent().addClass('wait'); };


}



 }, 500);
</script>

<style type="text/css">
.wait { background-color: #f9f9f9 !important; }	
.ok { background-color: #d3f3c5 !important;; }	
.reject { background-color: #f3c5c5 !important; }	
#tbl_registered_userslist { display: none; }
.ewMultiPage { margin-top: 0px !important; }
th, td { text-align: right !important;  }
.ewListOtherOptions { display: none }
form[name='fusersedit']  .ewMultiPage{ margin-top: -100px !important; }
form[name='fusersadd']  .ewMultiPage{ margin-top: -100px !important; }

</style>

