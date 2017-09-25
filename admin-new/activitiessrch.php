<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "activitiesinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$activities_search = NULL; // Initialize page object first

class cactivities_search extends cactivities {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'activities';

	// Page object name
	var $PageObjName = 'activities_search';

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

		// Table object (activities)
		if (!isset($GLOBALS["activities"]) || get_class($GLOBALS["activities"]) == "cactivities") {
			$GLOBALS["activities"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["activities"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'activities', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("activitieslist.php"));
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
		$this->activity_id->SetVisibility();
		$this->activity_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->activity_name_ar->SetVisibility();
		$this->activity_name_en->SetVisibility();
		$this->activity_start_date->SetVisibility();
		$this->activity_end_date->SetVisibility();
		$this->activity_time_ar->SetVisibility();
		$this->activity_time_en->SetVisibility();
		$this->activity_description_ar->SetVisibility();
		$this->activity_description_en->SetVisibility();
		$this->activity_persons->SetVisibility();
		$this->activity_hours->SetVisibility();
		$this->activity_city->SetVisibility();
		$this->activity_location_ar->SetVisibility();
		$this->activity_location_en->SetVisibility();
		$this->activity_location_map->SetVisibility();
		$this->activity_image->SetVisibility();
		$this->activity_organizer_ar->SetVisibility();
		$this->activity_organizer_en->SetVisibility();
		$this->activity_category_ar->SetVisibility();
		$this->activity_category_en->SetVisibility();
		$this->activity_type->SetVisibility();
		$this->activity_gender_target->SetVisibility();
		$this->activity_terms_and_conditions_ar->SetVisibility();
		$this->activity_terms_and_conditions_en->SetVisibility();
		$this->activity_active->SetVisibility();
		$this->leader_username->SetVisibility();

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
		global $EW_EXPORT, $activities;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($activities);
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
					if ($pageName == "activitiesview.php")
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
						$sSrchStr = "activitieslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->activity_id); // activity_id
		$this->BuildSearchUrl($sSrchUrl, $this->activity_name_ar); // activity_name_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_name_en); // activity_name_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_start_date); // activity_start_date
		$this->BuildSearchUrl($sSrchUrl, $this->activity_end_date); // activity_end_date
		$this->BuildSearchUrl($sSrchUrl, $this->activity_time_ar); // activity_time_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_time_en); // activity_time_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_description_ar); // activity_description_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_description_en); // activity_description_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_persons); // activity_persons
		$this->BuildSearchUrl($sSrchUrl, $this->activity_hours); // activity_hours
		$this->BuildSearchUrl($sSrchUrl, $this->activity_city); // activity_city
		$this->BuildSearchUrl($sSrchUrl, $this->activity_location_ar); // activity_location_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_location_en); // activity_location_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_location_map); // activity_location_map
		$this->BuildSearchUrl($sSrchUrl, $this->activity_image); // activity_image
		$this->BuildSearchUrl($sSrchUrl, $this->activity_organizer_ar); // activity_organizer_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_organizer_en); // activity_organizer_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_category_ar); // activity_category_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_category_en); // activity_category_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_type); // activity_type
		$this->BuildSearchUrl($sSrchUrl, $this->activity_gender_target); // activity_gender_target
		$this->BuildSearchUrl($sSrchUrl, $this->activity_terms_and_conditions_ar); // activity_terms_and_conditions_ar
		$this->BuildSearchUrl($sSrchUrl, $this->activity_terms_and_conditions_en); // activity_terms_and_conditions_en
		$this->BuildSearchUrl($sSrchUrl, $this->activity_active); // activity_active
		$this->BuildSearchUrl($sSrchUrl, $this->leader_username); // leader_username
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
		// activity_id

		$this->activity_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_id");
		$this->activity_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_id");

		// activity_name_ar
		$this->activity_name_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_name_ar");
		$this->activity_name_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_name_ar");

		// activity_name_en
		$this->activity_name_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_name_en");
		$this->activity_name_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_name_en");

		// activity_start_date
		$this->activity_start_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_start_date");
		$this->activity_start_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_start_date");

		// activity_end_date
		$this->activity_end_date->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_end_date");
		$this->activity_end_date->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_end_date");

		// activity_time_ar
		$this->activity_time_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_time_ar");
		$this->activity_time_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_time_ar");

		// activity_time_en
		$this->activity_time_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_time_en");
		$this->activity_time_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_time_en");

		// activity_description_ar
		$this->activity_description_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_description_ar");
		$this->activity_description_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_description_ar");

		// activity_description_en
		$this->activity_description_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_description_en");
		$this->activity_description_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_description_en");

		// activity_persons
		$this->activity_persons->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_persons");
		$this->activity_persons->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_persons");

		// activity_hours
		$this->activity_hours->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_hours");
		$this->activity_hours->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_hours");

		// activity_city
		$this->activity_city->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_city");
		$this->activity_city->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_city");

		// activity_location_ar
		$this->activity_location_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_location_ar");
		$this->activity_location_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_location_ar");

		// activity_location_en
		$this->activity_location_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_location_en");
		$this->activity_location_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_location_en");

		// activity_location_map
		$this->activity_location_map->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_location_map");
		$this->activity_location_map->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_location_map");

		// activity_image
		$this->activity_image->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_image");
		$this->activity_image->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_image");

		// activity_organizer_ar
		$this->activity_organizer_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_organizer_ar");
		$this->activity_organizer_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_organizer_ar");

		// activity_organizer_en
		$this->activity_organizer_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_organizer_en");
		$this->activity_organizer_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_organizer_en");

		// activity_category_ar
		$this->activity_category_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_category_ar");
		$this->activity_category_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_category_ar");

		// activity_category_en
		$this->activity_category_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_category_en");
		$this->activity_category_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_category_en");

		// activity_type
		$this->activity_type->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_type");
		$this->activity_type->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_type");

		// activity_gender_target
		$this->activity_gender_target->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_gender_target");
		$this->activity_gender_target->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_gender_target");

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_terms_and_conditions_ar");
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_terms_and_conditions_ar");

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_terms_and_conditions_en");
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_terms_and_conditions_en");

		// activity_active
		$this->activity_active->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_active");
		$this->activity_active->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_active");

		// leader_username
		$this->leader_username->AdvancedSearch->SearchValue = $objForm->GetValue("x_leader_username");
		$this->leader_username->AdvancedSearch->SearchOperator = $objForm->GetValue("z_leader_username");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// activity_id
		// activity_name_ar
		// activity_name_en
		// activity_start_date
		// activity_end_date
		// activity_time_ar
		// activity_time_en
		// activity_description_ar
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// activity_id
			$this->activity_id->EditAttrs["class"] = "form-control";
			$this->activity_id->EditCustomAttributes = "";
			$this->activity_id->EditValue = ew_HtmlEncode($this->activity_id->AdvancedSearch->SearchValue);
			$this->activity_id->PlaceHolder = ew_RemoveHtml($this->activity_id->FldCaption());

			// activity_name_ar
			$this->activity_name_ar->EditAttrs["class"] = "form-control";
			$this->activity_name_ar->EditCustomAttributes = "";
			$this->activity_name_ar->EditValue = ew_HtmlEncode($this->activity_name_ar->AdvancedSearch->SearchValue);
			$this->activity_name_ar->PlaceHolder = ew_RemoveHtml($this->activity_name_ar->FldCaption());

			// activity_name_en
			$this->activity_name_en->EditAttrs["class"] = "form-control";
			$this->activity_name_en->EditCustomAttributes = "";
			$this->activity_name_en->EditValue = ew_HtmlEncode($this->activity_name_en->AdvancedSearch->SearchValue);
			$this->activity_name_en->PlaceHolder = ew_RemoveHtml($this->activity_name_en->FldCaption());

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

			// activity_time_ar
			$this->activity_time_ar->EditAttrs["class"] = "form-control";
			$this->activity_time_ar->EditCustomAttributes = "";
			$this->activity_time_ar->EditValue = ew_HtmlEncode($this->activity_time_ar->AdvancedSearch->SearchValue);
			$this->activity_time_ar->PlaceHolder = ew_RemoveHtml($this->activity_time_ar->FldCaption());

			// activity_time_en
			$this->activity_time_en->EditAttrs["class"] = "form-control";
			$this->activity_time_en->EditCustomAttributes = "";
			$this->activity_time_en->EditValue = ew_HtmlEncode($this->activity_time_en->AdvancedSearch->SearchValue);
			$this->activity_time_en->PlaceHolder = ew_RemoveHtml($this->activity_time_en->FldCaption());

			// activity_description_ar
			$this->activity_description_ar->EditAttrs["class"] = "form-control";
			$this->activity_description_ar->EditCustomAttributes = "";
			$this->activity_description_ar->EditValue = ew_HtmlEncode($this->activity_description_ar->AdvancedSearch->SearchValue);
			$this->activity_description_ar->PlaceHolder = ew_RemoveHtml($this->activity_description_ar->FldCaption());

			// activity_description_en
			$this->activity_description_en->EditAttrs["class"] = "form-control";
			$this->activity_description_en->EditCustomAttributes = "";
			$this->activity_description_en->EditValue = ew_HtmlEncode($this->activity_description_en->AdvancedSearch->SearchValue);
			$this->activity_description_en->PlaceHolder = ew_RemoveHtml($this->activity_description_en->FldCaption());

			// activity_persons
			$this->activity_persons->EditAttrs["class"] = "form-control";
			$this->activity_persons->EditCustomAttributes = "";
			$this->activity_persons->EditValue = ew_HtmlEncode($this->activity_persons->AdvancedSearch->SearchValue);
			$this->activity_persons->PlaceHolder = ew_RemoveHtml($this->activity_persons->FldCaption());

			// activity_hours
			$this->activity_hours->EditAttrs["class"] = "form-control";
			$this->activity_hours->EditCustomAttributes = "";
			$this->activity_hours->EditValue = ew_HtmlEncode($this->activity_hours->AdvancedSearch->SearchValue);
			$this->activity_hours->PlaceHolder = ew_RemoveHtml($this->activity_hours->FldCaption());

			// activity_city
			$this->activity_city->EditAttrs["class"] = "form-control";
			$this->activity_city->EditCustomAttributes = "";
			$this->activity_city->EditValue = $this->activity_city->Options(TRUE);

			// activity_location_ar
			$this->activity_location_ar->EditAttrs["class"] = "form-control";
			$this->activity_location_ar->EditCustomAttributes = "";
			$this->activity_location_ar->EditValue = ew_HtmlEncode($this->activity_location_ar->AdvancedSearch->SearchValue);
			$this->activity_location_ar->PlaceHolder = ew_RemoveHtml($this->activity_location_ar->FldCaption());

			// activity_location_en
			$this->activity_location_en->EditAttrs["class"] = "form-control";
			$this->activity_location_en->EditCustomAttributes = "";
			$this->activity_location_en->EditValue = ew_HtmlEncode($this->activity_location_en->AdvancedSearch->SearchValue);
			$this->activity_location_en->PlaceHolder = ew_RemoveHtml($this->activity_location_en->FldCaption());

			// activity_location_map
			$this->activity_location_map->EditAttrs["class"] = "form-control";
			$this->activity_location_map->EditCustomAttributes = "";
			$this->activity_location_map->EditValue = ew_HtmlEncode($this->activity_location_map->AdvancedSearch->SearchValue);
			$this->activity_location_map->PlaceHolder = ew_RemoveHtml($this->activity_location_map->FldCaption());

			// activity_image
			$this->activity_image->EditAttrs["class"] = "form-control";
			$this->activity_image->EditCustomAttributes = "";
			$this->activity_image->EditValue = ew_HtmlEncode($this->activity_image->AdvancedSearch->SearchValue);
			$this->activity_image->PlaceHolder = ew_RemoveHtml($this->activity_image->FldCaption());

			// activity_organizer_ar
			$this->activity_organizer_ar->EditAttrs["class"] = "form-control";
			$this->activity_organizer_ar->EditCustomAttributes = "";
			$this->activity_organizer_ar->EditValue = ew_HtmlEncode($this->activity_organizer_ar->AdvancedSearch->SearchValue);
			$this->activity_organizer_ar->PlaceHolder = ew_RemoveHtml($this->activity_organizer_ar->FldCaption());

			// activity_organizer_en
			$this->activity_organizer_en->EditAttrs["class"] = "form-control";
			$this->activity_organizer_en->EditCustomAttributes = "";
			$this->activity_organizer_en->EditValue = ew_HtmlEncode($this->activity_organizer_en->AdvancedSearch->SearchValue);
			$this->activity_organizer_en->PlaceHolder = ew_RemoveHtml($this->activity_organizer_en->FldCaption());

			// activity_category_ar
			$this->activity_category_ar->EditAttrs["class"] = "form-control";
			$this->activity_category_ar->EditCustomAttributes = "";
			$this->activity_category_ar->EditValue = ew_HtmlEncode($this->activity_category_ar->AdvancedSearch->SearchValue);
			$this->activity_category_ar->PlaceHolder = ew_RemoveHtml($this->activity_category_ar->FldCaption());

			// activity_category_en
			$this->activity_category_en->EditAttrs["class"] = "form-control";
			$this->activity_category_en->EditCustomAttributes = "";
			$this->activity_category_en->EditValue = ew_HtmlEncode($this->activity_category_en->AdvancedSearch->SearchValue);
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
			$this->activity_terms_and_conditions_ar->EditValue = ew_HtmlEncode($this->activity_terms_and_conditions_ar->AdvancedSearch->SearchValue);
			$this->activity_terms_and_conditions_ar->PlaceHolder = ew_RemoveHtml($this->activity_terms_and_conditions_ar->FldCaption());

			// activity_terms_and_conditions_en
			$this->activity_terms_and_conditions_en->EditAttrs["class"] = "form-control";
			$this->activity_terms_and_conditions_en->EditCustomAttributes = "";
			$this->activity_terms_and_conditions_en->EditValue = ew_HtmlEncode($this->activity_terms_and_conditions_en->AdvancedSearch->SearchValue);
			$this->activity_terms_and_conditions_en->PlaceHolder = ew_RemoveHtml($this->activity_terms_and_conditions_en->FldCaption());

			// activity_active
			$this->activity_active->EditCustomAttributes = "";
			$this->activity_active->EditValue = $this->activity_active->Options(FALSE);

			// leader_username
			$this->leader_username->EditAttrs["class"] = "form-control";
			$this->leader_username->EditCustomAttributes = "";
			$this->leader_username->EditValue = ew_HtmlEncode($this->leader_username->AdvancedSearch->SearchValue);
			if (strval($this->leader_username->AdvancedSearch->SearchValue) <> "") {
				$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->leader_username->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->leader_username->LookupFilters = array("dx1" => '`full_name_ar`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->leader_username, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->leader_username->EditValue = $this->leader_username->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->leader_username->EditValue = ew_HtmlEncode($this->leader_username->AdvancedSearch->SearchValue);
				}
			} else {
				$this->leader_username->EditValue = NULL;
			}
			$this->leader_username->PlaceHolder = ew_RemoveHtml($this->leader_username->FldCaption());
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
		if (!ew_CheckInteger($this->activity_id->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->activity_id->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->activity_start_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->activity_start_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->activity_end_date->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->activity_end_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->activity_persons->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->activity_persons->FldErrMsg());
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
		$this->activity_id->AdvancedSearch->Load();
		$this->activity_name_ar->AdvancedSearch->Load();
		$this->activity_name_en->AdvancedSearch->Load();
		$this->activity_start_date->AdvancedSearch->Load();
		$this->activity_end_date->AdvancedSearch->Load();
		$this->activity_time_ar->AdvancedSearch->Load();
		$this->activity_time_en->AdvancedSearch->Load();
		$this->activity_description_ar->AdvancedSearch->Load();
		$this->activity_description_en->AdvancedSearch->Load();
		$this->activity_persons->AdvancedSearch->Load();
		$this->activity_hours->AdvancedSearch->Load();
		$this->activity_city->AdvancedSearch->Load();
		$this->activity_location_ar->AdvancedSearch->Load();
		$this->activity_location_en->AdvancedSearch->Load();
		$this->activity_location_map->AdvancedSearch->Load();
		$this->activity_image->AdvancedSearch->Load();
		$this->activity_organizer_ar->AdvancedSearch->Load();
		$this->activity_organizer_en->AdvancedSearch->Load();
		$this->activity_category_ar->AdvancedSearch->Load();
		$this->activity_category_en->AdvancedSearch->Load();
		$this->activity_type->AdvancedSearch->Load();
		$this->activity_gender_target->AdvancedSearch->Load();
		$this->activity_terms_and_conditions_ar->AdvancedSearch->Load();
		$this->activity_terms_and_conditions_en->AdvancedSearch->Load();
		$this->activity_active->AdvancedSearch->Load();
		$this->leader_username->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("activitieslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_leader_username":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `user_id` AS `LinkFld`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "{filter}";
			$this->leader_username->LookupFilters = array("dx1" => '`full_name_ar`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`user_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->leader_username, $sWhereWrk); // Call Lookup Selecting
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
		case "x_leader_username":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld` FROM `users`";
			$sWhereWrk = "`full_name_ar` LIKE '{query_value}%'";
			$this->leader_username->LookupFilters = array("dx1" => '`full_name_ar`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->leader_username, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($activities_search)) $activities_search = new cactivities_search();

// Page init
$activities_search->Page_Init();

// Page main
$activities_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$activities_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($activities_search->IsModal) { ?>
var CurrentAdvancedSearchForm = factivitiessearch = new ew_Form("factivitiessearch", "search");
<?php } else { ?>
var CurrentForm = factivitiessearch = new ew_Form("factivitiessearch", "search");
<?php } ?>

// Form_CustomValidate event
factivitiessearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factivitiessearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factivitiessearch.Lists["x_activity_city"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiessearch.Lists["x_activity_city"].Options = <?php echo json_encode($activities_search->activity_city->Options()) ?>;
factivitiessearch.Lists["x_activity_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiessearch.Lists["x_activity_type"].Options = <?php echo json_encode($activities_search->activity_type->Options()) ?>;
factivitiessearch.Lists["x_activity_gender_target"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiessearch.Lists["x_activity_gender_target"].Options = <?php echo json_encode($activities_search->activity_gender_target->Options()) ?>;
factivitiessearch.Lists["x_activity_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiessearch.Lists["x_activity_active"].Options = <?php echo json_encode($activities_search->activity_active->Options()) ?>;
factivitiessearch.Lists["x_leader_username"] = {"LinkField":"x_user_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_full_name_ar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
factivitiessearch.Lists["x_leader_username"].Data = "<?php echo $activities_search->leader_username->LookupFilterQuery(FALSE, "search") ?>";
factivitiessearch.AutoSuggests["x_leader_username"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $activities_search->leader_username->LookupFilterQuery(TRUE, "search"))) ?>;

// Form object for search
// Validate function for search

factivitiessearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_activity_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($activities->activity_id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_activity_start_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($activities->activity_start_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_activity_end_date");
	if (elm && !ew_CheckDateDef(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($activities->activity_end_date->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_activity_persons");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($activities->activity_persons->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $activities_search->ShowPageHeader(); ?>
<?php
$activities_search->ShowMessage();
?>
<form name="factivitiessearch" id="factivitiessearch" class="<?php echo $activities_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($activities_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $activities_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="activities">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($activities_search->IsModal) ?>">
<?php if (!$activities_search->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_activitiessearch" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($activities->activity_id->Visible) { // activity_id ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_id" class="form-group">
		<label for="x_activity_id" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_id"><?php echo $activities->activity_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_id" id="z_activity_id" value="="></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_id->CellAttributes() ?>>
			<span id="el_activities_activity_id">
<input type="text" data-table="activities" data-field="x_activity_id" name="x_activity_id" id="x_activity_id" placeholder="<?php echo ew_HtmlEncode($activities->activity_id->getPlaceHolder()) ?>" value="<?php echo $activities->activity_id->EditValue ?>"<?php echo $activities->activity_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_id">
		<td class="col-sm-2"><span id="elh_activities_activity_id"><?php echo $activities->activity_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_id" id="z_activity_id" value="="></span></td>
		<td<?php echo $activities->activity_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_id">
<input type="text" data-table="activities" data-field="x_activity_id" name="x_activity_id" id="x_activity_id" placeholder="<?php echo ew_HtmlEncode($activities->activity_id->getPlaceHolder()) ?>" value="<?php echo $activities->activity_id->EditValue ?>"<?php echo $activities->activity_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_name_ar->Visible) { // activity_name_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_name_ar" class="form-group">
		<label for="x_activity_name_ar" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_name_ar"><?php echo $activities->activity_name_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_name_ar" id="z_activity_name_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_name_ar->CellAttributes() ?>>
			<span id="el_activities_activity_name_ar">
<input type="text" data-table="activities" data-field="x_activity_name_ar" name="x_activity_name_ar" id="x_activity_name_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_ar->EditValue ?>"<?php echo $activities->activity_name_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_name_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_name_ar"><?php echo $activities->activity_name_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_name_ar" id="z_activity_name_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_name_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_name_ar">
<input type="text" data-table="activities" data-field="x_activity_name_ar" name="x_activity_name_ar" id="x_activity_name_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_ar->EditValue ?>"<?php echo $activities->activity_name_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_name_en->Visible) { // activity_name_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_name_en" class="form-group">
		<label for="x_activity_name_en" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_name_en"><?php echo $activities->activity_name_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_name_en" id="z_activity_name_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_name_en->CellAttributes() ?>>
			<span id="el_activities_activity_name_en">
<input type="text" data-table="activities" data-field="x_activity_name_en" name="x_activity_name_en" id="x_activity_name_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_en->EditValue ?>"<?php echo $activities->activity_name_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_name_en">
		<td class="col-sm-2"><span id="elh_activities_activity_name_en"><?php echo $activities->activity_name_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_name_en" id="z_activity_name_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_name_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_name_en">
<input type="text" data-table="activities" data-field="x_activity_name_en" name="x_activity_name_en" id="x_activity_name_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_en->EditValue ?>"<?php echo $activities->activity_name_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_start_date->Visible) { // activity_start_date ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_start_date" class="form-group">
		<label for="x_activity_start_date" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_start_date"><?php echo $activities->activity_start_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_start_date" id="z_activity_start_date" value="="></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_start_date->CellAttributes() ?>>
			<span id="el_activities_activity_start_date">
<input type="text" data-table="activities" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_start_date->EditValue ?>"<?php echo $activities->activity_start_date->EditAttributes() ?>>
<?php if (!$activities->activity_start_date->ReadOnly && !$activities->activity_start_date->Disabled && !isset($activities->activity_start_date->EditAttrs["readonly"]) && !isset($activities->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiessearch", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_start_date">
		<td class="col-sm-2"><span id="elh_activities_activity_start_date"><?php echo $activities->activity_start_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_start_date" id="z_activity_start_date" value="="></span></td>
		<td<?php echo $activities->activity_start_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_start_date">
<input type="text" data-table="activities" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_start_date->EditValue ?>"<?php echo $activities->activity_start_date->EditAttributes() ?>>
<?php if (!$activities->activity_start_date->ReadOnly && !$activities->activity_start_date->Disabled && !isset($activities->activity_start_date->EditAttrs["readonly"]) && !isset($activities->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiessearch", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_end_date->Visible) { // activity_end_date ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_end_date" class="form-group">
		<label for="x_activity_end_date" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_end_date"><?php echo $activities->activity_end_date->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_end_date" id="z_activity_end_date" value="="></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_end_date->CellAttributes() ?>>
			<span id="el_activities_activity_end_date">
<input type="text" data-table="activities" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_end_date->EditValue ?>"<?php echo $activities->activity_end_date->EditAttributes() ?>>
<?php if (!$activities->activity_end_date->ReadOnly && !$activities->activity_end_date->Disabled && !isset($activities->activity_end_date->EditAttrs["readonly"]) && !isset($activities->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiessearch", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_end_date">
		<td class="col-sm-2"><span id="elh_activities_activity_end_date"><?php echo $activities->activity_end_date->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_end_date" id="z_activity_end_date" value="="></span></td>
		<td<?php echo $activities->activity_end_date->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_end_date">
<input type="text" data-table="activities" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_end_date->EditValue ?>"<?php echo $activities->activity_end_date->EditAttributes() ?>>
<?php if (!$activities->activity_end_date->ReadOnly && !$activities->activity_end_date->Disabled && !isset($activities->activity_end_date->EditAttrs["readonly"]) && !isset($activities->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiessearch", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_time_ar->Visible) { // activity_time_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_time_ar" class="form-group">
		<label for="x_activity_time_ar" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_time_ar"><?php echo $activities->activity_time_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_time_ar" id="z_activity_time_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_time_ar->CellAttributes() ?>>
			<span id="el_activities_activity_time_ar">
<input type="text" data-table="activities" data-field="x_activity_time_ar" name="x_activity_time_ar" id="x_activity_time_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_ar->EditValue ?>"<?php echo $activities->activity_time_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_time_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_time_ar"><?php echo $activities->activity_time_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_time_ar" id="z_activity_time_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_time_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_time_ar">
<input type="text" data-table="activities" data-field="x_activity_time_ar" name="x_activity_time_ar" id="x_activity_time_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_ar->EditValue ?>"<?php echo $activities->activity_time_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_time_en->Visible) { // activity_time_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_time_en" class="form-group">
		<label for="x_activity_time_en" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_time_en"><?php echo $activities->activity_time_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_time_en" id="z_activity_time_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_time_en->CellAttributes() ?>>
			<span id="el_activities_activity_time_en">
<input type="text" data-table="activities" data-field="x_activity_time_en" name="x_activity_time_en" id="x_activity_time_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_en->EditValue ?>"<?php echo $activities->activity_time_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_time_en">
		<td class="col-sm-2"><span id="elh_activities_activity_time_en"><?php echo $activities->activity_time_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_time_en" id="z_activity_time_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_time_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_time_en">
<input type="text" data-table="activities" data-field="x_activity_time_en" name="x_activity_time_en" id="x_activity_time_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_en->EditValue ?>"<?php echo $activities->activity_time_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_description_ar->Visible) { // activity_description_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_description_ar" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_description_ar"><?php echo $activities->activity_description_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_description_ar" id="z_activity_description_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_description_ar->CellAttributes() ?>>
			<span id="el_activities_activity_description_ar">
<input type="text" data-table="activities" data-field="x_activity_description_ar" name="x_activity_description_ar" id="x_activity_description_ar" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_description_ar->EditValue ?>"<?php echo $activities->activity_description_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_description_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_description_ar"><?php echo $activities->activity_description_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_description_ar" id="z_activity_description_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_description_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_description_ar">
<input type="text" data-table="activities" data-field="x_activity_description_ar" name="x_activity_description_ar" id="x_activity_description_ar" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_description_ar->EditValue ?>"<?php echo $activities->activity_description_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_description_en->Visible) { // activity_description_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_description_en" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_description_en"><?php echo $activities->activity_description_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_description_en" id="z_activity_description_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_description_en->CellAttributes() ?>>
			<span id="el_activities_activity_description_en">
<input type="text" data-table="activities" data-field="x_activity_description_en" name="x_activity_description_en" id="x_activity_description_en" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_description_en->EditValue ?>"<?php echo $activities->activity_description_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_description_en">
		<td class="col-sm-2"><span id="elh_activities_activity_description_en"><?php echo $activities->activity_description_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_description_en" id="z_activity_description_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_description_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_description_en">
<input type="text" data-table="activities" data-field="x_activity_description_en" name="x_activity_description_en" id="x_activity_description_en" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_description_en->EditValue ?>"<?php echo $activities->activity_description_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_persons->Visible) { // activity_persons ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_persons" class="form-group">
		<label for="x_activity_persons" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_persons"><?php echo $activities->activity_persons->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_persons" id="z_activity_persons" value="="></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_persons->CellAttributes() ?>>
			<span id="el_activities_activity_persons">
<input type="text" data-table="activities" data-field="x_activity_persons" name="x_activity_persons" id="x_activity_persons" size="30" placeholder="<?php echo ew_HtmlEncode($activities->activity_persons->getPlaceHolder()) ?>" value="<?php echo $activities->activity_persons->EditValue ?>"<?php echo $activities->activity_persons->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_persons">
		<td class="col-sm-2"><span id="elh_activities_activity_persons"><?php echo $activities->activity_persons->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_persons" id="z_activity_persons" value="="></span></td>
		<td<?php echo $activities->activity_persons->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_persons">
<input type="text" data-table="activities" data-field="x_activity_persons" name="x_activity_persons" id="x_activity_persons" size="30" placeholder="<?php echo ew_HtmlEncode($activities->activity_persons->getPlaceHolder()) ?>" value="<?php echo $activities->activity_persons->EditValue ?>"<?php echo $activities->activity_persons->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_hours->Visible) { // activity_hours ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_hours" class="form-group">
		<label for="x_activity_hours" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_hours"><?php echo $activities->activity_hours->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_hours" id="z_activity_hours" value="="></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_hours->CellAttributes() ?>>
			<span id="el_activities_activity_hours">
<input type="text" data-table="activities" data-field="x_activity_hours" name="x_activity_hours" id="x_activity_hours" placeholder="<?php echo ew_HtmlEncode($activities->activity_hours->getPlaceHolder()) ?>" value="<?php echo $activities->activity_hours->EditValue ?>"<?php echo $activities->activity_hours->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_hours">
		<td class="col-sm-2"><span id="elh_activities_activity_hours"><?php echo $activities->activity_hours->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_hours" id="z_activity_hours" value="="></span></td>
		<td<?php echo $activities->activity_hours->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_hours">
<input type="text" data-table="activities" data-field="x_activity_hours" name="x_activity_hours" id="x_activity_hours" placeholder="<?php echo ew_HtmlEncode($activities->activity_hours->getPlaceHolder()) ?>" value="<?php echo $activities->activity_hours->EditValue ?>"<?php echo $activities->activity_hours->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_city->Visible) { // activity_city ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_city" class="form-group">
		<label for="x_activity_city" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_city"><?php echo $activities->activity_city->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_city" id="z_activity_city" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_city->CellAttributes() ?>>
			<span id="el_activities_activity_city">
<select data-table="activities" data-field="x_activity_city" data-value-separator="<?php echo $activities->activity_city->DisplayValueSeparatorAttribute() ?>" id="x_activity_city" name="x_activity_city"<?php echo $activities->activity_city->EditAttributes() ?>>
<?php echo $activities->activity_city->SelectOptionListHtml("x_activity_city") ?>
</select>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_city">
		<td class="col-sm-2"><span id="elh_activities_activity_city"><?php echo $activities->activity_city->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_city" id="z_activity_city" value="LIKE"></span></td>
		<td<?php echo $activities->activity_city->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_city">
<select data-table="activities" data-field="x_activity_city" data-value-separator="<?php echo $activities->activity_city->DisplayValueSeparatorAttribute() ?>" id="x_activity_city" name="x_activity_city"<?php echo $activities->activity_city->EditAttributes() ?>>
<?php echo $activities->activity_city->SelectOptionListHtml("x_activity_city") ?>
</select>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_location_ar->Visible) { // activity_location_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_location_ar" class="form-group">
		<label for="x_activity_location_ar" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_location_ar"><?php echo $activities->activity_location_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_location_ar" id="z_activity_location_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_location_ar->CellAttributes() ?>>
			<span id="el_activities_activity_location_ar">
<input type="text" data-table="activities" data-field="x_activity_location_ar" name="x_activity_location_ar" id="x_activity_location_ar" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_ar->EditValue ?>"<?php echo $activities->activity_location_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_location_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_location_ar"><?php echo $activities->activity_location_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_location_ar" id="z_activity_location_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_location_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_location_ar">
<input type="text" data-table="activities" data-field="x_activity_location_ar" name="x_activity_location_ar" id="x_activity_location_ar" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_ar->EditValue ?>"<?php echo $activities->activity_location_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_location_en->Visible) { // activity_location_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_location_en" class="form-group">
		<label for="x_activity_location_en" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_location_en"><?php echo $activities->activity_location_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_location_en" id="z_activity_location_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_location_en->CellAttributes() ?>>
			<span id="el_activities_activity_location_en">
<input type="text" data-table="activities" data-field="x_activity_location_en" name="x_activity_location_en" id="x_activity_location_en" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_en->EditValue ?>"<?php echo $activities->activity_location_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_location_en">
		<td class="col-sm-2"><span id="elh_activities_activity_location_en"><?php echo $activities->activity_location_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_location_en" id="z_activity_location_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_location_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_location_en">
<input type="text" data-table="activities" data-field="x_activity_location_en" name="x_activity_location_en" id="x_activity_location_en" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_en->EditValue ?>"<?php echo $activities->activity_location_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_location_map->Visible) { // activity_location_map ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_location_map" class="form-group">
		<label for="x_activity_location_map" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_location_map"><?php echo $activities->activity_location_map->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_location_map" id="z_activity_location_map" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_location_map->CellAttributes() ?>>
			<span id="el_activities_activity_location_map">
<input type="text" data-table="activities" data-field="x_activity_location_map" name="x_activity_location_map" id="x_activity_location_map" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_map->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_map->EditValue ?>"<?php echo $activities->activity_location_map->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_location_map">
		<td class="col-sm-2"><span id="elh_activities_activity_location_map"><?php echo $activities->activity_location_map->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_location_map" id="z_activity_location_map" value="LIKE"></span></td>
		<td<?php echo $activities->activity_location_map->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_location_map">
<input type="text" data-table="activities" data-field="x_activity_location_map" name="x_activity_location_map" id="x_activity_location_map" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_map->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_map->EditValue ?>"<?php echo $activities->activity_location_map->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_image->Visible) { // activity_image ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_image" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_image"><?php echo $activities->activity_image->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_image" id="z_activity_image" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_image->CellAttributes() ?>>
			<span id="el_activities_activity_image">
<input type="text" data-table="activities" data-field="x_activity_image" name="x_activity_image" id="x_activity_image" placeholder="<?php echo ew_HtmlEncode($activities->activity_image->getPlaceHolder()) ?>" value="<?php echo $activities->activity_image->EditValue ?>"<?php echo $activities->activity_image->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_image">
		<td class="col-sm-2"><span id="elh_activities_activity_image"><?php echo $activities->activity_image->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_image" id="z_activity_image" value="LIKE"></span></td>
		<td<?php echo $activities->activity_image->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_image">
<input type="text" data-table="activities" data-field="x_activity_image" name="x_activity_image" id="x_activity_image" placeholder="<?php echo ew_HtmlEncode($activities->activity_image->getPlaceHolder()) ?>" value="<?php echo $activities->activity_image->EditValue ?>"<?php echo $activities->activity_image->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_organizer_ar->Visible) { // activity_organizer_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_organizer_ar" class="form-group">
		<label for="x_activity_organizer_ar" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_organizer_ar"><?php echo $activities->activity_organizer_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_organizer_ar" id="z_activity_organizer_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_organizer_ar->CellAttributes() ?>>
			<span id="el_activities_activity_organizer_ar">
<input type="text" data-table="activities" data-field="x_activity_organizer_ar" name="x_activity_organizer_ar" id="x_activity_organizer_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_ar->EditValue ?>"<?php echo $activities->activity_organizer_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_organizer_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_organizer_ar"><?php echo $activities->activity_organizer_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_organizer_ar" id="z_activity_organizer_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_organizer_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_organizer_ar">
<input type="text" data-table="activities" data-field="x_activity_organizer_ar" name="x_activity_organizer_ar" id="x_activity_organizer_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_ar->EditValue ?>"<?php echo $activities->activity_organizer_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_organizer_en->Visible) { // activity_organizer_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_organizer_en" class="form-group">
		<label for="x_activity_organizer_en" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_organizer_en"><?php echo $activities->activity_organizer_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_organizer_en" id="z_activity_organizer_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_organizer_en->CellAttributes() ?>>
			<span id="el_activities_activity_organizer_en">
<input type="text" data-table="activities" data-field="x_activity_organizer_en" name="x_activity_organizer_en" id="x_activity_organizer_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_en->EditValue ?>"<?php echo $activities->activity_organizer_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_organizer_en">
		<td class="col-sm-2"><span id="elh_activities_activity_organizer_en"><?php echo $activities->activity_organizer_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_organizer_en" id="z_activity_organizer_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_organizer_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_organizer_en">
<input type="text" data-table="activities" data-field="x_activity_organizer_en" name="x_activity_organizer_en" id="x_activity_organizer_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_en->EditValue ?>"<?php echo $activities->activity_organizer_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_category_ar->Visible) { // activity_category_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_category_ar" class="form-group">
		<label for="x_activity_category_ar" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_category_ar"><?php echo $activities->activity_category_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_category_ar" id="z_activity_category_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_category_ar->CellAttributes() ?>>
			<span id="el_activities_activity_category_ar">
<input type="text" data-table="activities" data-field="x_activity_category_ar" name="x_activity_category_ar" id="x_activity_category_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_ar->EditValue ?>"<?php echo $activities->activity_category_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_category_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_category_ar"><?php echo $activities->activity_category_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_category_ar" id="z_activity_category_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_category_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_category_ar">
<input type="text" data-table="activities" data-field="x_activity_category_ar" name="x_activity_category_ar" id="x_activity_category_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_ar->EditValue ?>"<?php echo $activities->activity_category_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_category_en->Visible) { // activity_category_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_category_en" class="form-group">
		<label for="x_activity_category_en" class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_category_en"><?php echo $activities->activity_category_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_category_en" id="z_activity_category_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_category_en->CellAttributes() ?>>
			<span id="el_activities_activity_category_en">
<input type="text" data-table="activities" data-field="x_activity_category_en" name="x_activity_category_en" id="x_activity_category_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_en->EditValue ?>"<?php echo $activities->activity_category_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_category_en">
		<td class="col-sm-2"><span id="elh_activities_activity_category_en"><?php echo $activities->activity_category_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_category_en" id="z_activity_category_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_category_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_category_en">
<input type="text" data-table="activities" data-field="x_activity_category_en" name="x_activity_category_en" id="x_activity_category_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_en->EditValue ?>"<?php echo $activities->activity_category_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_type->Visible) { // activity_type ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_type" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_type"><?php echo $activities->activity_type->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_type" id="z_activity_type" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_type->CellAttributes() ?>>
			<span id="el_activities_activity_type">
<div id="tp_x_activity_type" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_type" data-value-separator="<?php echo $activities->activity_type->DisplayValueSeparatorAttribute() ?>" name="x_activity_type" id="x_activity_type" value="{value}"<?php echo $activities->activity_type->EditAttributes() ?>></div>
<div id="dsl_x_activity_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_type->RadioButtonListHtml(FALSE, "x_activity_type") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_type">
		<td class="col-sm-2"><span id="elh_activities_activity_type"><?php echo $activities->activity_type->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_type" id="z_activity_type" value="LIKE"></span></td>
		<td<?php echo $activities->activity_type->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_type">
<div id="tp_x_activity_type" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_type" data-value-separator="<?php echo $activities->activity_type->DisplayValueSeparatorAttribute() ?>" name="x_activity_type" id="x_activity_type" value="{value}"<?php echo $activities->activity_type->EditAttributes() ?>></div>
<div id="dsl_x_activity_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_type->RadioButtonListHtml(FALSE, "x_activity_type") ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_gender_target->Visible) { // activity_gender_target ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_gender_target" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_gender_target"><?php echo $activities->activity_gender_target->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_gender_target" id="z_activity_gender_target" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_gender_target->CellAttributes() ?>>
			<span id="el_activities_activity_gender_target">
<div id="tp_x_activity_gender_target" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_gender_target" data-value-separator="<?php echo $activities->activity_gender_target->DisplayValueSeparatorAttribute() ?>" name="x_activity_gender_target" id="x_activity_gender_target" value="{value}"<?php echo $activities->activity_gender_target->EditAttributes() ?>></div>
<div id="dsl_x_activity_gender_target" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_gender_target->RadioButtonListHtml(FALSE, "x_activity_gender_target") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_gender_target">
		<td class="col-sm-2"><span id="elh_activities_activity_gender_target"><?php echo $activities->activity_gender_target->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_gender_target" id="z_activity_gender_target" value="LIKE"></span></td>
		<td<?php echo $activities->activity_gender_target->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_gender_target">
<div id="tp_x_activity_gender_target" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_gender_target" data-value-separator="<?php echo $activities->activity_gender_target->DisplayValueSeparatorAttribute() ?>" name="x_activity_gender_target" id="x_activity_gender_target" value="{value}"<?php echo $activities->activity_gender_target->EditAttributes() ?>></div>
<div id="dsl_x_activity_gender_target" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_gender_target->RadioButtonListHtml(FALSE, "x_activity_gender_target") ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_terms_and_conditions_ar->Visible) { // activity_terms_and_conditions_ar ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_terms_and_conditions_ar" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_terms_and_conditions_ar"><?php echo $activities->activity_terms_and_conditions_ar->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_terms_and_conditions_ar" id="z_activity_terms_and_conditions_ar" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_terms_and_conditions_ar->CellAttributes() ?>>
			<span id="el_activities_activity_terms_and_conditions_ar">
<input type="text" data-table="activities" data-field="x_activity_terms_and_conditions_ar" name="x_activity_terms_and_conditions_ar" id="x_activity_terms_and_conditions_ar" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_terms_and_conditions_ar->EditValue ?>"<?php echo $activities->activity_terms_and_conditions_ar->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_terms_and_conditions_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_terms_and_conditions_ar"><?php echo $activities->activity_terms_and_conditions_ar->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_terms_and_conditions_ar" id="z_activity_terms_and_conditions_ar" value="LIKE"></span></td>
		<td<?php echo $activities->activity_terms_and_conditions_ar->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_terms_and_conditions_ar">
<input type="text" data-table="activities" data-field="x_activity_terms_and_conditions_ar" name="x_activity_terms_and_conditions_ar" id="x_activity_terms_and_conditions_ar" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_terms_and_conditions_ar->EditValue ?>"<?php echo $activities->activity_terms_and_conditions_ar->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_terms_and_conditions_en->Visible) { // activity_terms_and_conditions_en ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_terms_and_conditions_en" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_terms_and_conditions_en"><?php echo $activities->activity_terms_and_conditions_en->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_terms_and_conditions_en" id="z_activity_terms_and_conditions_en" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_terms_and_conditions_en->CellAttributes() ?>>
			<span id="el_activities_activity_terms_and_conditions_en">
<input type="text" data-table="activities" data-field="x_activity_terms_and_conditions_en" name="x_activity_terms_and_conditions_en" id="x_activity_terms_and_conditions_en" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_terms_and_conditions_en->EditValue ?>"<?php echo $activities->activity_terms_and_conditions_en->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_terms_and_conditions_en">
		<td class="col-sm-2"><span id="elh_activities_activity_terms_and_conditions_en"><?php echo $activities->activity_terms_and_conditions_en->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_activity_terms_and_conditions_en" id="z_activity_terms_and_conditions_en" value="LIKE"></span></td>
		<td<?php echo $activities->activity_terms_and_conditions_en->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_terms_and_conditions_en">
<input type="text" data-table="activities" data-field="x_activity_terms_and_conditions_en" name="x_activity_terms_and_conditions_en" id="x_activity_terms_and_conditions_en" size="35" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_terms_and_conditions_en->EditValue ?>"<?php echo $activities->activity_terms_and_conditions_en->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_active->Visible) { // activity_active ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_activity_active" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_activity_active"><?php echo $activities->activity_active->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_active" id="z_activity_active" value="="></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->activity_active->CellAttributes() ?>>
			<span id="el_activities_activity_active">
<div id="tp_x_activity_active" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_active" data-value-separator="<?php echo $activities->activity_active->DisplayValueSeparatorAttribute() ?>" name="x_activity_active" id="x_activity_active" value="{value}"<?php echo $activities->activity_active->EditAttributes() ?>></div>
<div id="dsl_x_activity_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_active->RadioButtonListHtml(FALSE, "x_activity_active") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_active">
		<td class="col-sm-2"><span id="elh_activities_activity_active"><?php echo $activities->activity_active->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_active" id="z_activity_active" value="="></span></td>
		<td<?php echo $activities->activity_active->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_activity_active">
<div id="tp_x_activity_active" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_active" data-value-separator="<?php echo $activities->activity_active->DisplayValueSeparatorAttribute() ?>" name="x_activity_active" id="x_activity_active" value="{value}"<?php echo $activities->activity_active->EditAttributes() ?>></div>
<div id="dsl_x_activity_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_active->RadioButtonListHtml(FALSE, "x_activity_active") ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->leader_username->Visible) { // leader_username ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
	<div id="r_leader_username" class="form-group">
		<label class="<?php echo $activities_search->LeftColumnClass ?>"><span id="elh_activities_leader_username"><?php echo $activities->leader_username->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_leader_username" id="z_leader_username" value="LIKE"></p>
		</label>
		<div class="<?php echo $activities_search->RightColumnClass ?>"><div<?php echo $activities->leader_username->CellAttributes() ?>>
			<span id="el_activities_leader_username">
<?php
$wrkonchange = trim(" " . @$activities->leader_username->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$activities->leader_username->EditAttrs["onchange"] = "";
?>
<span id="as_x_leader_username" style="white-space: nowrap; z-index: 8740">
	<input type="text" name="sv_x_leader_username" id="sv_x_leader_username" value="<?php echo $activities->leader_username->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>"<?php echo $activities->leader_username->EditAttributes() ?>>
</span>
<input type="hidden" data-table="activities" data-field="x_leader_username" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $activities->leader_username->DisplayValueSeparatorAttribute() ?>" name="x_leader_username" id="x_leader_username" value="<?php echo ew_HtmlEncode($activities->leader_username->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
factivitiessearch.CreateAutoSuggest({"id":"x_leader_username","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($activities->leader_username->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_leader_username',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_leader_username">
		<td class="col-sm-2"><span id="elh_activities_leader_username"><?php echo $activities->leader_username->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_leader_username" id="z_leader_username" value="LIKE"></span></td>
		<td<?php echo $activities->leader_username->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_activities_leader_username">
<?php
$wrkonchange = trim(" " . @$activities->leader_username->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$activities->leader_username->EditAttrs["onchange"] = "";
?>
<span id="as_x_leader_username" style="white-space: nowrap; z-index: 8740">
	<input type="text" name="sv_x_leader_username" id="sv_x_leader_username" value="<?php echo $activities->leader_username->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>"<?php echo $activities->leader_username->EditAttributes() ?>>
</span>
<input type="hidden" data-table="activities" data-field="x_leader_username" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $activities->leader_username->DisplayValueSeparatorAttribute() ?>" name="x_leader_username" id="x_leader_username" value="<?php echo ew_HtmlEncode($activities->leader_username->AdvancedSearch->SearchValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
factivitiessearch.CreateAutoSuggest({"id":"x_leader_username","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($activities->leader_username->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_leader_username',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$activities_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $activities_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$activities_search->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
factivitiessearch.Init();
</script>
<?php
$activities_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$activities_search->Page_Terminate();
?>
