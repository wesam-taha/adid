<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "registered_usersinfo.php" ?>
<?php include_once "activitiesinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$registered_users_search = NULL; // Initialize page object first

class cregistered_users_search extends cregistered_users {

	// Page ID
	var $PageID = 'search';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'registered_users';

	// Page object name
	var $PageObjName = 'registered_users_search';

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

		// Table object (registered_users)
		if (!isset($GLOBALS["registered_users"]) || get_class($GLOBALS["registered_users"]) == "cregistered_users") {
			$GLOBALS["registered_users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["registered_users"];
		}

		// Table object (activities)
		if (!isset($GLOBALS['activities'])) $GLOBALS['activities'] = new cactivities();

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'search', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'registered_users', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("registered_userslist.php"));
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
		$this->activity_id->SetVisibility();
		$this->user_id->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->evaluation_rate->SetVisibility();

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
		global $EW_EXPORT, $registered_users;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($registered_users);
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
					if ($pageName == "registered_usersview.php")
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
						$sSrchStr = "registered_userslist.php" . "?" . $sSrchStr;
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
		$this->BuildSearchUrl($sSrchUrl, $this->activity_id); // activity_id
		$this->BuildSearchUrl($sSrchUrl, $this->user_id); // user_id
		$this->BuildSearchUrl($sSrchUrl, $this->admin_approval); // admin_approval
		$this->BuildSearchUrl($sSrchUrl, $this->admin_comment); // admin_comment
		$this->BuildSearchUrl($sSrchUrl, $this->evaluation_rate); // evaluation_rate
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

		// activity_id
		$this->activity_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_activity_id");
		$this->activity_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_activity_id");

		// user_id
		$this->user_id->AdvancedSearch->SearchValue = $objForm->GetValue("x_user_id");
		$this->user_id->AdvancedSearch->SearchOperator = $objForm->GetValue("z_user_id");

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_approval");
		$this->admin_approval->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_approval");

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = $objForm->GetValue("x_admin_comment");
		$this->admin_comment->AdvancedSearch->SearchOperator = $objForm->GetValue("z_admin_comment");

		// evaluation_rate
		$this->evaluation_rate->AdvancedSearch->SearchValue = $objForm->GetValue("x_evaluation_rate");
		$this->evaluation_rate->AdvancedSearch->SearchOperator = $objForm->GetValue("z_evaluation_rate");
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// activity_id
		// user_id
		// admin_approval
		// admin_comment
		// evaluation_rate

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// activity_id
		if (strval($this->activity_id->CurrentValue) <> "") {
			$sFilterWrk = "`activity_id`" . ew_SearchString("=", $this->activity_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `activity_id`, `activity_name_ar` AS `DispFld`, `activity_start_date` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `activities`";
		$sWhereWrk = "";
		$this->activity_id->LookupFilters = array("dx1" => '`activity_name_ar`', "df2" => "0", "dx2" => ew_CastDateFieldForLike('`activity_start_date`', 0, "DB"));
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->activity_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = ew_FormatDateTime($rswrk->fields('Disp2Fld'), 0);
				$this->activity_id->ViewValue = $this->activity_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->activity_id->ViewValue = $this->activity_id->CurrentValue;
			}
		} else {
			$this->activity_id->ViewValue = NULL;
		}
		$this->activity_id->ViewCustomAttributes = "";

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

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

		// evaluation_rate
		$this->evaluation_rate->ViewValue = $this->evaluation_rate->CurrentValue;
		$this->evaluation_rate->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// activity_id
			$this->activity_id->LinkCustomAttributes = "";
			$this->activity_id->HrefValue = "";
			$this->activity_id->TooltipValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";

			// evaluation_rate
			$this->evaluation_rate->LinkCustomAttributes = "";
			$this->evaluation_rate->HrefValue = "";
			$this->evaluation_rate->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// activity_id
			$this->activity_id->EditCustomAttributes = "";
			if (trim(strval($this->activity_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`activity_id`" . ew_SearchString("=", $this->activity_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `activity_id`, `activity_name_ar` AS `DispFld`, `activity_start_date` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `activities`";
			$sWhereWrk = "";
			$this->activity_id->LookupFilters = array("dx1" => '`activity_name_ar`', "df2" => "0", "dx2" => ew_CastDateFieldForLike('`activity_start_date`', 0, "DB"));
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->activity_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode(ew_FormatDateTime($rswrk->fields('Disp2Fld'), 0));
				$this->activity_id->AdvancedSearch->ViewValue = $this->activity_id->DisplayValue($arwrk);
			} else {
				$this->activity_id->AdvancedSearch->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][2] = ew_FormatDateTime($arwrk[$rowcntwrk][2], 0);
			}
			$this->activity_id->EditValue = $arwrk;

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->AdvancedSearch->SearchValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->AdvancedSearch->SearchValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// evaluation_rate
			$this->evaluation_rate->EditAttrs["class"] = "form-control";
			$this->evaluation_rate->EditCustomAttributes = "";
			$this->evaluation_rate->EditValue = ew_HtmlEncode($this->evaluation_rate->AdvancedSearch->SearchValue);
			$this->evaluation_rate->PlaceHolder = ew_RemoveHtml($this->evaluation_rate->FldCaption());
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
		if (!ew_CheckInteger($this->evaluation_rate->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->evaluation_rate->FldErrMsg());
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
		$this->activity_id->AdvancedSearch->Load();
		$this->user_id->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->evaluation_rate->AdvancedSearch->Load();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("registered_userslist.php"), "", $this->TableVar, TRUE);
		$PageId = "search";
		$Breadcrumb->Add("search", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_activity_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `activity_id` AS `LinkFld`, `activity_name_ar` AS `DispFld`, `activity_start_date` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `activities`";
			$sWhereWrk = "{filter}";
			$this->activity_id->LookupFilters = array("dx1" => '`activity_name_ar`', "df2" => "0", "dx2" => ew_CastDateFieldForLike('`activity_start_date`', 0, "DB"));
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`activity_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->activity_id, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($registered_users_search)) $registered_users_search = new cregistered_users_search();

// Page init
$registered_users_search->Page_Init();

// Page main
$registered_users_search->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$registered_users_search->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "search";
<?php if ($registered_users_search->IsModal) { ?>
var CurrentAdvancedSearchForm = fregistered_userssearch = new ew_Form("fregistered_userssearch", "search");
<?php } else { ?>
var CurrentForm = fregistered_userssearch = new ew_Form("fregistered_userssearch", "search");
<?php } ?>

// Form_CustomValidate event
fregistered_userssearch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fregistered_userssearch.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fregistered_userssearch.Lists["x_activity_id"] = {"LinkField":"x_activity_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_activity_name_ar","x_activity_start_date","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"activities"};
fregistered_userssearch.Lists["x_activity_id"].Data = "<?php echo $registered_users_search->activity_id->LookupFilterQuery(FALSE, "search") ?>";
fregistered_userssearch.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fregistered_userssearch.Lists["x_admin_approval"].Options = <?php echo json_encode($registered_users_search->admin_approval->Options()) ?>;

// Form object for search
// Validate function for search

fregistered_userssearch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";
	elm = this.GetElements("x" + infix + "_id");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($registered_users->id->FldErrMsg()) ?>");
	elm = this.GetElements("x" + infix + "_evaluation_rate");
	if (elm && !ew_CheckInteger(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($registered_users->evaluation_rate->FldErrMsg()) ?>");

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $registered_users_search->ShowPageHeader(); ?>
<?php
$registered_users_search->ShowMessage();
?>
<form name="fregistered_userssearch" id="fregistered_userssearch" class="<?php echo $registered_users_search->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($registered_users_search->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $registered_users_search->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="registered_users">
<input type="hidden" name="a_search" id="a_search" value="S">
<input type="hidden" name="modal" value="<?php echo intval($registered_users_search->IsModal) ?>">
<?php if (!$registered_users_search->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
<div class="ewSearchDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_registered_userssearch" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($registered_users->id->Visible) { // id ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
	<div id="r_id" class="form-group">
		<label for="x_id" class="<?php echo $registered_users_search->LeftColumnClass ?>"><span id="elh_registered_users_id"><?php echo $registered_users->id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></p>
		</label>
		<div class="<?php echo $registered_users_search->RightColumnClass ?>"><div<?php echo $registered_users->id->CellAttributes() ?>>
			<span id="el_registered_users_id">
<input type="text" data-table="registered_users" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($registered_users->id->getPlaceHolder()) ?>" value="<?php echo $registered_users->id->EditValue ?>"<?php echo $registered_users->id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_id">
		<td class="col-sm-2"><span id="elh_registered_users_id"><?php echo $registered_users->id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_id" id="z_id" value="="></span></td>
		<td<?php echo $registered_users->id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_registered_users_id">
<input type="text" data-table="registered_users" data-field="x_id" name="x_id" id="x_id" placeholder="<?php echo ew_HtmlEncode($registered_users->id->getPlaceHolder()) ?>" value="<?php echo $registered_users->id->EditValue ?>"<?php echo $registered_users->id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->activity_id->Visible) { // activity_id ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
	<div id="r_activity_id" class="form-group">
		<label for="x_activity_id" class="<?php echo $registered_users_search->LeftColumnClass ?>"><span id="elh_registered_users_activity_id"><?php echo $registered_users->activity_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_id" id="z_activity_id" value="="></p>
		</label>
		<div class="<?php echo $registered_users_search->RightColumnClass ?>"><div<?php echo $registered_users->activity_id->CellAttributes() ?>>
			<span id="el_registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_activity_id"><?php echo (strval($registered_users->activity_id->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x_activity_id" id="x_activity_id" value="<?php echo $registered_users->activity_id->AdvancedSearch->SearchValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_id">
		<td class="col-sm-2"><span id="elh_registered_users_activity_id"><?php echo $registered_users->activity_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_activity_id" id="z_activity_id" value="="></span></td>
		<td<?php echo $registered_users->activity_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_activity_id"><?php echo (strval($registered_users->activity_id->AdvancedSearch->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->AdvancedSearch->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x_activity_id" id="x_activity_id" value="<?php echo $registered_users->activity_id->AdvancedSearch->SearchValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->user_id->Visible) { // user_id ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
	<div id="r_user_id" class="form-group">
		<label for="x_user_id" class="<?php echo $registered_users_search->LeftColumnClass ?>"><span id="elh_registered_users_user_id"><?php echo $registered_users->user_id->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_user_id" id="z_user_id" value="LIKE"></p>
		</label>
		<div class="<?php echo $registered_users_search->RightColumnClass ?>"><div<?php echo $registered_users->user_id->CellAttributes() ?>>
			<span id="el_registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x_user_id" id="x_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_user_id">
		<td class="col-sm-2"><span id="elh_registered_users_user_id"><?php echo $registered_users->user_id->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_user_id" id="z_user_id" value="LIKE"></span></td>
		<td<?php echo $registered_users->user_id->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x_user_id" id="x_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->admin_approval->Visible) { // admin_approval ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label class="<?php echo $registered_users_search->LeftColumnClass ?>"><span id="elh_registered_users_admin_approval"><?php echo $registered_users->admin_approval->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></p>
		</label>
		<div class="<?php echo $registered_users_search->RightColumnClass ?>"><div<?php echo $registered_users->admin_approval->CellAttributes() ?>>
			<span id="el_registered_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_registered_users_admin_approval"><?php echo $registered_users->admin_approval->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_admin_approval" id="z_admin_approval" value="="></span></td>
		<td<?php echo $registered_users->admin_approval->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_registered_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->admin_comment->Visible) { // admin_comment ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label for="x_admin_comment" class="<?php echo $registered_users_search->LeftColumnClass ?>"><span id="elh_registered_users_admin_comment"><?php echo $registered_users->admin_comment->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></p>
		</label>
		<div class="<?php echo $registered_users_search->RightColumnClass ?>"><div<?php echo $registered_users->admin_comment->CellAttributes() ?>>
			<span id="el_registered_users_admin_comment">
<input type="text" data-table="registered_users" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>" value="<?php echo $registered_users->admin_comment->EditValue ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_registered_users_admin_comment"><?php echo $registered_users->admin_comment->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_admin_comment" id="z_admin_comment" value="LIKE"></span></td>
		<td<?php echo $registered_users->admin_comment->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_registered_users_admin_comment">
<input type="text" data-table="registered_users" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" size="35" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>" value="<?php echo $registered_users->admin_comment->EditValue ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->evaluation_rate->Visible) { // evaluation_rate ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
	<div id="r_evaluation_rate" class="form-group">
		<label for="x_evaluation_rate" class="<?php echo $registered_users_search->LeftColumnClass ?>"><span id="elh_registered_users_evaluation_rate"><?php echo $registered_users->evaluation_rate->FldCaption() ?></span>
		<p class="form-control-static ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_evaluation_rate" id="z_evaluation_rate" value="="></p>
		</label>
		<div class="<?php echo $registered_users_search->RightColumnClass ?>"><div<?php echo $registered_users->evaluation_rate->CellAttributes() ?>>
			<span id="el_registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x_evaluation_rate" id="x_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
		</div></div>
	</div>
<?php } else { ?>
	<tr id="r_evaluation_rate">
		<td class="col-sm-2"><span id="elh_registered_users_evaluation_rate"><?php echo $registered_users->evaluation_rate->FldCaption() ?></span></td>
		<td class="col-sm-1"><span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_evaluation_rate" id="z_evaluation_rate" value="="></span></td>
		<td<?php echo $registered_users->evaluation_rate->CellAttributes() ?>>
			<div class="text-nowrap">
				<span id="el_registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x_evaluation_rate" id="x_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
			</div>
		</td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users_search->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$registered_users_search->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $registered_users_search->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("Search") ?></button>
<button class="btn btn-default ewButton" name="btnReset" id="btnReset" type="button" onclick="ew_ClearForm(this.form);"><?php echo $Language->Phrase("Reset") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$registered_users_search->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fregistered_userssearch.Init();
</script>
<?php
$registered_users_search->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$registered_users_search->Page_Terminate();
?>
