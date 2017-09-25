<?php

// Global variable for table object
$institutions_requests = NULL;

//
// Table class for institutions_requests
//
class cinstitutions_requests extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $id;
	var $institutions_id;
	var $event_name;
	var $event_emirate;
	var $event_location;
	var $activity_start_date;
	var $activity_end_date;
	var $activity_time;
	var $activity_description;
	var $activity_gender_target;
	var $no_of_persons_needed;
	var $no_of_hours;
	var $mobile_phone;
	var $pobox;
	var $admin_approval;
	var $admin_comment;
	var $email;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'institutions_requests';
		$this->TableName = 'institutions_requests';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`institutions_requests`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 10;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('institutions_requests', 'institutions_requests', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// institutions_id
		$this->institutions_id = new cField('institutions_requests', 'institutions_requests', 'x_institutions_id', 'institutions_id', '`institutions_id`', '`institutions_id`', 3, -1, FALSE, '`institutions_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->institutions_id->Sortable = TRUE; // Allow sort
		$this->institutions_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->institutions_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->institutions_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['institutions_id'] = &$this->institutions_id;

		// event_name
		$this->event_name = new cField('institutions_requests', 'institutions_requests', 'x_event_name', 'event_name', '`event_name`', '`event_name`', 201, -1, FALSE, '`event_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->event_name->Sortable = TRUE; // Allow sort
		$this->fields['event_name'] = &$this->event_name;

		// event_emirate
		$this->event_emirate = new cField('institutions_requests', 'institutions_requests', 'x_event_emirate', 'event_emirate', '`event_emirate`', '`event_emirate`', 201, -1, FALSE, '`event_emirate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->event_emirate->Sortable = TRUE; // Allow sort
		$this->event_emirate->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->event_emirate->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->event_emirate->OptionCount = 7;
		$this->fields['event_emirate'] = &$this->event_emirate;

		// event_location
		$this->event_location = new cField('institutions_requests', 'institutions_requests', 'x_event_location', 'event_location', '`event_location`', '`event_location`', 201, -1, FALSE, '`event_location`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->event_location->Sortable = TRUE; // Allow sort
		$this->fields['event_location'] = &$this->event_location;

		// activity_start_date
		$this->activity_start_date = new cField('institutions_requests', 'institutions_requests', 'x_activity_start_date', 'activity_start_date', '`activity_start_date`', ew_CastDateFieldForLike('`activity_start_date`', 0, "DB"), 133, 0, FALSE, '`activity_start_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_start_date->Sortable = TRUE; // Allow sort
		$this->activity_start_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['activity_start_date'] = &$this->activity_start_date;

		// activity_end_date
		$this->activity_end_date = new cField('institutions_requests', 'institutions_requests', 'x_activity_end_date', 'activity_end_date', '`activity_end_date`', ew_CastDateFieldForLike('`activity_end_date`', 0, "DB"), 133, 0, FALSE, '`activity_end_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_end_date->Sortable = TRUE; // Allow sort
		$this->activity_end_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['activity_end_date'] = &$this->activity_end_date;

		// activity_time
		$this->activity_time = new cField('institutions_requests', 'institutions_requests', 'x_activity_time', 'activity_time', '`activity_time`', '`activity_time`', 201, -1, FALSE, '`activity_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_time->Sortable = TRUE; // Allow sort
		$this->fields['activity_time'] = &$this->activity_time;

		// activity_description
		$this->activity_description = new cField('institutions_requests', 'institutions_requests', 'x_activity_description', 'activity_description', '`activity_description`', '`activity_description`', 201, -1, FALSE, '`activity_description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_description->Sortable = TRUE; // Allow sort
		$this->fields['activity_description'] = &$this->activity_description;

		// activity_gender_target
		$this->activity_gender_target = new cField('institutions_requests', 'institutions_requests', 'x_activity_gender_target', 'activity_gender_target', '`activity_gender_target`', '`activity_gender_target`', 201, -1, FALSE, '`activity_gender_target`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->activity_gender_target->Sortable = TRUE; // Allow sort
		$this->activity_gender_target->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->activity_gender_target->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->activity_gender_target->OptionCount = 3;
		$this->fields['activity_gender_target'] = &$this->activity_gender_target;

		// no_of_persons_needed
		$this->no_of_persons_needed = new cField('institutions_requests', 'institutions_requests', 'x_no_of_persons_needed', 'no_of_persons_needed', '`no_of_persons_needed`', '`no_of_persons_needed`', 201, -1, FALSE, '`no_of_persons_needed`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_of_persons_needed->Sortable = TRUE; // Allow sort
		$this->fields['no_of_persons_needed'] = &$this->no_of_persons_needed;

		// no_of_hours
		$this->no_of_hours = new cField('institutions_requests', 'institutions_requests', 'x_no_of_hours', 'no_of_hours', '`no_of_hours`', '`no_of_hours`', 201, -1, FALSE, '`no_of_hours`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_of_hours->Sortable = TRUE; // Allow sort
		$this->fields['no_of_hours'] = &$this->no_of_hours;

		// mobile_phone
		$this->mobile_phone = new cField('institutions_requests', 'institutions_requests', 'x_mobile_phone', 'mobile_phone', '`mobile_phone`', '`mobile_phone`', 201, -1, FALSE, '`mobile_phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->mobile_phone->Sortable = TRUE; // Allow sort
		$this->fields['mobile_phone'] = &$this->mobile_phone;

		// pobox
		$this->pobox = new cField('institutions_requests', 'institutions_requests', 'x_pobox', 'pobox', '`pobox`', '`pobox`', 201, -1, FALSE, '`pobox`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pobox->Sortable = TRUE; // Allow sort
		$this->fields['pobox'] = &$this->pobox;

		// admin_approval
		$this->admin_approval = new cField('institutions_requests', 'institutions_requests', 'x_admin_approval', 'admin_approval', '`admin_approval`', '`admin_approval`', 3, -1, FALSE, '`admin_approval`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->admin_approval->Sortable = TRUE; // Allow sort
		$this->admin_approval->OptionCount = 3;
		$this->admin_approval->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['admin_approval'] = &$this->admin_approval;

		// admin_comment
		$this->admin_comment = new cField('institutions_requests', 'institutions_requests', 'x_admin_comment', 'admin_comment', '`admin_comment`', '`admin_comment`', 201, -1, FALSE, '`admin_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->admin_comment->Sortable = TRUE; // Allow sort
		$this->fields['admin_comment'] = &$this->admin_comment;

		// email
		$this->email = new cField('institutions_requests', 'institutions_requests', 'x_email', 'email', '`email`', '`email`', 201, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->email;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $this->LeftColumnClass);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`institutions_requests`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`id` DESC";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$sSql = $this->ListSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->id->setDbValue($conn->Insert_ID());
			$rs['id'] = $this->id->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'id';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsold, $rsaudit);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id', $this->DBID) . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "institutions_requestslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "institutions_requestsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "institutions_requestsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "institutions_requestsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "institutions_requestslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("institutions_requestsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("institutions_requestsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "institutions_requestsadd.php?" . $this->UrlParm($parm);
		else
			$url = "institutions_requestsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("institutions_requestsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("institutions_requestsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("institutions_requestsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "id:" . ew_VarToJson($this->id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["id"]))
				$arKeys[] = $_POST["id"];
			elseif (isset($_GET["id"]))
				$arKeys[] = $_GET["id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->institutions_id->setDbValue($rs->fields('institutions_id'));
		$this->event_name->setDbValue($rs->fields('event_name'));
		$this->event_emirate->setDbValue($rs->fields('event_emirate'));
		$this->event_location->setDbValue($rs->fields('event_location'));
		$this->activity_start_date->setDbValue($rs->fields('activity_start_date'));
		$this->activity_end_date->setDbValue($rs->fields('activity_end_date'));
		$this->activity_time->setDbValue($rs->fields('activity_time'));
		$this->activity_description->setDbValue($rs->fields('activity_description'));
		$this->activity_gender_target->setDbValue($rs->fields('activity_gender_target'));
		$this->no_of_persons_needed->setDbValue($rs->fields('no_of_persons_needed'));
		$this->no_of_hours->setDbValue($rs->fields('no_of_hours'));
		$this->mobile_phone->setDbValue($rs->fields('mobile_phone'));
		$this->pobox->setDbValue($rs->fields('pobox'));
		$this->admin_approval->setDbValue($rs->fields('admin_approval'));
		$this->admin_comment->setDbValue($rs->fields('admin_comment'));
		$this->email->setDbValue($rs->fields('email'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// id
		// institutions_id
		// event_name
		// event_emirate
		// event_location
		// activity_start_date
		// activity_end_date
		// activity_time
		// activity_description
		// activity_gender_target
		// no_of_persons_needed
		// no_of_hours
		// mobile_phone
		// pobox
		// admin_approval
		// admin_comment
		// email
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// institutions_id
		if (strval($this->institutions_id->CurrentValue) <> "") {
			$sFilterWrk = "`institution_id`" . ew_SearchString("=", $this->institutions_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
		$sWhereWrk = "";
		$this->institutions_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->institutions_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->institutions_id->ViewValue = $this->institutions_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->institutions_id->ViewValue = $this->institutions_id->CurrentValue;
			}
		} else {
			$this->institutions_id->ViewValue = NULL;
		}
		$this->institutions_id->ViewCustomAttributes = "";

		// event_name
		$this->event_name->ViewValue = $this->event_name->CurrentValue;
		$this->event_name->ViewCustomAttributes = "";

		// event_emirate
		if (strval($this->event_emirate->CurrentValue) <> "") {
			$this->event_emirate->ViewValue = $this->event_emirate->OptionCaption($this->event_emirate->CurrentValue);
		} else {
			$this->event_emirate->ViewValue = NULL;
		}
		$this->event_emirate->ViewCustomAttributes = "";

		// event_location
		$this->event_location->ViewValue = $this->event_location->CurrentValue;
		$this->event_location->ViewCustomAttributes = "";

		// activity_start_date
		$this->activity_start_date->ViewValue = $this->activity_start_date->CurrentValue;
		$this->activity_start_date->ViewValue = ew_FormatDateTime($this->activity_start_date->ViewValue, 0);
		$this->activity_start_date->ViewCustomAttributes = "";

		// activity_end_date
		$this->activity_end_date->ViewValue = $this->activity_end_date->CurrentValue;
		$this->activity_end_date->ViewValue = ew_FormatDateTime($this->activity_end_date->ViewValue, 0);
		$this->activity_end_date->ViewCustomAttributes = "";

		// activity_time
		$this->activity_time->ViewValue = $this->activity_time->CurrentValue;
		$this->activity_time->ViewCustomAttributes = "";

		// activity_description
		$this->activity_description->ViewValue = $this->activity_description->CurrentValue;
		$this->activity_description->ViewCustomAttributes = "";

		// activity_gender_target
		if (strval($this->activity_gender_target->CurrentValue) <> "") {
			$this->activity_gender_target->ViewValue = $this->activity_gender_target->OptionCaption($this->activity_gender_target->CurrentValue);
		} else {
			$this->activity_gender_target->ViewValue = NULL;
		}
		$this->activity_gender_target->ViewCustomAttributes = "";

		// no_of_persons_needed
		$this->no_of_persons_needed->ViewValue = $this->no_of_persons_needed->CurrentValue;
		$this->no_of_persons_needed->ViewCustomAttributes = "";

		// no_of_hours
		$this->no_of_hours->ViewValue = $this->no_of_hours->CurrentValue;
		$this->no_of_hours->ViewCustomAttributes = "";

		// mobile_phone
		$this->mobile_phone->ViewValue = $this->mobile_phone->CurrentValue;
		$this->mobile_phone->ViewCustomAttributes = "";

		// pobox
		$this->pobox->ViewValue = $this->pobox->CurrentValue;
		$this->pobox->ViewCustomAttributes = "";

		// admin_approval
		if (strval($this->admin_approval->CurrentValue) <> "") {
			$this->admin_approval->ViewValue = $this->admin_approval->OptionCaption($this->admin_approval->CurrentValue);
		} else {
			$this->admin_approval->ViewValue = NULL;
		}
		$this->admin_approval->ViewCustomAttributes = "";

		// admin_comment
		$this->admin_comment->ViewValue = $this->admin_comment->CurrentValue;
		$this->admin_comment->ViewCustomAttributes = "";

		// email
		$this->email->ViewValue = $this->email->CurrentValue;
		$this->email->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// institutions_id
		$this->institutions_id->LinkCustomAttributes = "";
		$this->institutions_id->HrefValue = "";
		$this->institutions_id->TooltipValue = "";

		// event_name
		$this->event_name->LinkCustomAttributes = "";
		$this->event_name->HrefValue = "";
		$this->event_name->TooltipValue = "";

		// event_emirate
		$this->event_emirate->LinkCustomAttributes = "";
		$this->event_emirate->HrefValue = "";
		$this->event_emirate->TooltipValue = "";

		// event_location
		$this->event_location->LinkCustomAttributes = "";
		$this->event_location->HrefValue = "";
		$this->event_location->TooltipValue = "";

		// activity_start_date
		$this->activity_start_date->LinkCustomAttributes = "";
		$this->activity_start_date->HrefValue = "";
		$this->activity_start_date->TooltipValue = "";

		// activity_end_date
		$this->activity_end_date->LinkCustomAttributes = "";
		$this->activity_end_date->HrefValue = "";
		$this->activity_end_date->TooltipValue = "";

		// activity_time
		$this->activity_time->LinkCustomAttributes = "";
		$this->activity_time->HrefValue = "";
		$this->activity_time->TooltipValue = "";

		// activity_description
		$this->activity_description->LinkCustomAttributes = "";
		$this->activity_description->HrefValue = "";
		$this->activity_description->TooltipValue = "";

		// activity_gender_target
		$this->activity_gender_target->LinkCustomAttributes = "";
		$this->activity_gender_target->HrefValue = "";
		$this->activity_gender_target->TooltipValue = "";

		// no_of_persons_needed
		$this->no_of_persons_needed->LinkCustomAttributes = "";
		$this->no_of_persons_needed->HrefValue = "";
		$this->no_of_persons_needed->TooltipValue = "";

		// no_of_hours
		$this->no_of_hours->LinkCustomAttributes = "";
		$this->no_of_hours->HrefValue = "";
		$this->no_of_hours->TooltipValue = "";

		// mobile_phone
		$this->mobile_phone->LinkCustomAttributes = "";
		$this->mobile_phone->HrefValue = "";
		$this->mobile_phone->TooltipValue = "";

		// pobox
		$this->pobox->LinkCustomAttributes = "";
		$this->pobox->HrefValue = "";
		$this->pobox->TooltipValue = "";

		// admin_approval
		$this->admin_approval->LinkCustomAttributes = "";
		$this->admin_approval->HrefValue = "";
		$this->admin_approval->TooltipValue = "";

		// admin_comment
		$this->admin_comment->LinkCustomAttributes = "";
		$this->admin_comment->HrefValue = "";
		$this->admin_comment->TooltipValue = "";

		// email
		$this->email->LinkCustomAttributes = "";
		$this->email->HrefValue = "";
		$this->email->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// institutions_id
		$this->institutions_id->EditAttrs["class"] = "form-control";
		$this->institutions_id->EditCustomAttributes = "";

		// event_name
		$this->event_name->EditAttrs["class"] = "form-control";
		$this->event_name->EditCustomAttributes = "";
		$this->event_name->EditValue = $this->event_name->CurrentValue;
		$this->event_name->PlaceHolder = ew_RemoveHtml($this->event_name->FldCaption());

		// event_emirate
		$this->event_emirate->EditAttrs["class"] = "form-control";
		$this->event_emirate->EditCustomAttributes = "";
		$this->event_emirate->EditValue = $this->event_emirate->Options(TRUE);

		// event_location
		$this->event_location->EditAttrs["class"] = "form-control";
		$this->event_location->EditCustomAttributes = "";
		$this->event_location->EditValue = $this->event_location->CurrentValue;
		$this->event_location->PlaceHolder = ew_RemoveHtml($this->event_location->FldCaption());

		// activity_start_date
		$this->activity_start_date->EditAttrs["class"] = "form-control";
		$this->activity_start_date->EditCustomAttributes = "";
		$this->activity_start_date->EditValue = ew_FormatDateTime($this->activity_start_date->CurrentValue, 8);
		$this->activity_start_date->PlaceHolder = ew_RemoveHtml($this->activity_start_date->FldCaption());

		// activity_end_date
		$this->activity_end_date->EditAttrs["class"] = "form-control";
		$this->activity_end_date->EditCustomAttributes = "";
		$this->activity_end_date->EditValue = ew_FormatDateTime($this->activity_end_date->CurrentValue, 8);
		$this->activity_end_date->PlaceHolder = ew_RemoveHtml($this->activity_end_date->FldCaption());

		// activity_time
		$this->activity_time->EditAttrs["class"] = "form-control";
		$this->activity_time->EditCustomAttributes = "";
		$this->activity_time->EditValue = $this->activity_time->CurrentValue;
		$this->activity_time->PlaceHolder = ew_RemoveHtml($this->activity_time->FldCaption());

		// activity_description
		$this->activity_description->EditAttrs["class"] = "form-control";
		$this->activity_description->EditCustomAttributes = "";
		$this->activity_description->EditValue = $this->activity_description->CurrentValue;
		$this->activity_description->PlaceHolder = ew_RemoveHtml($this->activity_description->FldCaption());

		// activity_gender_target
		$this->activity_gender_target->EditAttrs["class"] = "form-control";
		$this->activity_gender_target->EditCustomAttributes = "";
		$this->activity_gender_target->EditValue = $this->activity_gender_target->Options(TRUE);

		// no_of_persons_needed
		$this->no_of_persons_needed->EditAttrs["class"] = "form-control";
		$this->no_of_persons_needed->EditCustomAttributes = "";
		$this->no_of_persons_needed->EditValue = $this->no_of_persons_needed->CurrentValue;
		$this->no_of_persons_needed->PlaceHolder = ew_RemoveHtml($this->no_of_persons_needed->FldCaption());

		// no_of_hours
		$this->no_of_hours->EditAttrs["class"] = "form-control";
		$this->no_of_hours->EditCustomAttributes = "";
		$this->no_of_hours->EditValue = $this->no_of_hours->CurrentValue;
		$this->no_of_hours->PlaceHolder = ew_RemoveHtml($this->no_of_hours->FldCaption());

		// mobile_phone
		$this->mobile_phone->EditAttrs["class"] = "form-control";
		$this->mobile_phone->EditCustomAttributes = "";
		$this->mobile_phone->EditValue = $this->mobile_phone->CurrentValue;
		$this->mobile_phone->PlaceHolder = ew_RemoveHtml($this->mobile_phone->FldCaption());

		// pobox
		$this->pobox->EditAttrs["class"] = "form-control";
		$this->pobox->EditCustomAttributes = "";
		$this->pobox->EditValue = $this->pobox->CurrentValue;
		$this->pobox->PlaceHolder = ew_RemoveHtml($this->pobox->FldCaption());

		// admin_approval
		$this->admin_approval->EditCustomAttributes = "";
		$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

		// admin_comment
		$this->admin_comment->EditAttrs["class"] = "form-control";
		$this->admin_comment->EditCustomAttributes = "";
		$this->admin_comment->EditValue = $this->admin_comment->CurrentValue;
		$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

		// email
		$this->email->EditAttrs["class"] = "form-control";
		$this->email->EditCustomAttributes = "";
		$this->email->EditValue = $this->email->CurrentValue;
		$this->email->PlaceHolder = ew_RemoveHtml($this->email->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->institutions_id->Exportable) $Doc->ExportCaption($this->institutions_id);
					if ($this->event_name->Exportable) $Doc->ExportCaption($this->event_name);
					if ($this->event_emirate->Exportable) $Doc->ExportCaption($this->event_emirate);
					if ($this->event_location->Exportable) $Doc->ExportCaption($this->event_location);
					if ($this->activity_start_date->Exportable) $Doc->ExportCaption($this->activity_start_date);
					if ($this->activity_end_date->Exportable) $Doc->ExportCaption($this->activity_end_date);
					if ($this->activity_time->Exportable) $Doc->ExportCaption($this->activity_time);
					if ($this->activity_description->Exportable) $Doc->ExportCaption($this->activity_description);
					if ($this->activity_gender_target->Exportable) $Doc->ExportCaption($this->activity_gender_target);
					if ($this->no_of_persons_needed->Exportable) $Doc->ExportCaption($this->no_of_persons_needed);
					if ($this->no_of_hours->Exportable) $Doc->ExportCaption($this->no_of_hours);
					if ($this->mobile_phone->Exportable) $Doc->ExportCaption($this->mobile_phone);
					if ($this->pobox->Exportable) $Doc->ExportCaption($this->pobox);
					if ($this->admin_approval->Exportable) $Doc->ExportCaption($this->admin_approval);
					if ($this->admin_comment->Exportable) $Doc->ExportCaption($this->admin_comment);
					if ($this->email->Exportable) $Doc->ExportCaption($this->email);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->institutions_id->Exportable) $Doc->ExportCaption($this->institutions_id);
					if ($this->event_name->Exportable) $Doc->ExportCaption($this->event_name);
					if ($this->event_emirate->Exportable) $Doc->ExportCaption($this->event_emirate);
					if ($this->event_location->Exportable) $Doc->ExportCaption($this->event_location);
					if ($this->activity_start_date->Exportable) $Doc->ExportCaption($this->activity_start_date);
					if ($this->activity_end_date->Exportable) $Doc->ExportCaption($this->activity_end_date);
					if ($this->activity_time->Exportable) $Doc->ExportCaption($this->activity_time);
					if ($this->activity_description->Exportable) $Doc->ExportCaption($this->activity_description);
					if ($this->activity_gender_target->Exportable) $Doc->ExportCaption($this->activity_gender_target);
					if ($this->no_of_persons_needed->Exportable) $Doc->ExportCaption($this->no_of_persons_needed);
					if ($this->no_of_hours->Exportable) $Doc->ExportCaption($this->no_of_hours);
					if ($this->mobile_phone->Exportable) $Doc->ExportCaption($this->mobile_phone);
					if ($this->pobox->Exportable) $Doc->ExportCaption($this->pobox);
					if ($this->admin_approval->Exportable) $Doc->ExportCaption($this->admin_approval);
					if ($this->admin_comment->Exportable) $Doc->ExportCaption($this->admin_comment);
					if ($this->email->Exportable) $Doc->ExportCaption($this->email);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->institutions_id->Exportable) $Doc->ExportField($this->institutions_id);
						if ($this->event_name->Exportable) $Doc->ExportField($this->event_name);
						if ($this->event_emirate->Exportable) $Doc->ExportField($this->event_emirate);
						if ($this->event_location->Exportable) $Doc->ExportField($this->event_location);
						if ($this->activity_start_date->Exportable) $Doc->ExportField($this->activity_start_date);
						if ($this->activity_end_date->Exportable) $Doc->ExportField($this->activity_end_date);
						if ($this->activity_time->Exportable) $Doc->ExportField($this->activity_time);
						if ($this->activity_description->Exportable) $Doc->ExportField($this->activity_description);
						if ($this->activity_gender_target->Exportable) $Doc->ExportField($this->activity_gender_target);
						if ($this->no_of_persons_needed->Exportable) $Doc->ExportField($this->no_of_persons_needed);
						if ($this->no_of_hours->Exportable) $Doc->ExportField($this->no_of_hours);
						if ($this->mobile_phone->Exportable) $Doc->ExportField($this->mobile_phone);
						if ($this->pobox->Exportable) $Doc->ExportField($this->pobox);
						if ($this->admin_approval->Exportable) $Doc->ExportField($this->admin_approval);
						if ($this->admin_comment->Exportable) $Doc->ExportField($this->admin_comment);
						if ($this->email->Exportable) $Doc->ExportField($this->email);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->institutions_id->Exportable) $Doc->ExportField($this->institutions_id);
						if ($this->event_name->Exportable) $Doc->ExportField($this->event_name);
						if ($this->event_emirate->Exportable) $Doc->ExportField($this->event_emirate);
						if ($this->event_location->Exportable) $Doc->ExportField($this->event_location);
						if ($this->activity_start_date->Exportable) $Doc->ExportField($this->activity_start_date);
						if ($this->activity_end_date->Exportable) $Doc->ExportField($this->activity_end_date);
						if ($this->activity_time->Exportable) $Doc->ExportField($this->activity_time);
						if ($this->activity_description->Exportable) $Doc->ExportField($this->activity_description);
						if ($this->activity_gender_target->Exportable) $Doc->ExportField($this->activity_gender_target);
						if ($this->no_of_persons_needed->Exportable) $Doc->ExportField($this->no_of_persons_needed);
						if ($this->no_of_hours->Exportable) $Doc->ExportField($this->no_of_hours);
						if ($this->mobile_phone->Exportable) $Doc->ExportField($this->mobile_phone);
						if ($this->pobox->Exportable) $Doc->ExportField($this->pobox);
						if ($this->admin_approval->Exportable) $Doc->ExportField($this->admin_approval);
						if ($this->admin_comment->Exportable) $Doc->ExportField($this->admin_comment);
						if ($this->email->Exportable) $Doc->ExportField($this->email);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'institutions_requests';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'institutions_requests';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'institutions_requests';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'institutions_requests';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
