<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "user_attachmentsgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$users_add = NULL; // Initialize page object first

class cusers_add extends cusers {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
		$this->admin_comment->SetVisibility();
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

			// Process auto fill for detail table 'user_attachments'
			if (@$_POST["grid"] == "fuser_attachmentsgrid") {
				if (!isset($GLOBALS["user_attachments_grid"])) $GLOBALS["user_attachments_grid"] = new cuser_attachments_grid;
				$GLOBALS["user_attachments_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
			if (@$_GET["user_id"] != "") {
				$this->user_id->setQueryStringValue($_GET["user_id"]);
				$this->setKey("user_id", $this->user_id->CurrentValue); // Set up key
			} else {
				$this->setKey("user_id", ""); // Clear key
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

		// Set up detail parameters
		$this->SetupDetailParms();

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
					$this->Page_Terminate("userslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = "userslist.php";
					if (ew_GetPageName($sReturnUrl) == "userslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "usersview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetupDetailParms();
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
		$this->personal_photo->Upload->Index = $objForm->Index;
		$this->personal_photo->Upload->UploadFile();
		$this->personal_photo->CurrentValue = $this->personal_photo->Upload->FileName;
		$this->visa_copy->Upload->Index = $objForm->Index;
		$this->visa_copy->Upload->UploadFile();
		$this->visa_copy->CurrentValue = $this->visa_copy->Upload->FileName;
		$this->emirates_id_copy->Upload->Index = $objForm->Index;
		$this->emirates_id_copy->Upload->UploadFile();
		$this->emirates_id_copy->CurrentValue = $this->emirates_id_copy->Upload->FileName;
		$this->passport_copy->Upload->Index = $objForm->Index;
		$this->passport_copy->Upload->UploadFile();
		$this->passport_copy->CurrentValue = $this->passport_copy->Upload->FileName;
		$this->cv->Upload->Index = $objForm->Index;
		$this->cv->Upload->UploadFile();
		$this->cv->CurrentValue = $this->cv->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->group_id->CurrentValue = NULL;
		$this->group_id->OldValue = $this->group_id->CurrentValue;
		$this->full_name_ar->CurrentValue = NULL;
		$this->full_name_ar->OldValue = $this->full_name_ar->CurrentValue;
		$this->full_name_en->CurrentValue = NULL;
		$this->full_name_en->OldValue = $this->full_name_en->CurrentValue;
		$this->date_of_birth->CurrentValue = NULL;
		$this->date_of_birth->OldValue = $this->date_of_birth->CurrentValue;
		$this->personal_photo->Upload->DbValue = NULL;
		$this->personal_photo->OldValue = $this->personal_photo->Upload->DbValue;
		$this->personal_photo->CurrentValue = NULL; // Clear file related field
		$this->gender->CurrentValue = NULL;
		$this->gender->OldValue = $this->gender->CurrentValue;
		$this->blood_type->CurrentValue = NULL;
		$this->blood_type->OldValue = $this->blood_type->CurrentValue;
		$this->driving_licence->CurrentValue = NULL;
		$this->driving_licence->OldValue = $this->driving_licence->CurrentValue;
		$this->job->CurrentValue = NULL;
		$this->job->OldValue = $this->job->CurrentValue;
		$this->volunteering_type->CurrentValue = NULL;
		$this->volunteering_type->OldValue = $this->volunteering_type->CurrentValue;
		$this->marital_status->CurrentValue = NULL;
		$this->marital_status->OldValue = $this->marital_status->CurrentValue;
		$this->nationality_type->CurrentValue = NULL;
		$this->nationality_type->OldValue = $this->nationality_type->CurrentValue;
		$this->nationality->CurrentValue = NULL;
		$this->nationality->OldValue = $this->nationality->CurrentValue;
		$this->unid->CurrentValue = NULL;
		$this->unid->OldValue = $this->unid->CurrentValue;
		$this->visa_expiry_date->CurrentValue = NULL;
		$this->visa_expiry_date->OldValue = $this->visa_expiry_date->CurrentValue;
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
		$this->qualifications->CurrentValue = NULL;
		$this->qualifications->OldValue = $this->qualifications->CurrentValue;
		$this->cv->Upload->DbValue = NULL;
		$this->cv->OldValue = $this->cv->Upload->DbValue;
		$this->cv->CurrentValue = NULL; // Clear file related field
		$this->home_phone->CurrentValue = NULL;
		$this->home_phone->OldValue = $this->home_phone->CurrentValue;
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
		$this->total_voluntary_hours->CurrentValue = NULL;
		$this->total_voluntary_hours->OldValue = $this->total_voluntary_hours->CurrentValue;
		$this->overall_evaluation->CurrentValue = NULL;
		$this->overall_evaluation->OldValue = $this->overall_evaluation->CurrentValue;
		$this->admin_approval->CurrentValue = NULL;
		$this->admin_approval->OldValue = $this->admin_approval->CurrentValue;
		$this->admin_comment->CurrentValue = NULL;
		$this->admin_comment->OldValue = $this->admin_comment->CurrentValue;
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
		if (!$this->group_id->FldIsDetailKey) {
			$this->group_id->setFormValue($objForm->GetValue("x_group_id"));
		}
		if (!$this->full_name_ar->FldIsDetailKey) {
			$this->full_name_ar->setFormValue($objForm->GetValue("x_full_name_ar"));
		}
		if (!$this->full_name_en->FldIsDetailKey) {
			$this->full_name_en->setFormValue($objForm->GetValue("x_full_name_en"));
		}
		if (!$this->date_of_birth->FldIsDetailKey) {
			$this->date_of_birth->setFormValue($objForm->GetValue("x_date_of_birth"));
			$this->date_of_birth->CurrentValue = ew_UnFormatDateTime($this->date_of_birth->CurrentValue, 0);
		}
		if (!$this->gender->FldIsDetailKey) {
			$this->gender->setFormValue($objForm->GetValue("x_gender"));
		}
		if (!$this->blood_type->FldIsDetailKey) {
			$this->blood_type->setFormValue($objForm->GetValue("x_blood_type"));
		}
		if (!$this->driving_licence->FldIsDetailKey) {
			$this->driving_licence->setFormValue($objForm->GetValue("x_driving_licence"));
		}
		if (!$this->job->FldIsDetailKey) {
			$this->job->setFormValue($objForm->GetValue("x_job"));
		}
		if (!$this->volunteering_type->FldIsDetailKey) {
			$this->volunteering_type->setFormValue($objForm->GetValue("x_volunteering_type"));
		}
		if (!$this->marital_status->FldIsDetailKey) {
			$this->marital_status->setFormValue($objForm->GetValue("x_marital_status"));
		}
		if (!$this->nationality_type->FldIsDetailKey) {
			$this->nationality_type->setFormValue($objForm->GetValue("x_nationality_type"));
		}
		if (!$this->nationality->FldIsDetailKey) {
			$this->nationality->setFormValue($objForm->GetValue("x_nationality"));
		}
		if (!$this->unid->FldIsDetailKey) {
			$this->unid->setFormValue($objForm->GetValue("x_unid"));
		}
		if (!$this->visa_expiry_date->FldIsDetailKey) {
			$this->visa_expiry_date->setFormValue($objForm->GetValue("x_visa_expiry_date"));
			$this->visa_expiry_date->CurrentValue = ew_UnFormatDateTime($this->visa_expiry_date->CurrentValue, 0);
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
		if (!$this->qualifications->FldIsDetailKey) {
			$this->qualifications->setFormValue($objForm->GetValue("x_qualifications"));
		}
		if (!$this->home_phone->FldIsDetailKey) {
			$this->home_phone->setFormValue($objForm->GetValue("x_home_phone"));
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
		if (!$this->total_voluntary_hours->FldIsDetailKey) {
			$this->total_voluntary_hours->setFormValue($objForm->GetValue("x_total_voluntary_hours"));
		}
		if (!$this->overall_evaluation->FldIsDetailKey) {
			$this->overall_evaluation->setFormValue($objForm->GetValue("x_overall_evaluation"));
		}
		if (!$this->admin_approval->FldIsDetailKey) {
			$this->admin_approval->setFormValue($objForm->GetValue("x_admin_approval"));
		}
		if (!$this->admin_comment->FldIsDetailKey) {
			$this->admin_comment->setFormValue($objForm->GetValue("x_admin_comment"));
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
		$this->group_id->CurrentValue = $this->group_id->FormValue;
		$this->full_name_ar->CurrentValue = $this->full_name_ar->FormValue;
		$this->full_name_en->CurrentValue = $this->full_name_en->FormValue;
		$this->date_of_birth->CurrentValue = $this->date_of_birth->FormValue;
		$this->date_of_birth->CurrentValue = ew_UnFormatDateTime($this->date_of_birth->CurrentValue, 0);
		$this->gender->CurrentValue = $this->gender->FormValue;
		$this->blood_type->CurrentValue = $this->blood_type->FormValue;
		$this->driving_licence->CurrentValue = $this->driving_licence->FormValue;
		$this->job->CurrentValue = $this->job->FormValue;
		$this->volunteering_type->CurrentValue = $this->volunteering_type->FormValue;
		$this->marital_status->CurrentValue = $this->marital_status->FormValue;
		$this->nationality_type->CurrentValue = $this->nationality_type->FormValue;
		$this->nationality->CurrentValue = $this->nationality->FormValue;
		$this->unid->CurrentValue = $this->unid->FormValue;
		$this->visa_expiry_date->CurrentValue = $this->visa_expiry_date->FormValue;
		$this->visa_expiry_date->CurrentValue = ew_UnFormatDateTime($this->visa_expiry_date->CurrentValue, 0);
		$this->current_emirate->CurrentValue = $this->current_emirate->FormValue;
		$this->full_address->CurrentValue = $this->full_address->FormValue;
		$this->emirates_id_number->CurrentValue = $this->emirates_id_number->FormValue;
		$this->eid_expiry_date->CurrentValue = $this->eid_expiry_date->FormValue;
		$this->eid_expiry_date->CurrentValue = ew_UnFormatDateTime($this->eid_expiry_date->CurrentValue, 0);
		$this->passport_number->CurrentValue = $this->passport_number->FormValue;
		$this->passport_ex_date->CurrentValue = $this->passport_ex_date->FormValue;
		$this->passport_ex_date->CurrentValue = ew_UnFormatDateTime($this->passport_ex_date->CurrentValue, 0);
		$this->place_of_work->CurrentValue = $this->place_of_work->FormValue;
		$this->qualifications->CurrentValue = $this->qualifications->FormValue;
		$this->home_phone->CurrentValue = $this->home_phone->FormValue;
		$this->work_phone->CurrentValue = $this->work_phone->FormValue;
		$this->mobile_phone->CurrentValue = $this->mobile_phone->FormValue;
		$this->fax->CurrentValue = $this->fax->FormValue;
		$this->pobbox->CurrentValue = $this->pobbox->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->password->CurrentValue = $this->password->FormValue;
		$this->total_voluntary_hours->CurrentValue = $this->total_voluntary_hours->FormValue;
		$this->overall_evaluation->CurrentValue = $this->overall_evaluation->FormValue;
		$this->admin_approval->CurrentValue = $this->admin_approval->FormValue;
		$this->admin_comment->CurrentValue = $this->admin_comment->FormValue;
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
		$this->user_id->setDbValue($row['user_id']);
		$this->group_id->setDbValue($row['group_id']);
		$this->full_name_ar->setDbValue($row['full_name_ar']);
		$this->full_name_en->setDbValue($row['full_name_en']);
		$this->date_of_birth->setDbValue($row['date_of_birth']);
		$this->personal_photo->Upload->DbValue = $row['personal_photo'];
		$this->personal_photo->CurrentValue = $this->personal_photo->Upload->DbValue;
		$this->gender->setDbValue($row['gender']);
		$this->blood_type->setDbValue($row['blood_type']);
		$this->driving_licence->setDbValue($row['driving_licence']);
		$this->job->setDbValue($row['job']);
		$this->volunteering_type->setDbValue($row['volunteering_type']);
		$this->marital_status->setDbValue($row['marital_status']);
		$this->nationality_type->setDbValue($row['nationality_type']);
		$this->nationality->setDbValue($row['nationality']);
		$this->unid->setDbValue($row['unid']);
		$this->visa_expiry_date->setDbValue($row['visa_expiry_date']);
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
		$this->qualifications->setDbValue($row['qualifications']);
		$this->cv->Upload->DbValue = $row['cv'];
		$this->cv->CurrentValue = $this->cv->Upload->DbValue;
		$this->home_phone->setDbValue($row['home_phone']);
		$this->work_phone->setDbValue($row['work_phone']);
		$this->mobile_phone->setDbValue($row['mobile_phone']);
		$this->fax->setDbValue($row['fax']);
		$this->pobbox->setDbValue($row['pobbox']);
		$this->_email->setDbValue($row['email']);
		$this->password->setDbValue($row['password']);
		$this->total_voluntary_hours->setDbValue($row['total_voluntary_hours']);
		$this->overall_evaluation->setDbValue($row['overall_evaluation']);
		$this->admin_approval->setDbValue($row['admin_approval']);
		$this->lastUpdatedBy->setDbValue($row['lastUpdatedBy']);
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->security_approval->setDbValue($row['security_approval']);
		$this->approvedBy->setDbValue($row['approvedBy']);
		$this->security_comment->setDbValue($row['security_comment']);
		$this->title_number->setDbValue($row['title_number']);
		$this->security_owner->setDbValue($row['security_owner']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['user_id'] = NULL;
		$row['group_id'] = NULL;
		$row['full_name_ar'] = NULL;
		$row['full_name_en'] = NULL;
		$row['date_of_birth'] = NULL;
		$row['personal_photo'] = NULL;
		$row['gender'] = NULL;
		$row['blood_type'] = NULL;
		$row['driving_licence'] = NULL;
		$row['job'] = NULL;
		$row['volunteering_type'] = NULL;
		$row['marital_status'] = NULL;
		$row['nationality_type'] = NULL;
		$row['nationality'] = NULL;
		$row['unid'] = NULL;
		$row['visa_expiry_date'] = NULL;
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
		$row['qualifications'] = NULL;
		$row['cv'] = NULL;
		$row['home_phone'] = NULL;
		$row['work_phone'] = NULL;
		$row['mobile_phone'] = NULL;
		$row['fax'] = NULL;
		$row['pobbox'] = NULL;
		$row['email'] = NULL;
		$row['password'] = NULL;
		$row['total_voluntary_hours'] = NULL;
		$row['overall_evaluation'] = NULL;
		$row['admin_approval'] = NULL;
		$row['lastUpdatedBy'] = NULL;
		$row['admin_comment'] = NULL;
		$row['security_approval'] = NULL;
		$row['approvedBy'] = NULL;
		$row['security_comment'] = NULL;
		$row['title_number'] = NULL;
		$row['security_owner'] = NULL;
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
		$this->user_id->DbValue = $row['user_id'];
		$this->group_id->DbValue = $row['group_id'];
		$this->full_name_ar->DbValue = $row['full_name_ar'];
		$this->full_name_en->DbValue = $row['full_name_en'];
		$this->date_of_birth->DbValue = $row['date_of_birth'];
		$this->personal_photo->Upload->DbValue = $row['personal_photo'];
		$this->gender->DbValue = $row['gender'];
		$this->blood_type->DbValue = $row['blood_type'];
		$this->driving_licence->DbValue = $row['driving_licence'];
		$this->job->DbValue = $row['job'];
		$this->volunteering_type->DbValue = $row['volunteering_type'];
		$this->marital_status->DbValue = $row['marital_status'];
		$this->nationality_type->DbValue = $row['nationality_type'];
		$this->nationality->DbValue = $row['nationality'];
		$this->unid->DbValue = $row['unid'];
		$this->visa_expiry_date->DbValue = $row['visa_expiry_date'];
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
		$this->qualifications->DbValue = $row['qualifications'];
		$this->cv->Upload->DbValue = $row['cv'];
		$this->home_phone->DbValue = $row['home_phone'];
		$this->work_phone->DbValue = $row['work_phone'];
		$this->mobile_phone->DbValue = $row['mobile_phone'];
		$this->fax->DbValue = $row['fax'];
		$this->pobbox->DbValue = $row['pobbox'];
		$this->_email->DbValue = $row['email'];
		$this->password->DbValue = $row['password'];
		$this->total_voluntary_hours->DbValue = $row['total_voluntary_hours'];
		$this->overall_evaluation->DbValue = $row['overall_evaluation'];
		$this->admin_approval->DbValue = $row['admin_approval'];
		$this->lastUpdatedBy->DbValue = $row['lastUpdatedBy'];
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->security_approval->DbValue = $row['security_approval'];
		$this->approvedBy->DbValue = $row['approvedBy'];
		$this->security_comment->DbValue = $row['security_comment'];
		$this->title_number->DbValue = $row['title_number'];
		$this->security_owner->DbValue = $row['security_owner'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("user_id")) <> "")
			$this->user_id->CurrentValue = $this->getKey("user_id"); // user_id
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

		// security_comment
		$this->security_comment->ViewValue = $this->security_comment->CurrentValue;
		$this->security_comment->ViewCustomAttributes = "";

		// title_number
		$this->title_number->ViewValue = $this->title_number->CurrentValue;
		$this->title_number->ViewCustomAttributes = "";

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

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";
			$this->security_approval->TooltipValue = "";

			// security_comment
			$this->security_comment->LinkCustomAttributes = "";
			$this->security_comment->HrefValue = "";
			$this->security_comment->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// group_id
			$this->group_id->EditCustomAttributes = "";
			if (trim(strval($this->group_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->group_id->CurrentValue);
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
				$this->group_id->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->group_id->ViewValue .= $this->group_id->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->group_id->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->group_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->group_id->EditValue = $arwrk;

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

			// date_of_birth
			$this->date_of_birth->EditAttrs["class"] = "form-control";
			$this->date_of_birth->EditCustomAttributes = "";
			$this->date_of_birth->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date_of_birth->CurrentValue, 8));
			$this->date_of_birth->PlaceHolder = ew_RemoveHtml($this->date_of_birth->FldCaption());

			// personal_photo
			$this->personal_photo->EditAttrs["class"] = "form-control";
			$this->personal_photo->EditCustomAttributes = "";
			$this->personal_photo->UploadPath = "../images";
			if (!ew_Empty($this->personal_photo->Upload->DbValue)) {
				$this->personal_photo->ImageWidth = 300;
				$this->personal_photo->ImageHeight = 0;
				$this->personal_photo->ImageAlt = $this->personal_photo->FldAlt();
				$this->personal_photo->EditValue = $this->personal_photo->Upload->DbValue;
			} else {
				$this->personal_photo->EditValue = "";
			}
			if (!ew_Empty($this->personal_photo->CurrentValue))
				$this->personal_photo->Upload->FileName = $this->personal_photo->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->personal_photo);

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
			$this->nationality->EditValue = ew_HtmlEncode($this->nationality->CurrentValue);
			$this->nationality->PlaceHolder = ew_RemoveHtml($this->nationality->FldCaption());

			// unid
			$this->unid->EditAttrs["class"] = "form-control";
			$this->unid->EditCustomAttributes = "";
			$this->unid->EditValue = ew_HtmlEncode($this->unid->CurrentValue);
			$this->unid->PlaceHolder = ew_RemoveHtml($this->unid->FldCaption());

			// visa_expiry_date
			$this->visa_expiry_date->EditAttrs["class"] = "form-control";
			$this->visa_expiry_date->EditCustomAttributes = "";
			$this->visa_expiry_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->visa_expiry_date->CurrentValue, 8));
			$this->visa_expiry_date->PlaceHolder = ew_RemoveHtml($this->visa_expiry_date->FldCaption());

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
				$this->emirates_id_copy->ImageWidth = 100;
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

			// qualifications
			$this->qualifications->EditAttrs["class"] = "form-control";
			$this->qualifications->EditCustomAttributes = "";
			$this->qualifications->EditValue = ew_HtmlEncode($this->qualifications->CurrentValue);
			$this->qualifications->PlaceHolder = ew_RemoveHtml($this->qualifications->FldCaption());

			// cv
			$this->cv->EditAttrs["class"] = "form-control";
			$this->cv->EditCustomAttributes = "";
			$this->cv->UploadPath = "../images";
			if (!ew_Empty($this->cv->Upload->DbValue)) {
				$this->cv->EditValue = $this->cv->Upload->DbValue;
			} else {
				$this->cv->EditValue = "";
			}
			if (!ew_Empty($this->cv->CurrentValue))
				$this->cv->Upload->FileName = $this->cv->CurrentValue;
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->cv);

			// home_phone
			$this->home_phone->EditAttrs["class"] = "form-control";
			$this->home_phone->EditCustomAttributes = "";
			$this->home_phone->EditValue = ew_HtmlEncode($this->home_phone->CurrentValue);
			$this->home_phone->PlaceHolder = ew_RemoveHtml($this->home_phone->FldCaption());

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

			// total_voluntary_hours
			$this->total_voluntary_hours->EditAttrs["class"] = "form-control";
			$this->total_voluntary_hours->EditCustomAttributes = "";
			$this->total_voluntary_hours->EditValue = ew_HtmlEncode($this->total_voluntary_hours->CurrentValue);
			$this->total_voluntary_hours->PlaceHolder = ew_RemoveHtml($this->total_voluntary_hours->FldCaption());

			// overall_evaluation
			$this->overall_evaluation->EditAttrs["class"] = "form-control";
			$this->overall_evaluation->EditCustomAttributes = "";
			$this->overall_evaluation->EditValue = ew_HtmlEncode($this->overall_evaluation->CurrentValue);
			$this->overall_evaluation->PlaceHolder = ew_RemoveHtml($this->overall_evaluation->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->CurrentValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// security_approval
			$this->security_approval->EditCustomAttributes = "";
			$this->security_approval->EditValue = $this->security_approval->Options(FALSE);

			// security_comment
			$this->security_comment->EditAttrs["class"] = "form-control";
			$this->security_comment->EditCustomAttributes = "";
			$this->security_comment->EditValue = ew_HtmlEncode($this->security_comment->CurrentValue);
			$this->security_comment->PlaceHolder = ew_RemoveHtml($this->security_comment->FldCaption());

			// Add refer script
			// group_id

			$this->group_id->LinkCustomAttributes = "";
			$this->group_id->HrefValue = "";

			// full_name_ar
			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";

			// date_of_birth
			$this->date_of_birth->LinkCustomAttributes = "";
			$this->date_of_birth->HrefValue = "";

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

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";

			// blood_type
			$this->blood_type->LinkCustomAttributes = "";
			$this->blood_type->HrefValue = "";

			// driving_licence
			$this->driving_licence->LinkCustomAttributes = "";
			$this->driving_licence->HrefValue = "";

			// job
			$this->job->LinkCustomAttributes = "";
			$this->job->HrefValue = "";

			// volunteering_type
			$this->volunteering_type->LinkCustomAttributes = "";
			$this->volunteering_type->HrefValue = "";

			// marital_status
			$this->marital_status->LinkCustomAttributes = "";
			$this->marital_status->HrefValue = "";

			// nationality_type
			$this->nationality_type->LinkCustomAttributes = "";
			$this->nationality_type->HrefValue = "";

			// nationality
			$this->nationality->LinkCustomAttributes = "";
			$this->nationality->HrefValue = "";

			// unid
			$this->unid->LinkCustomAttributes = "";
			$this->unid->HrefValue = "";

			// visa_expiry_date
			$this->visa_expiry_date->LinkCustomAttributes = "";
			$this->visa_expiry_date->HrefValue = "";

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

			// qualifications
			$this->qualifications->LinkCustomAttributes = "";
			$this->qualifications->HrefValue = "";

			// cv
			$this->cv->LinkCustomAttributes = "";
			$this->cv->HrefValue = "";
			$this->cv->HrefValue2 = $this->cv->UploadPath . $this->cv->Upload->DbValue;

			// home_phone
			$this->home_phone->LinkCustomAttributes = "";
			$this->home_phone->HrefValue = "";

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

			// total_voluntary_hours
			$this->total_voluntary_hours->LinkCustomAttributes = "";
			$this->total_voluntary_hours->HrefValue = "";

			// overall_evaluation
			$this->overall_evaluation->LinkCustomAttributes = "";
			$this->overall_evaluation->HrefValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";

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
		if (!ew_CheckDateDef($this->date_of_birth->FormValue)) {
			ew_AddMessage($gsFormError, $this->date_of_birth->FldErrMsg());
		}
		if (!ew_CheckInteger($this->unid->FormValue)) {
			ew_AddMessage($gsFormError, $this->unid->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->visa_expiry_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->visa_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->eid_expiry_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->eid_expiry_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->passport_ex_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->passport_ex_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->overall_evaluation->FormValue)) {
			ew_AddMessage($gsFormError, $this->overall_evaluation->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("user_attachments", $DetailTblVar) && $GLOBALS["user_attachments"]->DetailAdd) {
			if (!isset($GLOBALS["user_attachments_grid"])) $GLOBALS["user_attachments_grid"] = new cuser_attachments_grid(); // get detail page object
			$GLOBALS["user_attachments_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
			$this->personal_photo->OldUploadPath = "../images";
			$this->personal_photo->UploadPath = $this->personal_photo->OldUploadPath;
			$this->visa_copy->OldUploadPath = "../images";
			$this->visa_copy->UploadPath = $this->visa_copy->OldUploadPath;
			$this->emirates_id_copy->OldUploadPath = "../images";
			$this->emirates_id_copy->UploadPath = $this->emirates_id_copy->OldUploadPath;
			$this->passport_copy->OldUploadPath = "../images";
			$this->passport_copy->UploadPath = $this->passport_copy->OldUploadPath;
			$this->cv->OldUploadPath = "../images";
			$this->cv->UploadPath = $this->cv->OldUploadPath;
		}
		$rsnew = array();

		// group_id
		$this->group_id->SetDbValueDef($rsnew, $this->group_id->CurrentValue, NULL, FALSE);

		// full_name_ar
		$this->full_name_ar->SetDbValueDef($rsnew, $this->full_name_ar->CurrentValue, NULL, FALSE);

		// full_name_en
		$this->full_name_en->SetDbValueDef($rsnew, $this->full_name_en->CurrentValue, NULL, FALSE);

		// date_of_birth
		$this->date_of_birth->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date_of_birth->CurrentValue, 0), NULL, FALSE);

		// personal_photo
		if ($this->personal_photo->Visible && !$this->personal_photo->Upload->KeepFile) {
			$this->personal_photo->Upload->DbValue = ""; // No need to delete old file
			if ($this->personal_photo->Upload->FileName == "") {
				$rsnew['personal_photo'] = NULL;
			} else {
				$rsnew['personal_photo'] = $this->personal_photo->Upload->FileName;
			}
		}

		// gender
		$this->gender->SetDbValueDef($rsnew, $this->gender->CurrentValue, NULL, FALSE);

		// blood_type
		$this->blood_type->SetDbValueDef($rsnew, $this->blood_type->CurrentValue, NULL, FALSE);

		// driving_licence
		$this->driving_licence->SetDbValueDef($rsnew, $this->driving_licence->CurrentValue, NULL, FALSE);

		// job
		$this->job->SetDbValueDef($rsnew, $this->job->CurrentValue, NULL, FALSE);

		// volunteering_type
		$this->volunteering_type->SetDbValueDef($rsnew, $this->volunteering_type->CurrentValue, NULL, FALSE);

		// marital_status
		$this->marital_status->SetDbValueDef($rsnew, $this->marital_status->CurrentValue, NULL, FALSE);

		// nationality_type
		$this->nationality_type->SetDbValueDef($rsnew, $this->nationality_type->CurrentValue, NULL, FALSE);

		// nationality
		$this->nationality->SetDbValueDef($rsnew, $this->nationality->CurrentValue, NULL, FALSE);

		// unid
		$this->unid->SetDbValueDef($rsnew, $this->unid->CurrentValue, NULL, FALSE);

		// visa_expiry_date
		$this->visa_expiry_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->visa_expiry_date->CurrentValue, 0), NULL, FALSE);

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

		// qualifications
		$this->qualifications->SetDbValueDef($rsnew, $this->qualifications->CurrentValue, NULL, FALSE);

		// cv
		if ($this->cv->Visible && !$this->cv->Upload->KeepFile) {
			$this->cv->Upload->DbValue = ""; // No need to delete old file
			if ($this->cv->Upload->FileName == "") {
				$rsnew['cv'] = NULL;
			} else {
				$rsnew['cv'] = $this->cv->Upload->FileName;
			}
		}

		// home_phone
		$this->home_phone->SetDbValueDef($rsnew, $this->home_phone->CurrentValue, NULL, FALSE);

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

		// total_voluntary_hours
		$this->total_voluntary_hours->SetDbValueDef($rsnew, $this->total_voluntary_hours->CurrentValue, NULL, FALSE);

		// overall_evaluation
		$this->overall_evaluation->SetDbValueDef($rsnew, $this->overall_evaluation->CurrentValue, NULL, FALSE);

		// admin_approval
		$this->admin_approval->SetDbValueDef($rsnew, $this->admin_approval->CurrentValue, NULL, FALSE);

		// admin_comment
		$this->admin_comment->SetDbValueDef($rsnew, $this->admin_comment->CurrentValue, NULL, FALSE);

		// security_approval
		$this->security_approval->SetDbValueDef($rsnew, $this->security_approval->CurrentValue, NULL, FALSE);

		// security_comment
		$this->security_comment->SetDbValueDef($rsnew, $this->security_comment->CurrentValue, NULL, FALSE);
		if ($this->personal_photo->Visible && !$this->personal_photo->Upload->KeepFile) {
			$this->personal_photo->UploadPath = "../images";
			if (!ew_Empty($this->personal_photo->Upload->Value)) {
				if ($this->personal_photo->Upload->FileName == $this->personal_photo->Upload->DbValue) { // Overwrite if same file name
					$this->personal_photo->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['personal_photo'] = ew_UploadFileNameEx($this->personal_photo->PhysicalUploadPath(), $rsnew['personal_photo']); // Get new file name
				}
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
		if ($this->cv->Visible && !$this->cv->Upload->KeepFile) {
			$this->cv->UploadPath = "../images";
			if (!ew_Empty($this->cv->Upload->Value)) {
				if ($this->cv->Upload->FileName == $this->cv->Upload->DbValue) { // Overwrite if same file name
					$this->cv->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['cv'] = ew_UploadFileNameEx($this->cv->PhysicalUploadPath(), $rsnew['cv']); // Get new file name
				}
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
				if ($this->personal_photo->Visible && !$this->personal_photo->Upload->KeepFile) {
					if (!ew_Empty($this->personal_photo->Upload->Value)) {
						if (!$this->personal_photo->Upload->SaveToFile($rsnew['personal_photo'], TRUE)) {
							$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
							return FALSE;
						}
					}
					if ($this->personal_photo->Upload->DbValue <> "")
						@unlink($this->personal_photo->OldPhysicalUploadPath() . $this->personal_photo->Upload->DbValue);
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
				if ($this->cv->Visible && !$this->cv->Upload->KeepFile) {
					if (!ew_Empty($this->cv->Upload->Value)) {
						if (!$this->cv->Upload->SaveToFile($rsnew['cv'], TRUE)) {
							$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
							return FALSE;
						}
					}
					if ($this->cv->Upload->DbValue <> "")
						@unlink($this->cv->OldPhysicalUploadPath() . $this->cv->Upload->DbValue);
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

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("user_attachments", $DetailTblVar) && $GLOBALS["user_attachments"]->DetailAdd) {
				$GLOBALS["user_attachments"]->_userid->setSessionValue($this->user_id->CurrentValue); // Set master key
				if (!isset($GLOBALS["user_attachments_grid"])) $GLOBALS["user_attachments_grid"] = new cuser_attachments_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "user_attachments"); // Load user level of detail table
				$AddRow = $GLOBALS["user_attachments_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["user_attachments"]->_userid->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// personal_photo
		ew_CleanUploadTempPath($this->personal_photo, $this->personal_photo->Upload->Index);

		// visa_copy
		ew_CleanUploadTempPath($this->visa_copy, $this->visa_copy->Upload->Index);

		// emirates_id_copy
		ew_CleanUploadTempPath($this->emirates_id_copy, $this->emirates_id_copy->Upload->Index);

		// passport_copy
		ew_CleanUploadTempPath($this->passport_copy, $this->passport_copy->Upload->Index);

		// cv
		ew_CleanUploadTempPath($this->cv, $this->cv->Upload->Index);
		return $AddRow;
	}

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("user_attachments", $DetailTblVar)) {
				if (!isset($GLOBALS["user_attachments_grid"]))
					$GLOBALS["user_attachments_grid"] = new cuser_attachments_grid;
				if ($GLOBALS["user_attachments_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["user_attachments_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["user_attachments_grid"]->CurrentMode = "add";
					$GLOBALS["user_attachments_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["user_attachments_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["user_attachments_grid"]->setStartRecordNumber(1);
					$GLOBALS["user_attachments_grid"]->_userid->FldIsDetailKey = TRUE;
					$GLOBALS["user_attachments_grid"]->_userid->CurrentValue = $this->user_id->CurrentValue;
					$GLOBALS["user_attachments_grid"]->_userid->setSessionValue($GLOBALS["user_attachments_grid"]->_userid->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($users_add)) $users_add = new cusers_add();

// Page init
$users_add->Page_Init();

// Page main
$users_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fusersadd = new ew_Form("fusersadd", "add");

// Validate form
fusersadd.Validate = function() {
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
fusersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fusersadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fusersadd.MultiPage = new ew_MultiPage("fusersadd");

// Dynamic selection lists
fusersadd.Lists["x_group_id[]"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
fusersadd.Lists["x_group_id[]"].Data = "<?php echo $users_add->group_id->LookupFilterQuery(FALSE, "add") ?>";
fusersadd.Lists["x_gender"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_gender"].Options = <?php echo json_encode($users_add->gender->Options()) ?>;
fusersadd.Lists["x_blood_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_blood_type"].Options = <?php echo json_encode($users_add->blood_type->Options()) ?>;
fusersadd.Lists["x_driving_licence"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_driving_licence"].Options = <?php echo json_encode($users_add->driving_licence->Options()) ?>;
fusersadd.Lists["x_job"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_job"].Options = <?php echo json_encode($users_add->job->Options()) ?>;
fusersadd.Lists["x_volunteering_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_volunteering_type"].Options = <?php echo json_encode($users_add->volunteering_type->Options()) ?>;
fusersadd.Lists["x_marital_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_marital_status"].Options = <?php echo json_encode($users_add->marital_status->Options()) ?>;
fusersadd.Lists["x_nationality_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_nationality_type"].Options = <?php echo json_encode($users_add->nationality_type->Options()) ?>;
fusersadd.Lists["x_current_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_current_emirate"].Options = <?php echo json_encode($users_add->current_emirate->Options()) ?>;
fusersadd.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_admin_approval"].Options = <?php echo json_encode($users_add->admin_approval->Options()) ?>;
fusersadd.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersadd.Lists["x_security_approval"].Options = <?php echo json_encode($users_add->security_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php echo include_once('users_extra.php'); ?>
<script>
</script>
<?php $users_add->ShowPageHeader(); ?>
<?php
$users_add->ShowMessage();
?>
<form name="fusersadd" id="fusersadd" class="<?php echo $users_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($users_add->IsModal) ?>">
<?php if (!$users_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<div class="ewMultiPage"><!-- multi-page -->
<div class="nav-tabs-custom" id="users_add"><!-- multi-page .nav-tabs-custom -->
	<ul class="nav<?php echo $users_add->MultiPages->NavStyle() ?>">
		<li<?php echo $users_add->MultiPages->TabStyle("1") ?>><a href="#tab_users1" data-toggle="tab"><?php echo $users->PageCaption(1) ?></a></li>
		<li<?php echo $users_add->MultiPages->TabStyle("2") ?>><a href="#tab_users2" data-toggle="tab"><?php echo $users->PageCaption(2) ?></a></li>
		<li<?php echo $users_add->MultiPages->TabStyle("3") ?>><a href="#tab_users3" data-toggle="tab"><?php echo $users->PageCaption(3) ?></a></li>
		<li<?php echo $users_add->MultiPages->TabStyle("4") ?>><a href="#tab_users4" data-toggle="tab"><?php echo $users->PageCaption(4) ?></a></li>
		<li<?php echo $users_add->MultiPages->TabStyle("5") ?>><a href="#tab_users5" data-toggle="tab"><?php echo $users->PageCaption(5) ?></a></li>
		<li<?php echo $users_add->MultiPages->TabStyle("6") ?>><a href="#tab_users6" data-toggle="tab"><?php echo $users->PageCaption(6) ?></a></li>
		<li<?php echo $users_add->MultiPages->TabStyle("7") ?>><a href="#tab_users7" data-toggle="tab"><?php echo $users->PageCaption(7) ?></a></li>
	</ul>
	<div class="tab-content"><!-- multi-page .nav-tabs-custom .tab-content -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("1") ?>" id="tab_users1"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd1" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->group_id->Visible) { // group_id ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_group_id" class="form-group">
		<label id="elh_users_group_id" for="x_group_id" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->group_id->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->group_id->CellAttributes() ?>>
<span id="el_users_group_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_group_id"><?php echo (strval($users->group_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $users->group_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($users->group_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_group_id[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="users" data-field="x_group_id" data-page="1" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $users->group_id->DisplayValueSeparatorAttribute() ?>" name="x_group_id[]" id="x_group_id[]" value="<?php echo $users->group_id->CurrentValue ?>"<?php echo $users->group_id->EditAttributes() ?>>
</span>
<?php echo $users->group_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_group_id">
		<td class="col-sm-2"><span id="elh_users_group_id"><?php echo $users->group_id->FldCaption() ?></span></td>
		<td<?php echo $users->group_id->CellAttributes() ?>>
<span id="el_users_group_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_group_id"><?php echo (strval($users->group_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $users->group_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($users->group_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_group_id[]',m:1,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="users" data-field="x_group_id" data-page="1" data-multiple="1" data-lookup="1" data-value-separator="<?php echo $users->group_id->DisplayValueSeparatorAttribute() ?>" name="x_group_id[]" id="x_group_id[]" value="<?php echo $users->group_id->CurrentValue ?>"<?php echo $users->group_id->EditAttributes() ?>>
</span>
<?php echo $users->group_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_full_name_ar" class="form-group">
		<label id="elh_users_full_name_ar" for="x_full_name_ar" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->full_name_ar->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->full_name_ar->CellAttributes() ?>>
<span id="el_users_full_name_ar">
<input type="text" data-table="users" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($users->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $users->full_name_ar->EditValue ?>"<?php echo $users->full_name_ar->EditAttributes() ?>>
</span>
<?php echo $users->full_name_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_ar">
		<td class="col-sm-2"><span id="elh_users_full_name_ar"><?php echo $users->full_name_ar->FldCaption() ?></span></td>
		<td<?php echo $users->full_name_ar->CellAttributes() ?>>
<span id="el_users_full_name_ar">
<input type="text" data-table="users" data-field="x_full_name_ar" data-page="1" name="x_full_name_ar" id="x_full_name_ar" placeholder="<?php echo ew_HtmlEncode($users->full_name_ar->getPlaceHolder()) ?>" value="<?php echo $users->full_name_ar->EditValue ?>"<?php echo $users->full_name_ar->EditAttributes() ?>>
</span>
<?php echo $users->full_name_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_full_name_en" class="form-group">
		<label id="elh_users_full_name_en" for="x_full_name_en" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->full_name_en->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->full_name_en->CellAttributes() ?>>
<span id="el_users_full_name_en">
<input type="text" data-table="users" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($users->full_name_en->getPlaceHolder()) ?>" value="<?php echo $users->full_name_en->EditValue ?>"<?php echo $users->full_name_en->EditAttributes() ?>>
</span>
<?php echo $users->full_name_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_name_en">
		<td class="col-sm-2"><span id="elh_users_full_name_en"><?php echo $users->full_name_en->FldCaption() ?></span></td>
		<td<?php echo $users->full_name_en->CellAttributes() ?>>
<span id="el_users_full_name_en">
<input type="text" data-table="users" data-field="x_full_name_en" data-page="1" name="x_full_name_en" id="x_full_name_en" placeholder="<?php echo ew_HtmlEncode($users->full_name_en->getPlaceHolder()) ?>" value="<?php echo $users->full_name_en->EditValue ?>"<?php echo $users->full_name_en->EditAttributes() ?>>
</span>
<?php echo $users->full_name_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->date_of_birth->Visible) { // date_of_birth ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_date_of_birth" class="form-group">
		<label id="elh_users_date_of_birth" for="x_date_of_birth" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->date_of_birth->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->date_of_birth->CellAttributes() ?>>
<span id="el_users_date_of_birth">
<input type="text" data-table="users" data-field="x_date_of_birth" data-page="1" name="x_date_of_birth" id="x_date_of_birth" placeholder="<?php echo ew_HtmlEncode($users->date_of_birth->getPlaceHolder()) ?>" value="<?php echo $users->date_of_birth->EditValue ?>"<?php echo $users->date_of_birth->EditAttributes() ?>>
<?php if (!$users->date_of_birth->ReadOnly && !$users->date_of_birth->Disabled && !isset($users->date_of_birth->EditAttrs["readonly"]) && !isset($users->date_of_birth->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_date_of_birth", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->date_of_birth->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_date_of_birth">
		<td class="col-sm-2"><span id="elh_users_date_of_birth"><?php echo $users->date_of_birth->FldCaption() ?></span></td>
		<td<?php echo $users->date_of_birth->CellAttributes() ?>>
<span id="el_users_date_of_birth">
<input type="text" data-table="users" data-field="x_date_of_birth" data-page="1" name="x_date_of_birth" id="x_date_of_birth" placeholder="<?php echo ew_HtmlEncode($users->date_of_birth->getPlaceHolder()) ?>" value="<?php echo $users->date_of_birth->EditValue ?>"<?php echo $users->date_of_birth->EditAttributes() ?>>
<?php if (!$users->date_of_birth->ReadOnly && !$users->date_of_birth->Disabled && !isset($users->date_of_birth->EditAttrs["readonly"]) && !isset($users->date_of_birth->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_date_of_birth", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->date_of_birth->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->personal_photo->Visible) { // personal_photo ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_personal_photo" class="form-group">
		<label id="elh_users_personal_photo" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->personal_photo->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->personal_photo->CellAttributes() ?>>
<span id="el_users_personal_photo">
<div id="fd_x_personal_photo">
<span title="<?php echo $users->personal_photo->FldTitle() ? $users->personal_photo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->personal_photo->ReadOnly || $users->personal_photo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_personal_photo" data-page="1" name="x_personal_photo" id="x_personal_photo"<?php echo $users->personal_photo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_personal_photo" id= "fn_x_personal_photo" value="<?php echo $users->personal_photo->Upload->FileName ?>">
<input type="hidden" name="fa_x_personal_photo" id= "fa_x_personal_photo" value="0">
<input type="hidden" name="fs_x_personal_photo" id= "fs_x_personal_photo" value="255">
<input type="hidden" name="fx_x_personal_photo" id= "fx_x_personal_photo" value="<?php echo $users->personal_photo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_personal_photo" id= "fm_x_personal_photo" value="<?php echo $users->personal_photo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_personal_photo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->personal_photo->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_personal_photo">
		<td class="col-sm-2"><span id="elh_users_personal_photo"><?php echo $users->personal_photo->FldCaption() ?></span></td>
		<td<?php echo $users->personal_photo->CellAttributes() ?>>
<span id="el_users_personal_photo">
<div id="fd_x_personal_photo">
<span title="<?php echo $users->personal_photo->FldTitle() ? $users->personal_photo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->personal_photo->ReadOnly || $users->personal_photo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_personal_photo" data-page="1" name="x_personal_photo" id="x_personal_photo"<?php echo $users->personal_photo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_personal_photo" id= "fn_x_personal_photo" value="<?php echo $users->personal_photo->Upload->FileName ?>">
<input type="hidden" name="fa_x_personal_photo" id= "fa_x_personal_photo" value="0">
<input type="hidden" name="fs_x_personal_photo" id= "fs_x_personal_photo" value="255">
<input type="hidden" name="fx_x_personal_photo" id= "fx_x_personal_photo" value="<?php echo $users->personal_photo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_personal_photo" id= "fm_x_personal_photo" value="<?php echo $users->personal_photo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_personal_photo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->personal_photo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->gender->Visible) { // gender ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_gender" class="form-group">
		<label id="elh_users_gender" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->gender->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->gender->CellAttributes() ?>>
<span id="el_users_gender">
<div id="tp_x_gender" class="ewTemplate"><input type="radio" data-table="users" data-field="x_gender" data-page="1" data-value-separator="<?php echo $users->gender->DisplayValueSeparatorAttribute() ?>" name="x_gender" id="x_gender" value="{value}"<?php echo $users->gender->EditAttributes() ?>></div>
<div id="dsl_x_gender" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->gender->RadioButtonListHtml(FALSE, "x_gender", 1) ?>
</div></div>
</span>
<?php echo $users->gender->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_gender">
		<td class="col-sm-2"><span id="elh_users_gender"><?php echo $users->gender->FldCaption() ?></span></td>
		<td<?php echo $users->gender->CellAttributes() ?>>
<span id="el_users_gender">
<div id="tp_x_gender" class="ewTemplate"><input type="radio" data-table="users" data-field="x_gender" data-page="1" data-value-separator="<?php echo $users->gender->DisplayValueSeparatorAttribute() ?>" name="x_gender" id="x_gender" value="{value}"<?php echo $users->gender->EditAttributes() ?>></div>
<div id="dsl_x_gender" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->gender->RadioButtonListHtml(FALSE, "x_gender", 1) ?>
</div></div>
</span>
<?php echo $users->gender->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->blood_type->Visible) { // blood_type ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_blood_type" class="form-group">
		<label id="elh_users_blood_type" for="x_blood_type" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->blood_type->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->blood_type->CellAttributes() ?>>
<span id="el_users_blood_type">
<select data-table="users" data-field="x_blood_type" data-page="1" data-value-separator="<?php echo $users->blood_type->DisplayValueSeparatorAttribute() ?>" id="x_blood_type" name="x_blood_type"<?php echo $users->blood_type->EditAttributes() ?>>
<?php echo $users->blood_type->SelectOptionListHtml("x_blood_type") ?>
</select>
</span>
<?php echo $users->blood_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_blood_type">
		<td class="col-sm-2"><span id="elh_users_blood_type"><?php echo $users->blood_type->FldCaption() ?></span></td>
		<td<?php echo $users->blood_type->CellAttributes() ?>>
<span id="el_users_blood_type">
<select data-table="users" data-field="x_blood_type" data-page="1" data-value-separator="<?php echo $users->blood_type->DisplayValueSeparatorAttribute() ?>" id="x_blood_type" name="x_blood_type"<?php echo $users->blood_type->EditAttributes() ?>>
<?php echo $users->blood_type->SelectOptionListHtml("x_blood_type") ?>
</select>
</span>
<?php echo $users->blood_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->driving_licence->Visible) { // driving_licence ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_driving_licence" class="form-group">
		<label id="elh_users_driving_licence" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->driving_licence->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->driving_licence->CellAttributes() ?>>
<span id="el_users_driving_licence">
<div id="tp_x_driving_licence" class="ewTemplate"><input type="radio" data-table="users" data-field="x_driving_licence" data-page="1" data-value-separator="<?php echo $users->driving_licence->DisplayValueSeparatorAttribute() ?>" name="x_driving_licence" id="x_driving_licence" value="{value}"<?php echo $users->driving_licence->EditAttributes() ?>></div>
<div id="dsl_x_driving_licence" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->driving_licence->RadioButtonListHtml(FALSE, "x_driving_licence", 1) ?>
</div></div>
</span>
<?php echo $users->driving_licence->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_driving_licence">
		<td class="col-sm-2"><span id="elh_users_driving_licence"><?php echo $users->driving_licence->FldCaption() ?></span></td>
		<td<?php echo $users->driving_licence->CellAttributes() ?>>
<span id="el_users_driving_licence">
<div id="tp_x_driving_licence" class="ewTemplate"><input type="radio" data-table="users" data-field="x_driving_licence" data-page="1" data-value-separator="<?php echo $users->driving_licence->DisplayValueSeparatorAttribute() ?>" name="x_driving_licence" id="x_driving_licence" value="{value}"<?php echo $users->driving_licence->EditAttributes() ?>></div>
<div id="dsl_x_driving_licence" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->driving_licence->RadioButtonListHtml(FALSE, "x_driving_licence", 1) ?>
</div></div>
</span>
<?php echo $users->driving_licence->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->job->Visible) { // job ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_job" class="form-group">
		<label id="elh_users_job" for="x_job" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->job->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->job->CellAttributes() ?>>
<span id="el_users_job">
<select data-table="users" data-field="x_job" data-page="1" data-value-separator="<?php echo $users->job->DisplayValueSeparatorAttribute() ?>" id="x_job" name="x_job"<?php echo $users->job->EditAttributes() ?>>
<?php echo $users->job->SelectOptionListHtml("x_job") ?>
</select>
</span>
<?php echo $users->job->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_job">
		<td class="col-sm-2"><span id="elh_users_job"><?php echo $users->job->FldCaption() ?></span></td>
		<td<?php echo $users->job->CellAttributes() ?>>
<span id="el_users_job">
<select data-table="users" data-field="x_job" data-page="1" data-value-separator="<?php echo $users->job->DisplayValueSeparatorAttribute() ?>" id="x_job" name="x_job"<?php echo $users->job->EditAttributes() ?>>
<?php echo $users->job->SelectOptionListHtml("x_job") ?>
</select>
</span>
<?php echo $users->job->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->volunteering_type->Visible) { // volunteering_type ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_volunteering_type" class="form-group">
		<label id="elh_users_volunteering_type" for="x_volunteering_type" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->volunteering_type->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->volunteering_type->CellAttributes() ?>>
<span id="el_users_volunteering_type">
<select data-table="users" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $users->volunteering_type->DisplayValueSeparatorAttribute() ?>" id="x_volunteering_type" name="x_volunteering_type"<?php echo $users->volunteering_type->EditAttributes() ?>>
<?php echo $users->volunteering_type->SelectOptionListHtml("x_volunteering_type") ?>
</select>
</span>
<?php echo $users->volunteering_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_volunteering_type">
		<td class="col-sm-2"><span id="elh_users_volunteering_type"><?php echo $users->volunteering_type->FldCaption() ?></span></td>
		<td<?php echo $users->volunteering_type->CellAttributes() ?>>
<span id="el_users_volunteering_type">
<select data-table="users" data-field="x_volunteering_type" data-page="1" data-value-separator="<?php echo $users->volunteering_type->DisplayValueSeparatorAttribute() ?>" id="x_volunteering_type" name="x_volunteering_type"<?php echo $users->volunteering_type->EditAttributes() ?>>
<?php echo $users->volunteering_type->SelectOptionListHtml("x_volunteering_type") ?>
</select>
</span>
<?php echo $users->volunteering_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->marital_status->Visible) { // marital_status ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_marital_status" class="form-group">
		<label id="elh_users_marital_status" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->marital_status->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->marital_status->CellAttributes() ?>>
<span id="el_users_marital_status">
<div id="tp_x_marital_status" class="ewTemplate"><input type="radio" data-table="users" data-field="x_marital_status" data-page="1" data-value-separator="<?php echo $users->marital_status->DisplayValueSeparatorAttribute() ?>" name="x_marital_status" id="x_marital_status" value="{value}"<?php echo $users->marital_status->EditAttributes() ?>></div>
<div id="dsl_x_marital_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->marital_status->RadioButtonListHtml(FALSE, "x_marital_status", 1) ?>
</div></div>
</span>
<?php echo $users->marital_status->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_marital_status">
		<td class="col-sm-2"><span id="elh_users_marital_status"><?php echo $users->marital_status->FldCaption() ?></span></td>
		<td<?php echo $users->marital_status->CellAttributes() ?>>
<span id="el_users_marital_status">
<div id="tp_x_marital_status" class="ewTemplate"><input type="radio" data-table="users" data-field="x_marital_status" data-page="1" data-value-separator="<?php echo $users->marital_status->DisplayValueSeparatorAttribute() ?>" name="x_marital_status" id="x_marital_status" value="{value}"<?php echo $users->marital_status->EditAttributes() ?>></div>
<div id="dsl_x_marital_status" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->marital_status->RadioButtonListHtml(FALSE, "x_marital_status", 1) ?>
</div></div>
</span>
<?php echo $users->marital_status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("2") ?>" id="tab_users2"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd2" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->nationality_type->Visible) { // nationality_type ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_nationality_type" class="form-group">
		<label id="elh_users_nationality_type" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->nationality_type->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->nationality_type->CellAttributes() ?>>
<span id="el_users_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="users" data-field="x_nationality_type" data-page="2" data-value-separator="<?php echo $users->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $users->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 2) ?>
</div></div>
</span>
<?php echo $users->nationality_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality_type">
		<td class="col-sm-2"><span id="elh_users_nationality_type"><?php echo $users->nationality_type->FldCaption() ?></span></td>
		<td<?php echo $users->nationality_type->CellAttributes() ?>>
<span id="el_users_nationality_type">
<div id="tp_x_nationality_type" class="ewTemplate"><input type="radio" data-table="users" data-field="x_nationality_type" data-page="2" data-value-separator="<?php echo $users->nationality_type->DisplayValueSeparatorAttribute() ?>" name="x_nationality_type" id="x_nationality_type" value="{value}"<?php echo $users->nationality_type->EditAttributes() ?>></div>
<div id="dsl_x_nationality_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->nationality_type->RadioButtonListHtml(FALSE, "x_nationality_type", 2) ?>
</div></div>
</span>
<?php echo $users->nationality_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->nationality->Visible) { // nationality ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_nationality" class="form-group">
		<label id="elh_users_nationality" for="x_nationality" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->nationality->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->nationality->CellAttributes() ?>>
<span id="el_users_nationality">
<input type="text" data-table="users" data-field="x_nationality" data-page="2" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($users->nationality->getPlaceHolder()) ?>" value="<?php echo $users->nationality->EditValue ?>"<?php echo $users->nationality->EditAttributes() ?>>
</span>
<?php echo $users->nationality->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_nationality">
		<td class="col-sm-2"><span id="elh_users_nationality"><?php echo $users->nationality->FldCaption() ?></span></td>
		<td<?php echo $users->nationality->CellAttributes() ?>>
<span id="el_users_nationality">
<input type="text" data-table="users" data-field="x_nationality" data-page="2" name="x_nationality" id="x_nationality" placeholder="<?php echo ew_HtmlEncode($users->nationality->getPlaceHolder()) ?>" value="<?php echo $users->nationality->EditValue ?>"<?php echo $users->nationality->EditAttributes() ?>>
</span>
<?php echo $users->nationality->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->unid->Visible) { // unid ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_unid" class="form-group">
		<label id="elh_users_unid" for="x_unid" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->unid->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->unid->CellAttributes() ?>>
<span id="el_users_unid">
<input type="text" data-table="users" data-field="x_unid" data-page="2" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($users->unid->getPlaceHolder()) ?>" value="<?php echo $users->unid->EditValue ?>"<?php echo $users->unid->EditAttributes() ?>>
</span>
<?php echo $users->unid->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_unid">
		<td class="col-sm-2"><span id="elh_users_unid"><?php echo $users->unid->FldCaption() ?></span></td>
		<td<?php echo $users->unid->CellAttributes() ?>>
<span id="el_users_unid">
<input type="text" data-table="users" data-field="x_unid" data-page="2" name="x_unid" id="x_unid" size="30" placeholder="<?php echo ew_HtmlEncode($users->unid->getPlaceHolder()) ?>" value="<?php echo $users->unid->EditValue ?>"<?php echo $users->unid->EditAttributes() ?>>
</span>
<?php echo $users->unid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->visa_expiry_date->Visible) { // visa_expiry_date ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_visa_expiry_date" class="form-group">
		<label id="elh_users_visa_expiry_date" for="x_visa_expiry_date" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->visa_expiry_date->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->visa_expiry_date->CellAttributes() ?>>
<span id="el_users_visa_expiry_date">
<input type="text" data-table="users" data-field="x_visa_expiry_date" data-page="2" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->visa_expiry_date->EditValue ?>"<?php echo $users->visa_expiry_date->EditAttributes() ?>>
<?php if (!$users->visa_expiry_date->ReadOnly && !$users->visa_expiry_date->Disabled && !isset($users->visa_expiry_date->EditAttrs["readonly"]) && !isset($users->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->visa_expiry_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_expiry_date">
		<td class="col-sm-2"><span id="elh_users_visa_expiry_date"><?php echo $users->visa_expiry_date->FldCaption() ?></span></td>
		<td<?php echo $users->visa_expiry_date->CellAttributes() ?>>
<span id="el_users_visa_expiry_date">
<input type="text" data-table="users" data-field="x_visa_expiry_date" data-page="2" name="x_visa_expiry_date" id="x_visa_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->visa_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->visa_expiry_date->EditValue ?>"<?php echo $users->visa_expiry_date->EditAttributes() ?>>
<?php if (!$users->visa_expiry_date->ReadOnly && !$users->visa_expiry_date->Disabled && !isset($users->visa_expiry_date->EditAttrs["readonly"]) && !isset($users->visa_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_visa_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->visa_expiry_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->visa_copy->Visible) { // visa_copy ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_visa_copy" class="form-group">
		<label id="elh_users_visa_copy" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->visa_copy->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->visa_copy->CellAttributes() ?>>
<span id="el_users_visa_copy">
<div id="fd_x_visa_copy">
<span title="<?php echo $users->visa_copy->FldTitle() ? $users->visa_copy->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->visa_copy->ReadOnly || $users->visa_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_visa_copy" data-page="2" name="x_visa_copy" id="x_visa_copy"<?php echo $users->visa_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_visa_copy" id= "fn_x_visa_copy" value="<?php echo $users->visa_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_visa_copy" id= "fa_x_visa_copy" value="0">
<input type="hidden" name="fs_x_visa_copy" id= "fs_x_visa_copy" value="255">
<input type="hidden" name="fx_x_visa_copy" id= "fx_x_visa_copy" value="<?php echo $users->visa_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_visa_copy" id= "fm_x_visa_copy" value="<?php echo $users->visa_copy->UploadMaxFileSize ?>">
</div>
<table id="ft_x_visa_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->visa_copy->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_visa_copy">
		<td class="col-sm-2"><span id="elh_users_visa_copy"><?php echo $users->visa_copy->FldCaption() ?></span></td>
		<td<?php echo $users->visa_copy->CellAttributes() ?>>
<span id="el_users_visa_copy">
<div id="fd_x_visa_copy">
<span title="<?php echo $users->visa_copy->FldTitle() ? $users->visa_copy->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->visa_copy->ReadOnly || $users->visa_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_visa_copy" data-page="2" name="x_visa_copy" id="x_visa_copy"<?php echo $users->visa_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_visa_copy" id= "fn_x_visa_copy" value="<?php echo $users->visa_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_visa_copy" id= "fa_x_visa_copy" value="0">
<input type="hidden" name="fs_x_visa_copy" id= "fs_x_visa_copy" value="255">
<input type="hidden" name="fx_x_visa_copy" id= "fx_x_visa_copy" value="<?php echo $users->visa_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_visa_copy" id= "fm_x_visa_copy" value="<?php echo $users->visa_copy->UploadMaxFileSize ?>">
</div>
<table id="ft_x_visa_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->visa_copy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->current_emirate->Visible) { // current_emirate ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_current_emirate" class="form-group">
		<label id="elh_users_current_emirate" for="x_current_emirate" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->current_emirate->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->current_emirate->CellAttributes() ?>>
<span id="el_users_current_emirate">
<select data-table="users" data-field="x_current_emirate" data-page="2" data-value-separator="<?php echo $users->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $users->current_emirate->EditAttributes() ?>>
<?php echo $users->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
<?php echo $users->current_emirate->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_current_emirate">
		<td class="col-sm-2"><span id="elh_users_current_emirate"><?php echo $users->current_emirate->FldCaption() ?></span></td>
		<td<?php echo $users->current_emirate->CellAttributes() ?>>
<span id="el_users_current_emirate">
<select data-table="users" data-field="x_current_emirate" data-page="2" data-value-separator="<?php echo $users->current_emirate->DisplayValueSeparatorAttribute() ?>" id="x_current_emirate" name="x_current_emirate"<?php echo $users->current_emirate->EditAttributes() ?>>
<?php echo $users->current_emirate->SelectOptionListHtml("x_current_emirate") ?>
</select>
</span>
<?php echo $users->current_emirate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->full_address->Visible) { // full_address ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_full_address" class="form-group">
		<label id="elh_users_full_address" for="x_full_address" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->full_address->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->full_address->CellAttributes() ?>>
<span id="el_users_full_address">
<input type="text" data-table="users" data-field="x_full_address" data-page="2" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($users->full_address->getPlaceHolder()) ?>" value="<?php echo $users->full_address->EditValue ?>"<?php echo $users->full_address->EditAttributes() ?>>
</span>
<?php echo $users->full_address->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_full_address">
		<td class="col-sm-2"><span id="elh_users_full_address"><?php echo $users->full_address->FldCaption() ?></span></td>
		<td<?php echo $users->full_address->CellAttributes() ?>>
<span id="el_users_full_address">
<input type="text" data-table="users" data-field="x_full_address" data-page="2" name="x_full_address" id="x_full_address" placeholder="<?php echo ew_HtmlEncode($users->full_address->getPlaceHolder()) ?>" value="<?php echo $users->full_address->EditValue ?>"<?php echo $users->full_address->EditAttributes() ?>>
</span>
<?php echo $users->full_address->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("3") ?>" id="tab_users3"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd3" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->emirates_id_number->Visible) { // emirates_id_number ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_emirates_id_number" class="form-group">
		<label id="elh_users_emirates_id_number" for="x_emirates_id_number" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->emirates_id_number->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->emirates_id_number->CellAttributes() ?>>
<span id="el_users_emirates_id_number">
<input type="text" data-table="users" data-field="x_emirates_id_number" data-page="3" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($users->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $users->emirates_id_number->EditValue ?>"<?php echo $users->emirates_id_number->EditAttributes() ?>>
</span>
<?php echo $users->emirates_id_number->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_number">
		<td class="col-sm-2"><span id="elh_users_emirates_id_number"><?php echo $users->emirates_id_number->FldCaption() ?></span></td>
		<td<?php echo $users->emirates_id_number->CellAttributes() ?>>
<span id="el_users_emirates_id_number">
<input type="text" data-table="users" data-field="x_emirates_id_number" data-page="3" name="x_emirates_id_number" id="x_emirates_id_number" placeholder="<?php echo ew_HtmlEncode($users->emirates_id_number->getPlaceHolder()) ?>" value="<?php echo $users->emirates_id_number->EditValue ?>"<?php echo $users->emirates_id_number->EditAttributes() ?>>
</span>
<?php echo $users->emirates_id_number->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->eid_expiry_date->Visible) { // eid_expiry_date ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_eid_expiry_date" class="form-group">
		<label id="elh_users_eid_expiry_date" for="x_eid_expiry_date" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->eid_expiry_date->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->eid_expiry_date->CellAttributes() ?>>
<span id="el_users_eid_expiry_date">
<input type="text" data-table="users" data-field="x_eid_expiry_date" data-page="3" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->eid_expiry_date->EditValue ?>"<?php echo $users->eid_expiry_date->EditAttributes() ?>>
<?php if (!$users->eid_expiry_date->ReadOnly && !$users->eid_expiry_date->Disabled && !isset($users->eid_expiry_date->EditAttrs["readonly"]) && !isset($users->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->eid_expiry_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_eid_expiry_date">
		<td class="col-sm-2"><span id="elh_users_eid_expiry_date"><?php echo $users->eid_expiry_date->FldCaption() ?></span></td>
		<td<?php echo $users->eid_expiry_date->CellAttributes() ?>>
<span id="el_users_eid_expiry_date">
<input type="text" data-table="users" data-field="x_eid_expiry_date" data-page="3" name="x_eid_expiry_date" id="x_eid_expiry_date" placeholder="<?php echo ew_HtmlEncode($users->eid_expiry_date->getPlaceHolder()) ?>" value="<?php echo $users->eid_expiry_date->EditValue ?>"<?php echo $users->eid_expiry_date->EditAttributes() ?>>
<?php if (!$users->eid_expiry_date->ReadOnly && !$users->eid_expiry_date->Disabled && !isset($users->eid_expiry_date->EditAttrs["readonly"]) && !isset($users->eid_expiry_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_eid_expiry_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->eid_expiry_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->emirates_id_copy->Visible) { // emirates_id_copy ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_emirates_id_copy" class="form-group">
		<label id="elh_users_emirates_id_copy" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->emirates_id_copy->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->emirates_id_copy->CellAttributes() ?>>
<span id="el_users_emirates_id_copy">
<div id="fd_x_emirates_id_copy">
<span title="<?php echo $users->emirates_id_copy->FldTitle() ? $users->emirates_id_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->emirates_id_copy->ReadOnly || $users->emirates_id_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_emirates_id_copy" data-page="3" name="x_emirates_id_copy" id="x_emirates_id_copy" multiple="multiple"<?php echo $users->emirates_id_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_emirates_id_copy" id= "fn_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_emirates_id_copy" id= "fa_x_emirates_id_copy" value="0">
<input type="hidden" name="fs_x_emirates_id_copy" id= "fs_x_emirates_id_copy" value="65535">
<input type="hidden" name="fx_x_emirates_id_copy" id= "fx_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_emirates_id_copy" id= "fm_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_emirates_id_copy" id= "fc_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_emirates_id_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->emirates_id_copy->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_emirates_id_copy">
		<td class="col-sm-2"><span id="elh_users_emirates_id_copy"><?php echo $users->emirates_id_copy->FldCaption() ?></span></td>
		<td<?php echo $users->emirates_id_copy->CellAttributes() ?>>
<span id="el_users_emirates_id_copy">
<div id="fd_x_emirates_id_copy">
<span title="<?php echo $users->emirates_id_copy->FldTitle() ? $users->emirates_id_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->emirates_id_copy->ReadOnly || $users->emirates_id_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_emirates_id_copy" data-page="3" name="x_emirates_id_copy" id="x_emirates_id_copy" multiple="multiple"<?php echo $users->emirates_id_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_emirates_id_copy" id= "fn_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_emirates_id_copy" id= "fa_x_emirates_id_copy" value="0">
<input type="hidden" name="fs_x_emirates_id_copy" id= "fs_x_emirates_id_copy" value="65535">
<input type="hidden" name="fx_x_emirates_id_copy" id= "fx_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_emirates_id_copy" id= "fm_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_emirates_id_copy" id= "fc_x_emirates_id_copy" value="<?php echo $users->emirates_id_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_emirates_id_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->emirates_id_copy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->passport_number->Visible) { // passport_number ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_passport_number" class="form-group">
		<label id="elh_users_passport_number" for="x_passport_number" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->passport_number->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->passport_number->CellAttributes() ?>>
<span id="el_users_passport_number">
<input type="text" data-table="users" data-field="x_passport_number" data-page="3" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($users->passport_number->getPlaceHolder()) ?>" value="<?php echo $users->passport_number->EditValue ?>"<?php echo $users->passport_number->EditAttributes() ?>>
</span>
<?php echo $users->passport_number->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_number">
		<td class="col-sm-2"><span id="elh_users_passport_number"><?php echo $users->passport_number->FldCaption() ?></span></td>
		<td<?php echo $users->passport_number->CellAttributes() ?>>
<span id="el_users_passport_number">
<input type="text" data-table="users" data-field="x_passport_number" data-page="3" name="x_passport_number" id="x_passport_number" placeholder="<?php echo ew_HtmlEncode($users->passport_number->getPlaceHolder()) ?>" value="<?php echo $users->passport_number->EditValue ?>"<?php echo $users->passport_number->EditAttributes() ?>>
</span>
<?php echo $users->passport_number->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->passport_ex_date->Visible) { // passport_ex_date ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_passport_ex_date" class="form-group">
		<label id="elh_users_passport_ex_date" for="x_passport_ex_date" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->passport_ex_date->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->passport_ex_date->CellAttributes() ?>>
<span id="el_users_passport_ex_date">
<input type="text" data-table="users" data-field="x_passport_ex_date" data-page="3" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($users->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $users->passport_ex_date->EditValue ?>"<?php echo $users->passport_ex_date->EditAttributes() ?>>
<?php if (!$users->passport_ex_date->ReadOnly && !$users->passport_ex_date->Disabled && !isset($users->passport_ex_date->EditAttrs["readonly"]) && !isset($users->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->passport_ex_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_ex_date">
		<td class="col-sm-2"><span id="elh_users_passport_ex_date"><?php echo $users->passport_ex_date->FldCaption() ?></span></td>
		<td<?php echo $users->passport_ex_date->CellAttributes() ?>>
<span id="el_users_passport_ex_date">
<input type="text" data-table="users" data-field="x_passport_ex_date" data-page="3" name="x_passport_ex_date" id="x_passport_ex_date" placeholder="<?php echo ew_HtmlEncode($users->passport_ex_date->getPlaceHolder()) ?>" value="<?php echo $users->passport_ex_date->EditValue ?>"<?php echo $users->passport_ex_date->EditAttributes() ?>>
<?php if (!$users->passport_ex_date->ReadOnly && !$users->passport_ex_date->Disabled && !isset($users->passport_ex_date->EditAttrs["readonly"]) && !isset($users->passport_ex_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fusersadd", "x_passport_ex_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $users->passport_ex_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->passport_copy->Visible) { // passport_copy ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_passport_copy" class="form-group">
		<label id="elh_users_passport_copy" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->passport_copy->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->passport_copy->CellAttributes() ?>>
<span id="el_users_passport_copy">
<div id="fd_x_passport_copy">
<span title="<?php echo $users->passport_copy->FldTitle() ? $users->passport_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->passport_copy->ReadOnly || $users->passport_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_passport_copy" data-page="3" name="x_passport_copy" id="x_passport_copy" multiple="multiple"<?php echo $users->passport_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_passport_copy" id= "fn_x_passport_copy" value="<?php echo $users->passport_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_passport_copy" id= "fa_x_passport_copy" value="0">
<input type="hidden" name="fs_x_passport_copy" id= "fs_x_passport_copy" value="65535">
<input type="hidden" name="fx_x_passport_copy" id= "fx_x_passport_copy" value="<?php echo $users->passport_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_passport_copy" id= "fm_x_passport_copy" value="<?php echo $users->passport_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_passport_copy" id= "fc_x_passport_copy" value="<?php echo $users->passport_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_passport_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->passport_copy->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_passport_copy">
		<td class="col-sm-2"><span id="elh_users_passport_copy"><?php echo $users->passport_copy->FldCaption() ?></span></td>
		<td<?php echo $users->passport_copy->CellAttributes() ?>>
<span id="el_users_passport_copy">
<div id="fd_x_passport_copy">
<span title="<?php echo $users->passport_copy->FldTitle() ? $users->passport_copy->FldTitle() : $Language->Phrase("ChooseFiles") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->passport_copy->ReadOnly || $users->passport_copy->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_passport_copy" data-page="3" name="x_passport_copy" id="x_passport_copy" multiple="multiple"<?php echo $users->passport_copy->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_passport_copy" id= "fn_x_passport_copy" value="<?php echo $users->passport_copy->Upload->FileName ?>">
<input type="hidden" name="fa_x_passport_copy" id= "fa_x_passport_copy" value="0">
<input type="hidden" name="fs_x_passport_copy" id= "fs_x_passport_copy" value="65535">
<input type="hidden" name="fx_x_passport_copy" id= "fx_x_passport_copy" value="<?php echo $users->passport_copy->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_passport_copy" id= "fm_x_passport_copy" value="<?php echo $users->passport_copy->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_passport_copy" id= "fc_x_passport_copy" value="<?php echo $users->passport_copy->UploadMaxFileCount ?>">
</div>
<table id="ft_x_passport_copy" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->passport_copy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("4") ?>" id="tab_users4"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd4" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->place_of_work->Visible) { // place_of_work ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_place_of_work" class="form-group">
		<label id="elh_users_place_of_work" for="x_place_of_work" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->place_of_work->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->place_of_work->CellAttributes() ?>>
<span id="el_users_place_of_work">
<input type="text" data-table="users" data-field="x_place_of_work" data-page="4" name="x_place_of_work" id="x_place_of_work" placeholder="<?php echo ew_HtmlEncode($users->place_of_work->getPlaceHolder()) ?>" value="<?php echo $users->place_of_work->EditValue ?>"<?php echo $users->place_of_work->EditAttributes() ?>>
</span>
<?php echo $users->place_of_work->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_place_of_work">
		<td class="col-sm-2"><span id="elh_users_place_of_work"><?php echo $users->place_of_work->FldCaption() ?></span></td>
		<td<?php echo $users->place_of_work->CellAttributes() ?>>
<span id="el_users_place_of_work">
<input type="text" data-table="users" data-field="x_place_of_work" data-page="4" name="x_place_of_work" id="x_place_of_work" placeholder="<?php echo ew_HtmlEncode($users->place_of_work->getPlaceHolder()) ?>" value="<?php echo $users->place_of_work->EditValue ?>"<?php echo $users->place_of_work->EditAttributes() ?>>
</span>
<?php echo $users->place_of_work->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->qualifications->Visible) { // qualifications ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_qualifications" class="form-group">
		<label id="elh_users_qualifications" for="x_qualifications" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->qualifications->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->qualifications->CellAttributes() ?>>
<span id="el_users_qualifications">
<textarea data-table="users" data-field="x_qualifications" data-page="4" name="x_qualifications" id="x_qualifications" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->qualifications->getPlaceHolder()) ?>"<?php echo $users->qualifications->EditAttributes() ?>><?php echo $users->qualifications->EditValue ?></textarea>
</span>
<?php echo $users->qualifications->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_qualifications">
		<td class="col-sm-2"><span id="elh_users_qualifications"><?php echo $users->qualifications->FldCaption() ?></span></td>
		<td<?php echo $users->qualifications->CellAttributes() ?>>
<span id="el_users_qualifications">
<textarea data-table="users" data-field="x_qualifications" data-page="4" name="x_qualifications" id="x_qualifications" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->qualifications->getPlaceHolder()) ?>"<?php echo $users->qualifications->EditAttributes() ?>><?php echo $users->qualifications->EditValue ?></textarea>
</span>
<?php echo $users->qualifications->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->cv->Visible) { // cv ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_cv" class="form-group">
		<label id="elh_users_cv" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->cv->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->cv->CellAttributes() ?>>
<span id="el_users_cv">
<div id="fd_x_cv">
<span title="<?php echo $users->cv->FldTitle() ? $users->cv->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->cv->ReadOnly || $users->cv->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_cv" data-page="4" name="x_cv" id="x_cv"<?php echo $users->cv->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_cv" id= "fn_x_cv" value="<?php echo $users->cv->Upload->FileName ?>">
<input type="hidden" name="fa_x_cv" id= "fa_x_cv" value="0">
<input type="hidden" name="fs_x_cv" id= "fs_x_cv" value="65535">
<input type="hidden" name="fx_x_cv" id= "fx_x_cv" value="<?php echo $users->cv->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_cv" id= "fm_x_cv" value="<?php echo $users->cv->UploadMaxFileSize ?>">
</div>
<table id="ft_x_cv" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->cv->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_cv">
		<td class="col-sm-2"><span id="elh_users_cv"><?php echo $users->cv->FldCaption() ?></span></td>
		<td<?php echo $users->cv->CellAttributes() ?>>
<span id="el_users_cv">
<div id="fd_x_cv">
<span title="<?php echo $users->cv->FldTitle() ? $users->cv->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($users->cv->ReadOnly || $users->cv->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="users" data-field="x_cv" data-page="4" name="x_cv" id="x_cv"<?php echo $users->cv->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_cv" id= "fn_x_cv" value="<?php echo $users->cv->Upload->FileName ?>">
<input type="hidden" name="fa_x_cv" id= "fa_x_cv" value="0">
<input type="hidden" name="fs_x_cv" id= "fs_x_cv" value="65535">
<input type="hidden" name="fx_x_cv" id= "fx_x_cv" value="<?php echo $users->cv->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_cv" id= "fm_x_cv" value="<?php echo $users->cv->UploadMaxFileSize ?>">
</div>
<table id="ft_x_cv" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $users->cv->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->home_phone->Visible) { // home_phone ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_home_phone" class="form-group">
		<label id="elh_users_home_phone" for="x_home_phone" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->home_phone->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->home_phone->CellAttributes() ?>>
<span id="el_users_home_phone">
<input type="text" data-table="users" data-field="x_home_phone" data-page="4" name="x_home_phone" id="x_home_phone" placeholder="<?php echo ew_HtmlEncode($users->home_phone->getPlaceHolder()) ?>" value="<?php echo $users->home_phone->EditValue ?>"<?php echo $users->home_phone->EditAttributes() ?>>
</span>
<?php echo $users->home_phone->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_home_phone">
		<td class="col-sm-2"><span id="elh_users_home_phone"><?php echo $users->home_phone->FldCaption() ?></span></td>
		<td<?php echo $users->home_phone->CellAttributes() ?>>
<span id="el_users_home_phone">
<input type="text" data-table="users" data-field="x_home_phone" data-page="4" name="x_home_phone" id="x_home_phone" placeholder="<?php echo ew_HtmlEncode($users->home_phone->getPlaceHolder()) ?>" value="<?php echo $users->home_phone->EditValue ?>"<?php echo $users->home_phone->EditAttributes() ?>>
</span>
<?php echo $users->home_phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->work_phone->Visible) { // work_phone ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_work_phone" class="form-group">
		<label id="elh_users_work_phone" for="x_work_phone" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->work_phone->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->work_phone->CellAttributes() ?>>
<span id="el_users_work_phone">
<input type="text" data-table="users" data-field="x_work_phone" data-page="4" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($users->work_phone->getPlaceHolder()) ?>" value="<?php echo $users->work_phone->EditValue ?>"<?php echo $users->work_phone->EditAttributes() ?>>
</span>
<?php echo $users->work_phone->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_work_phone">
		<td class="col-sm-2"><span id="elh_users_work_phone"><?php echo $users->work_phone->FldCaption() ?></span></td>
		<td<?php echo $users->work_phone->CellAttributes() ?>>
<span id="el_users_work_phone">
<input type="text" data-table="users" data-field="x_work_phone" data-page="4" name="x_work_phone" id="x_work_phone" placeholder="<?php echo ew_HtmlEncode($users->work_phone->getPlaceHolder()) ?>" value="<?php echo $users->work_phone->EditValue ?>"<?php echo $users->work_phone->EditAttributes() ?>>
</span>
<?php echo $users->work_phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->mobile_phone->Visible) { // mobile_phone ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_mobile_phone" class="form-group">
		<label id="elh_users_mobile_phone" for="x_mobile_phone" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->mobile_phone->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->mobile_phone->CellAttributes() ?>>
<span id="el_users_mobile_phone">
<input type="text" data-table="users" data-field="x_mobile_phone" data-page="4" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($users->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $users->mobile_phone->EditValue ?>"<?php echo $users->mobile_phone->EditAttributes() ?>>
</span>
<?php echo $users->mobile_phone->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_users_mobile_phone"><?php echo $users->mobile_phone->FldCaption() ?></span></td>
		<td<?php echo $users->mobile_phone->CellAttributes() ?>>
<span id="el_users_mobile_phone">
<input type="text" data-table="users" data-field="x_mobile_phone" data-page="4" name="x_mobile_phone" id="x_mobile_phone" placeholder="<?php echo ew_HtmlEncode($users->mobile_phone->getPlaceHolder()) ?>" value="<?php echo $users->mobile_phone->EditValue ?>"<?php echo $users->mobile_phone->EditAttributes() ?>>
</span>
<?php echo $users->mobile_phone->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->fax->Visible) { // fax ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_fax" class="form-group">
		<label id="elh_users_fax" for="x_fax" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->fax->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->fax->CellAttributes() ?>>
<span id="el_users_fax">
<input type="text" data-table="users" data-field="x_fax" data-page="4" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($users->fax->getPlaceHolder()) ?>" value="<?php echo $users->fax->EditValue ?>"<?php echo $users->fax->EditAttributes() ?>>
</span>
<?php echo $users->fax->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_fax">
		<td class="col-sm-2"><span id="elh_users_fax"><?php echo $users->fax->FldCaption() ?></span></td>
		<td<?php echo $users->fax->CellAttributes() ?>>
<span id="el_users_fax">
<input type="text" data-table="users" data-field="x_fax" data-page="4" name="x_fax" id="x_fax" placeholder="<?php echo ew_HtmlEncode($users->fax->getPlaceHolder()) ?>" value="<?php echo $users->fax->EditValue ?>"<?php echo $users->fax->EditAttributes() ?>>
</span>
<?php echo $users->fax->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->pobbox->Visible) { // pobbox ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_pobbox" class="form-group">
		<label id="elh_users_pobbox" for="x_pobbox" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->pobbox->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->pobbox->CellAttributes() ?>>
<span id="el_users_pobbox">
<input type="text" data-table="users" data-field="x_pobbox" data-page="4" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($users->pobbox->getPlaceHolder()) ?>" value="<?php echo $users->pobbox->EditValue ?>"<?php echo $users->pobbox->EditAttributes() ?>>
</span>
<?php echo $users->pobbox->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_pobbox">
		<td class="col-sm-2"><span id="elh_users_pobbox"><?php echo $users->pobbox->FldCaption() ?></span></td>
		<td<?php echo $users->pobbox->CellAttributes() ?>>
<span id="el_users_pobbox">
<input type="text" data-table="users" data-field="x_pobbox" data-page="4" name="x_pobbox" id="x_pobbox" placeholder="<?php echo ew_HtmlEncode($users->pobbox->getPlaceHolder()) ?>" value="<?php echo $users->pobbox->EditValue ?>"<?php echo $users->pobbox->EditAttributes() ?>>
</span>
<?php echo $users->pobbox->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("5") ?>" id="tab_users5"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd5" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r__email" class="form-group">
		<label id="elh_users__email" for="x__email" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->_email->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email">
<input type="text" data-table="users" data-field="x__email" data-page="5" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($users->_email->getPlaceHolder()) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
<?php echo $users->_email->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_users__email"><?php echo $users->_email->FldCaption() ?></span></td>
		<td<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email">
<input type="text" data-table="users" data-field="x__email" data-page="5" name="x__email" id="x__email" placeholder="<?php echo ew_HtmlEncode($users->_email->getPlaceHolder()) ?>" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?>>
</span>
<?php echo $users->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_password" class="form-group">
		<label id="elh_users_password" for="x_password" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->password->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->password->CellAttributes() ?>>
<span id="el_users_password">
<input type="text" data-table="users" data-field="x_password" data-page="5" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($users->password->getPlaceHolder()) ?>" value="<?php echo $users->password->EditValue ?>"<?php echo $users->password->EditAttributes() ?>>
</span>
<?php echo $users->password->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_users_password"><?php echo $users->password->FldCaption() ?></span></td>
		<td<?php echo $users->password->CellAttributes() ?>>
<span id="el_users_password">
<input type="text" data-table="users" data-field="x_password" data-page="5" name="x_password" id="x_password" placeholder="<?php echo ew_HtmlEncode($users->password->getPlaceHolder()) ?>" value="<?php echo $users->password->EditValue ?>"<?php echo $users->password->EditAttributes() ?>>
</span>
<?php echo $users->password->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->total_voluntary_hours->Visible) { // total_voluntary_hours ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_total_voluntary_hours" class="form-group">
		<label id="elh_users_total_voluntary_hours" for="x_total_voluntary_hours" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->total_voluntary_hours->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->total_voluntary_hours->CellAttributes() ?>>
<span id="el_users_total_voluntary_hours">
<input type="text" data-table="users" data-field="x_total_voluntary_hours" data-page="5" name="x_total_voluntary_hours" id="x_total_voluntary_hours" placeholder="<?php echo ew_HtmlEncode($users->total_voluntary_hours->getPlaceHolder()) ?>" value="<?php echo $users->total_voluntary_hours->EditValue ?>"<?php echo $users->total_voluntary_hours->EditAttributes() ?>>
</span>
<?php echo $users->total_voluntary_hours->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_total_voluntary_hours">
		<td class="col-sm-2"><span id="elh_users_total_voluntary_hours"><?php echo $users->total_voluntary_hours->FldCaption() ?></span></td>
		<td<?php echo $users->total_voluntary_hours->CellAttributes() ?>>
<span id="el_users_total_voluntary_hours">
<input type="text" data-table="users" data-field="x_total_voluntary_hours" data-page="5" name="x_total_voluntary_hours" id="x_total_voluntary_hours" placeholder="<?php echo ew_HtmlEncode($users->total_voluntary_hours->getPlaceHolder()) ?>" value="<?php echo $users->total_voluntary_hours->EditValue ?>"<?php echo $users->total_voluntary_hours->EditAttributes() ?>>
</span>
<?php echo $users->total_voluntary_hours->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->overall_evaluation->Visible) { // overall_evaluation ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_overall_evaluation" class="form-group">
		<label id="elh_users_overall_evaluation" for="x_overall_evaluation" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->overall_evaluation->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->overall_evaluation->CellAttributes() ?>>
<span id="el_users_overall_evaluation">
<input type="text" data-table="users" data-field="x_overall_evaluation" data-page="5" name="x_overall_evaluation" id="x_overall_evaluation" size="30" placeholder="<?php echo ew_HtmlEncode($users->overall_evaluation->getPlaceHolder()) ?>" value="<?php echo $users->overall_evaluation->EditValue ?>"<?php echo $users->overall_evaluation->EditAttributes() ?>>
</span>
<?php echo $users->overall_evaluation->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_overall_evaluation">
		<td class="col-sm-2"><span id="elh_users_overall_evaluation"><?php echo $users->overall_evaluation->FldCaption() ?></span></td>
		<td<?php echo $users->overall_evaluation->CellAttributes() ?>>
<span id="el_users_overall_evaluation">
<input type="text" data-table="users" data-field="x_overall_evaluation" data-page="5" name="x_overall_evaluation" id="x_overall_evaluation" size="30" placeholder="<?php echo ew_HtmlEncode($users->overall_evaluation->getPlaceHolder()) ?>" value="<?php echo $users->overall_evaluation->EditValue ?>"<?php echo $users->overall_evaluation->EditAttributes() ?>>
</span>
<?php echo $users->overall_evaluation->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("6") ?>" id="tab_users6"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd6" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label id="elh_users_admin_approval" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->admin_approval->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->admin_approval->CellAttributes() ?>>
<span id="el_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_admin_approval" data-page="6" data-value-separator="<?php echo $users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 6) ?>
</div></div>
</span>
<?php echo $users->admin_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_users_admin_approval"><?php echo $users->admin_approval->FldCaption() ?></span></td>
		<td<?php echo $users->admin_approval->CellAttributes() ?>>
<span id="el_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_admin_approval" data-page="6" data-value-separator="<?php echo $users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval", 6) ?>
</div></div>
</span>
<?php echo $users->admin_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label id="elh_users_admin_comment" for="x_admin_comment" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->admin_comment->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->admin_comment->CellAttributes() ?>>
<span id="el_users_admin_comment">
<textarea data-table="users" data-field="x_admin_comment" data-page="6" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->admin_comment->getPlaceHolder()) ?>"<?php echo $users->admin_comment->EditAttributes() ?>><?php echo $users->admin_comment->EditValue ?></textarea>
</span>
<?php echo $users->admin_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_users_admin_comment"><?php echo $users->admin_comment->FldCaption() ?></span></td>
		<td<?php echo $users->admin_comment->CellAttributes() ?>>
<span id="el_users_admin_comment">
<textarea data-table="users" data-field="x_admin_comment" data-page="6" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->admin_comment->getPlaceHolder()) ?>"<?php echo $users->admin_comment->EditAttributes() ?>><?php echo $users->admin_comment->EditValue ?></textarea>
</span>
<?php echo $users->admin_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
		<div class="tab-pane<?php echo $users_add->MultiPages->PageStyle("7") ?>" id="tab_users7"><!-- multi-page .tab-pane -->
<?php if ($users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_usersadd7" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($users->security_approval->Visible) { // security_approval ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_security_approval" class="form-group">
		<label id="elh_users_security_approval" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->security_approval->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->security_approval->CellAttributes() ?>>
<span id="el_users_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_security_approval" data-page="7" data-value-separator="<?php echo $users->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $users->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 7) ?>
</div></div>
</span>
<?php echo $users->security_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_approval">
		<td class="col-sm-2"><span id="elh_users_security_approval"><?php echo $users->security_approval->FldCaption() ?></span></td>
		<td<?php echo $users->security_approval->CellAttributes() ?>>
<span id="el_users_security_approval">
<div id="tp_x_security_approval" class="ewTemplate"><input type="radio" data-table="users" data-field="x_security_approval" data-page="7" data-value-separator="<?php echo $users->security_approval->DisplayValueSeparatorAttribute() ?>" name="x_security_approval" id="x_security_approval" value="{value}"<?php echo $users->security_approval->EditAttributes() ?>></div>
<div id="dsl_x_security_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $users->security_approval->RadioButtonListHtml(FALSE, "x_security_approval", 7) ?>
</div></div>
</span>
<?php echo $users->security_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
<?php if ($users_add->IsMobileOrModal) { ?>
	<div id="r_security_comment" class="form-group">
		<label id="elh_users_security_comment" for="x_security_comment" class="<?php echo $users_add->LeftColumnClass ?>"><?php echo $users->security_comment->FldCaption() ?></label>
		<div class="<?php echo $users_add->RightColumnClass ?>"><div<?php echo $users->security_comment->CellAttributes() ?>>
<span id="el_users_security_comment">
<textarea data-table="users" data-field="x_security_comment" data-page="7" name="x_security_comment" id="x_security_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->security_comment->getPlaceHolder()) ?>"<?php echo $users->security_comment->EditAttributes() ?>><?php echo $users->security_comment->EditValue ?></textarea>
</span>
<?php echo $users->security_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_security_comment">
		<td class="col-sm-2"><span id="elh_users_security_comment"><?php echo $users->security_comment->FldCaption() ?></span></td>
		<td<?php echo $users->security_comment->CellAttributes() ?>>
<span id="el_users_security_comment">
<textarea data-table="users" data-field="x_security_comment" data-page="7" name="x_security_comment" id="x_security_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($users->security_comment->getPlaceHolder()) ?>"<?php echo $users->security_comment->EditAttributes() ?>><?php echo $users->security_comment->EditValue ?></textarea>
</span>
<?php echo $users->security_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
		</div><!-- /multi-page .tab-pane -->
	</div><!-- /multi-page .nav-tabs-custom .tab-content -->
</div><!-- /multi-page .nav-tabs-custom -->
</div><!-- /multi-page -->
<?php
	if (in_array("user_attachments", explode(",", $users->getCurrentDetailTable())) && $user_attachments->DetailAdd) {
?>
<?php if ($users->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("user_attachments", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "user_attachmentsgrid.php" ?>
<?php } ?>
<?php if (!$users_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $users_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $users_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$users_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fusersadd.Init();
</script>
<?php
$users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$users_add->Page_Terminate();
?>
