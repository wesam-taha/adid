<?php

// user_id
// full_name_ar
// full_name_en
// email
// admin_approval
// admin_comment
// security_approval
// security_comment

?>
<?php if ($users->Visible) { ?>
<div class="ewMasterDiv">
<table id="tbl_usersmaster" class="table ewViewTable ewMasterTable ewVertical">
	<tbody>
<?php if ($users->user_id->Visible) { // user_id ?>
		<tr id="r_user_id">
			<td class="col-sm-2"><?php echo $users->user_id->FldCaption() ?></td>
			<td<?php echo $users->user_id->CellAttributes() ?>>
<span id="el_users_user_id">
<span<?php echo $users->user_id->ViewAttributes() ?>>
<?php echo $users->user_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
		<tr id="r_full_name_ar">
			<td class="col-sm-2"><?php echo $users->full_name_ar->FldCaption() ?></td>
			<td<?php echo $users->full_name_ar->CellAttributes() ?>>
<span id="el_users_full_name_ar">
<span<?php echo $users->full_name_ar->ViewAttributes() ?>>
<?php echo $users->full_name_ar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
		<tr id="r_full_name_en">
			<td class="col-sm-2"><?php echo $users->full_name_en->FldCaption() ?></td>
			<td<?php echo $users->full_name_en->CellAttributes() ?>>
<span id="el_users_full_name_en">
<span<?php echo $users->full_name_en->ViewAttributes() ?>>
<?php echo $users->full_name_en->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
		<tr id="r__email">
			<td class="col-sm-2"><?php echo $users->_email->FldCaption() ?></td>
			<td<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email">
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
		<tr id="r_admin_approval">
			<td class="col-sm-2"><?php echo $users->admin_approval->FldCaption() ?></td>
			<td<?php echo $users->admin_approval->CellAttributes() ?>>
<span id="el_users_admin_approval">
<span<?php echo $users->admin_approval->ViewAttributes() ?>>
<?php echo $users->admin_approval->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
		<tr id="r_admin_comment">
			<td class="col-sm-2"><?php echo $users->admin_comment->FldCaption() ?></td>
			<td<?php echo $users->admin_comment->CellAttributes() ?>>
<span id="el_users_admin_comment">
<span<?php echo $users->admin_comment->ViewAttributes() ?>>
<?php echo $users->admin_comment->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->security_approval->Visible) { // security_approval ?>
		<tr id="r_security_approval">
			<td class="col-sm-2"><?php echo $users->security_approval->FldCaption() ?></td>
			<td<?php echo $users->security_approval->CellAttributes() ?>>
<span id="el_users_security_approval">
<span<?php echo $users->security_approval->ViewAttributes() ?>>
<?php echo $users->security_approval->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
		<tr id="r_security_comment">
			<td class="col-sm-2"><?php echo $users->security_comment->FldCaption() ?></td>
			<td<?php echo $users->security_comment->CellAttributes() ?>>
<span id="el_users_security_comment">
<span<?php echo $users->security_comment->ViewAttributes() ?>>
<?php echo $users->security_comment->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</div>
<?php } ?>
