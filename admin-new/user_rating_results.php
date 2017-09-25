<?


$selectQ = $db->query("select * from `registered_users`  where user_id='".$_SESSION['user_id']."' and activity_id='".$_SESSION['event_id']."' ");

$res = $db->fetch_assoc($selectQ);






?>
<div class="box ewBox ewGrid rating_values">

<table class="table ewTable">
	<tr class="ewTableHeader">
		<th style="width: 20%; text-align: right;">الحضور</th>
		<th  style="width: 40%;  text-align: right;" > ملاحظات المشرف </th>
		<th  style="width: 40%;  text-align: right;">نسبة التقييم</th>
	</tr>
	<tr class="ewTableRow">
		<td  style="width: 20%;  text-align: right;"><?   if($res['admin_approval'] == 1 ){ echo "تأكيد الحضور"; }else{ echo "لم يحضر"; } ?></td>
		<td  style="width: 40%;  text-align: right;"> <? if($res['admin_comment']){ echo $res['admin_comment']; }else{ echo "لا يوجد ملاحظات"; } ?> </td>
		<td  style="width: 40%;  text-align: right;"><? echo $res['evaluation_rate']; ?>% </td>
	</tr>
</table>
</div>