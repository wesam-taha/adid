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

$global_settings_edit = NULL; // Initialize page object first

class cglobal_settings_edit extends cglobal_settings {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'global_settings';

	// Page object name
	var $PageObjName = 'global_settings_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewEditForm form-horizontal";

		// Load key from QueryString
		if (@$_GET["global_id"] <> "") {
			$this->global_id->setQueryStringValue($_GET["global_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->global_id->CurrentValue == "") {
			$this->Page_Terminate("global_settingslist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("global_settingslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "global_settingslist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->system_logo->Upload->Index = $objForm->Index;
		$this->system_logo->Upload->UploadFile();
		$this->system_logo->CurrentValue = $this->system_logo->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->global_id->FldIsDetailKey)
			$this->global_id->setFormValue($objForm->GetValue("x_global_id"));
		if (!$this->system_name_ar->FldIsDetailKey) {
			$this->system_name_ar->setFormValue($objForm->GetValue("x_system_name_ar"));
		}
		if (!$this->system_name_en->FldIsDetailKey) {
			$this->system_name_en->setFormValue($objForm->GetValue("x_system_name_en"));
		}
		if (!$this->contact_email->FldIsDetailKey) {
			$this->contact_email->setFormValue($objForm->GetValue("x_contact_email"));
		}
		if (!$this->contact_info_ar->FldIsDetailKey) {
			$this->contact_info_ar->setFormValue($objForm->GetValue("x_contact_info_ar"));
		}
		if (!$this->contact_info_en->FldIsDetailKey) {
			$this->contact_info_en->setFormValue($objForm->GetValue("x_contact_info_en"));
		}
		if (!$this->about_us_ar->FldIsDetailKey) {
			$this->about_us_ar->setFormValue($objForm->GetValue("x_about_us_ar"));
		}
		if (!$this->about_us_en->FldIsDetailKey) {
			$this->about_us_en->setFormValue($objForm->GetValue("x_about_us_en"));
		}
		if (!$this->twiiter->FldIsDetailKey) {
			$this->twiiter->setFormValue($objForm->GetValue("x_twiiter"));
		}
		if (!$this->facebook->FldIsDetailKey) {
			$this->facebook->setFormValue($objForm->GetValue("x_facebook"));
		}
		if (!$this->instagram->FldIsDetailKey) {
			$this->instagram->setFormValue($objForm->GetValue("x_instagram"));
		}
		if (!$this->youtube->FldIsDetailKey) {
			$this->youtube->setFormValue($objForm->GetValue("x_youtube"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->global_id->CurrentValue = $this->global_id->FormValue;
		$this->system_name_ar->CurrentValue = $this->system_name_ar->FormValue;
		$this->system_name_en->CurrentValue = $this->system_name_en->FormValue;
		$this->contact_email->CurrentValue = $this->contact_email->FormValue;
		$this->contact_info_ar->CurrentValue = $this->contact_info_ar->FormValue;
		$this->contact_info_en->CurrentValue = $this->contact_info_en->FormValue;
		$this->about_us_ar->CurrentValue = $this->about_us_ar->FormValue;
		$this->about_us_en->CurrentValue = $this->about_us_en->FormValue;
		$this->twiiter->CurrentValue = $this->twiiter->FormValue;
		$this->facebook->CurrentValue = $this->facebook->FormValue;
		$this->instagram->CurrentValue = $this->instagram->FormValue;
		$this->youtube->CurrentValue = $this->youtube->FormValue;
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
		$this->global_id->setDbValue($row['global_id']);
		$this->system_name_ar->setDbValue($row['system_name_ar']);
		$this->system_name_en->setDbValue($row['system_name_en']);
		$this->contact_email->setDbValue($row['contact_email']);
		$this->system_logo->Upload->DbValue = $row['system_logo'];
		$this->system_logo->CurrentValue = $this->system_logo->Upload->DbValue;
		$this->contact_info_ar->setDbValue($row['contact_info_ar']);
		$this->contact_info_en->setDbValue($row['contact_info_en']);
		$this->about_us_ar->setDbValue($row['about_us_ar']);
		$this->about_us_en->setDbValue($row['about_us_en']);
		$this->twiiter->setDbValue($row['twiiter']);
		$this->facebook->setDbValue($row['facebook']);
		$this->instagram->setDbValue($row['instagram']);
		$this->youtube->setDbValue($row['youtube']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['global_id'] = NULL;
		$row['system_name_ar'] = NULL;
		$row['system_name_en'] = NULL;
		$row['contact_email'] = NULL;
		$row['system_logo'] = NULL;
		$row['contact_info_ar'] = NULL;
		$row['contact_info_en'] = NULL;
		$row['about_us_ar'] = NULL;
		$row['about_us_en'] = NULL;
		$row['twiiter'] = NULL;
		$row['facebook'] = NULL;
		$row['instagram'] = NULL;
		$row['youtube'] = NULL;
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
		$this->global_id->DbValue = $row['global_id'];
		$this->system_name_ar->DbValue = $row['system_name_ar'];
		$this->system_name_en->DbValue = $row['system_name_en'];
		$this->contact_email->DbValue = $row['contact_email'];
		$this->system_logo->Upload->DbValue = $row['system_logo'];
		$this->contact_info_ar->DbValue = $row['contact_info_ar'];
		$this->contact_info_en->DbValue = $row['contact_info_en'];
		$this->about_us_ar->DbValue = $row['about_us_ar'];
		$this->about_us_en->DbValue = $row['about_us_en'];
		$this->twiiter->DbValue = $row['twiiter'];
		$this->facebook->DbValue = $row['facebook'];
		$this->instagram->DbValue = $row['instagram'];
		$this->youtube->DbValue = $row['youtube'];
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// global_id
			$this->global_id->EditAttrs["class"] = "form-control";
			$this->global_id->EditCustomAttributes = "";
			$this->global_id->EditValue = $this->global_id->CurrentValue;
			$this->global_id->ViewCustomAttributes = "";

			// system_name_ar
			$this->system_name_ar->EditAttrs["class"] = "form-control";
			$this->system_name_ar->EditCustomAttributes = "";
			$this->system_name_ar->EditValue = ew_HtmlEncode($this->system_name_ar->CurrentValue);
			$this->system_name_ar->PlaceHolder = ew_RemoveHtml($this->system_name_ar->FldCaption());

			// system_name_en
			$this->system_name_en->EditAttrs["class"] = "form-control";
			$this->system_name_en->EditCustomAttributes = "";
			$this->system_name_en->EditValue = ew_HtmlEncode($this->system_name_en->CurrentValue);
			$this->system_name_en->PlaceHolder = ew_RemoveHtml($this->system_name_en->FldCaption());

			// contact_email
			$this->contact_email->EditAttrs["class"] = "form-control";
			$this->contact_email->EditCustomAttributes = "";
			$this->contact_email->EditValue = ew_HtmlEncode($this->contact_email->CurrentValue);
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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->system_logo);

			// contact_info_ar
			$this->contact_info_ar->EditAttrs["class"] = "form-control";
			$this->contact_info_ar->EditCustomAttributes = "";
			$this->contact_info_ar->EditValue = ew_HtmlEncode($this->contact_info_ar->CurrentValue);
			$this->contact_info_ar->PlaceHolder = ew_RemoveHtml($this->contact_info_ar->FldCaption());

			// contact_info_en
			$this->contact_info_en->EditAttrs["class"] = "form-control";
			$this->contact_info_en->EditCustomAttributes = "";
			$this->contact_info_en->EditValue = ew_HtmlEncode($this->contact_info_en->CurrentValue);
			$this->contact_info_en->PlaceHolder = ew_RemoveHtml($this->contact_info_en->FldCaption());

			// about_us_ar
			$this->about_us_ar->EditAttrs["class"] = "form-control";
			$this->about_us_ar->EditCustomAttributes = "";
			$this->about_us_ar->EditValue = ew_HtmlEncode($this->about_us_ar->CurrentValue);
			$this->about_us_ar->PlaceHolder = ew_RemoveHtml($this->about_us_ar->FldCaption());

			// about_us_en
			$this->about_us_en->EditAttrs["class"] = "form-control";
			$this->about_us_en->EditCustomAttributes = "";
			$this->about_us_en->EditValue = ew_HtmlEncode($this->about_us_en->CurrentValue);
			$this->about_us_en->PlaceHolder = ew_RemoveHtml($this->about_us_en->FldCaption());

			// twiiter
			$this->twiiter->EditAttrs["class"] = "form-control";
			$this->twiiter->EditCustomAttributes = "";
			$this->twiiter->EditValue = ew_HtmlEncode($this->twiiter->CurrentValue);
			$this->twiiter->PlaceHolder = ew_RemoveHtml($this->twiiter->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->CurrentValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// instagram
			$this->instagram->EditAttrs["class"] = "form-control";
			$this->instagram->EditCustomAttributes = "";
			$this->instagram->EditValue = ew_HtmlEncode($this->instagram->CurrentValue);
			$this->instagram->PlaceHolder = ew_RemoveHtml($this->instagram->FldCaption());

			// youtube
			$this->youtube->EditAttrs["class"] = "form-control";
			$this->youtube->EditCustomAttributes = "";
			$this->youtube->EditValue = ew_HtmlEncode($this->youtube->CurrentValue);
			$this->youtube->PlaceHolder = ew_RemoveHtml($this->youtube->FldCaption());

			// Edit refer script
			// global_id

			$this->global_id->LinkCustomAttributes = "";
			$this->global_id->HrefValue = "";

			// system_name_ar
			$this->system_name_ar->LinkCustomAttributes = "";
			$this->system_name_ar->HrefValue = "";

			// system_name_en
			$this->system_name_en->LinkCustomAttributes = "";
			$this->system_name_en->HrefValue = "";

			// contact_email
			$this->contact_email->LinkCustomAttributes = "";
			$this->contact_email->HrefValue = "";

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

			// contact_info_ar
			$this->contact_info_ar->LinkCustomAttributes = "";
			$this->contact_info_ar->HrefValue = "";

			// contact_info_en
			$this->contact_info_en->LinkCustomAttributes = "";
			$this->contact_info_en->HrefValue = "";

			// about_us_ar
			$this->about_us_ar->LinkCustomAttributes = "";
			$this->about_us_ar->HrefValue = "";

			// about_us_en
			$this->about_us_en->LinkCustomAttributes = "";
			$this->about_us_en->HrefValue = "";

			// twiiter
			$this->twiiter->LinkCustomAttributes = "";
			$this->twiiter->HrefValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			$this->facebook->HrefValue = "";

			// instagram
			$this->instagram->LinkCustomAttributes = "";
			$this->instagram->HrefValue = "";

			// youtube
			$this->youtube->LinkCustomAttributes = "";
			$this->youtube->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->system_logo->OldUploadPath = "../uploads";
			$this->system_logo->UploadPath = $this->system_logo->OldUploadPath;
			$rsnew = array();

			// system_name_ar
			$this->system_name_ar->SetDbValueDef($rsnew, $this->system_name_ar->CurrentValue, NULL, $this->system_name_ar->ReadOnly);

			// system_name_en
			$this->system_name_en->SetDbValueDef($rsnew, $this->system_name_en->CurrentValue, NULL, $this->system_name_en->ReadOnly);

			// contact_email
			$this->contact_email->SetDbValueDef($rsnew, $this->contact_email->CurrentValue, NULL, $this->contact_email->ReadOnly);

			// system_logo
			if ($this->system_logo->Visible && !$this->system_logo->ReadOnly && !$this->system_logo->Upload->KeepFile) {
				$this->system_logo->Upload->DbValue = $rsold['system_logo']; // Get original value
				if ($this->system_logo->Upload->FileName == "") {
					$rsnew['system_logo'] = NULL;
				} else {
					$rsnew['system_logo'] = $this->system_logo->Upload->FileName;
				}
			}

			// contact_info_ar
			$this->contact_info_ar->SetDbValueDef($rsnew, $this->contact_info_ar->CurrentValue, NULL, $this->contact_info_ar->ReadOnly);

			// contact_info_en
			$this->contact_info_en->SetDbValueDef($rsnew, $this->contact_info_en->CurrentValue, NULL, $this->contact_info_en->ReadOnly);

			// about_us_ar
			$this->about_us_ar->SetDbValueDef($rsnew, $this->about_us_ar->CurrentValue, NULL, $this->about_us_ar->ReadOnly);

			// about_us_en
			$this->about_us_en->SetDbValueDef($rsnew, $this->about_us_en->CurrentValue, NULL, $this->about_us_en->ReadOnly);

			// twiiter
			$this->twiiter->SetDbValueDef($rsnew, $this->twiiter->CurrentValue, NULL, $this->twiiter->ReadOnly);

			// facebook
			$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, NULL, $this->facebook->ReadOnly);

			// instagram
			$this->instagram->SetDbValueDef($rsnew, $this->instagram->CurrentValue, NULL, $this->instagram->ReadOnly);

			// youtube
			$this->youtube->SetDbValueDef($rsnew, $this->youtube->CurrentValue, NULL, $this->youtube->ReadOnly);
			if ($this->system_logo->Visible && !$this->system_logo->Upload->KeepFile) {
				$this->system_logo->UploadPath = "../uploads";
				if (!ew_Empty($this->system_logo->Upload->Value)) {
					if ($this->system_logo->Upload->FileName == $this->system_logo->Upload->DbValue) { // Overwrite if same file name
						$this->system_logo->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['system_logo'] = ew_UploadFileNameEx($this->system_logo->PhysicalUploadPath(), $rsnew['system_logo']); // Get new file name
					}
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->system_logo->Visible && !$this->system_logo->Upload->KeepFile) {
						if (!ew_Empty($this->system_logo->Upload->Value)) {
							if (!$this->system_logo->Upload->SaveToFile($rsnew['system_logo'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
						if ($this->system_logo->Upload->DbValue <> "")
							@unlink($this->system_logo->OldPhysicalUploadPath() . $this->system_logo->Upload->DbValue);
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// system_logo
		ew_CleanUploadTempPath($this->system_logo, $this->system_logo->Upload->Index);
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("global_settingslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($global_settings_edit)) $global_settings_edit = new cglobal_settings_edit();

// Page init
$global_settings_edit->Page_Init();

// Page main
$global_settings_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$global_settings_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fglobal_settingsedit = new ew_Form("fglobal_settingsedit", "edit");

// Validate form
fglobal_settingsedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fglobal_settingsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fglobal_settingsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $global_settings_edit->ShowPageHeader(); ?>
<?php
$global_settings_edit->ShowMessage();
?>
<form name="fglobal_settingsedit" id="fglobal_settingsedit" class="<?php echo $global_settings_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($global_settings_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $global_settings_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="global_settings">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($global_settings_edit->IsModal) ?>">
<?php if (!$global_settings_edit->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
<div class="ewEditDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_global_settingsedit" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($global_settings->global_id->Visible) { // global_id ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_global_id" class="form-group">
		<label id="elh_global_settings_global_id" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->global_id->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->global_id->CellAttributes() ?>>
<span id="el_global_settings_global_id">
<span<?php echo $global_settings->global_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $global_settings->global_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="global_settings" data-field="x_global_id" name="x_global_id" id="x_global_id" value="<?php echo ew_HtmlEncode($global_settings->global_id->CurrentValue) ?>">
<?php echo $global_settings->global_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_global_id">
		<td class="col-sm-2"><span id="elh_global_settings_global_id"><?php echo $global_settings->global_id->FldCaption() ?></span></td>
		<td<?php echo $global_settings->global_id->CellAttributes() ?>>
<span id="el_global_settings_global_id">
<span<?php echo $global_settings->global_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $global_settings->global_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="global_settings" data-field="x_global_id" name="x_global_id" id="x_global_id" value="<?php echo ew_HtmlEncode($global_settings->global_id->CurrentValue) ?>">
<?php echo $global_settings->global_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->system_name_ar->Visible) { // system_name_ar ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_system_name_ar" class="form-group">
		<label id="elh_global_settings_system_name_ar" for="x_system_name_ar" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->system_name_ar->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->system_name_ar->CellAttributes() ?>>
<span id="el_global_settings_system_name_ar">
<input type="text" data-table="global_settings" data-field="x_system_name_ar" name="x_system_name_ar" id="x_system_name_ar" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_ar->EditValue ?>"<?php echo $global_settings->system_name_ar->EditAttributes() ?>>
</span>
<?php echo $global_settings->system_name_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_system_name_ar">
		<td class="col-sm-2"><span id="elh_global_settings_system_name_ar"><?php echo $global_settings->system_name_ar->FldCaption() ?></span></td>
		<td<?php echo $global_settings->system_name_ar->CellAttributes() ?>>
<span id="el_global_settings_system_name_ar">
<input type="text" data-table="global_settings" data-field="x_system_name_ar" name="x_system_name_ar" id="x_system_name_ar" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_ar->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_ar->EditValue ?>"<?php echo $global_settings->system_name_ar->EditAttributes() ?>>
</span>
<?php echo $global_settings->system_name_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->system_name_en->Visible) { // system_name_en ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_system_name_en" class="form-group">
		<label id="elh_global_settings_system_name_en" for="x_system_name_en" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->system_name_en->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->system_name_en->CellAttributes() ?>>
<span id="el_global_settings_system_name_en">
<input type="text" data-table="global_settings" data-field="x_system_name_en" name="x_system_name_en" id="x_system_name_en" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_en->EditValue ?>"<?php echo $global_settings->system_name_en->EditAttributes() ?>>
</span>
<?php echo $global_settings->system_name_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_system_name_en">
		<td class="col-sm-2"><span id="elh_global_settings_system_name_en"><?php echo $global_settings->system_name_en->FldCaption() ?></span></td>
		<td<?php echo $global_settings->system_name_en->CellAttributes() ?>>
<span id="el_global_settings_system_name_en">
<input type="text" data-table="global_settings" data-field="x_system_name_en" name="x_system_name_en" id="x_system_name_en" placeholder="<?php echo ew_HtmlEncode($global_settings->system_name_en->getPlaceHolder()) ?>" value="<?php echo $global_settings->system_name_en->EditValue ?>"<?php echo $global_settings->system_name_en->EditAttributes() ?>>
</span>
<?php echo $global_settings->system_name_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_email->Visible) { // contact_email ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_contact_email" class="form-group">
		<label id="elh_global_settings_contact_email" for="x_contact_email" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->contact_email->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->contact_email->CellAttributes() ?>>
<span id="el_global_settings_contact_email">
<input type="text" data-table="global_settings" data-field="x_contact_email" name="x_contact_email" id="x_contact_email" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_email->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_email->EditValue ?>"<?php echo $global_settings->contact_email->EditAttributes() ?>>
</span>
<?php echo $global_settings->contact_email->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_contact_email">
		<td class="col-sm-2"><span id="elh_global_settings_contact_email"><?php echo $global_settings->contact_email->FldCaption() ?></span></td>
		<td<?php echo $global_settings->contact_email->CellAttributes() ?>>
<span id="el_global_settings_contact_email">
<input type="text" data-table="global_settings" data-field="x_contact_email" name="x_contact_email" id="x_contact_email" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_email->getPlaceHolder()) ?>" value="<?php echo $global_settings->contact_email->EditValue ?>"<?php echo $global_settings->contact_email->EditAttributes() ?>>
</span>
<?php echo $global_settings->contact_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->system_logo->Visible) { // system_logo ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_system_logo" class="form-group">
		<label id="elh_global_settings_system_logo" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->system_logo->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->system_logo->CellAttributes() ?>>
<span id="el_global_settings_system_logo">
<div id="fd_x_system_logo">
<span title="<?php echo $global_settings->system_logo->FldTitle() ? $global_settings->system_logo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($global_settings->system_logo->ReadOnly || $global_settings->system_logo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="global_settings" data-field="x_system_logo" name="x_system_logo" id="x_system_logo"<?php echo $global_settings->system_logo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_system_logo" id= "fn_x_system_logo" value="<?php echo $global_settings->system_logo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_system_logo"] == "0") { ?>
<input type="hidden" name="fa_x_system_logo" id= "fa_x_system_logo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_system_logo" id= "fa_x_system_logo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_system_logo" id= "fs_x_system_logo" value="65535">
<input type="hidden" name="fx_x_system_logo" id= "fx_x_system_logo" value="<?php echo $global_settings->system_logo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_system_logo" id= "fm_x_system_logo" value="<?php echo $global_settings->system_logo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_system_logo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $global_settings->system_logo->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_system_logo">
		<td class="col-sm-2"><span id="elh_global_settings_system_logo"><?php echo $global_settings->system_logo->FldCaption() ?></span></td>
		<td<?php echo $global_settings->system_logo->CellAttributes() ?>>
<span id="el_global_settings_system_logo">
<div id="fd_x_system_logo">
<span title="<?php echo $global_settings->system_logo->FldTitle() ? $global_settings->system_logo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($global_settings->system_logo->ReadOnly || $global_settings->system_logo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="global_settings" data-field="x_system_logo" name="x_system_logo" id="x_system_logo"<?php echo $global_settings->system_logo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_system_logo" id= "fn_x_system_logo" value="<?php echo $global_settings->system_logo->Upload->FileName ?>">
<?php if (@$_POST["fa_x_system_logo"] == "0") { ?>
<input type="hidden" name="fa_x_system_logo" id= "fa_x_system_logo" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_system_logo" id= "fa_x_system_logo" value="1">
<?php } ?>
<input type="hidden" name="fs_x_system_logo" id= "fs_x_system_logo" value="65535">
<input type="hidden" name="fx_x_system_logo" id= "fx_x_system_logo" value="<?php echo $global_settings->system_logo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_system_logo" id= "fm_x_system_logo" value="<?php echo $global_settings->system_logo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_system_logo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $global_settings->system_logo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_info_ar->Visible) { // contact_info_ar ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_contact_info_ar" class="form-group">
		<label id="elh_global_settings_contact_info_ar" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->contact_info_ar->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->contact_info_ar->CellAttributes() ?>>
<span id="el_global_settings_contact_info_ar">
<?php ew_AppendClass($global_settings->contact_info_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_contact_info_ar" name="x_contact_info_ar" id="x_contact_info_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_ar->getPlaceHolder()) ?>"<?php echo $global_settings->contact_info_ar->EditAttributes() ?>><?php echo $global_settings->contact_info_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_contact_info_ar", 35, 4, <?php echo ($global_settings->contact_info_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->contact_info_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_contact_info_ar">
		<td class="col-sm-2"><span id="elh_global_settings_contact_info_ar"><?php echo $global_settings->contact_info_ar->FldCaption() ?></span></td>
		<td<?php echo $global_settings->contact_info_ar->CellAttributes() ?>>
<span id="el_global_settings_contact_info_ar">
<?php ew_AppendClass($global_settings->contact_info_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_contact_info_ar" name="x_contact_info_ar" id="x_contact_info_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_ar->getPlaceHolder()) ?>"<?php echo $global_settings->contact_info_ar->EditAttributes() ?>><?php echo $global_settings->contact_info_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_contact_info_ar", 35, 4, <?php echo ($global_settings->contact_info_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->contact_info_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_info_en->Visible) { // contact_info_en ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_contact_info_en" class="form-group">
		<label id="elh_global_settings_contact_info_en" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->contact_info_en->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->contact_info_en->CellAttributes() ?>>
<span id="el_global_settings_contact_info_en">
<?php ew_AppendClass($global_settings->contact_info_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_contact_info_en" name="x_contact_info_en" id="x_contact_info_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_en->getPlaceHolder()) ?>"<?php echo $global_settings->contact_info_en->EditAttributes() ?>><?php echo $global_settings->contact_info_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_contact_info_en", 35, 4, <?php echo ($global_settings->contact_info_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->contact_info_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_contact_info_en">
		<td class="col-sm-2"><span id="elh_global_settings_contact_info_en"><?php echo $global_settings->contact_info_en->FldCaption() ?></span></td>
		<td<?php echo $global_settings->contact_info_en->CellAttributes() ?>>
<span id="el_global_settings_contact_info_en">
<?php ew_AppendClass($global_settings->contact_info_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_contact_info_en" name="x_contact_info_en" id="x_contact_info_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->contact_info_en->getPlaceHolder()) ?>"<?php echo $global_settings->contact_info_en->EditAttributes() ?>><?php echo $global_settings->contact_info_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_contact_info_en", 35, 4, <?php echo ($global_settings->contact_info_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->contact_info_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->about_us_ar->Visible) { // about_us_ar ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_about_us_ar" class="form-group">
		<label id="elh_global_settings_about_us_ar" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->about_us_ar->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->about_us_ar->CellAttributes() ?>>
<span id="el_global_settings_about_us_ar">
<?php ew_AppendClass($global_settings->about_us_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_about_us_ar" name="x_about_us_ar" id="x_about_us_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_ar->getPlaceHolder()) ?>"<?php echo $global_settings->about_us_ar->EditAttributes() ?>><?php echo $global_settings->about_us_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_about_us_ar", 35, 4, <?php echo ($global_settings->about_us_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->about_us_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_about_us_ar">
		<td class="col-sm-2"><span id="elh_global_settings_about_us_ar"><?php echo $global_settings->about_us_ar->FldCaption() ?></span></td>
		<td<?php echo $global_settings->about_us_ar->CellAttributes() ?>>
<span id="el_global_settings_about_us_ar">
<?php ew_AppendClass($global_settings->about_us_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_about_us_ar" name="x_about_us_ar" id="x_about_us_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_ar->getPlaceHolder()) ?>"<?php echo $global_settings->about_us_ar->EditAttributes() ?>><?php echo $global_settings->about_us_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_about_us_ar", 35, 4, <?php echo ($global_settings->about_us_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->about_us_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->about_us_en->Visible) { // about_us_en ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_about_us_en" class="form-group">
		<label id="elh_global_settings_about_us_en" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->about_us_en->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->about_us_en->CellAttributes() ?>>
<span id="el_global_settings_about_us_en">
<?php ew_AppendClass($global_settings->about_us_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_about_us_en" name="x_about_us_en" id="x_about_us_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_en->getPlaceHolder()) ?>"<?php echo $global_settings->about_us_en->EditAttributes() ?>><?php echo $global_settings->about_us_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_about_us_en", 35, 4, <?php echo ($global_settings->about_us_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->about_us_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_about_us_en">
		<td class="col-sm-2"><span id="elh_global_settings_about_us_en"><?php echo $global_settings->about_us_en->FldCaption() ?></span></td>
		<td<?php echo $global_settings->about_us_en->CellAttributes() ?>>
<span id="el_global_settings_about_us_en">
<?php ew_AppendClass($global_settings->about_us_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="global_settings" data-field="x_about_us_en" name="x_about_us_en" id="x_about_us_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->about_us_en->getPlaceHolder()) ?>"<?php echo $global_settings->about_us_en->EditAttributes() ?>><?php echo $global_settings->about_us_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fglobal_settingsedit", "x_about_us_en", 35, 4, <?php echo ($global_settings->about_us_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $global_settings->about_us_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->twiiter->Visible) { // twiiter ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_twiiter" class="form-group">
		<label id="elh_global_settings_twiiter" for="x_twiiter" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->twiiter->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->twiiter->CellAttributes() ?>>
<span id="el_global_settings_twiiter">
<textarea data-table="global_settings" data-field="x_twiiter" name="x_twiiter" id="x_twiiter" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->twiiter->getPlaceHolder()) ?>"<?php echo $global_settings->twiiter->EditAttributes() ?>><?php echo $global_settings->twiiter->EditValue ?></textarea>
</span>
<?php echo $global_settings->twiiter->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_twiiter">
		<td class="col-sm-2"><span id="elh_global_settings_twiiter"><?php echo $global_settings->twiiter->FldCaption() ?></span></td>
		<td<?php echo $global_settings->twiiter->CellAttributes() ?>>
<span id="el_global_settings_twiiter">
<textarea data-table="global_settings" data-field="x_twiiter" name="x_twiiter" id="x_twiiter" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->twiiter->getPlaceHolder()) ?>"<?php echo $global_settings->twiiter->EditAttributes() ?>><?php echo $global_settings->twiiter->EditValue ?></textarea>
</span>
<?php echo $global_settings->twiiter->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->facebook->Visible) { // facebook ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_facebook" class="form-group">
		<label id="elh_global_settings_facebook" for="x_facebook" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->facebook->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->facebook->CellAttributes() ?>>
<span id="el_global_settings_facebook">
<textarea data-table="global_settings" data-field="x_facebook" name="x_facebook" id="x_facebook" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->facebook->getPlaceHolder()) ?>"<?php echo $global_settings->facebook->EditAttributes() ?>><?php echo $global_settings->facebook->EditValue ?></textarea>
</span>
<?php echo $global_settings->facebook->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_facebook">
		<td class="col-sm-2"><span id="elh_global_settings_facebook"><?php echo $global_settings->facebook->FldCaption() ?></span></td>
		<td<?php echo $global_settings->facebook->CellAttributes() ?>>
<span id="el_global_settings_facebook">
<textarea data-table="global_settings" data-field="x_facebook" name="x_facebook" id="x_facebook" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->facebook->getPlaceHolder()) ?>"<?php echo $global_settings->facebook->EditAttributes() ?>><?php echo $global_settings->facebook->EditValue ?></textarea>
</span>
<?php echo $global_settings->facebook->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->instagram->Visible) { // instagram ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_instagram" class="form-group">
		<label id="elh_global_settings_instagram" for="x_instagram" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->instagram->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->instagram->CellAttributes() ?>>
<span id="el_global_settings_instagram">
<textarea data-table="global_settings" data-field="x_instagram" name="x_instagram" id="x_instagram" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->instagram->getPlaceHolder()) ?>"<?php echo $global_settings->instagram->EditAttributes() ?>><?php echo $global_settings->instagram->EditValue ?></textarea>
</span>
<?php echo $global_settings->instagram->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_instagram">
		<td class="col-sm-2"><span id="elh_global_settings_instagram"><?php echo $global_settings->instagram->FldCaption() ?></span></td>
		<td<?php echo $global_settings->instagram->CellAttributes() ?>>
<span id="el_global_settings_instagram">
<textarea data-table="global_settings" data-field="x_instagram" name="x_instagram" id="x_instagram" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->instagram->getPlaceHolder()) ?>"<?php echo $global_settings->instagram->EditAttributes() ?>><?php echo $global_settings->instagram->EditValue ?></textarea>
</span>
<?php echo $global_settings->instagram->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings->youtube->Visible) { // youtube ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
	<div id="r_youtube" class="form-group">
		<label id="elh_global_settings_youtube" for="x_youtube" class="<?php echo $global_settings_edit->LeftColumnClass ?>"><?php echo $global_settings->youtube->FldCaption() ?></label>
		<div class="<?php echo $global_settings_edit->RightColumnClass ?>"><div<?php echo $global_settings->youtube->CellAttributes() ?>>
<span id="el_global_settings_youtube">
<textarea data-table="global_settings" data-field="x_youtube" name="x_youtube" id="x_youtube" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->youtube->getPlaceHolder()) ?>"<?php echo $global_settings->youtube->EditAttributes() ?>><?php echo $global_settings->youtube->EditValue ?></textarea>
</span>
<?php echo $global_settings->youtube->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_youtube">
		<td class="col-sm-2"><span id="elh_global_settings_youtube"><?php echo $global_settings->youtube->FldCaption() ?></span></td>
		<td<?php echo $global_settings->youtube->CellAttributes() ?>>
<span id="el_global_settings_youtube">
<textarea data-table="global_settings" data-field="x_youtube" name="x_youtube" id="x_youtube" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($global_settings->youtube->getPlaceHolder()) ?>"<?php echo $global_settings->youtube->EditAttributes() ?>><?php echo $global_settings->youtube->EditValue ?></textarea>
</span>
<?php echo $global_settings->youtube->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($global_settings_edit->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$global_settings_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $global_settings_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $global_settings_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$global_settings_edit->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fglobal_settingsedit.Init();
</script>
<?php
$global_settings_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$global_settings_edit->Page_Terminate();
?>
