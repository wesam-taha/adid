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

$institutions_requests_add = NULL; // Initialize page object first

class cinstitutions_requests_add extends cinstitutions_requests {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions_requests';

	// Page object name
	var $PageObjName = 'institutions_requests_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("institutions_requestslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "institutions_requestslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "institutions_requestsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->institutions_id->CurrentValue = NULL;
		$this->institutions_id->OldValue = $this->institutions_id->CurrentValue;
		$this->event_name->CurrentValue = NULL;
		$this->event_name->OldValue = $this->event_name->CurrentValue;
		$this->event_emirate->CurrentValue = NULL;
		$this->event_emirate->OldValue = $this->event_emirate->CurrentValue;
		$this->event_location->CurrentValue = NULL;
		$this->event_location->OldValue = $this->event_location->CurrentValue;
		$this->activity_start_date->CurrentValue = NULL;
		$this->activity_start_date->OldValue = $this->activity_start_date->CurrentValue;
		$this->activity_end_date->CurrentValue = NULL;
		$this->activity_end_date->OldValue = $this->activity_end_date->CurrentValue;
		$this->activity_time->CurrentValue = NULL;
		$this->activity_time->OldValue = $this->activity_time->CurrentValue;
		$this->activity_description->CurrentValue = NULL;
		$this->activity_description->OldValue = $this->activity_description->CurrentValue;
		$this->activity_gender_target->CurrentValue = NULL;
		$this->activity_gender_target->OldValue = $this->activity_gender_target->CurrentValue;
		$this->no_of_persons_needed->CurrentValue = NULL;
		$this->no_of_persons_needed->OldValue = $this->no_of_persons_needed->CurrentValue;
		$this->no_of_hours->CurrentValue = NULL;
		$this->no_of_hours->OldValue = $this->no_of_hours->CurrentValue;
		$this->mobile_phone->CurrentValue = NULL;
		$this->mobile_phone->OldValue = $this->mobile_phone->CurrentValue;
		$this->pobox->CurrentValue = NULL;
		$this->pobox->OldValue = $this->pobox->CurrentValue;
		$this->admin_approval->CurrentValue = NULL;
		$this->admin_approval->OldValue = $this->admin_approval->CurrentValue;
		$this->admin_comment->CurrentValue = NULL;
		$this->admin_comment->OldValue = $this->admin_comment->CurrentValue;
		$this->email->CurrentValue = NULL;
		$this->email->OldValue = $this->email->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->institutions_id->FldIsDetailKey) {
			$this->institutions_id->setFormValue($objForm->GetValue("x_institutions_id"));
		}
		if (!$this->event_name->FldIsDetailKey) {
			$this->event_name->setFormValue($objForm->GetValue("x_event_name"));
		}
		if (!$this->event_emirate->FldIsDetailKey) {
			$this->event_emirate->setFormValue($objForm->GetValue("x_event_emirate"));
		}
		if (!$this->event_location->FldIsDetailKey) {
			$this->event_location->setFormValue($objForm->GetValue("x_event_location"));
		}
		if (!$this->activity_start_date->FldIsDetailKey) {
			$this->activity_start_date->setFormValue($objForm->GetValue("x_activity_start_date"));
			$this->activity_start_date->CurrentValue = ew_UnFormatDateTime($this->activity_start_date->CurrentValue, 0);
		}
		if (!$this->activity_end_date->FldIsDetailKey) {
			$this->activity_end_date->setFormValue($objForm->GetValue("x_activity_end_date"));
			$this->activity_end_date->CurrentValue = ew_UnFormatDateTime($this->activity_end_date->CurrentValue, 0);
		}
		if (!$this->activity_time->FldIsDetailKey) {
			$this->activity_time->setFormValue($objForm->GetValue("x_activity_time"));
		}
		if (!$this->activity_description->FldIsDetailKey) {
			$this->activity_description->setFormValue($objForm->GetValue("x_activity_description"));
		}
		if (!$this->activity_gender_target->FldIsDetailKey) {
			$this->activity_gender_target->setFormValue($objForm->GetValue("x_activity_gender_target"));
		}
		if (!$this->no_of_persons_needed->FldIsDetailKey) {
			$this->no_of_persons_needed->setFormValue($objForm->GetValue("x_no_of_persons_needed"));
		}
		if (!$this->no_of_hours->FldIsDetailKey) {
			$this->no_of_hours->setFormValue($objForm->GetValue("x_no_of_hours"));
		}
		if (!$this->mobile_phone->FldIsDetailKey) {
			$this->mobile_phone->setFormValue($objForm->GetValue("x_mobile_phone"));
		}
		if (!$this->pobox->FldIsDetailKey) {
			$this->pobox->setFormValue($objForm->GetValue("x_pobox"));
		}
		if (!$this->admin_approval->FldIsDetailKey) {
			$this->admin_approval->setFormValue($objForm->GetValue("x_admin_approval"));
		}
		if (!$this->admin_comment->FldIsDetailKey) {
			$this->admin_comment->setFormValue($objForm->GetValue("x_admin_comment"));
		}
		if (!$this->email->FldIsDetailKey) {
			$this->email->setFormValue($objForm->GetValue("x_email"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->institutions_id->CurrentValue = $this->institutions_id->FormValue;
		$this->event_name->CurrentValue = $this->event_name->FormValue;
		$this->event_emirate->CurrentValue = $this->event_emirate->FormValue;
		$this->event_location->CurrentValue = $this->event_location->FormValue;
		$this->activity_start_date->CurrentValue = $this->activity_start_date->FormValue;
		$this->activity_start_date->CurrentValue = ew_UnFormatDateTime($this->activity_start_date->CurrentValue, 0);
		$this->activity_end_date->CurrentValue = $this->activity_end_date->FormValue;
		$this->activity_end_date->CurrentValue = ew_UnFormatDateTime($this->activity_end_date->CurrentValue, 0);
		$this->activity_time->CurrentValue = $this->activity_time->FormValue;
		$this->activity_description->CurrentValue = $this->activity_description->FormValue;
		$this->activity_gender_target->CurrentValue = $this->activity_gender_target->FormValue;
		$this->no_of_persons_needed->CurrentValue = $this->no_of_persons_needed->FormValue;
		$this->no_of_hours->CurrentValue = $this->no_of_hours->FormValue;
		$this->mobile_phone->CurrentValue = $this->mobile_phone->FormValue;
		$this->pobox->CurrentValue = $this->pobox->FormValue;
		$this->admin_approval->CurrentValue = $this->admin_approval->FormValue;
		$this->admin_comment->CurrentValue = $this->admin_comment->FormValue;
		$this->email->CurrentValue = $this->email->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// institutions_id
			$this->institutions_id->EditAttrs["class"] = "form-control";
			$this->institutions_id->EditCustomAttributes = "";
			if (trim(strval($this->institutions_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`institution_id`" . ew_SearchString("=", $this->institutions_id->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			$this->event_name->EditValue = ew_HtmlEncode($this->event_name->CurrentValue);
			$this->event_name->PlaceHolder = ew_RemoveHtml($this->event_name->FldCaption());

			// event_emirate
			$this->event_emirate->EditAttrs["class"] = "form-control";
			$this->event_emirate->EditCustomAttributes = "";
			$this->event_emirate->EditValue = $this->event_emirate->Options(TRUE);

			// event_location
			$this->event_location->EditAttrs["class"] = "form-control";
			$this->event_location->EditCustomAttributes = "";
			$this->event_location->EditValue = ew_HtmlEncode($this->event_location->CurrentValue);
			$this->event_location->PlaceHolder = ew_RemoveHtml($this->event_location->FldCaption());

			// activity_start_date
			$this->activity_start_date->EditAttrs["class"] = "form-control";
			$this->activity_start_date->EditCustomAttributes = "";
			$this->activity_start_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->activity_start_date->CurrentValue, 8));
			$this->activity_start_date->PlaceHolder = ew_RemoveHtml($this->activity_start_date->FldCaption());

			// activity_end_date
			$this->activity_end_date->EditAttrs["class"] = "form-control";
			$this->activity_end_date->EditCustomAttributes = "";
			$this->activity_end_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->activity_end_date->CurrentValue, 8));
			$this->activity_end_date->PlaceHolder = ew_RemoveHtml($this->activity_end_date->FldCaption());

			// activity_time
			$this->activity_time->EditAttrs["class"] = "form-control";
			$this->activity_time->EditCustomAttributes = "";
			$this->activity_time->EditValue = ew_HtmlEncode($this->activity_time->CurrentValue);
			$this->activity_time->PlaceHolder = ew_RemoveHtml($this->activity_time->FldCaption());

			// activity_description
			$this->activity_description->EditAttrs["class"] = "form-control";
			$this->activity_description->EditCustomAttributes = "";
			$this->activity_description->EditValue = ew_HtmlEncode($this->activity_description->CurrentValue);
			$this->activity_description->PlaceHolder = ew_RemoveHtml($this->activity_description->FldCaption());

			// activity_gender_target
			$this->activity_gender_target->EditAttrs["class"] = "form-control";
			$this->activity_gender_target->EditCustomAttributes = "";
			$this->activity_gender_target->EditValue = $this->activity_gender_target->Options(TRUE);

			// no_of_persons_needed
			$this->no_of_persons_needed->EditAttrs["class"] = "form-control";
			$this->no_of_persons_needed->EditCustomAttributes = "";
			$this->no_of_persons_needed->EditValue = ew_HtmlEncode($this->no_of_persons_needed->CurrentValue);
			$this->no_of_persons_needed->PlaceHolder = ew_RemoveHtml($this->no_of_persons_needed->FldCaption());

			// no_of_hours
			$this->no_of_hours->EditAttrs["class"] = "form-control";
			$this->no_of_hours->EditCustomAttributes = "";
			$this->no_of_hours->EditValue = ew_HtmlEncode($this->no_of_hours->CurrentValue);
			$this->no_of_hours->PlaceHolder = ew_RemoveHtml($this->no_of_hours->FldCaption());

			// mobile_phone
			$this->mobile_phone->EditAttrs["class"] = "form-control";
			$this->mobile_phone->EditCustomAttributes = "";
			$this->mobile_phone->EditValue = ew_HtmlEncode($this->mobile_phone->CurrentValue);
			$this->mobile_phone->PlaceHolder = ew_RemoveHtml($this->mobile_phone->FldCaption());

			// pobox
			$this->pobox->EditAttrs["class"] = "form-control";
			$this->pobox->EditCustomAttributes = "";
			$this->pobox->EditValue = ew_HtmlEncode($this->pobox->CurrentValue);
			$this->pobox->PlaceHolder = ew_RemoveHtml($this->pobox->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->CurrentValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// email
			$this->email->EditAttrs["class"] = "form-control";
			$this->email->EditCustomAttributes = "";
			$this->email->EditValue = ew_HtmlEncode($this->email->CurrentValue);
			$this->email->PlaceHolder = ew_RemoveHtml($this->email->FldCaption());

			// Add refer script
			// institutions_id

			$this->institutions_id->LinkCustomAttributes = "";
			$this->institutions_id->HrefValue = "";

			// event_name
			$this->event_name->LinkCustomAttributes = "";
			$this->event_name->HrefValue = "";

			// event_emirate
			$this->event_emirate->LinkCustomAttributes = "";
			$this->event_emirate->HrefValue = "";

			// event_location
			$this->event_location->LinkCustomAttributes = "";
			$this->event_location->HrefValue = "";

			// activity_start_date
			$this->activity_start_date->LinkCustomAttributes = "";
			$this->activity_start_date->HrefValue = "";

			// activity_end_date
			$this->activity_end_date->LinkCustomAttributes = "";
			$this->activity_end_date->HrefValue = "";

			// activity_time
			$this->activity_time->LinkCustomAttributes = "";
			$this->activity_time->HrefValue = "";

			// activity_description
			$this->activity_description->LinkCustomAttributes = "";
			$this->activity_description->HrefValue = "";

			// activity_gender_target
			$this->activity_gender_target->LinkCustomAttributes = "";
			$this->activity_gender_target->HrefValue = "";

			// no_of_persons_needed
			$this->no_of_persons_needed->LinkCustomAttributes = "";
			$this->no_of_persons_needed->HrefValue = "";

			// no_of_hours
			$this->no_of_hours->LinkCustomAttributes = "";
			$this->no_of_hours->HrefValue = "";

			// mobile_phone
			$this->mobile_phone->LinkCustomAttributes = "";
			$this->mobile_phone->HrefValue = "";

			// pobox
			$this->pobox->LinkCustomAttributes = "";
			$this->pobox->HrefValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";

			// email
			$this->email->LinkCustomAttributes = "";
			$this->email->HrefValue = "";
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
		if (!$this->institutions_id->FldIsDetailKey && !is_null($this->institutions_id->FormValue) && $this->institutions_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->institutions_id->FldCaption(), $this->institutions_id->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->activity_start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->activity_start_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->activity_end_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->activity_end_date->FldErrMsg());
		}

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// institutions_id
		$this->institutions_id->SetDbValueDef($rsnew, $this->institutions_id->CurrentValue, 0, FALSE);

		// event_name
		$this->event_name->SetDbValueDef($rsnew, $this->event_name->CurrentValue, NULL, FALSE);

		// event_emirate
		$this->event_emirate->SetDbValueDef($rsnew, $this->event_emirate->CurrentValue, NULL, FALSE);

		// event_location
		$this->event_location->SetDbValueDef($rsnew, $this->event_location->CurrentValue, NULL, FALSE);

		// activity_start_date
		$this->activity_start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->activity_start_date->CurrentValue, 0), NULL, FALSE);

		// activity_end_date
		$this->activity_end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->activity_end_date->CurrentValue, 0), NULL, FALSE);

		// activity_time
		$this->activity_time->SetDbValueDef($rsnew, $this->activity_time->CurrentValue, NULL, FALSE);

		// activity_description
		$this->activity_description->SetDbValueDef($rsnew, $this->activity_description->CurrentValue, NULL, FALSE);

		// activity_gender_target
		$this->activity_gender_target->SetDbValueDef($rsnew, $this->activity_gender_target->CurrentValue, NULL, FALSE);

		// no_of_persons_needed
		$this->no_of_persons_needed->SetDbValueDef($rsnew, $this->no_of_persons_needed->CurrentValue, NULL, FALSE);

		// no_of_hours
		$this->no_of_hours->SetDbValueDef($rsnew, $this->no_of_hours->CurrentValue, NULL, FALSE);

		// mobile_phone
		$this->mobile_phone->SetDbValueDef($rsnew, $this->mobile_phone->CurrentValue, NULL, FALSE);

		// pobox
		$this->pobox->SetDbValueDef($rsnew, $this->pobox->CurrentValue, NULL, FALSE);

		// admin_approval
		$this->admin_approval->SetDbValueDef($rsnew, $this->admin_approval->CurrentValue, NULL, FALSE);

		// admin_comment
		$this->admin_comment->SetDbValueDef($rsnew, $this->admin_comment->CurrentValue, NULL, FALSE);

		// email
		$this->email->SetDbValueDef($rsnew, $this->email->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institutions_requestslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($institutions_requests_add)) $institutions_requests_add = new cinstitutions_requests_add();

// Page init
$institutions_requests_add->Page_Init();

// Page main
$institutions_requests_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_requests_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = finstitutions_requestsadd = new ew_Form("finstitutions_requestsadd", "add");

// Validate form
finstitutions_requestsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_institutions_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $institutions_requests->institutions_id->FldCaption(), $institutions_requests->institutions_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_activity_start_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions_requests->activity_start_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_activity_end_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions_requests->activity_end_date->FldErrMsg()) ?>");

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
finstitutions_requestsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutions_requestsadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstitutions_requestsadd.Lists["x_institutions_id"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
finstitutions_requestsadd.Lists["x_institutions_id"].Data = "<?php echo $institutions_requests_add->institutions_id->LookupFilterQuery(FALSE, "add") ?>";
finstitutions_requestsadd.Lists["x_event_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestsadd.Lists["x_event_emirate"].Options = <?php echo json_encode($institutions_requests_add->event_emirate->Options()) ?>;
finstitutions_requestsadd.Lists["x_activity_gender_target"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestsadd.Lists["x_activity_gender_target"].Options = <?php echo json_encode($institutions_requests_add->activity_gender_target->Options()) ?>;
finstitutions_requestsadd.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestsadd.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_requests_add->admin_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $institutions_requests_add->ShowPageHeader(); ?>
<?php
$institutions_requests_add->ShowMessage();
?>
<form name="finstitutions_requestsadd" id="finstitutions_requestsadd" class="<?php echo $institutions_requests_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_requests_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_requests_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions_requests">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($institutions_requests_add->IsModal) ?>">
<?php if (!$institutions_requests_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutions_requestsadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions_requests->institutions_id->Visible) { // institutions_id ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_institutions_id" class="form-group">
		<label id="elh_institutions_requests_institutions_id" for="x_institutions_id" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->institutions_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->institutions_id->CellAttributes() ?>>
<span id="el_institutions_requests_institutions_id">
<select data-table="institutions_requests" data-field="x_institutions_id" data-value-separator="<?php echo $institutions_requests->institutions_id->DisplayValueSeparatorAttribute() ?>" id="x_institutions_id" name="x_institutions_id"<?php echo $institutions_requests->institutions_id->EditAttributes() ?>>
<?php echo $institutions_requests->institutions_id->SelectOptionListHtml("x_institutions_id") ?>
</select>
</span>
<?php echo $institutions_requests->institutions_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_institutions_id">
		<td class="col-sm-2"><span id="elh_institutions_requests_institutions_id"><?php echo $institutions_requests->institutions_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $institutions_requests->institutions_id->CellAttributes() ?>>
<span id="el_institutions_requests_institutions_id">
<select data-table="institutions_requests" data-field="x_institutions_id" data-value-separator="<?php echo $institutions_requests->institutions_id->DisplayValueSeparatorAttribute() ?>" id="x_institutions_id" name="x_institutions_id"<?php echo $institutions_requests->institutions_id->EditAttributes() ?>>
<?php echo $institutions_requests->institutions_id->SelectOptionListHtml("x_institutions_id") ?>
</select>
</span>
<?php echo $institutions_requests->institutions_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_name->Visible) { // event_name ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_event_name" class="form-group">
		<label id="elh_institutions_requests_event_name" for="x_event_name" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->event_name->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->event_name->CellAttributes() ?>>
<span id="el_institutions_requests_event_name">
<input type="text" data-table="institutions_requests" data-field="x_event_name" name="x_event_name" id="x_event_name" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_name->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_name->EditValue ?>"<?php echo $institutions_requests->event_name->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->event_name->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_name">
		<td class="col-sm-2"><span id="elh_institutions_requests_event_name"><?php echo $institutions_requests->event_name->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->event_name->CellAttributes() ?>>
<span id="el_institutions_requests_event_name">
<input type="text" data-table="institutions_requests" data-field="x_event_name" name="x_event_name" id="x_event_name" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_name->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_name->EditValue ?>"<?php echo $institutions_requests->event_name->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->event_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_emirate->Visible) { // event_emirate ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_event_emirate" class="form-group">
		<label id="elh_institutions_requests_event_emirate" for="x_event_emirate" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->event_emirate->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->event_emirate->CellAttributes() ?>>
<span id="el_institutions_requests_event_emirate">
<select data-table="institutions_requests" data-field="x_event_emirate" data-value-separator="<?php echo $institutions_requests->event_emirate->DisplayValueSeparatorAttribute() ?>" id="x_event_emirate" name="x_event_emirate"<?php echo $institutions_requests->event_emirate->EditAttributes() ?>>
<?php echo $institutions_requests->event_emirate->SelectOptionListHtml("x_event_emirate") ?>
</select>
</span>
<?php echo $institutions_requests->event_emirate->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_emirate">
		<td class="col-sm-2"><span id="elh_institutions_requests_event_emirate"><?php echo $institutions_requests->event_emirate->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->event_emirate->CellAttributes() ?>>
<span id="el_institutions_requests_event_emirate">
<select data-table="institutions_requests" data-field="x_event_emirate" data-value-separator="<?php echo $institutions_requests->event_emirate->DisplayValueSeparatorAttribute() ?>" id="x_event_emirate" name="x_event_emirate"<?php echo $institutions_requests->event_emirate->EditAttributes() ?>>
<?php echo $institutions_requests->event_emirate->SelectOptionListHtml("x_event_emirate") ?>
</select>
</span>
<?php echo $institutions_requests->event_emirate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_location->Visible) { // event_location ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_event_location" class="form-group">
		<label id="elh_institutions_requests_event_location" for="x_event_location" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->event_location->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->event_location->CellAttributes() ?>>
<span id="el_institutions_requests_event_location">
<input type="text" data-table="institutions_requests" data-field="x_event_location" name="x_event_location" id="x_event_location" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_location->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_location->EditValue ?>"<?php echo $institutions_requests->event_location->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->event_location->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_location">
		<td class="col-sm-2"><span id="elh_institutions_requests_event_location"><?php echo $institutions_requests->event_location->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->event_location->CellAttributes() ?>>
<span id="el_institutions_requests_event_location">
<input type="text" data-table="institutions_requests" data-field="x_event_location" name="x_event_location" id="x_event_location" placeholder="<?php echo ew_HtmlEncode($institutions_requests->event_location->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->event_location->EditValue ?>"<?php echo $institutions_requests->event_location->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->event_location->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_start_date->Visible) { // activity_start_date ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_activity_start_date" class="form-group">
		<label id="elh_institutions_requests_activity_start_date" for="x_activity_start_date" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_start_date->CellAttributes() ?>>
<span id="el_institutions_requests_activity_start_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_start_date->EditValue ?>"<?php echo $institutions_requests->activity_start_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_start_date->ReadOnly && !$institutions_requests->activity_start_date->Disabled && !isset($institutions_requests->activity_start_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestsadd", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions_requests->activity_start_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_start_date">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_start_date"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->activity_start_date->CellAttributes() ?>>
<span id="el_institutions_requests_activity_start_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_start_date->EditValue ?>"<?php echo $institutions_requests->activity_start_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_start_date->ReadOnly && !$institutions_requests->activity_start_date->Disabled && !isset($institutions_requests->activity_start_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestsadd", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions_requests->activity_start_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_end_date->Visible) { // activity_end_date ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_activity_end_date" class="form-group">
		<label id="elh_institutions_requests_activity_end_date" for="x_activity_end_date" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_end_date->CellAttributes() ?>>
<span id="el_institutions_requests_activity_end_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_end_date->EditValue ?>"<?php echo $institutions_requests->activity_end_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_end_date->ReadOnly && !$institutions_requests->activity_end_date->Disabled && !isset($institutions_requests->activity_end_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestsadd", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions_requests->activity_end_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_end_date">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_end_date"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->activity_end_date->CellAttributes() ?>>
<span id="el_institutions_requests_activity_end_date">
<input type="text" data-table="institutions_requests" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->activity_end_date->EditValue ?>"<?php echo $institutions_requests->activity_end_date->EditAttributes() ?>>
<?php if (!$institutions_requests->activity_end_date->ReadOnly && !$institutions_requests->activity_end_date->Disabled && !isset($institutions_requests->activity_end_date->EditAttrs["readonly"]) && !isset($institutions_requests->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutions_requestsadd", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions_requests->activity_end_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_time->Visible) { // activity_time ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_activity_time" class="form-group">
		<label id="elh_institutions_requests_activity_time" for="x_activity_time" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->activity_time->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_time->CellAttributes() ?>>
<span id="el_institutions_requests_activity_time">
<textarea data-table="institutions_requests" data-field="x_activity_time" name="x_activity_time" id="x_activity_time" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_time->getPlaceHolder()) ?>"<?php echo $institutions_requests->activity_time->EditAttributes() ?>><?php echo $institutions_requests->activity_time->EditValue ?></textarea>
</span>
<?php echo $institutions_requests->activity_time->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_time">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_time"><?php echo $institutions_requests->activity_time->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->activity_time->CellAttributes() ?>>
<span id="el_institutions_requests_activity_time">
<textarea data-table="institutions_requests" data-field="x_activity_time" name="x_activity_time" id="x_activity_time" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_time->getPlaceHolder()) ?>"<?php echo $institutions_requests->activity_time->EditAttributes() ?>><?php echo $institutions_requests->activity_time->EditValue ?></textarea>
</span>
<?php echo $institutions_requests->activity_time->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_description->Visible) { // activity_description ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_activity_description" class="form-group">
		<label id="elh_institutions_requests_activity_description" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->activity_description->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_description->CellAttributes() ?>>
<span id="el_institutions_requests_activity_description">
<?php ew_AppendClass($institutions_requests->activity_description->EditAttrs["class"], "editor"); ?>
<textarea data-table="institutions_requests" data-field="x_activity_description" name="x_activity_description" id="x_activity_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_description->getPlaceHolder()) ?>"<?php echo $institutions_requests->activity_description->EditAttributes() ?>><?php echo $institutions_requests->activity_description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("finstitutions_requestsadd", "x_activity_description", 35, 4, <?php echo ($institutions_requests->activity_description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $institutions_requests->activity_description->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_description">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_description"><?php echo $institutions_requests->activity_description->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->activity_description->CellAttributes() ?>>
<span id="el_institutions_requests_activity_description">
<?php ew_AppendClass($institutions_requests->activity_description->EditAttrs["class"], "editor"); ?>
<textarea data-table="institutions_requests" data-field="x_activity_description" name="x_activity_description" id="x_activity_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->activity_description->getPlaceHolder()) ?>"<?php echo $institutions_requests->activity_description->EditAttributes() ?>><?php echo $institutions_requests->activity_description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("finstitutions_requestsadd", "x_activity_description", 35, 4, <?php echo ($institutions_requests->activity_description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $institutions_requests->activity_description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_gender_target->Visible) { // activity_gender_target ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_activity_gender_target" class="form-group">
		<label id="elh_institutions_requests_activity_gender_target" for="x_activity_gender_target" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->activity_gender_target->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->activity_gender_target->CellAttributes() ?>>
<span id="el_institutions_requests_activity_gender_target">
<select data-table="institutions_requests" data-field="x_activity_gender_target" data-value-separator="<?php echo $institutions_requests->activity_gender_target->DisplayValueSeparatorAttribute() ?>" id="x_activity_gender_target" name="x_activity_gender_target"<?php echo $institutions_requests->activity_gender_target->EditAttributes() ?>>
<?php echo $institutions_requests->activity_gender_target->SelectOptionListHtml("x_activity_gender_target") ?>
</select>
</span>
<?php echo $institutions_requests->activity_gender_target->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_gender_target">
		<td class="col-sm-2"><span id="elh_institutions_requests_activity_gender_target"><?php echo $institutions_requests->activity_gender_target->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->activity_gender_target->CellAttributes() ?>>
<span id="el_institutions_requests_activity_gender_target">
<select data-table="institutions_requests" data-field="x_activity_gender_target" data-value-separator="<?php echo $institutions_requests->activity_gender_target->DisplayValueSeparatorAttribute() ?>" id="x_activity_gender_target" name="x_activity_gender_target"<?php echo $institutions_requests->activity_gender_target->EditAttributes() ?>>
<?php echo $institutions_requests->activity_gender_target->SelectOptionListHtml("x_activity_gender_target") ?>
</select>
</span>
<?php echo $institutions_requests->activity_gender_target->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->no_of_persons_needed->Visible) { // no_of_persons_needed ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_no_of_persons_needed" class="form-group">
		<label id="elh_institutions_requests_no_of_persons_needed" for="x_no_of_persons_needed" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->no_of_persons_needed->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->no_of_persons_needed->CellAttributes() ?>>
<span id="el_institutions_requests_no_of_persons_needed">
<input type="text" data-table="institutions_requests" data-field="x_no_of_persons_needed" name="x_no_of_persons_needed" id="x_no_of_persons_needed" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_persons_needed->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_persons_needed->EditValue ?>"<?php echo $institutions_requests->no_of_persons_needed->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->no_of_persons_needed->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_no_of_persons_needed">
		<td class="col-sm-2"><span id="elh_institutions_requests_no_of_persons_needed"><?php echo $institutions_requests->no_of_persons_needed->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->no_of_persons_needed->CellAttributes() ?>>
<span id="el_institutions_requests_no_of_persons_needed">
<input type="text" data-table="institutions_requests" data-field="x_no_of_persons_needed" name="x_no_of_persons_needed" id="x_no_of_persons_needed" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_persons_needed->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_persons_needed->EditValue ?>"<?php echo $institutions_requests->no_of_persons_needed->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->no_of_persons_needed->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->no_of_hours->Visible) { // no_of_hours ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_no_of_hours" class="form-group">
		<label id="elh_institutions_requests_no_of_hours" for="x_no_of_hours" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->no_of_hours->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->no_of_hours->CellAttributes() ?>>
<span id="el_institutions_requests_no_of_hours">
<input type="text" data-table="institutions_requests" data-field="x_no_of_hours" name="x_no_of_hours" id="x_no_of_hours" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_hours->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_hours->EditValue ?>"<?php echo $institutions_requests->no_of_hours->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->no_of_hours->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_no_of_hours">
		<td class="col-sm-2"><span id="elh_institutions_requests_no_of_hours"><?php echo $institutions_requests->no_of_hours->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->no_of_hours->CellAttributes() ?>>
<span id="el_institutions_requests_no_of_hours">
<input type="text" data-table="institutions_requests" data-field="x_no_of_hours" name="x_no_of_hours" id="x_no_of_hours" placeholder="<?php echo ew_HtmlEncode($institutions_requests->no_of_hours->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->no_of_hours->EditValue ?>"<?php echo $institutions_requests->no_of_hours->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->no_of_hours->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->mobile_phone->Visible) { // mobile_phone ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_mobile_phone" class="form-group">
		<label id="elh_institutions_requests_mobile_phone" for="x_mobile_phone" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->mobile_phone->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->mobile_phone->CellAttributes() ?>>
<span id="el_institutions_requests_mobile_phone">
<input type="text" data-table="institutions_requests" data-field="x_mobile_phone" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions_requests->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->mobile_phone->EditValue ?>"<?php echo $institutions_requests->mobile_phone->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->mobile_phone->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_institutions_requests_mobile_phone"><?php echo $institutions_requests->mobile_phone->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->mobile_phone->CellAttributes() ?>>
<span id="el_institutions_requests_mobile_phone">
<input type="text" data-table="institutions_requests" data-field="x_mobile_phone" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions_requests->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->mobile_phone->EditValue ?>"<?php echo $institutions_requests->mobile_phone->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->mobile_phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->pobox->Visible) { // pobox ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_pobox" class="form-group">
		<label id="elh_institutions_requests_pobox" for="x_pobox" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->pobox->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->pobox->CellAttributes() ?>>
<span id="el_institutions_requests_pobox">
<input type="text" data-table="institutions_requests" data-field="x_pobox" name="x_pobox" id="x_pobox" placeholder="<?php echo ew_HtmlEncode($institutions_requests->pobox->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->pobox->EditValue ?>"<?php echo $institutions_requests->pobox->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->pobox->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_pobox">
		<td class="col-sm-2"><span id="elh_institutions_requests_pobox"><?php echo $institutions_requests->pobox->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->pobox->CellAttributes() ?>>
<span id="el_institutions_requests_pobox">
<input type="text" data-table="institutions_requests" data-field="x_pobox" name="x_pobox" id="x_pobox" placeholder="<?php echo ew_HtmlEncode($institutions_requests->pobox->getPlaceHolder()) ?>" value="<?php echo $institutions_requests->pobox->EditValue ?>"<?php echo $institutions_requests->pobox->EditAttributes() ?>>
</span>
<?php echo $institutions_requests->pobox->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->admin_approval->Visible) { // admin_approval ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label id="elh_institutions_requests_admin_approval" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->admin_approval->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->admin_approval->CellAttributes() ?>>
<span id="el_institutions_requests_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions_requests" data-field="x_admin_approval" data-value-separator="<?php echo $institutions_requests->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions_requests->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions_requests->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
<?php echo $institutions_requests->admin_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_institutions_requests_admin_approval"><?php echo $institutions_requests->admin_approval->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->admin_approval->CellAttributes() ?>>
<span id="el_institutions_requests_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions_requests" data-field="x_admin_approval" data-value-separator="<?php echo $institutions_requests->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions_requests->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions_requests->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
<?php echo $institutions_requests->admin_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->admin_comment->Visible) { // admin_comment ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label id="elh_institutions_requests_admin_comment" for="x_admin_comment" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->admin_comment->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->admin_comment->CellAttributes() ?>>
<span id="el_institutions_requests_admin_comment">
<textarea data-table="institutions_requests" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->admin_comment->getPlaceHolder()) ?>"<?php echo $institutions_requests->admin_comment->EditAttributes() ?>><?php echo $institutions_requests->admin_comment->EditValue ?></textarea>
</span>
<?php echo $institutions_requests->admin_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_institutions_requests_admin_comment"><?php echo $institutions_requests->admin_comment->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->admin_comment->CellAttributes() ?>>
<span id="el_institutions_requests_admin_comment">
<textarea data-table="institutions_requests" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->admin_comment->getPlaceHolder()) ?>"<?php echo $institutions_requests->admin_comment->EditAttributes() ?>><?php echo $institutions_requests->admin_comment->EditValue ?></textarea>
</span>
<?php echo $institutions_requests->admin_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests->email->Visible) { // email ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
	<div id="r_email" class="form-group">
		<label id="elh_institutions_requests_email" for="x_email" class="<?php echo $institutions_requests_add->LeftColumnClass ?>"><?php echo $institutions_requests->email->FldCaption() ?></label>
		<div class="<?php echo $institutions_requests_add->RightColumnClass ?>"><div<?php echo $institutions_requests->email->CellAttributes() ?>>
<span id="el_institutions_requests_email">
<textarea data-table="institutions_requests" data-field="x_email" name="x_email" id="x_email" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->email->getPlaceHolder()) ?>"<?php echo $institutions_requests->email->EditAttributes() ?>><?php echo $institutions_requests->email->EditValue ?></textarea>
</span>
<?php echo $institutions_requests->email->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_email">
		<td class="col-sm-2"><span id="elh_institutions_requests_email"><?php echo $institutions_requests->email->FldCaption() ?></span></td>
		<td<?php echo $institutions_requests->email->CellAttributes() ?>>
<span id="el_institutions_requests_email">
<textarea data-table="institutions_requests" data-field="x_email" name="x_email" id="x_email" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions_requests->email->getPlaceHolder()) ?>"<?php echo $institutions_requests->email->EditAttributes() ?>><?php echo $institutions_requests->email->EditValue ?></textarea>
</span>
<?php echo $institutions_requests->email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_requests_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$institutions_requests_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $institutions_requests_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $institutions_requests_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$institutions_requests_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
finstitutions_requestsadd.Init();
</script>
<?php
$institutions_requests_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$institutions_requests_add->Page_Terminate();
?>
