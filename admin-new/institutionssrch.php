<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "institutionsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$institutions_search = NULL; // Initialize page object first

class cinstitutions_search extends cinstitutions {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions';

	// Page object name
	var $PageObjName = 'institutions_search';

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

		// Table object (institutions)
		if (!isset($GLOBALS["institutions"]) || get_class($GLOBALS["institutions"]) == "cinstitutions") {
			$GLOBALS["institutions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["institutions"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'institutions', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("institutionslist.php"));
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
		$this->institution_id->SetVisibility();
		$this->institution_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->full_name_ar->SetVisibility();
		$this->full_name_en->SetVisibility();
		$this->institution_type->SetVisibility();
		$this->institutes_name->SetVisibility();
		$this->volunteering_type->SetVisibility();
		$this->licence_no->SetVisibility();
		$this->trade_licence->SetVisibility();
		$this->tl_expiry_date->SetVisibility();
		$this->nationality_type->SetVisibility();
		$this->nationality->SetVisibility();
		$this->visa_expiry_date->SetVisibility();
		$this->unid->SetVisibility();
		$this->visa_copy->SetVisibility();
		$this->current_emirate->SetVisibility();
		$this->full_address->SetVisibility();
		$this->emirates_id_number->SetVisibility();
		$this->eid_expiry_date->SetVisibility();
		$this->emirates_id_copy->SetVisibility();
		$this->passport_number->SetVisibility();
		$this->passport_ex_date->SetVisibility();
		$this->passport_copy->SetVisibility();
		$this->place_of_work->SetVisibility();
		$this->work_phone->SetVisibility();
		$this->mobile_phone->SetVisibility();
		$this->fax->SetVisibility();
		$this->pobbox->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->forward_to_dep->SetVisibility();
		$this->eco_department_approval->SetVisibility();
		$this->eco_departmnet_comment->SetVisibility();
		$this->security_approval->SetVisibility();
		$this->security_comment->SetVisibility();

		// Set up multi page object
		$this->SetupMultiPages();

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
		global $EW_EXPORT, $institutions;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($institutions);
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
					if ($pageName == "institutionsview.php")
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
	var $MultiPages; // Multi pages object

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
						$sSrchStr = "institutionslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->institution_id); // institution_id
		$this->BuildSearchUrl($sSrchUrl, $this->full_name_ar); // full_name_ar
		$this->BuildSearchUrl($sSrchUrl, $this->full_name_en); // full_name_en
		$this->BuildSearchUrl($sSrchUrl, $this->institution_type); // institution_type
		$this->BuildSearchUrl($sSrchUrl, $this->institutes_name); // institutes_name
		$this->BuildSearchUrl($sSrchUrl, $this->volunteering_type); // volunteering_type
		$this->BuildSearchUrl($sSrchUrl, $this->licence_no); // licence_no
		$this->BuildSearchUrl($sSrchUrl, $this->trade_licence); // trade_licence
		$this->BuildSearchUrl($sSrchUrl, $this->tl_expiry_date); // tl_expiry_date
		$this->BuildSearchUrl($sSrchUrl, $this->nationality_type); // nationality_type
		$this->BuildSearchUrl($sSrchUrl, $this->nationality); // nationality
		$this->BuildSearchUrl($sSrchUrl, $this->visa_expiry_date); // visa_expiry_date
		$this->BuildSearchUrl($sSrchUrl, $this->unid); // unid
		$this->BuildSearchUrl($sSrchUrl, $this->visa_copy); // visa_copy
		$this->BuildSearchUrl($sSrchUrl, $this->current_emirate); // current_emirate
		$this->BuildSearchUrl($sSrchUrl, $this->full_address); // full_address
		$this->BuildSearchUrl($sSrchUrl, $this->emirates_id_number); // emirates_id_number
		$this->BuildSearchUrl($sSrchUrl, $this->eid_expiry_date); // eid_expiry_date
		$this->BuildSearchUrl($sSrchUrl, $this->emirates_id_copy); // emirates_id_copy
		$this->BuildSearchUrl($sSrchUrl, $this->passport_number); // passport_number
		$this->BuildSearchUrl($sSrchUrl, $this->passport_ex_date); // passport_ex_date
		$this->BuildSearchUrl($sSrchUrl, $this->passport_copy); // passport_copy
		$this->BuildSearchUrl($sSrchUrl, $this->place_of_work); // place_of_work
		$this->BuildSearchUrl($sSrchUrl, $this->work_phone); // work_phone
		$this->BuildSearchUrl($sSrchUrl, $this->mobile_phone); // mobile_phone
		$this->BuildSearchUrl($sSrchUrl, $this->fax); // fax
		$this->BuildSearchUrl($sSrchUrl, $this->pobbox); // pobbox
		$this->BuildSearchUrl($sSrchUrl, $this->_email); // email
		$this->BuildSearchUrl($sSrchUrl, $this->password); // password
		$this->BuildSearchUrl($sSrchUrl, $this->admin_approval); // admin_approval
		$this->BuildSearchUrl($sSrchUrl, $this->admin_comment); // admin_comment
		$this->BuildSearchUrl($sSrchUrl, $this->forward_to_dep); // forward_to_dep
		$this->BuildSearchUrl($sSrchUrl, $this->eco_department_approval); // eco_department_approval
		$this->BuildSearchUrl($sSrchUrl, $this->eco_departmnet_comment); // eco_departmnet_comment
		$this->BuildSearchUrl($sSrchUrl, $this->security_approval); // security_approval
		$this->BuildSearchUrl($sSrchUrl, $this->security_comment); // security_comment
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
		// institution_id

		$this->institution_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_institution_id");
		$this->institution_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_institution_id");

		// full_name_ar
		$this->full_name_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_full_name_ar");
		$this->full_name_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_full_name_ar");

		// full_name_en
		$this->full_name_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_full_name_en");
		$this->full_name_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_full_name_en");

		// institution_type
		$this->institution_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_institution_type");
		$this->institution_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_institution_type");

		// institutes_name
		$this->institutes_name->AdvancedSearch->SearchValue = $objForm->GetValue("x_institutes_name");
		$this->institutes_name->AdvancedSearch->SearchOperator = $objForm->GetValue("z_institutes_name");

		// volunteering_type
		$this->volunteering_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_volunteering_type");
		$this->volunteering_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_volunteering_type");

		// licence_no
		$this->licence_no->AdvancedSearch->SearchValue = $objForm->GetValue("x_licence_no");
		$this->licence_no->AdvancedSearch->SearchOperator = $objForm->GetValue("z_licence_no");

		// trade_licence
		$this->trade_licence->AdvancedSearch->SearchValue = $objForm->GetValue("x_trade_licence");
		$this->trade_licence->AdvancedSearch->SearchOperator = $objForm->GetValue("z_trade_licence");

		// tl_expiry_date
		$this->tl_expiry_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_tl_expiry_date");
		$this->tl_expiry_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_tl_expiry_date");

		// nationality_type
		$this->nationality_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_nationality_type");
		$this->nationality_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nationality_type");

		// nationality
		$this->nationality->AdvancedSearch->SearchValue = $objForm->GetValue("x_nationality");
		$this->nationality->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nationality");

		// visa_expiry_date
		$this->visa_expiry_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_visa_expiry_date");
		$this->visa_expiry_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_visa_expiry_date");

		// unid
		$this->unid->AdvancedSearch->SearchValue = $objForm->GetValue("x_unid");
		$this->unid->AdvancedSearch->SearchOperator = $objForm->GetValue("z_unid");

		// visa_copy
		$this->visa_copy->AdvancedSearch->SearchValue = $objForm->GetValue("x_visa_copy");
		$this->visa_copy->AdvancedSearch->SearchOperator = $objForm->GetValue("z_visa_copy");

		// current_emirate
		$this->current_emirate->AdvancedSearch->SearchValue = $objForm->GetValue("x_current_emirate");
		$this->current_emirate->AdvancedSearch->SearchOperator = $objForm->GetValue("z_current_emirate");

		// full_address
		$this->full_address->AdvancedSearch->SearchValue = $objForm->GetValue("x_full_address");
		$this->full_address->AdvancedSearch->SearchOperator = $objForm->GetValue("z_full_address");

		// emirates_id_number
		$this->emirates_id_number->AdvancedSearch->SearchValue = $objForm->GetValue("x_emirates_id_number");
		$this->emirates_id_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_emirates_id_number");

		// eid_expiry_date
		$this->eid_expiry_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_eid_expiry_date");
		$this->eid_expiry_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_eid_expiry_date");

		// emirates_id_copy
		$this->emirates_id_copy->AdvancedSearch->SearchValue = $objForm->GetValue("x_emirates_id_copy");
		$this->emirates_id_copy->AdvancedSearch->SearchOperator = $objForm->GetValue("z_emirates_id_copy");

		// passport_number
		$this->passport_number->AdvancedSearch->SearchValue = $objForm->GetValue("x_passport_number");
		$this->passport_number->AdvancedSearch->SearchOperator = $objForm->GetValue("z_passport_number");

		// passport_ex_date
		$this->passport_ex_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_passport_ex_date");
		$this->passport_ex_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_passport_ex_date");

		// passport_copy
		$this->passport_copy->AdvancedSearch->SearchValue = $objForm->GetValue("x_passport_copy");
		$this->passport_copy->AdvancedSearch->SearchOperator = $objForm->GetValue("z_passport_copy");

		// place_of_work
		$this->place_of_work->AdvancedSearch->SearchValue = $objForm->GetValue("x_place_of_work");
		$this->place_of_work->AdvancedSearch->SearchOperator = $objForm->GetValue("z_place_of_work");

		// work_phone
		$this->work_phone->AdvancedSearch->SearchValue = $objForm->GetValue("x_work_phone");
		$this->work_phone->AdvancedSearch->SearchOperator = $objForm->GetValue("z_work_phone");

		// mobile_phone
		$this->mobile_phone->AdvancedSearch->SearchValue = $objForm->GetValue("x_mobile_phone");
		$this->mobile_phone->AdvancedSearch->SearchOperator = $objForm->GetValue("z_mobile_phone");

		// fax
		$this->fax->AdvancedSearch->SearchValue = $objForm->GetValue("x_fax");
		$this->fax->AdvancedSearch->SearchOperator = $objForm->GetValue("z_fax");

		// pobbox
		$this->pobbox->AdvancedSearch->SearchValue = $objForm->GetValue("x_pobbox");
		$this->pobbox->AdvancedSearch->SearchOperator = $objForm->GetValue("z_pobbox");

		// email
		$this->_email->AdvancedSearch->SearchValue = $objForm->GetValue("x__email");
		$this->_email->AdvancedSearch->SearchOperator = $objForm->GetValue("z__email");

		// password
		$this->password->AdvancedSearch->SearchValue = $objForm->GetValue("x_password");
		$this->password->AdvancedSearch->SearchOperator = $objForm->GetValue("z_password");

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_approval");
		$this->admin_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_approval");

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_comment");
		$this->admin_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_comment");

		// forward_to_dep
		$this->forward_to_dep->AdvancedSearch->SearchValue = $objForm->GetValue("x_forward_to_dep");
		$this->forward_to_dep->AdvancedSearch->SearchOperator = $objForm->GetValue("z_forward_to_dep");

		// eco_department_approval
		$this->eco_department_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_eco_department_approval");
		$this->eco_department_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_eco_department_approval");

		// eco_departmnet_comment
		$this->eco_departmnet_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_eco_departmnet_comment");
		$this->eco_departmnet_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_eco_departmnet_comment");

		// security_approval
		$this->security_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_security_approval");
		$this->security_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_security_approval");

		// security_comment
		$this->security_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_security_comment");
		$this->security_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_security_comment");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// institution_id
		// full_name_ar
		// full_name_en
		// institution_type
		// institutes_name
		// volunteering_type
		// licence_no
		// trade_licence
		// tl_expiry_date
		// nationality_type
		// nationality
		// visa_expiry_date
		// unid
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
		// work_phone
		// mobile_phone
		// fax
		// pobbox
		// email
		// password
		// admin_approval
		// admin_comment
		// forward_to_dep
		// eco_department_approval
		// eco_departmnet_comment
		// security_approval
		// security_comment

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// institution_id
		$this->institution_id->ViewValue = $this->institution_id->CurrentValue;
		$this->institution_id->ViewCustomAttributes = "";

		// full_name_ar
		$this->full_name_ar->ViewValue = $this->full_name_ar->CurrentValue;
		$this->full_name_ar->ViewCustomAttributes = "";

		// full_name_en
		$this->full_name_en->ViewValue = $this->full_name_en->CurrentValue;
		$this->full_name_en->ViewCustomAttributes = "";

		// institution_type
		if (strval($this->institution_type->CurrentValue) <> "") {
			$this->institution_type->ViewValue = $this->institution_type->OptionCaption($this->institution_type->CurrentValue);
		} else {
			$this->institution_type->ViewValue = NULL;
		}
		$this->institution_type->ViewCustomAttributes = "";

		// institutes_name
		$this->institutes_name->ViewValue = $this->institutes_name->CurrentValue;
		$this->institutes_name->ViewCustomAttributes = "";

		// volunteering_type
		if (strval($this->volunteering_type->CurrentValue) <> "") {
			$this->volunteering_type->ViewValue = $this->volunteering_type->OptionCaption($this->volunteering_type->CurrentValue);
		} else {
			$this->volunteering_type->ViewValue = NULL;
		}
		$this->volunteering_type->ViewCustomAttributes = "";

		// licence_no
		$this->licence_no->ViewValue = $this->licence_no->CurrentValue;
		$this->licence_no->ViewCustomAttributes = "";

		// trade_licence
		$this->trade_licence->UploadPath = "../images";
		if (!ew_Empty($this->trade_licence->Upload->DbValue)) {
			$this->trade_licence->ImageWidth = 300;
			$this->trade_licence->ImageHeight = 0;
			$this->trade_licence->ImageAlt = $this->trade_licence->FldAlt();
			$this->trade_licence->ViewValue = $this->trade_licence->Upload->DbValue;
		} else {
			$this->trade_licence->ViewValue = "";
		}
		$this->trade_licence->ViewCustomAttributes = "";

		// tl_expiry_date
		$this->tl_expiry_date->ViewValue = $this->tl_expiry_date->CurrentValue;
		$this->tl_expiry_date->ViewValue = ew_FormatDateTime($this->tl_expiry_date->ViewValue, 0);
		$this->tl_expiry_date->ViewCustomAttributes = "";

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

		// visa_expiry_date
		$this->visa_expiry_date->ViewValue = $this->visa_expiry_date->CurrentValue;
		$this->visa_expiry_date->ViewValue = ew_FormatDateTime($this->visa_expiry_date->ViewValue, 0);
		$this->visa_expiry_date->ViewCustomAttributes = "";

		// unid
		$this->unid->ViewValue = $this->unid->CurrentValue;
		$this->unid->ViewCustomAttributes = "";

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
			$this->emirates_id_copy->ImageWidth = 300;
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

		// forward_to_dep
		if (strval($this->forward_to_dep->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->forward_to_dep->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->forward_to_dep->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->forward_to_dep, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->forward_to_dep->ViewValue = $this->forward_to_dep->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->forward_to_dep->ViewValue = $this->forward_to_dep->CurrentValue;
			}
		} else {
			$this->forward_to_dep->ViewValue = NULL;
		}
		$this->forward_to_dep->ViewCustomAttributes = "";

		// eco_department_approval
		if (strval($this->eco_department_approval->CurrentValue) <> "") {
			$this->eco_department_approval->ViewValue = $this->eco_department_approval->OptionCaption($this->eco_department_approval->CurrentValue);
		} else {
			$this->eco_department_approval->ViewValue = NULL;
		}
		$this->eco_department_approval->ViewCustomAttributes = "";

		// eco_departmnet_comment
		$this->eco_departmnet_comment->ViewValue = $this->eco_departmnet_comment->CurrentValue;
		$this->eco_departmnet_comment->ViewCustomAttributes = "";

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

			// institution_id
			$this->institution_id->LinkCustomAttributes = "";
			$this->institution_id->HrefValue = "";
			$this->institution_id->TooltipValue = "";

			// full_name_ar
			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";
			$this->full_name_ar->TooltipValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";
			$this->full_name_en->TooltipValue = "";

			// institution_type
			$this->institution_type->LinkCustomAttributes = "";
			$this->institution_type->HrefValue = "";
			$this->institution_type->TooltipValue = "";

			// institutes_name
			$this->institutes_name->LinkCustomAttributes = "";
			$this->institutes_name->HrefValue = "";
			$this->institutes_name->TooltipValue = "";

			// volunteering_type
			$this->volunteering_type->LinkCustomAttributes = "";
			$this->volunteering_type->HrefValue = "";
			$this->volunteering_type->TooltipValue = "";

			// licence_no
			$this->licence_no->LinkCustomAttributes = "";
			$this->licence_no->HrefValue = "";
			$this->licence_no->TooltipValue = "";

			// trade_licence
			$this->trade_licence->LinkCustomAttributes = "";
			$this->trade_licence->UploadPath = "../images";
			if (!ew_Empty($this->trade_licence->Upload->DbValue)) {
				$this->trade_licence->HrefValue = "%u"; // Add prefix/suffix
				$this->trade_licence->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->trade_licence->HrefValue = ew_FullUrl($this->trade_licence->HrefValue, "href");
			} else {
				$this->trade_licence->HrefValue = "";
			}
			$this->trade_licence->HrefValue2 = $this->trade_licence->UploadPath . $this->trade_licence->Upload->DbValue;
			$this->trade_licence->TooltipValue = "";
			if ($this->trade_licence->UseColorbox) {
				if (ew_Empty($this->trade_licence->TooltipValue))
					$this->trade_licence->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->trade_licence->LinkAttrs["data-rel"] = "institutions_x_trade_licence";
				ew_AppendClass($this->trade_licence->LinkAttrs["class"], "ewLightbox");
			}

			// tl_expiry_date
			$this->tl_expiry_date->LinkCustomAttributes = "";
			$this->tl_expiry_date->HrefValue = "";
			$this->tl_expiry_date->TooltipValue = "";

			// nationality_type
			$this->nationality_type->LinkCustomAttributes = "";
			$this->nationality_type->HrefValue = "";
			$this->nationality_type->TooltipValue = "";

			// nationality
			$this->nationality->LinkCustomAttributes = "";
			$this->nationality->HrefValue = "";
			$this->nationality->TooltipValue = "";

			// visa_expiry_date
			$this->visa_expiry_date->LinkCustomAttributes = "";
			$this->visa_expiry_date->HrefValue = "";
			$this->visa_expiry_date->TooltipValue = "";

			// unid
			$this->unid->LinkCustomAttributes = "";
			$this->unid->HrefValue = "";
			$this->unid->TooltipValue = "";

			// visa_copy
			$this->visa_copy->LinkCustomAttributes = "";
			$this->visa_copy->UploadPath = "../images";
			if (!ew_Empty($this->visa_copy->Upload->DbValue)) {
				$this->visa_copy->HrefValue = ew_GetFileUploadUrl($this->visa_copy, $this->visa_copy->Upload->DbValue); // Add prefix/suffix
				$this->visa_copy->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->visa_copy->HrefValue = ew_FullUrl($this->visa_copy->HrefValue, "href");
			} else {
				$this->visa_copy->HrefValue = "";
			}
			$this->visa_copy->HrefValue2 = $this->visa_copy->UploadPath . $this->visa_copy->Upload->DbValue;
			$this->visa_copy->TooltipValue = "";
			if ($this->visa_copy->UseColorbox) {
				if (ew_Empty($this->visa_copy->TooltipValue))
					$this->visa_copy->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->visa_copy->LinkAttrs["data-rel"] = "institutions_x_visa_copy";
				ew_AppendClass($this->visa_copy->LinkAttrs["class"], "ewLightbox");
			}

			// current_emirate
			$this->current_emirate->LinkCustomAttributes = "";
			$this->current_emirate->HrefValue = "";
			$this->current_emirate->TooltipValue = "";

			// full_address
			$this->full_address->LinkCustomAttributes = "";
			$this->full_address->HrefValue = "";
			$this->full_address->TooltipValue = "";

			// emirates_id_number
			$this->emirates_id_number->LinkCustomAttributes = "";
			$this->emirates_id_number->HrefValue = "";
			$this->emirates_id_number->TooltipValue = "";

			// eid_expiry_date
			$this->eid_expiry_date->LinkCustomAttributes = "";
			$this->eid_expiry_date->HrefValue = "";
			$this->eid_expiry_date->TooltipValue = "";

			// emirates_id_copy
			$this->emirates_id_copy->LinkCustomAttributes = "";
			$this->emirates_id_copy->UploadPath = "../images";
			if (!ew_Empty($this->emirates_id_copy->Upload->DbValue)) {
				$this->emirates_id_copy->HrefValue = "%u"; // Add prefix/suffix
				$this->emirates_id_copy->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->emirates_id_copy->HrefValue = ew_FullUrl($this->emirates_id_copy->HrefValue, "href");
			} else {
				$this->emirates_id_copy->HrefValue = "";
			}
			$this->emirates_id_copy->HrefValue2 = $this->emirates_id_copy->UploadPath . $this->emirates_id_copy->Upload->DbValue;
			$this->emirates_id_copy->TooltipValue = "";
			if ($this->emirates_id_copy->UseColorbox) {
				if (ew_Empty($this->emirates_id_copy->TooltipValue))
					$this->emirates_id_copy->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->emirates_id_copy->LinkAttrs["data-rel"] = "institutions_x_emirates_id_copy";
				ew_AppendClass($this->emirates_id_copy->LinkAttrs["class"], "ewLightbox");
			}

			// passport_number
			$this->passport_number->LinkCustomAttributes = "";
			$this->passport_number->HrefValue = "";
			$this->passport_number->TooltipValue = "";

			// passport_ex_date
			$this->passport_ex_date->LinkCustomAttributes = "";
			$this->passport_ex_date->HrefValue = "";
			$this->passport_ex_date->TooltipValue = "";

			// passport_copy
			$this->passport_copy->LinkCustomAttributes = "";
			$this->passport_copy->UploadPath = "../images";
			if (!ew_Empty($this->passport_copy->Upload->DbValue)) {
				$this->passport_copy->HrefValue = "%u"; // Add prefix/suffix
				$this->passport_copy->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->passport_copy->HrefValue = ew_FullUrl($this->passport_copy->HrefValue, "href");
			} else {
				$this->passport_copy->HrefValue = "";
			}
			$this->passport_copy->HrefValue2 = $this->passport_copy->UploadPath . $this->passport_copy->Upload->DbValue;
			$this->passport_copy->TooltipValue = "";
			if ($this->passport_copy->UseColorbox) {
				if (ew_Empty($this->passport_copy->TooltipValue))
					$this->passport_copy->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->passport_copy->LinkAttrs["data-rel"] = "institutions_x_passport_copy";
				ew_AppendClass($this->passport_copy->LinkAttrs["class"], "ewLightbox");
			}

			// place_of_work
			$this->place_of_work->LinkCustomAttributes = "";
			$this->place_of_work->HrefValue = "";
			$this->place_of_work->TooltipValue = "";

			// work_phone
			$this->work_phone->LinkCustomAttributes = "";
			$this->work_phone->HrefValue = "";
			$this->work_phone->TooltipValue = "";

			// mobile_phone
			$this->mobile_phone->LinkCustomAttributes = "";
			$this->mobile_phone->HrefValue = "";
			$this->mobile_phone->TooltipValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";
			$this->fax->TooltipValue = "";

			// pobbox
			$this->pobbox->LinkCustomAttributes = "";
			$this->pobbox->HrefValue = "";
			$this->pobbox->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";
			$this->password->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";

			// forward_to_dep
			$this->forward_to_dep->LinkCustomAttributes = "";
			$this->forward_to_dep->HrefValue = "";
			$this->forward_to_dep->TooltipValue = "";

			// eco_department_approval
			$this->eco_department_approval->LinkCustomAttributes = "";
			$this->eco_department_approval->HrefValue = "";
			$this->eco_department_approval->TooltipValue = "";

			// eco_departmnet_comment
			$this->eco_departmnet_comment->LinkCustomAttributes = "";
			$this->eco_departmnet_comment->HrefValue = "";
			$this->eco_departmnet_comment->TooltipValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";
			$this->security_approval->TooltipValue = "";

			// security_comment
			$this->security_comment->LinkCustomAttributes = "";
			$this->security_comment->HrefValue = "";
			$this->security_comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// institution_id
			$this->institution_id->EditAttrs["class"] = "form-control";
			$this->institution_id->EditCustomAttributes = "";
			$this->institution_id->EditValue = ew_HtmlEncode($this->institution_id->AdvancedSearch->SearchValue);
			$this->institution_id->PlaceHolder = ew_RemoveHtml($this->institution_id->FldCaption());

			// full_name_ar
			$this->full_name_ar->EditAttrs["class"] = "form-control";
			$this->full_name_ar->EditCustomAttributes = "";
			$this->full_name_ar->EditValue = ew_HtmlEncode($this->full_name_ar->AdvancedSearch->SearchValue);
			$this->full_name_ar->PlaceHolder = ew_RemoveHtml($this->full_name_ar->FldCaption());

			// full_name_en
			$this->full_name_en->EditAttrs["class"] = "form-control";
			$this->full_name_en->EditCustomAttributes = "";
			$this->full_name_en->EditValue = ew_HtmlEncode($this->full_name_en->AdvancedSearch->SearchValue);
			$this->full_name_en->PlaceHolder = ew_RemoveHtml($this->full_name_en->FldCaption());

			// institution_type
			$this->institution_type->EditCustomAttributes = "";
			$this->institution_type->EditValue = $this->institution_type->Options(FALSE);

			// institutes_name
			$this->institutes_name->EditAttrs["class"] = "form-control";
			$this->institutes_name->EditCustomAttributes = "";
			$this->institutes_name->EditValue = ew_HtmlEncode($this->institutes_name->AdvancedSearch->SearchValue);
			$this->institutes_name->PlaceHolder = ew_RemoveHtml($this->institutes_name->FldCaption());

			// volunteering_type
			$this->volunteering_type->EditCustomAttributes = "";
			$this->volunteering_type->EditValue = $this->volunteering_type->Options(FALSE);

			// licence_no
			$this->licence_no->EditAttrs["class"] = "form-control";
			$this->licence_no->EditCustomAttributes = "";
			$this->licence_no->EditValue = ew_HtmlEncode($this->licence_no->AdvancedSearch->SearchValue);
			$this->licence_no->PlaceHolder = ew_RemoveHtml($this->licence_no->FldCaption());

			// trade_licence
			$this->trade_licence->EditAttrs["class"] = "form-control";
			$this->trade_licence->EditCustomAttributes = "";
			$this->trade_licence->EditValue = ew_HtmlEncode($this->trade_licence->AdvancedSearch->SearchValue);
			$this->trade_licence->PlaceHolder = ew_RemoveHtml($this->trade_licence->FldCaption());

			// tl_expiry_date
			$this->tl_expiry_date->EditAttrs["class"] = "form-control";
			$this->tl_expiry_date->EditCustomAttributes = "";
			$this->tl_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->tl_expiry_date->AdvancedSearch->SearchValue, 0), 8));
			$this->tl_expiry_date->PlaceHolder = ew_RemoveHtml($this->tl_expiry_date->FldCaption());

			// nationality_type
			$this->nationality_type->EditCustomAttributes = "";
			$this->nationality_type->EditValue = $this->nationality_type->Options(FALSE);

			// nationality
			$this->nationality->EditAttrs["class"] = "form-control";
			$this->nationality->EditCustomAttributes = "";
			$this->nationality->EditValue = ew_HtmlEncode($this->nationality->AdvancedSearch->SearchValue);
			$this->nationality->PlaceHolder = ew_RemoveHtml($this->nationality->FldCaption());

			// visa_expiry_date
			$this->visa_expiry_date->EditAttrs["class"] = "form-control";
			$this->visa_expiry_date->EditCustomAttributes = "";
			$this->visa_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->visa_expiry_date->AdvancedSearch->SearchValue, 0), 8));
			$this->visa_expiry_date->PlaceHolder = ew_RemoveHtml($this->visa_expiry_date->FldCaption());

			// unid
			$this->unid->EditAttrs["class"] = "form-control";
			$this->unid->EditCustomAttributes = "";
			$this->unid->EditValue = ew_HtmlEncode($this->unid->AdvancedSearch->SearchValue);
			$this->unid->PlaceHolder = ew_RemoveHtml($this->unid->FldCaption());

			// visa_copy
			$this->visa_copy->EditAttrs["class"] = "form-control";
			$this->visa_copy->EditCustomAttributes = "";
			$this->visa_copy->EditValue = ew_HtmlEncode($this->visa_copy->AdvancedSearch->SearchValue);
			$this->visa_copy->PlaceHolder = ew_RemoveHtml($this->visa_copy->FldCaption());

			// current_emirate
			$this->current_emirate->EditAttrs["class"] = "form-control";
			$this->current_emirate->EditCustomAttributes = "";
			$this->current_emirate->EditValue = $this->current_emirate->Options(TRUE);

			// full_address
			$this->full_address->EditAttrs["class"] = "form-control";
			$this->full_address->EditCustomAttributes = "";
			$this->full_address->EditValue = ew_HtmlEncode($this->full_address->AdvancedSearch->SearchValue);
			$this->full_address->PlaceHolder = ew_RemoveHtml($this->full_address->FldCaption());

			// emirates_id_number
			$this->emirates_id_number->EditAttrs["class"] = "form-control";
			$this->emirates_id_number->EditCustomAttributes = "";
			$this->emirates_id_number->EditValue = ew_HtmlEncode($this->emirates_id_number->AdvancedSearch->SearchValue);
			$this->emirates_id_number->PlaceHolder = ew_RemoveHtml($this->emirates_id_number->FldCaption());

			// eid_expiry_date
			$this->eid_expiry_date->EditAttrs["class"] = "form-control";
			$this->eid_expiry_date->EditCustomAttributes = "";
			$this->eid_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->eid_expiry_date->AdvancedSearch->SearchValue, 0), 8));
			$this->eid_expiry_date->PlaceHolder = ew_RemoveHtml($this->eid_expiry_date->FldCaption());

			// emirates_id_copy
			$this->emirates_id_copy->EditAttrs["class"] = "form-control";
			$this->emirates_id_copy->EditCustomAttributes = "";
			$this->emirates_id_copy->EditValue = ew_HtmlEncode($this->emirates_id_copy->AdvancedSearch->SearchValue);
			$this->emirates_id_copy->PlaceHolder = ew_RemoveHtml($this->emirates_id_copy->FldCaption());

			// passport_number
			$this->passport_number->EditAttrs["class"] = "form-control";
			$this->passport_number->EditCustomAttributes = "";
			$this->passport_number->EditValue = ew_HtmlEncode($this->passport_number->AdvancedSearch->SearchValue);
			$this->passport_number->PlaceHolder = ew_RemoveHtml($this->passport_number->FldCaption());

			// passport_ex_date
			$this->passport_ex_date->EditAttrs["class"] = "form-control";
			$this->passport_ex_date->EditCustomAttributes = "";
			$this->passport_ex_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->passport_ex_date->AdvancedSearch->SearchValue, 0), 8));
			$this->passport_ex_date->PlaceHolder = ew_RemoveHtml($this->passport_ex_date->FldCaption());

			// passport_copy
			$this->passport_copy->EditAttrs["class"] = "form-control";
			$this->passport_copy->EditCustomAttributes = "";
			$this->passport_copy->EditValue = ew_HtmlEncode($this->passport_copy->AdvancedSearch->SearchValue);
			$this->passport_copy->PlaceHolder = ew_RemoveHtml($this->passport_copy->FldCaption());

			// place_of_work
			$this->place_of_work->EditAttrs["class"] = "form-control";
			$this->place_of_work->EditCustomAttributes = "";
			$this->place_of_work->EditValue = ew_HtmlEncode($this->place_of_work->AdvancedSearch->SearchValue);
			$this->place_of_work->PlaceHolder = ew_RemoveHtml($this->place_of_work->FldCaption());

			// work_phone
			$this->work_phone->EditAttrs["class"] = "form-control";
			$this->work_phone->EditCustomAttributes = "";
			$this->work_phone->EditValue = ew_HtmlEncode($this->work_phone->AdvancedSearch->SearchValue);
			$this->work_phone->PlaceHolder = ew_RemoveHtml($this->work_phone->FldCaption());

			// mobile_phone
			$this->mobile_phone->EditAttrs["class"] = "form-control";
			$this->mobile_phone->EditCustomAttributes = "";
			$this->mobile_phone->EditValue = ew_HtmlEncode($this->mobile_phone->AdvancedSearch->SearchValue);
			$this->mobile_phone->PlaceHolder = ew_RemoveHtml($this->mobile_phone->FldCaption());

			// fax
			$this->fax->EditAttrs["class"] = "form-control";
			$this->fax->EditCustomAttributes = "";
			$this->fax->EditValue = ew_HtmlEncode($this->fax->AdvancedSearch->SearchValue);
			$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

			// pobbox
			$this->pobbox->EditAttrs["class"] = "form-control";
			$this->pobbox->EditCustomAttributes = "";
			$this->pobbox->EditValue = ew_HtmlEncode($this->pobbox->AdvancedSearch->SearchValue);
			$this->pobbox->PlaceHolder = ew_RemoveHtml($this->pobbox->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->AdvancedSearch->SearchValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->AdvancedSearch->SearchValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// forward_to_dep
			$this->forward_to_dep->EditAttrs["class"] = "form-control";
			$this->forward_to_dep->EditCustomAttributes = "";
			if (trim(strval($this->forward_to_dep->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->forward_to_dep->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->forward_to_dep->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->forward_to_dep, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->forward_to_dep->EditValue = $arwrk;

			// eco_department_approval
			$this->eco_department_approval->EditCustomAttributes = "";
			$this->eco_department_approval->EditValue = $this->eco_department_approval->Options(FALSE);

			// eco_departmnet_comment
			$this->eco_departmnet_comment->EditAttrs["class"] = "form-control";
			$this->eco_departmnet_comment->EditCustomAttributes = "";
			$this->eco_departmnet_comment->EditValue = ew_HtmlEncode($this->eco_departmnet_comment->AdvancedSearch->SearchValue);
			$this->eco_departmnet_comment->PlaceHolder = ew_RemoveHtml($this->eco_departmnet_comment->FldCaption());

			// security_approval
			$this->security_approval->EditCustomAttributes = "";
			$this->security_approval->EditValue = $this->security_approval->Options(FALSE);

			// security_comment
			$this->security_comment->EditAttrs["class"] = "form-control";
			$this->security_comment->EditCustomAttributes = "";
			$this->security_comment->EditValue = ew_HtmlEncode($this->security_comment->AdvancedSearch->SearchValue);
			$this->security_comment->PlaceHolder = ew_RemoveHtml($this->security_comment->FldCaption());
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
		if (!ew_CheckInteger($this->institution_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->institution_id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->tl_expiry_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->tl_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->visa_expiry_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->visa_expiry_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->unid->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->unid->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->eid_expiry_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->eid_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->passport_ex_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->passport_ex_date->FldErrMsg());
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
		$this->institution_id->AdvancedSearch->Load();
		$this->full_name_ar->AdvancedSearch->Load();
		$this->full_name_en->AdvancedSearch->Load();
		$this->institution_type->AdvancedSearch->Load();
		$this->institutes_name->AdvancedSearch->Load();
		$this->volunteering_type->AdvancedSearch->Load();
		$this->licence_no->AdvancedSearch->Load();
		$this->trade_licence->AdvancedSearch->Load();
		$this->tl_expiry_date->AdvancedSearch->Load();
		$this->nationality_type->AdvancedSearch->Load();
		$this->nationality->AdvancedSearch->Load();
		$this->visa_expiry_date->AdvancedSearch->Load();
		$this->unid->AdvancedSearch->Load();
		$this->visa_copy->AdvancedSearch->Load();
		$this->current_emirate->AdvancedSearch->Load();
		$this->full_address->AdvancedSearch->Load();
		$this->emirates_id_number->AdvancedSearch->Load();
		$this->eid_expiry_date->AdvancedSearch->Load();
		$this->emirates_id_copy->AdvancedSearch->Load();
		$this->passport_number->AdvancedSearch->Load();
		$this->passport_ex_date->AdvancedSearch->Load();
		$this->passport_copy->AdvancedSearch->Load();
		$this->place_of_work->AdvancedSearch->Load();
		$this->work_phone->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->pobbox->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->forward_to_dep->AdvancedSearch->Load();
		$this->eco_department_approval->AdvancedSearch->Load();
		$this->eco_departmnet_comment->AdvancedSearch->Load();
		$this->security_approval->AdvancedSearch->Load();
		$this->security_comment->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institutionslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Set up multi pages
	function SetupMultiPages() {
		$pages = new cSubPages();
		$pages->Style = "tabs";
		$pages->Add(0);
		$pages->Add(1);
		$pages->Add(2);
		$pages->Add(3);
		$pages->Add(4);
		$pages->Add(5);
		$pages->Add(6);
		$pages->Add(7);
		$pages->Add(8);
		$pages->Add(9);
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_forward_to_dep":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$this->forward_to_dep->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`userlevelid` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->forward_to_dep, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($institutions_search)) $institutions_search = new cinstitutions_search();

// Page init
$institutions_search->Page_Init();

// Page main
$institutions_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($institutions_search->IsModal) { ?>
var CurrentAdvancedSearchForm = finstitutionssearch = new ew_Form("finstitutionssearch", "search");
<?php } else { ?>
var CurrentForm = finstitutionssearch = new ew_Form("finstitutionssearch", "search");
<?php } ?>

// Form_CustomValidate event
finstitutionssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutionssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
finstitutionssearch.MultiPage = new ew_MultiPage("finstitutionssearch");

// Dynamic selection lists
finstitutionssearch.Lists["x_institution_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_institution_type"].Options = <?php echo json_encode($institutions_search->institution_type->Options()) ?>;
finstitutionssearch.Lists["x_volunteering_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_volunteering_type"].Options = <?php echo json_encode($institutions_search->volunteering_type->Options()) ?>;
finstitutionssearch.Lists["x_nationality_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_nationality_type"].Options = <?php echo json_encode($institutions_search->nationality_type->Options()) ?>;
finstitutionssearch.Lists["x_current_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_current_emirate"].Options = <?php echo json_encode($institutions_search->current_emirate->Options()) ?>;
finstitutionssearch.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_search->admin_approval->Options()) ?>;
finstitutionssearch.Lists["x_forward_to_dep"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
finstitutionssearch.Lists["x_forward_to_dep"].Data = "<?php echo $institutions_search->forward_to_dep->LookupFilterQuery(FALSE, "search") ?>";
finstitutionssearch.Lists["x_eco_department_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_eco_department_approval"].Options = <?php echo json_encode($institutions_search->eco_department_approval->Options()) ?>;
finstitutionssearch.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionssearch.Lists["x_security_approval"].Options = <?php echo json_encode($institutions_search->security_approval->Options()) ?>;

// Form object for search
// Validate function for search

finstitutionssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_institution_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->institution_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_tl_expiry_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->tl_expiry_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_visa_expiry_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->visa_expiry_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_unid");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->unid->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_eid_expiry_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->eid_expiry_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_passport_ex_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->passport_ex_date->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $institutions_search->ShowPageHeader(); ?>
<?php
$institutions_search->ShowMessage();
?>
<form name="finstitutionssearch" id="finstitutionssearch" class="<?php echo $institutions_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($institutions_search->IsModal) ?>">
<?php if (!$institutions_search->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="institutions_search"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $institutions_search->MultiPages->NavStyle() ?>">
		<li<?php echo $institutions_search->MultiPages->TabStyle("1") ?>><a href="#tab_institutions1" data-toggle="tab"><?php echo $institutions->PageCaption(1) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("2") ?>><a href="#tab_institutions2" data-toggle="tab"><?php echo $institutions->PageCaption(2) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("3") ?>><a href="#tab_institutions3" data-toggle="tab"><?php echo $institutions->PageCaption(3) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("4") ?>><a href="#tab_institutions4" data-toggle="tab"><?php echo $institutions->PageCaption(4) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("5") ?>><a href="#tab_institutions5" data-toggle="tab"><?php echo $institutions->PageCaption(5) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("6") ?>><a href="#tab_institutions6" data-toggle="tab"><?php echo $institutions->PageCaption(6) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("7") ?>><a href="#tab_institutions7" data-toggle="tab"><?php echo $institutions->PageCaption(7) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("8") ?>><a href="#tab_institutions8" data-toggle="tab"><?php echo $institutions->PageCaption(8) ?></a></li>
		<li<?php echo $institutions_search->MultiPages->TabStyle("9") ?>><a href="#tab_institutions9" data-toggle="tab"><?php echo $institutions->PageCaption(9) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("1") ?>" id="tab_institutions1"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch1" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->institution_id->Visible) { // institution_id ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_institution_id" class="form-group">
		<label for="x_institution_id" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_institution_id"><?php echo $institutions->institution_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_institution_id" id="z_institution_id" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->institution_id->CellAttributes() ?>>
			<span id="el_institutions_institution_id">
<input type="text" data-table="institutions" data-field="x_institution_id" data-page="1" name="x_institution_id" id="x_institution_id" size="30" placeholder="<?php echo ew_HtmlEncode($institutions->institution_id->getPlaceHolder()) ?>" value="<?php echo $institutions->institution_id->EditValue ?>"<?php echo $institutions->institution_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_institution_id">
		<td class="col-sm-2"><span id="elh_institutions_institution_id"><?php echo $institutions->institution_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_institution_id" id="z_institution_id" value="="></span></td>
		<td<?php echo $institutions->institution_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_institution_id">
<input type="text" data-table="institutions" data-field="x_institution_id" data-page="1" name="x_institution_id" id="x_institution_id" size="30" placeholder="<?php echo ew_HtmlEncode($institutions->institution_id->getPlaceHolder()) ?>" value="<?php echo $institutions->institution_id->EditValue ?>"<?php echo $institutions->institution_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->full_name_ar->Visible) { // full_name_ar ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_full_name_ar" class="form-group">
		<label for="x_full_name_ar" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_full_name_ar"><?php echo $institutions->full_name_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_ar" id="z_full_name_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->full_name_ar->CellAttributes() ?>>
			<span id="el_institutions_full_name_ar">
<input type="text" data-table="institutions" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_ar->EditValue ?>"<?php echo $institutions->full_name_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_ar">
		<td class="col-sm-2"><span id="elh_institutions_full_name_ar"><?php echo $institutions->full_name_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_ar" id="z_full_name_ar" value="LIKE"></span></td>
		<td<?php echo $institutions->full_name_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_full_name_ar">
<input type="text" data-table="institutions" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_ar->EditValue ?>"<?php echo $institutions->full_name_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->full_name_en->Visible) { // full_name_en ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_full_name_en" class="form-group">
		<label for="x_full_name_en" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_full_name_en"><?php echo $institutions->full_name_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_en" id="z_full_name_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->full_name_en->CellAttributes() ?>>
			<span id="el_institutions_full_name_en">
<input type="text" data-table="institutions" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_en->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_en->EditValue ?>"<?php echo $institutions->full_name_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_en">
		<td class="col-sm-2"><span id="elh_institutions_full_name_en"><?php echo $institutions->full_name_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_en" id="z_full_name_en" value="LIKE"></span></td>
		<td<?php echo $institutions->full_name_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_full_name_en">
<input type="text" data-table="institutions" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_en->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_en->EditValue ?>"<?php echo $institutions->full_name_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->institution_type->Visible) { // institution_type ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_institution_type" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_institution_type"><?php echo $institutions->institution_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_institution_type" id="z_institution_type" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->institution_type->CellAttributes() ?>>
			<span id="el_institutions_institution_type">
<div id="tp_x_institution_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_institution_type" data-page="1" data-value-separator="<?php echo $institutions->institution_type->DisplayValueSeparatorAttribute() ?>" name="x_institution_type" id="x_institution_type" value="{value}"<?php echo $institutions->institution_type->EditAttributes() ?>></div>
<div id="dsl_x_institution_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->institution_type->RadioButtonListHtml(FALSE, "x_institution_type", 1) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_institution_type">
		<td class="col-sm-2"><span id="elh_institutions_institution_type"><?php echo $institutions->institution_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_institution_type" id="z_institution_type" value="LIKE"></span></td>
		<td<?php echo $institutions->institution_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_institution_type">
<div id="tp_x_institution_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_institution_type" data-page="1" data-value-separator="<?php echo $institutions->institution_type->DisplayValueSeparatorAttribute() ?>" name="x_institution_type" id="x_institution_type" value="{value}"<?php echo $institutions->institution_type->EditAttributes() ?>></div>
<div id="dsl_x_institution_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->institution_type->RadioButtonListHtml(FALSE, "x_institution_type", 1) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->institutes_name->Visible) { // institutes_name ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_institutes_name" class="form-group">
		<label for="x_institutes_name" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_institutes_name"><?php echo $institutions->institutes_name->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_institutes_name" id="z_institutes_name" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->institutes_name->CellAttributes() ?>>
			<span id="el_institutions_institutes_name">
<input type="text" data-table="institutions" data-field="x_institutes_name" data-page="1" name="x_institutes_name" id="x_institutes_name" placeholder="<?php echo ew_HtmlEncode($institutions->institutes_name->getPlaceHolder()) ?>" value="<?php echo $institutions->institutes_name->EditValue ?>"<?php echo $institutions->institutes_name->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_institutes_name">
		<td class="col-sm-2"><span id="elh_institutions_institutes_name"><?php echo $institutions->institutes_name->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_institutes_name" id="z_institutes_name" value="LIKE"></span></td>
		<td<?php echo $institutions->institutes_name->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_institutes_name">
<input type="text" data-table="institutions" data-field="x_institutes_name" data-page="1" name="x_institutes_name" id="x_institutes_name" placeholder="<?php echo ew_HtmlEncode($institutions->institutes_name->getPlaceHolder()) ?>" value="<?php echo $institutions->institutes_name->EditValue ?>"<?php echo $institutions->institutes_name->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->volunteering_type->Visible) { // volunteering_type ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_volunteering_type" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_volunteering_type"><?php echo $institutions->volunteering_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_volunteering_type" id="z_volunteering_type" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->volunteering_type->CellAttributes() ?>>
			<span id="el_institutions_volunteering_type">
<div id="tp_x_volunteering_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $institutions->volunteering_type->DisplayValueSeparatorAttribute() ?>" name="x_volunteering_type" id="x_volunteering_type" value="{value}"<?php echo $institutions->volunteering_type->EditAttributes() ?>></div>
<div id="dsl_x_volunteering_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->volunteering_type->RadioButtonListHtml(FALSE, "x_volunteering_type", 1) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_volunteering_type">
		<td class="col-sm-2"><span id="elh_institutions_volunteering_type"><?php echo $institutions->volunteering_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_volunteering_type" id="z_volunteering_type" value="="></span></td>
		<td<?php echo $institutions->volunteering_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_volunteering_type">
<div id="tp_x_volunteering_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $institutions->volunteering_type->DisplayValueSeparatorAttribute() ?>" name="x_volunteering_type" id="x_volunteering_type" value="{value}"<?php echo $institutions->volunteering_type->EditAttributes() ?>></div>
<div id="dsl_x_volunteering_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->volunteering_type->RadioButtonListHtml(FALSE, "x_volunteering_type", 1) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("2") ?>" id="tab_institutions2"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch2" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->licence_no->Visible) { // licence_no ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_licence_no" class="form-group">
		<label for="x_licence_no" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_licence_no"><?php echo $institutions->licence_no->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_licence_no" id="z_licence_no" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->licence_no->CellAttributes() ?>>
			<span id="el_institutions_licence_no">
<input type="text" data-table="institutions" data-field="x_licence_no" data-page="2" name="x_licence_no" id="x_licence_no" placeholder="<?php echo ew_HtmlEncode($institutions->licence_no->getPlaceHolder()) ?>" value="<?php echo $institutions->licence_no->EditValue ?>"<?php echo $institutions->licence_no->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_licence_no">
		<td class="col-sm-2"><span id="elh_institutions_licence_no"><?php echo $institutions->licence_no->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_licence_no" id="z_licence_no" value="LIKE"></span></td>
		<td<?php echo $institutions->licence_no->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_licence_no">
<input type="text" data-table="institutions" data-field="x_licence_no" data-page="2" name="x_licence_no" id="x_licence_no" placeholder="<?php echo ew_HtmlEncode($institutions->licence_no->getPlaceHolder()) ?>" value="<?php echo $institutions->licence_no->EditValue ?>"<?php echo $institutions->licence_no->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->trade_licence->Visible) { // trade_licence ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_trade_licence" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_trade_licence"><?php echo $institutions->trade_licence->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_trade_licence" id="z_trade_licence" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->trade_licence->CellAttributes() ?>>
			<span id="el_institutions_trade_licence">
<input type="text" data-table="institutions" data-field="x_trade_licence" data-page="2" name="x_trade_licence" id="x_trade_licence" placeholder="<?php echo ew_HtmlEncode($institutions->trade_licence->getPlaceHolder()) ?>" value="<?php echo $institutions->trade_licence->EditValue ?>"<?php echo $institutions->trade_licence->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_trade_licence">
		<td class="col-sm-2"><span id="elh_institutions_trade_licence"><?php echo $institutions->trade_licence->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_trade_licence" id="z_trade_licence" value="LIKE"></span></td>
		<td<?php echo $institutions->trade_licence->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_trade_licence">
<input type="text" data-table="institutions" data-field="x_trade_licence" data-page="2" name="x_trade_licence" id="x_trade_licence" placeholder="<?php echo ew_HtmlEncode($institutions->trade_licence->getPlaceHolder()) ?>" value="<?php echo $institutions->trade_licence->EditValue ?>"<?php echo $institutions->trade_licence->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->tl_expiry_date->Visible) { // tl_expiry_date ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_tl_expiry_date" class="form-group">
		<label for="x_tl_expiry_date" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_tl_expiry_date"><?php echo $institutions->tl_expiry_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tl_expiry_date" id="z_tl_expiry_date" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->tl_expiry_date->CellAttributes() ?>>
			<span id="el_institutions_tl_expiry_date">
<input type="text" data-table="institutions" data-field="x_tl_expiry_date" data-page="2" name="x_tl_expiry_date" id="x_tl_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->tl_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->tl_expiry_date->EditValue ?>"<?php echo $institutions->tl_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->tl_expiry_date->ReadOnly && !$institutions->tl_expiry_date->Disabled && !isset($institutions->tl_expiry_date->EditAttrs["readonly"]) && !isset($institutions->tl_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_tl_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_tl_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_tl_expiry_date"><?php echo $institutions->tl_expiry_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_tl_expiry_date" id="z_tl_expiry_date" value="="></span></td>
		<td<?php echo $institutions->tl_expiry_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_tl_expiry_date">
<input type="text" data-table="institutions" data-field="x_tl_expiry_date" data-page="2" name="x_tl_expiry_date" id="x_tl_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->tl_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->tl_expiry_date->EditValue ?>"<?php echo $institutions->tl_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->tl_expiry_date->ReadOnly && !$institutions->tl_expiry_date->Disabled && !isset($institutions->tl_expiry_date->EditAttrs["readonly"]) && !isset($institutions->tl_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_tl_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("3") ?>" id="tab_institutions3"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch3" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->nationality_type->Visible) { // nationality_type ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_nationality_type" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_nationality_type"><?php echo $institutions->nationality_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationality_type" id="z_nationality_type" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->nationality_type->CellAttributes() ?>>
			<span id="el_institutions_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_nationality_type" data-page="3" data-value-separator="<?php echo $institutions->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $institutions->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 3) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality_type">
		<td class="col-sm-2"><span id="elh_institutions_nationality_type"><?php echo $institutions->nationality_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationality_type" id="z_nationality_type" value="LIKE"></span></td>
		<td<?php echo $institutions->nationality_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_nationality_type" data-page="3" data-value-separator="<?php echo $institutions->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $institutions->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 3) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->nationality->Visible) { // nationality ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_nationality" class="form-group">
		<label for="x_nationality" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_nationality"><?php echo $institutions->nationality->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationality" id="z_nationality" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->nationality->CellAttributes() ?>>
			<span id="el_institutions_nationality">
<input type="text" data-table="institutions" data-field="x_nationality" data-page="3" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($institutions->nationality->getPlaceHolder()) ?>" value="<?php echo $institutions->nationality->EditValue ?>"<?php echo $institutions->nationality->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality">
		<td class="col-sm-2"><span id="elh_institutions_nationality"><?php echo $institutions->nationality->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationality" id="z_nationality" value="LIKE"></span></td>
		<td<?php echo $institutions->nationality->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_nationality">
<input type="text" data-table="institutions" data-field="x_nationality" data-page="3" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($institutions->nationality->getPlaceHolder()) ?>" value="<?php echo $institutions->nationality->EditValue ?>"<?php echo $institutions->nationality->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->visa_expiry_date->Visible) { // visa_expiry_date ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_visa_expiry_date" class="form-group">
		<label for="x_visa_expiry_date" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_visa_expiry_date"><?php echo $institutions->visa_expiry_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_visa_expiry_date" id="z_visa_expiry_date" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->visa_expiry_date->CellAttributes() ?>>
			<span id="el_institutions_visa_expiry_date">
<input type="text" data-table="institutions" data-field="x_visa_expiry_date" data-page="3" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->visa_expiry_date->EditValue ?>"<?php echo $institutions->visa_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->visa_expiry_date->ReadOnly && !$institutions->visa_expiry_date->Disabled && !isset($institutions->visa_expiry_date->EditAttrs["readonly"]) && !isset($institutions->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_visa_expiry_date"><?php echo $institutions->visa_expiry_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_visa_expiry_date" id="z_visa_expiry_date" value="="></span></td>
		<td<?php echo $institutions->visa_expiry_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_visa_expiry_date">
<input type="text" data-table="institutions" data-field="x_visa_expiry_date" data-page="3" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->visa_expiry_date->EditValue ?>"<?php echo $institutions->visa_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->visa_expiry_date->ReadOnly && !$institutions->visa_expiry_date->Disabled && !isset($institutions->visa_expiry_date->EditAttrs["readonly"]) && !isset($institutions->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->unid->Visible) { // unid ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_unid" class="form-group">
		<label for="x_unid" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_unid"><?php echo $institutions->unid->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_unid" id="z_unid" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->unid->CellAttributes() ?>>
			<span id="el_institutions_unid">
<input type="text" data-table="institutions" data-field="x_unid" data-page="3" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($institutions->unid->getPlaceHolder()) ?>" value="<?php echo $institutions->unid->EditValue ?>"<?php echo $institutions->unid->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_unid">
		<td class="col-sm-2"><span id="elh_institutions_unid"><?php echo $institutions->unid->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_unid" id="z_unid" value="="></span></td>
		<td<?php echo $institutions->unid->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_unid">
<input type="text" data-table="institutions" data-field="x_unid" data-page="3" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($institutions->unid->getPlaceHolder()) ?>" value="<?php echo $institutions->unid->EditValue ?>"<?php echo $institutions->unid->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->visa_copy->Visible) { // visa_copy ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_visa_copy" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_visa_copy"><?php echo $institutions->visa_copy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_visa_copy" id="z_visa_copy" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->visa_copy->CellAttributes() ?>>
			<span id="el_institutions_visa_copy">
<input type="text" data-table="institutions" data-field="x_visa_copy" data-page="3" name="x_visa_copy" id="x_visa_copy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($institutions->visa_copy->getPlaceHolder()) ?>" value="<?php echo $institutions->visa_copy->EditValue ?>"<?php echo $institutions->visa_copy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_copy">
		<td class="col-sm-2"><span id="elh_institutions_visa_copy"><?php echo $institutions->visa_copy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_visa_copy" id="z_visa_copy" value="LIKE"></span></td>
		<td<?php echo $institutions->visa_copy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_visa_copy">
<input type="text" data-table="institutions" data-field="x_visa_copy" data-page="3" name="x_visa_copy" id="x_visa_copy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($institutions->visa_copy->getPlaceHolder()) ?>" value="<?php echo $institutions->visa_copy->EditValue ?>"<?php echo $institutions->visa_copy->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->current_emirate->Visible) { // current_emirate ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_current_emirate" class="form-group">
		<label for="x_current_emirate" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_current_emirate"><?php echo $institutions->current_emirate->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_current_emirate" id="z_current_emirate" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->current_emirate->CellAttributes() ?>>
			<span id="el_institutions_current_emirate">
<select data-table="institutions" data-field="x_current_emirate" data-page="3" data-value-separator="<?php echo $institutions->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $institutions->current_emirate->EditAttributes() ?>>
<?php echo $institutions->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_current_emirate">
		<td class="col-sm-2"><span id="elh_institutions_current_emirate"><?php echo $institutions->current_emirate->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_current_emirate" id="z_current_emirate" value="LIKE"></span></td>
		<td<?php echo $institutions->current_emirate->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_current_emirate">
<select data-table="institutions" data-field="x_current_emirate" data-page="3" data-value-separator="<?php echo $institutions->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $institutions->current_emirate->EditAttributes() ?>>
<?php echo $institutions->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->full_address->Visible) { // full_address ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_full_address" class="form-group">
		<label for="x_full_address" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_full_address"><?php echo $institutions->full_address->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_address" id="z_full_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->full_address->CellAttributes() ?>>
			<span id="el_institutions_full_address">
<input type="text" data-table="institutions" data-field="x_full_address" data-page="3" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($institutions->full_address->getPlaceHolder()) ?>" value="<?php echo $institutions->full_address->EditValue ?>"<?php echo $institutions->full_address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_address">
		<td class="col-sm-2"><span id="elh_institutions_full_address"><?php echo $institutions->full_address->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_address" id="z_full_address" value="LIKE"></span></td>
		<td<?php echo $institutions->full_address->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_full_address">
<input type="text" data-table="institutions" data-field="x_full_address" data-page="3" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($institutions->full_address->getPlaceHolder()) ?>" value="<?php echo $institutions->full_address->EditValue ?>"<?php echo $institutions->full_address->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("4") ?>" id="tab_institutions4"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch4" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->emirates_id_number->Visible) { // emirates_id_number ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_emirates_id_number" class="form-group">
		<label for="x_emirates_id_number" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_emirates_id_number"><?php echo $institutions->emirates_id_number->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_number" id="z_emirates_id_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->emirates_id_number->CellAttributes() ?>>
			<span id="el_institutions_emirates_id_number">
<input type="text" data-table="institutions" data-field="x_emirates_id_number" data-page="4" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($institutions->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $institutions->emirates_id_number->EditValue ?>"<?php echo $institutions->emirates_id_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_number">
		<td class="col-sm-2"><span id="elh_institutions_emirates_id_number"><?php echo $institutions->emirates_id_number->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_number" id="z_emirates_id_number" value="LIKE"></span></td>
		<td<?php echo $institutions->emirates_id_number->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_emirates_id_number">
<input type="text" data-table="institutions" data-field="x_emirates_id_number" data-page="4" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($institutions->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $institutions->emirates_id_number->EditValue ?>"<?php echo $institutions->emirates_id_number->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->eid_expiry_date->Visible) { // eid_expiry_date ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_eid_expiry_date" class="form-group">
		<label for="x_eid_expiry_date" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_eid_expiry_date"><?php echo $institutions->eid_expiry_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_eid_expiry_date" id="z_eid_expiry_date" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->eid_expiry_date->CellAttributes() ?>>
			<span id="el_institutions_eid_expiry_date">
<input type="text" data-table="institutions" data-field="x_eid_expiry_date" data-page="4" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->eid_expiry_date->EditValue ?>"<?php echo $institutions->eid_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->eid_expiry_date->ReadOnly && !$institutions->eid_expiry_date->Disabled && !isset($institutions->eid_expiry_date->EditAttrs["readonly"]) && !isset($institutions->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_eid_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_eid_expiry_date"><?php echo $institutions->eid_expiry_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_eid_expiry_date" id="z_eid_expiry_date" value="="></span></td>
		<td<?php echo $institutions->eid_expiry_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_eid_expiry_date">
<input type="text" data-table="institutions" data-field="x_eid_expiry_date" data-page="4" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->eid_expiry_date->EditValue ?>"<?php echo $institutions->eid_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->eid_expiry_date->ReadOnly && !$institutions->eid_expiry_date->Disabled && !isset($institutions->eid_expiry_date->EditAttrs["readonly"]) && !isset($institutions->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->emirates_id_copy->Visible) { // emirates_id_copy ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_emirates_id_copy" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_emirates_id_copy"><?php echo $institutions->emirates_id_copy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_copy" id="z_emirates_id_copy" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->emirates_id_copy->CellAttributes() ?>>
			<span id="el_institutions_emirates_id_copy">
<input type="text" data-table="institutions" data-field="x_emirates_id_copy" data-page="4" name="x_emirates_id_copy" id="x_emirates_id_copy" placeholder="<?php echo ew_HtmlEncode($institutions->emirates_id_copy->getPlaceHolder()) ?>" value="<?php echo $institutions->emirates_id_copy->EditValue ?>"<?php echo $institutions->emirates_id_copy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_copy">
		<td class="col-sm-2"><span id="elh_institutions_emirates_id_copy"><?php echo $institutions->emirates_id_copy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_copy" id="z_emirates_id_copy" value="LIKE"></span></td>
		<td<?php echo $institutions->emirates_id_copy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_emirates_id_copy">
<input type="text" data-table="institutions" data-field="x_emirates_id_copy" data-page="4" name="x_emirates_id_copy" id="x_emirates_id_copy" placeholder="<?php echo ew_HtmlEncode($institutions->emirates_id_copy->getPlaceHolder()) ?>" value="<?php echo $institutions->emirates_id_copy->EditValue ?>"<?php echo $institutions->emirates_id_copy->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->passport_number->Visible) { // passport_number ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_passport_number" class="form-group">
		<label for="x_passport_number" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_passport_number"><?php echo $institutions->passport_number->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_number" id="z_passport_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->passport_number->CellAttributes() ?>>
			<span id="el_institutions_passport_number">
<input type="text" data-table="institutions" data-field="x_passport_number" data-page="4" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($institutions->passport_number->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_number->EditValue ?>"<?php echo $institutions->passport_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_number">
		<td class="col-sm-2"><span id="elh_institutions_passport_number"><?php echo $institutions->passport_number->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_number" id="z_passport_number" value="LIKE"></span></td>
		<td<?php echo $institutions->passport_number->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_passport_number">
<input type="text" data-table="institutions" data-field="x_passport_number" data-page="4" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($institutions->passport_number->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_number->EditValue ?>"<?php echo $institutions->passport_number->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->passport_ex_date->Visible) { // passport_ex_date ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_passport_ex_date" class="form-group">
		<label for="x_passport_ex_date" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_passport_ex_date"><?php echo $institutions->passport_ex_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_passport_ex_date" id="z_passport_ex_date" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->passport_ex_date->CellAttributes() ?>>
			<span id="el_institutions_passport_ex_date">
<input type="text" data-table="institutions" data-field="x_passport_ex_date" data-page="4" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($institutions->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_ex_date->EditValue ?>"<?php echo $institutions->passport_ex_date->EditAttributes() ?>>
<?php if (!$institutions->passport_ex_date->ReadOnly && !$institutions->passport_ex_date->Disabled && !isset($institutions->passport_ex_date->EditAttrs["readonly"]) && !isset($institutions->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_ex_date">
		<td class="col-sm-2"><span id="elh_institutions_passport_ex_date"><?php echo $institutions->passport_ex_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_passport_ex_date" id="z_passport_ex_date" value="="></span></td>
		<td<?php echo $institutions->passport_ex_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_passport_ex_date">
<input type="text" data-table="institutions" data-field="x_passport_ex_date" data-page="4" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($institutions->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_ex_date->EditValue ?>"<?php echo $institutions->passport_ex_date->EditAttributes() ?>>
<?php if (!$institutions->passport_ex_date->ReadOnly && !$institutions->passport_ex_date->Disabled && !isset($institutions->passport_ex_date->EditAttrs["readonly"]) && !isset($institutions->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionssearch", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->passport_copy->Visible) { // passport_copy ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_passport_copy" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_passport_copy"><?php echo $institutions->passport_copy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_copy" id="z_passport_copy" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->passport_copy->CellAttributes() ?>>
			<span id="el_institutions_passport_copy">
<input type="text" data-table="institutions" data-field="x_passport_copy" data-page="4" name="x_passport_copy" id="x_passport_copy" placeholder="<?php echo ew_HtmlEncode($institutions->passport_copy->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_copy->EditValue ?>"<?php echo $institutions->passport_copy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_copy">
		<td class="col-sm-2"><span id="elh_institutions_passport_copy"><?php echo $institutions->passport_copy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_copy" id="z_passport_copy" value="LIKE"></span></td>
		<td<?php echo $institutions->passport_copy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_passport_copy">
<input type="text" data-table="institutions" data-field="x_passport_copy" data-page="4" name="x_passport_copy" id="x_passport_copy" placeholder="<?php echo ew_HtmlEncode($institutions->passport_copy->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_copy->EditValue ?>"<?php echo $institutions->passport_copy->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("5") ?>" id="tab_institutions5"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch5" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->place_of_work->Visible) { // place_of_work ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_place_of_work" class="form-group">
		<label for="x_place_of_work" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_place_of_work"><?php echo $institutions->place_of_work->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_place_of_work" id="z_place_of_work" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->place_of_work->CellAttributes() ?>>
			<span id="el_institutions_place_of_work">
<input type="text" data-table="institutions" data-field="x_place_of_work" data-page="5" name="x_place_of_work" id="x_place_of_work" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->place_of_work->getPlaceHolder()) ?>" value="<?php echo $institutions->place_of_work->EditValue ?>"<?php echo $institutions->place_of_work->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_place_of_work">
		<td class="col-sm-2"><span id="elh_institutions_place_of_work"><?php echo $institutions->place_of_work->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_place_of_work" id="z_place_of_work" value="LIKE"></span></td>
		<td<?php echo $institutions->place_of_work->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_place_of_work">
<input type="text" data-table="institutions" data-field="x_place_of_work" data-page="5" name="x_place_of_work" id="x_place_of_work" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->place_of_work->getPlaceHolder()) ?>" value="<?php echo $institutions->place_of_work->EditValue ?>"<?php echo $institutions->place_of_work->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->work_phone->Visible) { // work_phone ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_work_phone" class="form-group">
		<label for="x_work_phone" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_work_phone"><?php echo $institutions->work_phone->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_work_phone" id="z_work_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->work_phone->CellAttributes() ?>>
			<span id="el_institutions_work_phone">
<input type="text" data-table="institutions" data-field="x_work_phone" data-page="5" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($institutions->work_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->work_phone->EditValue ?>"<?php echo $institutions->work_phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_work_phone">
		<td class="col-sm-2"><span id="elh_institutions_work_phone"><?php echo $institutions->work_phone->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_work_phone" id="z_work_phone" value="LIKE"></span></td>
		<td<?php echo $institutions->work_phone->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_work_phone">
<input type="text" data-table="institutions" data-field="x_work_phone" data-page="5" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($institutions->work_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->work_phone->EditValue ?>"<?php echo $institutions->work_phone->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->mobile_phone->Visible) { // mobile_phone ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_mobile_phone" class="form-group">
		<label for="x_mobile_phone" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_mobile_phone"><?php echo $institutions->mobile_phone->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_phone" id="z_mobile_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->mobile_phone->CellAttributes() ?>>
			<span id="el_institutions_mobile_phone">
<input type="text" data-table="institutions" data-field="x_mobile_phone" data-page="5" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->mobile_phone->EditValue ?>"<?php echo $institutions->mobile_phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_institutions_mobile_phone"><?php echo $institutions->mobile_phone->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_phone" id="z_mobile_phone" value="LIKE"></span></td>
		<td<?php echo $institutions->mobile_phone->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_mobile_phone">
<input type="text" data-table="institutions" data-field="x_mobile_phone" data-page="5" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->mobile_phone->EditValue ?>"<?php echo $institutions->mobile_phone->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->fax->Visible) { // fax ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_fax" class="form-group">
		<label for="x_fax" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_fax"><?php echo $institutions->fax->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fax" id="z_fax" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->fax->CellAttributes() ?>>
			<span id="el_institutions_fax">
<input type="text" data-table="institutions" data-field="x_fax" data-page="5" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($institutions->fax->getPlaceHolder()) ?>" value="<?php echo $institutions->fax->EditValue ?>"<?php echo $institutions->fax->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_fax">
		<td class="col-sm-2"><span id="elh_institutions_fax"><?php echo $institutions->fax->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fax" id="z_fax" value="LIKE"></span></td>
		<td<?php echo $institutions->fax->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_fax">
<input type="text" data-table="institutions" data-field="x_fax" data-page="5" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($institutions->fax->getPlaceHolder()) ?>" value="<?php echo $institutions->fax->EditValue ?>"<?php echo $institutions->fax->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->pobbox->Visible) { // pobbox ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_pobbox" class="form-group">
		<label for="x_pobbox" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_pobbox"><?php echo $institutions->pobbox->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pobbox" id="z_pobbox" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->pobbox->CellAttributes() ?>>
			<span id="el_institutions_pobbox">
<input type="text" data-table="institutions" data-field="x_pobbox" data-page="5" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($institutions->pobbox->getPlaceHolder()) ?>" value="<?php echo $institutions->pobbox->EditValue ?>"<?php echo $institutions->pobbox->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_pobbox">
		<td class="col-sm-2"><span id="elh_institutions_pobbox"><?php echo $institutions->pobbox->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pobbox" id="z_pobbox" value="LIKE"></span></td>
		<td<?php echo $institutions->pobbox->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_pobbox">
<input type="text" data-table="institutions" data-field="x_pobbox" data-page="5" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($institutions->pobbox->getPlaceHolder()) ?>" value="<?php echo $institutions->pobbox->EditValue ?>"<?php echo $institutions->pobbox->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("6") ?>" id="tab_institutions6"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch6" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->_email->Visible) { // email ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r__email" class="form-group">
		<label for="x__email" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions__email"><?php echo $institutions->_email->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->_email->CellAttributes() ?>>
			<span id="el_institutions__email">
<input type="text" data-table="institutions" data-field="x__email" data-page="6" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($institutions->_email->getPlaceHolder()) ?>" value="<?php echo $institutions->_email->EditValue ?>"<?php echo $institutions->_email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_institutions__email"><?php echo $institutions->_email->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></span></td>
		<td<?php echo $institutions->_email->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions__email">
<input type="text" data-table="institutions" data-field="x__email" data-page="6" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($institutions->_email->getPlaceHolder()) ?>" value="<?php echo $institutions->_email->EditValue ?>"<?php echo $institutions->_email->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->password->Visible) { // password ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_password" class="form-group">
		<label for="x_password" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_password"><?php echo $institutions->password->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_password" id="z_password" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->password->CellAttributes() ?>>
			<span id="el_institutions_password">
<input type="text" data-table="institutions" data-field="x_password" data-page="6" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($institutions->password->getPlaceHolder()) ?>" value="<?php echo $institutions->password->EditValue ?>"<?php echo $institutions->password->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_institutions_password"><?php echo $institutions->password->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_password" id="z_password" value="LIKE"></span></td>
		<td<?php echo $institutions->password->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_password">
<input type="text" data-table="institutions" data-field="x_password" data-page="6" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($institutions->password->getPlaceHolder()) ?>" value="<?php echo $institutions->password->EditValue ?>"<?php echo $institutions->password->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("7") ?>" id="tab_institutions7"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch7" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->admin_approval->Visible) { // admin_approval ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_admin_approval"><?php echo $institutions->admin_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->admin_approval->CellAttributes() ?>>
			<span id="el_institutions_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_admin_approval" data-page="7" data-value-separator="<?php echo $institutions->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 7) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_institutions_admin_approval"><?php echo $institutions->admin_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></span></td>
		<td<?php echo $institutions->admin_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_admin_approval" data-page="7" data-value-separator="<?php echo $institutions->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 7) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->admin_comment->Visible) { // admin_comment ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label for="x_admin_comment" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_admin_comment"><?php echo $institutions->admin_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->admin_comment->CellAttributes() ?>>
			<span id="el_institutions_admin_comment">
<input type="text" data-table="institutions" data-field="x_admin_comment" data-page="7" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->admin_comment->getPlaceHolder()) ?>" value="<?php echo $institutions->admin_comment->EditValue ?>"<?php echo $institutions->admin_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_institutions_admin_comment"><?php echo $institutions->admin_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></span></td>
		<td<?php echo $institutions->admin_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_admin_comment">
<input type="text" data-table="institutions" data-field="x_admin_comment" data-page="7" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->admin_comment->getPlaceHolder()) ?>" value="<?php echo $institutions->admin_comment->EditValue ?>"<?php echo $institutions->admin_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->forward_to_dep->Visible) { // forward_to_dep ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_forward_to_dep" class="form-group">
		<label for="x_forward_to_dep" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_forward_to_dep"><?php echo $institutions->forward_to_dep->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_forward_to_dep" id="z_forward_to_dep" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->forward_to_dep->CellAttributes() ?>>
			<span id="el_institutions_forward_to_dep">
<select data-table="institutions" data-field="x_forward_to_dep" data-page="7" data-value-separator="<?php echo $institutions->forward_to_dep->DisplayValueSeparatorAttribute() ?>" id="x_forward_to_dep" name="x_forward_to_dep"<?php echo $institutions->forward_to_dep->EditAttributes() ?>>
<?php echo $institutions->forward_to_dep->SelectOptionListHtml("x_forward_to_dep") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_forward_to_dep">
		<td class="col-sm-2"><span id="elh_institutions_forward_to_dep"><?php echo $institutions->forward_to_dep->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_forward_to_dep" id="z_forward_to_dep" value="="></span></td>
		<td<?php echo $institutions->forward_to_dep->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_forward_to_dep">
<select data-table="institutions" data-field="x_forward_to_dep" data-page="7" data-value-separator="<?php echo $institutions->forward_to_dep->DisplayValueSeparatorAttribute() ?>" id="x_forward_to_dep" name="x_forward_to_dep"<?php echo $institutions->forward_to_dep->EditAttributes() ?>>
<?php echo $institutions->forward_to_dep->SelectOptionListHtml("x_forward_to_dep") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("8") ?>" id="tab_institutions8"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch8" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->eco_department_approval->Visible) { // eco_department_approval ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_eco_department_approval" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_eco_department_approval"><?php echo $institutions->eco_department_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_eco_department_approval" id="z_eco_department_approval" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->eco_department_approval->CellAttributes() ?>>
			<span id="el_institutions_eco_department_approval">
<div id="tp_x_eco_department_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_eco_department_approval" data-page="8" data-value-separator="<?php echo $institutions->eco_department_approval->DisplayValueSeparatorAttribute() ?>" name="x_eco_department_approval" id="x_eco_department_approval" value="{value}"<?php echo $institutions->eco_department_approval->EditAttributes() ?>></div>
<div id="dsl_x_eco_department_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->eco_department_approval->RadioButtonListHtml(FALSE, "x_eco_department_approval", 8) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_eco_department_approval">
		<td class="col-sm-2"><span id="elh_institutions_eco_department_approval"><?php echo $institutions->eco_department_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_eco_department_approval" id="z_eco_department_approval" value="="></span></td>
		<td<?php echo $institutions->eco_department_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_eco_department_approval">
<div id="tp_x_eco_department_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_eco_department_approval" data-page="8" data-value-separator="<?php echo $institutions->eco_department_approval->DisplayValueSeparatorAttribute() ?>" name="x_eco_department_approval" id="x_eco_department_approval" value="{value}"<?php echo $institutions->eco_department_approval->EditAttributes() ?>></div>
<div id="dsl_x_eco_department_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->eco_department_approval->RadioButtonListHtml(FALSE, "x_eco_department_approval", 8) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->eco_departmnet_comment->Visible) { // eco_departmnet_comment ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_eco_departmnet_comment" class="form-group">
		<label for="x_eco_departmnet_comment" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_eco_departmnet_comment"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_eco_departmnet_comment" id="z_eco_departmnet_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->eco_departmnet_comment->CellAttributes() ?>>
			<span id="el_institutions_eco_departmnet_comment">
<input type="text" data-table="institutions" data-field="x_eco_departmnet_comment" data-page="8" name="x_eco_departmnet_comment" id="x_eco_departmnet_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->eco_departmnet_comment->getPlaceHolder()) ?>" value="<?php echo $institutions->eco_departmnet_comment->EditValue ?>"<?php echo $institutions->eco_departmnet_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_eco_departmnet_comment">
		<td class="col-sm-2"><span id="elh_institutions_eco_departmnet_comment"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_eco_departmnet_comment" id="z_eco_departmnet_comment" value="LIKE"></span></td>
		<td<?php echo $institutions->eco_departmnet_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_eco_departmnet_comment">
<input type="text" data-table="institutions" data-field="x_eco_departmnet_comment" data-page="8" name="x_eco_departmnet_comment" id="x_eco_departmnet_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->eco_departmnet_comment->getPlaceHolder()) ?>" value="<?php echo $institutions->eco_departmnet_comment->EditValue ?>"<?php echo $institutions->eco_departmnet_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_search->MultiPages->PageStyle("9") ?>" id="tab_institutions9"><!-- multi-page .tab-pane -->
<?php if ($institutions_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionssearch9" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->security_approval->Visible) { // security_approval ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_security_approval" class="form-group">
		<label class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_security_approval"><?php echo $institutions->security_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_security_approval" id="z_security_approval" value="="></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->security_approval->CellAttributes() ?>>
			<span id="el_institutions_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_security_approval" data-page="9" data-value-separator="<?php echo $institutions->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $institutions->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 9) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_approval">
		<td class="col-sm-2"><span id="elh_institutions_security_approval"><?php echo $institutions->security_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_security_approval" id="z_security_approval" value="="></span></td>
		<td<?php echo $institutions->security_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_security_approval" data-page="9" data-value-separator="<?php echo $institutions->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $institutions->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 9) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->security_comment->Visible) { // security_comment ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
	<div id="r_security_comment" class="form-group">
		<label for="x_security_comment" class="<?php echo $institutions_search->LeftColumnClass ?>"><span id="elh_institutions_security_comment"><?php echo $institutions->security_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_security_comment" id="z_security_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $institutions_search->RightColumnClass ?>"><div<?php echo $institutions->security_comment->CellAttributes() ?>>
			<span id="el_institutions_security_comment">
<input type="text" data-table="institutions" data-field="x_security_comment" data-page="9" name="x_security_comment" id="x_security_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->security_comment->getPlaceHolder()) ?>" value="<?php echo $institutions->security_comment->EditValue ?>"<?php echo $institutions->security_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_comment">
		<td class="col-sm-2"><span id="elh_institutions_security_comment"><?php echo $institutions->security_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_security_comment" id="z_security_comment" value="LIKE"></span></td>
		<td<?php echo $institutions->security_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_institutions_security_comment">
<input type="text" data-table="institutions" data-field="x_security_comment" data-page="9" name="x_security_comment" id="x_security_comment" size="35" placeholder="<?php echo ew_HtmlEncode($institutions->security_comment->getPlaceHolder()) ?>" value="<?php echo $institutions->security_comment->EditValue ?>"<?php echo $institutions->security_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$institutions_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $institutions_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$institutions_search->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
finstitutionssearch.Init();
</script>
<?php
$institutions_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$institutions_search->Page_Terminate();
?>
