<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$users_delete = NULL; // Initialize page object first

class cusers_delete extends cusers {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_delete';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'users', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (management)
		if (!isset($UserTable)) {
			$UserTable = new cmanagement();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("userslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->user_id->SetVisibility();
		$this->user_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->full_name_ar->SetVisibility();
		$this->full_name_en->SetVisibility();
		$this->_email->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->security_approval->SetVisibility();
		$this->security_comment->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $users;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($users);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("userslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in users class, usersinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("userslist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->user_id->setDbValue($row['user_id']);
		$this->group_id->setDbValue($row['group_id']);
		$this->full_name_ar->setDbValue($row['full_name_ar']);
		$this->full_name_en->setDbValue($row['full_name_en']);
		$this->date_of_birth->setDbValue($row['date_of_birth']);
		$this->personal_photo->Upload->DbValue = $row['personal_photo'];
		$this->personal_photo->CurrentValue = $this->personal_photo->Upload->DbValue;
		$this->gender->setDbValue($row['gender']);
		$this->blood_type->setDbValue($row['blood_type']);
		$this->driving_licence->setDbValue($row['driving_licence']);
		$this->job->setDbValue($row['job']);
		$this->volunteering_type->setDbValue($row['volunteering_type']);
		$this->marital_status->setDbValue($row['marital_status']);
		$this->nationality_type->setDbValue($row['nationality_type']);
		$this->nationality->setDbValue($row['nationality']);
		$this->unid->setDbValue($row['unid']);
		$this->visa_expiry_date->setDbValue($row['visa_expiry_date']);
		$this->visa_copy->Upload->DbValue = $row['visa_copy'];
		$this->visa_copy->CurrentValue = $this->visa_copy->Upload->DbValue;
		$this->current_emirate->setDbValue($row['current_emirate']);
		$this->full_address->setDbValue($row['full_address']);
		$this->emirates_id_number->setDbValue($row['emirates_id_number']);
		$this->eid_expiry_date->setDbValue($row['eid_expiry_date']);
		$this->emirates_id_copy->Upload->DbValue = $row['emirates_id_copy'];
		$this->emirates_id_copy->CurrentValue = $this->emirates_id_copy->Upload->DbValue;
		$this->passport_number->setDbValue($row['passport_number']);
		$this->passport_ex_date->setDbValue($row['passport_ex_date']);
		$this->passport_copy->Upload->DbValue = $row['passport_copy'];
		$this->passport_copy->CurrentValue = $this->passport_copy->Upload->DbValue;
		$this->place_of_work->setDbValue($row['place_of_work']);
		$this->qualifications->setDbValue($row['qualifications']);
		$this->cv->Upload->DbValue = $row['cv'];
		$this->cv->CurrentValue = $this->cv->Upload->DbValue;
		$this->home_phone->setDbValue($row['home_phone']);
		$this->work_phone->setDbValue($row['work_phone']);
		$this->mobile_phone->setDbValue($row['mobile_phone']);
		$this->fax->setDbValue($row['fax']);
		$this->pobbox->setDbValue($row['pobbox']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->total_voluntary_hours->setDbValue($row['total_voluntary_hours']);
		$this->overall_evaluation->setDbValue($row['overall_evaluation']);
		$this->admin_approval->setDbValue($row['admin_approval']);
		$this->lastUpdatedBy->setDbValue($row['lastUpdatedBy']);
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->security_approval->setDbValue($row['security_approval']);
		$this->approvedBy->setDbValue($row['approvedBy']);
		$this->security_comment->setDbValue($row['security_comment']);
		$this->title_number->setDbValue($row['title_number']);
		$this->security_owner->setDbValue($row['security_owner']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['user_id'] = NULL;
		$row['group_id'] = NULL;
		$row['full_name_ar'] = NULL;
		$row['full_name_en'] = NULL;
		$row['date_of_birth'] = NULL;
		$row['personal_photo'] = NULL;
		$row['gender'] = NULL;
		$row['blood_type'] = NULL;
		$row['driving_licence'] = NULL;
		$row['job'] = NULL;
		$row['volunteering_type'] = NULL;
		$row['marital_status'] = NULL;
		$row['nationality_type'] = NULL;
		$row['nationality'] = NULL;
		$row['unid'] = NULL;
		$row['visa_expiry_date'] = NULL;
		$row['visa_copy'] = NULL;
		$row['current_emirate'] = NULL;
		$row['full_address'] = NULL;
		$row['emirates_id_number'] = NULL;
		$row['eid_expiry_date'] = NULL;
		$row['emirates_id_copy'] = NULL;
		$row['passport_number'] = NULL;
		$row['passport_ex_date'] = NULL;
		$row['passport_copy'] = NULL;
		$row['place_of_work'] = NULL;
		$row['qualifications'] = NULL;
		$row['cv'] = NULL;
		$row['home_phone'] = NULL;
		$row['work_phone'] = NULL;
		$row['mobile_phone'] = NULL;
		$row['fax'] = NULL;
		$row['pobbox'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['total_voluntary_hours'] = NULL;
		$row['overall_evaluation'] = NULL;
		$row['admin_approval'] = NULL;
		$row['lastUpdatedBy'] = NULL;
		$row['admin_comment'] = NULL;
		$row['security_approval'] = NULL;
		$row['approvedBy'] = NULL;
		$row['security_comment'] = NULL;
		$row['title_number'] = NULL;
		$row['security_owner'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (is_array($rs))
			$row = $rs;
		elseif (is_null($rs))
			$row = $this->NullRow();
		elseif ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			return;

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$row || !is_array($row))
			return;
		$this->user_id->DbValue = $row['user_id'];
		$this->group_id->DbValue = $row['group_id'];
		$this->full_name_ar->DbValue = $row['full_name_ar'];
		$this->full_name_en->DbValue = $row['full_name_en'];
		$this->date_of_birth->DbValue = $row['date_of_birth'];
		$this->personal_photo->Upload->DbValue = $row['personal_photo'];
		$this->gender->DbValue = $row['gender'];
		$this->blood_type->DbValue = $row['blood_type'];
		$this->driving_licence->DbValue = $row['driving_licence'];
		$this->job->DbValue = $row['job'];
		$this->volunteering_type->DbValue = $row['volunteering_type'];
		$this->marital_status->DbValue = $row['marital_status'];
		$this->nationality_type->DbValue = $row['nationality_type'];
		$this->nationality->DbValue = $row['nationality'];
		$this->unid->DbValue = $row['unid'];
		$this->visa_expiry_date->DbValue = $row['visa_expiry_date'];
		$this->visa_copy->Upload->DbValue = $row['visa_copy'];
		$this->current_emirate->DbValue = $row['current_emirate'];
		$this->full_address->DbValue = $row['full_address'];
		$this->emirates_id_number->DbValue = $row['emirates_id_number'];
		$this->eid_expiry_date->DbValue = $row['eid_expiry_date'];
		$this->emirates_id_copy->Upload->DbValue = $row['emirates_id_copy'];
		$this->passport_number->DbValue = $row['passport_number'];
		$this->passport_ex_date->DbValue = $row['passport_ex_date'];
		$this->passport_copy->Upload->DbValue = $row['passport_copy'];
		$this->place_of_work->DbValue = $row['place_of_work'];
		$this->qualifications->DbValue = $row['qualifications'];
		$this->cv->Upload->DbValue = $row['cv'];
		$this->home_phone->DbValue = $row['home_phone'];
		$this->work_phone->DbValue = $row['work_phone'];
		$this->mobile_phone->DbValue = $row['mobile_phone'];
		$this->fax->DbValue = $row['fax'];
		$this->pobbox->DbValue = $row['pobbox'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->total_voluntary_hours->DbValue = $row['total_voluntary_hours'];
		$this->overall_evaluation->DbValue = $row['overall_evaluation'];
		$this->admin_approval->DbValue = $row['admin_approval'];
		$this->lastUpdatedBy->DbValue = $row['lastUpdatedBy'];
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->security_approval->DbValue = $row['security_approval'];
		$this->approvedBy->DbValue = $row['approvedBy'];
		$this->security_comment->DbValue = $row['security_comment'];
		$this->title_number->DbValue = $row['title_number'];
		$this->security_owner->DbValue = $row['security_owner'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// user_id
		// group_id
		// full_name_ar
		// full_name_en
		// date_of_birth
		// personal_photo
		// gender
		// blood_type
		// driving_licence
		// job
		// volunteering_type
		// marital_status
		// nationality_type
		// nationality
		// unid
		// visa_expiry_date
		// visa_copy
		// current_emirate
		// full_address
		// emirates_id_number
		// eid_expiry_date
		// emirates_id_copy
		// passport_number
		// passport_ex_date
		// passport_copy
		// place_of_work
		// qualifications
		// cv
		// home_phone
		// work_phone
		// mobile_phone
		// fax
		// pobbox
		// email
		// password
		// total_voluntary_hours
		// overall_evaluation
		// admin_approval
		// lastUpdatedBy
		// admin_comment
		// security_approval
		// approvedBy
		// security_comment
		// title_number

		$this->title_number->CellCssStyle = "white-space: nowrap;";

		// security_owner
		$this->security_owner->CellCssStyle = "white-space: nowrap;";
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// group_id
		if (strval($this->group_id->CurrentValue) <> "") {
			$arwrk = explode(",", $this->group_id->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`institution_id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
		$sWhereWrk = "";
		$this->group_id->LookupFilters = array("dx1" => '`institutes_name`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->group_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->group_id->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->group_id->ViewValue .= $this->group_id->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->group_id->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->group_id->ViewValue = $this->group_id->CurrentValue;
			}
		} else {
			$this->group_id->ViewValue = NULL;
		}
		$this->group_id->ViewCustomAttributes = "";

		// full_name_ar
		$this->full_name_ar->ViewValue = $this->full_name_ar->CurrentValue;
		$this->full_name_ar->ViewCustomAttributes = "";

		// full_name_en
		$this->full_name_en->ViewValue = $this->full_name_en->CurrentValue;
		$this->full_name_en->ViewCustomAttributes = "";

		// date_of_birth
		$this->date_of_birth->ViewValue = $this->date_of_birth->CurrentValue;
		$this->date_of_birth->ViewValue = ew_FormatDateTime($this->date_of_birth->ViewValue, 0);
		$this->date_of_birth->ViewCustomAttributes = "";

		// personal_photo
		$this->personal_photo->UploadPath = "../images";
		if (!ew_Empty($this->personal_photo->Upload->DbValue)) {
			$this->personal_photo->ImageWidth = 300;
			$this->personal_photo->ImageHeight = 0;
			$this->personal_photo->ImageAlt = $this->personal_photo->FldAlt();
			$this->personal_photo->ViewValue = $this->personal_photo->Upload->DbValue;
		} else {
			$this->personal_photo->ViewValue = "";
		}
		$this->personal_photo->ViewCustomAttributes = "";

		// gender
		if (strval($this->gender->CurrentValue) <> "") {
			$this->gender->ViewValue = $this->gender->OptionCaption($this->gender->CurrentValue);
		} else {
			$this->gender->ViewValue = NULL;
		}
		$this->gender->ViewCustomAttributes = "";

		// blood_type
		if (strval($this->blood_type->CurrentValue) <> "") {
			$this->blood_type->ViewValue = $this->blood_type->OptionCaption($this->blood_type->CurrentValue);
		} else {
			$this->blood_type->ViewValue = NULL;
		}
		$this->blood_type->ViewCustomAttributes = "";

		// driving_licence
		if (strval($this->driving_licence->CurrentValue) <> "") {
			$this->driving_licence->ViewValue = $this->driving_licence->OptionCaption($this->driving_licence->CurrentValue);
		} else {
			$this->driving_licence->ViewValue = NULL;
		}
		$this->driving_licence->ViewCustomAttributes = "";

		// job
		if (strval($this->job->CurrentValue) <> "") {
			$this->job->ViewValue = $this->job->OptionCaption($this->job->CurrentValue);
		} else {
			$this->job->ViewValue = NULL;
		}
		$this->job->ViewCustomAttributes = "";

		// volunteering_type
		if (strval($this->volunteering_type->CurrentValue) <> "") {
			$this->volunteering_type->ViewValue = $this->volunteering_type->OptionCaption($this->volunteering_type->CurrentValue);
		} else {
			$this->volunteering_type->ViewValue = NULL;
		}
		$this->volunteering_type->ViewCustomAttributes = "";

		// marital_status
		if (strval($this->marital_status->CurrentValue) <> "") {
			$this->marital_status->ViewValue = $this->marital_status->OptionCaption($this->marital_status->CurrentValue);
		} else {
			$this->marital_status->ViewValue = NULL;
		}
		$this->marital_status->ViewCustomAttributes = "";

		// nationality_type
		if (strval($this->nationality_type->CurrentValue) <> "") {
			$this->nationality_type->ViewValue = $this->nationality_type->OptionCaption($this->nationality_type->CurrentValue);
		} else {
			$this->nationality_type->ViewValue = NULL;
		}
		$this->nationality_type->ViewCustomAttributes = "";

		// nationality
		$this->nationality->ViewValue = $this->nationality->CurrentValue;
		$this->nationality->ViewCustomAttributes = "";

		// unid
		$this->unid->ViewValue = $this->unid->CurrentValue;
		$this->unid->ViewCustomAttributes = "";

		// visa_expiry_date
		$this->visa_expiry_date->ViewValue = $this->visa_expiry_date->CurrentValue;
		$this->visa_expiry_date->ViewValue = ew_FormatDateTime($this->visa_expiry_date->ViewValue, 0);
		$this->visa_expiry_date->ViewCustomAttributes = "";

		// visa_copy
		$this->visa_copy->UploadPath = "../images";
		if (!ew_Empty($this->visa_copy->Upload->DbValue)) {
			$this->visa_copy->ImageWidth = 300;
			$this->visa_copy->ImageHeight = 0;
			$this->visa_copy->ImageAlt = $this->visa_copy->FldAlt();
			$this->visa_copy->ViewValue = $this->visa_copy->Upload->DbValue;
		} else {
			$this->visa_copy->ViewValue = "";
		}
		$this->visa_copy->ViewCustomAttributes = "";

		// current_emirate
		if (strval($this->current_emirate->CurrentValue) <> "") {
			$this->current_emirate->ViewValue = $this->current_emirate->OptionCaption($this->current_emirate->CurrentValue);
		} else {
			$this->current_emirate->ViewValue = NULL;
		}
		$this->current_emirate->ViewCustomAttributes = "";

		// full_address
		$this->full_address->ViewValue = $this->full_address->CurrentValue;
		$this->full_address->ViewCustomAttributes = "";

		// emirates_id_number
		$this->emirates_id_number->ViewValue = $this->emirates_id_number->CurrentValue;
		$this->emirates_id_number->ViewCustomAttributes = "";

		// eid_expiry_date
		$this->eid_expiry_date->ViewValue = $this->eid_expiry_date->CurrentValue;
		$this->eid_expiry_date->ViewValue = ew_FormatDateTime($this->eid_expiry_date->ViewValue, 0);
		$this->eid_expiry_date->ViewCustomAttributes = "";

		// emirates_id_copy
		$this->emirates_id_copy->UploadPath = "../images";
		if (!ew_Empty($this->emirates_id_copy->Upload->DbValue)) {
			$this->emirates_id_copy->ImageWidth = 100;
			$this->emirates_id_copy->ImageHeight = 0;
			$this->emirates_id_copy->ImageAlt = $this->emirates_id_copy->FldAlt();
			$this->emirates_id_copy->ViewValue = $this->emirates_id_copy->Upload->DbValue;
		} else {
			$this->emirates_id_copy->ViewValue = "";
		}
		$this->emirates_id_copy->ViewCustomAttributes = "";

		// passport_number
		$this->passport_number->ViewValue = $this->passport_number->CurrentValue;
		$this->passport_number->ViewCustomAttributes = "";

		// passport_ex_date
		$this->passport_ex_date->ViewValue = $this->passport_ex_date->CurrentValue;
		$this->passport_ex_date->ViewValue = ew_FormatDateTime($this->passport_ex_date->ViewValue, 0);
		$this->passport_ex_date->ViewCustomAttributes = "";

		// passport_copy
		$this->passport_copy->UploadPath = "../images";
		if (!ew_Empty($this->passport_copy->Upload->DbValue)) {
			$this->passport_copy->ImageWidth = 300;
			$this->passport_copy->ImageHeight = 0;
			$this->passport_copy->ImageAlt = $this->passport_copy->FldAlt();
			$this->passport_copy->ViewValue = $this->passport_copy->Upload->DbValue;
		} else {
			$this->passport_copy->ViewValue = "";
		}
		$this->passport_copy->ViewCustomAttributes = "";

		// place_of_work
		$this->place_of_work->ViewValue = $this->place_of_work->CurrentValue;
		$this->place_of_work->ViewCustomAttributes = "";

		// qualifications
		$this->qualifications->ViewValue = $this->qualifications->CurrentValue;
		$this->qualifications->ViewCustomAttributes = "";

		// cv
		$this->cv->UploadPath = "../images";
		if (!ew_Empty($this->cv->Upload->DbValue)) {
			$this->cv->ViewValue = $this->cv->Upload->DbValue;
		} else {
			$this->cv->ViewValue = "";
		}
		$this->cv->ViewCustomAttributes = "";

		// home_phone
		$this->home_phone->ViewValue = $this->home_phone->CurrentValue;
		$this->home_phone->ViewCustomAttributes = "";

		// work_phone
		$this->work_phone->ViewValue = $this->work_phone->CurrentValue;
		$this->work_phone->ViewCustomAttributes = "";

		// mobile_phone
		$this->mobile_phone->ViewValue = $this->mobile_phone->CurrentValue;
		$this->mobile_phone->ViewCustomAttributes = "";

		// fax
		$this->fax->ViewValue = $this->fax->CurrentValue;
		$this->fax->ViewCustomAttributes = "";

		// pobbox
		$this->pobbox->ViewValue = $this->pobbox->CurrentValue;
		$this->pobbox->ViewCustomAttributes = "";

		// email
		$this->_email->ViewValue = $this->_email->CurrentValue;
		$this->_email->ViewCustomAttributes = "";

		// password
		$this->password->ViewValue = $this->password->CurrentValue;
		$this->password->ViewCustomAttributes = "";

		// total_voluntary_hours
		$this->total_voluntary_hours->ViewValue = $this->total_voluntary_hours->CurrentValue;
		$this->total_voluntary_hours->ViewCustomAttributes = "";

		// overall_evaluation
		$this->overall_evaluation->ViewValue = $this->overall_evaluation->CurrentValue;
		$this->overall_evaluation->ViewCustomAttributes = "";

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

		// security_approval
		if (strval($this->security_approval->CurrentValue) <> "") {
			$this->security_approval->ViewValue = $this->security_approval->OptionCaption($this->security_approval->CurrentValue);
		} else {
			$this->security_approval->ViewValue = NULL;
		}
		$this->security_approval->ViewCustomAttributes = "";

		// security_comment
		$this->security_comment->ViewValue = $this->security_comment->CurrentValue;
		$this->security_comment->ViewCustomAttributes = "";

		// title_number
		$this->title_number->ViewValue = $this->title_number->CurrentValue;
		$this->title_number->ViewCustomAttributes = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// full_name_ar
			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";
			$this->full_name_ar->TooltipValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";
			$this->full_name_en->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";
			$this->security_approval->TooltipValue = "";

			// security_comment
			$this->security_comment->LinkCustomAttributes = "";
			$this->security_comment->HrefValue = "";
			$this->security_comment->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['user_id'];
				$this->LoadDbValues($row);
				$this->personal_photo->OldUploadPath = "../images";
				@unlink($this->personal_photo->OldPhysicalUploadPath() . $row['personal_photo']);
				$this->visa_copy->OldUploadPath = "../images";
				@unlink($this->visa_copy->OldPhysicalUploadPath() . $row['visa_copy']);
				$this->emirates_id_copy->OldUploadPath = "../images";
				$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $row['emirates_id_copy']);
				$FileCount = count($OldFiles);
				for ($i = 0; $i < $FileCount; $i++)
					@unlink($this->emirates_id_copy->OldPhysicalUploadPath() . $OldFiles[$i]);
				$this->passport_copy->OldUploadPath = "../images";
				$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $row['passport_copy']);
				$FileCount = count($OldFiles);
				for ($i = 0; $i < $FileCount; $i++)
					@unlink($this->passport_copy->OldPhysicalUploadPath() . $OldFiles[$i]);
				$this->cv->OldUploadPath = "../images";
				@unlink($this->cv->OldPhysicalUploadPath() . $row['cv']);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($users_delete)) $users_delete = new cusers_delete();

// Page init
$users_delete->Page_Init();

// Page main
$users_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fusersdelete = new ew_Form("fusersdelete", "delete");

// Form_CustomValidate event
fusersdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fusersdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fusersdelete.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersdelete.Lists["x_admin_approval"].Options = <?php echo json_encode($users_delete->admin_approval->Options()) ?>;
fusersdelete.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersdelete.Lists["x_security_approval"].Options = <?php echo json_encode($users_delete->security_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $users_delete->ShowPageHeader(); ?>
<?php
$users_delete->ShowMessage();
?>
<form name="fusersdelete" id="fusersdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($users_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($users->user_id->Visible) { // user_id ?>
		<th class="<?php echo $users->user_id->HeaderCellClass() ?>"><span id="elh_users_user_id" class="users_user_id"><?php echo $users->user_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
		<th class="<?php echo $users->full_name_ar->HeaderCellClass() ?>"><span id="elh_users_full_name_ar" class="users_full_name_ar"><?php echo $users->full_name_ar->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
		<th class="<?php echo $users->full_name_en->HeaderCellClass() ?>"><span id="elh_users_full_name_en" class="users_full_name_en"><?php echo $users->full_name_en->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
		<th class="<?php echo $users->_email->HeaderCellClass() ?>"><span id="elh_users__email" class="users__email"><?php echo $users->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
		<th class="<?php echo $users->admin_approval->HeaderCellClass() ?>"><span id="elh_users_admin_approval" class="users_admin_approval"><?php echo $users->admin_approval->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
		<th class="<?php echo $users->admin_comment->HeaderCellClass() ?>"><span id="elh_users_admin_comment" class="users_admin_comment"><?php echo $users->admin_comment->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->security_approval->Visible) { // security_approval ?>
		<th class="<?php echo $users->security_approval->HeaderCellClass() ?>"><span id="elh_users_security_approval" class="users_security_approval"><?php echo $users->security_approval->FldCaption() ?></span></th>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
		<th class="<?php echo $users->security_comment->HeaderCellClass() ?>"><span id="elh_users_security_comment" class="users_security_comment"><?php echo $users->security_comment->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$users_delete->RecCnt = 0;
$i = 0;
while (!$users_delete->Recordset->EOF) {
	$users_delete->RecCnt++;
	$users_delete->RowCnt++;

	// Set row properties
	$users->ResetAttrs();
	$users->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$users_delete->LoadRowValues($users_delete->Recordset);

	// Render row
	$users_delete->RenderRow();
?>
	<tr<?php echo $users->RowAttributes() ?>>
<?php if ($users->user_id->Visible) { // user_id ?>
		<td<?php echo $users->user_id->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_user_id" class="users_user_id">
<span<?php echo $users->user_id->ViewAttributes() ?>>
<?php echo $users->user_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
		<td<?php echo $users->full_name_ar->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_full_name_ar" class="users_full_name_ar">
<span<?php echo $users->full_name_ar->ViewAttributes() ?>>
<?php echo $users->full_name_ar->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
		<td<?php echo $users->full_name_en->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_full_name_en" class="users_full_name_en">
<span<?php echo $users->full_name_en->ViewAttributes() ?>>
<?php echo $users->full_name_en->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
		<td<?php echo $users->_email->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users__email" class="users__email">
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
		<td<?php echo $users->admin_approval->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_admin_approval" class="users_admin_approval">
<span<?php echo $users->admin_approval->ViewAttributes() ?>>
<?php echo $users->admin_approval->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
		<td<?php echo $users->admin_comment->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_admin_comment" class="users_admin_comment">
<span<?php echo $users->admin_comment->ViewAttributes() ?>>
<?php echo $users->admin_comment->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->security_approval->Visible) { // security_approval ?>
		<td<?php echo $users->security_approval->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_security_approval" class="users_security_approval">
<span<?php echo $users->security_approval->ViewAttributes() ?>>
<?php echo $users->security_approval->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
		<td<?php echo $users->security_comment->CellAttributes() ?>>
<span id="el<?php echo $users_delete->RowCnt ?>_users_security_comment" class="users_security_comment">
<span<?php echo $users->security_comment->ViewAttributes() ?>>
<?php echo $users->security_comment->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$users_delete->Recordset->MoveNext();
}
$users_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $users_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fusersdelete.Init();
</script>
<?php
$users_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_delete->Page_Terminate();
?>
