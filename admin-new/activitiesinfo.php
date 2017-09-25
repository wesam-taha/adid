<?php

// Global variable for table object
$activities = NULL;

//
// Table class for activities
//
class cactivities extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $activity_id;
	var $activity_name_ar;
	var $activity_name_en;
	var $activity_start_date;
	var $activity_end_date;
	var $activity_time_ar;
	var $activity_time_en;
	var $activity_description_ar;
	var $activity_description_en;
	var $activity_persons;
	var $activity_hours;
	var $activity_city;
	var $activity_location_ar;
	var $activity_location_en;
	var $activity_location_map;
	var $activity_image;
	var $activity_organizer_ar;
	var $activity_organizer_en;
	var $activity_category_ar;
	var $activity_category_en;
	var $activity_type;
	var $activity_gender_target;
	var $activity_terms_and_conditions_ar;
	var $activity_terms_and_conditions_en;
	var $activity_active;
	var $leader_username;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'activities';
		$this->TableName = 'activities';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`activities`";
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

		// activity_id
		$this->activity_id = new cField('activities', 'activities', 'x_activity_id', 'activity_id', '`activity_id`', '`activity_id`', 3, -1, FALSE, '`activity_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->activity_id->Sortable = TRUE; // Allow sort
		$this->activity_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activity_id'] = &$this->activity_id;

		// activity_name_ar
		$this->activity_name_ar = new cField('activities', 'activities', 'x_activity_name_ar', 'activity_name_ar', '`activity_name_ar`', '`activity_name_ar`', 201, -1, FALSE, '`activity_name_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_name_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_name_ar'] = &$this->activity_name_ar;

		// activity_name_en
		$this->activity_name_en = new cField('activities', 'activities', 'x_activity_name_en', 'activity_name_en', '`activity_name_en`', '`activity_name_en`', 201, -1, FALSE, '`activity_name_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_name_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_name_en'] = &$this->activity_name_en;

		// activity_start_date
		$this->activity_start_date = new cField('activities', 'activities', 'x_activity_start_date', 'activity_start_date', '`activity_start_date`', ew_CastDateFieldForLike('`activity_start_date`', 0, "DB"), 133, 0, FALSE, '`activity_start_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_start_date->Sortable = TRUE; // Allow sort
		$this->activity_start_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['activity_start_date'] = &$this->activity_start_date;

		// activity_end_date
		$this->activity_end_date = new cField('activities', 'activities', 'x_activity_end_date', 'activity_end_date', '`activity_end_date`', ew_CastDateFieldForLike('`activity_end_date`', 0, "DB"), 133, 0, FALSE, '`activity_end_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_end_date->Sortable = TRUE; // Allow sort
		$this->activity_end_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['activity_end_date'] = &$this->activity_end_date;

		// activity_time_ar
		$this->activity_time_ar = new cField('activities', 'activities', 'x_activity_time_ar', 'activity_time_ar', '`activity_time_ar`', '`activity_time_ar`', 201, -1, FALSE, '`activity_time_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_time_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_time_ar'] = &$this->activity_time_ar;

		// activity_time_en
		$this->activity_time_en = new cField('activities', 'activities', 'x_activity_time_en', 'activity_time_en', '`activity_time_en`', '`activity_time_en`', 201, -1, FALSE, '`activity_time_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_time_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_time_en'] = &$this->activity_time_en;

		// activity_description_ar
		$this->activity_description_ar = new cField('activities', 'activities', 'x_activity_description_ar', 'activity_description_ar', '`activity_description_ar`', '`activity_description_ar`', 201, -1, FALSE, '`activity_description_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_description_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_description_ar'] = &$this->activity_description_ar;

		// activity_description_en
		$this->activity_description_en = new cField('activities', 'activities', 'x_activity_description_en', 'activity_description_en', '`activity_description_en`', '`activity_description_en`', 201, -1, FALSE, '`activity_description_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_description_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_description_en'] = &$this->activity_description_en;

		// activity_persons
		$this->activity_persons = new cField('activities', 'activities', 'x_activity_persons', 'activity_persons', '`activity_persons`', '`activity_persons`', 3, -1, FALSE, '`activity_persons`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_persons->Sortable = TRUE; // Allow sort
		$this->activity_persons->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activity_persons'] = &$this->activity_persons;

		// activity_hours
		$this->activity_hours = new cField('activities', 'activities', 'x_activity_hours', 'activity_hours', '`activity_hours`', '`activity_hours`', 3, -1, FALSE, '`activity_hours`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_hours->Sortable = TRUE; // Allow sort
		$this->activity_hours->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activity_hours'] = &$this->activity_hours;

		// activity_city
		$this->activity_city = new cField('activities', 'activities', 'x_activity_city', 'activity_city', '`activity_city`', '`activity_city`', 201, -1, FALSE, '`activity_city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->activity_city->Sortable = TRUE; // Allow sort
		$this->activity_city->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->activity_city->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->activity_city->OptionCount = 7;
		$this->fields['activity_city'] = &$this->activity_city;

		// activity_location_ar
		$this->activity_location_ar = new cField('activities', 'activities', 'x_activity_location_ar', 'activity_location_ar', '`activity_location_ar`', '`activity_location_ar`', 201, -1, FALSE, '`activity_location_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_location_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_location_ar'] = &$this->activity_location_ar;

		// activity_location_en
		$this->activity_location_en = new cField('activities', 'activities', 'x_activity_location_en', 'activity_location_en', '`activity_location_en`', '`activity_location_en`', 201, -1, FALSE, '`activity_location_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_location_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_location_en'] = &$this->activity_location_en;

		// activity_location_map
		$this->activity_location_map = new cField('activities', 'activities', 'x_activity_location_map', 'activity_location_map', '`activity_location_map`', '`activity_location_map`', 201, -1, FALSE, '`activity_location_map`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_location_map->Sortable = TRUE; // Allow sort
		$this->fields['activity_location_map'] = &$this->activity_location_map;

		// activity_image
		$this->activity_image = new cField('activities', 'activities', 'x_activity_image', 'activity_image', '`activity_image`', '`activity_image`', 201, -1, TRUE, '`activity_image`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->activity_image->Sortable = TRUE; // Allow sort
		$this->fields['activity_image'] = &$this->activity_image;

		// activity_organizer_ar
		$this->activity_organizer_ar = new cField('activities', 'activities', 'x_activity_organizer_ar', 'activity_organizer_ar', '`activity_organizer_ar`', '`activity_organizer_ar`', 201, -1, FALSE, '`activity_organizer_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_organizer_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_organizer_ar'] = &$this->activity_organizer_ar;

		// activity_organizer_en
		$this->activity_organizer_en = new cField('activities', 'activities', 'x_activity_organizer_en', 'activity_organizer_en', '`activity_organizer_en`', '`activity_organizer_en`', 201, -1, FALSE, '`activity_organizer_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_organizer_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_organizer_en'] = &$this->activity_organizer_en;

		// activity_category_ar
		$this->activity_category_ar = new cField('activities', 'activities', 'x_activity_category_ar', 'activity_category_ar', '`activity_category_ar`', '`activity_category_ar`', 201, -1, FALSE, '`activity_category_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_category_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_category_ar'] = &$this->activity_category_ar;

		// activity_category_en
		$this->activity_category_en = new cField('activities', 'activities', 'x_activity_category_en', 'activity_category_en', '`activity_category_en`', '`activity_category_en`', 201, -1, FALSE, '`activity_category_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->activity_category_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_category_en'] = &$this->activity_category_en;

		// activity_type
		$this->activity_type = new cField('activities', 'activities', 'x_activity_type', 'activity_type', '`activity_type`', '`activity_type`', 201, -1, FALSE, '`activity_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->activity_type->Sortable = TRUE; // Allow sort
		$this->activity_type->OptionCount = 2;
		$this->fields['activity_type'] = &$this->activity_type;

		// activity_gender_target
		$this->activity_gender_target = new cField('activities', 'activities', 'x_activity_gender_target', 'activity_gender_target', '`activity_gender_target`', '`activity_gender_target`', 201, -1, FALSE, '`activity_gender_target`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->activity_gender_target->Sortable = TRUE; // Allow sort
		$this->activity_gender_target->OptionCount = 4;
		$this->fields['activity_gender_target'] = &$this->activity_gender_target;

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar = new cField('activities', 'activities', 'x_activity_terms_and_conditions_ar', 'activity_terms_and_conditions_ar', '`activity_terms_and_conditions_ar`', '`activity_terms_and_conditions_ar`', 201, -1, FALSE, '`activity_terms_and_conditions_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_terms_and_conditions_ar->Sortable = TRUE; // Allow sort
		$this->fields['activity_terms_and_conditions_ar'] = &$this->activity_terms_and_conditions_ar;

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en = new cField('activities', 'activities', 'x_activity_terms_and_conditions_en', 'activity_terms_and_conditions_en', '`activity_terms_and_conditions_en`', '`activity_terms_and_conditions_en`', 201, -1, FALSE, '`activity_terms_and_conditions_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->activity_terms_and_conditions_en->Sortable = TRUE; // Allow sort
		$this->fields['activity_terms_and_conditions_en'] = &$this->activity_terms_and_conditions_en;

		// activity_active
		$this->activity_active = new cField('activities', 'activities', 'x_activity_active', 'activity_active', '`activity_active`', '`activity_active`', 3, -1, FALSE, '`activity_active`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->activity_active->Sortable = TRUE; // Allow sort
		$this->activity_active->OptionCount = 2;
		$this->activity_active->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['activity_active'] = &$this->activity_active;

		// leader_username
		$this->leader_username = new cField('activities', 'activities', 'x_leader_username', 'leader_username', '`leader_username`', '`leader_username`', 201, -1, FALSE, '`leader_username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->leader_username->Sortable = TRUE; // Allow sort
		$this->fields['leader_username'] = &$this->leader_username;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "registered_users") {
			$sDetailUrl = $GLOBALS["registered_users"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_activity_id=" . urlencode($this->activity_id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "activitieslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`activities`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`activity_id` DESC";
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
			$this->activity_id->setDbValue($conn->Insert_ID());
			$rs['activity_id'] = $this->activity_id->DbValue;
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

		// Cascade Update detail table 'registered_users'
		$bCascadeUpdate = FALSE;
		$rscascade = array();
		if (!is_null($rsold) && (isset($rs['activity_id']) && $rsold['activity_id'] <> $rs['activity_id'])) { // Update detail field 'activity_id'
			$bCascadeUpdate = TRUE;
			$rscascade['activity_id'] = $rs['activity_id']; 
		}
		if ($bCascadeUpdate) {
			if (!isset($GLOBALS["registered_users"])) $GLOBALS["registered_users"] = new cregistered_users();
			$rswrk = $GLOBALS["registered_users"]->LoadRs("`activity_id` = " . ew_QuotedValue($rsold['activity_id'], EW_DATATYPE_NUMBER, 'DB')); 
			while ($rswrk && !$rswrk->EOF) {
				$rskey = array();
				$fldname = 'id';
				$rskey[$fldname] = $rswrk->fields[$fldname];
				$rsdtlold = &$rswrk->fields;
				$rsdtlnew = array_merge($rsdtlold, $rscascade);

				// Call Row_Updating event
				$bUpdate = $GLOBALS["registered_users"]->Row_Updating($rsdtlold, $rsdtlnew);
				if ($bUpdate)
					$bUpdate = $GLOBALS["registered_users"]->Update($rscascade, $rskey, $rswrk->fields);
				if (!$bUpdate) return FALSE;

				// Call Row_Updated event
				$GLOBALS["registered_users"]->Row_Updated($rsdtlold, $rsdtlnew);
				$rswrk->MoveNext();
			}
		}
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'activity_id';
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
			if (array_key_exists('activity_id', $rs))
				ew_AddFilter($where, ew_QuotedName('activity_id', $this->DBID) . '=' . ew_QuotedValue($rs['activity_id'], $this->activity_id->FldDataType, $this->DBID));
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

		// Cascade delete detail table 'registered_users'
		if (!isset($GLOBALS["registered_users"])) $GLOBALS["registered_users"] = new cregistered_users();
		$rscascade = $GLOBALS["registered_users"]->LoadRs("`activity_id` = " . ew_QuotedValue($rs['activity_id'], EW_DATATYPE_NUMBER, "DB")); 
		$dtlrows = ($rscascade) ? $rscascade->GetRows() : array();

		// Call Row Deleting event
		foreach ($dtlrows as $dtlrow) {
			$bDelete = $GLOBALS["registered_users"]->Row_Deleting($dtlrow);
			if (!$bDelete) break;
		}
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$bDelete = $GLOBALS["registered_users"]->Delete($dtlrow); // Delete
				if ($bDelete === FALSE)
					break;
			}
		}

		// Call Row Deleted event
		if ($bDelete) {
			foreach ($dtlrows as $dtlrow) {
				$GLOBALS["registered_users"]->Row_Deleted($dtlrow);
			}
		}
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`activity_id` = @activity_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->activity_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@activity_id@", ew_AdjustSql($this->activity_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "activitieslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "activitiesview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "activitiesedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "activitiesadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "activitieslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("activitiesview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("activitiesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "activitiesadd.php?" . $this->UrlParm($parm);
		else
			$url = "activitiesadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("activitiesedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("activitiesedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("activitiesadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("activitiesadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("activitiesdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "activity_id:" . ew_VarToJson($this->activity_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->activity_id->CurrentValue)) {
			$sUrl .= "activity_id=" . urlencode($this->activity_id->CurrentValue);
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
			if ($isPost && isset($_POST["activity_id"]))
				$arKeys[] = $_POST["activity_id"];
			elseif (isset($_GET["activity_id"]))
				$arKeys[] = $_GET["activity_id"];
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
			$this->activity_id->CurrentValue = $key;
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
		$this->activity_id->setDbValue($rs->fields('activity_id'));
		$this->activity_name_ar->setDbValue($rs->fields('activity_name_ar'));
		$this->activity_name_en->setDbValue($rs->fields('activity_name_en'));
		$this->activity_start_date->setDbValue($rs->fields('activity_start_date'));
		$this->activity_end_date->setDbValue($rs->fields('activity_end_date'));
		$this->activity_time_ar->setDbValue($rs->fields('activity_time_ar'));
		$this->activity_time_en->setDbValue($rs->fields('activity_time_en'));
		$this->activity_description_ar->setDbValue($rs->fields('activity_description_ar'));
		$this->activity_description_en->setDbValue($rs->fields('activity_description_en'));
		$this->activity_persons->setDbValue($rs->fields('activity_persons'));
		$this->activity_hours->setDbValue($rs->fields('activity_hours'));
		$this->activity_city->setDbValue($rs->fields('activity_city'));
		$this->activity_location_ar->setDbValue($rs->fields('activity_location_ar'));
		$this->activity_location_en->setDbValue($rs->fields('activity_location_en'));
		$this->activity_location_map->setDbValue($rs->fields('activity_location_map'));
		$this->activity_image->Upload->DbValue = $rs->fields('activity_image');
		$this->activity_organizer_ar->setDbValue($rs->fields('activity_organizer_ar'));
		$this->activity_organizer_en->setDbValue($rs->fields('activity_organizer_en'));
		$this->activity_category_ar->setDbValue($rs->fields('activity_category_ar'));
		$this->activity_category_en->setDbValue($rs->fields('activity_category_en'));
		$this->activity_type->setDbValue($rs->fields('activity_type'));
		$this->activity_gender_target->setDbValue($rs->fields('activity_gender_target'));
		$this->activity_terms_and_conditions_ar->setDbValue($rs->fields('activity_terms_and_conditions_ar'));
		$this->activity_terms_and_conditions_en->setDbValue($rs->fields('activity_terms_and_conditions_en'));
		$this->activity_active->setDbValue($rs->fields('activity_active'));
		$this->leader_username->setDbValue($rs->fields('leader_username'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// activity_id
		// activity_name_ar
		// activity_name_en
		// activity_start_date
		// activity_end_date
		// activity_time_ar
		// activity_time_en
		// activity_description_ar

		$this->activity_description_ar->CellCssStyle = "white-space: nowrap;";

		// activity_description_en
		// activity_persons
		// activity_hours
		// activity_city
		// activity_location_ar
		// activity_location_en
		// activity_location_map
		// activity_image
		// activity_organizer_ar
		// activity_organizer_en
		// activity_category_ar
		// activity_category_en
		// activity_type
		// activity_gender_target
		// activity_terms_and_conditions_ar
		// activity_terms_and_conditions_en
		// activity_active
		// leader_username
		// activity_id

		$this->activity_id->ViewValue = $this->activity_id->CurrentValue;
		$this->activity_id->ViewCustomAttributes = "";

		// activity_name_ar
		$this->activity_name_ar->ViewValue = $this->activity_name_ar->CurrentValue;
		$this->activity_name_ar->ViewCustomAttributes = "";

		// activity_name_en
		$this->activity_name_en->ViewValue = $this->activity_name_en->CurrentValue;
		$this->activity_name_en->ViewCustomAttributes = "";

		// activity_start_date
		$this->activity_start_date->ViewValue = $this->activity_start_date->CurrentValue;
		$this->activity_start_date->ViewValue = ew_FormatDateTime($this->activity_start_date->ViewValue, 0);
		$this->activity_start_date->ViewCustomAttributes = "";

		// activity_end_date
		$this->activity_end_date->ViewValue = $this->activity_end_date->CurrentValue;
		$this->activity_end_date->ViewValue = ew_FormatDateTime($this->activity_end_date->ViewValue, 0);
		$this->activity_end_date->ViewCustomAttributes = "";

		// activity_time_ar
		$this->activity_time_ar->ViewValue = $this->activity_time_ar->CurrentValue;
		$this->activity_time_ar->ViewCustomAttributes = "";

		// activity_time_en
		$this->activity_time_en->ViewValue = $this->activity_time_en->CurrentValue;
		$this->activity_time_en->ViewCustomAttributes = "";

		// activity_description_ar
		$this->activity_description_ar->ViewValue = $this->activity_description_ar->CurrentValue;
		$this->activity_description_ar->ViewCustomAttributes = "";

		// activity_description_en
		$this->activity_description_en->ViewValue = $this->activity_description_en->CurrentValue;
		$this->activity_description_en->ViewCustomAttributes = "";

		// activity_persons
		$this->activity_persons->ViewValue = $this->activity_persons->CurrentValue;
		$this->activity_persons->ViewCustomAttributes = "";

		// activity_hours
		$this->activity_hours->ViewValue = $this->activity_hours->CurrentValue;
		$this->activity_hours->ViewCustomAttributes = "";

		// activity_city
		if (strval($this->activity_city->CurrentValue) <> "") {
			$this->activity_city->ViewValue = $this->activity_city->OptionCaption($this->activity_city->CurrentValue);
		} else {
			$this->activity_city->ViewValue = NULL;
		}
		$this->activity_city->ViewCustomAttributes = "";

		// activity_location_ar
		$this->activity_location_ar->ViewValue = $this->activity_location_ar->CurrentValue;
		$this->activity_location_ar->ViewCustomAttributes = "";

		// activity_location_en
		$this->activity_location_en->ViewValue = $this->activity_location_en->CurrentValue;
		$this->activity_location_en->ViewCustomAttributes = "";

		// activity_location_map
		$this->activity_location_map->ViewValue = $this->activity_location_map->CurrentValue;
		$this->activity_location_map->ViewCustomAttributes = "";

		// activity_image
		$this->activity_image->UploadPath = "../images";
		if (!ew_Empty($this->activity_image->Upload->DbValue)) {
			$this->activity_image->ImageWidth = 100;
			$this->activity_image->ImageHeight = 0;
			$this->activity_image->ImageAlt = $this->activity_image->FldAlt();
			$this->activity_image->ViewValue = $this->activity_image->Upload->DbValue;
		} else {
			$this->activity_image->ViewValue = "";
		}
		$this->activity_image->ViewCustomAttributes = "";

		// activity_organizer_ar
		$this->activity_organizer_ar->ViewValue = $this->activity_organizer_ar->CurrentValue;
		$this->activity_organizer_ar->ViewCustomAttributes = "";

		// activity_organizer_en
		$this->activity_organizer_en->ViewValue = $this->activity_organizer_en->CurrentValue;
		$this->activity_organizer_en->ViewCustomAttributes = "";

		// activity_category_ar
		$this->activity_category_ar->ViewValue = $this->activity_category_ar->CurrentValue;
		$this->activity_category_ar->ViewCustomAttributes = "";

		// activity_category_en
		$this->activity_category_en->ViewValue = $this->activity_category_en->CurrentValue;
		$this->activity_category_en->ViewCustomAttributes = "";

		// activity_type
		if (strval($this->activity_type->CurrentValue) <> "") {
			$this->activity_type->ViewValue = $this->activity_type->OptionCaption($this->activity_type->CurrentValue);
		} else {
			$this->activity_type->ViewValue = NULL;
		}
		$this->activity_type->ViewCustomAttributes = "";

		// activity_gender_target
		if (strval($this->activity_gender_target->CurrentValue) <> "") {
			$this->activity_gender_target->ViewValue = $this->activity_gender_target->OptionCaption($this->activity_gender_target->CurrentValue);
		} else {
			$this->activity_gender_target->ViewValue = NULL;
		}
		$this->activity_gender_target->ViewCustomAttributes = "";

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->ViewValue = $this->activity_terms_and_conditions_ar->CurrentValue;
		$this->activity_terms_and_conditions_ar->ViewCustomAttributes = "";

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->ViewValue = $this->activity_terms_and_conditions_en->CurrentValue;
		$this->activity_terms_and_conditions_en->ViewCustomAttributes = "";

		// activity_active
		if (strval($this->activity_active->CurrentValue) <> "") {
			$this->activity_active->ViewValue = $this->activity_active->OptionCaption($this->activity_active->CurrentValue);
		} else {
			$this->activity_active->ViewValue = NULL;
		}
		$this->activity_active->ViewCustomAttributes = "";

		// leader_username
		$this->leader_username->ViewValue = $this->leader_username->CurrentValue;
		if (strval($this->leader_username->CurrentValue) <> "") {
			$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->leader_username->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->leader_username->LookupFilters = array("dx1" => '`full_name_ar`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->leader_username, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->leader_username->ViewValue = $this->leader_username->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->leader_username->ViewValue = $this->leader_username->CurrentValue;
			}
		} else {
			$this->leader_username->ViewValue = NULL;
		}
		$this->leader_username->ViewCustomAttributes = "";

		// activity_id
		$this->activity_id->LinkCustomAttributes = "";
		$this->activity_id->HrefValue = "";
		$this->activity_id->TooltipValue = "";

		// activity_name_ar
		$this->activity_name_ar->LinkCustomAttributes = "";
		$this->activity_name_ar->HrefValue = "";
		$this->activity_name_ar->TooltipValue = "";

		// activity_name_en
		$this->activity_name_en->LinkCustomAttributes = "";
		$this->activity_name_en->HrefValue = "";
		$this->activity_name_en->TooltipValue = "";

		// activity_start_date
		$this->activity_start_date->LinkCustomAttributes = "";
		$this->activity_start_date->HrefValue = "";
		$this->activity_start_date->TooltipValue = "";

		// activity_end_date
		$this->activity_end_date->LinkCustomAttributes = "";
		$this->activity_end_date->HrefValue = "";
		$this->activity_end_date->TooltipValue = "";

		// activity_time_ar
		$this->activity_time_ar->LinkCustomAttributes = "";
		$this->activity_time_ar->HrefValue = "";
		$this->activity_time_ar->TooltipValue = "";

		// activity_time_en
		$this->activity_time_en->LinkCustomAttributes = "";
		$this->activity_time_en->HrefValue = "";
		$this->activity_time_en->TooltipValue = "";

		// activity_description_ar
		$this->activity_description_ar->LinkCustomAttributes = "";
		$this->activity_description_ar->HrefValue = "";
		$this->activity_description_ar->TooltipValue = "";

		// activity_description_en
		$this->activity_description_en->LinkCustomAttributes = "";
		$this->activity_description_en->HrefValue = "";
		$this->activity_description_en->TooltipValue = "";

		// activity_persons
		$this->activity_persons->LinkCustomAttributes = "";
		$this->activity_persons->HrefValue = "";
		$this->activity_persons->TooltipValue = "";

		// activity_hours
		$this->activity_hours->LinkCustomAttributes = "";
		$this->activity_hours->HrefValue = "";
		$this->activity_hours->TooltipValue = "";

		// activity_city
		$this->activity_city->LinkCustomAttributes = "";
		$this->activity_city->HrefValue = "";
		$this->activity_city->TooltipValue = "";

		// activity_location_ar
		$this->activity_location_ar->LinkCustomAttributes = "";
		$this->activity_location_ar->HrefValue = "";
		$this->activity_location_ar->TooltipValue = "";

		// activity_location_en
		$this->activity_location_en->LinkCustomAttributes = "";
		$this->activity_location_en->HrefValue = "";
		$this->activity_location_en->TooltipValue = "";

		// activity_location_map
		$this->activity_location_map->LinkCustomAttributes = "";
		$this->activity_location_map->HrefValue = "";
		$this->activity_location_map->TooltipValue = "";

		// activity_image
		$this->activity_image->LinkCustomAttributes = "";
		$this->activity_image->UploadPath = "../images";
		if (!ew_Empty($this->activity_image->Upload->DbValue)) {
			$this->activity_image->HrefValue = ew_GetFileUploadUrl($this->activity_image, $this->activity_image->Upload->DbValue); // Add prefix/suffix
			$this->activity_image->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->activity_image->HrefValue = ew_FullUrl($this->activity_image->HrefValue, "href");
		} else {
			$this->activity_image->HrefValue = "";
		}
		$this->activity_image->HrefValue2 = $this->activity_image->UploadPath . $this->activity_image->Upload->DbValue;
		$this->activity_image->TooltipValue = "";
		if ($this->activity_image->UseColorbox) {
			if (ew_Empty($this->activity_image->TooltipValue))
				$this->activity_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->activity_image->LinkAttrs["data-rel"] = "activities_x_activity_image";
			ew_AppendClass($this->activity_image->LinkAttrs["class"], "ewLightbox");
		}

		// activity_organizer_ar
		$this->activity_organizer_ar->LinkCustomAttributes = "";
		$this->activity_organizer_ar->HrefValue = "";
		$this->activity_organizer_ar->TooltipValue = "";

		// activity_organizer_en
		$this->activity_organizer_en->LinkCustomAttributes = "";
		$this->activity_organizer_en->HrefValue = "";
		$this->activity_organizer_en->TooltipValue = "";

		// activity_category_ar
		$this->activity_category_ar->LinkCustomAttributes = "";
		$this->activity_category_ar->HrefValue = "";
		$this->activity_category_ar->TooltipValue = "";

		// activity_category_en
		$this->activity_category_en->LinkCustomAttributes = "";
		$this->activity_category_en->HrefValue = "";
		$this->activity_category_en->TooltipValue = "";

		// activity_type
		$this->activity_type->LinkCustomAttributes = "";
		$this->activity_type->HrefValue = "";
		$this->activity_type->TooltipValue = "";

		// activity_gender_target
		$this->activity_gender_target->LinkCustomAttributes = "";
		$this->activity_gender_target->HrefValue = "";
		$this->activity_gender_target->TooltipValue = "";

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->LinkCustomAttributes = "";
		$this->activity_terms_and_conditions_ar->HrefValue = "";
		$this->activity_terms_and_conditions_ar->TooltipValue = "";

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->LinkCustomAttributes = "";
		$this->activity_terms_and_conditions_en->HrefValue = "";
		$this->activity_terms_and_conditions_en->TooltipValue = "";

		// activity_active
		$this->activity_active->LinkCustomAttributes = "";
		$this->activity_active->HrefValue = "";
		$this->activity_active->TooltipValue = "";

		// leader_username
		$this->leader_username->LinkCustomAttributes = "";
		$this->leader_username->HrefValue = "";
		$this->leader_username->TooltipValue = "";

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

		// activity_id
		$this->activity_id->EditAttrs["class"] = "form-control";
		$this->activity_id->EditCustomAttributes = "";
		$this->activity_id->EditValue = $this->activity_id->CurrentValue;
		$this->activity_id->ViewCustomAttributes = "";

		// activity_name_ar
		$this->activity_name_ar->EditAttrs["class"] = "form-control";
		$this->activity_name_ar->EditCustomAttributes = "";
		$this->activity_name_ar->EditValue = $this->activity_name_ar->CurrentValue;
		$this->activity_name_ar->PlaceHolder = ew_RemoveHtml($this->activity_name_ar->FldCaption());

		// activity_name_en
		$this->activity_name_en->EditAttrs["class"] = "form-control";
		$this->activity_name_en->EditCustomAttributes = "";
		$this->activity_name_en->EditValue = $this->activity_name_en->CurrentValue;
		$this->activity_name_en->PlaceHolder = ew_RemoveHtml($this->activity_name_en->FldCaption());

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

		// activity_time_ar
		$this->activity_time_ar->EditAttrs["class"] = "form-control";
		$this->activity_time_ar->EditCustomAttributes = "";
		$this->activity_time_ar->EditValue = $this->activity_time_ar->CurrentValue;
		$this->activity_time_ar->PlaceHolder = ew_RemoveHtml($this->activity_time_ar->FldCaption());

		// activity_time_en
		$this->activity_time_en->EditAttrs["class"] = "form-control";
		$this->activity_time_en->EditCustomAttributes = "";
		$this->activity_time_en->EditValue = $this->activity_time_en->CurrentValue;
		$this->activity_time_en->PlaceHolder = ew_RemoveHtml($this->activity_time_en->FldCaption());

		// activity_description_ar
		$this->activity_description_ar->EditAttrs["class"] = "form-control";
		$this->activity_description_ar->EditCustomAttributes = "";
		$this->activity_description_ar->EditValue = $this->activity_description_ar->CurrentValue;
		$this->activity_description_ar->PlaceHolder = ew_RemoveHtml($this->activity_description_ar->FldCaption());

		// activity_description_en
		$this->activity_description_en->EditAttrs["class"] = "form-control";
		$this->activity_description_en->EditCustomAttributes = "";
		$this->activity_description_en->EditValue = $this->activity_description_en->CurrentValue;
		$this->activity_description_en->PlaceHolder = ew_RemoveHtml($this->activity_description_en->FldCaption());

		// activity_persons
		$this->activity_persons->EditAttrs["class"] = "form-control";
		$this->activity_persons->EditCustomAttributes = "";
		$this->activity_persons->EditValue = $this->activity_persons->CurrentValue;
		$this->activity_persons->PlaceHolder = ew_RemoveHtml($this->activity_persons->FldCaption());

		// activity_hours
		$this->activity_hours->EditAttrs["class"] = "form-control";
		$this->activity_hours->EditCustomAttributes = "";
		$this->activity_hours->EditValue = $this->activity_hours->CurrentValue;
		$this->activity_hours->PlaceHolder = ew_RemoveHtml($this->activity_hours->FldCaption());

		// activity_city
		$this->activity_city->EditAttrs["class"] = "form-control";
		$this->activity_city->EditCustomAttributes = "";
		$this->activity_city->EditValue = $this->activity_city->Options(TRUE);

		// activity_location_ar
		$this->activity_location_ar->EditAttrs["class"] = "form-control";
		$this->activity_location_ar->EditCustomAttributes = "";
		$this->activity_location_ar->EditValue = $this->activity_location_ar->CurrentValue;
		$this->activity_location_ar->PlaceHolder = ew_RemoveHtml($this->activity_location_ar->FldCaption());

		// activity_location_en
		$this->activity_location_en->EditAttrs["class"] = "form-control";
		$this->activity_location_en->EditCustomAttributes = "";
		$this->activity_location_en->EditValue = $this->activity_location_en->CurrentValue;
		$this->activity_location_en->PlaceHolder = ew_RemoveHtml($this->activity_location_en->FldCaption());

		// activity_location_map
		$this->activity_location_map->EditAttrs["class"] = "form-control";
		$this->activity_location_map->EditCustomAttributes = "";
		$this->activity_location_map->EditValue = $this->activity_location_map->CurrentValue;
		$this->activity_location_map->PlaceHolder = ew_RemoveHtml($this->activity_location_map->FldCaption());

		// activity_image
		$this->activity_image->EditAttrs["class"] = "form-control";
		$this->activity_image->EditCustomAttributes = "";
		$this->activity_image->UploadPath = "../images";
		if (!ew_Empty($this->activity_image->Upload->DbValue)) {
			$this->activity_image->ImageWidth = 100;
			$this->activity_image->ImageHeight = 0;
			$this->activity_image->ImageAlt = $this->activity_image->FldAlt();
			$this->activity_image->EditValue = $this->activity_image->Upload->DbValue;
		} else {
			$this->activity_image->EditValue = "";
		}
		if (!ew_Empty($this->activity_image->CurrentValue))
			$this->activity_image->Upload->FileName = $this->activity_image->CurrentValue;

		// activity_organizer_ar
		$this->activity_organizer_ar->EditAttrs["class"] = "form-control";
		$this->activity_organizer_ar->EditCustomAttributes = "";
		$this->activity_organizer_ar->EditValue = $this->activity_organizer_ar->CurrentValue;
		$this->activity_organizer_ar->PlaceHolder = ew_RemoveHtml($this->activity_organizer_ar->FldCaption());

		// activity_organizer_en
		$this->activity_organizer_en->EditAttrs["class"] = "form-control";
		$this->activity_organizer_en->EditCustomAttributes = "";
		$this->activity_organizer_en->EditValue = $this->activity_organizer_en->CurrentValue;
		$this->activity_organizer_en->PlaceHolder = ew_RemoveHtml($this->activity_organizer_en->FldCaption());

		// activity_category_ar
		$this->activity_category_ar->EditAttrs["class"] = "form-control";
		$this->activity_category_ar->EditCustomAttributes = "";
		$this->activity_category_ar->EditValue = $this->activity_category_ar->CurrentValue;
		$this->activity_category_ar->PlaceHolder = ew_RemoveHtml($this->activity_category_ar->FldCaption());

		// activity_category_en
		$this->activity_category_en->EditAttrs["class"] = "form-control";
		$this->activity_category_en->EditCustomAttributes = "";
		$this->activity_category_en->EditValue = $this->activity_category_en->CurrentValue;
		$this->activity_category_en->PlaceHolder = ew_RemoveHtml($this->activity_category_en->FldCaption());

		// activity_type
		$this->activity_type->EditCustomAttributes = "";
		$this->activity_type->EditValue = $this->activity_type->Options(FALSE);

		// activity_gender_target
		$this->activity_gender_target->EditCustomAttributes = "";
		$this->activity_gender_target->EditValue = $this->activity_gender_target->Options(FALSE);

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->EditAttrs["class"] = "form-control";
		$this->activity_terms_and_conditions_ar->EditCustomAttributes = "";
		$this->activity_terms_and_conditions_ar->EditValue = $this->activity_terms_and_conditions_ar->CurrentValue;
		$this->activity_terms_and_conditions_ar->PlaceHolder = ew_RemoveHtml($this->activity_terms_and_conditions_ar->FldCaption());

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->EditAttrs["class"] = "form-control";
		$this->activity_terms_and_conditions_en->EditCustomAttributes = "";
		$this->activity_terms_and_conditions_en->EditValue = $this->activity_terms_and_conditions_en->CurrentValue;
		$this->activity_terms_and_conditions_en->PlaceHolder = ew_RemoveHtml($this->activity_terms_and_conditions_en->FldCaption());

		// activity_active
		$this->activity_active->EditCustomAttributes = "";
		$this->activity_active->EditValue = $this->activity_active->Options(FALSE);

		// leader_username
		$this->leader_username->EditAttrs["class"] = "form-control";
		$this->leader_username->EditCustomAttributes = "";
		$this->leader_username->EditValue = $this->leader_username->CurrentValue;
		$this->leader_username->PlaceHolder = ew_RemoveHtml($this->leader_username->FldCaption());

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
					if ($this->activity_id->Exportable) $Doc->ExportCaption($this->activity_id);
					if ($this->activity_name_ar->Exportable) $Doc->ExportCaption($this->activity_name_ar);
					if ($this->activity_name_en->Exportable) $Doc->ExportCaption($this->activity_name_en);
					if ($this->activity_start_date->Exportable) $Doc->ExportCaption($this->activity_start_date);
					if ($this->activity_end_date->Exportable) $Doc->ExportCaption($this->activity_end_date);
					if ($this->activity_time_ar->Exportable) $Doc->ExportCaption($this->activity_time_ar);
					if ($this->activity_time_en->Exportable) $Doc->ExportCaption($this->activity_time_en);
					if ($this->activity_description_ar->Exportable) $Doc->ExportCaption($this->activity_description_ar);
					if ($this->activity_description_en->Exportable) $Doc->ExportCaption($this->activity_description_en);
					if ($this->activity_persons->Exportable) $Doc->ExportCaption($this->activity_persons);
					if ($this->activity_hours->Exportable) $Doc->ExportCaption($this->activity_hours);
					if ($this->activity_city->Exportable) $Doc->ExportCaption($this->activity_city);
					if ($this->activity_location_ar->Exportable) $Doc->ExportCaption($this->activity_location_ar);
					if ($this->activity_location_en->Exportable) $Doc->ExportCaption($this->activity_location_en);
					if ($this->activity_location_map->Exportable) $Doc->ExportCaption($this->activity_location_map);
					if ($this->activity_image->Exportable) $Doc->ExportCaption($this->activity_image);
					if ($this->activity_organizer_ar->Exportable) $Doc->ExportCaption($this->activity_organizer_ar);
					if ($this->activity_organizer_en->Exportable) $Doc->ExportCaption($this->activity_organizer_en);
					if ($this->activity_category_ar->Exportable) $Doc->ExportCaption($this->activity_category_ar);
					if ($this->activity_category_en->Exportable) $Doc->ExportCaption($this->activity_category_en);
					if ($this->activity_type->Exportable) $Doc->ExportCaption($this->activity_type);
					if ($this->activity_gender_target->Exportable) $Doc->ExportCaption($this->activity_gender_target);
					if ($this->activity_terms_and_conditions_ar->Exportable) $Doc->ExportCaption($this->activity_terms_and_conditions_ar);
					if ($this->activity_terms_and_conditions_en->Exportable) $Doc->ExportCaption($this->activity_terms_and_conditions_en);
					if ($this->activity_active->Exportable) $Doc->ExportCaption($this->activity_active);
					if ($this->leader_username->Exportable) $Doc->ExportCaption($this->leader_username);
				} else {
					if ($this->activity_id->Exportable) $Doc->ExportCaption($this->activity_id);
					if ($this->activity_name_ar->Exportable) $Doc->ExportCaption($this->activity_name_ar);
					if ($this->activity_name_en->Exportable) $Doc->ExportCaption($this->activity_name_en);
					if ($this->activity_start_date->Exportable) $Doc->ExportCaption($this->activity_start_date);
					if ($this->activity_end_date->Exportable) $Doc->ExportCaption($this->activity_end_date);
					if ($this->activity_time_ar->Exportable) $Doc->ExportCaption($this->activity_time_ar);
					if ($this->activity_time_en->Exportable) $Doc->ExportCaption($this->activity_time_en);
					if ($this->activity_description_ar->Exportable) $Doc->ExportCaption($this->activity_description_ar);
					if ($this->activity_description_en->Exportable) $Doc->ExportCaption($this->activity_description_en);
					if ($this->activity_persons->Exportable) $Doc->ExportCaption($this->activity_persons);
					if ($this->activity_hours->Exportable) $Doc->ExportCaption($this->activity_hours);
					if ($this->activity_city->Exportable) $Doc->ExportCaption($this->activity_city);
					if ($this->activity_location_ar->Exportable) $Doc->ExportCaption($this->activity_location_ar);
					if ($this->activity_location_en->Exportable) $Doc->ExportCaption($this->activity_location_en);
					if ($this->activity_location_map->Exportable) $Doc->ExportCaption($this->activity_location_map);
					if ($this->activity_image->Exportable) $Doc->ExportCaption($this->activity_image);
					if ($this->activity_organizer_ar->Exportable) $Doc->ExportCaption($this->activity_organizer_ar);
					if ($this->activity_organizer_en->Exportable) $Doc->ExportCaption($this->activity_organizer_en);
					if ($this->activity_category_ar->Exportable) $Doc->ExportCaption($this->activity_category_ar);
					if ($this->activity_category_en->Exportable) $Doc->ExportCaption($this->activity_category_en);
					if ($this->activity_type->Exportable) $Doc->ExportCaption($this->activity_type);
					if ($this->activity_gender_target->Exportable) $Doc->ExportCaption($this->activity_gender_target);
					if ($this->activity_terms_and_conditions_ar->Exportable) $Doc->ExportCaption($this->activity_terms_and_conditions_ar);
					if ($this->activity_terms_and_conditions_en->Exportable) $Doc->ExportCaption($this->activity_terms_and_conditions_en);
					if ($this->activity_active->Exportable) $Doc->ExportCaption($this->activity_active);
					if ($this->leader_username->Exportable) $Doc->ExportCaption($this->leader_username);
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
						if ($this->activity_id->Exportable) $Doc->ExportField($this->activity_id);
						if ($this->activity_name_ar->Exportable) $Doc->ExportField($this->activity_name_ar);
						if ($this->activity_name_en->Exportable) $Doc->ExportField($this->activity_name_en);
						if ($this->activity_start_date->Exportable) $Doc->ExportField($this->activity_start_date);
						if ($this->activity_end_date->Exportable) $Doc->ExportField($this->activity_end_date);
						if ($this->activity_time_ar->Exportable) $Doc->ExportField($this->activity_time_ar);
						if ($this->activity_time_en->Exportable) $Doc->ExportField($this->activity_time_en);
						if ($this->activity_description_ar->Exportable) $Doc->ExportField($this->activity_description_ar);
						if ($this->activity_description_en->Exportable) $Doc->ExportField($this->activity_description_en);
						if ($this->activity_persons->Exportable) $Doc->ExportField($this->activity_persons);
						if ($this->activity_hours->Exportable) $Doc->ExportField($this->activity_hours);
						if ($this->activity_city->Exportable) $Doc->ExportField($this->activity_city);
						if ($this->activity_location_ar->Exportable) $Doc->ExportField($this->activity_location_ar);
						if ($this->activity_location_en->Exportable) $Doc->ExportField($this->activity_location_en);
						if ($this->activity_location_map->Exportable) $Doc->ExportField($this->activity_location_map);
						if ($this->activity_image->Exportable) $Doc->ExportField($this->activity_image);
						if ($this->activity_organizer_ar->Exportable) $Doc->ExportField($this->activity_organizer_ar);
						if ($this->activity_organizer_en->Exportable) $Doc->ExportField($this->activity_organizer_en);
						if ($this->activity_category_ar->Exportable) $Doc->ExportField($this->activity_category_ar);
						if ($this->activity_category_en->Exportable) $Doc->ExportField($this->activity_category_en);
						if ($this->activity_type->Exportable) $Doc->ExportField($this->activity_type);
						if ($this->activity_gender_target->Exportable) $Doc->ExportField($this->activity_gender_target);
						if ($this->activity_terms_and_conditions_ar->Exportable) $Doc->ExportField($this->activity_terms_and_conditions_ar);
						if ($this->activity_terms_and_conditions_en->Exportable) $Doc->ExportField($this->activity_terms_and_conditions_en);
						if ($this->activity_active->Exportable) $Doc->ExportField($this->activity_active);
						if ($this->leader_username->Exportable) $Doc->ExportField($this->leader_username);
					} else {
						if ($this->activity_id->Exportable) $Doc->ExportField($this->activity_id);
						if ($this->activity_name_ar->Exportable) $Doc->ExportField($this->activity_name_ar);
						if ($this->activity_name_en->Exportable) $Doc->ExportField($this->activity_name_en);
						if ($this->activity_start_date->Exportable) $Doc->ExportField($this->activity_start_date);
						if ($this->activity_end_date->Exportable) $Doc->ExportField($this->activity_end_date);
						if ($this->activity_time_ar->Exportable) $Doc->ExportField($this->activity_time_ar);
						if ($this->activity_time_en->Exportable) $Doc->ExportField($this->activity_time_en);
						if ($this->activity_description_ar->Exportable) $Doc->ExportField($this->activity_description_ar);
						if ($this->activity_description_en->Exportable) $Doc->ExportField($this->activity_description_en);
						if ($this->activity_persons->Exportable) $Doc->ExportField($this->activity_persons);
						if ($this->activity_hours->Exportable) $Doc->ExportField($this->activity_hours);
						if ($this->activity_city->Exportable) $Doc->ExportField($this->activity_city);
						if ($this->activity_location_ar->Exportable) $Doc->ExportField($this->activity_location_ar);
						if ($this->activity_location_en->Exportable) $Doc->ExportField($this->activity_location_en);
						if ($this->activity_location_map->Exportable) $Doc->ExportField($this->activity_location_map);
						if ($this->activity_image->Exportable) $Doc->ExportField($this->activity_image);
						if ($this->activity_organizer_ar->Exportable) $Doc->ExportField($this->activity_organizer_ar);
						if ($this->activity_organizer_en->Exportable) $Doc->ExportField($this->activity_organizer_en);
						if ($this->activity_category_ar->Exportable) $Doc->ExportField($this->activity_category_ar);
						if ($this->activity_category_en->Exportable) $Doc->ExportField($this->activity_category_en);
						if ($this->activity_type->Exportable) $Doc->ExportField($this->activity_type);
						if ($this->activity_gender_target->Exportable) $Doc->ExportField($this->activity_gender_target);
						if ($this->activity_terms_and_conditions_ar->Exportable) $Doc->ExportField($this->activity_terms_and_conditions_ar);
						if ($this->activity_terms_and_conditions_en->Exportable) $Doc->ExportField($this->activity_terms_and_conditions_en);
						if ($this->activity_active->Exportable) $Doc->ExportField($this->activity_active);
						if ($this->leader_username->Exportable) $Doc->ExportField($this->leader_username);
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
		$table = 'activities';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'activities';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['activity_id'];

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
		$table = 'activities';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['activity_id'];

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
		$table = 'activities';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['activity_id'];

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
