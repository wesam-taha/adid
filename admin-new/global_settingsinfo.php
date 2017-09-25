<?php

// Global variable for table object
$global_settings = NULL;

//
// Table class for global_settings
//
class cglobal_settings extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $global_id;
	var $system_name_ar;
	var $system_name_en;
	var $contact_email;
	var $system_logo;
	var $contact_info_ar;
	var $contact_info_en;
	var $about_us_ar;
	var $about_us_en;
	var $twiiter;
	var $facebook;
	var $instagram;
	var $youtube;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'global_settings';
		$this->TableName = 'global_settings';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`global_settings`";
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

		// global_id
		$this->global_id = new cField('global_settings', 'global_settings', 'x_global_id', 'global_id', '`global_id`', '`global_id`', 3, -1, FALSE, '`global_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->global_id->Sortable = TRUE; // Allow sort
		$this->global_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['global_id'] = &$this->global_id;

		// system_name_ar
		$this->system_name_ar = new cField('global_settings', 'global_settings', 'x_system_name_ar', 'system_name_ar', '`system_name_ar`', '`system_name_ar`', 201, -1, FALSE, '`system_name_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->system_name_ar->Sortable = TRUE; // Allow sort
		$this->fields['system_name_ar'] = &$this->system_name_ar;

		// system_name_en
		$this->system_name_en = new cField('global_settings', 'global_settings', 'x_system_name_en', 'system_name_en', '`system_name_en`', '`system_name_en`', 201, -1, FALSE, '`system_name_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->system_name_en->Sortable = TRUE; // Allow sort
		$this->fields['system_name_en'] = &$this->system_name_en;

		// contact_email
		$this->contact_email = new cField('global_settings', 'global_settings', 'x_contact_email', 'contact_email', '`contact_email`', '`contact_email`', 201, -1, FALSE, '`contact_email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->contact_email->Sortable = TRUE; // Allow sort
		$this->fields['contact_email'] = &$this->contact_email;

		// system_logo
		$this->system_logo = new cField('global_settings', 'global_settings', 'x_system_logo', 'system_logo', '`system_logo`', '`system_logo`', 201, -1, TRUE, '`system_logo`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->system_logo->Sortable = TRUE; // Allow sort
		$this->fields['system_logo'] = &$this->system_logo;

		// contact_info_ar
		$this->contact_info_ar = new cField('global_settings', 'global_settings', 'x_contact_info_ar', 'contact_info_ar', '`contact_info_ar`', '`contact_info_ar`', 201, -1, FALSE, '`contact_info_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->contact_info_ar->Sortable = TRUE; // Allow sort
		$this->fields['contact_info_ar'] = &$this->contact_info_ar;

		// contact_info_en
		$this->contact_info_en = new cField('global_settings', 'global_settings', 'x_contact_info_en', 'contact_info_en', '`contact_info_en`', '`contact_info_en`', 201, -1, FALSE, '`contact_info_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->contact_info_en->Sortable = TRUE; // Allow sort
		$this->fields['contact_info_en'] = &$this->contact_info_en;

		// about_us_ar
		$this->about_us_ar = new cField('global_settings', 'global_settings', 'x_about_us_ar', 'about_us_ar', '`about_us_ar`', '`about_us_ar`', 201, -1, FALSE, '`about_us_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->about_us_ar->Sortable = TRUE; // Allow sort
		$this->fields['about_us_ar'] = &$this->about_us_ar;

		// about_us_en
		$this->about_us_en = new cField('global_settings', 'global_settings', 'x_about_us_en', 'about_us_en', '`about_us_en`', '`about_us_en`', 201, -1, FALSE, '`about_us_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->about_us_en->Sortable = TRUE; // Allow sort
		$this->fields['about_us_en'] = &$this->about_us_en;

		// twiiter
		$this->twiiter = new cField('global_settings', 'global_settings', 'x_twiiter', 'twiiter', '`twiiter`', '`twiiter`', 201, -1, FALSE, '`twiiter`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->twiiter->Sortable = TRUE; // Allow sort
		$this->fields['twiiter'] = &$this->twiiter;

		// facebook
		$this->facebook = new cField('global_settings', 'global_settings', 'x_facebook', 'facebook', '`facebook`', '`facebook`', 201, -1, FALSE, '`facebook`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->facebook->Sortable = TRUE; // Allow sort
		$this->fields['facebook'] = &$this->facebook;

		// instagram
		$this->instagram = new cField('global_settings', 'global_settings', 'x_instagram', 'instagram', '`instagram`', '`instagram`', 201, -1, FALSE, '`instagram`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->instagram->Sortable = TRUE; // Allow sort
		$this->fields['instagram'] = &$this->instagram;

		// youtube
		$this->youtube = new cField('global_settings', 'global_settings', 'x_youtube', 'youtube', '`youtube`', '`youtube`', 201, -1, FALSE, '`youtube`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->youtube->Sortable = TRUE; // Allow sort
		$this->fields['youtube'] = &$this->youtube;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`global_settings`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`global_id` DESC";
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
			$this->global_id->setDbValue($conn->Insert_ID());
			$rs['global_id'] = $this->global_id->DbValue;
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
			$fldname = 'global_id';
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
			if (array_key_exists('global_id', $rs))
				ew_AddFilter($where, ew_QuotedName('global_id', $this->DBID) . '=' . ew_QuotedValue($rs['global_id'], $this->global_id->FldDataType, $this->DBID));
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
		return "`global_id` = @global_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->global_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@global_id@", ew_AdjustSql($this->global_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "global_settingslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "global_settingsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "global_settingsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "global_settingsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "global_settingslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("global_settingsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("global_settingsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "global_settingsadd.php?" . $this->UrlParm($parm);
		else
			$url = "global_settingsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("global_settingsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("global_settingsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("global_settingsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "global_id:" . ew_VarToJson($this->global_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->global_id->CurrentValue)) {
			$sUrl .= "global_id=" . urlencode($this->global_id->CurrentValue);
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
			if ($isPost && isset($_POST["global_id"]))
				$arKeys[] = $_POST["global_id"];
			elseif (isset($_GET["global_id"]))
				$arKeys[] = $_GET["global_id"];
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
			$this->global_id->CurrentValue = $key;
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
		$this->global_id->setDbValue($rs->fields('global_id'));
		$this->system_name_ar->setDbValue($rs->fields('system_name_ar'));
		$this->system_name_en->setDbValue($rs->fields('system_name_en'));
		$this->contact_email->setDbValue($rs->fields('contact_email'));
		$this->system_logo->Upload->DbValue = $rs->fields('system_logo');
		$this->contact_info_ar->setDbValue($rs->fields('contact_info_ar'));
		$this->contact_info_en->setDbValue($rs->fields('contact_info_en'));
		$this->about_us_ar->setDbValue($rs->fields('about_us_ar'));
		$this->about_us_en->setDbValue($rs->fields('about_us_en'));
		$this->twiiter->setDbValue($rs->fields('twiiter'));
		$this->facebook->setDbValue($rs->fields('facebook'));
		$this->instagram->setDbValue($rs->fields('instagram'));
		$this->youtube->setDbValue($rs->fields('youtube'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// global_id
		// system_name_ar
		// system_name_en
		// contact_email
		// system_logo
		// contact_info_ar
		// contact_info_en
		// about_us_ar
		// about_us_en
		// twiiter
		// facebook
		// instagram
		// youtube
		// global_id

		$this->global_id->ViewValue = $this->global_id->CurrentValue;
		$this->global_id->ViewCustomAttributes = "";

		// system_name_ar
		$this->system_name_ar->ViewValue = $this->system_name_ar->CurrentValue;
		$this->system_name_ar->ViewCustomAttributes = "";

		// system_name_en
		$this->system_name_en->ViewValue = $this->system_name_en->CurrentValue;
		$this->system_name_en->ViewCustomAttributes = "";

		// contact_email
		$this->contact_email->ViewValue = $this->contact_email->CurrentValue;
		$this->contact_email->ViewCustomAttributes = "";

		// system_logo
		$this->system_logo->UploadPath = "../uploads";
		if (!ew_Empty($this->system_logo->Upload->DbValue)) {
			$this->system_logo->ImageWidth = 100;
			$this->system_logo->ImageHeight = 0;
			$this->system_logo->ImageAlt = $this->system_logo->FldAlt();
			$this->system_logo->ViewValue = $this->system_logo->Upload->DbValue;
		} else {
			$this->system_logo->ViewValue = "";
		}
		$this->system_logo->ViewCustomAttributes = "";

		// contact_info_ar
		$this->contact_info_ar->ViewValue = $this->contact_info_ar->CurrentValue;
		$this->contact_info_ar->ViewCustomAttributes = "";

		// contact_info_en
		$this->contact_info_en->ViewValue = $this->contact_info_en->CurrentValue;
		$this->contact_info_en->ViewCustomAttributes = "";

		// about_us_ar
		$this->about_us_ar->ViewValue = $this->about_us_ar->CurrentValue;
		$this->about_us_ar->ViewCustomAttributes = "";

		// about_us_en
		$this->about_us_en->ViewValue = $this->about_us_en->CurrentValue;
		$this->about_us_en->ViewCustomAttributes = "";

		// twiiter
		$this->twiiter->ViewValue = $this->twiiter->CurrentValue;
		$this->twiiter->ViewCustomAttributes = "";

		// facebook
		$this->facebook->ViewValue = $this->facebook->CurrentValue;
		$this->facebook->ViewCustomAttributes = "";

		// instagram
		$this->instagram->ViewValue = $this->instagram->CurrentValue;
		$this->instagram->ViewCustomAttributes = "";

		// youtube
		$this->youtube->ViewValue = $this->youtube->CurrentValue;
		$this->youtube->ViewCustomAttributes = "";

		// global_id
		$this->global_id->LinkCustomAttributes = "";
		$this->global_id->HrefValue = "";
		$this->global_id->TooltipValue = "";

		// system_name_ar
		$this->system_name_ar->LinkCustomAttributes = "";
		$this->system_name_ar->HrefValue = "";
		$this->system_name_ar->TooltipValue = "";

		// system_name_en
		$this->system_name_en->LinkCustomAttributes = "";
		$this->system_name_en->HrefValue = "";
		$this->system_name_en->TooltipValue = "";

		// contact_email
		$this->contact_email->LinkCustomAttributes = "";
		$this->contact_email->HrefValue = "";
		$this->contact_email->TooltipValue = "";

		// system_logo
		$this->system_logo->LinkCustomAttributes = "";
		$this->system_logo->UploadPath = "../uploads";
		if (!ew_Empty($this->system_logo->Upload->DbValue)) {
			$this->system_logo->HrefValue = ew_GetFileUploadUrl($this->system_logo, $this->system_logo->Upload->DbValue); // Add prefix/suffix
			$this->system_logo->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->system_logo->HrefValue = ew_FullUrl($this->system_logo->HrefValue, "href");
		} else {
			$this->system_logo->HrefValue = "";
		}
		$this->system_logo->HrefValue2 = $this->system_logo->UploadPath . $this->system_logo->Upload->DbValue;
		$this->system_logo->TooltipValue = "";
		if ($this->system_logo->UseColorbox) {
			if (ew_Empty($this->system_logo->TooltipValue))
				$this->system_logo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->system_logo->LinkAttrs["data-rel"] = "global_settings_x_system_logo";
			ew_AppendClass($this->system_logo->LinkAttrs["class"], "ewLightbox");
		}

		// contact_info_ar
		$this->contact_info_ar->LinkCustomAttributes = "";
		$this->contact_info_ar->HrefValue = "";
		$this->contact_info_ar->TooltipValue = "";

		// contact_info_en
		$this->contact_info_en->LinkCustomAttributes = "";
		$this->contact_info_en->HrefValue = "";
		$this->contact_info_en->TooltipValue = "";

		// about_us_ar
		$this->about_us_ar->LinkCustomAttributes = "";
		$this->about_us_ar->HrefValue = "";
		$this->about_us_ar->TooltipValue = "";

		// about_us_en
		$this->about_us_en->LinkCustomAttributes = "";
		$this->about_us_en->HrefValue = "";
		$this->about_us_en->TooltipValue = "";

		// twiiter
		$this->twiiter->LinkCustomAttributes = "";
		$this->twiiter->HrefValue = "";
		$this->twiiter->TooltipValue = "";

		// facebook
		$this->facebook->LinkCustomAttributes = "";
		$this->facebook->HrefValue = "";
		$this->facebook->TooltipValue = "";

		// instagram
		$this->instagram->LinkCustomAttributes = "";
		$this->instagram->HrefValue = "";
		$this->instagram->TooltipValue = "";

		// youtube
		$this->youtube->LinkCustomAttributes = "";
		$this->youtube->HrefValue = "";
		$this->youtube->TooltipValue = "";

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

		// global_id
		$this->global_id->EditAttrs["class"] = "form-control";
		$this->global_id->EditCustomAttributes = "";
		$this->global_id->EditValue = $this->global_id->CurrentValue;
		$this->global_id->ViewCustomAttributes = "";

		// system_name_ar
		$this->system_name_ar->EditAttrs["class"] = "form-control";
		$this->system_name_ar->EditCustomAttributes = "";
		$this->system_name_ar->EditValue = $this->system_name_ar->CurrentValue;
		$this->system_name_ar->PlaceHolder = ew_RemoveHtml($this->system_name_ar->FldCaption());

		// system_name_en
		$this->system_name_en->EditAttrs["class"] = "form-control";
		$this->system_name_en->EditCustomAttributes = "";
		$this->system_name_en->EditValue = $this->system_name_en->CurrentValue;
		$this->system_name_en->PlaceHolder = ew_RemoveHtml($this->system_name_en->FldCaption());

		// contact_email
		$this->contact_email->EditAttrs["class"] = "form-control";
		$this->contact_email->EditCustomAttributes = "";
		$this->contact_email->EditValue = $this->contact_email->CurrentValue;
		$this->contact_email->PlaceHolder = ew_RemoveHtml($this->contact_email->FldCaption());

		// system_logo
		$this->system_logo->EditAttrs["class"] = "form-control";
		$this->system_logo->EditCustomAttributes = "";
		$this->system_logo->UploadPath = "../uploads";
		if (!ew_Empty($this->system_logo->Upload->DbValue)) {
			$this->system_logo->ImageWidth = 100;
			$this->system_logo->ImageHeight = 0;
			$this->system_logo->ImageAlt = $this->system_logo->FldAlt();
			$this->system_logo->EditValue = $this->system_logo->Upload->DbValue;
		} else {
			$this->system_logo->EditValue = "";
		}
		if (!ew_Empty($this->system_logo->CurrentValue))
			$this->system_logo->Upload->FileName = $this->system_logo->CurrentValue;

		// contact_info_ar
		$this->contact_info_ar->EditAttrs["class"] = "form-control";
		$this->contact_info_ar->EditCustomAttributes = "";
		$this->contact_info_ar->EditValue = $this->contact_info_ar->CurrentValue;
		$this->contact_info_ar->PlaceHolder = ew_RemoveHtml($this->contact_info_ar->FldCaption());

		// contact_info_en
		$this->contact_info_en->EditAttrs["class"] = "form-control";
		$this->contact_info_en->EditCustomAttributes = "";
		$this->contact_info_en->EditValue = $this->contact_info_en->CurrentValue;
		$this->contact_info_en->PlaceHolder = ew_RemoveHtml($this->contact_info_en->FldCaption());

		// about_us_ar
		$this->about_us_ar->EditAttrs["class"] = "form-control";
		$this->about_us_ar->EditCustomAttributes = "";
		$this->about_us_ar->EditValue = $this->about_us_ar->CurrentValue;
		$this->about_us_ar->PlaceHolder = ew_RemoveHtml($this->about_us_ar->FldCaption());

		// about_us_en
		$this->about_us_en->EditAttrs["class"] = "form-control";
		$this->about_us_en->EditCustomAttributes = "";
		$this->about_us_en->EditValue = $this->about_us_en->CurrentValue;
		$this->about_us_en->PlaceHolder = ew_RemoveHtml($this->about_us_en->FldCaption());

		// twiiter
		$this->twiiter->EditAttrs["class"] = "form-control";
		$this->twiiter->EditCustomAttributes = "";
		$this->twiiter->EditValue = $this->twiiter->CurrentValue;
		$this->twiiter->PlaceHolder = ew_RemoveHtml($this->twiiter->FldCaption());

		// facebook
		$this->facebook->EditAttrs["class"] = "form-control";
		$this->facebook->EditCustomAttributes = "";
		$this->facebook->EditValue = $this->facebook->CurrentValue;
		$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

		// instagram
		$this->instagram->EditAttrs["class"] = "form-control";
		$this->instagram->EditCustomAttributes = "";
		$this->instagram->EditValue = $this->instagram->CurrentValue;
		$this->instagram->PlaceHolder = ew_RemoveHtml($this->instagram->FldCaption());

		// youtube
		$this->youtube->EditAttrs["class"] = "form-control";
		$this->youtube->EditCustomAttributes = "";
		$this->youtube->EditValue = $this->youtube->CurrentValue;
		$this->youtube->PlaceHolder = ew_RemoveHtml($this->youtube->FldCaption());

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
					if ($this->global_id->Exportable) $Doc->ExportCaption($this->global_id);
					if ($this->system_name_ar->Exportable) $Doc->ExportCaption($this->system_name_ar);
					if ($this->system_name_en->Exportable) $Doc->ExportCaption($this->system_name_en);
					if ($this->contact_email->Exportable) $Doc->ExportCaption($this->contact_email);
					if ($this->system_logo->Exportable) $Doc->ExportCaption($this->system_logo);
					if ($this->contact_info_ar->Exportable) $Doc->ExportCaption($this->contact_info_ar);
					if ($this->contact_info_en->Exportable) $Doc->ExportCaption($this->contact_info_en);
					if ($this->about_us_ar->Exportable) $Doc->ExportCaption($this->about_us_ar);
					if ($this->about_us_en->Exportable) $Doc->ExportCaption($this->about_us_en);
					if ($this->twiiter->Exportable) $Doc->ExportCaption($this->twiiter);
					if ($this->facebook->Exportable) $Doc->ExportCaption($this->facebook);
					if ($this->instagram->Exportable) $Doc->ExportCaption($this->instagram);
					if ($this->youtube->Exportable) $Doc->ExportCaption($this->youtube);
				} else {
					if ($this->global_id->Exportable) $Doc->ExportCaption($this->global_id);
					if ($this->system_name_ar->Exportable) $Doc->ExportCaption($this->system_name_ar);
					if ($this->system_name_en->Exportable) $Doc->ExportCaption($this->system_name_en);
					if ($this->contact_email->Exportable) $Doc->ExportCaption($this->contact_email);
					if ($this->system_logo->Exportable) $Doc->ExportCaption($this->system_logo);
					if ($this->contact_info_ar->Exportable) $Doc->ExportCaption($this->contact_info_ar);
					if ($this->contact_info_en->Exportable) $Doc->ExportCaption($this->contact_info_en);
					if ($this->about_us_ar->Exportable) $Doc->ExportCaption($this->about_us_ar);
					if ($this->about_us_en->Exportable) $Doc->ExportCaption($this->about_us_en);
					if ($this->twiiter->Exportable) $Doc->ExportCaption($this->twiiter);
					if ($this->facebook->Exportable) $Doc->ExportCaption($this->facebook);
					if ($this->instagram->Exportable) $Doc->ExportCaption($this->instagram);
					if ($this->youtube->Exportable) $Doc->ExportCaption($this->youtube);
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
						if ($this->global_id->Exportable) $Doc->ExportField($this->global_id);
						if ($this->system_name_ar->Exportable) $Doc->ExportField($this->system_name_ar);
						if ($this->system_name_en->Exportable) $Doc->ExportField($this->system_name_en);
						if ($this->contact_email->Exportable) $Doc->ExportField($this->contact_email);
						if ($this->system_logo->Exportable) $Doc->ExportField($this->system_logo);
						if ($this->contact_info_ar->Exportable) $Doc->ExportField($this->contact_info_ar);
						if ($this->contact_info_en->Exportable) $Doc->ExportField($this->contact_info_en);
						if ($this->about_us_ar->Exportable) $Doc->ExportField($this->about_us_ar);
						if ($this->about_us_en->Exportable) $Doc->ExportField($this->about_us_en);
						if ($this->twiiter->Exportable) $Doc->ExportField($this->twiiter);
						if ($this->facebook->Exportable) $Doc->ExportField($this->facebook);
						if ($this->instagram->Exportable) $Doc->ExportField($this->instagram);
						if ($this->youtube->Exportable) $Doc->ExportField($this->youtube);
					} else {
						if ($this->global_id->Exportable) $Doc->ExportField($this->global_id);
						if ($this->system_name_ar->Exportable) $Doc->ExportField($this->system_name_ar);
						if ($this->system_name_en->Exportable) $Doc->ExportField($this->system_name_en);
						if ($this->contact_email->Exportable) $Doc->ExportField($this->contact_email);
						if ($this->system_logo->Exportable) $Doc->ExportField($this->system_logo);
						if ($this->contact_info_ar->Exportable) $Doc->ExportField($this->contact_info_ar);
						if ($this->contact_info_en->Exportable) $Doc->ExportField($this->contact_info_en);
						if ($this->about_us_ar->Exportable) $Doc->ExportField($this->about_us_ar);
						if ($this->about_us_en->Exportable) $Doc->ExportField($this->about_us_en);
						if ($this->twiiter->Exportable) $Doc->ExportField($this->twiiter);
						if ($this->facebook->Exportable) $Doc->ExportField($this->facebook);
						if ($this->instagram->Exportable) $Doc->ExportField($this->instagram);
						if ($this->youtube->Exportable) $Doc->ExportField($this->youtube);
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
		$table = 'global_settings';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'global_settings';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['global_id'];

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
		$table = 'global_settings';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['global_id'];

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
		$table = 'global_settings';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['global_id'];

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
