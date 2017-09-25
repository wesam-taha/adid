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

$institutions_add = NULL; // Initialize page object first

class cinstitutions_add extends cinstitutions {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions';

	// Page object name
	var $PageObjName = 'institutions_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;
	var $MultiPages; // Multi pages object

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
			if (@$_GET["institution_id"] != "") {
				$this->institution_id->setQueryStringValue($_GET["institution_id"]);
				$this->setKey("institution_id", $this->institution_id->CurrentValue); // Set up key
			} else {
				$this->setKey("institution_id", ""); // Clear key
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
					$this->Page_Terminate("institutionslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "institutionslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "institutionsview.php")
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
		$this->trade_licence->Upload->Index = $objForm->Index;
		$this->trade_licence->Upload->UploadFile();
		$this->trade_licence->CurrentValue = $this->trade_licence->Upload->FileName;
		$this->visa_copy->Upload->Index = $objForm->Index;
		$this->visa_copy->Upload->UploadFile();
		$this->visa_copy->CurrentValue = $this->visa_copy->Upload->FileName;
		$this->emirates_id_copy->Upload->Index = $objForm->Index;
		$this->emirates_id_copy->Upload->UploadFile();
		$this->emirates_id_copy->CurrentValue = $this->emirates_id_copy->Upload->FileName;
		$this->passport_copy->Upload->Index = $objForm->Index;
		$this->passport_copy->Upload->UploadFile();
		$this->passport_copy->CurrentValue = $this->passport_copy->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->full_name_ar->CurrentValue = NULL;
		$this->full_name_ar->OldValue = $this->full_name_ar->CurrentValue;
		$this->full_name_en->CurrentValue = NULL;
		$this->full_name_en->OldValue = $this->full_name_en->CurrentValue;
		$this->institution_type->CurrentValue = NULL;
		$this->institution_type->OldValue = $this->institution_type->CurrentValue;
		$this->institutes_name->CurrentValue = NULL;
		$this->institutes_name->OldValue = $this->institutes_name->CurrentValue;
		$this->volunteering_type->CurrentValue = NULL;
		$this->volunteering_type->OldValue = $this->volunteering_type->CurrentValue;
		$this->licence_no->CurrentValue = NULL;
		$this->licence_no->OldValue = $this->licence_no->CurrentValue;
		$this->trade_licence->Upload->DbValue = NULL;
		$this->trade_licence->OldValue = $this->trade_licence->Upload->DbValue;
		$this->trade_licence->CurrentValue = NULL; // Clear file related field
		$this->tl_expiry_date->CurrentValue = NULL;
		$this->tl_expiry_date->OldValue = $this->tl_expiry_date->CurrentValue;
		$this->nationality_type->CurrentValue = NULL;
		$this->nationality_type->OldValue = $this->nationality_type->CurrentValue;
		$this->nationality->CurrentValue = NULL;
		$this->nationality->OldValue = $this->nationality->CurrentValue;
		$this->visa_expiry_date->CurrentValue = NULL;
		$this->visa_expiry_date->OldValue = $this->visa_expiry_date->CurrentValue;
		$this->unid->CurrentValue = NULL;
		$this->unid->OldValue = $this->unid->CurrentValue;
		$this->visa_copy->Upload->DbValue = NULL;
		$this->visa_copy->OldValue = $this->visa_copy->Upload->DbValue;
		$this->visa_copy->CurrentValue = NULL; // Clear file related field
		$this->current_emirate->CurrentValue = NULL;
		$this->current_emirate->OldValue = $this->current_emirate->CurrentValue;
		$this->full_address->CurrentValue = NULL;
		$this->full_address->OldValue = $this->full_address->CurrentValue;
		$this->emirates_id_number->CurrentValue = NULL;
		$this->emirates_id_number->OldValue = $this->emirates_id_number->CurrentValue;
		$this->eid_expiry_date->CurrentValue = NULL;
		$this->eid_expiry_date->OldValue = $this->eid_expiry_date->CurrentValue;
		$this->emirates_id_copy->Upload->DbValue = NULL;
		$this->emirates_id_copy->OldValue = $this->emirates_id_copy->Upload->DbValue;
		$this->emirates_id_copy->CurrentValue = NULL; // Clear file related field
		$this->passport_number->CurrentValue = NULL;
		$this->passport_number->OldValue = $this->passport_number->CurrentValue;
		$this->passport_ex_date->CurrentValue = NULL;
		$this->passport_ex_date->OldValue = $this->passport_ex_date->CurrentValue;
		$this->passport_copy->Upload->DbValue = NULL;
		$this->passport_copy->OldValue = $this->passport_copy->Upload->DbValue;
		$this->passport_copy->CurrentValue = NULL; // Clear file related field
		$this->place_of_work->CurrentValue = NULL;
		$this->place_of_work->OldValue = $this->place_of_work->CurrentValue;
		$this->work_phone->CurrentValue = NULL;
		$this->work_phone->OldValue = $this->work_phone->CurrentValue;
		$this->mobile_phone->CurrentValue = NULL;
		$this->mobile_phone->OldValue = $this->mobile_phone->CurrentValue;
		$this->fax->CurrentValue = NULL;
		$this->fax->OldValue = $this->fax->CurrentValue;
		$this->pobbox->CurrentValue = NULL;
		$this->pobbox->OldValue = $this->pobbox->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->password->CurrentValue = NULL;
		$this->password->OldValue = $this->password->CurrentValue;
		$this->admin_approval->CurrentValue = NULL;
		$this->admin_approval->OldValue = $this->admin_approval->CurrentValue;
		$this->admin_comment->CurrentValue = NULL;
		$this->admin_comment->OldValue = $this->admin_comment->CurrentValue;
		$this->forward_to_dep->CurrentValue = NULL;
		$this->forward_to_dep->OldValue = $this->forward_to_dep->CurrentValue;
		$this->eco_department_approval->CurrentValue = NULL;
		$this->eco_department_approval->OldValue = $this->eco_department_approval->CurrentValue;
		$this->eco_departmnet_comment->CurrentValue = NULL;
		$this->eco_departmnet_comment->OldValue = $this->eco_departmnet_comment->CurrentValue;
		$this->security_approval->CurrentValue = NULL;
		$this->security_approval->OldValue = $this->security_approval->CurrentValue;
		$this->security_comment->CurrentValue = NULL;
		$this->security_comment->OldValue = $this->security_comment->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->full_name_ar->FldIsDetailKey) {
			$this->full_name_ar->setFormValue($objForm->GetValue("x_full_name_ar"));
		}
		if (!$this->full_name_en->FldIsDetailKey) {
			$this->full_name_en->setFormValue($objForm->GetValue("x_full_name_en"));
		}
		if (!$this->institution_type->FldIsDetailKey) {
			$this->institution_type->setFormValue($objForm->GetValue("x_institution_type"));
		}
		if (!$this->institutes_name->FldIsDetailKey) {
			$this->institutes_name->setFormValue($objForm->GetValue("x_institutes_name"));
		}
		if (!$this->volunteering_type->FldIsDetailKey) {
			$this->volunteering_type->setFormValue($objForm->GetValue("x_volunteering_type"));
		}
		if (!$this->licence_no->FldIsDetailKey) {
			$this->licence_no->setFormValue($objForm->GetValue("x_licence_no"));
		}
		if (!$this->tl_expiry_date->FldIsDetailKey) {
			$this->tl_expiry_date->setFormValue($objForm->GetValue("x_tl_expiry_date"));
			$this->tl_expiry_date->CurrentValue = ew_UnFormatDateTime($this->tl_expiry_date->CurrentValue, 0);
		}
		if (!$this->nationality_type->FldIsDetailKey) {
			$this->nationality_type->setFormValue($objForm->GetValue("x_nationality_type"));
		}
		if (!$this->nationality->FldIsDetailKey) {
			$this->nationality->setFormValue($objForm->GetValue("x_nationality"));
		}
		if (!$this->visa_expiry_date->FldIsDetailKey) {
			$this->visa_expiry_date->setFormValue($objForm->GetValue("x_visa_expiry_date"));
			$this->visa_expiry_date->CurrentValue = ew_UnFormatDateTime($this->visa_expiry_date->CurrentValue, 0);
		}
		if (!$this->unid->FldIsDetailKey) {
			$this->unid->setFormValue($objForm->GetValue("x_unid"));
		}
		if (!$this->current_emirate->FldIsDetailKey) {
			$this->current_emirate->setFormValue($objForm->GetValue("x_current_emirate"));
		}
		if (!$this->full_address->FldIsDetailKey) {
			$this->full_address->setFormValue($objForm->GetValue("x_full_address"));
		}
		if (!$this->emirates_id_number->FldIsDetailKey) {
			$this->emirates_id_number->setFormValue($objForm->GetValue("x_emirates_id_number"));
		}
		if (!$this->eid_expiry_date->FldIsDetailKey) {
			$this->eid_expiry_date->setFormValue($objForm->GetValue("x_eid_expiry_date"));
			$this->eid_expiry_date->CurrentValue = ew_UnFormatDateTime($this->eid_expiry_date->CurrentValue, 0);
		}
		if (!$this->passport_number->FldIsDetailKey) {
			$this->passport_number->setFormValue($objForm->GetValue("x_passport_number"));
		}
		if (!$this->passport_ex_date->FldIsDetailKey) {
			$this->passport_ex_date->setFormValue($objForm->GetValue("x_passport_ex_date"));
			$this->passport_ex_date->CurrentValue = ew_UnFormatDateTime($this->passport_ex_date->CurrentValue, 0);
		}
		if (!$this->place_of_work->FldIsDetailKey) {
			$this->place_of_work->setFormValue($objForm->GetValue("x_place_of_work"));
		}
		if (!$this->work_phone->FldIsDetailKey) {
			$this->work_phone->setFormValue($objForm->GetValue("x_work_phone"));
		}
		if (!$this->mobile_phone->FldIsDetailKey) {
			$this->mobile_phone->setFormValue($objForm->GetValue("x_mobile_phone"));
		}
		if (!$this->fax->FldIsDetailKey) {
			$this->fax->setFormValue($objForm->GetValue("x_fax"));
		}
		if (!$this->pobbox->FldIsDetailKey) {
			$this->pobbox->setFormValue($objForm->GetValue("x_pobbox"));
		}
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->password->FldIsDetailKey) {
			$this->password->setFormValue($objForm->GetValue("x_password"));
		}
		if (!$this->admin_approval->FldIsDetailKey) {
			$this->admin_approval->setFormValue($objForm->GetValue("x_admin_approval"));
		}
		if (!$this->admin_comment->FldIsDetailKey) {
			$this->admin_comment->setFormValue($objForm->GetValue("x_admin_comment"));
		}
		if (!$this->forward_to_dep->FldIsDetailKey) {
			$this->forward_to_dep->setFormValue($objForm->GetValue("x_forward_to_dep"));
		}
		if (!$this->eco_department_approval->FldIsDetailKey) {
			$this->eco_department_approval->setFormValue($objForm->GetValue("x_eco_department_approval"));
		}
		if (!$this->eco_departmnet_comment->FldIsDetailKey) {
			$this->eco_departmnet_comment->setFormValue($objForm->GetValue("x_eco_departmnet_comment"));
		}
		if (!$this->security_approval->FldIsDetailKey) {
			$this->security_approval->setFormValue($objForm->GetValue("x_security_approval"));
		}
		if (!$this->security_comment->FldIsDetailKey) {
			$this->security_comment->setFormValue($objForm->GetValue("x_security_comment"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->full_name_ar->CurrentValue = $this->full_name_ar->FormValue;
		$this->full_name_en->CurrentValue = $this->full_name_en->FormValue;
		$this->institution_type->CurrentValue = $this->institution_type->FormValue;
		$this->institutes_name->CurrentValue = $this->institutes_name->FormValue;
		$this->volunteering_type->CurrentValue = $this->volunteering_type->FormValue;
		$this->licence_no->CurrentValue = $this->licence_no->FormValue;
		$this->tl_expiry_date->CurrentValue = $this->tl_expiry_date->FormValue;
		$this->tl_expiry_date->CurrentValue = ew_UnFormatDateTime($this->tl_expiry_date->CurrentValue, 0);
		$this->nationality_type->CurrentValue = $this->nationality_type->FormValue;
		$this->nationality->CurrentValue = $this->nationality->FormValue;
		$this->visa_expiry_date->CurrentValue = $this->visa_expiry_date->FormValue;
		$this->visa_expiry_date->CurrentValue = ew_UnFormatDateTime($this->visa_expiry_date->CurrentValue, 0);
		$this->unid->CurrentValue = $this->unid->FormValue;
		$this->current_emirate->CurrentValue = $this->current_emirate->FormValue;
		$this->full_address->CurrentValue = $this->full_address->FormValue;
		$this->emirates_id_number->CurrentValue = $this->emirates_id_number->FormValue;
		$this->eid_expiry_date->CurrentValue = $this->eid_expiry_date->FormValue;
		$this->eid_expiry_date->CurrentValue = ew_UnFormatDateTime($this->eid_expiry_date->CurrentValue, 0);
		$this->passport_number->CurrentValue = $this->passport_number->FormValue;
		$this->passport_ex_date->CurrentValue = $this->passport_ex_date->FormValue;
		$this->passport_ex_date->CurrentValue = ew_UnFormatDateTime($this->passport_ex_date->CurrentValue, 0);
		$this->place_of_work->CurrentValue = $this->place_of_work->FormValue;
		$this->work_phone->CurrentValue = $this->work_phone->FormValue;
		$this->mobile_phone->CurrentValue = $this->mobile_phone->FormValue;
		$this->fax->CurrentValue = $this->fax->FormValue;
		$this->pobbox->CurrentValue = $this->pobbox->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->admin_approval->CurrentValue = $this->admin_approval->FormValue;
		$this->admin_comment->CurrentValue = $this->admin_comment->FormValue;
		$this->forward_to_dep->CurrentValue = $this->forward_to_dep->FormValue;
		$this->eco_department_approval->CurrentValue = $this->eco_department_approval->FormValue;
		$this->eco_departmnet_comment->CurrentValue = $this->eco_departmnet_comment->FormValue;
		$this->security_approval->CurrentValue = $this->security_approval->FormValue;
		$this->security_comment->CurrentValue = $this->security_comment->FormValue;
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
		$this->institution_id->setDbValue($row['institution_id']);
		$this->full_name_ar->setDbValue($row['full_name_ar']);
		$this->full_name_en->setDbValue($row['full_name_en']);
		$this->institution_type->setDbValue($row['institution_type']);
		$this->institutes_name->setDbValue($row['institutes_name']);
		$this->volunteering_type->setDbValue($row['volunteering_type']);
		$this->licence_no->setDbValue($row['licence_no']);
		$this->trade_licence->Upload->DbValue = $row['trade_licence'];
		$this->trade_licence->CurrentValue = $this->trade_licence->Upload->DbValue;
		$this->tl_expiry_date->setDbValue($row['tl_expiry_date']);
		$this->nationality_type->setDbValue($row['nationality_type']);
		$this->nationality->setDbValue($row['nationality']);
		$this->visa_expiry_date->setDbValue($row['visa_expiry_date']);
		$this->unid->setDbValue($row['unid']);
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
		$this->work_phone->setDbValue($row['work_phone']);
		$this->mobile_phone->setDbValue($row['mobile_phone']);
		$this->fax->setDbValue($row['fax']);
		$this->pobbox->setDbValue($row['pobbox']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->admin_approval->setDbValue($row['admin_approval']);
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->forward_to_dep->setDbValue($row['forward_to_dep']);
		$this->eco_department_approval->setDbValue($row['eco_department_approval']);
		$this->eco_departmnet_comment->setDbValue($row['eco_departmnet_comment']);
		$this->security_approval->setDbValue($row['security_approval']);
		$this->security_comment->setDbValue($row['security_comment']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['institution_id'] = NULL;
		$row['full_name_ar'] = NULL;
		$row['full_name_en'] = NULL;
		$row['institution_type'] = NULL;
		$row['institutes_name'] = NULL;
		$row['volunteering_type'] = NULL;
		$row['licence_no'] = NULL;
		$row['trade_licence'] = NULL;
		$row['tl_expiry_date'] = NULL;
		$row['nationality_type'] = NULL;
		$row['nationality'] = NULL;
		$row['visa_expiry_date'] = NULL;
		$row['unid'] = NULL;
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
		$row['work_phone'] = NULL;
		$row['mobile_phone'] = NULL;
		$row['fax'] = NULL;
		$row['pobbox'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['admin_approval'] = NULL;
		$row['admin_comment'] = NULL;
		$row['forward_to_dep'] = NULL;
		$row['eco_department_approval'] = NULL;
		$row['eco_departmnet_comment'] = NULL;
		$row['security_approval'] = NULL;
		$row['security_comment'] = NULL;
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
		$this->institution_id->DbValue = $row['institution_id'];
		$this->full_name_ar->DbValue = $row['full_name_ar'];
		$this->full_name_en->DbValue = $row['full_name_en'];
		$this->institution_type->DbValue = $row['institution_type'];
		$this->institutes_name->DbValue = $row['institutes_name'];
		$this->volunteering_type->DbValue = $row['volunteering_type'];
		$this->licence_no->DbValue = $row['licence_no'];
		$this->trade_licence->Upload->DbValue = $row['trade_licence'];
		$this->tl_expiry_date->DbValue = $row['tl_expiry_date'];
		$this->nationality_type->DbValue = $row['nationality_type'];
		$this->nationality->DbValue = $row['nationality'];
		$this->visa_expiry_date->DbValue = $row['visa_expiry_date'];
		$this->unid->DbValue = $row['unid'];
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
		$this->work_phone->DbValue = $row['work_phone'];
		$this->mobile_phone->DbValue = $row['mobile_phone'];
		$this->fax->DbValue = $row['fax'];
		$this->pobbox->DbValue = $row['pobbox'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->admin_approval->DbValue = $row['admin_approval'];
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->forward_to_dep->DbValue = $row['forward_to_dep'];
		$this->eco_department_approval->DbValue = $row['eco_department_approval'];
		$this->eco_departmnet_comment->DbValue = $row['eco_departmnet_comment'];
		$this->security_approval->DbValue = $row['security_approval'];
		$this->security_comment->DbValue = $row['security_comment'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("institution_id")) <> "")
			$this->institution_id->CurrentValue = $this->getKey("institution_id"); // institution_id
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// full_name_ar
			$this->full_name_ar->EditAttrs["class"] = "form-control";
			$this->full_name_ar->EditCustomAttributes = "";
			$this->full_name_ar->EditValue = ew_HtmlEncode($this->full_name_ar->CurrentValue);
			$this->full_name_ar->PlaceHolder = ew_RemoveHtml($this->full_name_ar->FldCaption());

			// full_name_en
			$this->full_name_en->EditAttrs["class"] = "form-control";
			$this->full_name_en->EditCustomAttributes = "";
			$this->full_name_en->EditValue = ew_HtmlEncode($this->full_name_en->CurrentValue);
			$this->full_name_en->PlaceHolder = ew_RemoveHtml($this->full_name_en->FldCaption());

			// institution_type
			$this->institution_type->EditCustomAttributes = "";
			$this->institution_type->EditValue = $this->institution_type->Options(FALSE);

			// institutes_name
			$this->institutes_name->EditAttrs["class"] = "form-control";
			$this->institutes_name->EditCustomAttributes = "";
			$this->institutes_name->EditValue = ew_HtmlEncode($this->institutes_name->CurrentValue);
			$this->institutes_name->PlaceHolder = ew_RemoveHtml($this->institutes_name->FldCaption());

			// volunteering_type
			$this->volunteering_type->EditCustomAttributes = "";
			$this->volunteering_type->EditValue = $this->volunteering_type->Options(FALSE);

			// licence_no
			$this->licence_no->EditAttrs["class"] = "form-control";
			$this->licence_no->EditCustomAttributes = "";
			$this->licence_no->EditValue = ew_HtmlEncode($this->licence_no->CurrentValue);
			$this->licence_no->PlaceHolder = ew_RemoveHtml($this->licence_no->FldCaption());

			// trade_licence
			$this->trade_licence->EditAttrs["class"] = "form-control";
			$this->trade_licence->EditCustomAttributes = "";
			$this->trade_licence->UploadPath = "../images";
			if (!ew_Empty($this->trade_licence->Upload->DbValue)) {
				$this->trade_licence->ImageWidth = 300;
				$this->trade_licence->ImageHeight = 0;
				$this->trade_licence->ImageAlt = $this->trade_licence->FldAlt();
				$this->trade_licence->EditValue = $this->trade_licence->Upload->DbValue;
			} else {
				$this->trade_licence->EditValue = "";
			}
			if (!ew_Empty($this->trade_licence->CurrentValue))
				$this->trade_licence->Upload->FileName = $this->trade_licence->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->trade_licence);

			// tl_expiry_date
			$this->tl_expiry_date->EditAttrs["class"] = "form-control";
			$this->tl_expiry_date->EditCustomAttributes = "";
			$this->tl_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tl_expiry_date->CurrentValue, 8));
			$this->tl_expiry_date->PlaceHolder = ew_RemoveHtml($this->tl_expiry_date->FldCaption());

			// nationality_type
			$this->nationality_type->EditCustomAttributes = "";
			$this->nationality_type->EditValue = $this->nationality_type->Options(FALSE);

			// nationality
			$this->nationality->EditAttrs["class"] = "form-control";
			$this->nationality->EditCustomAttributes = "";
			$this->nationality->EditValue = ew_HtmlEncode($this->nationality->CurrentValue);
			$this->nationality->PlaceHolder = ew_RemoveHtml($this->nationality->FldCaption());

			// visa_expiry_date
			$this->visa_expiry_date->EditAttrs["class"] = "form-control";
			$this->visa_expiry_date->EditCustomAttributes = "";
			$this->visa_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->visa_expiry_date->CurrentValue, 8));
			$this->visa_expiry_date->PlaceHolder = ew_RemoveHtml($this->visa_expiry_date->FldCaption());

			// unid
			$this->unid->EditAttrs["class"] = "form-control";
			$this->unid->EditCustomAttributes = "";
			$this->unid->EditValue = ew_HtmlEncode($this->unid->CurrentValue);
			$this->unid->PlaceHolder = ew_RemoveHtml($this->unid->FldCaption());

			// visa_copy
			$this->visa_copy->EditAttrs["class"] = "form-control";
			$this->visa_copy->EditCustomAttributes = "";
			$this->visa_copy->UploadPath = "../images";
			if (!ew_Empty($this->visa_copy->Upload->DbValue)) {
				$this->visa_copy->ImageWidth = 300;
				$this->visa_copy->ImageHeight = 0;
				$this->visa_copy->ImageAlt = $this->visa_copy->FldAlt();
				$this->visa_copy->EditValue = $this->visa_copy->Upload->DbValue;
			} else {
				$this->visa_copy->EditValue = "";
			}
			if (!ew_Empty($this->visa_copy->CurrentValue))
				$this->visa_copy->Upload->FileName = $this->visa_copy->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->visa_copy);

			// current_emirate
			$this->current_emirate->EditAttrs["class"] = "form-control";
			$this->current_emirate->EditCustomAttributes = "";
			$this->current_emirate->EditValue = $this->current_emirate->Options(TRUE);

			// full_address
			$this->full_address->EditAttrs["class"] = "form-control";
			$this->full_address->EditCustomAttributes = "";
			$this->full_address->EditValue = ew_HtmlEncode($this->full_address->CurrentValue);
			$this->full_address->PlaceHolder = ew_RemoveHtml($this->full_address->FldCaption());

			// emirates_id_number
			$this->emirates_id_number->EditAttrs["class"] = "form-control";
			$this->emirates_id_number->EditCustomAttributes = "";
			$this->emirates_id_number->EditValue = ew_HtmlEncode($this->emirates_id_number->CurrentValue);
			$this->emirates_id_number->PlaceHolder = ew_RemoveHtml($this->emirates_id_number->FldCaption());

			// eid_expiry_date
			$this->eid_expiry_date->EditAttrs["class"] = "form-control";
			$this->eid_expiry_date->EditCustomAttributes = "";
			$this->eid_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->eid_expiry_date->CurrentValue, 8));
			$this->eid_expiry_date->PlaceHolder = ew_RemoveHtml($this->eid_expiry_date->FldCaption());

			// emirates_id_copy
			$this->emirates_id_copy->EditAttrs["class"] = "form-control";
			$this->emirates_id_copy->EditCustomAttributes = "";
			$this->emirates_id_copy->UploadPath = "../images";
			if (!ew_Empty($this->emirates_id_copy->Upload->DbValue)) {
				$this->emirates_id_copy->ImageWidth = 300;
				$this->emirates_id_copy->ImageHeight = 0;
				$this->emirates_id_copy->ImageAlt = $this->emirates_id_copy->FldAlt();
				$this->emirates_id_copy->EditValue = $this->emirates_id_copy->Upload->DbValue;
			} else {
				$this->emirates_id_copy->EditValue = "";
			}
			if (!ew_Empty($this->emirates_id_copy->CurrentValue))
				$this->emirates_id_copy->Upload->FileName = $this->emirates_id_copy->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->emirates_id_copy);

			// passport_number
			$this->passport_number->EditAttrs["class"] = "form-control";
			$this->passport_number->EditCustomAttributes = "";
			$this->passport_number->EditValue = ew_HtmlEncode($this->passport_number->CurrentValue);
			$this->passport_number->PlaceHolder = ew_RemoveHtml($this->passport_number->FldCaption());

			// passport_ex_date
			$this->passport_ex_date->EditAttrs["class"] = "form-control";
			$this->passport_ex_date->EditCustomAttributes = "";
			$this->passport_ex_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->passport_ex_date->CurrentValue, 8));
			$this->passport_ex_date->PlaceHolder = ew_RemoveHtml($this->passport_ex_date->FldCaption());

			// passport_copy
			$this->passport_copy->EditAttrs["class"] = "form-control";
			$this->passport_copy->EditCustomAttributes = "";
			$this->passport_copy->UploadPath = "../images";
			if (!ew_Empty($this->passport_copy->Upload->DbValue)) {
				$this->passport_copy->ImageWidth = 300;
				$this->passport_copy->ImageHeight = 0;
				$this->passport_copy->ImageAlt = $this->passport_copy->FldAlt();
				$this->passport_copy->EditValue = $this->passport_copy->Upload->DbValue;
			} else {
				$this->passport_copy->EditValue = "";
			}
			if (!ew_Empty($this->passport_copy->CurrentValue))
				$this->passport_copy->Upload->FileName = $this->passport_copy->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->passport_copy);

			// place_of_work
			$this->place_of_work->EditAttrs["class"] = "form-control";
			$this->place_of_work->EditCustomAttributes = "";
			$this->place_of_work->EditValue = ew_HtmlEncode($this->place_of_work->CurrentValue);
			$this->place_of_work->PlaceHolder = ew_RemoveHtml($this->place_of_work->FldCaption());

			// work_phone
			$this->work_phone->EditAttrs["class"] = "form-control";
			$this->work_phone->EditCustomAttributes = "";
			$this->work_phone->EditValue = ew_HtmlEncode($this->work_phone->CurrentValue);
			$this->work_phone->PlaceHolder = ew_RemoveHtml($this->work_phone->FldCaption());

			// mobile_phone
			$this->mobile_phone->EditAttrs["class"] = "form-control";
			$this->mobile_phone->EditCustomAttributes = "";
			$this->mobile_phone->EditValue = ew_HtmlEncode($this->mobile_phone->CurrentValue);
			$this->mobile_phone->PlaceHolder = ew_RemoveHtml($this->mobile_phone->FldCaption());

			// fax
			$this->fax->EditAttrs["class"] = "form-control";
			$this->fax->EditCustomAttributes = "";
			$this->fax->EditValue = ew_HtmlEncode($this->fax->CurrentValue);
			$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

			// pobbox
			$this->pobbox->EditAttrs["class"] = "form-control";
			$this->pobbox->EditCustomAttributes = "";
			$this->pobbox->EditValue = ew_HtmlEncode($this->pobbox->CurrentValue);
			$this->pobbox->PlaceHolder = ew_RemoveHtml($this->pobbox->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// password
			$this->password->EditAttrs["class"] = "form-control";
			$this->password->EditCustomAttributes = "";
			$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
			$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->CurrentValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// forward_to_dep
			$this->forward_to_dep->EditAttrs["class"] = "form-control";
			$this->forward_to_dep->EditCustomAttributes = "";
			if (trim(strval($this->forward_to_dep->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->forward_to_dep->CurrentValue, EW_DATATYPE_NUMBER, "");
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
			$this->eco_departmnet_comment->EditValue = ew_HtmlEncode($this->eco_departmnet_comment->CurrentValue);
			$this->eco_departmnet_comment->PlaceHolder = ew_RemoveHtml($this->eco_departmnet_comment->FldCaption());

			// security_approval
			$this->security_approval->EditCustomAttributes = "";
			$this->security_approval->EditValue = $this->security_approval->Options(FALSE);

			// security_comment
			$this->security_comment->EditAttrs["class"] = "form-control";
			$this->security_comment->EditCustomAttributes = "";
			$this->security_comment->EditValue = ew_HtmlEncode($this->security_comment->CurrentValue);
			$this->security_comment->PlaceHolder = ew_RemoveHtml($this->security_comment->FldCaption());

			// Add refer script
			// full_name_ar

			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";

			// institution_type
			$this->institution_type->LinkCustomAttributes = "";
			$this->institution_type->HrefValue = "";

			// institutes_name
			$this->institutes_name->LinkCustomAttributes = "";
			$this->institutes_name->HrefValue = "";

			// volunteering_type
			$this->volunteering_type->LinkCustomAttributes = "";
			$this->volunteering_type->HrefValue = "";

			// licence_no
			$this->licence_no->LinkCustomAttributes = "";
			$this->licence_no->HrefValue = "";

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

			// tl_expiry_date
			$this->tl_expiry_date->LinkCustomAttributes = "";
			$this->tl_expiry_date->HrefValue = "";

			// nationality_type
			$this->nationality_type->LinkCustomAttributes = "";
			$this->nationality_type->HrefValue = "";

			// nationality
			$this->nationality->LinkCustomAttributes = "";
			$this->nationality->HrefValue = "";

			// visa_expiry_date
			$this->visa_expiry_date->LinkCustomAttributes = "";
			$this->visa_expiry_date->HrefValue = "";

			// unid
			$this->unid->LinkCustomAttributes = "";
			$this->unid->HrefValue = "";

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

			// current_emirate
			$this->current_emirate->LinkCustomAttributes = "";
			$this->current_emirate->HrefValue = "";

			// full_address
			$this->full_address->LinkCustomAttributes = "";
			$this->full_address->HrefValue = "";

			// emirates_id_number
			$this->emirates_id_number->LinkCustomAttributes = "";
			$this->emirates_id_number->HrefValue = "";

			// eid_expiry_date
			$this->eid_expiry_date->LinkCustomAttributes = "";
			$this->eid_expiry_date->HrefValue = "";

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

			// passport_number
			$this->passport_number->LinkCustomAttributes = "";
			$this->passport_number->HrefValue = "";

			// passport_ex_date
			$this->passport_ex_date->LinkCustomAttributes = "";
			$this->passport_ex_date->HrefValue = "";

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

			// place_of_work
			$this->place_of_work->LinkCustomAttributes = "";
			$this->place_of_work->HrefValue = "";

			// work_phone
			$this->work_phone->LinkCustomAttributes = "";
			$this->work_phone->HrefValue = "";

			// mobile_phone
			$this->mobile_phone->LinkCustomAttributes = "";
			$this->mobile_phone->HrefValue = "";

			// fax
			$this->fax->LinkCustomAttributes = "";
			$this->fax->HrefValue = "";

			// pobbox
			$this->pobbox->LinkCustomAttributes = "";
			$this->pobbox->HrefValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";

			// password
			$this->password->LinkCustomAttributes = "";
			$this->password->HrefValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";

			// forward_to_dep
			$this->forward_to_dep->LinkCustomAttributes = "";
			$this->forward_to_dep->HrefValue = "";

			// eco_department_approval
			$this->eco_department_approval->LinkCustomAttributes = "";
			$this->eco_department_approval->HrefValue = "";

			// eco_departmnet_comment
			$this->eco_departmnet_comment->LinkCustomAttributes = "";
			$this->eco_departmnet_comment->HrefValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";

			// security_comment
			$this->security_comment->LinkCustomAttributes = "";
			$this->security_comment->HrefValue = "";
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
		if (!ew_CheckDateDef($this->tl_expiry_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->tl_expiry_date->FldErrMsg());
		}
		if ($this->nationality_type->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nationality_type->FldCaption(), $this->nationality_type->ReqErrMsg));
		}
		if (!$this->visa_expiry_date->FldIsDetailKey && !is_null($this->visa_expiry_date->FormValue) && $this->visa_expiry_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->visa_expiry_date->FldCaption(), $this->visa_expiry_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->visa_expiry_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->visa_expiry_date->FldErrMsg());
		}
		if (!$this->unid->FldIsDetailKey && !is_null($this->unid->FormValue) && $this->unid->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->unid->FldCaption(), $this->unid->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->unid->FormValue)) {
			ew_AddMessage($gsFormError, $this->unid->FldErrMsg());
		}
		if ($this->visa_copy->Upload->FileName == "" && !$this->visa_copy->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->visa_copy->FldCaption(), $this->visa_copy->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->eid_expiry_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->eid_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->passport_ex_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->passport_ex_date->FldErrMsg());
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
			$this->trade_licence->OldUploadPath = "../images";
			$this->trade_licence->UploadPath = $this->trade_licence->OldUploadPath;
			$this->visa_copy->OldUploadPath = "../images";
			$this->visa_copy->UploadPath = $this->visa_copy->OldUploadPath;
			$this->emirates_id_copy->OldUploadPath = "../images";
			$this->emirates_id_copy->UploadPath = $this->emirates_id_copy->OldUploadPath;
			$this->passport_copy->OldUploadPath = "../images";
			$this->passport_copy->UploadPath = $this->passport_copy->OldUploadPath;
		}
		$rsnew = array();

		// full_name_ar
		$this->full_name_ar->SetDbValueDef($rsnew, $this->full_name_ar->CurrentValue, NULL, FALSE);

		// full_name_en
		$this->full_name_en->SetDbValueDef($rsnew, $this->full_name_en->CurrentValue, NULL, FALSE);

		// institution_type
		$this->institution_type->SetDbValueDef($rsnew, $this->institution_type->CurrentValue, NULL, FALSE);

		// institutes_name
		$this->institutes_name->SetDbValueDef($rsnew, $this->institutes_name->CurrentValue, NULL, FALSE);

		// volunteering_type
		$this->volunteering_type->SetDbValueDef($rsnew, $this->volunteering_type->CurrentValue, NULL, FALSE);

		// licence_no
		$this->licence_no->SetDbValueDef($rsnew, $this->licence_no->CurrentValue, NULL, FALSE);

		// trade_licence
		if ($this->trade_licence->Visible && !$this->trade_licence->Upload->KeepFile) {
			$this->trade_licence->Upload->DbValue = ""; // No need to delete old file
			if ($this->trade_licence->Upload->FileName == "") {
				$rsnew['trade_licence'] = NULL;
			} else {
				$rsnew['trade_licence'] = $this->trade_licence->Upload->FileName;
			}
		}

		// tl_expiry_date
		$this->tl_expiry_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tl_expiry_date->CurrentValue, 0), NULL, FALSE);

		// nationality_type
		$this->nationality_type->SetDbValueDef($rsnew, $this->nationality_type->CurrentValue, "", FALSE);

		// nationality
		$this->nationality->SetDbValueDef($rsnew, $this->nationality->CurrentValue, NULL, FALSE);

		// visa_expiry_date
		$this->visa_expiry_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->visa_expiry_date->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// unid
		$this->unid->SetDbValueDef($rsnew, $this->unid->CurrentValue, 0, FALSE);

		// visa_copy
		if ($this->visa_copy->Visible && !$this->visa_copy->Upload->KeepFile) {
			$this->visa_copy->Upload->DbValue = ""; // No need to delete old file
			if ($this->visa_copy->Upload->FileName == "") {
				$rsnew['visa_copy'] = NULL;
			} else {
				$rsnew['visa_copy'] = $this->visa_copy->Upload->FileName;
			}
		}

		// current_emirate
		$this->current_emirate->SetDbValueDef($rsnew, $this->current_emirate->CurrentValue, NULL, FALSE);

		// full_address
		$this->full_address->SetDbValueDef($rsnew, $this->full_address->CurrentValue, NULL, FALSE);

		// emirates_id_number
		$this->emirates_id_number->SetDbValueDef($rsnew, $this->emirates_id_number->CurrentValue, NULL, FALSE);

		// eid_expiry_date
		$this->eid_expiry_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->eid_expiry_date->CurrentValue, 0), NULL, FALSE);

		// emirates_id_copy
		if ($this->emirates_id_copy->Visible && !$this->emirates_id_copy->Upload->KeepFile) {
			$this->emirates_id_copy->Upload->DbValue = ""; // No need to delete old file
			if ($this->emirates_id_copy->Upload->FileName == "") {
				$rsnew['emirates_id_copy'] = NULL;
			} else {
				$rsnew['emirates_id_copy'] = $this->emirates_id_copy->Upload->FileName;
			}
		}

		// passport_number
		$this->passport_number->SetDbValueDef($rsnew, $this->passport_number->CurrentValue, NULL, FALSE);

		// passport_ex_date
		$this->passport_ex_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->passport_ex_date->CurrentValue, 0), NULL, FALSE);

		// passport_copy
		if ($this->passport_copy->Visible && !$this->passport_copy->Upload->KeepFile) {
			$this->passport_copy->Upload->DbValue = ""; // No need to delete old file
			if ($this->passport_copy->Upload->FileName == "") {
				$rsnew['passport_copy'] = NULL;
			} else {
				$rsnew['passport_copy'] = $this->passport_copy->Upload->FileName;
			}
		}

		// place_of_work
		$this->place_of_work->SetDbValueDef($rsnew, $this->place_of_work->CurrentValue, NULL, FALSE);

		// work_phone
		$this->work_phone->SetDbValueDef($rsnew, $this->work_phone->CurrentValue, NULL, FALSE);

		// mobile_phone
		$this->mobile_phone->SetDbValueDef($rsnew, $this->mobile_phone->CurrentValue, NULL, FALSE);

		// fax
		$this->fax->SetDbValueDef($rsnew, $this->fax->CurrentValue, NULL, FALSE);

		// pobbox
		$this->pobbox->SetDbValueDef($rsnew, $this->pobbox->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// password
		$this->password->SetDbValueDef($rsnew, $this->password->CurrentValue, NULL, FALSE);

		// admin_approval
		$this->admin_approval->SetDbValueDef($rsnew, $this->admin_approval->CurrentValue, NULL, FALSE);

		// admin_comment
		$this->admin_comment->SetDbValueDef($rsnew, $this->admin_comment->CurrentValue, NULL, FALSE);

		// forward_to_dep
		$this->forward_to_dep->SetDbValueDef($rsnew, $this->forward_to_dep->CurrentValue, NULL, FALSE);

		// eco_department_approval
		$this->eco_department_approval->SetDbValueDef($rsnew, $this->eco_department_approval->CurrentValue, NULL, FALSE);

		// eco_departmnet_comment
		$this->eco_departmnet_comment->SetDbValueDef($rsnew, $this->eco_departmnet_comment->CurrentValue, NULL, FALSE);

		// security_approval
		$this->security_approval->SetDbValueDef($rsnew, $this->security_approval->CurrentValue, NULL, FALSE);

		// security_comment
		$this->security_comment->SetDbValueDef($rsnew, $this->security_comment->CurrentValue, NULL, FALSE);
		if ($this->trade_licence->Visible && !$this->trade_licence->Upload->KeepFile) {
			$this->trade_licence->UploadPath = "../images";
			$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->trade_licence->Upload->DbValue);
			if (!ew_Empty($this->trade_licence->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->trade_licence->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->trade_licence->Upload->Index < 0) ? $this->trade_licence->FldVar : substr($this->trade_licence->FldVar, 0, 1) . $this->trade_licence->Upload->Index . substr($this->trade_licence->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->trade_licence->TblVar) . $file)) {
							if (!in_array($file, $OldFiles)) {
								$file1 = ew_UploadFileNameEx($this->trade_licence->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->trade_licence->TblVar) . $file1)) // Make sure did not clash with existing upload file
										$file1 = ew_UniqueFilename($this->trade_licence->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->trade_licence->TblVar) . $file, ew_UploadTempPath($fldvar, $this->trade_licence->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
				}
				$this->trade_licence->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$rsnew['trade_licence'] = $this->trade_licence->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}
		if ($this->visa_copy->Visible && !$this->visa_copy->Upload->KeepFile) {
			$this->visa_copy->UploadPath = "../images";
			if (!ew_Empty($this->visa_copy->Upload->Value)) {
				if ($this->visa_copy->Upload->FileName == $this->visa_copy->Upload->DbValue) { // Overwrite if same file name
					$this->visa_copy->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['visa_copy'] = ew_UploadFileNameEx($this->visa_copy->PhysicalUploadPath(), $rsnew['visa_copy']); // Get new file name
				}
			}
		}
		if ($this->emirates_id_copy->Visible && !$this->emirates_id_copy->Upload->KeepFile) {
			$this->emirates_id_copy->UploadPath = "../images";
			$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->emirates_id_copy->Upload->DbValue);
			if (!ew_Empty($this->emirates_id_copy->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->emirates_id_copy->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->emirates_id_copy->Upload->Index < 0) ? $this->emirates_id_copy->FldVar : substr($this->emirates_id_copy->FldVar, 0, 1) . $this->emirates_id_copy->Upload->Index . substr($this->emirates_id_copy->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->emirates_id_copy->TblVar) . $file)) {
							if (!in_array($file, $OldFiles)) {
								$file1 = ew_UploadFileNameEx($this->emirates_id_copy->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->emirates_id_copy->TblVar) . $file1)) // Make sure did not clash with existing upload file
										$file1 = ew_UniqueFilename($this->emirates_id_copy->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->emirates_id_copy->TblVar) . $file, ew_UploadTempPath($fldvar, $this->emirates_id_copy->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
				}
				$this->emirates_id_copy->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$rsnew['emirates_id_copy'] = $this->emirates_id_copy->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}
		if ($this->passport_copy->Visible && !$this->passport_copy->Upload->KeepFile) {
			$this->passport_copy->UploadPath = "../images";
			$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->passport_copy->Upload->DbValue);
			if (!ew_Empty($this->passport_copy->Upload->FileName)) {
				$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->passport_copy->Upload->FileName);
				$FileCount = count($NewFiles);
				for ($i = 0; $i < $FileCount; $i++) {
					$fldvar = ($this->passport_copy->Upload->Index < 0) ? $this->passport_copy->FldVar : substr($this->passport_copy->FldVar, 0, 1) . $this->passport_copy->Upload->Index . substr($this->passport_copy->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->passport_copy->TblVar) . $file)) {
							if (!in_array($file, $OldFiles)) {
								$file1 = ew_UploadFileNameEx($this->passport_copy->PhysicalUploadPath(), $file); // Get new file name
								if ($file1 <> $file) { // Rename temp file
									while (file_exists(ew_UploadTempPath($fldvar, $this->passport_copy->TblVar) . $file1)) // Make sure did not clash with existing upload file
										$file1 = ew_UniqueFilename($this->passport_copy->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
									rename(ew_UploadTempPath($fldvar, $this->passport_copy->TblVar) . $file, ew_UploadTempPath($fldvar, $this->passport_copy->TblVar) . $file1);
									$NewFiles[$i] = $file1;
								}
							}
						}
					}
				}
				$this->passport_copy->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$rsnew['passport_copy'] = $this->passport_copy->Upload->FileName;
			} else {
				$NewFiles = array();
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->trade_licence->Visible && !$this->trade_licence->Upload->KeepFile) {
					$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->trade_licence->Upload->DbValue);
					if (!ew_Empty($this->trade_licence->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->trade_licence->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['trade_licence']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->trade_licence->Upload->Index < 0) ? $this->trade_licence->FldVar : substr($this->trade_licence->FldVar, 0, 1) . $this->trade_licence->Upload->Index . substr($this->trade_licence->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->trade_licence->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (!$this->trade_licence->Upload->SaveToFile((@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE, $i)) { // Just replace
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink($this->trade_licence->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
				if ($this->visa_copy->Visible && !$this->visa_copy->Upload->KeepFile) {
					if (!ew_Empty($this->visa_copy->Upload->Value)) {
						if (!$this->visa_copy->Upload->SaveToFile($rsnew['visa_copy'], TRUE)) {
							$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
							return FALSE;
						}
					}
					if ($this->visa_copy->Upload->DbValue <> "")
						@unlink($this->visa_copy->OldPhysicalUploadPath() . $this->visa_copy->Upload->DbValue);
				}
				if ($this->emirates_id_copy->Visible && !$this->emirates_id_copy->Upload->KeepFile) {
					$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->emirates_id_copy->Upload->DbValue);
					if (!ew_Empty($this->emirates_id_copy->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->emirates_id_copy->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['emirates_id_copy']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->emirates_id_copy->Upload->Index < 0) ? $this->emirates_id_copy->FldVar : substr($this->emirates_id_copy->FldVar, 0, 1) . $this->emirates_id_copy->Upload->Index . substr($this->emirates_id_copy->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->emirates_id_copy->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (!$this->emirates_id_copy->Upload->SaveToFile((@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE, $i)) { // Just replace
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink($this->emirates_id_copy->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
				if ($this->passport_copy->Visible && !$this->passport_copy->Upload->KeepFile) {
					$OldFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->passport_copy->Upload->DbValue);
					if (!ew_Empty($this->passport_copy->Upload->FileName)) {
						$NewFiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->passport_copy->Upload->FileName);
						$NewFiles2 = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $rsnew['passport_copy']);
						$FileCount = count($NewFiles);
						for ($i = 0; $i < $FileCount; $i++) {
							$fldvar = ($this->passport_copy->Upload->Index < 0) ? $this->passport_copy->FldVar : substr($this->passport_copy->FldVar, 0, 1) . $this->passport_copy->Upload->Index . substr($this->passport_copy->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->passport_copy->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (!$this->passport_copy->Upload->SaveToFile((@$NewFiles2[$i] <> "") ? $NewFiles2[$i] : $NewFiles[$i], TRUE, $i)) { // Just replace
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
					$FileCount = count($OldFiles);
					for ($i = 0; $i < $FileCount; $i++) {
						if ($OldFiles[$i] <> "" && !in_array($OldFiles[$i], $NewFiles))
							@unlink($this->passport_copy->OldPhysicalUploadPath() . $OldFiles[$i]);
					}
				}
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

		// trade_licence
		ew_CleanUploadTempPath($this->trade_licence, $this->trade_licence->Upload->Index);

		// visa_copy
		ew_CleanUploadTempPath($this->visa_copy, $this->visa_copy->Upload->Index);

		// emirates_id_copy
		ew_CleanUploadTempPath($this->emirates_id_copy, $this->emirates_id_copy->Upload->Index);

		// passport_copy
		ew_CleanUploadTempPath($this->passport_copy, $this->passport_copy->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institutionslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($institutions_add)) $institutions_add = new cinstitutions_add();

// Page init
$institutions_add->Page_Init();

// Page main
$institutions_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = finstitutionsadd = new ew_Form("finstitutionsadd", "add");

// Validate form
finstitutionsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tl_expiry_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->tl_expiry_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nationality_type");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $institutions->nationality_type->FldCaption(), $institutions->nationality_type->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_visa_expiry_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $institutions->visa_expiry_date->FldCaption(), $institutions->visa_expiry_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_visa_expiry_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->visa_expiry_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_unid");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $institutions->unid->FldCaption(), $institutions->unid->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_unid");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->unid->FldErrMsg()) ?>");
			felm = this.GetElements("x" + infix + "_visa_copy");
			elm = this.GetElements("fn_x" + infix + "_visa_copy");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $institutions->visa_copy->FldCaption(), $institutions->visa_copy->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_eid_expiry_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->eid_expiry_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_passport_ex_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($institutions->passport_ex_date->FldErrMsg()) ?>");

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
finstitutionsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutionsadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
finstitutionsadd.MultiPage = new ew_MultiPage("finstitutionsadd");

// Dynamic selection lists
finstitutionsadd.Lists["x_institution_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_institution_type"].Options = <?php echo json_encode($institutions_add->institution_type->Options()) ?>;
finstitutionsadd.Lists["x_volunteering_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_volunteering_type"].Options = <?php echo json_encode($institutions_add->volunteering_type->Options()) ?>;
finstitutionsadd.Lists["x_nationality_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_nationality_type"].Options = <?php echo json_encode($institutions_add->nationality_type->Options()) ?>;
finstitutionsadd.Lists["x_current_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_current_emirate"].Options = <?php echo json_encode($institutions_add->current_emirate->Options()) ?>;
finstitutionsadd.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_add->admin_approval->Options()) ?>;
finstitutionsadd.Lists["x_forward_to_dep"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
finstitutionsadd.Lists["x_forward_to_dep"].Data = "<?php echo $institutions_add->forward_to_dep->LookupFilterQuery(FALSE, "add") ?>";
finstitutionsadd.Lists["x_eco_department_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_eco_department_approval"].Options = <?php echo json_encode($institutions_add->eco_department_approval->Options()) ?>;
finstitutionsadd.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsadd.Lists["x_security_approval"].Options = <?php echo json_encode($institutions_add->security_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $institutions_add->ShowPageHeader(); ?>
<?php
$institutions_add->ShowMessage();
?>
<form name="finstitutionsadd" id="finstitutionsadd" class="<?php echo $institutions_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($institutions_add->IsModal) ?>">
<?php if (!$institutions_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="institutions_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $institutions_add->MultiPages->NavStyle() ?>">
		<li<?php echo $institutions_add->MultiPages->TabStyle("1") ?>><a href="#tab_institutions1" data-toggle="tab"><?php echo $institutions->PageCaption(1) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("2") ?>><a href="#tab_institutions2" data-toggle="tab"><?php echo $institutions->PageCaption(2) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("3") ?>><a href="#tab_institutions3" data-toggle="tab"><?php echo $institutions->PageCaption(3) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("4") ?>><a href="#tab_institutions4" data-toggle="tab"><?php echo $institutions->PageCaption(4) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("5") ?>><a href="#tab_institutions5" data-toggle="tab"><?php echo $institutions->PageCaption(5) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("6") ?>><a href="#tab_institutions6" data-toggle="tab"><?php echo $institutions->PageCaption(6) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("7") ?>><a href="#tab_institutions7" data-toggle="tab"><?php echo $institutions->PageCaption(7) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("8") ?>><a href="#tab_institutions8" data-toggle="tab"><?php echo $institutions->PageCaption(8) ?></a></li>
		<li<?php echo $institutions_add->MultiPages->TabStyle("9") ?>><a href="#tab_institutions9" data-toggle="tab"><?php echo $institutions->PageCaption(9) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("1") ?>" id="tab_institutions1"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd1" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->full_name_ar->Visible) { // full_name_ar ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_full_name_ar" class="form-group">
		<label id="elh_institutions_full_name_ar" for="x_full_name_ar" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->full_name_ar->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->full_name_ar->CellAttributes() ?>>
<span id="el_institutions_full_name_ar">
<input type="text" data-table="institutions" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_ar->EditValue ?>"<?php echo $institutions->full_name_ar->EditAttributes() ?>>
</span>
<?php echo $institutions->full_name_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_ar">
		<td class="col-sm-2"><span id="elh_institutions_full_name_ar"><?php echo $institutions->full_name_ar->FldCaption() ?></span></td>
		<td<?php echo $institutions->full_name_ar->CellAttributes() ?>>
<span id="el_institutions_full_name_ar">
<input type="text" data-table="institutions" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_ar->EditValue ?>"<?php echo $institutions->full_name_ar->EditAttributes() ?>>
</span>
<?php echo $institutions->full_name_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->full_name_en->Visible) { // full_name_en ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_full_name_en" class="form-group">
		<label id="elh_institutions_full_name_en" for="x_full_name_en" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->full_name_en->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->full_name_en->CellAttributes() ?>>
<span id="el_institutions_full_name_en">
<input type="text" data-table="institutions" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_en->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_en->EditValue ?>"<?php echo $institutions->full_name_en->EditAttributes() ?>>
</span>
<?php echo $institutions->full_name_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_en">
		<td class="col-sm-2"><span id="elh_institutions_full_name_en"><?php echo $institutions->full_name_en->FldCaption() ?></span></td>
		<td<?php echo $institutions->full_name_en->CellAttributes() ?>>
<span id="el_institutions_full_name_en">
<input type="text" data-table="institutions" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($institutions->full_name_en->getPlaceHolder()) ?>" value="<?php echo $institutions->full_name_en->EditValue ?>"<?php echo $institutions->full_name_en->EditAttributes() ?>>
</span>
<?php echo $institutions->full_name_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->institution_type->Visible) { // institution_type ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_institution_type" class="form-group">
		<label id="elh_institutions_institution_type" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->institution_type->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->institution_type->CellAttributes() ?>>
<span id="el_institutions_institution_type">
<div id="tp_x_institution_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_institution_type" data-page="1" data-value-separator="<?php echo $institutions->institution_type->DisplayValueSeparatorAttribute() ?>" name="x_institution_type" id="x_institution_type" value="{value}"<?php echo $institutions->institution_type->EditAttributes() ?>></div>
<div id="dsl_x_institution_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->institution_type->RadioButtonListHtml(FALSE, "x_institution_type", 1) ?>
</div></div>
</span>
<?php echo $institutions->institution_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_institution_type">
		<td class="col-sm-2"><span id="elh_institutions_institution_type"><?php echo $institutions->institution_type->FldCaption() ?></span></td>
		<td<?php echo $institutions->institution_type->CellAttributes() ?>>
<span id="el_institutions_institution_type">
<div id="tp_x_institution_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_institution_type" data-page="1" data-value-separator="<?php echo $institutions->institution_type->DisplayValueSeparatorAttribute() ?>" name="x_institution_type" id="x_institution_type" value="{value}"<?php echo $institutions->institution_type->EditAttributes() ?>></div>
<div id="dsl_x_institution_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->institution_type->RadioButtonListHtml(FALSE, "x_institution_type", 1) ?>
</div></div>
</span>
<?php echo $institutions->institution_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->institutes_name->Visible) { // institutes_name ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_institutes_name" class="form-group">
		<label id="elh_institutions_institutes_name" for="x_institutes_name" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->institutes_name->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->institutes_name->CellAttributes() ?>>
<span id="el_institutions_institutes_name">
<input type="text" data-table="institutions" data-field="x_institutes_name" data-page="1" name="x_institutes_name" id="x_institutes_name" placeholder="<?php echo ew_HtmlEncode($institutions->institutes_name->getPlaceHolder()) ?>" value="<?php echo $institutions->institutes_name->EditValue ?>"<?php echo $institutions->institutes_name->EditAttributes() ?>>
</span>
<?php echo $institutions->institutes_name->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_institutes_name">
		<td class="col-sm-2"><span id="elh_institutions_institutes_name"><?php echo $institutions->institutes_name->FldCaption() ?></span></td>
		<td<?php echo $institutions->institutes_name->CellAttributes() ?>>
<span id="el_institutions_institutes_name">
<input type="text" data-table="institutions" data-field="x_institutes_name" data-page="1" name="x_institutes_name" id="x_institutes_name" placeholder="<?php echo ew_HtmlEncode($institutions->institutes_name->getPlaceHolder()) ?>" value="<?php echo $institutions->institutes_name->EditValue ?>"<?php echo $institutions->institutes_name->EditAttributes() ?>>
</span>
<?php echo $institutions->institutes_name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->volunteering_type->Visible) { // volunteering_type ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_volunteering_type" class="form-group">
		<label id="elh_institutions_volunteering_type" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->volunteering_type->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->volunteering_type->CellAttributes() ?>>
<span id="el_institutions_volunteering_type">
<div id="tp_x_volunteering_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $institutions->volunteering_type->DisplayValueSeparatorAttribute() ?>" name="x_volunteering_type" id="x_volunteering_type" value="{value}"<?php echo $institutions->volunteering_type->EditAttributes() ?>></div>
<div id="dsl_x_volunteering_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->volunteering_type->RadioButtonListHtml(FALSE, "x_volunteering_type", 1) ?>
</div></div>
</span>
<?php echo $institutions->volunteering_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_volunteering_type">
		<td class="col-sm-2"><span id="elh_institutions_volunteering_type"><?php echo $institutions->volunteering_type->FldCaption() ?></span></td>
		<td<?php echo $institutions->volunteering_type->CellAttributes() ?>>
<span id="el_institutions_volunteering_type">
<div id="tp_x_volunteering_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $institutions->volunteering_type->DisplayValueSeparatorAttribute() ?>" name="x_volunteering_type" id="x_volunteering_type" value="{value}"<?php echo $institutions->volunteering_type->EditAttributes() ?>></div>
<div id="dsl_x_volunteering_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->volunteering_type->RadioButtonListHtml(FALSE, "x_volunteering_type", 1) ?>
</div></div>
</span>
<?php echo $institutions->volunteering_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("2") ?>" id="tab_institutions2"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd2" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->licence_no->Visible) { // licence_no ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_licence_no" class="form-group">
		<label id="elh_institutions_licence_no" for="x_licence_no" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->licence_no->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->licence_no->CellAttributes() ?>>
<span id="el_institutions_licence_no">
<input type="text" data-table="institutions" data-field="x_licence_no" data-page="2" name="x_licence_no" id="x_licence_no" placeholder="<?php echo ew_HtmlEncode($institutions->licence_no->getPlaceHolder()) ?>" value="<?php echo $institutions->licence_no->EditValue ?>"<?php echo $institutions->licence_no->EditAttributes() ?>>
</span>
<?php echo $institutions->licence_no->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_licence_no">
		<td class="col-sm-2"><span id="elh_institutions_licence_no"><?php echo $institutions->licence_no->FldCaption() ?></span></td>
		<td<?php echo $institutions->licence_no->CellAttributes() ?>>
<span id="el_institutions_licence_no">
<input type="text" data-table="institutions" data-field="x_licence_no" data-page="2" name="x_licence_no" id="x_licence_no" placeholder="<?php echo ew_HtmlEncode($institutions->licence_no->getPlaceHolder()) ?>" value="<?php echo $institutions->licence_no->EditValue ?>"<?php echo $institutions->licence_no->EditAttributes() ?>>
</span>
<?php echo $institutions->licence_no->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->trade_licence->Visible) { // trade_licence ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_trade_licence" class="form-group">
		<label id="elh_institutions_trade_licence" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->trade_licence->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->trade_licence->CellAttributes() ?>>
<span id="el_institutions_trade_licence">
<div id="fd_x_trade_licence">
<span title="<?php echo $institutions->trade_licence->FldTitle() ? $institutions->trade_licence->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->trade_licence->ReadOnly || $institutions->trade_licence->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_trade_licence" data-page="2" name="x_trade_licence" id="x_trade_licence" multiple="multiple"<?php echo $institutions->trade_licence->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_trade_licence" id= "fn_x_trade_licence" value="<?php echo $institutions->trade_licence->Upload->FileName ?>">
<input type="hidden" name="fa_x_trade_licence" id= "fa_x_trade_licence" value="0">
<input type="hidden" name="fs_x_trade_licence" id= "fs_x_trade_licence" value="-1">
<input type="hidden" name="fx_x_trade_licence" id= "fx_x_trade_licence" value="<?php echo $institutions->trade_licence->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_trade_licence" id= "fm_x_trade_licence" value="<?php echo $institutions->trade_licence->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_trade_licence" id= "fc_x_trade_licence" value="<?php echo $institutions->trade_licence->UploadMaxFileCount ?>">
</div>
<table id="ft_x_trade_licence" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->trade_licence->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_trade_licence">
		<td class="col-sm-2"><span id="elh_institutions_trade_licence"><?php echo $institutions->trade_licence->FldCaption() ?></span></td>
		<td<?php echo $institutions->trade_licence->CellAttributes() ?>>
<span id="el_institutions_trade_licence">
<div id="fd_x_trade_licence">
<span title="<?php echo $institutions->trade_licence->FldTitle() ? $institutions->trade_licence->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->trade_licence->ReadOnly || $institutions->trade_licence->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_trade_licence" data-page="2" name="x_trade_licence" id="x_trade_licence" multiple="multiple"<?php echo $institutions->trade_licence->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_trade_licence" id= "fn_x_trade_licence" value="<?php echo $institutions->trade_licence->Upload->FileName ?>">
<input type="hidden" name="fa_x_trade_licence" id= "fa_x_trade_licence" value="0">
<input type="hidden" name="fs_x_trade_licence" id= "fs_x_trade_licence" value="-1">
<input type="hidden" name="fx_x_trade_licence" id= "fx_x_trade_licence" value="<?php echo $institutions->trade_licence->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_trade_licence" id= "fm_x_trade_licence" value="<?php echo $institutions->trade_licence->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_trade_licence" id= "fc_x_trade_licence" value="<?php echo $institutions->trade_licence->UploadMaxFileCount ?>">
</div>
<table id="ft_x_trade_licence" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->trade_licence->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->tl_expiry_date->Visible) { // tl_expiry_date ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_tl_expiry_date" class="form-group">
		<label id="elh_institutions_tl_expiry_date" for="x_tl_expiry_date" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->tl_expiry_date->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->tl_expiry_date->CellAttributes() ?>>
<span id="el_institutions_tl_expiry_date">
<input type="text" data-table="institutions" data-field="x_tl_expiry_date" data-page="2" name="x_tl_expiry_date" id="x_tl_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->tl_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->tl_expiry_date->EditValue ?>"<?php echo $institutions->tl_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->tl_expiry_date->ReadOnly && !$institutions->tl_expiry_date->Disabled && !isset($institutions->tl_expiry_date->EditAttrs["readonly"]) && !isset($institutions->tl_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_tl_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->tl_expiry_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_tl_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_tl_expiry_date"><?php echo $institutions->tl_expiry_date->FldCaption() ?></span></td>
		<td<?php echo $institutions->tl_expiry_date->CellAttributes() ?>>
<span id="el_institutions_tl_expiry_date">
<input type="text" data-table="institutions" data-field="x_tl_expiry_date" data-page="2" name="x_tl_expiry_date" id="x_tl_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->tl_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->tl_expiry_date->EditValue ?>"<?php echo $institutions->tl_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->tl_expiry_date->ReadOnly && !$institutions->tl_expiry_date->Disabled && !isset($institutions->tl_expiry_date->EditAttrs["readonly"]) && !isset($institutions->tl_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_tl_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->tl_expiry_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("3") ?>" id="tab_institutions3"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd3" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->nationality_type->Visible) { // nationality_type ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_nationality_type" class="form-group">
		<label id="elh_institutions_nationality_type" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->nationality_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->nationality_type->CellAttributes() ?>>
<span id="el_institutions_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_nationality_type" data-page="3" data-value-separator="<?php echo $institutions->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $institutions->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 3) ?>
</div></div>
</span>
<?php echo $institutions->nationality_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality_type">
		<td class="col-sm-2"><span id="elh_institutions_nationality_type"><?php echo $institutions->nationality_type->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $institutions->nationality_type->CellAttributes() ?>>
<span id="el_institutions_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_nationality_type" data-page="3" data-value-separator="<?php echo $institutions->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $institutions->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 3) ?>
</div></div>
</span>
<?php echo $institutions->nationality_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->nationality->Visible) { // nationality ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_nationality" class="form-group">
		<label id="elh_institutions_nationality" for="x_nationality" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->nationality->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->nationality->CellAttributes() ?>>
<span id="el_institutions_nationality">
<input type="text" data-table="institutions" data-field="x_nationality" data-page="3" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($institutions->nationality->getPlaceHolder()) ?>" value="<?php echo $institutions->nationality->EditValue ?>"<?php echo $institutions->nationality->EditAttributes() ?>>
</span>
<?php echo $institutions->nationality->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality">
		<td class="col-sm-2"><span id="elh_institutions_nationality"><?php echo $institutions->nationality->FldCaption() ?></span></td>
		<td<?php echo $institutions->nationality->CellAttributes() ?>>
<span id="el_institutions_nationality">
<input type="text" data-table="institutions" data-field="x_nationality" data-page="3" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($institutions->nationality->getPlaceHolder()) ?>" value="<?php echo $institutions->nationality->EditValue ?>"<?php echo $institutions->nationality->EditAttributes() ?>>
</span>
<?php echo $institutions->nationality->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->visa_expiry_date->Visible) { // visa_expiry_date ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_visa_expiry_date" class="form-group">
		<label id="elh_institutions_visa_expiry_date" for="x_visa_expiry_date" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->visa_expiry_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->visa_expiry_date->CellAttributes() ?>>
<span id="el_institutions_visa_expiry_date">
<input type="text" data-table="institutions" data-field="x_visa_expiry_date" data-page="3" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->visa_expiry_date->EditValue ?>"<?php echo $institutions->visa_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->visa_expiry_date->ReadOnly && !$institutions->visa_expiry_date->Disabled && !isset($institutions->visa_expiry_date->EditAttrs["readonly"]) && !isset($institutions->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->visa_expiry_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_visa_expiry_date"><?php echo $institutions->visa_expiry_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $institutions->visa_expiry_date->CellAttributes() ?>>
<span id="el_institutions_visa_expiry_date">
<input type="text" data-table="institutions" data-field="x_visa_expiry_date" data-page="3" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->visa_expiry_date->EditValue ?>"<?php echo $institutions->visa_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->visa_expiry_date->ReadOnly && !$institutions->visa_expiry_date->Disabled && !isset($institutions->visa_expiry_date->EditAttrs["readonly"]) && !isset($institutions->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->visa_expiry_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->unid->Visible) { // unid ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_unid" class="form-group">
		<label id="elh_institutions_unid" for="x_unid" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->unid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->unid->CellAttributes() ?>>
<span id="el_institutions_unid">
<input type="text" data-table="institutions" data-field="x_unid" data-page="3" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($institutions->unid->getPlaceHolder()) ?>" value="<?php echo $institutions->unid->EditValue ?>"<?php echo $institutions->unid->EditAttributes() ?>>
</span>
<?php echo $institutions->unid->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_unid">
		<td class="col-sm-2"><span id="elh_institutions_unid"><?php echo $institutions->unid->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $institutions->unid->CellAttributes() ?>>
<span id="el_institutions_unid">
<input type="text" data-table="institutions" data-field="x_unid" data-page="3" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($institutions->unid->getPlaceHolder()) ?>" value="<?php echo $institutions->unid->EditValue ?>"<?php echo $institutions->unid->EditAttributes() ?>>
</span>
<?php echo $institutions->unid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->visa_copy->Visible) { // visa_copy ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_visa_copy" class="form-group">
		<label id="elh_institutions_visa_copy" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->visa_copy->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->visa_copy->CellAttributes() ?>>
<span id="el_institutions_visa_copy">
<div id="fd_x_visa_copy">
<span title="<?php echo $institutions->visa_copy->FldTitle() ? $institutions->visa_copy->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->visa_copy->ReadOnly || $institutions->visa_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_visa_copy" data-page="3" name="x_visa_copy" id="x_visa_copy"<?php echo $institutions->visa_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_visa_copy" id= "fn_x_visa_copy" value="<?php echo $institutions->visa_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_visa_copy" id= "fa_x_visa_copy" value="0">
<input type="hidden" name="fs_x_visa_copy" id= "fs_x_visa_copy" value="255">
<input type="hidden" name="fx_x_visa_copy" id= "fx_x_visa_copy" value="<?php echo $institutions->visa_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_visa_copy" id= "fm_x_visa_copy" value="<?php echo $institutions->visa_copy->UploadMaxFileSize ?>">
</div>
<table id="ft_x_visa_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->visa_copy->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_copy">
		<td class="col-sm-2"><span id="elh_institutions_visa_copy"><?php echo $institutions->visa_copy->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $institutions->visa_copy->CellAttributes() ?>>
<span id="el_institutions_visa_copy">
<div id="fd_x_visa_copy">
<span title="<?php echo $institutions->visa_copy->FldTitle() ? $institutions->visa_copy->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->visa_copy->ReadOnly || $institutions->visa_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_visa_copy" data-page="3" name="x_visa_copy" id="x_visa_copy"<?php echo $institutions->visa_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_visa_copy" id= "fn_x_visa_copy" value="<?php echo $institutions->visa_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_visa_copy" id= "fa_x_visa_copy" value="0">
<input type="hidden" name="fs_x_visa_copy" id= "fs_x_visa_copy" value="255">
<input type="hidden" name="fx_x_visa_copy" id= "fx_x_visa_copy" value="<?php echo $institutions->visa_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_visa_copy" id= "fm_x_visa_copy" value="<?php echo $institutions->visa_copy->UploadMaxFileSize ?>">
</div>
<table id="ft_x_visa_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->visa_copy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->current_emirate->Visible) { // current_emirate ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_current_emirate" class="form-group">
		<label id="elh_institutions_current_emirate" for="x_current_emirate" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->current_emirate->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->current_emirate->CellAttributes() ?>>
<span id="el_institutions_current_emirate">
<select data-table="institutions" data-field="x_current_emirate" data-page="3" data-value-separator="<?php echo $institutions->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $institutions->current_emirate->EditAttributes() ?>>
<?php echo $institutions->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
<?php echo $institutions->current_emirate->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_current_emirate">
		<td class="col-sm-2"><span id="elh_institutions_current_emirate"><?php echo $institutions->current_emirate->FldCaption() ?></span></td>
		<td<?php echo $institutions->current_emirate->CellAttributes() ?>>
<span id="el_institutions_current_emirate">
<select data-table="institutions" data-field="x_current_emirate" data-page="3" data-value-separator="<?php echo $institutions->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $institutions->current_emirate->EditAttributes() ?>>
<?php echo $institutions->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
<?php echo $institutions->current_emirate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->full_address->Visible) { // full_address ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_full_address" class="form-group">
		<label id="elh_institutions_full_address" for="x_full_address" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->full_address->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->full_address->CellAttributes() ?>>
<span id="el_institutions_full_address">
<input type="text" data-table="institutions" data-field="x_full_address" data-page="3" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($institutions->full_address->getPlaceHolder()) ?>" value="<?php echo $institutions->full_address->EditValue ?>"<?php echo $institutions->full_address->EditAttributes() ?>>
</span>
<?php echo $institutions->full_address->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_address">
		<td class="col-sm-2"><span id="elh_institutions_full_address"><?php echo $institutions->full_address->FldCaption() ?></span></td>
		<td<?php echo $institutions->full_address->CellAttributes() ?>>
<span id="el_institutions_full_address">
<input type="text" data-table="institutions" data-field="x_full_address" data-page="3" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($institutions->full_address->getPlaceHolder()) ?>" value="<?php echo $institutions->full_address->EditValue ?>"<?php echo $institutions->full_address->EditAttributes() ?>>
</span>
<?php echo $institutions->full_address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("4") ?>" id="tab_institutions4"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd4" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->emirates_id_number->Visible) { // emirates_id_number ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_emirates_id_number" class="form-group">
		<label id="elh_institutions_emirates_id_number" for="x_emirates_id_number" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->emirates_id_number->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->emirates_id_number->CellAttributes() ?>>
<span id="el_institutions_emirates_id_number">
<input type="text" data-table="institutions" data-field="x_emirates_id_number" data-page="4" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($institutions->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $institutions->emirates_id_number->EditValue ?>"<?php echo $institutions->emirates_id_number->EditAttributes() ?>>
</span>
<?php echo $institutions->emirates_id_number->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_number">
		<td class="col-sm-2"><span id="elh_institutions_emirates_id_number"><?php echo $institutions->emirates_id_number->FldCaption() ?></span></td>
		<td<?php echo $institutions->emirates_id_number->CellAttributes() ?>>
<span id="el_institutions_emirates_id_number">
<input type="text" data-table="institutions" data-field="x_emirates_id_number" data-page="4" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($institutions->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $institutions->emirates_id_number->EditValue ?>"<?php echo $institutions->emirates_id_number->EditAttributes() ?>>
</span>
<?php echo $institutions->emirates_id_number->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->eid_expiry_date->Visible) { // eid_expiry_date ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_eid_expiry_date" class="form-group">
		<label id="elh_institutions_eid_expiry_date" for="x_eid_expiry_date" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->eid_expiry_date->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->eid_expiry_date->CellAttributes() ?>>
<span id="el_institutions_eid_expiry_date">
<input type="text" data-table="institutions" data-field="x_eid_expiry_date" data-page="4" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->eid_expiry_date->EditValue ?>"<?php echo $institutions->eid_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->eid_expiry_date->ReadOnly && !$institutions->eid_expiry_date->Disabled && !isset($institutions->eid_expiry_date->EditAttrs["readonly"]) && !isset($institutions->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->eid_expiry_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_eid_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_eid_expiry_date"><?php echo $institutions->eid_expiry_date->FldCaption() ?></span></td>
		<td<?php echo $institutions->eid_expiry_date->CellAttributes() ?>>
<span id="el_institutions_eid_expiry_date">
<input type="text" data-table="institutions" data-field="x_eid_expiry_date" data-page="4" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($institutions->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $institutions->eid_expiry_date->EditValue ?>"<?php echo $institutions->eid_expiry_date->EditAttributes() ?>>
<?php if (!$institutions->eid_expiry_date->ReadOnly && !$institutions->eid_expiry_date->Disabled && !isset($institutions->eid_expiry_date->EditAttrs["readonly"]) && !isset($institutions->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->eid_expiry_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->emirates_id_copy->Visible) { // emirates_id_copy ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_emirates_id_copy" class="form-group">
		<label id="elh_institutions_emirates_id_copy" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->emirates_id_copy->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->emirates_id_copy->CellAttributes() ?>>
<span id="el_institutions_emirates_id_copy">
<div id="fd_x_emirates_id_copy">
<span title="<?php echo $institutions->emirates_id_copy->FldTitle() ? $institutions->emirates_id_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->emirates_id_copy->ReadOnly || $institutions->emirates_id_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_emirates_id_copy" data-page="4" name="x_emirates_id_copy" id="x_emirates_id_copy" multiple="multiple"<?php echo $institutions->emirates_id_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_emirates_id_copy" id= "fn_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_emirates_id_copy" id= "fa_x_emirates_id_copy" value="0">
<input type="hidden" name="fs_x_emirates_id_copy" id= "fs_x_emirates_id_copy" value="65535">
<input type="hidden" name="fx_x_emirates_id_copy" id= "fx_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_emirates_id_copy" id= "fm_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_emirates_id_copy" id= "fc_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_emirates_id_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->emirates_id_copy->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_copy">
		<td class="col-sm-2"><span id="elh_institutions_emirates_id_copy"><?php echo $institutions->emirates_id_copy->FldCaption() ?></span></td>
		<td<?php echo $institutions->emirates_id_copy->CellAttributes() ?>>
<span id="el_institutions_emirates_id_copy">
<div id="fd_x_emirates_id_copy">
<span title="<?php echo $institutions->emirates_id_copy->FldTitle() ? $institutions->emirates_id_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->emirates_id_copy->ReadOnly || $institutions->emirates_id_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_emirates_id_copy" data-page="4" name="x_emirates_id_copy" id="x_emirates_id_copy" multiple="multiple"<?php echo $institutions->emirates_id_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_emirates_id_copy" id= "fn_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_emirates_id_copy" id= "fa_x_emirates_id_copy" value="0">
<input type="hidden" name="fs_x_emirates_id_copy" id= "fs_x_emirates_id_copy" value="65535">
<input type="hidden" name="fx_x_emirates_id_copy" id= "fx_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_emirates_id_copy" id= "fm_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_emirates_id_copy" id= "fc_x_emirates_id_copy" value="<?php echo $institutions->emirates_id_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_emirates_id_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->emirates_id_copy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->passport_number->Visible) { // passport_number ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_passport_number" class="form-group">
		<label id="elh_institutions_passport_number" for="x_passport_number" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->passport_number->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->passport_number->CellAttributes() ?>>
<span id="el_institutions_passport_number">
<input type="text" data-table="institutions" data-field="x_passport_number" data-page="4" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($institutions->passport_number->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_number->EditValue ?>"<?php echo $institutions->passport_number->EditAttributes() ?>>
</span>
<?php echo $institutions->passport_number->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_number">
		<td class="col-sm-2"><span id="elh_institutions_passport_number"><?php echo $institutions->passport_number->FldCaption() ?></span></td>
		<td<?php echo $institutions->passport_number->CellAttributes() ?>>
<span id="el_institutions_passport_number">
<input type="text" data-table="institutions" data-field="x_passport_number" data-page="4" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($institutions->passport_number->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_number->EditValue ?>"<?php echo $institutions->passport_number->EditAttributes() ?>>
</span>
<?php echo $institutions->passport_number->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->passport_ex_date->Visible) { // passport_ex_date ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_passport_ex_date" class="form-group">
		<label id="elh_institutions_passport_ex_date" for="x_passport_ex_date" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->passport_ex_date->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->passport_ex_date->CellAttributes() ?>>
<span id="el_institutions_passport_ex_date">
<input type="text" data-table="institutions" data-field="x_passport_ex_date" data-page="4" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($institutions->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_ex_date->EditValue ?>"<?php echo $institutions->passport_ex_date->EditAttributes() ?>>
<?php if (!$institutions->passport_ex_date->ReadOnly && !$institutions->passport_ex_date->Disabled && !isset($institutions->passport_ex_date->EditAttrs["readonly"]) && !isset($institutions->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->passport_ex_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_ex_date">
		<td class="col-sm-2"><span id="elh_institutions_passport_ex_date"><?php echo $institutions->passport_ex_date->FldCaption() ?></span></td>
		<td<?php echo $institutions->passport_ex_date->CellAttributes() ?>>
<span id="el_institutions_passport_ex_date">
<input type="text" data-table="institutions" data-field="x_passport_ex_date" data-page="4" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($institutions->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $institutions->passport_ex_date->EditValue ?>"<?php echo $institutions->passport_ex_date->EditAttributes() ?>>
<?php if (!$institutions->passport_ex_date->ReadOnly && !$institutions->passport_ex_date->Disabled && !isset($institutions->passport_ex_date->EditAttrs["readonly"]) && !isset($institutions->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("finstitutionsadd", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $institutions->passport_ex_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->passport_copy->Visible) { // passport_copy ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_passport_copy" class="form-group">
		<label id="elh_institutions_passport_copy" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->passport_copy->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->passport_copy->CellAttributes() ?>>
<span id="el_institutions_passport_copy">
<div id="fd_x_passport_copy">
<span title="<?php echo $institutions->passport_copy->FldTitle() ? $institutions->passport_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->passport_copy->ReadOnly || $institutions->passport_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_passport_copy" data-page="4" name="x_passport_copy" id="x_passport_copy" multiple="multiple"<?php echo $institutions->passport_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_passport_copy" id= "fn_x_passport_copy" value="<?php echo $institutions->passport_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_passport_copy" id= "fa_x_passport_copy" value="0">
<input type="hidden" name="fs_x_passport_copy" id= "fs_x_passport_copy" value="65535">
<input type="hidden" name="fx_x_passport_copy" id= "fx_x_passport_copy" value="<?php echo $institutions->passport_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_passport_copy" id= "fm_x_passport_copy" value="<?php echo $institutions->passport_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_passport_copy" id= "fc_x_passport_copy" value="<?php echo $institutions->passport_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_passport_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->passport_copy->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_copy">
		<td class="col-sm-2"><span id="elh_institutions_passport_copy"><?php echo $institutions->passport_copy->FldCaption() ?></span></td>
		<td<?php echo $institutions->passport_copy->CellAttributes() ?>>
<span id="el_institutions_passport_copy">
<div id="fd_x_passport_copy">
<span title="<?php echo $institutions->passport_copy->FldTitle() ? $institutions->passport_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($institutions->passport_copy->ReadOnly || $institutions->passport_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="institutions" data-field="x_passport_copy" data-page="4" name="x_passport_copy" id="x_passport_copy" multiple="multiple"<?php echo $institutions->passport_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_passport_copy" id= "fn_x_passport_copy" value="<?php echo $institutions->passport_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_passport_copy" id= "fa_x_passport_copy" value="0">
<input type="hidden" name="fs_x_passport_copy" id= "fs_x_passport_copy" value="65535">
<input type="hidden" name="fx_x_passport_copy" id= "fx_x_passport_copy" value="<?php echo $institutions->passport_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_passport_copy" id= "fm_x_passport_copy" value="<?php echo $institutions->passport_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_passport_copy" id= "fc_x_passport_copy" value="<?php echo $institutions->passport_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_passport_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $institutions->passport_copy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("5") ?>" id="tab_institutions5"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd5" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->place_of_work->Visible) { // place_of_work ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_place_of_work" class="form-group">
		<label id="elh_institutions_place_of_work" for="x_place_of_work" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->place_of_work->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->place_of_work->CellAttributes() ?>>
<span id="el_institutions_place_of_work">
<textarea data-table="institutions" data-field="x_place_of_work" data-page="5" name="x_place_of_work" id="x_place_of_work" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->place_of_work->getPlaceHolder()) ?>"<?php echo $institutions->place_of_work->EditAttributes() ?>><?php echo $institutions->place_of_work->EditValue ?></textarea>
</span>
<?php echo $institutions->place_of_work->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_place_of_work">
		<td class="col-sm-2"><span id="elh_institutions_place_of_work"><?php echo $institutions->place_of_work->FldCaption() ?></span></td>
		<td<?php echo $institutions->place_of_work->CellAttributes() ?>>
<span id="el_institutions_place_of_work">
<textarea data-table="institutions" data-field="x_place_of_work" data-page="5" name="x_place_of_work" id="x_place_of_work" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->place_of_work->getPlaceHolder()) ?>"<?php echo $institutions->place_of_work->EditAttributes() ?>><?php echo $institutions->place_of_work->EditValue ?></textarea>
</span>
<?php echo $institutions->place_of_work->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->work_phone->Visible) { // work_phone ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_work_phone" class="form-group">
		<label id="elh_institutions_work_phone" for="x_work_phone" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->work_phone->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->work_phone->CellAttributes() ?>>
<span id="el_institutions_work_phone">
<input type="text" data-table="institutions" data-field="x_work_phone" data-page="5" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($institutions->work_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->work_phone->EditValue ?>"<?php echo $institutions->work_phone->EditAttributes() ?>>
</span>
<?php echo $institutions->work_phone->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_work_phone">
		<td class="col-sm-2"><span id="elh_institutions_work_phone"><?php echo $institutions->work_phone->FldCaption() ?></span></td>
		<td<?php echo $institutions->work_phone->CellAttributes() ?>>
<span id="el_institutions_work_phone">
<input type="text" data-table="institutions" data-field="x_work_phone" data-page="5" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($institutions->work_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->work_phone->EditValue ?>"<?php echo $institutions->work_phone->EditAttributes() ?>>
</span>
<?php echo $institutions->work_phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->mobile_phone->Visible) { // mobile_phone ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_mobile_phone" class="form-group">
		<label id="elh_institutions_mobile_phone" for="x_mobile_phone" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->mobile_phone->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->mobile_phone->CellAttributes() ?>>
<span id="el_institutions_mobile_phone">
<input type="text" data-table="institutions" data-field="x_mobile_phone" data-page="5" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->mobile_phone->EditValue ?>"<?php echo $institutions->mobile_phone->EditAttributes() ?>>
</span>
<?php echo $institutions->mobile_phone->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_institutions_mobile_phone"><?php echo $institutions->mobile_phone->FldCaption() ?></span></td>
		<td<?php echo $institutions->mobile_phone->CellAttributes() ?>>
<span id="el_institutions_mobile_phone">
<input type="text" data-table="institutions" data-field="x_mobile_phone" data-page="5" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($institutions->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $institutions->mobile_phone->EditValue ?>"<?php echo $institutions->mobile_phone->EditAttributes() ?>>
</span>
<?php echo $institutions->mobile_phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->fax->Visible) { // fax ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_fax" class="form-group">
		<label id="elh_institutions_fax" for="x_fax" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->fax->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->fax->CellAttributes() ?>>
<span id="el_institutions_fax">
<input type="text" data-table="institutions" data-field="x_fax" data-page="5" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($institutions->fax->getPlaceHolder()) ?>" value="<?php echo $institutions->fax->EditValue ?>"<?php echo $institutions->fax->EditAttributes() ?>>
</span>
<?php echo $institutions->fax->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_fax">
		<td class="col-sm-2"><span id="elh_institutions_fax"><?php echo $institutions->fax->FldCaption() ?></span></td>
		<td<?php echo $institutions->fax->CellAttributes() ?>>
<span id="el_institutions_fax">
<input type="text" data-table="institutions" data-field="x_fax" data-page="5" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($institutions->fax->getPlaceHolder()) ?>" value="<?php echo $institutions->fax->EditValue ?>"<?php echo $institutions->fax->EditAttributes() ?>>
</span>
<?php echo $institutions->fax->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->pobbox->Visible) { // pobbox ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_pobbox" class="form-group">
		<label id="elh_institutions_pobbox" for="x_pobbox" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->pobbox->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->pobbox->CellAttributes() ?>>
<span id="el_institutions_pobbox">
<input type="text" data-table="institutions" data-field="x_pobbox" data-page="5" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($institutions->pobbox->getPlaceHolder()) ?>" value="<?php echo $institutions->pobbox->EditValue ?>"<?php echo $institutions->pobbox->EditAttributes() ?>>
</span>
<?php echo $institutions->pobbox->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_pobbox">
		<td class="col-sm-2"><span id="elh_institutions_pobbox"><?php echo $institutions->pobbox->FldCaption() ?></span></td>
		<td<?php echo $institutions->pobbox->CellAttributes() ?>>
<span id="el_institutions_pobbox">
<input type="text" data-table="institutions" data-field="x_pobbox" data-page="5" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($institutions->pobbox->getPlaceHolder()) ?>" value="<?php echo $institutions->pobbox->EditValue ?>"<?php echo $institutions->pobbox->EditAttributes() ?>>
</span>
<?php echo $institutions->pobbox->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("6") ?>" id="tab_institutions6"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd6" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->_email->Visible) { // email ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r__email" class="form-group">
		<label id="elh_institutions__email" for="x__email" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->_email->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->_email->CellAttributes() ?>>
<span id="el_institutions__email">
<input type="text" data-table="institutions" data-field="x__email" data-page="6" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($institutions->_email->getPlaceHolder()) ?>" value="<?php echo $institutions->_email->EditValue ?>"<?php echo $institutions->_email->EditAttributes() ?>>
</span>
<?php echo $institutions->_email->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_institutions__email"><?php echo $institutions->_email->FldCaption() ?></span></td>
		<td<?php echo $institutions->_email->CellAttributes() ?>>
<span id="el_institutions__email">
<input type="text" data-table="institutions" data-field="x__email" data-page="6" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($institutions->_email->getPlaceHolder()) ?>" value="<?php echo $institutions->_email->EditValue ?>"<?php echo $institutions->_email->EditAttributes() ?>>
</span>
<?php echo $institutions->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->password->Visible) { // password ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_password" class="form-group">
		<label id="elh_institutions_password" for="x_password" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->password->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->password->CellAttributes() ?>>
<span id="el_institutions_password">
<input type="text" data-table="institutions" data-field="x_password" data-page="6" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($institutions->password->getPlaceHolder()) ?>" value="<?php echo $institutions->password->EditValue ?>"<?php echo $institutions->password->EditAttributes() ?>>
</span>
<?php echo $institutions->password->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_institutions_password"><?php echo $institutions->password->FldCaption() ?></span></td>
		<td<?php echo $institutions->password->CellAttributes() ?>>
<span id="el_institutions_password">
<input type="text" data-table="institutions" data-field="x_password" data-page="6" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($institutions->password->getPlaceHolder()) ?>" value="<?php echo $institutions->password->EditValue ?>"<?php echo $institutions->password->EditAttributes() ?>>
</span>
<?php echo $institutions->password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("7") ?>" id="tab_institutions7"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd7" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->admin_approval->Visible) { // admin_approval ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label id="elh_institutions_admin_approval" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->admin_approval->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->admin_approval->CellAttributes() ?>>
<span id="el_institutions_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_admin_approval" data-page="7" data-value-separator="<?php echo $institutions->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 7) ?>
</div></div>
</span>
<?php echo $institutions->admin_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_institutions_admin_approval"><?php echo $institutions->admin_approval->FldCaption() ?></span></td>
		<td<?php echo $institutions->admin_approval->CellAttributes() ?>>
<span id="el_institutions_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_admin_approval" data-page="7" data-value-separator="<?php echo $institutions->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $institutions->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 7) ?>
</div></div>
</span>
<?php echo $institutions->admin_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->admin_comment->Visible) { // admin_comment ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label id="elh_institutions_admin_comment" for="x_admin_comment" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->admin_comment->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->admin_comment->CellAttributes() ?>>
<span id="el_institutions_admin_comment">
<textarea data-table="institutions" data-field="x_admin_comment" data-page="7" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->admin_comment->getPlaceHolder()) ?>"<?php echo $institutions->admin_comment->EditAttributes() ?>><?php echo $institutions->admin_comment->EditValue ?></textarea>
</span>
<?php echo $institutions->admin_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_institutions_admin_comment"><?php echo $institutions->admin_comment->FldCaption() ?></span></td>
		<td<?php echo $institutions->admin_comment->CellAttributes() ?>>
<span id="el_institutions_admin_comment">
<textarea data-table="institutions" data-field="x_admin_comment" data-page="7" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->admin_comment->getPlaceHolder()) ?>"<?php echo $institutions->admin_comment->EditAttributes() ?>><?php echo $institutions->admin_comment->EditValue ?></textarea>
</span>
<?php echo $institutions->admin_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->forward_to_dep->Visible) { // forward_to_dep ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_forward_to_dep" class="form-group">
		<label id="elh_institutions_forward_to_dep" for="x_forward_to_dep" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->forward_to_dep->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->forward_to_dep->CellAttributes() ?>>
<span id="el_institutions_forward_to_dep">
<select data-table="institutions" data-field="x_forward_to_dep" data-page="7" data-value-separator="<?php echo $institutions->forward_to_dep->DisplayValueSeparatorAttribute() ?>" id="x_forward_to_dep" name="x_forward_to_dep"<?php echo $institutions->forward_to_dep->EditAttributes() ?>>
<?php echo $institutions->forward_to_dep->SelectOptionListHtml("x_forward_to_dep") ?>
</select>
</span>
<?php echo $institutions->forward_to_dep->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_forward_to_dep">
		<td class="col-sm-2"><span id="elh_institutions_forward_to_dep"><?php echo $institutions->forward_to_dep->FldCaption() ?></span></td>
		<td<?php echo $institutions->forward_to_dep->CellAttributes() ?>>
<span id="el_institutions_forward_to_dep">
<select data-table="institutions" data-field="x_forward_to_dep" data-page="7" data-value-separator="<?php echo $institutions->forward_to_dep->DisplayValueSeparatorAttribute() ?>" id="x_forward_to_dep" name="x_forward_to_dep"<?php echo $institutions->forward_to_dep->EditAttributes() ?>>
<?php echo $institutions->forward_to_dep->SelectOptionListHtml("x_forward_to_dep") ?>
</select>
</span>
<?php echo $institutions->forward_to_dep->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("8") ?>" id="tab_institutions8"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd8" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->eco_department_approval->Visible) { // eco_department_approval ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_eco_department_approval" class="form-group">
		<label id="elh_institutions_eco_department_approval" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->eco_department_approval->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->eco_department_approval->CellAttributes() ?>>
<span id="el_institutions_eco_department_approval">
<div id="tp_x_eco_department_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_eco_department_approval" data-page="8" data-value-separator="<?php echo $institutions->eco_department_approval->DisplayValueSeparatorAttribute() ?>" name="x_eco_department_approval" id="x_eco_department_approval" value="{value}"<?php echo $institutions->eco_department_approval->EditAttributes() ?>></div>
<div id="dsl_x_eco_department_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->eco_department_approval->RadioButtonListHtml(FALSE, "x_eco_department_approval", 8) ?>
</div></div>
</span>
<?php echo $institutions->eco_department_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_eco_department_approval">
		<td class="col-sm-2"><span id="elh_institutions_eco_department_approval"><?php echo $institutions->eco_department_approval->FldCaption() ?></span></td>
		<td<?php echo $institutions->eco_department_approval->CellAttributes() ?>>
<span id="el_institutions_eco_department_approval">
<div id="tp_x_eco_department_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_eco_department_approval" data-page="8" data-value-separator="<?php echo $institutions->eco_department_approval->DisplayValueSeparatorAttribute() ?>" name="x_eco_department_approval" id="x_eco_department_approval" value="{value}"<?php echo $institutions->eco_department_approval->EditAttributes() ?>></div>
<div id="dsl_x_eco_department_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->eco_department_approval->RadioButtonListHtml(FALSE, "x_eco_department_approval", 8) ?>
</div></div>
</span>
<?php echo $institutions->eco_department_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->eco_departmnet_comment->Visible) { // eco_departmnet_comment ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_eco_departmnet_comment" class="form-group">
		<label id="elh_institutions_eco_departmnet_comment" for="x_eco_departmnet_comment" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->eco_departmnet_comment->CellAttributes() ?>>
<span id="el_institutions_eco_departmnet_comment">
<textarea data-table="institutions" data-field="x_eco_departmnet_comment" data-page="8" name="x_eco_departmnet_comment" id="x_eco_departmnet_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->eco_departmnet_comment->getPlaceHolder()) ?>"<?php echo $institutions->eco_departmnet_comment->EditAttributes() ?>><?php echo $institutions->eco_departmnet_comment->EditValue ?></textarea>
</span>
<?php echo $institutions->eco_departmnet_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_eco_departmnet_comment">
		<td class="col-sm-2"><span id="elh_institutions_eco_departmnet_comment"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?></span></td>
		<td<?php echo $institutions->eco_departmnet_comment->CellAttributes() ?>>
<span id="el_institutions_eco_departmnet_comment">
<textarea data-table="institutions" data-field="x_eco_departmnet_comment" data-page="8" name="x_eco_departmnet_comment" id="x_eco_departmnet_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->eco_departmnet_comment->getPlaceHolder()) ?>"<?php echo $institutions->eco_departmnet_comment->EditAttributes() ?>><?php echo $institutions->eco_departmnet_comment->EditValue ?></textarea>
</span>
<?php echo $institutions->eco_departmnet_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $institutions_add->MultiPages->PageStyle("9") ?>" id="tab_institutions9"><!-- multi-page .tab-pane -->
<?php if ($institutions_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_institutionsadd9" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($institutions->security_approval->Visible) { // security_approval ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_security_approval" class="form-group">
		<label id="elh_institutions_security_approval" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->security_approval->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->security_approval->CellAttributes() ?>>
<span id="el_institutions_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_security_approval" data-page="9" data-value-separator="<?php echo $institutions->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $institutions->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 9) ?>
</div></div>
</span>
<?php echo $institutions->security_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_approval">
		<td class="col-sm-2"><span id="elh_institutions_security_approval"><?php echo $institutions->security_approval->FldCaption() ?></span></td>
		<td<?php echo $institutions->security_approval->CellAttributes() ?>>
<span id="el_institutions_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="institutions" data-field="x_security_approval" data-page="9" data-value-separator="<?php echo $institutions->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $institutions->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $institutions->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 9) ?>
</div></div>
</span>
<?php echo $institutions->security_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions->security_comment->Visible) { // security_comment ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
	<div id="r_security_comment" class="form-group">
		<label id="elh_institutions_security_comment" for="x_security_comment" class="<?php echo $institutions_add->LeftColumnClass ?>"><?php echo $institutions->security_comment->FldCaption() ?></label>
		<div class="<?php echo $institutions_add->RightColumnClass ?>"><div<?php echo $institutions->security_comment->CellAttributes() ?>>
<span id="el_institutions_security_comment">
<textarea data-table="institutions" data-field="x_security_comment" data-page="9" name="x_security_comment" id="x_security_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->security_comment->getPlaceHolder()) ?>"<?php echo $institutions->security_comment->EditAttributes() ?>><?php echo $institutions->security_comment->EditValue ?></textarea>
</span>
<?php echo $institutions->security_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_comment">
		<td class="col-sm-2"><span id="elh_institutions_security_comment"><?php echo $institutions->security_comment->FldCaption() ?></span></td>
		<td<?php echo $institutions->security_comment->CellAttributes() ?>>
<span id="el_institutions_security_comment">
<textarea data-table="institutions" data-field="x_security_comment" data-page="9" name="x_security_comment" id="x_security_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($institutions->security_comment->getPlaceHolder()) ?>"<?php echo $institutions->security_comment->EditAttributes() ?>><?php echo $institutions->security_comment->EditValue ?></textarea>
</span>
<?php echo $institutions->security_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($institutions_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php if (!$institutions_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $institutions_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $institutions_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$institutions_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
finstitutionsadd.Init();
</script>
<?php
$institutions_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$institutions_add->Page_Terminate();
?>
