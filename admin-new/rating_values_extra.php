

<?php if(isset($_REQUEST['event_id'])){ ?>
<script type="text/javascript">
location = 'rating_valueslist.php'; 
</script>
<? } ?>

<?php if(isset($_REQUEST['user_id'])){ ?>
<script type="text/javascript">
location = 'rating_valueslist.php'; 
</script>
<? } ?>





<?

include_once('connect.php'); 


isset($_REQUEST['event_id']) ? $_SESSION['event_id'] = $_REQUEST['event_id'] : $_SESSION['event_id'] = $_SESSION['event_id']  ;
isset($_REQUEST['user_id']) ? $_SESSION['user_id'] = $_REQUEST['user_id'] : $_SESSION['user_id'] = $_SESSION['user_id']  ;






$distinctQuery = $db->query("SELECT DISTINCT rating_values.event_id, activities.activity_name_ar FROM rating_values,activities where rating_values.event_id = activities.activity_id ");
?>
<select  onchange="location='rating_valueslist.php?event_id='+this.value" class="form-control" style="
    font-size: 12px;
    margin-bottom: 20px;
    font-weight: 700;
" name="event_id">
	<option value="" > فلتر بحسب الفعالية</option>
	<? 
while ($res = $db->fetch_assoc($distinctQuery)) {
	?>
	<option <? if($_SESSION['event_id'] == $res['event_id']){ ?> selected="selected" <? } ?> value="<? echo $res['event_id']; ?>" > <? echo $res['activity_name_ar'] ?> </option>
	<?
}
?>
</select>

<?
$distinctQuery = $db->query("SELECT DISTINCT rating_values.user_id, users.full_name_ar FROM rating_values,users where rating_values.user_id = users.user_id ");
?>
<select  onchange="location='rating_valueslist.php?user_id='+this.value" class="form-control" style="
    font-size: 12px;
    margin-bottom: 20px;
    font-weight: 700;
" name="user_id">
	<option value="" > فلتر بحسب المستخدم</option>
	<? 
while ($res = $db->fetch_assoc($distinctQuery)) {
	?>
	<option <? if($_SESSION['user_id'] == $res['user_id']){ ?> selected="selected" <? } ?> value="<? echo $res['user_id']; ?>" > <? echo $res['full_name_ar'] ?> </option>
	<?
}
?>

</select>

<?
if(isset($_SESSION['event_id']) && $_SESSION['event_id'] > 0  &&  isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0  ){ 

include_once('user_rating_results.php');

}
?>