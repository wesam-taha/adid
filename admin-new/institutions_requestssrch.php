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

$institutions_requests_search = NULL; // Initialize page object first

class cinstitutions_requests_search extends cinstitutions_requests {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions_requests';

	// Page object name
	var $PageObjName = 'institutions_requests_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->institutions_id->SetVisibility();
		$this->event_name->SetVisibility();
		$this->event_emirate->SetVisibility();
		$this->event_location->SetVisibility();
		$this->activity_start_date->SetVisibility();
		$this->activity_end_date->SetVisibility();
		$this->activity_time->SetVisibility();
		$this->activity_description->SetVisibility();
		$this->activity_gender_target->SetVisibility();
		$this->no_of_persons_needed->SetVisibility();
		$this->no_of_hours->SetVisibility();
		$this->mobile_phone->SetVisibility();
		$this->pobox->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->email->SetVisibility();

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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Show as modal
					$row["modal"] = "1";
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "institutions_requestsview.php")
						$row["view"] = "1";
				}
				echo ew_ArrayToJson(array($row));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewSearchForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsSearchError;
		global $gbSkipHeaderFooter;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewSearchForm form-horizontal";
		if ($this->IsPageRequest()) { // Validate request

			// Get action
			$this->CurrentAction = $objForm->GetValue("a_search");
			switch ($this->CurrentAction) {
				case "S": // Get search criteria

					// Build search string for advanced search, remove blank field
					$this->LoadSearchValues(); // Get search values
					if ($this->ValidateSearch()) {
						$sSrchStr = $this->BuildAdvancedSearch();
					} else {
						$sSrchStr = "";
						$this->setFailureMessage($gsSearchError);
					}
					if ($sSrchStr <> "") {
						$sSrchStr = $this->UrlParm($sSrchStr);
						$sSrchStr = "institutions_requestslist.php" . "?" . $sSrchStr;
						$this->Page_Terminate($sSrchStr); // Go to list page
					}
			}
		}

		// Restore search settings from Session
		if ($gsSearchError == "")
			$this->LoadAdvancedSearch();

		// Render row for search
		$this->RowType = EW_ROWTYPE_SEARCH;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Build advanced search
	function BuildAdvancedSearch() {
		$sSrchUrl = "";
		$this->BuildSearchUrl($sSrchUrl, $this->id); // id
		$this->BuildSearchUrl($sSrchUrl, $this->institutions_id); // institutions_id
		$this->BuildSearchUrl($sSrchUrl, $this->event_name); // event_name
		$this->BuildSearchUrl($sSrchUrl, $this->event_emirate); // event_emirate
		$this->BuildSearchUrl($sSrchUrl, $this->event_location); // event_location
		$this->BuildSearchUrl($sSrchUrl, $this->activity_start_date); // activity_start_date
		$this->BuildSearchUrl($sSrchUrl, $this->activity_end_date); // activity_end_date
		$this->BuildSearchUrl($sSrchUrl, $this->activity_time); // activity_time
		$this->BuildSearchUrl($sSrchUrl, $this->activity_description); // activity_description
		$this->BuildSearchUrl($sSrchUrl, $this->activity_gender_target); // activity_gender_target
		$this->BuildSearchUrl($sSrchUrl, $this->no_of_persons_needed); // no_of_persons_needed
		$this->BuildSearchUrl($sSrchUrl, $this->no_of_hours); // no_of_hours
		$this->BuildSearchUrl($sSrchUrl, $this->mobile_phone); // mobile_phone
		$this->BuildSearchUrl($sSrchUrl, $this->pobox); // pobox
		$this->BuildSearchUrl($sSrchUrl, $this->admin_approval); // admin_approval
		$this->BuildSearchUrl($sSrchUrl, $this->admin_comment); // admin_comment
		$this->BuildSearchUrl($sSrchUrl, $this->email); // email
		if ($sSrchUrl <> "") $sSrchUrl .= "&";
		$sSrchUrl .= "cmd=search";
		return $sSrchUrl;
	}

	// Build search URL
	function BuildSearchUrl(&$Url, &$Fld, $OprOnly=FALSE) {
		global $objForm;
		$sWrk = "";
		$FldParm = $Fld->FldParm();
		$FldVal = $objForm->GetValue("x_$FldParm");
		$FldOpr = $objForm->GetValue("z_$FldParm");
		$FldCond = $objForm->GetValue("v_$FldParm");
		$FldVal2 = $objForm->GetValue("y_$FldParm");
		$FldOpr2 = $objForm->GetValue("w_$FldParm");
		$FldVal = $FldVal;
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = $FldVal2;
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		$lFldDataType = ($Fld->FldIsVirtual) ? EW_DATATYPE_STRING : $Fld->FldDataType;
		if ($FldOpr == "BETWEEN") {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal) && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			}
		} else {
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $lFldDataType)) {
				$sWrk = "x_" . $FldParm . "=" . urlencode($FldVal) .
					"&z_" . $FldParm . "=" . urlencode($FldOpr);
			} elseif ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL" || ($FldOpr <> "" && $OprOnly && ew_IsValidOpr($FldOpr, $lFldDataType))) {
				$sWrk = "z_" . $FldParm . "=" . urlencode($FldOpr);
			}
			$IsValidValue = ($lFldDataType <> EW_DATATYPE_NUMBER) ||
				($lFldDataType == EW_DATATYPE_NUMBER && $this->SearchValueIsNumeric($Fld, $FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $lFldDataType)) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "y_" . $FldParm . "=" . urlencode($FldVal2) .
					"&w_" . $FldParm . "=" . urlencode($FldOpr2);
			} elseif ($FldOpr2 == "IS NULL" || $FldOpr2 == "IS NOT NULL" || ($FldOpr2 <> "" && $OprOnly && ew_IsValidOpr($FldOpr2, $lFldDataType))) {
				if ($sWrk <> "") $sWrk .= "&v_" . $FldParm . "=" . urlencode($FldCond) . "&";
				$sWrk .= "w_" . $FldParm . "=" . urlencode($FldOpr2);
			}
		}
		if ($sWrk <> "") {
			if ($Url <> "") $Url .= "&";
			$Url .= $sWrk;
		}
	}

	function SearchValueIsNumeric($Fld, $Value) {
		if (ew_IsFloatFormat($Fld->FldType)) $Value = ew_StrToFloat($Value);
		return is_numeric($Value);
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = $objForm->GetValue("x_id");
		$this->id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_id");

		// institutions_id
		$this->institutions_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_institutions_id");
		$this->institutions_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_institutions_id");

		// event_name
		$this->event_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_event_name");
		$this->event_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_event_name");

		// event_emirate
		$this->event_emirate->AdvancedSearch->SearchValue = $objForm->GetValue("x_event_emirate");
		$this->event_emirate->AdvancedSearch->SearchOperator = $objForm->GetValue("z_event_emirate");

		// event_location
		$this->event_location->AdvancedSearch->SearchValue = $objForm->GetValue("x_event_location");
		$this->event_location->AdvancedSearch->SearchOperator = $objForm->GetValue("z_event_location");

		// activity_start_date
		$this->activity_start_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_start_date");
		$this->activity_start_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_start_date");

		// activity_end_date
		$this->activity_end_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_end_date");
		$this->activity_end_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_end_date");

		// activity_time
		$this->activity_time->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_time");
		$this->activity_time->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_time");

		// activity_description
		$this->activity_description->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_description");
		$this->activity_description->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_description");

		// activity_gender_target
		$this->activity_gender_target->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_gender_target");
		$this->activity_gender_target->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_gender_target");

		// no_of_persons_needed
		$this->no_of_persons_needed->AdvancedSearch->SearchValue = $objForm->GetValue("x_no_of_persons_needed");
		$this->no_of_persons_needed->AdvancedSearch->SearchOperator = $objForm->GetValue("z_no_of_persons_needed");

		// no_of_hours
		$this->no_of_hours->AdvancedSearch->SearchValue = $objForm->GetValue("x_no_of_hours");
		$this->no_of_hours->AdvancedSearch->SearchOperator = $objForm->GetValue("z_no_of_hours");

		// mobile_phone
		$this->mobile_phone->AdvancedSearch->SearchValue = $objForm->GetValue("x_mobile_phone");
		$this->mobile_phone->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobile_phone");

		// pobox
		$this->pobox->AdvancedSearch->SearchValue = $objForm->GetValue("x_pobox");
		$this->pobox->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pobox");

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_approval");
		$this->admin_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_approval");

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_comment");
		$this->admin_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_comment");

		// email
		$this->email->AdvancedSearch->SearchValue = $objForm->GetValue("x_email");
		$this->email->AdvancedSearch->SearchOperator = $objForm->GetValue("z_email");
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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// institutions_id
			$this->institutions_id->EditAttrs["class"] = "form-control";
			$this->institutions_id->EditCustomAttributes = "";
			if (trim(strval($this->institutions_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`institution_id`" . ew_SearchString("=", $this->institutions_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `institutions`";
			$sWhereWrk = "";
			$this->institutions_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->institutions_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->institutions_id->EditValue = $arwrk;

			// event_name
			$this->event_name->EditAttrs["class"] = "form-control";
			$this->event_name->EditCustomAttributes = "";
			$this->event_name->EditValue = ew_HtmlEncode($this->event_name->AdvancedSearch->SearchValue);
			$this->event_name->PlaceHolder = ew_RemoveHtml($this->event_name->FldCaption());

			// event_emirate
			$this->event_emirate->EditAttrs["class"] = "form-control";
			$this->event_emirate->EditCustomAttributes = "";
			$this->event_emirate->EditValue = $this->event_emirate->Options(TRUE);

			// event_location
			$this->event_location->EditAttrs["class"] = "form-control";
			$this->event_location->EditCustomAttributes = "";
			$this->event_location->EditValue = ew_HtmlEncode($this->event_location->AdvancedSearch->SearchValue);
			$this->event_location->PlaceHolder = ew_RemoveHtml($this->event_location->FldCaption());

			// activity_start_date
			$this->activity_start_date->EditAttrs["class"] = "form-control";
			$this->activity_start_date->EditCustomAttributes = "";
			$this->activity_start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->activity_start_date->AdvancedSearch->SearchValue, 0), 8));
			$this->activity_start_date->PlaceHolder = ew_RemoveHtml($this->activity_start_date->FldCaption());

			// activity_end_date
			$this->activity_end_date->EditAttrs["class"] = "form-control";
			$this->activity_end_date->EditCustomAttributes = "";
			$this->activity_end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->activity_end_date->AdvancedSearch->SearchValue, 0), 8));
			$this->activity_end_date->PlaceHolder = ew_RemoveHtml($this->activity_end_date->FldCaption());

			// activity_time
			$this->activity_time->EditAttrs["class"] = "form-control";
			$this->activity_time->EditCustomAttributes = "";
			$this->activity_time->EditValue = ew_HtmlEncode($this->activity_time->AdvancedSearch->SearchValue);
			$this->activity_time->PlaceHolder = ew_RemoveHtml($this->activity_time->FldCaption());

			// activity_description
			$this->activity_description->EditAttrs["class"] = "form-control";
			$this->activity_description->EditCustomAttributes = "";
			$this->activity_description->EditValue = ew_HtmlEncode($this->activity_description->AdvancedSearch->SearchValue);
			$this->activity_description->PlaceHolder = ew_RemoveHtml($this->activity_description->FldCaption());

			// activity_gender_target
			$this->activity_gender_target->EditAttrs["class"] = "form-control";
			$this->activity_gender_target->EditCustomAttributes = "";
			$this->activity_gender_target->EditValue = $this->activity_gender_target->Options(TRUE);

			// no_of_persons_needed
			$this->no_of_persons_needed->EditAttrs["class"] = "form-control";
			$this->no_of_persons_needed->EditCustomAttributes = "";
			$this->no_of_persons_needed->EditValue = ew_HtmlEncode($this->no_of_persons_needed->AdvancedSearch->SearchValue);
			$this->no_of_persons_needed->PlaceHolder = ew_RemoveHtml($this->no_of_persons_needed->FldCaption());

			// no_of_hours
			$this->no_of_hours->EditAttrs["class"] = "form-control";
			$this->no_of_hours->EditCustomAttributes = "";
			$this->no_of_hours->EditValue = ew_HtmlEncode($this->no_of_hours->AdvancedSearch->SearchValue);
			$this->no_of_hours->PlaceHolder = ew_RemoveHtml($this->no_of_hours->FldCaption());

			// mobile_phone
			$this->mobile_phone->EditAttrs["class"] = "form-control";
			$this->mobile_phone->EditCustomAttributes = "";
			$this->mobile_phone->EditValue = ew_HtmlEncode($this->mobile_phone->AdvancedSearch->SearchValue);
			$this->mobile_phone->PlaceHolder = ew_RemoveHtml($this->mobile_phone->FldCaption());

			// pobox
			$this->pobox->EditAttrs["class"] = "form-control";
			$this->pobox->EditCustomAttributes = "";
			$this->pobox->EditValue = ew_HtmlEncode($this->pobox->AdvancedSearch->SearchValue);
			$this->pobox->PlaceHolder = ew_RemoveHtml($this->pobox->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->AdvancedSearch->SearchValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// email
			$this->email->EditAttrs["class"] = "form-control";
			$this->email->EditCustomAttributes = "";
			$this->email->EditValue = ew_HtmlEncode($this->email->AdvancedSearch->SearchValue);
			$this->email->PlaceHolder = ew_RemoveHtml($this->email->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckInteger($this->id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->activity_start_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->activity_start_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->activity_end_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->activity_end_date->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->institutions_id->AdvancedSearch->Load();
		$this->event_name->AdvancedSearch->Load();
		$this->event_emirate->AdvancedSearch->Load();
		$this->event_location->AdvancedSearch->Load();
		$this->activity_start_date->AdvancedSearch->Load();
		$this->activity_end_date->AdvancedSearch->Load();
		$this->activity_time->AdvancedSearch->Load();
		$this->activity_description->AdvancedSearch->Load();
		$this->activity_gender_target->AdvancedSearch->Load();
		$this->no_of_persons_needed->AdvancedSearch->Load();
		$this->no_of_hours->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->pobox->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->email->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institutions_requestslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_institutions_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `institution_id` AS `LinkFld`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
			$sWhereWrk = "";
			$this->institutions_id->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`institution_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->institutions_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($institutions_requests_search)) $institutions_requests_search = new cinstitutions_requests_search();

// Page init
$institutions_requests_search->Page_Init();

// Page main
$institutions_requests_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_requests_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($institutions_requests_search->IsModal) { ?>
var CurrentAdvancedSearchForm = finstitutions_requestssearch = new ew_Form("finstitutions_requestssearch", "search");
<?php } else { ?>
var CurrentForm = finstitutions_requestssearch = new ew_Form("finstitutions_requestssearch", "search");
<?php } ?>

// Form_CustomValidate event
finstitutions_requestssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutions_requestssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstitutions_requestssearch.Lists["x_institutions_id"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
finstitutions_requestssearch.Lists["x_institutions_id"].Data = "<?php echo $institutions_requests_search->institutions_id->LookupFilterQuery(FALSE, "search") ?>";
finstitutions_requestssearch.Lists["x_event_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestssearch.Lists["x_event_emirate"].Options = <?php echo json_encode($institutions_requests_search->event_emirate->Options()) ?>;
finstitutions_requestssearch.Lists["x_activity_gender_target"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestssearch.Lists["x_activity_gender_target"].Options = <?php echo json_encode($institutions_requests_search->activity_gender_target->Options()) ?>;
finstitutions_requestssearch.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestssearch.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_requests_search->admin_approval->Options()) ?>;

// Form object for search
// Validate function for search

finstitutions_requestssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions_requests->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_activity_start_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions_requests->activity_start_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_activity_end_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions_requests->activity_end_date->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $institutions_requests_search->ShowPageHeader(); ?>
<?php
$institutions_requests_search->ShowMessage();
?>
<form name="finstitutions_requestssearch" id="finstitutions_requestssearch" class="<?php echo $institutions_requests_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_requests_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_requests_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions_requests">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($institutions_requests_search->IsModal) ?>">
<?php if (!$institutions_requests_search->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutions_requestssearch" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions_requests->id->Visible) { // id ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_id"><?php echo $institutions_requests->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->id->CellAttributes() ?>>
			<span id="el_institutions_requests_id">
<input type="text" data-table="institutions_requests" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo ew_HtmlEncode($institutions_requests->id->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->id->EditValue ?>"<?php echo $institutions_requests->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_id">
		<td class="col-sm-2"><span id="elh_institutions_requests_id"><?php echo $institutions_requests->id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></span></td>
		<td<?php echo $institutions_requests->id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_id">
<input type="text" data-table="institutions_requests" data-field="x_id" name="x_id" id="x_id" size="30" placeholder="<?php echo ew_HtmlEncode($institutions_requests->id->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->id->EditValue ?>"<?php echo $institutions_requests->id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->institutions_id->Visible) { // institutions_id ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_institutions_id" class="form-group">
		<label for="x_institutions_id" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_institutions_id"><?php echo $institutions_requests->institutions_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_institutions_id" id="z_institutions_id" value="="></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->institutions_id->CellAttributes() ?>>
			<span id="el_institutions_requests_institutions_id">
<select data-table="institutions_requests" data-field="x_institutions_id" data-value-separator="<?php echo $institutions_requests->institutions_id->DisplayValueSeparatorAttribute() ?>" id="x_institutions_id" name="x_institutions_id"<?php echo $institutions_requests->institutions_id->EditAttributes() ?>>
<?php echo $institutions_requests->institutions_id->SelectOptionListHtml("x_institutions_id") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_institutions_id">
		<td class="col-sm-2"><span id="elh_institutions_requests_institutions_id"><?php echo $institutions_requests->institutions_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_institutions_id" id="z_institutions_id" value="="></span></td>
		<td<?php echo $institutions_requests->institutions_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_institutions_id">
<select data-table="institutions_requests" data-field="x_institutions_id" data-value-separator="<?php echo $institutions_requests->institutions_id->DisplayValueSeparatorAttribute() ?>" id="x_institutions_id" name="x_institutions_id"<?php echo $institutions_requests->institutions_id->EditAttributes() ?>>
<?php echo $institutions_requests->institutions_id->SelectOptionListHtml("x_institutions_id") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_name->Visible) { // event_name ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_event_name" class="form-group">
		<label for="x_event_name" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_event_name"><?php echo $institutions_requests->event_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_event_name" id="z_event_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->event_name->CellAttributes() ?>>
			<span id="el_institutions_requests_event_name">
<input type="text" data-table="institutions_requests" data-field="x_event_name" name="x_event_name" id="x_event_name" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_name->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_name->EditValue ?>"<?php echo $institutions_requests->event_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_name">
		<td class="col-sm-2"><span id="elh_institutions_requests_event_name"><?php echo $institutions_requests->event_name->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_event_name" id="z_event_name" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->event_name->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_event_name">
<input type="text" data-table="institutions_requests" data-field="x_event_name" name="x_event_name" id="x_event_name" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_name->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_name->EditValue ?>"<?php echo $institutions_requests->event_name->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_emirate->Visible) { // event_emirate ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_event_emirate" class="form-group">
		<label for="x_event_emirate" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_event_emirate"><?php echo $institutions_requests->event_emirate->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_event_emirate" id="z_event_emirate" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->event_emirate->CellAttributes() ?>>
			<span id="el_institutions_requests_event_emirate">
<select data-table="institutions_requests" data-field="x_event_emirate" data-value-separator="<?php echo $institutions_requests->event_emirate->DisplayValueSeparatorAttribute() ?>" id="x_event_emirate" name="x_event_emirate"<?php echo $institutions_requests->event_emirate->EditAttributes() ?>>
<?php echo $institutions_requests->event_emirate->SelectOptionListHtml("x_event_emirate") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_emirate">
		<td class="col-sm-2"><span id="elh_institutions_requests_event_emirate"><?php echo $institutions_requests->event_emirate->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_event_emirate" id="z_event_emirate" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->event_emirate->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_event_emirate">
<select data-table="institutions_requests" data-field="x_event_emirate" data-value-separator="<?php echo $institutions_requests->event_emirate->DisplayValueSeparatorAttribute() ?>" id="x_event_emirate" name="x_event_emirate"<?php echo $institutions_requests->event_emirate->EditAttributes() ?>>
<?php echo $institutions_requests->event_emirate->SelectOptionListHtml("x_event_emirate") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_location->Visible) { // event_location ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_event_location" class="form-group">
		<label for="x_event_location" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_event_location"><?php echo $institutions_requests->event_location->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_event_location" id="z_event_location" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->event_location->CellAttributes() ?>>
			<span id="el_institutions_requests_event_location">
<input type="text" data-table="institutions_requests" data-field="x_event_location" name="x_event_location" id="x_event_location" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_location->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_location->EditValue ?>"<?php echo $institutions_requests->event_location->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_location">
		<td class="col-sm-2"><span id="elh_institutions_requests_event_location"><?php echo $institutions_requests->event_location->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_event_location" id="z_event_location" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->event_location->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_event_location">
<input type="text" data-table="institutions_requests" data-field="x_event_location" name="x_event_location" id="x_event_location" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_location->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_location->EditValue ?>"<?php echo $institutions_requests->event_location->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_start_date->Visible) { // activity_start_date ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_activity_start_date" class="form-group">
		<label for="x_activity_start_date" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_activity_start_date"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_start_date" id="z_activity_start_date" value="="></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_start_date->CellAttributes() ?>>
			<span id="el_institutions_requests_activity_start_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_start_date->EditValue ?>"<?php echo $institutions_requests->activity_start_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_start_date->ReadOnly && !$institutions_requests->activity_start_date->Disabled && !isset($institutions_requests->activity_start_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestssearch", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_start_date">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_start_date"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_start_date" id="z_activity_start_date" value="="></span></td>
		<td<?php echo $institutions_requests->activity_start_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_activity_start_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_start_date->EditValue ?>"<?php echo $institutions_requests->activity_start_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_start_date->ReadOnly && !$institutions_requests->activity_start_date->Disabled && !isset($institutions_requests->activity_start_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestssearch", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_end_date->Visible) { // activity_end_date ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_activity_end_date" class="form-group">
		<label for="x_activity_end_date" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_activity_end_date"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_end_date" id="z_activity_end_date" value="="></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_end_date->CellAttributes() ?>>
			<span id="el_institutions_requests_activity_end_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_end_date->EditValue ?>"<?php echo $institutions_requests->activity_end_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_end_date->ReadOnly && !$institutions_requests->activity_end_date->Disabled && !isset($institutions_requests->activity_end_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestssearch", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_end_date">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_end_date"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_end_date" id="z_activity_end_date" value="="></span></td>
		<td<?php echo $institutions_requests->activity_end_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_activity_end_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_end_date->EditValue ?>"<?php echo $institutions_requests->activity_end_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_end_date->ReadOnly && !$institutions_requests->activity_end_date->Disabled && !isset($institutions_requests->activity_end_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestssearch", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_time->Visible) { // activity_time ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_activity_time" class="form-group">
		<label for="x_activity_time" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_activity_time"><?php echo $institutions_requests->activity_time->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_time" id="z_activity_time" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_time->CellAttributes() ?>>
			<span id="el_institutions_requests_activity_time">
<input type="text" data-table="institutions_requests" data-field="x_activity_time" name="x_activity_time" id="x_activity_time" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_time->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_time->EditValue ?>"<?php echo $institutions_requests->activity_time->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_time">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_time"><?php echo $institutions_requests->activity_time->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_time" id="z_activity_time" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->activity_time->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_activity_time">
<input type="text" data-table="institutions_requests" data-field="x_activity_time" name="x_activity_time" id="x_activity_time" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_time->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_time->EditValue ?>"<?php echo $institutions_requests->activity_time->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_description->Visible) { // activity_description ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_activity_description" class="form-group">
		<label class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_activity_description"><?php echo $institutions_requests->activity_description->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_description" id="z_activity_description" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_description->CellAttributes() ?>>
			<span id="el_institutions_requests_activity_description">
<input type="text" data-table="institutions_requests" data-field="x_activity_description" name="x_activity_description" id="x_activity_description" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_description->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_description->EditValue ?>"<?php echo $institutions_requests->activity_description->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_description">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_description"><?php echo $institutions_requests->activity_description->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_description" id="z_activity_description" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->activity_description->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_activity_description">
<input type="text" data-table="institutions_requests" data-field="x_activity_description" name="x_activity_description" id="x_activity_description" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_description->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_description->EditValue ?>"<?php echo $institutions_requests->activity_description->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_gender_target->Visible) { // activity_gender_target ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_activity_gender_target" class="form-group">
		<label for="x_activity_gender_target" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_activity_gender_target"><?php echo $institutions_requests->activity_gender_target->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_gender_target" id="z_activity_gender_target" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_gender_target->CellAttributes() ?>>
			<span id="el_institutions_requests_activity_gender_target">
<select data-table="institutions_requests" data-field="x_activity_gender_target" data-value-separator="<?php echo $institutions_requests->activity_gender_target->DisplayValueSeparatorAttribute() ?>" id="x_activity_gender_target" name="x_activity_gender_target"<?php echo $institutions_requests->activity_gender_target->EditAttributes() ?>>
<?php echo $institutions_requests->activity_gender_target->SelectOptionListHtml("x_activity_gender_target") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_gender_target">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_gender_target"><?php echo $institutions_requests->activity_gender_target->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_gender_target" id="z_activity_gender_target" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->activity_gender_target->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_activity_gender_target">
<select data-table="institutions_requests" data-field="x_activity_gender_target" data-value-separator="<?php echo $institutions_requests->activity_gender_target->DisplayValueSeparatorAttribute() ?>" id="x_activity_gender_target" name="x_activity_gender_target"<?php echo $institutions_requests->activity_gender_target->EditAttributes() ?>>
<?php echo $institutions_requests->activity_gender_target->SelectOptionListHtml("x_activity_gender_target") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->no_of_persons_needed->Visible) { // no_of_persons_needed ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_no_of_persons_needed" class="form-group">
		<label for="x_no_of_persons_needed" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_no_of_persons_needed"><?php echo $institutions_requests->no_of_persons_needed->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_of_persons_needed" id="z_no_of_persons_needed" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->no_of_persons_needed->CellAttributes() ?>>
			<span id="el_institutions_requests_no_of_persons_needed">
<input type="text" data-table="institutions_requests" data-field="x_no_of_persons_needed" name="x_no_of_persons_needed" id="x_no_of_persons_needed" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_persons_needed->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_persons_needed->EditValue ?>"<?php echo $institutions_requests->no_of_persons_needed->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_no_of_persons_needed">
		<td class="col-sm-2"><span id="elh_institutions_requests_no_of_persons_needed"><?php echo $institutions_requests->no_of_persons_needed->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_of_persons_needed" id="z_no_of_persons_needed" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->no_of_persons_needed->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_no_of_persons_needed">
<input type="text" data-table="institutions_requests" data-field="x_no_of_persons_needed" name="x_no_of_persons_needed" id="x_no_of_persons_needed" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_persons_needed->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_persons_needed->EditValue ?>"<?php echo $institutions_requests->no_of_persons_needed->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->no_of_hours->Visible) { // no_of_hours ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_no_of_hours" class="form-group">
		<label for="x_no_of_hours" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_no_of_hours"><?php echo $institutions_requests->no_of_hours->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_of_hours" id="z_no_of_hours" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->no_of_hours->CellAttributes() ?>>
			<span id="el_institutions_requests_no_of_hours">
<input type="text" data-table="institutions_requests" data-field="x_no_of_hours" name="x_no_of_hours" id="x_no_of_hours" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_hours->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_hours->EditValue ?>"<?php echo $institutions_requests->no_of_hours->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_no_of_hours">
		<td class="col-sm-2"><span id="elh_institutions_requests_no_of_hours"><?php echo $institutions_requests->no_of_hours->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_no_of_hours" id="z_no_of_hours" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->no_of_hours->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_no_of_hours">
<input type="text" data-table="institutions_requests" data-field="x_no_of_hours" name="x_no_of_hours" id="x_no_of_hours" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_hours->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_hours->EditValue ?>"<?php echo $institutions_requests->no_of_hours->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->mobile_phone->Visible) { // mobile_phone ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_mobile_phone" class="form-group">
		<label for="x_mobile_phone" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_mobile_phone"><?php echo $institutions_requests->mobile_phone->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_phone" id="z_mobile_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->mobile_phone->CellAttributes() ?>>
			<span id="el_institutions_requests_mobile_phone">
<input type="text" data-table="institutions_requests" data-field="x_mobile_phone" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions_requests->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->mobile_phone->EditValue ?>"<?php echo $institutions_requests->mobile_phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_institutions_requests_mobile_phone"><?php echo $institutions_requests->mobile_phone->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_phone" id="z_mobile_phone" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->mobile_phone->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_mobile_phone">
<input type="text" data-table="institutions_requests" data-field="x_mobile_phone" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions_requests->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->mobile_phone->EditValue ?>"<?php echo $institutions_requests->mobile_phone->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->pobox->Visible) { // pobox ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_pobox" class="form-group">
		<label for="x_pobox" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_pobox"><?php echo $institutions_requests->pobox->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pobox" id="z_pobox" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->pobox->CellAttributes() ?>>
			<span id="el_institutions_requests_pobox">
<input type="text" data-table="institutions_requests" data-field="x_pobox" name="x_pobox" id="x_pobox" placeholder="<?php echo ew_HtmlEncode($institutions_requests->pobox->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->pobox->EditValue ?>"<?php echo $institutions_requests->pobox->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_pobox">
		<td class="col-sm-2"><span id="elh_institutions_requests_pobox"><?php echo $institutions_requests->pobox->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pobox" id="z_pobox" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->pobox->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_pobox">
<input type="text" data-table="institutions_requests" data-field="x_pobox" name="x_pobox" id="x_pobox" placeholder="<?php echo ew_HtmlEncode($institutions_requests->pobox->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->pobox->EditValue ?>"<?php echo $institutions_requests->pobox->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->admin_approval->Visible) { // admin_approval ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_admin_approval"><?php echo $institutions_requests->admin_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->admin_approval->CellAttributes() ?>>
			<span id="el_institutions_requests_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions_requests" data-field="x_admin_approval" data-value-separator="<?php echo $institutions_requests->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions_requests->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions_requests->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_institutions_requests_admin_approval"><?php echo $institutions_requests->admin_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></span></td>
		<td<?php echo $institutions_requests->admin_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions_requests" data-field="x_admin_approval" data-value-separator="<?php echo $institutions_requests->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions_requests->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions_requests->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->admin_comment->Visible) { // admin_comment ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label for="x_admin_comment" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_admin_comment"><?php echo $institutions_requests->admin_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->admin_comment->CellAttributes() ?>>
			<span id="el_institutions_requests_admin_comment">
<input type="text" data-table="institutions_requests" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->admin_comment->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->admin_comment->EditValue ?>"<?php echo $institutions_requests->admin_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_institutions_requests_admin_comment"><?php echo $institutions_requests->admin_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->admin_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_admin_comment">
<input type="text" data-table="institutions_requests" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->admin_comment->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->admin_comment->EditValue ?>"<?php echo $institutions_requests->admin_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->email->Visible) { // email ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
	<div id="r_email" class="form-group">
		<label for="x_email" class="<?php echo $institutions_requests_search->LeftColumnClass ?>"><span id="elh_institutions_requests_email"><?php echo $institutions_requests->email->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_email" id="z_email" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_requests_search->RightColumnClass ?>"><div<?php echo $institutions_requests->email->CellAttributes() ?>>
			<span id="el_institutions_requests_email">
<input type="text" data-table="institutions_requests" data-field="x_email" name="x_email" id="x_email" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->email->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->email->EditValue ?>"<?php echo $institutions_requests->email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_email">
		<td class="col-sm-2"><span id="elh_institutions_requests_email"><?php echo $institutions_requests->email->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_email" id="z_email" value="LIKE"></span></td>
		<td<?php echo $institutions_requests->email->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_requests_email">
<input type="text" data-table="institutions_requests" data-field="x_email" name="x_email" id="x_email" size="35" placeholder="<?php echo ew_HtmlEncode($institutions_requests->email->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->email->EditValue ?>"<?php echo $institutions_requests->email->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$institutions_requests_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $institutions_requests_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$institutions_requests_search->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
finstitutions_requestssearch.Init();
</script>
<?php
$institutions_requests_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$institutions_requests_search->Page_Terminate();
?>
