<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "global_settingsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$global_settings_search = NULL; // Initialize page object first

class cglobal_settings_search extends cglobal_settings {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'global_settings';

	// Page object name
	var $PageObjName = 'global_settings_search';

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

		// Table object (global_settings)
		if (!isset($GLOBALS["global_settings"]) || get_class($GLOBALS["global_settings"]) == "cglobal_settings") {
			$GLOBALS["global_settings"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["global_settings"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'global_settings', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("global_settingslist.php"));
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
		$this->global_id->SetVisibility();
		$this->global_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->system_name_ar->SetVisibility();
		$this->system_name_en->SetVisibility();
		$this->contact_email->SetVisibility();
		$this->system_logo->SetVisibility();
		$this->contact_info_ar->SetVisibility();
		$this->contact_info_en->SetVisibility();
		$this->about_us_ar->SetVisibility();
		$this->about_us_en->SetVisibility();
		$this->twiiter->SetVisibility();
		$this->facebook->SetVisibility();
		$this->instagram->SetVisibility();
		$this->youtube->SetVisibility();

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
		global $EW_EXPORT, $global_settings;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($global_settings);
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
					if ($pageName == "global_settingsview.php")
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
						$sSrchStr = "global_settingslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->global_id); // global_id
		$this->BuildSearchUrl($sSrchUrl, $this->system_name_ar); // system_name_ar
		$this->BuildSearchUrl($sSrchUrl, $this->system_name_en); // system_name_en
		$this->BuildSearchUrl($sSrchUrl, $this->contact_email); // contact_email
		$this->BuildSearchUrl($sSrchUrl, $this->system_logo); // system_logo
		$this->BuildSearchUrl($sSrchUrl, $this->contact_info_ar); // contact_info_ar
		$this->BuildSearchUrl($sSrchUrl, $this->contact_info_en); // contact_info_en
		$this->BuildSearchUrl($sSrchUrl, $this->about_us_ar); // about_us_ar
		$this->BuildSearchUrl($sSrchUrl, $this->about_us_en); // about_us_en
		$this->BuildSearchUrl($sSrchUrl, $this->twiiter); // twiiter
		$this->BuildSearchUrl($sSrchUrl, $this->facebook); // facebook
		$this->BuildSearchUrl($sSrchUrl, $this->instagram); // instagram
		$this->BuildSearchUrl($sSrchUrl, $this->youtube); // youtube
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
		// global_id

		$this->global_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_global_id");
		$this->global_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_global_id");

		// system_name_ar
		$this->system_name_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_system_name_ar");
		$this->system_name_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_system_name_ar");

		// system_name_en
		$this->system_name_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_system_name_en");
		$this->system_name_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_system_name_en");

		// contact_email
		$this->contact_email->AdvancedSearch->SearchValue = $objForm->GetValue("x_contact_email");
		$this->contact_email->AdvancedSearch->SearchOperator = $objForm->GetValue("z_contact_email");

		// system_logo
		$this->system_logo->AdvancedSearch->SearchValue = $objForm->GetValue("x_system_logo");
		$this->system_logo->AdvancedSearch->SearchOperator = $objForm->GetValue("z_system_logo");

		// contact_info_ar
		$this->contact_info_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_contact_info_ar");
		$this->contact_info_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_contact_info_ar");

		// contact_info_en
		$this->contact_info_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_contact_info_en");
		$this->contact_info_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_contact_info_en");

		// about_us_ar
		$this->about_us_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_about_us_ar");
		$this->about_us_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_about_us_ar");

		// about_us_en
		$this->about_us_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_about_us_en");
		$this->about_us_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_about_us_en");

		// twiiter
		$this->twiiter->AdvancedSearch->SearchValue = $objForm->GetValue("x_twiiter");
		$this->twiiter->AdvancedSearch->SearchOperator = $objForm->GetValue("z_twiiter");

		// facebook
		$this->facebook->AdvancedSearch->SearchValue = $objForm->GetValue("x_facebook");
		$this->facebook->AdvancedSearch->SearchOperator = $objForm->GetValue("z_facebook");

		// instagram
		$this->instagram->AdvancedSearch->SearchValue = $objForm->GetValue("x_instagram");
		$this->instagram->AdvancedSearch->SearchOperator = $objForm->GetValue("z_instagram");

		// youtube
		$this->youtube->AdvancedSearch->SearchValue = $objForm->GetValue("x_youtube");
		$this->youtube->AdvancedSearch->SearchOperator = $objForm->GetValue("z_youtube");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// global_id
			$this->global_id->EditAttrs["class"] = "form-control";
			$this->global_id->EditCustomAttributes = "";
			$this->global_id->EditValue = ew_HtmlEncode($this->global_id->AdvancedSearch->SearchValue);
			$this->global_id->PlaceHolder = ew_RemoveHtml($this->global_id->FldCaption());

			// system_name_ar
			$this->system_name_ar->EditAttrs["class"] = "form-control";
			$this->system_name_ar->EditCustomAttributes = "";
			$this->system_name_ar->EditValue = ew_HtmlEncode($this->system_name_ar->AdvancedSearch->SearchValue);
			$this->system_name_ar->PlaceHolder = ew_RemoveHtml($this->system_name_ar->FldCaption());

			// system_name_en
			$this->system_name_en->EditAttrs["class"] = "form-control";
			$this->system_name_en->EditCustomAttributes = "";
			$this->system_name_en->EditValue = ew_HtmlEncode($this->system_name_en->AdvancedSearch->SearchValue);
			$this->system_name_en->PlaceHolder = ew_RemoveHtml($this->system_name_en->FldCaption());

			// contact_email
			$this->contact_email->EditAttrs["class"] = "form-control";
			$this->contact_email->EditCustomAttributes = "";
			$this->contact_email->EditValue = ew_HtmlEncode($this->contact_email->AdvancedSearch->SearchValue);
			$this->contact_email->PlaceHolder = ew_RemoveHtml($this->contact_email->FldCaption());

			// system_logo
			$this->system_logo->EditAttrs["class"] = "form-control";
			$this->system_logo->EditCustomAttributes = "";
			$this->system_logo->EditValue = ew_HtmlEncode($this->system_logo->AdvancedSearch->SearchValue);
			$this->system_logo->PlaceHolder = ew_RemoveHtml($this->system_logo->FldCaption());

			// contact_info_ar
			$this->contact_info_ar->EditAttrs["class"] = "form-control";
			$this->contact_info_ar->EditCustomAttributes = "";
			$this->contact_info_ar->EditValue = ew_HtmlEncode($this->contact_info_ar->AdvancedSearch->SearchValue);
			$this->contact_info_ar->PlaceHolder = ew_RemoveHtml($this->contact_info_ar->FldCaption());

			// contact_info_en
			$this->contact_info_en->EditAttrs["class"] = "form-control";
			$this->contact_info_en->EditCustomAttributes = "";
			$this->contact_info_en->EditValue = ew_HtmlEncode($this->contact_info_en->AdvancedSearch->SearchValue);
			$this->contact_info_en->PlaceHolder = ew_RemoveHtml($this->contact_info_en->FldCaption());

			// about_us_ar
			$this->about_us_ar->EditAttrs["class"] = "form-control";
			$this->about_us_ar->EditCustomAttributes = "";
			$this->about_us_ar->EditValue = ew_HtmlEncode($this->about_us_ar->AdvancedSearch->SearchValue);
			$this->about_us_ar->PlaceHolder = ew_RemoveHtml($this->about_us_ar->FldCaption());

			// about_us_en
			$this->about_us_en->EditAttrs["class"] = "form-control";
			$this->about_us_en->EditCustomAttributes = "";
			$this->about_us_en->EditValue = ew_HtmlEncode($this->about_us_en->AdvancedSearch->SearchValue);
			$this->about_us_en->PlaceHolder = ew_RemoveHtml($this->about_us_en->FldCaption());

			// twiiter
			$this->twiiter->EditAttrs["class"] = "form-control";
			$this->twiiter->EditCustomAttributes = "";
			$this->twiiter->EditValue = ew_HtmlEncode($this->twiiter->AdvancedSearch->SearchValue);
			$this->twiiter->PlaceHolder = ew_RemoveHtml($this->twiiter->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->AdvancedSearch->SearchValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// instagram
			$this->instagram->EditAttrs["class"] = "form-control";
			$this->instagram->EditCustomAttributes = "";
			$this->instagram->EditValue = ew_HtmlEncode($this->instagram->AdvancedSearch->SearchValue);
			$this->instagram->PlaceHolder = ew_RemoveHtml($this->instagram->FldCaption());

			// youtube
			$this->youtube->EditAttrs["class"] = "form-control";
			$this->youtube->EditCustomAttributes = "";
			$this->youtube->EditValue = ew_HtmlEncode($this->youtube->AdvancedSearch->SearchValue);
			$this->youtube->PlaceHolder = ew_RemoveHtml($this->youtube->FldCaption());
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
		if (!ew_CheckInteger($this->global_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->global_id->FldErrMsg());
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
		$this->global_id->AdvancedSearch->Load();
		$this->system_name_ar->AdvancedSearch->Load();
		$this->system_name_en->AdvancedSearch->Load();
		$this->contact_email->AdvancedSearch->Load();
		$this->system_logo->AdvancedSearch->Load();
		$this->contact_info_ar->AdvancedSearch->Load();
		$this->contact_info_en->AdvancedSearch->Load();
		$this->about_us_ar->AdvancedSearch->Load();
		$this->about_us_en->AdvancedSearch->Load();
		$this->twiiter->AdvancedSearch->Load();
		$this->facebook->AdvancedSearch->Load();
		$this->instagram->AdvancedSearch->Load();
		$this->youtube->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("global_settingslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
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
if (!isset($global_settings_search)) $global_settings_search = new cglobal_settings_search();

// Page init
$global_settings_search->Page_Init();

// Page main
$global_settings_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$global_settings_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($global_settings_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fglobal_settingssearch = new ew_Form("fglobal_settingssearch", "search");
<?php } else { ?>
var CurrentForm = fglobal_settingssearch = new ew_Form("fglobal_settingssearch", "search");
<?php } ?>

// Form_CustomValidate event
fglobal_settingssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fglobal_settingssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search
// Validate function for search

fglobal_settingssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_global_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($global_settings->global_id->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $global_settings_search->ShowPageHeader(); ?>
<?php
$global_settings_search->ShowMessage();
?>
<form name="fglobal_settingssearch" id="fglobal_settingssearch" class="<?php echo $global_settings_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($global_settings_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $global_settings_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="global_settings">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($global_settings_search->IsModal) ?>">
<?php if (!$global_settings_search->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_global_settingssearch" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($global_settings->global_id->Visible) { // global_id ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_global_id" class="form-group">
		<label for="x_global_id" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_global_id"><?php echo $global_settings->global_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_global_id" id="z_global_id" value="="></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->global_id->CellAttributes() ?>>
			<span id="el_global_settings_global_id">
<input type="text" data-table="global_settings" data-field="x_global_id" name="x_global_id" id="x_global_id" placeholder="<?php echo ew_HtmlEncode($global_settings->global_id->getPlaceHolder()) ?>" value="<?php echo $global_settings->global_id->EditValue ?>"<?php echo $global_settings->global_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_global_id">
		<td class="col-sm-2"><span id="elh_global_settings_global_id"><?php echo $global_settings->global_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_global_id" id="z_global_id" value="="></span></td>
		<td<?php echo $global_settings->global_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_global_id">
<input type="text" data-table="global_settings" data-field="x_global_id" name="x_global_id" id="x_global_id" placeholder="<?php echo ew_HtmlEncode($global_settings->global_id->getPlaceHolder()) ?>" value="<?php echo $global_settings->global_id->EditValue ?>"<?php echo $global_settings->global_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->system_name_ar->Visible) { // system_name_ar ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_system_name_ar" class="form-group">
		<label for="x_system_name_ar" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_system_name_ar"><?php echo $global_settings->system_name_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_system_name_ar" id="z_system_name_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->system_name_ar->CellAttributes() ?>>
			<span id="el_global_settings_system_name_ar">
<input type="text" data-table="global_settings" data-field="x_system_name_ar" name="x_system_name_ar" id="x_system_name_ar" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_ar->EditValue ?>"<?php echo $global_settings->system_name_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_system_name_ar">
		<td class="col-sm-2"><span id="elh_global_settings_system_name_ar"><?php echo $global_settings->system_name_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_system_name_ar" id="z_system_name_ar" value="LIKE"></span></td>
		<td<?php echo $global_settings->system_name_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_system_name_ar">
<input type="text" data-table="global_settings" data-field="x_system_name_ar" name="x_system_name_ar" id="x_system_name_ar" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_ar->EditValue ?>"<?php echo $global_settings->system_name_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->system_name_en->Visible) { // system_name_en ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_system_name_en" class="form-group">
		<label for="x_system_name_en" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_system_name_en"><?php echo $global_settings->system_name_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_system_name_en" id="z_system_name_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->system_name_en->CellAttributes() ?>>
			<span id="el_global_settings_system_name_en">
<input type="text" data-table="global_settings" data-field="x_system_name_en" name="x_system_name_en" id="x_system_name_en" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_en->EditValue ?>"<?php echo $global_settings->system_name_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_system_name_en">
		<td class="col-sm-2"><span id="elh_global_settings_system_name_en"><?php echo $global_settings->system_name_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_system_name_en" id="z_system_name_en" value="LIKE"></span></td>
		<td<?php echo $global_settings->system_name_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_system_name_en">
<input type="text" data-table="global_settings" data-field="x_system_name_en" name="x_system_name_en" id="x_system_name_en" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_en->EditValue ?>"<?php echo $global_settings->system_name_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_email->Visible) { // contact_email ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_contact_email" class="form-group">
		<label for="x_contact_email" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_contact_email"><?php echo $global_settings->contact_email->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_email" id="z_contact_email" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->contact_email->CellAttributes() ?>>
			<span id="el_global_settings_contact_email">
<input type="text" data-table="global_settings" data-field="x_contact_email" name="x_contact_email" id="x_contact_email" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_email->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_email->EditValue ?>"<?php echo $global_settings->contact_email->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_contact_email">
		<td class="col-sm-2"><span id="elh_global_settings_contact_email"><?php echo $global_settings->contact_email->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_email" id="z_contact_email" value="LIKE"></span></td>
		<td<?php echo $global_settings->contact_email->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_contact_email">
<input type="text" data-table="global_settings" data-field="x_contact_email" name="x_contact_email" id="x_contact_email" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_email->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_email->EditValue ?>"<?php echo $global_settings->contact_email->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->system_logo->Visible) { // system_logo ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_system_logo" class="form-group">
		<label class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_system_logo"><?php echo $global_settings->system_logo->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_system_logo" id="z_system_logo" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->system_logo->CellAttributes() ?>>
			<span id="el_global_settings_system_logo">
<input type="text" data-table="global_settings" data-field="x_system_logo" name="x_system_logo" id="x_system_logo" placeholder="<?php echo ew_HtmlEncode($global_settings->system_logo->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_logo->EditValue ?>"<?php echo $global_settings->system_logo->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_system_logo">
		<td class="col-sm-2"><span id="elh_global_settings_system_logo"><?php echo $global_settings->system_logo->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_system_logo" id="z_system_logo" value="LIKE"></span></td>
		<td<?php echo $global_settings->system_logo->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_system_logo">
<input type="text" data-table="global_settings" data-field="x_system_logo" name="x_system_logo" id="x_system_logo" placeholder="<?php echo ew_HtmlEncode($global_settings->system_logo->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_logo->EditValue ?>"<?php echo $global_settings->system_logo->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_info_ar->Visible) { // contact_info_ar ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_contact_info_ar" class="form-group">
		<label class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_contact_info_ar"><?php echo $global_settings->contact_info_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_info_ar" id="z_contact_info_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->contact_info_ar->CellAttributes() ?>>
			<span id="el_global_settings_contact_info_ar">
<input type="text" data-table="global_settings" data-field="x_contact_info_ar" name="x_contact_info_ar" id="x_contact_info_ar" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_info_ar->EditValue ?>"<?php echo $global_settings->contact_info_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_contact_info_ar">
		<td class="col-sm-2"><span id="elh_global_settings_contact_info_ar"><?php echo $global_settings->contact_info_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_info_ar" id="z_contact_info_ar" value="LIKE"></span></td>
		<td<?php echo $global_settings->contact_info_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_contact_info_ar">
<input type="text" data-table="global_settings" data-field="x_contact_info_ar" name="x_contact_info_ar" id="x_contact_info_ar" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_info_ar->EditValue ?>"<?php echo $global_settings->contact_info_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_info_en->Visible) { // contact_info_en ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_contact_info_en" class="form-group">
		<label class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_contact_info_en"><?php echo $global_settings->contact_info_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_info_en" id="z_contact_info_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->contact_info_en->CellAttributes() ?>>
			<span id="el_global_settings_contact_info_en">
<input type="text" data-table="global_settings" data-field="x_contact_info_en" name="x_contact_info_en" id="x_contact_info_en" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_info_en->EditValue ?>"<?php echo $global_settings->contact_info_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_contact_info_en">
		<td class="col-sm-2"><span id="elh_global_settings_contact_info_en"><?php echo $global_settings->contact_info_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_contact_info_en" id="z_contact_info_en" value="LIKE"></span></td>
		<td<?php echo $global_settings->contact_info_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_contact_info_en">
<input type="text" data-table="global_settings" data-field="x_contact_info_en" name="x_contact_info_en" id="x_contact_info_en" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_info_en->EditValue ?>"<?php echo $global_settings->contact_info_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->about_us_ar->Visible) { // about_us_ar ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_about_us_ar" class="form-group">
		<label class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_about_us_ar"><?php echo $global_settings->about_us_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_about_us_ar" id="z_about_us_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->about_us_ar->CellAttributes() ?>>
			<span id="el_global_settings_about_us_ar">
<input type="text" data-table="global_settings" data-field="x_about_us_ar" name="x_about_us_ar" id="x_about_us_ar" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->about_us_ar->EditValue ?>"<?php echo $global_settings->about_us_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_about_us_ar">
		<td class="col-sm-2"><span id="elh_global_settings_about_us_ar"><?php echo $global_settings->about_us_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_about_us_ar" id="z_about_us_ar" value="LIKE"></span></td>
		<td<?php echo $global_settings->about_us_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_about_us_ar">
<input type="text" data-table="global_settings" data-field="x_about_us_ar" name="x_about_us_ar" id="x_about_us_ar" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->about_us_ar->EditValue ?>"<?php echo $global_settings->about_us_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->about_us_en->Visible) { // about_us_en ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_about_us_en" class="form-group">
		<label class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_about_us_en"><?php echo $global_settings->about_us_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_about_us_en" id="z_about_us_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->about_us_en->CellAttributes() ?>>
			<span id="el_global_settings_about_us_en">
<input type="text" data-table="global_settings" data-field="x_about_us_en" name="x_about_us_en" id="x_about_us_en" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->about_us_en->EditValue ?>"<?php echo $global_settings->about_us_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_about_us_en">
		<td class="col-sm-2"><span id="elh_global_settings_about_us_en"><?php echo $global_settings->about_us_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_about_us_en" id="z_about_us_en" value="LIKE"></span></td>
		<td<?php echo $global_settings->about_us_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_about_us_en">
<input type="text" data-table="global_settings" data-field="x_about_us_en" name="x_about_us_en" id="x_about_us_en" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->about_us_en->EditValue ?>"<?php echo $global_settings->about_us_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->twiiter->Visible) { // twiiter ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_twiiter" class="form-group">
		<label for="x_twiiter" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_twiiter"><?php echo $global_settings->twiiter->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_twiiter" id="z_twiiter" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->twiiter->CellAttributes() ?>>
			<span id="el_global_settings_twiiter">
<input type="text" data-table="global_settings" data-field="x_twiiter" name="x_twiiter" id="x_twiiter" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->twiiter->getPlaceHolder()) ?>" value="<?php echo $global_settings->twiiter->EditValue ?>"<?php echo $global_settings->twiiter->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_twiiter">
		<td class="col-sm-2"><span id="elh_global_settings_twiiter"><?php echo $global_settings->twiiter->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_twiiter" id="z_twiiter" value="LIKE"></span></td>
		<td<?php echo $global_settings->twiiter->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_twiiter">
<input type="text" data-table="global_settings" data-field="x_twiiter" name="x_twiiter" id="x_twiiter" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->twiiter->getPlaceHolder()) ?>" value="<?php echo $global_settings->twiiter->EditValue ?>"<?php echo $global_settings->twiiter->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->facebook->Visible) { // facebook ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_facebook" class="form-group">
		<label for="x_facebook" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_facebook"><?php echo $global_settings->facebook->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_facebook" id="z_facebook" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->facebook->CellAttributes() ?>>
			<span id="el_global_settings_facebook">
<input type="text" data-table="global_settings" data-field="x_facebook" name="x_facebook" id="x_facebook" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->facebook->getPlaceHolder()) ?>" value="<?php echo $global_settings->facebook->EditValue ?>"<?php echo $global_settings->facebook->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_facebook">
		<td class="col-sm-2"><span id="elh_global_settings_facebook"><?php echo $global_settings->facebook->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_facebook" id="z_facebook" value="LIKE"></span></td>
		<td<?php echo $global_settings->facebook->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_facebook">
<input type="text" data-table="global_settings" data-field="x_facebook" name="x_facebook" id="x_facebook" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->facebook->getPlaceHolder()) ?>" value="<?php echo $global_settings->facebook->EditValue ?>"<?php echo $global_settings->facebook->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->instagram->Visible) { // instagram ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_instagram" class="form-group">
		<label for="x_instagram" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_instagram"><?php echo $global_settings->instagram->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_instagram" id="z_instagram" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->instagram->CellAttributes() ?>>
			<span id="el_global_settings_instagram">
<input type="text" data-table="global_settings" data-field="x_instagram" name="x_instagram" id="x_instagram" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->instagram->getPlaceHolder()) ?>" value="<?php echo $global_settings->instagram->EditValue ?>"<?php echo $global_settings->instagram->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_instagram">
		<td class="col-sm-2"><span id="elh_global_settings_instagram"><?php echo $global_settings->instagram->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_instagram" id="z_instagram" value="LIKE"></span></td>
		<td<?php echo $global_settings->instagram->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_instagram">
<input type="text" data-table="global_settings" data-field="x_instagram" name="x_instagram" id="x_instagram" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->instagram->getPlaceHolder()) ?>" value="<?php echo $global_settings->instagram->EditValue ?>"<?php echo $global_settings->instagram->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->youtube->Visible) { // youtube ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
	<div id="r_youtube" class="form-group">
		<label for="x_youtube" class="<?php echo $global_settings_search->LeftColumnClass ?>"><span id="elh_global_settings_youtube"><?php echo $global_settings->youtube->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_youtube" id="z_youtube" value="LIKE"></p>
		</label>
		<div class="<?php echo $global_settings_search->RightColumnClass ?>"><div<?php echo $global_settings->youtube->CellAttributes() ?>>
			<span id="el_global_settings_youtube">
<input type="text" data-table="global_settings" data-field="x_youtube" name="x_youtube" id="x_youtube" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->youtube->getPlaceHolder()) ?>" value="<?php echo $global_settings->youtube->EditValue ?>"<?php echo $global_settings->youtube->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_youtube">
		<td class="col-sm-2"><span id="elh_global_settings_youtube"><?php echo $global_settings->youtube->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_youtube" id="z_youtube" value="LIKE"></span></td>
		<td<?php echo $global_settings->youtube->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_global_settings_youtube">
<input type="text" data-table="global_settings" data-field="x_youtube" name="x_youtube" id="x_youtube" size="35" placeholder="<?php echo ew_HtmlEncode($global_settings->youtube->getPlaceHolder()) ?>" value="<?php echo $global_settings->youtube->EditValue ?>"<?php echo $global_settings->youtube->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$global_settings_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $global_settings_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$global_settings_search->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fglobal_settingssearch.Init();
</script>
<?php
$global_settings_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$global_settings_search->Page_Terminate();
?>
