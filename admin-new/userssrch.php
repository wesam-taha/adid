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

$users_search = NULL; // Initialize page object first

class cusers_search extends cusers {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_search';

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
			define("EW_PAGE_ID", 'search', TRUE);

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
		if (!$Security->CanSearch()) {
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
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->user_id->SetVisibility();
		$this->user_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->group_id->SetVisibility();
		$this->full_name_ar->SetVisibility();
		$this->full_name_en->SetVisibility();
		$this->date_of_birth->SetVisibility();
		$this->personal_photo->SetVisibility();
		$this->gender->SetVisibility();
		$this->blood_type->SetVisibility();
		$this->driving_licence->SetVisibility();
		$this->job->SetVisibility();
		$this->volunteering_type->SetVisibility();
		$this->marital_status->SetVisibility();
		$this->nationality_type->SetVisibility();
		$this->nationality->SetVisibility();
		$this->unid->SetVisibility();
		$this->visa_expiry_date->SetVisibility();
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
		$this->qualifications->SetVisibility();
		$this->cv->SetVisibility();
		$this->home_phone->SetVisibility();
		$this->work_phone->SetVisibility();
		$this->mobile_phone->SetVisibility();
		$this->fax->SetVisibility();
		$this->pobbox->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->total_voluntary_hours->SetVisibility();
		$this->overall_evaluation->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->lastUpdatedBy->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->security_approval->SetVisibility();
		$this->approvedBy->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Show as modal
					$row["modal"] = "1";
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "usersview.php")
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
						$sSrchStr = "userslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->user_id); // user_id
		$this->BuildSearchUrl($sSrchUrl, $this->group_id); // group_id
		$this->BuildSearchUrl($sSrchUrl, $this->full_name_ar); // full_name_ar
		$this->BuildSearchUrl($sSrchUrl, $this->full_name_en); // full_name_en
		$this->BuildSearchUrl($sSrchUrl, $this->date_of_birth); // date_of_birth
		$this->BuildSearchUrl($sSrchUrl, $this->personal_photo); // personal_photo
		$this->BuildSearchUrl($sSrchUrl, $this->gender); // gender
		$this->BuildSearchUrl($sSrchUrl, $this->blood_type); // blood_type
		$this->BuildSearchUrl($sSrchUrl, $this->driving_licence); // driving_licence
		$this->BuildSearchUrl($sSrchUrl, $this->job); // job
		$this->BuildSearchUrl($sSrchUrl, $this->volunteering_type); // volunteering_type
		$this->BuildSearchUrl($sSrchUrl, $this->marital_status); // marital_status
		$this->BuildSearchUrl($sSrchUrl, $this->nationality_type); // nationality_type
		$this->BuildSearchUrl($sSrchUrl, $this->nationality); // nationality
		$this->BuildSearchUrl($sSrchUrl, $this->unid); // unid
		$this->BuildSearchUrl($sSrchUrl, $this->visa_expiry_date); // visa_expiry_date
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
		$this->BuildSearchUrl($sSrchUrl, $this->qualifications); // qualifications
		$this->BuildSearchUrl($sSrchUrl, $this->cv); // cv
		$this->BuildSearchUrl($sSrchUrl, $this->home_phone); // home_phone
		$this->BuildSearchUrl($sSrchUrl, $this->work_phone); // work_phone
		$this->BuildSearchUrl($sSrchUrl, $this->mobile_phone); // mobile_phone
		$this->BuildSearchUrl($sSrchUrl, $this->fax); // fax
		$this->BuildSearchUrl($sSrchUrl, $this->pobbox); // pobbox
		$this->BuildSearchUrl($sSrchUrl, $this->_email); // email
		$this->BuildSearchUrl($sSrchUrl, $this->password); // password
		$this->BuildSearchUrl($sSrchUrl, $this->total_voluntary_hours); // total_voluntary_hours
		$this->BuildSearchUrl($sSrchUrl, $this->overall_evaluation); // overall_evaluation
		$this->BuildSearchUrl($sSrchUrl, $this->admin_approval); // admin_approval
		$this->BuildSearchUrl($sSrchUrl, $this->lastUpdatedBy); // lastUpdatedBy
		$this->BuildSearchUrl($sSrchUrl, $this->admin_comment); // admin_comment
		$this->BuildSearchUrl($sSrchUrl, $this->security_approval); // security_approval
		$this->BuildSearchUrl($sSrchUrl, $this->approvedBy); // approvedBy
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
		// user_id

		$this->user_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_user_id");
		$this->user_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_user_id");

		// group_id
		$this->group_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_group_id");
		$this->group_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_group_id");
		if (is_array($this->group_id->AdvancedSearch->SearchValue)) $this->group_id->AdvancedSearch->SearchValue = implode(",", $this->group_id->AdvancedSearch->SearchValue);
		if (is_array($this->group_id->AdvancedSearch->SearchValue2)) $this->group_id->AdvancedSearch->SearchValue2 = implode(",", $this->group_id->AdvancedSearch->SearchValue2);

		// full_name_ar
		$this->full_name_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_full_name_ar");
		$this->full_name_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_full_name_ar");

		// full_name_en
		$this->full_name_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_full_name_en");
		$this->full_name_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_full_name_en");

		// date_of_birth
		$this->date_of_birth->AdvancedSearch->SearchValue = $objForm->GetValue("x_date_of_birth");
		$this->date_of_birth->AdvancedSearch->SearchOperator = $objForm->GetValue("z_date_of_birth");

		// personal_photo
		$this->personal_photo->AdvancedSearch->SearchValue = $objForm->GetValue("x_personal_photo");
		$this->personal_photo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_personal_photo");

		// gender
		$this->gender->AdvancedSearch->SearchValue = $objForm->GetValue("x_gender");
		$this->gender->AdvancedSearch->SearchOperator = $objForm->GetValue("z_gender");

		// blood_type
		$this->blood_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_blood_type");
		$this->blood_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_blood_type");

		// driving_licence
		$this->driving_licence->AdvancedSearch->SearchValue = $objForm->GetValue("x_driving_licence");
		$this->driving_licence->AdvancedSearch->SearchOperator = $objForm->GetValue("z_driving_licence");

		// job
		$this->job->AdvancedSearch->SearchValue = $objForm->GetValue("x_job");
		$this->job->AdvancedSearch->SearchOperator = $objForm->GetValue("z_job");

		// volunteering_type
		$this->volunteering_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_volunteering_type");
		$this->volunteering_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_volunteering_type");

		// marital_status
		$this->marital_status->AdvancedSearch->SearchValue = $objForm->GetValue("x_marital_status");
		$this->marital_status->AdvancedSearch->SearchOperator = $objForm->GetValue("z_marital_status");

		// nationality_type
		$this->nationality_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_nationality_type");
		$this->nationality_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nationality_type");

		// nationality
		$this->nationality->AdvancedSearch->SearchValue = $objForm->GetValue("x_nationality");
		$this->nationality->AdvancedSearch->SearchOperator = $objForm->GetValue("z_nationality");

		// unid
		$this->unid->AdvancedSearch->SearchValue = $objForm->GetValue("x_unid");
		$this->unid->AdvancedSearch->SearchOperator = $objForm->GetValue("z_unid");

		// visa_expiry_date
		$this->visa_expiry_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_visa_expiry_date");
		$this->visa_expiry_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_visa_expiry_date");

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

		// qualifications
		$this->qualifications->AdvancedSearch->SearchValue = $objForm->GetValue("x_qualifications");
		$this->qualifications->AdvancedSearch->SearchOperator = $objForm->GetValue("z_qualifications");

		// cv
		$this->cv->AdvancedSearch->SearchValue = $objForm->GetValue("x_cv");
		$this->cv->AdvancedSearch->SearchOperator = $objForm->GetValue("z_cv");

		// home_phone
		$this->home_phone->AdvancedSearch->SearchValue = $objForm->GetValue("x_home_phone");
		$this->home_phone->AdvancedSearch->SearchOperator = $objForm->GetValue("z_home_phone");

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

		// total_voluntary_hours
		$this->total_voluntary_hours->AdvancedSearch->SearchValue = $objForm->GetValue("x_total_voluntary_hours");
		$this->total_voluntary_hours->AdvancedSearch->SearchOperator = $objForm->GetValue("z_total_voluntary_hours");

		// overall_evaluation
		$this->overall_evaluation->AdvancedSearch->SearchValue = $objForm->GetValue("x_overall_evaluation");
		$this->overall_evaluation->AdvancedSearch->SearchOperator = $objForm->GetValue("z_overall_evaluation");

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_approval");
		$this->admin_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_approval");

		// lastUpdatedBy
		$this->lastUpdatedBy->AdvancedSearch->SearchValue = $objForm->GetValue("x_lastUpdatedBy");
		$this->lastUpdatedBy->AdvancedSearch->SearchOperator = $objForm->GetValue("z_lastUpdatedBy");

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_comment");
		$this->admin_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_comment");

		// security_approval
		$this->security_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_security_approval");
		$this->security_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_security_approval");

		// approvedBy
		$this->approvedBy->AdvancedSearch->SearchValue = $objForm->GetValue("x_approvedBy");
		$this->approvedBy->AdvancedSearch->SearchOperator = $objForm->GetValue("z_approvedBy");

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
		// security_owner

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

		// lastUpdatedBy
		if (strval($this->lastUpdatedBy->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->lastUpdatedBy->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `management`";
		$sWhereWrk = "";
		$this->lastUpdatedBy->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->lastUpdatedBy, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->lastUpdatedBy->ViewValue = $this->lastUpdatedBy->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->lastUpdatedBy->ViewValue = $this->lastUpdatedBy->CurrentValue;
			}
		} else {
			$this->lastUpdatedBy->ViewValue = NULL;
		}
		$this->lastUpdatedBy->ViewCustomAttributes = "";

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

		// approvedBy
		if (strval($this->approvedBy->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approvedBy->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `management`";
		$sWhereWrk = "";
		$this->approvedBy->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->approvedBy, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->approvedBy->ViewValue = $this->approvedBy->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approvedBy->ViewValue = $this->approvedBy->CurrentValue;
			}
		} else {
			$this->approvedBy->ViewValue = NULL;
		}
		$this->approvedBy->ViewCustomAttributes = "";

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

			// group_id
			$this->group_id->LinkCustomAttributes = "";
			$this->group_id->HrefValue = "";
			$this->group_id->TooltipValue = "";

			// full_name_ar
			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";
			$this->full_name_ar->TooltipValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";
			$this->full_name_en->TooltipValue = "";

			// date_of_birth
			$this->date_of_birth->LinkCustomAttributes = "";
			$this->date_of_birth->HrefValue = "";
			$this->date_of_birth->TooltipValue = "";

			// personal_photo
			$this->personal_photo->LinkCustomAttributes = "";
			$this->personal_photo->UploadPath = "../images";
			if (!ew_Empty($this->personal_photo->Upload->DbValue)) {
				$this->personal_photo->HrefValue = ew_GetFileUploadUrl($this->personal_photo, $this->personal_photo->Upload->DbValue); // Add prefix/suffix
				$this->personal_photo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->personal_photo->HrefValue = ew_FullUrl($this->personal_photo->HrefValue, "href");
			} else {
				$this->personal_photo->HrefValue = "";
			}
			$this->personal_photo->HrefValue2 = $this->personal_photo->UploadPath . $this->personal_photo->Upload->DbValue;
			$this->personal_photo->TooltipValue = "";
			if ($this->personal_photo->UseColorbox) {
				if (ew_Empty($this->personal_photo->TooltipValue))
					$this->personal_photo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->personal_photo->LinkAttrs["data-rel"] = "users_x_personal_photo";
				ew_AppendClass($this->personal_photo->LinkAttrs["class"], "ewLightbox");
			}

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// blood_type
			$this->blood_type->LinkCustomAttributes = "";
			$this->blood_type->HrefValue = "";
			$this->blood_type->TooltipValue = "";

			// driving_licence
			$this->driving_licence->LinkCustomAttributes = "";
			$this->driving_licence->HrefValue = "";
			$this->driving_licence->TooltipValue = "";

			// job
			$this->job->LinkCustomAttributes = "";
			$this->job->HrefValue = "";
			$this->job->TooltipValue = "";

			// volunteering_type
			$this->volunteering_type->LinkCustomAttributes = "";
			$this->volunteering_type->HrefValue = "";
			$this->volunteering_type->TooltipValue = "";

			// marital_status
			$this->marital_status->LinkCustomAttributes = "";
			$this->marital_status->HrefValue = "";
			$this->marital_status->TooltipValue = "";

			// nationality_type
			$this->nationality_type->LinkCustomAttributes = "";
			$this->nationality_type->HrefValue = "";
			$this->nationality_type->TooltipValue = "";

			// nationality
			$this->nationality->LinkCustomAttributes = "";
			$this->nationality->HrefValue = "";
			$this->nationality->TooltipValue = "";

			// unid
			$this->unid->LinkCustomAttributes = "";
			$this->unid->HrefValue = "";
			$this->unid->TooltipValue = "";

			// visa_expiry_date
			$this->visa_expiry_date->LinkCustomAttributes = "";
			$this->visa_expiry_date->HrefValue = "";
			$this->visa_expiry_date->TooltipValue = "";

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
				$this->visa_copy->LinkAttrs["data-rel"] = "users_x_visa_copy";
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
				$this->emirates_id_copy->LinkAttrs["data-rel"] = "users_x_emirates_id_copy";
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
				$this->passport_copy->LinkAttrs["data-rel"] = "users_x_passport_copy";
				ew_AppendClass($this->passport_copy->LinkAttrs["class"], "ewLightbox");
			}

			// place_of_work
			$this->place_of_work->LinkCustomAttributes = "";
			$this->place_of_work->HrefValue = "";
			$this->place_of_work->TooltipValue = "";

			// qualifications
			$this->qualifications->LinkCustomAttributes = "";
			$this->qualifications->HrefValue = "";
			$this->qualifications->TooltipValue = "";

			// cv
			$this->cv->LinkCustomAttributes = "";
			$this->cv->HrefValue = "";
			$this->cv->HrefValue2 = $this->cv->UploadPath . $this->cv->Upload->DbValue;
			$this->cv->TooltipValue = "";

			// home_phone
			$this->home_phone->LinkCustomAttributes = "";
			$this->home_phone->HrefValue = "";
			$this->home_phone->TooltipValue = "";

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

			// total_voluntary_hours
			$this->total_voluntary_hours->LinkCustomAttributes = "";
			$this->total_voluntary_hours->HrefValue = "";
			$this->total_voluntary_hours->TooltipValue = "";

			// overall_evaluation
			$this->overall_evaluation->LinkCustomAttributes = "";
			$this->overall_evaluation->HrefValue = "";
			$this->overall_evaluation->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// lastUpdatedBy
			$this->lastUpdatedBy->LinkCustomAttributes = "";
			$this->lastUpdatedBy->HrefValue = "";
			$this->lastUpdatedBy->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";
			$this->security_approval->TooltipValue = "";

			// approvedBy
			$this->approvedBy->LinkCustomAttributes = "";
			$this->approvedBy->HrefValue = "";
			$this->approvedBy->TooltipValue = "";

			// security_comment
			$this->security_comment->LinkCustomAttributes = "";
			$this->security_comment->HrefValue = "";
			$this->security_comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->AdvancedSearch->SearchValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// group_id
			$this->group_id->EditCustomAttributes = "";
			if (trim(strval($this->group_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->group_id->AdvancedSearch->SearchValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`institution_id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `institutions`";
			$sWhereWrk = "";
			$this->group_id->LookupFilters = array("dx1" => '`institutes_name`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->group_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->group_id->AdvancedSearch->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->group_id->AdvancedSearch->ViewValue .= $this->group_id->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->group_id->AdvancedSearch->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->group_id->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->group_id->EditValue = $arwrk;

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

			// date_of_birth
			$this->date_of_birth->EditAttrs["class"] = "form-control";
			$this->date_of_birth->EditCustomAttributes = "";
			$this->date_of_birth->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->date_of_birth->AdvancedSearch->SearchValue, 0), 8));
			$this->date_of_birth->PlaceHolder = ew_RemoveHtml($this->date_of_birth->FldCaption());

			// personal_photo
			$this->personal_photo->EditAttrs["class"] = "form-control";
			$this->personal_photo->EditCustomAttributes = "";
			$this->personal_photo->EditValue = ew_HtmlEncode($this->personal_photo->AdvancedSearch->SearchValue);
			$this->personal_photo->PlaceHolder = ew_RemoveHtml($this->personal_photo->FldCaption());

			// gender
			$this->gender->EditCustomAttributes = "";
			$this->gender->EditValue = $this->gender->Options(FALSE);

			// blood_type
			$this->blood_type->EditAttrs["class"] = "form-control";
			$this->blood_type->EditCustomAttributes = "";
			$this->blood_type->EditValue = $this->blood_type->Options(TRUE);

			// driving_licence
			$this->driving_licence->EditCustomAttributes = "";
			$this->driving_licence->EditValue = $this->driving_licence->Options(FALSE);

			// job
			$this->job->EditAttrs["class"] = "form-control";
			$this->job->EditCustomAttributes = "";
			$this->job->EditValue = $this->job->Options(TRUE);

			// volunteering_type
			$this->volunteering_type->EditAttrs["class"] = "form-control";
			$this->volunteering_type->EditCustomAttributes = "";
			$this->volunteering_type->EditValue = $this->volunteering_type->Options(TRUE);

			// marital_status
			$this->marital_status->EditCustomAttributes = "";
			$this->marital_status->EditValue = $this->marital_status->Options(FALSE);

			// nationality_type
			$this->nationality_type->EditCustomAttributes = "";
			$this->nationality_type->EditValue = $this->nationality_type->Options(FALSE);

			// nationality
			$this->nationality->EditAttrs["class"] = "form-control";
			$this->nationality->EditCustomAttributes = "";
			$this->nationality->EditValue = ew_HtmlEncode($this->nationality->AdvancedSearch->SearchValue);
			$this->nationality->PlaceHolder = ew_RemoveHtml($this->nationality->FldCaption());

			// unid
			$this->unid->EditAttrs["class"] = "form-control";
			$this->unid->EditCustomAttributes = "";
			$this->unid->EditValue = ew_HtmlEncode($this->unid->AdvancedSearch->SearchValue);
			$this->unid->PlaceHolder = ew_RemoveHtml($this->unid->FldCaption());

			// visa_expiry_date
			$this->visa_expiry_date->EditAttrs["class"] = "form-control";
			$this->visa_expiry_date->EditCustomAttributes = "";
			$this->visa_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->visa_expiry_date->AdvancedSearch->SearchValue, 0), 8));
			$this->visa_expiry_date->PlaceHolder = ew_RemoveHtml($this->visa_expiry_date->FldCaption());

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

			// qualifications
			$this->qualifications->EditAttrs["class"] = "form-control";
			$this->qualifications->EditCustomAttributes = "";
			$this->qualifications->EditValue = ew_HtmlEncode($this->qualifications->AdvancedSearch->SearchValue);
			$this->qualifications->PlaceHolder = ew_RemoveHtml($this->qualifications->FldCaption());

			// cv
			$this->cv->EditAttrs["class"] = "form-control";
			$this->cv->EditCustomAttributes = "";
			$this->cv->EditValue = ew_HtmlEncode($this->cv->AdvancedSearch->SearchValue);
			$this->cv->PlaceHolder = ew_RemoveHtml($this->cv->FldCaption());

			// home_phone
			$this->home_phone->EditAttrs["class"] = "form-control";
			$this->home_phone->EditCustomAttributes = "";
			$this->home_phone->EditValue = ew_HtmlEncode($this->home_phone->AdvancedSearch->SearchValue);
			$this->home_phone->PlaceHolder = ew_RemoveHtml($this->home_phone->FldCaption());

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

			// total_voluntary_hours
			$this->total_voluntary_hours->EditAttrs["class"] = "form-control";
			$this->total_voluntary_hours->EditCustomAttributes = "";
			$this->total_voluntary_hours->EditValue = ew_HtmlEncode($this->total_voluntary_hours->AdvancedSearch->SearchValue);
			$this->total_voluntary_hours->PlaceHolder = ew_RemoveHtml($this->total_voluntary_hours->FldCaption());

			// overall_evaluation
			$this->overall_evaluation->EditAttrs["class"] = "form-control";
			$this->overall_evaluation->EditCustomAttributes = "";
			$this->overall_evaluation->EditValue = ew_HtmlEncode($this->overall_evaluation->AdvancedSearch->SearchValue);
			$this->overall_evaluation->PlaceHolder = ew_RemoveHtml($this->overall_evaluation->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// lastUpdatedBy
			$this->lastUpdatedBy->EditAttrs["class"] = "form-control";
			$this->lastUpdatedBy->EditCustomAttributes = "";
			if (trim(strval($this->lastUpdatedBy->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->lastUpdatedBy->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `management`";
			$sWhereWrk = "";
			$this->lastUpdatedBy->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["users"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["management"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->lastUpdatedBy, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->lastUpdatedBy->EditValue = $arwrk;

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->AdvancedSearch->SearchValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// security_approval
			$this->security_approval->EditCustomAttributes = "";
			$this->security_approval->EditValue = $this->security_approval->Options(FALSE);

			// approvedBy
			$this->approvedBy->EditAttrs["class"] = "form-control";
			$this->approvedBy->EditCustomAttributes = "";
			if (trim(strval($this->approvedBy->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->approvedBy->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `management`";
			$sWhereWrk = "";
			$this->approvedBy->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["users"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["management"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->approvedBy, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->approvedBy->EditValue = $arwrk;

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
		if (!ew_CheckInteger($this->user_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->user_id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->date_of_birth->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->date_of_birth->FldErrMsg());
		}
		if (!ew_CheckInteger($this->unid->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->unid->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->visa_expiry_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->visa_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->eid_expiry_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->eid_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->passport_ex_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->passport_ex_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->overall_evaluation->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->overall_evaluation->FldErrMsg());
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
		$this->user_id->AdvancedSearch->Load();
		$this->group_id->AdvancedSearch->Load();
		$this->full_name_ar->AdvancedSearch->Load();
		$this->full_name_en->AdvancedSearch->Load();
		$this->date_of_birth->AdvancedSearch->Load();
		$this->personal_photo->AdvancedSearch->Load();
		$this->gender->AdvancedSearch->Load();
		$this->blood_type->AdvancedSearch->Load();
		$this->driving_licence->AdvancedSearch->Load();
		$this->job->AdvancedSearch->Load();
		$this->volunteering_type->AdvancedSearch->Load();
		$this->marital_status->AdvancedSearch->Load();
		$this->nationality_type->AdvancedSearch->Load();
		$this->nationality->AdvancedSearch->Load();
		$this->unid->AdvancedSearch->Load();
		$this->visa_expiry_date->AdvancedSearch->Load();
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
		$this->qualifications->AdvancedSearch->Load();
		$this->cv->AdvancedSearch->Load();
		$this->home_phone->AdvancedSearch->Load();
		$this->work_phone->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->pobbox->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->total_voluntary_hours->AdvancedSearch->Load();
		$this->overall_evaluation->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->lastUpdatedBy->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->security_approval->AdvancedSearch->Load();
		$this->approvedBy->AdvancedSearch->Load();
		$this->security_comment->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
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
		$this->MultiPages = $pages;
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_group_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `institution_id` AS `LinkFld`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
			$sWhereWrk = "{filter}";
			$this->group_id->LookupFilters = array("dx1" => '`institutes_name`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`institution_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->group_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_lastUpdatedBy":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `management`";
			$sWhereWrk = "";
			$this->lastUpdatedBy->LookupFilters = array();
			if (!$GLOBALS["users"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["management"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->lastUpdatedBy, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_approvedBy":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `management`";
			$sWhereWrk = "";
			$this->approvedBy->LookupFilters = array();
			if (!$GLOBALS["users"]->UserIDAllow("search")) $sWhereWrk = $GLOBALS["management"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->approvedBy, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($users_search)) $users_search = new cusers_search();

// Page init
$users_search->Page_Init();

// Page main
$users_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($users_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fuserssearch = new ew_Form("fuserssearch", "search");
<?php } else { ?>
var CurrentForm = fuserssearch = new ew_Form("fuserssearch", "search");
<?php } ?>

// Form_CustomValidate event
fuserssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuserssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fuserssearch.MultiPage = new ew_MultiPage("fuserssearch");

// Dynamic selection lists
fuserssearch.Lists["x_group_id[]"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
fuserssearch.Lists["x_group_id[]"].Data = "<?php echo $users_search->group_id->LookupFilterQuery(FALSE, "search") ?>";
fuserssearch.Lists["x_gender"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_gender"].Options = <?php echo json_encode($users_search->gender->Options()) ?>;
fuserssearch.Lists["x_blood_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_blood_type"].Options = <?php echo json_encode($users_search->blood_type->Options()) ?>;
fuserssearch.Lists["x_driving_licence"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_driving_licence"].Options = <?php echo json_encode($users_search->driving_licence->Options()) ?>;
fuserssearch.Lists["x_job"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_job"].Options = <?php echo json_encode($users_search->job->Options()) ?>;
fuserssearch.Lists["x_volunteering_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_volunteering_type"].Options = <?php echo json_encode($users_search->volunteering_type->Options()) ?>;
fuserssearch.Lists["x_marital_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_marital_status"].Options = <?php echo json_encode($users_search->marital_status->Options()) ?>;
fuserssearch.Lists["x_nationality_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_nationality_type"].Options = <?php echo json_encode($users_search->nationality_type->Options()) ?>;
fuserssearch.Lists["x_current_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_current_emirate"].Options = <?php echo json_encode($users_search->current_emirate->Options()) ?>;
fuserssearch.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_admin_approval"].Options = <?php echo json_encode($users_search->admin_approval->Options()) ?>;
fuserssearch.Lists["x_lastUpdatedBy"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_username","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"management"};
fuserssearch.Lists["x_lastUpdatedBy"].Data = "<?php echo $users_search->lastUpdatedBy->LookupFilterQuery(FALSE, "search") ?>";
fuserssearch.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserssearch.Lists["x_security_approval"].Options = <?php echo json_encode($users_search->security_approval->Options()) ?>;
fuserssearch.Lists["x_approvedBy"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_username","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"management"};
fuserssearch.Lists["x_approvedBy"].Data = "<?php echo $users_search->approvedBy->LookupFilterQuery(FALSE, "search") ?>";

// Form object for search
// Validate function for search

fuserssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_user_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->user_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_date_of_birth");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->date_of_birth->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_unid");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->unid->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_visa_expiry_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->visa_expiry_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_eid_expiry_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->eid_expiry_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_passport_ex_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->passport_ex_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_overall_evaluation");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($users->overall_evaluation->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $users_search->ShowPageHeader(); ?>
<?php
$users_search->ShowMessage();
?>
<form name="fuserssearch" id="fuserssearch" class="<?php echo $users_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($users_search->IsModal) ?>">
<?php if (!$users_search->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="users_search"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $users_search->MultiPages->NavStyle() ?>">
		<li<?php echo $users_search->MultiPages->TabStyle("1") ?>><a href="#tab_users1" data-toggle="tab"><?php echo $users->PageCaption(1) ?></a></li>
		<li<?php echo $users_search->MultiPages->TabStyle("2") ?>><a href="#tab_users2" data-toggle="tab"><?php echo $users->PageCaption(2) ?></a></li>
		<li<?php echo $users_search->MultiPages->TabStyle("3") ?>><a href="#tab_users3" data-toggle="tab"><?php echo $users->PageCaption(3) ?></a></li>
		<li<?php echo $users_search->MultiPages->TabStyle("4") ?>><a href="#tab_users4" data-toggle="tab"><?php echo $users->PageCaption(4) ?></a></li>
		<li<?php echo $users_search->MultiPages->TabStyle("5") ?>><a href="#tab_users5" data-toggle="tab"><?php echo $users->PageCaption(5) ?></a></li>
		<li<?php echo $users_search->MultiPages->TabStyle("6") ?>><a href="#tab_users6" data-toggle="tab"><?php echo $users->PageCaption(6) ?></a></li>
		<li<?php echo $users_search->MultiPages->TabStyle("7") ?>><a href="#tab_users7" data-toggle="tab"><?php echo $users->PageCaption(7) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("1") ?>" id="tab_users1"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch1" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->user_id->Visible) { // user_id ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_user_id" class="form-group">
		<label for="x_user_id" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_user_id"><?php echo $users->user_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user_id" id="z_user_id" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->user_id->CellAttributes() ?>>
			<span id="el_users_user_id">
<input type="text" data-table="users" data-field="x_user_id" data-page="1" name="x_user_id" id="x_user_id" placeholder="<?php echo ew_HtmlEncode($users->user_id->getPlaceHolder()) ?>" value="<?php echo $users->user_id->EditValue ?>"<?php echo $users->user_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_user_id">
		<td class="col-sm-2"><span id="elh_users_user_id"><?php echo $users->user_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_user_id" id="z_user_id" value="="></span></td>
		<td<?php echo $users->user_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_user_id">
<input type="text" data-table="users" data-field="x_user_id" data-page="1" name="x_user_id" id="x_user_id" placeholder="<?php echo ew_HtmlEncode($users->user_id->getPlaceHolder()) ?>" value="<?php echo $users->user_id->EditValue ?>"<?php echo $users->user_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->group_id->Visible) { // group_id ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_group_id" class="form-group">
		<label for="x_group_id" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_group_id"><?php echo $users->group_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_group_id" id="z_group_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->group_id->CellAttributes() ?>>
			<span id="el_users_group_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_group_id"><?php echo (strval($users->group_id->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $users->group_id->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($users->group_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_group_id[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="users" data-field="x_group_id" data-page="1" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $users->group_id->DisplayValueSeparatorAttribute() ?>" name="x_group_id[]" id="x_group_id[]" value="<?php echo $users->group_id->AdvancedSearch->SearchValue ?>"<?php echo $users->group_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_group_id">
		<td class="col-sm-2"><span id="elh_users_group_id"><?php echo $users->group_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_group_id" id="z_group_id" value="LIKE"></span></td>
		<td<?php echo $users->group_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_group_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_group_id"><?php echo (strval($users->group_id->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $users->group_id->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($users->group_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_group_id[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="users" data-field="x_group_id" data-page="1" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $users->group_id->DisplayValueSeparatorAttribute() ?>" name="x_group_id[]" id="x_group_id[]" value="<?php echo $users->group_id->AdvancedSearch->SearchValue ?>"<?php echo $users->group_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_full_name_ar" class="form-group">
		<label for="x_full_name_ar" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_full_name_ar"><?php echo $users->full_name_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_ar" id="z_full_name_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->full_name_ar->CellAttributes() ?>>
			<span id="el_users_full_name_ar">
<input type="text" data-table="users" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($users->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $users->full_name_ar->EditValue ?>"<?php echo $users->full_name_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_ar">
		<td class="col-sm-2"><span id="elh_users_full_name_ar"><?php echo $users->full_name_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_ar" id="z_full_name_ar" value="LIKE"></span></td>
		<td<?php echo $users->full_name_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_full_name_ar">
<input type="text" data-table="users" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($users->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $users->full_name_ar->EditValue ?>"<?php echo $users->full_name_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_full_name_en" class="form-group">
		<label for="x_full_name_en" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_full_name_en"><?php echo $users->full_name_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_en" id="z_full_name_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->full_name_en->CellAttributes() ?>>
			<span id="el_users_full_name_en">
<input type="text" data-table="users" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($users->full_name_en->getPlaceHolder()) ?>" value="<?php echo $users->full_name_en->EditValue ?>"<?php echo $users->full_name_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_en">
		<td class="col-sm-2"><span id="elh_users_full_name_en"><?php echo $users->full_name_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_name_en" id="z_full_name_en" value="LIKE"></span></td>
		<td<?php echo $users->full_name_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_full_name_en">
<input type="text" data-table="users" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($users->full_name_en->getPlaceHolder()) ?>" value="<?php echo $users->full_name_en->EditValue ?>"<?php echo $users->full_name_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->date_of_birth->Visible) { // date_of_birth ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_date_of_birth" class="form-group">
		<label for="x_date_of_birth" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_date_of_birth"><?php echo $users->date_of_birth->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_of_birth" id="z_date_of_birth" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->date_of_birth->CellAttributes() ?>>
			<span id="el_users_date_of_birth">
<input type="text" data-table="users" data-field="x_date_of_birth" data-page="1" name="x_date_of_birth" id="x_date_of_birth" placeholder="<?php echo ew_HtmlEncode($users->date_of_birth->getPlaceHolder()) ?>" value="<?php echo $users->date_of_birth->EditValue ?>"<?php echo $users->date_of_birth->EditAttributes() ?>>
<?php if (!$users->date_of_birth->ReadOnly && !$users->date_of_birth->Disabled && !isset($users->date_of_birth->EditAttrs["readonly"]) && !isset($users->date_of_birth->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_date_of_birth", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_date_of_birth">
		<td class="col-sm-2"><span id="elh_users_date_of_birth"><?php echo $users->date_of_birth->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_date_of_birth" id="z_date_of_birth" value="="></span></td>
		<td<?php echo $users->date_of_birth->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_date_of_birth">
<input type="text" data-table="users" data-field="x_date_of_birth" data-page="1" name="x_date_of_birth" id="x_date_of_birth" placeholder="<?php echo ew_HtmlEncode($users->date_of_birth->getPlaceHolder()) ?>" value="<?php echo $users->date_of_birth->EditValue ?>"<?php echo $users->date_of_birth->EditAttributes() ?>>
<?php if (!$users->date_of_birth->ReadOnly && !$users->date_of_birth->Disabled && !isset($users->date_of_birth->EditAttrs["readonly"]) && !isset($users->date_of_birth->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_date_of_birth", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->personal_photo->Visible) { // personal_photo ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_personal_photo" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_personal_photo"><?php echo $users->personal_photo->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_personal_photo" id="z_personal_photo" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->personal_photo->CellAttributes() ?>>
			<span id="el_users_personal_photo">
<input type="text" data-table="users" data-field="x_personal_photo" data-page="1" name="x_personal_photo" id="x_personal_photo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->personal_photo->getPlaceHolder()) ?>" value="<?php echo $users->personal_photo->EditValue ?>"<?php echo $users->personal_photo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_personal_photo">
		<td class="col-sm-2"><span id="elh_users_personal_photo"><?php echo $users->personal_photo->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_personal_photo" id="z_personal_photo" value="LIKE"></span></td>
		<td<?php echo $users->personal_photo->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_personal_photo">
<input type="text" data-table="users" data-field="x_personal_photo" data-page="1" name="x_personal_photo" id="x_personal_photo" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->personal_photo->getPlaceHolder()) ?>" value="<?php echo $users->personal_photo->EditValue ?>"<?php echo $users->personal_photo->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->gender->Visible) { // gender ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_gender" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_gender"><?php echo $users->gender->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_gender" id="z_gender" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->gender->CellAttributes() ?>>
			<span id="el_users_gender">
<div id="tp_x_gender" class="ewTemplate"><input type="radio" data-table="users" data-field="x_gender" data-page="1" data-value-separator="<?php echo $users->gender->DisplayValueSeparatorAttribute() ?>" name="x_gender" id="x_gender" value="{value}"<?php echo $users->gender->EditAttributes() ?>></div>
<div id="dsl_x_gender" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->gender->RadioButtonListHtml(FALSE, "x_gender", 1) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_gender">
		<td class="col-sm-2"><span id="elh_users_gender"><?php echo $users->gender->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_gender" id="z_gender" value="="></span></td>
		<td<?php echo $users->gender->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_gender">
<div id="tp_x_gender" class="ewTemplate"><input type="radio" data-table="users" data-field="x_gender" data-page="1" data-value-separator="<?php echo $users->gender->DisplayValueSeparatorAttribute() ?>" name="x_gender" id="x_gender" value="{value}"<?php echo $users->gender->EditAttributes() ?>></div>
<div id="dsl_x_gender" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->gender->RadioButtonListHtml(FALSE, "x_gender", 1) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->blood_type->Visible) { // blood_type ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_blood_type" class="form-group">
		<label for="x_blood_type" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_blood_type"><?php echo $users->blood_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_blood_type" id="z_blood_type" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->blood_type->CellAttributes() ?>>
			<span id="el_users_blood_type">
<select data-table="users" data-field="x_blood_type" data-page="1" data-value-separator="<?php echo $users->blood_type->DisplayValueSeparatorAttribute() ?>" id="x_blood_type" name="x_blood_type"<?php echo $users->blood_type->EditAttributes() ?>>
<?php echo $users->blood_type->SelectOptionListHtml("x_blood_type") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_blood_type">
		<td class="col-sm-2"><span id="elh_users_blood_type"><?php echo $users->blood_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_blood_type" id="z_blood_type" value="="></span></td>
		<td<?php echo $users->blood_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_blood_type">
<select data-table="users" data-field="x_blood_type" data-page="1" data-value-separator="<?php echo $users->blood_type->DisplayValueSeparatorAttribute() ?>" id="x_blood_type" name="x_blood_type"<?php echo $users->blood_type->EditAttributes() ?>>
<?php echo $users->blood_type->SelectOptionListHtml("x_blood_type") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->driving_licence->Visible) { // driving_licence ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_driving_licence" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_driving_licence"><?php echo $users->driving_licence->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_driving_licence" id="z_driving_licence" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->driving_licence->CellAttributes() ?>>
			<span id="el_users_driving_licence">
<div id="tp_x_driving_licence" class="ewTemplate"><input type="radio" data-table="users" data-field="x_driving_licence" data-page="1" data-value-separator="<?php echo $users->driving_licence->DisplayValueSeparatorAttribute() ?>" name="x_driving_licence" id="x_driving_licence" value="{value}"<?php echo $users->driving_licence->EditAttributes() ?>></div>
<div id="dsl_x_driving_licence" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->driving_licence->RadioButtonListHtml(FALSE, "x_driving_licence", 1) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_driving_licence">
		<td class="col-sm-2"><span id="elh_users_driving_licence"><?php echo $users->driving_licence->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_driving_licence" id="z_driving_licence" value="="></span></td>
		<td<?php echo $users->driving_licence->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_driving_licence">
<div id="tp_x_driving_licence" class="ewTemplate"><input type="radio" data-table="users" data-field="x_driving_licence" data-page="1" data-value-separator="<?php echo $users->driving_licence->DisplayValueSeparatorAttribute() ?>" name="x_driving_licence" id="x_driving_licence" value="{value}"<?php echo $users->driving_licence->EditAttributes() ?>></div>
<div id="dsl_x_driving_licence" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->driving_licence->RadioButtonListHtml(FALSE, "x_driving_licence", 1) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->job->Visible) { // job ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_job" class="form-group">
		<label for="x_job" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_job"><?php echo $users->job->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_job" id="z_job" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->job->CellAttributes() ?>>
			<span id="el_users_job">
<select data-table="users" data-field="x_job" data-page="1" data-value-separator="<?php echo $users->job->DisplayValueSeparatorAttribute() ?>" id="x_job" name="x_job"<?php echo $users->job->EditAttributes() ?>>
<?php echo $users->job->SelectOptionListHtml("x_job") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_job">
		<td class="col-sm-2"><span id="elh_users_job"><?php echo $users->job->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_job" id="z_job" value="="></span></td>
		<td<?php echo $users->job->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_job">
<select data-table="users" data-field="x_job" data-page="1" data-value-separator="<?php echo $users->job->DisplayValueSeparatorAttribute() ?>" id="x_job" name="x_job"<?php echo $users->job->EditAttributes() ?>>
<?php echo $users->job->SelectOptionListHtml("x_job") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->volunteering_type->Visible) { // volunteering_type ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_volunteering_type" class="form-group">
		<label for="x_volunteering_type" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_volunteering_type"><?php echo $users->volunteering_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_volunteering_type" id="z_volunteering_type" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->volunteering_type->CellAttributes() ?>>
			<span id="el_users_volunteering_type">
<select data-table="users" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $users->volunteering_type->DisplayValueSeparatorAttribute() ?>" id="x_volunteering_type" name="x_volunteering_type"<?php echo $users->volunteering_type->EditAttributes() ?>>
<?php echo $users->volunteering_type->SelectOptionListHtml("x_volunteering_type") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_volunteering_type">
		<td class="col-sm-2"><span id="elh_users_volunteering_type"><?php echo $users->volunteering_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_volunteering_type" id="z_volunteering_type" value="="></span></td>
		<td<?php echo $users->volunteering_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_volunteering_type">
<select data-table="users" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $users->volunteering_type->DisplayValueSeparatorAttribute() ?>" id="x_volunteering_type" name="x_volunteering_type"<?php echo $users->volunteering_type->EditAttributes() ?>>
<?php echo $users->volunteering_type->SelectOptionListHtml("x_volunteering_type") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->marital_status->Visible) { // marital_status ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_marital_status" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_marital_status"><?php echo $users->marital_status->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_marital_status" id="z_marital_status" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->marital_status->CellAttributes() ?>>
			<span id="el_users_marital_status">
<div id="tp_x_marital_status" class="ewTemplate"><input type="radio" data-table="users" data-field="x_marital_status" data-page="1" data-value-separator="<?php echo $users->marital_status->DisplayValueSeparatorAttribute() ?>" name="x_marital_status" id="x_marital_status" value="{value}"<?php echo $users->marital_status->EditAttributes() ?>></div>
<div id="dsl_x_marital_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->marital_status->RadioButtonListHtml(FALSE, "x_marital_status", 1) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_marital_status">
		<td class="col-sm-2"><span id="elh_users_marital_status"><?php echo $users->marital_status->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_marital_status" id="z_marital_status" value="="></span></td>
		<td<?php echo $users->marital_status->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_marital_status">
<div id="tp_x_marital_status" class="ewTemplate"><input type="radio" data-table="users" data-field="x_marital_status" data-page="1" data-value-separator="<?php echo $users->marital_status->DisplayValueSeparatorAttribute() ?>" name="x_marital_status" id="x_marital_status" value="{value}"<?php echo $users->marital_status->EditAttributes() ?>></div>
<div id="dsl_x_marital_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->marital_status->RadioButtonListHtml(FALSE, "x_marital_status", 1) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("2") ?>" id="tab_users2"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch2" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->nationality_type->Visible) { // nationality_type ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_nationality_type" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_nationality_type"><?php echo $users->nationality_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nationality_type" id="z_nationality_type" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->nationality_type->CellAttributes() ?>>
			<span id="el_users_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="users" data-field="x_nationality_type" data-page="2" data-value-separator="<?php echo $users->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $users->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 2) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality_type">
		<td class="col-sm-2"><span id="elh_users_nationality_type"><?php echo $users->nationality_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_nationality_type" id="z_nationality_type" value="="></span></td>
		<td<?php echo $users->nationality_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="users" data-field="x_nationality_type" data-page="2" data-value-separator="<?php echo $users->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $users->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 2) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->nationality->Visible) { // nationality ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_nationality" class="form-group">
		<label for="x_nationality" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_nationality"><?php echo $users->nationality->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationality" id="z_nationality" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->nationality->CellAttributes() ?>>
			<span id="el_users_nationality">
<input type="text" data-table="users" data-field="x_nationality" data-page="2" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($users->nationality->getPlaceHolder()) ?>" value="<?php echo $users->nationality->EditValue ?>"<?php echo $users->nationality->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality">
		<td class="col-sm-2"><span id="elh_users_nationality"><?php echo $users->nationality->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_nationality" id="z_nationality" value="LIKE"></span></td>
		<td<?php echo $users->nationality->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_nationality">
<input type="text" data-table="users" data-field="x_nationality" data-page="2" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($users->nationality->getPlaceHolder()) ?>" value="<?php echo $users->nationality->EditValue ?>"<?php echo $users->nationality->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->unid->Visible) { // unid ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_unid" class="form-group">
		<label for="x_unid" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_unid"><?php echo $users->unid->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_unid" id="z_unid" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->unid->CellAttributes() ?>>
			<span id="el_users_unid">
<input type="text" data-table="users" data-field="x_unid" data-page="2" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($users->unid->getPlaceHolder()) ?>" value="<?php echo $users->unid->EditValue ?>"<?php echo $users->unid->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_unid">
		<td class="col-sm-2"><span id="elh_users_unid"><?php echo $users->unid->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_unid" id="z_unid" value="="></span></td>
		<td<?php echo $users->unid->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_unid">
<input type="text" data-table="users" data-field="x_unid" data-page="2" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($users->unid->getPlaceHolder()) ?>" value="<?php echo $users->unid->EditValue ?>"<?php echo $users->unid->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->visa_expiry_date->Visible) { // visa_expiry_date ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_visa_expiry_date" class="form-group">
		<label for="x_visa_expiry_date" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_visa_expiry_date"><?php echo $users->visa_expiry_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_visa_expiry_date" id="z_visa_expiry_date" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->visa_expiry_date->CellAttributes() ?>>
			<span id="el_users_visa_expiry_date">
<input type="text" data-table="users" data-field="x_visa_expiry_date" data-page="2" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->visa_expiry_date->EditValue ?>"<?php echo $users->visa_expiry_date->EditAttributes() ?>>
<?php if (!$users->visa_expiry_date->ReadOnly && !$users->visa_expiry_date->Disabled && !isset($users->visa_expiry_date->EditAttrs["readonly"]) && !isset($users->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_expiry_date">
		<td class="col-sm-2"><span id="elh_users_visa_expiry_date"><?php echo $users->visa_expiry_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_visa_expiry_date" id="z_visa_expiry_date" value="="></span></td>
		<td<?php echo $users->visa_expiry_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_visa_expiry_date">
<input type="text" data-table="users" data-field="x_visa_expiry_date" data-page="2" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->visa_expiry_date->EditValue ?>"<?php echo $users->visa_expiry_date->EditAttributes() ?>>
<?php if (!$users->visa_expiry_date->ReadOnly && !$users->visa_expiry_date->Disabled && !isset($users->visa_expiry_date->EditAttrs["readonly"]) && !isset($users->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->visa_copy->Visible) { // visa_copy ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_visa_copy" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_visa_copy"><?php echo $users->visa_copy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_visa_copy" id="z_visa_copy" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->visa_copy->CellAttributes() ?>>
			<span id="el_users_visa_copy">
<input type="text" data-table="users" data-field="x_visa_copy" data-page="2" name="x_visa_copy" id="x_visa_copy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->visa_copy->getPlaceHolder()) ?>" value="<?php echo $users->visa_copy->EditValue ?>"<?php echo $users->visa_copy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_copy">
		<td class="col-sm-2"><span id="elh_users_visa_copy"><?php echo $users->visa_copy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_visa_copy" id="z_visa_copy" value="LIKE"></span></td>
		<td<?php echo $users->visa_copy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_visa_copy">
<input type="text" data-table="users" data-field="x_visa_copy" data-page="2" name="x_visa_copy" id="x_visa_copy" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($users->visa_copy->getPlaceHolder()) ?>" value="<?php echo $users->visa_copy->EditValue ?>"<?php echo $users->visa_copy->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->current_emirate->Visible) { // current_emirate ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_current_emirate" class="form-group">
		<label for="x_current_emirate" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_current_emirate"><?php echo $users->current_emirate->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_current_emirate" id="z_current_emirate" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->current_emirate->CellAttributes() ?>>
			<span id="el_users_current_emirate">
<select data-table="users" data-field="x_current_emirate" data-page="2" data-value-separator="<?php echo $users->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $users->current_emirate->EditAttributes() ?>>
<?php echo $users->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_current_emirate">
		<td class="col-sm-2"><span id="elh_users_current_emirate"><?php echo $users->current_emirate->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_current_emirate" id="z_current_emirate" value="LIKE"></span></td>
		<td<?php echo $users->current_emirate->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_current_emirate">
<select data-table="users" data-field="x_current_emirate" data-page="2" data-value-separator="<?php echo $users->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $users->current_emirate->EditAttributes() ?>>
<?php echo $users->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->full_address->Visible) { // full_address ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_full_address" class="form-group">
		<label for="x_full_address" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_full_address"><?php echo $users->full_address->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_address" id="z_full_address" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->full_address->CellAttributes() ?>>
			<span id="el_users_full_address">
<input type="text" data-table="users" data-field="x_full_address" data-page="2" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($users->full_address->getPlaceHolder()) ?>" value="<?php echo $users->full_address->EditValue ?>"<?php echo $users->full_address->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_address">
		<td class="col-sm-2"><span id="elh_users_full_address"><?php echo $users->full_address->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_full_address" id="z_full_address" value="LIKE"></span></td>
		<td<?php echo $users->full_address->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_full_address">
<input type="text" data-table="users" data-field="x_full_address" data-page="2" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($users->full_address->getPlaceHolder()) ?>" value="<?php echo $users->full_address->EditValue ?>"<?php echo $users->full_address->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("3") ?>" id="tab_users3"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch3" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->emirates_id_number->Visible) { // emirates_id_number ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_emirates_id_number" class="form-group">
		<label for="x_emirates_id_number" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_emirates_id_number"><?php echo $users->emirates_id_number->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_number" id="z_emirates_id_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->emirates_id_number->CellAttributes() ?>>
			<span id="el_users_emirates_id_number">
<input type="text" data-table="users" data-field="x_emirates_id_number" data-page="3" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($users->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $users->emirates_id_number->EditValue ?>"<?php echo $users->emirates_id_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_number">
		<td class="col-sm-2"><span id="elh_users_emirates_id_number"><?php echo $users->emirates_id_number->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_number" id="z_emirates_id_number" value="LIKE"></span></td>
		<td<?php echo $users->emirates_id_number->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_emirates_id_number">
<input type="text" data-table="users" data-field="x_emirates_id_number" data-page="3" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($users->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $users->emirates_id_number->EditValue ?>"<?php echo $users->emirates_id_number->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->eid_expiry_date->Visible) { // eid_expiry_date ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_eid_expiry_date" class="form-group">
		<label for="x_eid_expiry_date" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_eid_expiry_date"><?php echo $users->eid_expiry_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_eid_expiry_date" id="z_eid_expiry_date" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->eid_expiry_date->CellAttributes() ?>>
			<span id="el_users_eid_expiry_date">
<input type="text" data-table="users" data-field="x_eid_expiry_date" data-page="3" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->eid_expiry_date->EditValue ?>"<?php echo $users->eid_expiry_date->EditAttributes() ?>>
<?php if (!$users->eid_expiry_date->ReadOnly && !$users->eid_expiry_date->Disabled && !isset($users->eid_expiry_date->EditAttrs["readonly"]) && !isset($users->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_eid_expiry_date">
		<td class="col-sm-2"><span id="elh_users_eid_expiry_date"><?php echo $users->eid_expiry_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_eid_expiry_date" id="z_eid_expiry_date" value="="></span></td>
		<td<?php echo $users->eid_expiry_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_eid_expiry_date">
<input type="text" data-table="users" data-field="x_eid_expiry_date" data-page="3" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->eid_expiry_date->EditValue ?>"<?php echo $users->eid_expiry_date->EditAttributes() ?>>
<?php if (!$users->eid_expiry_date->ReadOnly && !$users->eid_expiry_date->Disabled && !isset($users->eid_expiry_date->EditAttrs["readonly"]) && !isset($users->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->emirates_id_copy->Visible) { // emirates_id_copy ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_emirates_id_copy" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_emirates_id_copy"><?php echo $users->emirates_id_copy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_copy" id="z_emirates_id_copy" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->emirates_id_copy->CellAttributes() ?>>
			<span id="el_users_emirates_id_copy">
<input type="text" data-table="users" data-field="x_emirates_id_copy" data-page="3" name="x_emirates_id_copy" id="x_emirates_id_copy" placeholder="<?php echo ew_HtmlEncode($users->emirates_id_copy->getPlaceHolder()) ?>" value="<?php echo $users->emirates_id_copy->EditValue ?>"<?php echo $users->emirates_id_copy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_copy">
		<td class="col-sm-2"><span id="elh_users_emirates_id_copy"><?php echo $users->emirates_id_copy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_emirates_id_copy" id="z_emirates_id_copy" value="LIKE"></span></td>
		<td<?php echo $users->emirates_id_copy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_emirates_id_copy">
<input type="text" data-table="users" data-field="x_emirates_id_copy" data-page="3" name="x_emirates_id_copy" id="x_emirates_id_copy" placeholder="<?php echo ew_HtmlEncode($users->emirates_id_copy->getPlaceHolder()) ?>" value="<?php echo $users->emirates_id_copy->EditValue ?>"<?php echo $users->emirates_id_copy->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->passport_number->Visible) { // passport_number ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_passport_number" class="form-group">
		<label for="x_passport_number" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_passport_number"><?php echo $users->passport_number->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_number" id="z_passport_number" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->passport_number->CellAttributes() ?>>
			<span id="el_users_passport_number">
<input type="text" data-table="users" data-field="x_passport_number" data-page="3" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($users->passport_number->getPlaceHolder()) ?>" value="<?php echo $users->passport_number->EditValue ?>"<?php echo $users->passport_number->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_number">
		<td class="col-sm-2"><span id="elh_users_passport_number"><?php echo $users->passport_number->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_number" id="z_passport_number" value="LIKE"></span></td>
		<td<?php echo $users->passport_number->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_passport_number">
<input type="text" data-table="users" data-field="x_passport_number" data-page="3" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($users->passport_number->getPlaceHolder()) ?>" value="<?php echo $users->passport_number->EditValue ?>"<?php echo $users->passport_number->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->passport_ex_date->Visible) { // passport_ex_date ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_passport_ex_date" class="form-group">
		<label for="x_passport_ex_date" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_passport_ex_date"><?php echo $users->passport_ex_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_passport_ex_date" id="z_passport_ex_date" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->passport_ex_date->CellAttributes() ?>>
			<span id="el_users_passport_ex_date">
<input type="text" data-table="users" data-field="x_passport_ex_date" data-page="3" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($users->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $users->passport_ex_date->EditValue ?>"<?php echo $users->passport_ex_date->EditAttributes() ?>>
<?php if (!$users->passport_ex_date->ReadOnly && !$users->passport_ex_date->Disabled && !isset($users->passport_ex_date->EditAttrs["readonly"]) && !isset($users->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_ex_date">
		<td class="col-sm-2"><span id="elh_users_passport_ex_date"><?php echo $users->passport_ex_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_passport_ex_date" id="z_passport_ex_date" value="="></span></td>
		<td<?php echo $users->passport_ex_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_passport_ex_date">
<input type="text" data-table="users" data-field="x_passport_ex_date" data-page="3" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($users->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $users->passport_ex_date->EditValue ?>"<?php echo $users->passport_ex_date->EditAttributes() ?>>
<?php if (!$users->passport_ex_date->ReadOnly && !$users->passport_ex_date->Disabled && !isset($users->passport_ex_date->EditAttrs["readonly"]) && !isset($users->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fuserssearch", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->passport_copy->Visible) { // passport_copy ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_passport_copy" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_passport_copy"><?php echo $users->passport_copy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_copy" id="z_passport_copy" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->passport_copy->CellAttributes() ?>>
			<span id="el_users_passport_copy">
<input type="text" data-table="users" data-field="x_passport_copy" data-page="3" name="x_passport_copy" id="x_passport_copy" placeholder="<?php echo ew_HtmlEncode($users->passport_copy->getPlaceHolder()) ?>" value="<?php echo $users->passport_copy->EditValue ?>"<?php echo $users->passport_copy->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_copy">
		<td class="col-sm-2"><span id="elh_users_passport_copy"><?php echo $users->passport_copy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_passport_copy" id="z_passport_copy" value="LIKE"></span></td>
		<td<?php echo $users->passport_copy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_passport_copy">
<input type="text" data-table="users" data-field="x_passport_copy" data-page="3" name="x_passport_copy" id="x_passport_copy" placeholder="<?php echo ew_HtmlEncode($users->passport_copy->getPlaceHolder()) ?>" value="<?php echo $users->passport_copy->EditValue ?>"<?php echo $users->passport_copy->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("4") ?>" id="tab_users4"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch4" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->place_of_work->Visible) { // place_of_work ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_place_of_work" class="form-group">
		<label for="x_place_of_work" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_place_of_work"><?php echo $users->place_of_work->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_place_of_work" id="z_place_of_work" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->place_of_work->CellAttributes() ?>>
			<span id="el_users_place_of_work">
<input type="text" data-table="users" data-field="x_place_of_work" data-page="4" name="x_place_of_work" id="x_place_of_work" placeholder="<?php echo ew_HtmlEncode($users->place_of_work->getPlaceHolder()) ?>" value="<?php echo $users->place_of_work->EditValue ?>"<?php echo $users->place_of_work->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_place_of_work">
		<td class="col-sm-2"><span id="elh_users_place_of_work"><?php echo $users->place_of_work->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_place_of_work" id="z_place_of_work" value="LIKE"></span></td>
		<td<?php echo $users->place_of_work->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_place_of_work">
<input type="text" data-table="users" data-field="x_place_of_work" data-page="4" name="x_place_of_work" id="x_place_of_work" placeholder="<?php echo ew_HtmlEncode($users->place_of_work->getPlaceHolder()) ?>" value="<?php echo $users->place_of_work->EditValue ?>"<?php echo $users->place_of_work->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->qualifications->Visible) { // qualifications ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_qualifications" class="form-group">
		<label for="x_qualifications" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_qualifications"><?php echo $users->qualifications->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_qualifications" id="z_qualifications" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->qualifications->CellAttributes() ?>>
			<span id="el_users_qualifications">
<input type="text" data-table="users" data-field="x_qualifications" data-page="4" name="x_qualifications" id="x_qualifications" size="35" placeholder="<?php echo ew_HtmlEncode($users->qualifications->getPlaceHolder()) ?>" value="<?php echo $users->qualifications->EditValue ?>"<?php echo $users->qualifications->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_qualifications">
		<td class="col-sm-2"><span id="elh_users_qualifications"><?php echo $users->qualifications->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_qualifications" id="z_qualifications" value="LIKE"></span></td>
		<td<?php echo $users->qualifications->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_qualifications">
<input type="text" data-table="users" data-field="x_qualifications" data-page="4" name="x_qualifications" id="x_qualifications" size="35" placeholder="<?php echo ew_HtmlEncode($users->qualifications->getPlaceHolder()) ?>" value="<?php echo $users->qualifications->EditValue ?>"<?php echo $users->qualifications->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->cv->Visible) { // cv ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_cv" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_cv"><?php echo $users->cv->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_cv" id="z_cv" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->cv->CellAttributes() ?>>
			<span id="el_users_cv">
<input type="text" data-table="users" data-field="x_cv" data-page="4" name="x_cv" id="x_cv" placeholder="<?php echo ew_HtmlEncode($users->cv->getPlaceHolder()) ?>" value="<?php echo $users->cv->EditValue ?>"<?php echo $users->cv->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_cv">
		<td class="col-sm-2"><span id="elh_users_cv"><?php echo $users->cv->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_cv" id="z_cv" value="LIKE"></span></td>
		<td<?php echo $users->cv->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_cv">
<input type="text" data-table="users" data-field="x_cv" data-page="4" name="x_cv" id="x_cv" placeholder="<?php echo ew_HtmlEncode($users->cv->getPlaceHolder()) ?>" value="<?php echo $users->cv->EditValue ?>"<?php echo $users->cv->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->home_phone->Visible) { // home_phone ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_home_phone" class="form-group">
		<label for="x_home_phone" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_home_phone"><?php echo $users->home_phone->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_home_phone" id="z_home_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->home_phone->CellAttributes() ?>>
			<span id="el_users_home_phone">
<input type="text" data-table="users" data-field="x_home_phone" data-page="4" name="x_home_phone" id="x_home_phone" placeholder="<?php echo ew_HtmlEncode($users->home_phone->getPlaceHolder()) ?>" value="<?php echo $users->home_phone->EditValue ?>"<?php echo $users->home_phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_home_phone">
		<td class="col-sm-2"><span id="elh_users_home_phone"><?php echo $users->home_phone->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_home_phone" id="z_home_phone" value="LIKE"></span></td>
		<td<?php echo $users->home_phone->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_home_phone">
<input type="text" data-table="users" data-field="x_home_phone" data-page="4" name="x_home_phone" id="x_home_phone" placeholder="<?php echo ew_HtmlEncode($users->home_phone->getPlaceHolder()) ?>" value="<?php echo $users->home_phone->EditValue ?>"<?php echo $users->home_phone->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->work_phone->Visible) { // work_phone ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_work_phone" class="form-group">
		<label for="x_work_phone" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_work_phone"><?php echo $users->work_phone->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_work_phone" id="z_work_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->work_phone->CellAttributes() ?>>
			<span id="el_users_work_phone">
<input type="text" data-table="users" data-field="x_work_phone" data-page="4" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($users->work_phone->getPlaceHolder()) ?>" value="<?php echo $users->work_phone->EditValue ?>"<?php echo $users->work_phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_work_phone">
		<td class="col-sm-2"><span id="elh_users_work_phone"><?php echo $users->work_phone->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_work_phone" id="z_work_phone" value="LIKE"></span></td>
		<td<?php echo $users->work_phone->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_work_phone">
<input type="text" data-table="users" data-field="x_work_phone" data-page="4" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($users->work_phone->getPlaceHolder()) ?>" value="<?php echo $users->work_phone->EditValue ?>"<?php echo $users->work_phone->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->mobile_phone->Visible) { // mobile_phone ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_mobile_phone" class="form-group">
		<label for="x_mobile_phone" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_mobile_phone"><?php echo $users->mobile_phone->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_phone" id="z_mobile_phone" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->mobile_phone->CellAttributes() ?>>
			<span id="el_users_mobile_phone">
<input type="text" data-table="users" data-field="x_mobile_phone" data-page="4" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($users->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $users->mobile_phone->EditValue ?>"<?php echo $users->mobile_phone->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_users_mobile_phone"><?php echo $users->mobile_phone->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_mobile_phone" id="z_mobile_phone" value="LIKE"></span></td>
		<td<?php echo $users->mobile_phone->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_mobile_phone">
<input type="text" data-table="users" data-field="x_mobile_phone" data-page="4" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($users->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $users->mobile_phone->EditValue ?>"<?php echo $users->mobile_phone->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->fax->Visible) { // fax ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_fax" class="form-group">
		<label for="x_fax" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_fax"><?php echo $users->fax->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fax" id="z_fax" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->fax->CellAttributes() ?>>
			<span id="el_users_fax">
<input type="text" data-table="users" data-field="x_fax" data-page="4" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($users->fax->getPlaceHolder()) ?>" value="<?php echo $users->fax->EditValue ?>"<?php echo $users->fax->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_fax">
		<td class="col-sm-2"><span id="elh_users_fax"><?php echo $users->fax->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_fax" id="z_fax" value="LIKE"></span></td>
		<td<?php echo $users->fax->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_fax">
<input type="text" data-table="users" data-field="x_fax" data-page="4" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($users->fax->getPlaceHolder()) ?>" value="<?php echo $users->fax->EditValue ?>"<?php echo $users->fax->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->pobbox->Visible) { // pobbox ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_pobbox" class="form-group">
		<label for="x_pobbox" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_pobbox"><?php echo $users->pobbox->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pobbox" id="z_pobbox" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->pobbox->CellAttributes() ?>>
			<span id="el_users_pobbox">
<input type="text" data-table="users" data-field="x_pobbox" data-page="4" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($users->pobbox->getPlaceHolder()) ?>" value="<?php echo $users->pobbox->EditValue ?>"<?php echo $users->pobbox->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_pobbox">
		<td class="col-sm-2"><span id="elh_users_pobbox"><?php echo $users->pobbox->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_pobbox" id="z_pobbox" value="LIKE"></span></td>
		<td<?php echo $users->pobbox->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_pobbox">
<input type="text" data-table="users" data-field="x_pobbox" data-page="4" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($users->pobbox->getPlaceHolder()) ?>" value="<?php echo $users->pobbox->EditValue ?>"<?php echo $users->pobbox->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("5") ?>" id="tab_users5"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch5" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r__email" class="form-group">
		<label for="x__email" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users__email"><?php echo $users->_email->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->_email->CellAttributes() ?>>
			<span id="el_users__email">
<input type="text" data-table="users" data-field="x__email" data-page="5" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($users->_email->getPlaceHolder()) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_users__email"><?php echo $users->_email->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></span></td>
		<td<?php echo $users->_email->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users__email">
<input type="text" data-table="users" data-field="x__email" data-page="5" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($users->_email->getPlaceHolder()) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_password" class="form-group">
		<label for="x_password" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_password"><?php echo $users->password->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_password" id="z_password" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->password->CellAttributes() ?>>
			<span id="el_users_password">
<input type="text" data-table="users" data-field="x_password" data-page="5" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($users->password->getPlaceHolder()) ?>" value="<?php echo $users->password->EditValue ?>"<?php echo $users->password->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_users_password"><?php echo $users->password->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_password" id="z_password" value="LIKE"></span></td>
		<td<?php echo $users->password->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_password">
<input type="text" data-table="users" data-field="x_password" data-page="5" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($users->password->getPlaceHolder()) ?>" value="<?php echo $users->password->EditValue ?>"<?php echo $users->password->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->total_voluntary_hours->Visible) { // total_voluntary_hours ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_total_voluntary_hours" class="form-group">
		<label for="x_total_voluntary_hours" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_total_voluntary_hours"><?php echo $users->total_voluntary_hours->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_total_voluntary_hours" id="z_total_voluntary_hours" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->total_voluntary_hours->CellAttributes() ?>>
			<span id="el_users_total_voluntary_hours">
<input type="text" data-table="users" data-field="x_total_voluntary_hours" data-page="5" name="x_total_voluntary_hours" id="x_total_voluntary_hours" placeholder="<?php echo ew_HtmlEncode($users->total_voluntary_hours->getPlaceHolder()) ?>" value="<?php echo $users->total_voluntary_hours->EditValue ?>"<?php echo $users->total_voluntary_hours->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_total_voluntary_hours">
		<td class="col-sm-2"><span id="elh_users_total_voluntary_hours"><?php echo $users->total_voluntary_hours->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_total_voluntary_hours" id="z_total_voluntary_hours" value="LIKE"></span></td>
		<td<?php echo $users->total_voluntary_hours->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_total_voluntary_hours">
<input type="text" data-table="users" data-field="x_total_voluntary_hours" data-page="5" name="x_total_voluntary_hours" id="x_total_voluntary_hours" placeholder="<?php echo ew_HtmlEncode($users->total_voluntary_hours->getPlaceHolder()) ?>" value="<?php echo $users->total_voluntary_hours->EditValue ?>"<?php echo $users->total_voluntary_hours->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->overall_evaluation->Visible) { // overall_evaluation ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_overall_evaluation" class="form-group">
		<label for="x_overall_evaluation" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_overall_evaluation"><?php echo $users->overall_evaluation->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_overall_evaluation" id="z_overall_evaluation" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->overall_evaluation->CellAttributes() ?>>
			<span id="el_users_overall_evaluation">
<input type="text" data-table="users" data-field="x_overall_evaluation" data-page="5" name="x_overall_evaluation" id="x_overall_evaluation" size="30" placeholder="<?php echo ew_HtmlEncode($users->overall_evaluation->getPlaceHolder()) ?>" value="<?php echo $users->overall_evaluation->EditValue ?>"<?php echo $users->overall_evaluation->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_overall_evaluation">
		<td class="col-sm-2"><span id="elh_users_overall_evaluation"><?php echo $users->overall_evaluation->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_overall_evaluation" id="z_overall_evaluation" value="="></span></td>
		<td<?php echo $users->overall_evaluation->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_overall_evaluation">
<input type="text" data-table="users" data-field="x_overall_evaluation" data-page="5" name="x_overall_evaluation" id="x_overall_evaluation" size="30" placeholder="<?php echo ew_HtmlEncode($users->overall_evaluation->getPlaceHolder()) ?>" value="<?php echo $users->overall_evaluation->EditValue ?>"<?php echo $users->overall_evaluation->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("6") ?>" id="tab_users6"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch6" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_admin_approval"><?php echo $users->admin_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->admin_approval->CellAttributes() ?>>
			<span id="el_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_admin_approval" data-page="6" data-value-separator="<?php echo $users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 6) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_users_admin_approval"><?php echo $users->admin_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></span></td>
		<td<?php echo $users->admin_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_admin_approval" data-page="6" data-value-separator="<?php echo $users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 6) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->lastUpdatedBy->Visible) { // lastUpdatedBy ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_lastUpdatedBy" class="form-group">
		<label for="x_lastUpdatedBy" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_lastUpdatedBy"><?php echo $users->lastUpdatedBy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_lastUpdatedBy" id="z_lastUpdatedBy" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->lastUpdatedBy->CellAttributes() ?>>
			<span id="el_users_lastUpdatedBy">
<select data-table="users" data-field="x_lastUpdatedBy" data-page="6" data-value-separator="<?php echo $users->lastUpdatedBy->DisplayValueSeparatorAttribute() ?>" id="x_lastUpdatedBy" name="x_lastUpdatedBy"<?php echo $users->lastUpdatedBy->EditAttributes() ?>>
<?php echo $users->lastUpdatedBy->SelectOptionListHtml("x_lastUpdatedBy") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_lastUpdatedBy">
		<td class="col-sm-2"><span id="elh_users_lastUpdatedBy"><?php echo $users->lastUpdatedBy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_lastUpdatedBy" id="z_lastUpdatedBy" value="LIKE"></span></td>
		<td<?php echo $users->lastUpdatedBy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_lastUpdatedBy">
<select data-table="users" data-field="x_lastUpdatedBy" data-page="6" data-value-separator="<?php echo $users->lastUpdatedBy->DisplayValueSeparatorAttribute() ?>" id="x_lastUpdatedBy" name="x_lastUpdatedBy"<?php echo $users->lastUpdatedBy->EditAttributes() ?>>
<?php echo $users->lastUpdatedBy->SelectOptionListHtml("x_lastUpdatedBy") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label for="x_admin_comment" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_admin_comment"><?php echo $users->admin_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->admin_comment->CellAttributes() ?>>
			<span id="el_users_admin_comment">
<input type="text" data-table="users" data-field="x_admin_comment" data-page="6" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($users->admin_comment->getPlaceHolder()) ?>" value="<?php echo $users->admin_comment->EditValue ?>"<?php echo $users->admin_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_users_admin_comment"><?php echo $users->admin_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></span></td>
		<td<?php echo $users->admin_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_admin_comment">
<input type="text" data-table="users" data-field="x_admin_comment" data-page="6" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($users->admin_comment->getPlaceHolder()) ?>" value="<?php echo $users->admin_comment->EditValue ?>"<?php echo $users->admin_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_search->MultiPages->PageStyle("7") ?>" id="tab_users7"><!-- multi-page .tab-pane -->
<?php if ($users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_userssearch7" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->security_approval->Visible) { // security_approval ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_security_approval" class="form-group">
		<label class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_security_approval"><?php echo $users->security_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_security_approval" id="z_security_approval" value="="></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->security_approval->CellAttributes() ?>>
			<span id="el_users_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_security_approval" data-page="7" data-value-separator="<?php echo $users->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $users->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 7) ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_approval">
		<td class="col-sm-2"><span id="elh_users_security_approval"><?php echo $users->security_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_security_approval" id="z_security_approval" value="="></span></td>
		<td<?php echo $users->security_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_security_approval" data-page="7" data-value-separator="<?php echo $users->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $users->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 7) ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->approvedBy->Visible) { // approvedBy ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_approvedBy" class="form-group">
		<label for="x_approvedBy" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_approvedBy"><?php echo $users->approvedBy->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approvedBy" id="z_approvedBy" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->approvedBy->CellAttributes() ?>>
			<span id="el_users_approvedBy">
<select data-table="users" data-field="x_approvedBy" data-page="7" data-value-separator="<?php echo $users->approvedBy->DisplayValueSeparatorAttribute() ?>" id="x_approvedBy" name="x_approvedBy"<?php echo $users->approvedBy->EditAttributes() ?>>
<?php echo $users->approvedBy->SelectOptionListHtml("x_approvedBy") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_approvedBy">
		<td class="col-sm-2"><span id="elh_users_approvedBy"><?php echo $users->approvedBy->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_approvedBy" id="z_approvedBy" value="LIKE"></span></td>
		<td<?php echo $users->approvedBy->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_approvedBy">
<select data-table="users" data-field="x_approvedBy" data-page="7" data-value-separator="<?php echo $users->approvedBy->DisplayValueSeparatorAttribute() ?>" id="x_approvedBy" name="x_approvedBy"<?php echo $users->approvedBy->EditAttributes() ?>>
<?php echo $users->approvedBy->SelectOptionListHtml("x_approvedBy") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
<?php if ($users_search->IsMobileOrModal) { ?>
	<div id="r_security_comment" class="form-group">
		<label for="x_security_comment" class="<?php echo $users_search->LeftColumnClass ?>"><span id="elh_users_security_comment"><?php echo $users->security_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_security_comment" id="z_security_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $users_search->RightColumnClass ?>"><div<?php echo $users->security_comment->CellAttributes() ?>>
			<span id="el_users_security_comment">
<input type="text" data-table="users" data-field="x_security_comment" data-page="7" name="x_security_comment" id="x_security_comment" size="35" placeholder="<?php echo ew_HtmlEncode($users->security_comment->getPlaceHolder()) ?>" value="<?php echo $users->security_comment->EditValue ?>"<?php echo $users->security_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_comment">
		<td class="col-sm-2"><span id="elh_users_security_comment"><?php echo $users->security_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_security_comment" id="z_security_comment" value="LIKE"></span></td>
		<td<?php echo $users->security_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_users_security_comment">
<input type="text" data-table="users" data-field="x_security_comment" data-page="7" name="x_security_comment" id="x_security_comment" size="35" placeholder="<?php echo ew_HtmlEncode($users->security_comment->getPlaceHolder()) ?>" value="<?php echo $users->security_comment->EditValue ?>"<?php echo $users->security_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$users_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $users_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$users_search->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fuserssearch.Init();
</script>
<?php
$users_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_search->Page_Terminate();
?>
