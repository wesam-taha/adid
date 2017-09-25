<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "institutions_requestsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$institutions_requests_delete = NULL; // Initialize page object first

class cinstitutions_requests_delete extends cinstitutions_requests {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions_requests';

	// Page object name
	var $PageObjName = 'institutions_requests_delete';

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

		// Table object (institutions_requests)
		if (!isset($GLOBALS["institutions_requests"]) || get_class($GLOBALS["institutions_requests"]) == "cinstitutions_requests") {
			$GLOBALS["institutions_requests"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["institutions_requests"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'institutions_requests', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("institutions_requestslist.php"));
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
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->institutions_id->SetVisibility();
		$this->event_name->SetVisibility();
		$this->event_emirate->SetVisibility();
		$this->event_location->SetVisibility();
		$this->activity_start_date->SetVisibility();
		$this->activity_end_date->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();

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
		global $EW_EXPORT, $institutions_requests;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($institutions_requests);
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
			$this->Page_Terminate("institutions_requestslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in institutions_requests class, institutions_requestsinfo.php

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
				$this->Page_Terminate("institutions_requestslist.php"); // Return to list
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
		$this->id->setDbValue($row['id']);
		$this->institutions_id->setDbValue($row['institutions_id']);
		$this->event_name->setDbValue($row['event_name']);
		$this->event_emirate->setDbValue($row['event_emirate']);
		$this->event_location->setDbValue($row['event_location']);
		$this->activity_start_date->setDbValue($row['activity_start_date']);
		$this->activity_end_date->setDbValue($row['activity_end_date']);
		$this->activity_time->setDbValue($row['activity_time']);
		$this->activity_description->setDbValue($row['activity_description']);
		$this->activity_gender_target->setDbValue($row['activity_gender_target']);
		$this->no_of_persons_needed->setDbValue($row['no_of_persons_needed']);
		$this->no_of_hours->setDbValue($row['no_of_hours']);
		$this->mobile_phone->setDbValue($row['mobile_phone']);
		$this->pobox->setDbValue($row['pobox']);
		$this->admin_approval->setDbValue($row['admin_approval']);
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->email->setDbValue($row['email']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['institutions_id'] = NULL;
		$row['event_name'] = NULL;
		$row['event_emirate'] = NULL;
		$row['event_location'] = NULL;
		$row['activity_start_date'] = NULL;
		$row['activity_end_date'] = NULL;
		$row['activity_time'] = NULL;
		$row['activity_description'] = NULL;
		$row['activity_gender_target'] = NULL;
		$row['no_of_persons_needed'] = NULL;
		$row['no_of_hours'] = NULL;
		$row['mobile_phone'] = NULL;
		$row['pobox'] = NULL;
		$row['admin_approval'] = NULL;
		$row['admin_comment'] = NULL;
		$row['email'] = NULL;
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
		$this->id->DbValue = $row['id'];
		$this->institutions_id->DbValue = $row['institutions_id'];
		$this->event_name->DbValue = $row['event_name'];
		$this->event_emirate->DbValue = $row['event_emirate'];
		$this->event_location->DbValue = $row['event_location'];
		$this->activity_start_date->DbValue = $row['activity_start_date'];
		$this->activity_end_date->DbValue = $row['activity_end_date'];
		$this->activity_time->DbValue = $row['activity_time'];
		$this->activity_description->DbValue = $row['activity_description'];
		$this->activity_gender_target->DbValue = $row['activity_gender_target'];
		$this->no_of_persons_needed->DbValue = $row['no_of_persons_needed'];
		$this->no_of_hours->DbValue = $row['no_of_hours'];
		$this->mobile_phone->DbValue = $row['mobile_phone'];
		$this->pobox->DbValue = $row['pobox'];
		$this->admin_approval->DbValue = $row['admin_approval'];
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->email->DbValue = $row['email'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";
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
				$sThisKey .= $row['id'];
				$this->LoadDbValues($row);
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institutions_requestslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($institutions_requests_delete)) $institutions_requests_delete = new cinstitutions_requests_delete();

// Page init
$institutions_requests_delete->Page_Init();

// Page main
$institutions_requests_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_requests_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = finstitutions_requestsdelete = new ew_Form("finstitutions_requestsdelete", "delete");

// Form_CustomValidate event
finstitutions_requestsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutions_requestsdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstitutions_requestsdelete.Lists["x_institutions_id"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
finstitutions_requestsdelete.Lists["x_institutions_id"].Data = "<?php echo $institutions_requests_delete->institutions_id->LookupFilterQuery(FALSE, "delete") ?>";
finstitutions_requestsdelete.Lists["x_event_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestsdelete.Lists["x_event_emirate"].Options = <?php echo json_encode($institutions_requests_delete->event_emirate->Options()) ?>;
finstitutions_requestsdelete.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestsdelete.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_requests_delete->admin_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $institutions_requests_delete->ShowPageHeader(); ?>
<?php
$institutions_requests_delete->ShowMessage();
?>
<form name="finstitutions_requestsdelete" id="finstitutions_requestsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_requests_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_requests_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions_requests">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($institutions_requests_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($institutions_requests->id->Visible) { // id ?>
		<th class="<?php echo $institutions_requests->id->HeaderCellClass() ?>"><span id="elh_institutions_requests_id" class="institutions_requests_id"><?php echo $institutions_requests->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->institutions_id->Visible) { // institutions_id ?>
		<th class="<?php echo $institutions_requests->institutions_id->HeaderCellClass() ?>"><span id="elh_institutions_requests_institutions_id" class="institutions_requests_institutions_id"><?php echo $institutions_requests->institutions_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->event_name->Visible) { // event_name ?>
		<th class="<?php echo $institutions_requests->event_name->HeaderCellClass() ?>"><span id="elh_institutions_requests_event_name" class="institutions_requests_event_name"><?php echo $institutions_requests->event_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->event_emirate->Visible) { // event_emirate ?>
		<th class="<?php echo $institutions_requests->event_emirate->HeaderCellClass() ?>"><span id="elh_institutions_requests_event_emirate" class="institutions_requests_event_emirate"><?php echo $institutions_requests->event_emirate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->event_location->Visible) { // event_location ?>
		<th class="<?php echo $institutions_requests->event_location->HeaderCellClass() ?>"><span id="elh_institutions_requests_event_location" class="institutions_requests_event_location"><?php echo $institutions_requests->event_location->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->activity_start_date->Visible) { // activity_start_date ?>
		<th class="<?php echo $institutions_requests->activity_start_date->HeaderCellClass() ?>"><span id="elh_institutions_requests_activity_start_date" class="institutions_requests_activity_start_date"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->activity_end_date->Visible) { // activity_end_date ?>
		<th class="<?php echo $institutions_requests->activity_end_date->HeaderCellClass() ?>"><span id="elh_institutions_requests_activity_end_date" class="institutions_requests_activity_end_date"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->admin_approval->Visible) { // admin_approval ?>
		<th class="<?php echo $institutions_requests->admin_approval->HeaderCellClass() ?>"><span id="elh_institutions_requests_admin_approval" class="institutions_requests_admin_approval"><?php echo $institutions_requests->admin_approval->FldCaption() ?></span></th>
<?php } ?>
<?php if ($institutions_requests->admin_comment->Visible) { // admin_comment ?>
		<th class="<?php echo $institutions_requests->admin_comment->HeaderCellClass() ?>"><span id="elh_institutions_requests_admin_comment" class="institutions_requests_admin_comment"><?php echo $institutions_requests->admin_comment->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$institutions_requests_delete->RecCnt = 0;
$i = 0;
while (!$institutions_requests_delete->Recordset->EOF) {
	$institutions_requests_delete->RecCnt++;
	$institutions_requests_delete->RowCnt++;

	// Set row properties
	$institutions_requests->ResetAttrs();
	$institutions_requests->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$institutions_requests_delete->LoadRowValues($institutions_requests_delete->Recordset);

	// Render row
	$institutions_requests_delete->RenderRow();
?>
	<tr<?php echo $institutions_requests->RowAttributes() ?>>
<?php if ($institutions_requests->id->Visible) { // id ?>
		<td<?php echo $institutions_requests->id->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_id" class="institutions_requests_id">
<span<?php echo $institutions_requests->id->ViewAttributes() ?>>
<?php echo $institutions_requests->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->institutions_id->Visible) { // institutions_id ?>
		<td<?php echo $institutions_requests->institutions_id->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_institutions_id" class="institutions_requests_institutions_id">
<span<?php echo $institutions_requests->institutions_id->ViewAttributes() ?>>
<?php echo $institutions_requests->institutions_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->event_name->Visible) { // event_name ?>
		<td<?php echo $institutions_requests->event_name->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_event_name" class="institutions_requests_event_name">
<span<?php echo $institutions_requests->event_name->ViewAttributes() ?>>
<?php echo $institutions_requests->event_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->event_emirate->Visible) { // event_emirate ?>
		<td<?php echo $institutions_requests->event_emirate->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_event_emirate" class="institutions_requests_event_emirate">
<span<?php echo $institutions_requests->event_emirate->ViewAttributes() ?>>
<?php echo $institutions_requests->event_emirate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->event_location->Visible) { // event_location ?>
		<td<?php echo $institutions_requests->event_location->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_event_location" class="institutions_requests_event_location">
<span<?php echo $institutions_requests->event_location->ViewAttributes() ?>>
<?php echo $institutions_requests->event_location->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->activity_start_date->Visible) { // activity_start_date ?>
		<td<?php echo $institutions_requests->activity_start_date->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_activity_start_date" class="institutions_requests_activity_start_date">
<span<?php echo $institutions_requests->activity_start_date->ViewAttributes() ?>>
<?php echo $institutions_requests->activity_start_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->activity_end_date->Visible) { // activity_end_date ?>
		<td<?php echo $institutions_requests->activity_end_date->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_activity_end_date" class="institutions_requests_activity_end_date">
<span<?php echo $institutions_requests->activity_end_date->ViewAttributes() ?>>
<?php echo $institutions_requests->activity_end_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->admin_approval->Visible) { // admin_approval ?>
		<td<?php echo $institutions_requests->admin_approval->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_admin_approval" class="institutions_requests_admin_approval">
<span<?php echo $institutions_requests->admin_approval->ViewAttributes() ?>>
<?php echo $institutions_requests->admin_approval->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($institutions_requests->admin_comment->Visible) { // admin_comment ?>
		<td<?php echo $institutions_requests->admin_comment->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_delete->RowCnt ?>_institutions_requests_admin_comment" class="institutions_requests_admin_comment">
<span<?php echo $institutions_requests->admin_comment->ViewAttributes() ?>>
<?php echo $institutions_requests->admin_comment->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$institutions_requests_delete->Recordset->MoveNext();
}
$institutions_requests_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $institutions_requests_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
finstitutions_requestsdelete.Init();
</script>
<?php
$institutions_requests_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$institutions_requests_delete->Page_Terminate();
?>
