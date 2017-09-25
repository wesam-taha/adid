<?php include_once "managementinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($registered_users_grid)) $registered_users_grid = new cregistered_users_grid();

// Page init
$registered_users_grid->Page_Init();

// Page main
$registered_users_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$registered_users_grid->Page_Render();
?>
<?php if ($registered_users->Export == "") { ?>
<script type="text/javascript">

// Form object
var fregistered_usersgrid = new ew_Form("fregistered_usersgrid", "grid");
fregistered_usersgrid.FormKeyCountName = '<?php echo $registered_users_grid->FormKeyCountName ?>';

// Validate form
fregistered_usersgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_evaluation_rate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($registered_users->evaluation_rate->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fregistered_usersgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "activity_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "user_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "admin_approval", false)) return false;
	if (ew_ValueChanged(fobj, infix, "admin_comment", false)) return false;
	if (ew_ValueChanged(fobj, infix, "evaluation_rate", false)) return false;
	return true;
}

// Form_CustomValidate event
fregistered_usersgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fregistered_usersgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fregistered_usersgrid.Lists["x_activity_id"] = {"LinkField":"x_activity_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_activity_name_ar","x_activity_start_date","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"activities"};
fregistered_usersgrid.Lists["x_activity_id"].Data = "<?php echo $registered_users_grid->activity_id->LookupFilterQuery(FALSE, "grid") ?>";
fregistered_usersgrid.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fregistered_usersgrid.Lists["x_admin_approval"].Options = <?php echo json_encode($registered_users_grid->admin_approval->Options()) ?>;

// Form object for search
</script>
<?php } ?>
<?php
if ($registered_users->CurrentAction == "gridadd") {
	if ($registered_users->CurrentMode == "copy") {
		$bSelectLimit = $registered_users_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$registered_users_grid->TotalRecs = $registered_users->ListRecordCount();
			$registered_users_grid->Recordset = $registered_users_grid->LoadRecordset($registered_users_grid->StartRec-1, $registered_users_grid->DisplayRecs);
		} else {
			if ($registered_users_grid->Recordset = $registered_users_grid->LoadRecordset())
				$registered_users_grid->TotalRecs = $registered_users_grid->Recordset->RecordCount();
		}
		$registered_users_grid->StartRec = 1;
		$registered_users_grid->DisplayRecs = $registered_users_grid->TotalRecs;
	} else {
		$registered_users->CurrentFilter = "0=1";
		$registered_users_grid->StartRec = 1;
		$registered_users_grid->DisplayRecs = $registered_users->GridAddRowCount;
	}
	$registered_users_grid->TotalRecs = $registered_users_grid->DisplayRecs;
	$registered_users_grid->StopRec = $registered_users_grid->DisplayRecs;
} else {
	$bSelectLimit = $registered_users_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($registered_users_grid->TotalRecs <= 0)
			$registered_users_grid->TotalRecs = $registered_users->ListRecordCount();
	} else {
		if (!$registered_users_grid->Recordset && ($registered_users_grid->Recordset = $registered_users_grid->LoadRecordset()))
			$registered_users_grid->TotalRecs = $registered_users_grid->Recordset->RecordCount();
	}
	$registered_users_grid->StartRec = 1;
	$registered_users_grid->DisplayRecs = $registered_users_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$registered_users_grid->Recordset = $registered_users_grid->LoadRecordset($registered_users_grid->StartRec-1, $registered_users_grid->DisplayRecs);

	// Set no record found message
	if ($registered_users->CurrentAction == "" && $registered_users_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$registered_users_grid->setWarningMessage(ew_DeniedMsg());
		if ($registered_users_grid->SearchWhere == "0=101")
			$registered_users_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$registered_users_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$registered_users_grid->RenderOtherOptions();
?>
<?php $registered_users_grid->ShowPageHeader(); ?>
<?php
$registered_users_grid->ShowMessage();
?>
<?php if ($registered_users_grid->TotalRecs > 0 || $registered_users->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($registered_users_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> registered_users">
<div id="fregistered_usersgrid" class="ewForm ewListForm form-inline">
<?php if ($registered_users_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($registered_users_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_registered_users" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_registered_usersgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$registered_users_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$registered_users_grid->RenderListOptions();

// Render list options (header, left)
$registered_users_grid->ListOptions->Render("header", "left");
?>
<?php if ($registered_users->id->Visible) { // id ?>
	<?php if ($registered_users->SortUrl($registered_users->id) == "") { ?>
		<th data-name="id" class="<?php echo $registered_users->id->HeaderCellClass() ?>"><div id="elh_registered_users_id" class="registered_users_id"><div class="ewTableHeaderCaption"><?php echo $registered_users->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $registered_users->id->HeaderCellClass() ?>"><div><div id="elh_registered_users_id" class="registered_users_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $registered_users->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($registered_users->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($registered_users->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($registered_users->activity_id->Visible) { // activity_id ?>
	<?php if ($registered_users->SortUrl($registered_users->activity_id) == "") { ?>
		<th data-name="activity_id" class="<?php echo $registered_users->activity_id->HeaderCellClass() ?>"><div id="elh_registered_users_activity_id" class="registered_users_activity_id"><div class="ewTableHeaderCaption"><?php echo $registered_users->activity_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_id" class="<?php echo $registered_users->activity_id->HeaderCellClass() ?>"><div><div id="elh_registered_users_activity_id" class="registered_users_activity_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $registered_users->activity_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($registered_users->activity_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($registered_users->activity_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($registered_users->user_id->Visible) { // user_id ?>
	<?php if ($registered_users->SortUrl($registered_users->user_id) == "") { ?>
		<th data-name="user_id" class="<?php echo $registered_users->user_id->HeaderCellClass() ?>"><div id="elh_registered_users_user_id" class="registered_users_user_id"><div class="ewTableHeaderCaption"><?php echo $registered_users->user_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_id" class="<?php echo $registered_users->user_id->HeaderCellClass() ?>"><div><div id="elh_registered_users_user_id" class="registered_users_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $registered_users->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($registered_users->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($registered_users->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($registered_users->admin_approval->Visible) { // admin_approval ?>
	<?php if ($registered_users->SortUrl($registered_users->admin_approval) == "") { ?>
		<th data-name="admin_approval" class="<?php echo $registered_users->admin_approval->HeaderCellClass() ?>"><div id="elh_registered_users_admin_approval" class="registered_users_admin_approval"><div class="ewTableHeaderCaption"><?php echo $registered_users->admin_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_approval" class="<?php echo $registered_users->admin_approval->HeaderCellClass() ?>"><div><div id="elh_registered_users_admin_approval" class="registered_users_admin_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $registered_users->admin_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($registered_users->admin_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($registered_users->admin_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($registered_users->admin_comment->Visible) { // admin_comment ?>
	<?php if ($registered_users->SortUrl($registered_users->admin_comment) == "") { ?>
		<th data-name="admin_comment" class="<?php echo $registered_users->admin_comment->HeaderCellClass() ?>"><div id="elh_registered_users_admin_comment" class="registered_users_admin_comment"><div class="ewTableHeaderCaption"><?php echo $registered_users->admin_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_comment" class="<?php echo $registered_users->admin_comment->HeaderCellClass() ?>"><div><div id="elh_registered_users_admin_comment" class="registered_users_admin_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $registered_users->admin_comment->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($registered_users->admin_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($registered_users->admin_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($registered_users->evaluation_rate->Visible) { // evaluation_rate ?>
	<?php if ($registered_users->SortUrl($registered_users->evaluation_rate) == "") { ?>
		<th data-name="evaluation_rate" class="<?php echo $registered_users->evaluation_rate->HeaderCellClass() ?>"><div id="elh_registered_users_evaluation_rate" class="registered_users_evaluation_rate"><div class="ewTableHeaderCaption"><?php echo $registered_users->evaluation_rate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="evaluation_rate" class="<?php echo $registered_users->evaluation_rate->HeaderCellClass() ?>"><div><div id="elh_registered_users_evaluation_rate" class="registered_users_evaluation_rate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $registered_users->evaluation_rate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($registered_users->evaluation_rate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($registered_users->evaluation_rate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$registered_users_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$registered_users_grid->StartRec = 1;
$registered_users_grid->StopRec = $registered_users_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($registered_users_grid->FormKeyCountName) && ($registered_users->CurrentAction == "gridadd" || $registered_users->CurrentAction == "gridedit" || $registered_users->CurrentAction == "F")) {
		$registered_users_grid->KeyCount = $objForm->GetValue($registered_users_grid->FormKeyCountName);
		$registered_users_grid->StopRec = $registered_users_grid->StartRec + $registered_users_grid->KeyCount - 1;
	}
}
$registered_users_grid->RecCnt = $registered_users_grid->StartRec - 1;
if ($registered_users_grid->Recordset && !$registered_users_grid->Recordset->EOF) {
	$registered_users_grid->Recordset->MoveFirst();
	$bSelectLimit = $registered_users_grid->UseSelectLimit;
	if (!$bSelectLimit && $registered_users_grid->StartRec > 1)
		$registered_users_grid->Recordset->Move($registered_users_grid->StartRec - 1);
} elseif (!$registered_users->AllowAddDeleteRow && $registered_users_grid->StopRec == 0) {
	$registered_users_grid->StopRec = $registered_users->GridAddRowCount;
}

// Initialize aggregate
$registered_users->RowType = EW_ROWTYPE_AGGREGATEINIT;
$registered_users->ResetAttrs();
$registered_users_grid->RenderRow();
if ($registered_users->CurrentAction == "gridadd")
	$registered_users_grid->RowIndex = 0;
if ($registered_users->CurrentAction == "gridedit")
	$registered_users_grid->RowIndex = 0;
while ($registered_users_grid->RecCnt < $registered_users_grid->StopRec) {
	$registered_users_grid->RecCnt++;
	if (intval($registered_users_grid->RecCnt) >= intval($registered_users_grid->StartRec)) {
		$registered_users_grid->RowCnt++;
		if ($registered_users->CurrentAction == "gridadd" || $registered_users->CurrentAction == "gridedit" || $registered_users->CurrentAction == "F") {
			$registered_users_grid->RowIndex++;
			$objForm->Index = $registered_users_grid->RowIndex;
			if ($objForm->HasValue($registered_users_grid->FormActionName))
				$registered_users_grid->RowAction = strval($objForm->GetValue($registered_users_grid->FormActionName));
			elseif ($registered_users->CurrentAction == "gridadd")
				$registered_users_grid->RowAction = "insert";
			else
				$registered_users_grid->RowAction = "";
		}

		// Set up key count
		$registered_users_grid->KeyCount = $registered_users_grid->RowIndex;

		// Init row class and style
		$registered_users->ResetAttrs();
		$registered_users->CssClass = "";
		if ($registered_users->CurrentAction == "gridadd") {
			if ($registered_users->CurrentMode == "copy") {
				$registered_users_grid->LoadRowValues($registered_users_grid->Recordset); // Load row values
				$registered_users_grid->SetRecordKey($registered_users_grid->RowOldKey, $registered_users_grid->Recordset); // Set old record key
			} else {
				$registered_users_grid->LoadDefaultValues(); // Load default values
				$registered_users_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$registered_users_grid->LoadRowValues($registered_users_grid->Recordset); // Load row values
		}
		$registered_users->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($registered_users->CurrentAction == "gridadd") // Grid add
			$registered_users->RowType = EW_ROWTYPE_ADD; // Render add
		if ($registered_users->CurrentAction == "gridadd" && $registered_users->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$registered_users_grid->RestoreCurrentRowFormValues($registered_users_grid->RowIndex); // Restore form values
		if ($registered_users->CurrentAction == "gridedit") { // Grid edit
			if ($registered_users->EventCancelled) {
				$registered_users_grid->RestoreCurrentRowFormValues($registered_users_grid->RowIndex); // Restore form values
			}
			if ($registered_users_grid->RowAction == "insert")
				$registered_users->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$registered_users->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($registered_users->CurrentAction == "gridedit" && ($registered_users->RowType == EW_ROWTYPE_EDIT || $registered_users->RowType == EW_ROWTYPE_ADD) && $registered_users->EventCancelled) // Update failed
			$registered_users_grid->RestoreCurrentRowFormValues($registered_users_grid->RowIndex); // Restore form values
		if ($registered_users->RowType == EW_ROWTYPE_EDIT) // Edit row
			$registered_users_grid->EditRowCnt++;
		if ($registered_users->CurrentAction == "F") // Confirm row
			$registered_users_grid->RestoreCurrentRowFormValues($registered_users_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$registered_users->RowAttrs = array_merge($registered_users->RowAttrs, array('data-rowindex'=>$registered_users_grid->RowCnt, 'id'=>'r' . $registered_users_grid->RowCnt . '_registered_users', 'data-rowtype'=>$registered_users->RowType));

		// Render row
		$registered_users_grid->RenderRow();

		// Render list options
		$registered_users_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($registered_users_grid->RowAction <> "delete" && $registered_users_grid->RowAction <> "insertdelete" && !($registered_users_grid->RowAction == "insert" && $registered_users->CurrentAction == "F" && $registered_users_grid->EmptyRow())) {
?>
	<tr<?php echo $registered_users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$registered_users_grid->ListOptions->Render("body", "left", $registered_users_grid->RowCnt);
?>
	<?php if ($registered_users->id->Visible) { // id ?>
		<td data-name="id"<?php echo $registered_users->id->CellAttributes() ?>>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="registered_users" data-field="x_id" name="o<?php echo $registered_users_grid->RowIndex ?>_id" id="o<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->OldValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_id" class="form-group registered_users_id">
<span<?php echo $registered_users->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_id" name="x<?php echo $registered_users_grid->RowIndex ?>_id" id="x<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->CurrentValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_id" class="registered_users_id">
<span<?php echo $registered_users->id->ViewAttributes() ?>>
<?php echo $registered_users->id->ListViewValue() ?></span>
</span>
<?php if ($registered_users->CurrentAction <> "F") { ?>
<input type="hidden" data-table="registered_users" data-field="x_id" name="x<?php echo $registered_users_grid->RowIndex ?>_id" id="x<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_id" name="o<?php echo $registered_users_grid->RowIndex ?>_id" id="o<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="registered_users" data-field="x_id" name="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_id" id="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_id" name="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_id" id="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($registered_users->activity_id->Visible) { // activity_id ?>
		<td data-name="activity_id"<?php echo $registered_users->activity_id->CellAttributes() ?>>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($registered_users->activity_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_activity_id" class="form-group registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->activity_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_activity_id" class="form-group registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $registered_users_grid->RowIndex ?>_activity_id"><?php echo (strval($registered_users->activity_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $registered_users_grid->RowIndex ?>_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo $registered_users->activity_id->CurrentValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="o<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="o<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->OldValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($registered_users->activity_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_activity_id" class="form-group registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->activity_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_activity_id" class="form-group registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $registered_users_grid->RowIndex ?>_activity_id"><?php echo (strval($registered_users->activity_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $registered_users_grid->RowIndex ?>_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo $registered_users->activity_id->CurrentValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_activity_id" class="registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<?php echo $registered_users->activity_id->ListViewValue() ?></span>
</span>
<?php if ($registered_users->CurrentAction <> "F") { ?>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="o<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="o<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($registered_users->user_id->Visible) { // user_id ?>
		<td data-name="user_id"<?php echo $registered_users->user_id->CellAttributes() ?>>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_user_id" class="form-group registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x<?php echo $registered_users_grid->RowIndex ?>_user_id" id="x<?php echo $registered_users_grid->RowIndex ?>_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="o<?php echo $registered_users_grid->RowIndex ?>_user_id" id="o<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->OldValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_user_id" class="form-group registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x<?php echo $registered_users_grid->RowIndex ?>_user_id" id="x<?php echo $registered_users_grid->RowIndex ?>_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_user_id" class="registered_users_user_id">
<span<?php echo $registered_users->user_id->ViewAttributes() ?>>
<?php echo $registered_users->user_id->ListViewValue() ?></span>
</span>
<?php if ($registered_users->CurrentAction <> "F") { ?>
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="x<?php echo $registered_users_grid->RowIndex ?>_user_id" id="x<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="o<?php echo $registered_users_grid->RowIndex ?>_user_id" id="o<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_user_id" id="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_user_id" id="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($registered_users->admin_approval->Visible) { // admin_approval ?>
		<td data-name="admin_approval"<?php echo $registered_users->admin_approval->CellAttributes() ?>>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_admin_approval" class="form-group registered_users_admin_approval">
<div id="tp_x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x{$registered_users_grid->RowIndex}_admin_approval") ?>
</div></div>
</span>
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->OldValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_admin_approval" class="form-group registered_users_admin_approval">
<div id="tp_x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x{$registered_users_grid->RowIndex}_admin_approval") ?>
</div></div>
</span>
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_admin_approval" class="registered_users_admin_approval">
<span<?php echo $registered_users->admin_approval->ViewAttributes() ?>>
<?php echo $registered_users->admin_approval->ListViewValue() ?></span>
</span>
<?php if ($registered_users->CurrentAction <> "F") { ?>
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($registered_users->admin_comment->Visible) { // admin_comment ?>
		<td data-name="admin_comment"<?php echo $registered_users->admin_comment->CellAttributes() ?>>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_admin_comment" class="form-group registered_users_admin_comment">
<textarea data-table="registered_users" data-field="x_admin_comment" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>><?php echo $registered_users->admin_comment->EditValue ?></textarea>
</span>
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->OldValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_admin_comment" class="form-group registered_users_admin_comment">
<textarea data-table="registered_users" data-field="x_admin_comment" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>><?php echo $registered_users->admin_comment->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_admin_comment" class="registered_users_admin_comment">
<span<?php echo $registered_users->admin_comment->ViewAttributes() ?>>
<?php echo $registered_users->admin_comment->ListViewValue() ?></span>
</span>
<?php if ($registered_users->CurrentAction <> "F") { ?>
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($registered_users->evaluation_rate->Visible) { // evaluation_rate ?>
		<td data-name="evaluation_rate"<?php echo $registered_users->evaluation_rate->CellAttributes() ?>>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_evaluation_rate" class="form-group registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->OldValue) ?>">
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_evaluation_rate" class="form-group registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($registered_users->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $registered_users_grid->RowCnt ?>_registered_users_evaluation_rate" class="registered_users_evaluation_rate">
<span<?php echo $registered_users->evaluation_rate->ViewAttributes() ?>>
<?php echo $registered_users->evaluation_rate->ListViewValue() ?></span>
</span>
<?php if ($registered_users->CurrentAction <> "F") { ?>
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="fregistered_usersgrid$x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->FormValue) ?>">
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="fregistered_usersgrid$o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$registered_users_grid->ListOptions->Render("body", "right", $registered_users_grid->RowCnt);
?>
	</tr>
<?php if ($registered_users->RowType == EW_ROWTYPE_ADD || $registered_users->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fregistered_usersgrid.UpdateOpts(<?php echo $registered_users_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($registered_users->CurrentAction <> "gridadd" || $registered_users->CurrentMode == "copy")
		if (!$registered_users_grid->Recordset->EOF) $registered_users_grid->Recordset->MoveNext();
}
?>
<?php
	if ($registered_users->CurrentMode == "add" || $registered_users->CurrentMode == "copy" || $registered_users->CurrentMode == "edit") {
		$registered_users_grid->RowIndex = '$rowindex$';
		$registered_users_grid->LoadDefaultValues();

		// Set row properties
		$registered_users->ResetAttrs();
		$registered_users->RowAttrs = array_merge($registered_users->RowAttrs, array('data-rowindex'=>$registered_users_grid->RowIndex, 'id'=>'r0_registered_users', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($registered_users->RowAttrs["class"], "ewTemplate");
		$registered_users->RowType = EW_ROWTYPE_ADD;

		// Render row
		$registered_users_grid->RenderRow();

		// Render list options
		$registered_users_grid->RenderListOptions();
		$registered_users_grid->StartRowCnt = 0;
?>
	<tr<?php echo $registered_users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$registered_users_grid->ListOptions->Render("body", "left", $registered_users_grid->RowIndex);
?>
	<?php if ($registered_users->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($registered_users->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_registered_users_id" class="form-group registered_users_id">
<span<?php echo $registered_users->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_id" name="x<?php echo $registered_users_grid->RowIndex ?>_id" id="x<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_id" name="o<?php echo $registered_users_grid->RowIndex ?>_id" id="o<?php echo $registered_users_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($registered_users->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($registered_users->activity_id->Visible) { // activity_id ?>
		<td data-name="activity_id">
<?php if ($registered_users->CurrentAction <> "F") { ?>
<?php if ($registered_users->activity_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_registered_users_activity_id" class="form-group registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->activity_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_registered_users_activity_id" class="form-group registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $registered_users_grid->RowIndex ?>_activity_id"><?php echo (strval($registered_users->activity_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $registered_users_grid->RowIndex ?>_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo $registered_users->activity_id->CurrentValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_registered_users_activity_id" class="form-group registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->activity_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="x<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" name="o<?php echo $registered_users_grid->RowIndex ?>_activity_id" id="o<?php echo $registered_users_grid->RowIndex ?>_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($registered_users->user_id->Visible) { // user_id ?>
		<td data-name="user_id">
<?php if ($registered_users->CurrentAction <> "F") { ?>
<span id="el$rowindex$_registered_users_user_id" class="form-group registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x<?php echo $registered_users_grid->RowIndex ?>_user_id" id="x<?php echo $registered_users_grid->RowIndex ?>_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_registered_users_user_id" class="form-group registered_users_user_id">
<span<?php echo $registered_users->user_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->user_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="x<?php echo $registered_users_grid->RowIndex ?>_user_id" id="x<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_user_id" name="o<?php echo $registered_users_grid->RowIndex ?>_user_id" id="o<?php echo $registered_users_grid->RowIndex ?>_user_id" value="<?php echo ew_HtmlEncode($registered_users->user_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($registered_users->admin_approval->Visible) { // admin_approval ?>
		<td data-name="admin_approval">
<?php if ($registered_users->CurrentAction <> "F") { ?>
<span id="el$rowindex$_registered_users_admin_approval" class="form-group registered_users_admin_approval">
<div id="tp_x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x{$registered_users_grid->RowIndex}_admin_approval") ?>
</div></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_registered_users_admin_approval" class="form-group registered_users_admin_approval">
<span<?php echo $registered_users->admin_approval->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->admin_approval->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_admin_approval" name="o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" id="o<?php echo $registered_users_grid->RowIndex ?>_admin_approval" value="<?php echo ew_HtmlEncode($registered_users->admin_approval->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($registered_users->admin_comment->Visible) { // admin_comment ?>
		<td data-name="admin_comment">
<?php if ($registered_users->CurrentAction <> "F") { ?>
<span id="el$rowindex$_registered_users_admin_comment" class="form-group registered_users_admin_comment">
<textarea data-table="registered_users" data-field="x_admin_comment" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>><?php echo $registered_users->admin_comment->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_registered_users_admin_comment" class="form-group registered_users_admin_comment">
<span<?php echo $registered_users->admin_comment->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->admin_comment->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="x<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_admin_comment" name="o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" id="o<?php echo $registered_users_grid->RowIndex ?>_admin_comment" value="<?php echo ew_HtmlEncode($registered_users->admin_comment->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($registered_users->evaluation_rate->Visible) { // evaluation_rate ?>
		<td data-name="evaluation_rate">
<?php if ($registered_users->CurrentAction <> "F") { ?>
<span id="el$rowindex$_registered_users_evaluation_rate" class="form-group registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_registered_users_evaluation_rate" class="form-group registered_users_evaluation_rate">
<span<?php echo $registered_users->evaluation_rate->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->evaluation_rate->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="x<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="registered_users" data-field="x_evaluation_rate" name="o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" id="o<?php echo $registered_users_grid->RowIndex ?>_evaluation_rate" value="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$registered_users_grid->ListOptions->Render("body", "right", $registered_users_grid->RowCnt);
?>
<script type="text/javascript">
fregistered_usersgrid.UpdateOpts(<?php echo $registered_users_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($registered_users->CurrentMode == "add" || $registered_users->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $registered_users_grid->FormKeyCountName ?>" id="<?php echo $registered_users_grid->FormKeyCountName ?>" value="<?php echo $registered_users_grid->KeyCount ?>">
<?php echo $registered_users_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($registered_users->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $registered_users_grid->FormKeyCountName ?>" id="<?php echo $registered_users_grid->FormKeyCountName ?>" value="<?php echo $registered_users_grid->KeyCount ?>">
<?php echo $registered_users_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($registered_users->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fregistered_usersgrid">
</div>
<?php

// Close recordset
if ($registered_users_grid->Recordset)
	$registered_users_grid->Recordset->Close();
?>
<?php if ($registered_users_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($registered_users_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($registered_users_grid->TotalRecs == 0 && $registered_users->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($registered_users_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($registered_users->Export == "") { ?>
<script type="text/javascript">
fregistered_usersgrid.Init();
</script>
<?php } ?>
<?php
$registered_users_grid->Page_Terminate();
?>
