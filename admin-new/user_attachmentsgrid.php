<?php include_once "managementinfo.php" ?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($user_attachments_grid)) $user_attachments_grid = new cuser_attachments_grid();

// Page init
$user_attachments_grid->Page_Init();

// Page main
$user_attachments_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_attachments_grid->Page_Render();
?>
<?php if ($user_attachments->Export == "") { ?>
<script type="text/javascript">

// Form object
var fuser_attachmentsgrid = new ew_Form("fuser_attachmentsgrid", "grid");
fuser_attachmentsgrid.FormKeyCountName = '<?php echo $user_attachments_grid->FormKeyCountName ?>';

// Validate form
fuser_attachmentsgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "__userid");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_attachments->_userid->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fuser_attachmentsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "_userid", false)) return false;
	if (ew_ValueChanged(fobj, infix, "description", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hours", false)) return false;
	if (ew_ValueChanged(fobj, infix, "file", false)) return false;
	return true;
}

// Form_CustomValidate event
fuser_attachmentsgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuser_attachmentsgrid.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($user_attachments->CurrentAction == "gridadd") {
	if ($user_attachments->CurrentMode == "copy") {
		$bSelectLimit = $user_attachments_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$user_attachments_grid->TotalRecs = $user_attachments->ListRecordCount();
			$user_attachments_grid->Recordset = $user_attachments_grid->LoadRecordset($user_attachments_grid->StartRec-1, $user_attachments_grid->DisplayRecs);
		} else {
			if ($user_attachments_grid->Recordset = $user_attachments_grid->LoadRecordset())
				$user_attachments_grid->TotalRecs = $user_attachments_grid->Recordset->RecordCount();
		}
		$user_attachments_grid->StartRec = 1;
		$user_attachments_grid->DisplayRecs = $user_attachments_grid->TotalRecs;
	} else {
		$user_attachments->CurrentFilter = "0=1";
		$user_attachments_grid->StartRec = 1;
		$user_attachments_grid->DisplayRecs = $user_attachments->GridAddRowCount;
	}
	$user_attachments_grid->TotalRecs = $user_attachments_grid->DisplayRecs;
	$user_attachments_grid->StopRec = $user_attachments_grid->DisplayRecs;
} else {
	$bSelectLimit = $user_attachments_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($user_attachments_grid->TotalRecs <= 0)
			$user_attachments_grid->TotalRecs = $user_attachments->ListRecordCount();
	} else {
		if (!$user_attachments_grid->Recordset && ($user_attachments_grid->Recordset = $user_attachments_grid->LoadRecordset()))
			$user_attachments_grid->TotalRecs = $user_attachments_grid->Recordset->RecordCount();
	}
	$user_attachments_grid->StartRec = 1;
	$user_attachments_grid->DisplayRecs = $user_attachments_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$user_attachments_grid->Recordset = $user_attachments_grid->LoadRecordset($user_attachments_grid->StartRec-1, $user_attachments_grid->DisplayRecs);

	// Set no record found message
	if ($user_attachments->CurrentAction == "" && $user_attachments_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$user_attachments_grid->setWarningMessage(ew_DeniedMsg());
		if ($user_attachments_grid->SearchWhere == "0=101")
			$user_attachments_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$user_attachments_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$user_attachments_grid->RenderOtherOptions();
?>
<?php $user_attachments_grid->ShowPageHeader(); ?>
<?php
$user_attachments_grid->ShowMessage();
?>
<?php if ($user_attachments_grid->TotalRecs > 0 || $user_attachments->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($user_attachments_grid->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> user_attachments">
<div id="fuser_attachmentsgrid" class="ewForm ewListForm form-inline">
<?php if ($user_attachments_grid->ShowOtherOptions) { ?>
<div class="box-header ewGridUpperPanel">
<?php
	foreach ($user_attachments_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_user_attachments" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table id="tbl_user_attachmentsgrid" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$user_attachments_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$user_attachments_grid->RenderListOptions();

// Render list options (header, left)
$user_attachments_grid->ListOptions->Render("header", "left");
?>
<?php if ($user_attachments->id->Visible) { // id ?>
	<?php if ($user_attachments->SortUrl($user_attachments->id) == "") { ?>
		<th data-name="id" class="<?php echo $user_attachments->id->HeaderCellClass() ?>"><div id="elh_user_attachments_id" class="user_attachments_id"><div class="ewTableHeaderCaption"><?php echo $user_attachments->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $user_attachments->id->HeaderCellClass() ?>"><div><div id="elh_user_attachments_id" class="user_attachments_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user_attachments->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user_attachments->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user_attachments->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($user_attachments->_userid->Visible) { // userid ?>
	<?php if ($user_attachments->SortUrl($user_attachments->_userid) == "") { ?>
		<th data-name="_userid" class="<?php echo $user_attachments->_userid->HeaderCellClass() ?>"><div id="elh_user_attachments__userid" class="user_attachments__userid"><div class="ewTableHeaderCaption"><?php echo $user_attachments->_userid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_userid" class="<?php echo $user_attachments->_userid->HeaderCellClass() ?>"><div><div id="elh_user_attachments__userid" class="user_attachments__userid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user_attachments->_userid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user_attachments->_userid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user_attachments->_userid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($user_attachments->description->Visible) { // description ?>
	<?php if ($user_attachments->SortUrl($user_attachments->description) == "") { ?>
		<th data-name="description" class="<?php echo $user_attachments->description->HeaderCellClass() ?>"><div id="elh_user_attachments_description" class="user_attachments_description"><div class="ewTableHeaderCaption"><?php echo $user_attachments->description->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="description" class="<?php echo $user_attachments->description->HeaderCellClass() ?>"><div><div id="elh_user_attachments_description" class="user_attachments_description">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user_attachments->description->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user_attachments->description->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user_attachments->description->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($user_attachments->hours->Visible) { // hours ?>
	<?php if ($user_attachments->SortUrl($user_attachments->hours) == "") { ?>
		<th data-name="hours" class="<?php echo $user_attachments->hours->HeaderCellClass() ?>"><div id="elh_user_attachments_hours" class="user_attachments_hours"><div class="ewTableHeaderCaption"><?php echo $user_attachments->hours->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hours" class="<?php echo $user_attachments->hours->HeaderCellClass() ?>"><div><div id="elh_user_attachments_hours" class="user_attachments_hours">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user_attachments->hours->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user_attachments->hours->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user_attachments->hours->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($user_attachments->file->Visible) { // file ?>
	<?php if ($user_attachments->SortUrl($user_attachments->file) == "") { ?>
		<th data-name="file" class="<?php echo $user_attachments->file->HeaderCellClass() ?>"><div id="elh_user_attachments_file" class="user_attachments_file"><div class="ewTableHeaderCaption"><?php echo $user_attachments->file->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="file" class="<?php echo $user_attachments->file->HeaderCellClass() ?>"><div><div id="elh_user_attachments_file" class="user_attachments_file">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $user_attachments->file->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($user_attachments->file->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($user_attachments->file->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$user_attachments_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$user_attachments_grid->StartRec = 1;
$user_attachments_grid->StopRec = $user_attachments_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($user_attachments_grid->FormKeyCountName) && ($user_attachments->CurrentAction == "gridadd" || $user_attachments->CurrentAction == "gridedit" || $user_attachments->CurrentAction == "F")) {
		$user_attachments_grid->KeyCount = $objForm->GetValue($user_attachments_grid->FormKeyCountName);
		$user_attachments_grid->StopRec = $user_attachments_grid->StartRec + $user_attachments_grid->KeyCount - 1;
	}
}
$user_attachments_grid->RecCnt = $user_attachments_grid->StartRec - 1;
if ($user_attachments_grid->Recordset && !$user_attachments_grid->Recordset->EOF) {
	$user_attachments_grid->Recordset->MoveFirst();
	$bSelectLimit = $user_attachments_grid->UseSelectLimit;
	if (!$bSelectLimit && $user_attachments_grid->StartRec > 1)
		$user_attachments_grid->Recordset->Move($user_attachments_grid->StartRec - 1);
} elseif (!$user_attachments->AllowAddDeleteRow && $user_attachments_grid->StopRec == 0) {
	$user_attachments_grid->StopRec = $user_attachments->GridAddRowCount;
}

// Initialize aggregate
$user_attachments->RowType = EW_ROWTYPE_AGGREGATEINIT;
$user_attachments->ResetAttrs();
$user_attachments_grid->RenderRow();
if ($user_attachments->CurrentAction == "gridadd")
	$user_attachments_grid->RowIndex = 0;
if ($user_attachments->CurrentAction == "gridedit")
	$user_attachments_grid->RowIndex = 0;
while ($user_attachments_grid->RecCnt < $user_attachments_grid->StopRec) {
	$user_attachments_grid->RecCnt++;
	if (intval($user_attachments_grid->RecCnt) >= intval($user_attachments_grid->StartRec)) {
		$user_attachments_grid->RowCnt++;
		if ($user_attachments->CurrentAction == "gridadd" || $user_attachments->CurrentAction == "gridedit" || $user_attachments->CurrentAction == "F") {
			$user_attachments_grid->RowIndex++;
			$objForm->Index = $user_attachments_grid->RowIndex;
			if ($objForm->HasValue($user_attachments_grid->FormActionName))
				$user_attachments_grid->RowAction = strval($objForm->GetValue($user_attachments_grid->FormActionName));
			elseif ($user_attachments->CurrentAction == "gridadd")
				$user_attachments_grid->RowAction = "insert";
			else
				$user_attachments_grid->RowAction = "";
		}

		// Set up key count
		$user_attachments_grid->KeyCount = $user_attachments_grid->RowIndex;

		// Init row class and style
		$user_attachments->ResetAttrs();
		$user_attachments->CssClass = "";
		if ($user_attachments->CurrentAction == "gridadd") {
			if ($user_attachments->CurrentMode == "copy") {
				$user_attachments_grid->LoadRowValues($user_attachments_grid->Recordset); // Load row values
				$user_attachments_grid->SetRecordKey($user_attachments_grid->RowOldKey, $user_attachments_grid->Recordset); // Set old record key
			} else {
				$user_attachments_grid->LoadDefaultValues(); // Load default values
				$user_attachments_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$user_attachments_grid->LoadRowValues($user_attachments_grid->Recordset); // Load row values
		}
		$user_attachments->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($user_attachments->CurrentAction == "gridadd") // Grid add
			$user_attachments->RowType = EW_ROWTYPE_ADD; // Render add
		if ($user_attachments->CurrentAction == "gridadd" && $user_attachments->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$user_attachments_grid->RestoreCurrentRowFormValues($user_attachments_grid->RowIndex); // Restore form values
		if ($user_attachments->CurrentAction == "gridedit") { // Grid edit
			if ($user_attachments->EventCancelled) {
				$user_attachments_grid->RestoreCurrentRowFormValues($user_attachments_grid->RowIndex); // Restore form values
			}
			if ($user_attachments_grid->RowAction == "insert")
				$user_attachments->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$user_attachments->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($user_attachments->CurrentAction == "gridedit" && ($user_attachments->RowType == EW_ROWTYPE_EDIT || $user_attachments->RowType == EW_ROWTYPE_ADD) && $user_attachments->EventCancelled) // Update failed
			$user_attachments_grid->RestoreCurrentRowFormValues($user_attachments_grid->RowIndex); // Restore form values
		if ($user_attachments->RowType == EW_ROWTYPE_EDIT) // Edit row
			$user_attachments_grid->EditRowCnt++;
		if ($user_attachments->CurrentAction == "F") // Confirm row
			$user_attachments_grid->RestoreCurrentRowFormValues($user_attachments_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$user_attachments->RowAttrs = array_merge($user_attachments->RowAttrs, array('data-rowindex'=>$user_attachments_grid->RowCnt, 'id'=>'r' . $user_attachments_grid->RowCnt . '_user_attachments', 'data-rowtype'=>$user_attachments->RowType));

		// Render row
		$user_attachments_grid->RenderRow();

		// Render list options
		$user_attachments_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($user_attachments_grid->RowAction <> "delete" && $user_attachments_grid->RowAction <> "insertdelete" && !($user_attachments_grid->RowAction == "insert" && $user_attachments->CurrentAction == "F" && $user_attachments_grid->EmptyRow())) {
?>
	<tr<?php echo $user_attachments->RowAttributes() ?>>
<?php

// Render list options (body, left)
$user_attachments_grid->ListOptions->Render("body", "left", $user_attachments_grid->RowCnt);
?>
	<?php if ($user_attachments->id->Visible) { // id ?>
		<td data-name="id"<?php echo $user_attachments->id->CellAttributes() ?>>
<?php if ($user_attachments->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="o<?php echo $user_attachments_grid->RowIndex ?>_id" id="o<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->OldValue) ?>">
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_id" class="form-group user_attachments_id">
<span<?php echo $user_attachments->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="x<?php echo $user_attachments_grid->RowIndex ?>_id" id="x<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->CurrentValue) ?>">
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_id" class="user_attachments_id">
<span<?php echo $user_attachments->id->ViewAttributes() ?>>
<?php echo $user_attachments->id->ListViewValue() ?></span>
</span>
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="x<?php echo $user_attachments_grid->RowIndex ?>_id" id="x<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x_id" name="o<?php echo $user_attachments_grid->RowIndex ?>_id" id="o<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>_id" id="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x_id" name="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>_id" id="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($user_attachments->_userid->Visible) { // userid ?>
		<td data-name="_userid"<?php echo $user_attachments->_userid->CellAttributes() ?>>
<?php if ($user_attachments->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($user_attachments->_userid->getSessionValue() <> "") { ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments__userid" class="form-group user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->_userid->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments__userid" class="form-group user_attachments__userid">
<input type="text" data-table="user_attachments" data-field="x__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" size="30" placeholder="<?php echo ew_HtmlEncode($user_attachments->_userid->getPlaceHolder()) ?>" value="<?php echo $user_attachments->_userid->EditValue ?>"<?php echo $user_attachments->_userid->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="o<?php echo $user_attachments_grid->RowIndex ?>__userid" id="o<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->OldValue) ?>">
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($user_attachments->_userid->getSessionValue() <> "") { ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments__userid" class="form-group user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->_userid->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments__userid" class="form-group user_attachments__userid">
<input type="text" data-table="user_attachments" data-field="x__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" size="30" placeholder="<?php echo ew_HtmlEncode($user_attachments->_userid->getPlaceHolder()) ?>" value="<?php echo $user_attachments->_userid->EditValue ?>"<?php echo $user_attachments->_userid->EditAttributes() ?>>
</span>
<?php } ?>
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments__userid" class="user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<?php echo $user_attachments->_userid->ListViewValue() ?></span>
</span>
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="o<?php echo $user_attachments_grid->RowIndex ?>__userid" id="o<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>__userid" id="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>__userid" id="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($user_attachments->description->Visible) { // description ?>
		<td data-name="description"<?php echo $user_attachments->description->CellAttributes() ?>>
<?php if ($user_attachments->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_description" class="form-group user_attachments_description">
<textarea data-table="user_attachments" data-field="x_description" name="x<?php echo $user_attachments_grid->RowIndex ?>_description" id="x<?php echo $user_attachments_grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user_attachments->description->getPlaceHolder()) ?>"<?php echo $user_attachments->description->EditAttributes() ?>><?php echo $user_attachments->description->EditValue ?></textarea>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_description" name="o<?php echo $user_attachments_grid->RowIndex ?>_description" id="o<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->OldValue) ?>">
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_description" class="form-group user_attachments_description">
<textarea data-table="user_attachments" data-field="x_description" name="x<?php echo $user_attachments_grid->RowIndex ?>_description" id="x<?php echo $user_attachments_grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user_attachments->description->getPlaceHolder()) ?>"<?php echo $user_attachments->description->EditAttributes() ?>><?php echo $user_attachments->description->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_description" class="user_attachments_description">
<span<?php echo $user_attachments->description->ViewAttributes() ?>>
<?php echo $user_attachments->description->ListViewValue() ?></span>
</span>
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<input type="hidden" data-table="user_attachments" data-field="x_description" name="x<?php echo $user_attachments_grid->RowIndex ?>_description" id="x<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x_description" name="o<?php echo $user_attachments_grid->RowIndex ?>_description" id="o<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="user_attachments" data-field="x_description" name="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>_description" id="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x_description" name="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>_description" id="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($user_attachments->hours->Visible) { // hours ?>
		<td data-name="hours"<?php echo $user_attachments->hours->CellAttributes() ?>>
<?php if ($user_attachments->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_hours" class="form-group user_attachments_hours">
<input type="text" data-table="user_attachments" data-field="x_hours" name="x<?php echo $user_attachments_grid->RowIndex ?>_hours" id="x<?php echo $user_attachments_grid->RowIndex ?>_hours" placeholder="<?php echo ew_HtmlEncode($user_attachments->hours->getPlaceHolder()) ?>" value="<?php echo $user_attachments->hours->EditValue ?>"<?php echo $user_attachments->hours->EditAttributes() ?>>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="o<?php echo $user_attachments_grid->RowIndex ?>_hours" id="o<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->OldValue) ?>">
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_hours" class="form-group user_attachments_hours">
<input type="text" data-table="user_attachments" data-field="x_hours" name="x<?php echo $user_attachments_grid->RowIndex ?>_hours" id="x<?php echo $user_attachments_grid->RowIndex ?>_hours" placeholder="<?php echo ew_HtmlEncode($user_attachments->hours->getPlaceHolder()) ?>" value="<?php echo $user_attachments->hours->EditValue ?>"<?php echo $user_attachments->hours->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($user_attachments->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_hours" class="user_attachments_hours">
<span<?php echo $user_attachments->hours->ViewAttributes() ?>>
<?php echo $user_attachments->hours->ListViewValue() ?></span>
</span>
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="x<?php echo $user_attachments_grid->RowIndex ?>_hours" id="x<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="o<?php echo $user_attachments_grid->RowIndex ?>_hours" id="o<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>_hours" id="fuser_attachmentsgrid$x<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->FormValue) ?>">
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>_hours" id="fuser_attachmentsgrid$o<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($user_attachments->file->Visible) { // file ?>
		<td data-name="file"<?php echo $user_attachments->file->CellAttributes() ?>>
<?php if ($user_attachments_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_user_attachments_file" class="form-group user_attachments_file">
<div id="fd_x<?php echo $user_attachments_grid->RowIndex ?>_file">
<span title="<?php echo $user_attachments->file->FldTitle() ? $user_attachments->file->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($user_attachments->file->ReadOnly || $user_attachments->file->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="user_attachments" data-field="x_file" name="x<?php echo $user_attachments_grid->RowIndex ?>_file" id="x<?php echo $user_attachments_grid->RowIndex ?>_file"<?php echo $user_attachments->file->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fn_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="0">
<input type="hidden" name="fs_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fs_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="65535">
<input type="hidden" name="fx_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fx_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fm_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $user_attachments_grid->RowIndex ?>_file" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_file" name="o<?php echo $user_attachments_grid->RowIndex ?>_file" id="o<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo ew_HtmlEncode($user_attachments->file->OldValue) ?>">
<?php } elseif ($user_attachments->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_file" class="user_attachments_file">
<span<?php echo $user_attachments->file->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($user_attachments->file, $user_attachments->file->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $user_attachments_grid->RowCnt ?>_user_attachments_file" class="form-group user_attachments_file">
<div id="fd_x<?php echo $user_attachments_grid->RowIndex ?>_file">
<span title="<?php echo $user_attachments->file->FldTitle() ? $user_attachments->file->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($user_attachments->file->ReadOnly || $user_attachments->file->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="user_attachments" data-field="x_file" name="x<?php echo $user_attachments_grid->RowIndex ?>_file" id="x<?php echo $user_attachments_grid->RowIndex ?>_file"<?php echo $user_attachments->file->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fn_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $user_attachments_grid->RowIndex ?>_file"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fs_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="65535">
<input type="hidden" name="fx_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fx_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fm_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $user_attachments_grid->RowIndex ?>_file" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$user_attachments_grid->ListOptions->Render("body", "right", $user_attachments_grid->RowCnt);
?>
	</tr>
<?php if ($user_attachments->RowType == EW_ROWTYPE_ADD || $user_attachments->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fuser_attachmentsgrid.UpdateOpts(<?php echo $user_attachments_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($user_attachments->CurrentAction <> "gridadd" || $user_attachments->CurrentMode == "copy")
		if (!$user_attachments_grid->Recordset->EOF) $user_attachments_grid->Recordset->MoveNext();
}
?>
<?php
	if ($user_attachments->CurrentMode == "add" || $user_attachments->CurrentMode == "copy" || $user_attachments->CurrentMode == "edit") {
		$user_attachments_grid->RowIndex = '$rowindex$';
		$user_attachments_grid->LoadDefaultValues();

		// Set row properties
		$user_attachments->ResetAttrs();
		$user_attachments->RowAttrs = array_merge($user_attachments->RowAttrs, array('data-rowindex'=>$user_attachments_grid->RowIndex, 'id'=>'r0_user_attachments', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($user_attachments->RowAttrs["class"], "ewTemplate");
		$user_attachments->RowType = EW_ROWTYPE_ADD;

		// Render row
		$user_attachments_grid->RenderRow();

		// Render list options
		$user_attachments_grid->RenderListOptions();
		$user_attachments_grid->StartRowCnt = 0;
?>
	<tr<?php echo $user_attachments->RowAttributes() ?>>
<?php

// Render list options (body, left)
$user_attachments_grid->ListOptions->Render("body", "left", $user_attachments_grid->RowIndex);
?>
	<?php if ($user_attachments->id->Visible) { // id ?>
		<td data-name="id">
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_user_attachments_id" class="form-group user_attachments_id">
<span<?php echo $user_attachments->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="x<?php echo $user_attachments_grid->RowIndex ?>_id" id="x<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="o<?php echo $user_attachments_grid->RowIndex ?>_id" id="o<?php echo $user_attachments_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($user_attachments->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($user_attachments->_userid->Visible) { // userid ?>
		<td data-name="_userid">
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<?php if ($user_attachments->_userid->getSessionValue() <> "") { ?>
<span id="el$rowindex$_user_attachments__userid" class="form-group user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->_userid->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_user_attachments__userid" class="form-group user_attachments__userid">
<input type="text" data-table="user_attachments" data-field="x__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" size="30" placeholder="<?php echo ew_HtmlEncode($user_attachments->_userid->getPlaceHolder()) ?>" value="<?php echo $user_attachments->_userid->EditValue ?>"<?php echo $user_attachments->_userid->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_user_attachments__userid" class="form-group user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->_userid->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="x<?php echo $user_attachments_grid->RowIndex ?>__userid" id="x<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="user_attachments" data-field="x__userid" name="o<?php echo $user_attachments_grid->RowIndex ?>__userid" id="o<?php echo $user_attachments_grid->RowIndex ?>__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($user_attachments->description->Visible) { // description ?>
		<td data-name="description">
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<span id="el$rowindex$_user_attachments_description" class="form-group user_attachments_description">
<textarea data-table="user_attachments" data-field="x_description" name="x<?php echo $user_attachments_grid->RowIndex ?>_description" id="x<?php echo $user_attachments_grid->RowIndex ?>_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user_attachments->description->getPlaceHolder()) ?>"<?php echo $user_attachments->description->EditAttributes() ?>><?php echo $user_attachments->description->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_user_attachments_description" class="form-group user_attachments_description">
<span<?php echo $user_attachments->description->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->description->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_description" name="x<?php echo $user_attachments_grid->RowIndex ?>_description" id="x<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="user_attachments" data-field="x_description" name="o<?php echo $user_attachments_grid->RowIndex ?>_description" id="o<?php echo $user_attachments_grid->RowIndex ?>_description" value="<?php echo ew_HtmlEncode($user_attachments->description->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($user_attachments->hours->Visible) { // hours ?>
		<td data-name="hours">
<?php if ($user_attachments->CurrentAction <> "F") { ?>
<span id="el$rowindex$_user_attachments_hours" class="form-group user_attachments_hours">
<input type="text" data-table="user_attachments" data-field="x_hours" name="x<?php echo $user_attachments_grid->RowIndex ?>_hours" id="x<?php echo $user_attachments_grid->RowIndex ?>_hours" placeholder="<?php echo ew_HtmlEncode($user_attachments->hours->getPlaceHolder()) ?>" value="<?php echo $user_attachments->hours->EditValue ?>"<?php echo $user_attachments->hours->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_user_attachments_hours" class="form-group user_attachments_hours">
<span<?php echo $user_attachments->hours->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->hours->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="x<?php echo $user_attachments_grid->RowIndex ?>_hours" id="x<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="user_attachments" data-field="x_hours" name="o<?php echo $user_attachments_grid->RowIndex ?>_hours" id="o<?php echo $user_attachments_grid->RowIndex ?>_hours" value="<?php echo ew_HtmlEncode($user_attachments->hours->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($user_attachments->file->Visible) { // file ?>
		<td data-name="file">
<span id="el$rowindex$_user_attachments_file" class="form-group user_attachments_file">
<div id="fd_x<?php echo $user_attachments_grid->RowIndex ?>_file">
<span title="<?php echo $user_attachments->file->FldTitle() ? $user_attachments->file->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($user_attachments->file->ReadOnly || $user_attachments->file->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="user_attachments" data-field="x_file" name="x<?php echo $user_attachments_grid->RowIndex ?>_file" id="x<?php echo $user_attachments_grid->RowIndex ?>_file"<?php echo $user_attachments->file->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fn_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fa_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="0">
<input type="hidden" name="fs_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fs_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="65535">
<input type="hidden" name="fx_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fx_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $user_attachments_grid->RowIndex ?>_file" id= "fm_x<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo $user_attachments->file->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $user_attachments_grid->RowIndex ?>_file" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_file" name="o<?php echo $user_attachments_grid->RowIndex ?>_file" id="o<?php echo $user_attachments_grid->RowIndex ?>_file" value="<?php echo ew_HtmlEncode($user_attachments->file->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$user_attachments_grid->ListOptions->Render("body", "right", $user_attachments_grid->RowCnt);
?>
<script type="text/javascript">
fuser_attachmentsgrid.UpdateOpts(<?php echo $user_attachments_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($user_attachments->CurrentMode == "add" || $user_attachments->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $user_attachments_grid->FormKeyCountName ?>" id="<?php echo $user_attachments_grid->FormKeyCountName ?>" value="<?php echo $user_attachments_grid->KeyCount ?>">
<?php echo $user_attachments_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($user_attachments->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $user_attachments_grid->FormKeyCountName ?>" id="<?php echo $user_attachments_grid->FormKeyCountName ?>" value="<?php echo $user_attachments_grid->KeyCount ?>">
<?php echo $user_attachments_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($user_attachments->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fuser_attachmentsgrid">
</div>
<?php

// Close recordset
if ($user_attachments_grid->Recordset)
	$user_attachments_grid->Recordset->Close();
?>
<?php if ($user_attachments_grid->ShowOtherOptions) { ?>
<div class="box-footer ewGridLowerPanel">
<?php
	foreach ($user_attachments_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($user_attachments_grid->TotalRecs == 0 && $user_attachments->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($user_attachments_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($user_attachments->Export == "") { ?>
<script type="text/javascript">
fuser_attachmentsgrid.Init();
</script>
<?php } ?>
<?php
$user_attachments_grid->Page_Terminate();
?>
