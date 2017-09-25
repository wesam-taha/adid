<?php

// activity_id
// activity_name_ar
// activity_start_date
// activity_end_date
// activity_city
// activity_active

?>
<?php if ($activities->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_activitiesmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($activities->activity_id->Visible) { // activity_id ?>
		<tr id="r_activity_id">
			<td class="col-sm-2"><?php echo $activities->activity_id->FldCaption() ?></td>
			<td<?php echo $activities->activity_id->CellAttributes() ?>>
<span id="el_activities_activity_id">
<span<?php echo $activities->activity_id->ViewAttributes() ?>>
<?php echo $activities->activity_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($activities->activity_name_ar->Visible) { // activity_name_ar ?>
		<tr id="r_activity_name_ar">
			<td class="col-sm-2"><?php echo $activities->activity_name_ar->FldCaption() ?></td>
			<td<?php echo $activities->activity_name_ar->CellAttributes() ?>>
<span id="el_activities_activity_name_ar">
<span<?php echo $activities->activity_name_ar->ViewAttributes() ?>>
<?php echo $activities->activity_name_ar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($activities->activity_start_date->Visible) { // activity_start_date ?>
		<tr id="r_activity_start_date">
			<td class="col-sm-2"><?php echo $activities->activity_start_date->FldCaption() ?></td>
			<td<?php echo $activities->activity_start_date->CellAttributes() ?>>
<span id="el_activities_activity_start_date">
<span<?php echo $activities->activity_start_date->ViewAttributes() ?>>
<?php echo $activities->activity_start_date->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($activities->activity_end_date->Visible) { // activity_end_date ?>
		<tr id="r_activity_end_date">
			<td class="col-sm-2"><?php echo $activities->activity_end_date->FldCaption() ?></td>
			<td<?php echo $activities->activity_end_date->CellAttributes() ?>>
<span id="el_activities_activity_end_date">
<span<?php echo $activities->activity_end_date->ViewAttributes() ?>>
<?php echo $activities->activity_end_date->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($activities->activity_city->Visible) { // activity_city ?>
		<tr id="r_activity_city">
			<td class="col-sm-2"><?php echo $activities->activity_city->FldCaption() ?></td>
			<td<?php echo $activities->activity_city->CellAttributes() ?>>
<span id="el_activities_activity_city">
<span<?php echo $activities->activity_city->ViewAttributes() ?>>
<?php echo $activities->activity_city->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($activities->activity_active->Visible) { // activity_active ?>
		<tr id="r_activity_active">
			<td class="col-sm-2"><?php echo $activities->activity_active->FldCaption() ?></td>
			<td<?php echo $activities->activity_active->CellAttributes() ?>>
<span id="el_activities_activity_active">
<span<?php echo $activities->activity_active->ViewAttributes() ?>>
<?php echo $activities->activity_active->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
