<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "activitiesinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "registered_usersgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$activities_edit = NULL; // Initialize page object first

class cactivities_edit extends cactivities {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'activities';

	// Page object name
	var $PageObjName = 'activities_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 'registered_users'
			if (@$_POST["grid"] == "fregistered_usersgrid") {
				if (!isset($GLOBALS["registered_users_grid"])) $GLOBALS["registered_users_grid"] = new cregistered_users_grid;
				$GLOBALS["registered_users_grid"]->Page_Init();
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
		if (@$_GET["activity_id"] <> "") {
			$this->activity_id->setQueryStringValue($_GET["activity_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetupDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->activity_id->CurrentValue == "") {
			$this->Page_Terminate("activitieslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("activitieslist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetupDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "activitieslist.php")
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

					// Set up detail parameters
					$this->SetupDetailParms();
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
		$this->activity_image->Upload->Index = $objForm->Index;
		$this->activity_image->Upload->UploadFile();
		$this->activity_image->CurrentValue = $this->activity_image->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->activity_id->FldIsDetailKey)
			$this->activity_id->setFormValue($objForm->GetValue("x_activity_id"));
		if (!$this->activity_name_ar->FldIsDetailKey) {
			$this->activity_name_ar->setFormValue($objForm->GetValue("x_activity_name_ar"));
		}
		if (!$this->activity_name_en->FldIsDetailKey) {
			$this->activity_name_en->setFormValue($objForm->GetValue("x_activity_name_en"));
		}
		if (!$this->activity_start_date->FldIsDetailKey) {
			$this->activity_start_date->setFormValue($objForm->GetValue("x_activity_start_date"));
			$this->activity_start_date->CurrentValue = ew_UnFormatDateTime($this->activity_start_date->CurrentValue, 0);
		}
		if (!$this->activity_end_date->FldIsDetailKey) {
			$this->activity_end_date->setFormValue($objForm->GetValue("x_activity_end_date"));
			$this->activity_end_date->CurrentValue = ew_UnFormatDateTime($this->activity_end_date->CurrentValue, 0);
		}
		if (!$this->activity_time_ar->FldIsDetailKey) {
			$this->activity_time_ar->setFormValue($objForm->GetValue("x_activity_time_ar"));
		}
		if (!$this->activity_time_en->FldIsDetailKey) {
			$this->activity_time_en->setFormValue($objForm->GetValue("x_activity_time_en"));
		}
		if (!$this->activity_description_ar->FldIsDetailKey) {
			$this->activity_description_ar->setFormValue($objForm->GetValue("x_activity_description_ar"));
		}
		if (!$this->activity_description_en->FldIsDetailKey) {
			$this->activity_description_en->setFormValue($objForm->GetValue("x_activity_description_en"));
		}
		if (!$this->activity_persons->FldIsDetailKey) {
			$this->activity_persons->setFormValue($objForm->GetValue("x_activity_persons"));
		}
		if (!$this->activity_hours->FldIsDetailKey) {
			$this->activity_hours->setFormValue($objForm->GetValue("x_activity_hours"));
		}
		if (!$this->activity_city->FldIsDetailKey) {
			$this->activity_city->setFormValue($objForm->GetValue("x_activity_city"));
		}
		if (!$this->activity_location_ar->FldIsDetailKey) {
			$this->activity_location_ar->setFormValue($objForm->GetValue("x_activity_location_ar"));
		}
		if (!$this->activity_location_en->FldIsDetailKey) {
			$this->activity_location_en->setFormValue($objForm->GetValue("x_activity_location_en"));
		}
		if (!$this->activity_location_map->FldIsDetailKey) {
			$this->activity_location_map->setFormValue($objForm->GetValue("x_activity_location_map"));
		}
		if (!$this->activity_organizer_ar->FldIsDetailKey) {
			$this->activity_organizer_ar->setFormValue($objForm->GetValue("x_activity_organizer_ar"));
		}
		if (!$this->activity_organizer_en->FldIsDetailKey) {
			$this->activity_organizer_en->setFormValue($objForm->GetValue("x_activity_organizer_en"));
		}
		if (!$this->activity_category_ar->FldIsDetailKey) {
			$this->activity_category_ar->setFormValue($objForm->GetValue("x_activity_category_ar"));
		}
		if (!$this->activity_category_en->FldIsDetailKey) {
			$this->activity_category_en->setFormValue($objForm->GetValue("x_activity_category_en"));
		}
		if (!$this->activity_type->FldIsDetailKey) {
			$this->activity_type->setFormValue($objForm->GetValue("x_activity_type"));
		}
		if (!$this->activity_gender_target->FldIsDetailKey) {
			$this->activity_gender_target->setFormValue($objForm->GetValue("x_activity_gender_target"));
		}
		if (!$this->activity_terms_and_conditions_ar->FldIsDetailKey) {
			$this->activity_terms_and_conditions_ar->setFormValue($objForm->GetValue("x_activity_terms_and_conditions_ar"));
		}
		if (!$this->activity_terms_and_conditions_en->FldIsDetailKey) {
			$this->activity_terms_and_conditions_en->setFormValue($objForm->GetValue("x_activity_terms_and_conditions_en"));
		}
		if (!$this->activity_active->FldIsDetailKey) {
			$this->activity_active->setFormValue($objForm->GetValue("x_activity_active"));
		}
		if (!$this->leader_username->FldIsDetailKey) {
			$this->leader_username->setFormValue($objForm->GetValue("x_leader_username"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->activity_id->CurrentValue = $this->activity_id->FormValue;
		$this->activity_name_ar->CurrentValue = $this->activity_name_ar->FormValue;
		$this->activity_name_en->CurrentValue = $this->activity_name_en->FormValue;
		$this->activity_start_date->CurrentValue = $this->activity_start_date->FormValue;
		$this->activity_start_date->CurrentValue = ew_UnFormatDateTime($this->activity_start_date->CurrentValue, 0);
		$this->activity_end_date->CurrentValue = $this->activity_end_date->FormValue;
		$this->activity_end_date->CurrentValue = ew_UnFormatDateTime($this->activity_end_date->CurrentValue, 0);
		$this->activity_time_ar->CurrentValue = $this->activity_time_ar->FormValue;
		$this->activity_time_en->CurrentValue = $this->activity_time_en->FormValue;
		$this->activity_description_ar->CurrentValue = $this->activity_description_ar->FormValue;
		$this->activity_description_en->CurrentValue = $this->activity_description_en->FormValue;
		$this->activity_persons->CurrentValue = $this->activity_persons->FormValue;
		$this->activity_hours->CurrentValue = $this->activity_hours->FormValue;
		$this->activity_city->CurrentValue = $this->activity_city->FormValue;
		$this->activity_location_ar->CurrentValue = $this->activity_location_ar->FormValue;
		$this->activity_location_en->CurrentValue = $this->activity_location_en->FormValue;
		$this->activity_location_map->CurrentValue = $this->activity_location_map->FormValue;
		$this->activity_organizer_ar->CurrentValue = $this->activity_organizer_ar->FormValue;
		$this->activity_organizer_en->CurrentValue = $this->activity_organizer_en->FormValue;
		$this->activity_category_ar->CurrentValue = $this->activity_category_ar->FormValue;
		$this->activity_category_en->CurrentValue = $this->activity_category_en->FormValue;
		$this->activity_type->CurrentValue = $this->activity_type->FormValue;
		$this->activity_gender_target->CurrentValue = $this->activity_gender_target->FormValue;
		$this->activity_terms_and_conditions_ar->CurrentValue = $this->activity_terms_and_conditions_ar->FormValue;
		$this->activity_terms_and_conditions_en->CurrentValue = $this->activity_terms_and_conditions_en->FormValue;
		$this->activity_active->CurrentValue = $this->activity_active->FormValue;
		$this->leader_username->CurrentValue = $this->leader_username->FormValue;
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
		$this->activity_id->setDbValue($row['activity_id']);
		$this->activity_name_ar->setDbValue($row['activity_name_ar']);
		$this->activity_name_en->setDbValue($row['activity_name_en']);
		$this->activity_start_date->setDbValue($row['activity_start_date']);
		$this->activity_end_date->setDbValue($row['activity_end_date']);
		$this->activity_time_ar->setDbValue($row['activity_time_ar']);
		$this->activity_time_en->setDbValue($row['activity_time_en']);
		$this->activity_description_ar->setDbValue($row['activity_description_ar']);
		$this->activity_description_en->setDbValue($row['activity_description_en']);
		$this->activity_persons->setDbValue($row['activity_persons']);
		$this->activity_hours->setDbValue($row['activity_hours']);
		$this->activity_city->setDbValue($row['activity_city']);
		$this->activity_location_ar->setDbValue($row['activity_location_ar']);
		$this->activity_location_en->setDbValue($row['activity_location_en']);
		$this->activity_location_map->setDbValue($row['activity_location_map']);
		$this->activity_image->Upload->DbValue = $row['activity_image'];
		$this->activity_image->CurrentValue = $this->activity_image->Upload->DbValue;
		$this->activity_organizer_ar->setDbValue($row['activity_organizer_ar']);
		$this->activity_organizer_en->setDbValue($row['activity_organizer_en']);
		$this->activity_category_ar->setDbValue($row['activity_category_ar']);
		$this->activity_category_en->setDbValue($row['activity_category_en']);
		$this->activity_type->setDbValue($row['activity_type']);
		$this->activity_gender_target->setDbValue($row['activity_gender_target']);
		$this->activity_terms_and_conditions_ar->setDbValue($row['activity_terms_and_conditions_ar']);
		$this->activity_terms_and_conditions_en->setDbValue($row['activity_terms_and_conditions_en']);
		$this->activity_active->setDbValue($row['activity_active']);
		$this->leader_username->setDbValue($row['leader_username']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['activity_id'] = NULL;
		$row['activity_name_ar'] = NULL;
		$row['activity_name_en'] = NULL;
		$row['activity_start_date'] = NULL;
		$row['activity_end_date'] = NULL;
		$row['activity_time_ar'] = NULL;
		$row['activity_time_en'] = NULL;
		$row['activity_description_ar'] = NULL;
		$row['activity_description_en'] = NULL;
		$row['activity_persons'] = NULL;
		$row['activity_hours'] = NULL;
		$row['activity_city'] = NULL;
		$row['activity_location_ar'] = NULL;
		$row['activity_location_en'] = NULL;
		$row['activity_location_map'] = NULL;
		$row['activity_image'] = NULL;
		$row['activity_organizer_ar'] = NULL;
		$row['activity_organizer_en'] = NULL;
		$row['activity_category_ar'] = NULL;
		$row['activity_category_en'] = NULL;
		$row['activity_type'] = NULL;
		$row['activity_gender_target'] = NULL;
		$row['activity_terms_and_conditions_ar'] = NULL;
		$row['activity_terms_and_conditions_en'] = NULL;
		$row['activity_active'] = NULL;
		$row['leader_username'] = NULL;
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
		$this->activity_id->DbValue = $row['activity_id'];
		$this->activity_name_ar->DbValue = $row['activity_name_ar'];
		$this->activity_name_en->DbValue = $row['activity_name_en'];
		$this->activity_start_date->DbValue = $row['activity_start_date'];
		$this->activity_end_date->DbValue = $row['activity_end_date'];
		$this->activity_time_ar->DbValue = $row['activity_time_ar'];
		$this->activity_time_en->DbValue = $row['activity_time_en'];
		$this->activity_description_ar->DbValue = $row['activity_description_ar'];
		$this->activity_description_en->DbValue = $row['activity_description_en'];
		$this->activity_persons->DbValue = $row['activity_persons'];
		$this->activity_hours->DbValue = $row['activity_hours'];
		$this->activity_city->DbValue = $row['activity_city'];
		$this->activity_location_ar->DbValue = $row['activity_location_ar'];
		$this->activity_location_en->DbValue = $row['activity_location_en'];
		$this->activity_location_map->DbValue = $row['activity_location_map'];
		$this->activity_image->Upload->DbValue = $row['activity_image'];
		$this->activity_organizer_ar->DbValue = $row['activity_organizer_ar'];
		$this->activity_organizer_en->DbValue = $row['activity_organizer_en'];
		$this->activity_category_ar->DbValue = $row['activity_category_ar'];
		$this->activity_category_en->DbValue = $row['activity_category_en'];
		$this->activity_type->DbValue = $row['activity_type'];
		$this->activity_gender_target->DbValue = $row['activity_gender_target'];
		$this->activity_terms_and_conditions_ar->DbValue = $row['activity_terms_and_conditions_ar'];
		$this->activity_terms_and_conditions_en->DbValue = $row['activity_terms_and_conditions_en'];
		$this->activity_active->DbValue = $row['activity_active'];
		$this->leader_username->DbValue = $row['leader_username'];
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// activity_id
			$this->activity_id->EditAttrs["class"] = "form-control";
			$this->activity_id->EditCustomAttributes = "";
			$this->activity_id->EditValue = $this->activity_id->CurrentValue;
			$this->activity_id->ViewCustomAttributes = "";

			// activity_name_ar
			$this->activity_name_ar->EditAttrs["class"] = "form-control";
			$this->activity_name_ar->EditCustomAttributes = "";
			$this->activity_name_ar->EditValue = ew_HtmlEncode($this->activity_name_ar->CurrentValue);
			$this->activity_name_ar->PlaceHolder = ew_RemoveHtml($this->activity_name_ar->FldCaption());

			// activity_name_en
			$this->activity_name_en->EditAttrs["class"] = "form-control";
			$this->activity_name_en->EditCustomAttributes = "";
			$this->activity_name_en->EditValue = ew_HtmlEncode($this->activity_name_en->CurrentValue);
			$this->activity_name_en->PlaceHolder = ew_RemoveHtml($this->activity_name_en->FldCaption());

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

			// activity_time_ar
			$this->activity_time_ar->EditAttrs["class"] = "form-control";
			$this->activity_time_ar->EditCustomAttributes = "";
			$this->activity_time_ar->EditValue = ew_HtmlEncode($this->activity_time_ar->CurrentValue);
			$this->activity_time_ar->PlaceHolder = ew_RemoveHtml($this->activity_time_ar->FldCaption());

			// activity_time_en
			$this->activity_time_en->EditAttrs["class"] = "form-control";
			$this->activity_time_en->EditCustomAttributes = "";
			$this->activity_time_en->EditValue = ew_HtmlEncode($this->activity_time_en->CurrentValue);
			$this->activity_time_en->PlaceHolder = ew_RemoveHtml($this->activity_time_en->FldCaption());

			// activity_description_ar
			$this->activity_description_ar->EditAttrs["class"] = "form-control";
			$this->activity_description_ar->EditCustomAttributes = "";
			$this->activity_description_ar->EditValue = ew_HtmlEncode($this->activity_description_ar->CurrentValue);
			$this->activity_description_ar->PlaceHolder = ew_RemoveHtml($this->activity_description_ar->FldCaption());

			// activity_description_en
			$this->activity_description_en->EditAttrs["class"] = "form-control";
			$this->activity_description_en->EditCustomAttributes = "";
			$this->activity_description_en->EditValue = ew_HtmlEncode($this->activity_description_en->CurrentValue);
			$this->activity_description_en->PlaceHolder = ew_RemoveHtml($this->activity_description_en->FldCaption());

			// activity_persons
			$this->activity_persons->EditAttrs["class"] = "form-control";
			$this->activity_persons->EditCustomAttributes = "";
			$this->activity_persons->EditValue = ew_HtmlEncode($this->activity_persons->CurrentValue);
			$this->activity_persons->PlaceHolder = ew_RemoveHtml($this->activity_persons->FldCaption());

			// activity_hours
			$this->activity_hours->EditAttrs["class"] = "form-control";
			$this->activity_hours->EditCustomAttributes = "";
			$this->activity_hours->EditValue = ew_HtmlEncode($this->activity_hours->CurrentValue);
			$this->activity_hours->PlaceHolder = ew_RemoveHtml($this->activity_hours->FldCaption());

			// activity_city
			$this->activity_city->EditAttrs["class"] = "form-control";
			$this->activity_city->EditCustomAttributes = "";
			$this->activity_city->EditValue = $this->activity_city->Options(TRUE);

			// activity_location_ar
			$this->activity_location_ar->EditAttrs["class"] = "form-control";
			$this->activity_location_ar->EditCustomAttributes = "";
			$this->activity_location_ar->EditValue = ew_HtmlEncode($this->activity_location_ar->CurrentValue);
			$this->activity_location_ar->PlaceHolder = ew_RemoveHtml($this->activity_location_ar->FldCaption());

			// activity_location_en
			$this->activity_location_en->EditAttrs["class"] = "form-control";
			$this->activity_location_en->EditCustomAttributes = "";
			$this->activity_location_en->EditValue = ew_HtmlEncode($this->activity_location_en->CurrentValue);
			$this->activity_location_en->PlaceHolder = ew_RemoveHtml($this->activity_location_en->FldCaption());

			// activity_location_map
			$this->activity_location_map->EditAttrs["class"] = "form-control";
			$this->activity_location_map->EditCustomAttributes = "";
			$this->activity_location_map->EditValue = ew_HtmlEncode($this->activity_location_map->CurrentValue);
			$this->activity_location_map->PlaceHolder = ew_RemoveHtml($this->activity_location_map->FldCaption());

			// activity_image
			$this->activity_image->EditAttrs["class"] = "form-control";
			$this->activity_image->EditCustomAttributes = "";
			$this->activity_image->UploadPath = "../images";
			if (!ew_Empty($this->activity_image->Upload->DbValue)) {
				$this->activity_image->ImageWidth = 100;
				$this->activity_image->ImageHeight = 0;
				$this->activity_image->ImageAlt = $this->activity_image->FldAlt();
				$this->activity_image->EditValue = $this->activity_image->Upload->DbValue;
			} else {
				$this->activity_image->EditValue = "";
			}
			if (!ew_Empty($this->activity_image->CurrentValue))
				$this->activity_image->Upload->FileName = $this->activity_image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->activity_image);

			// activity_organizer_ar
			$this->activity_organizer_ar->EditAttrs["class"] = "form-control";
			$this->activity_organizer_ar->EditCustomAttributes = "";
			$this->activity_organizer_ar->EditValue = ew_HtmlEncode($this->activity_organizer_ar->CurrentValue);
			$this->activity_organizer_ar->PlaceHolder = ew_RemoveHtml($this->activity_organizer_ar->FldCaption());

			// activity_organizer_en
			$this->activity_organizer_en->EditAttrs["class"] = "form-control";
			$this->activity_organizer_en->EditCustomAttributes = "";
			$this->activity_organizer_en->EditValue = ew_HtmlEncode($this->activity_organizer_en->CurrentValue);
			$this->activity_organizer_en->PlaceHolder = ew_RemoveHtml($this->activity_organizer_en->FldCaption());

			// activity_category_ar
			$this->activity_category_ar->EditAttrs["class"] = "form-control";
			$this->activity_category_ar->EditCustomAttributes = "";
			$this->activity_category_ar->EditValue = ew_HtmlEncode($this->activity_category_ar->CurrentValue);
			$this->activity_category_ar->PlaceHolder = ew_RemoveHtml($this->activity_category_ar->FldCaption());

			// activity_category_en
			$this->activity_category_en->EditAttrs["class"] = "form-control";
			$this->activity_category_en->EditCustomAttributes = "";
			$this->activity_category_en->EditValue = ew_HtmlEncode($this->activity_category_en->CurrentValue);
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
			$this->activity_terms_and_conditions_ar->EditValue = ew_HtmlEncode($this->activity_terms_and_conditions_ar->CurrentValue);
			$this->activity_terms_and_conditions_ar->PlaceHolder = ew_RemoveHtml($this->activity_terms_and_conditions_ar->FldCaption());

			// activity_terms_and_conditions_en
			$this->activity_terms_and_conditions_en->EditAttrs["class"] = "form-control";
			$this->activity_terms_and_conditions_en->EditCustomAttributes = "";
			$this->activity_terms_and_conditions_en->EditValue = ew_HtmlEncode($this->activity_terms_and_conditions_en->CurrentValue);
			$this->activity_terms_and_conditions_en->PlaceHolder = ew_RemoveHtml($this->activity_terms_and_conditions_en->FldCaption());

			// activity_active
			$this->activity_active->EditCustomAttributes = "";
			$this->activity_active->EditValue = $this->activity_active->Options(FALSE);

			// leader_username
			$this->leader_username->EditAttrs["class"] = "form-control";
			$this->leader_username->EditCustomAttributes = "";
			$this->leader_username->EditValue = ew_HtmlEncode($this->leader_username->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->leader_username->EditValue = $this->leader_username->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->leader_username->EditValue = ew_HtmlEncode($this->leader_username->CurrentValue);
				}
			} else {
				$this->leader_username->EditValue = NULL;
			}
			$this->leader_username->PlaceHolder = ew_RemoveHtml($this->leader_username->FldCaption());

			// Edit refer script
			// activity_id

			$this->activity_id->LinkCustomAttributes = "";
			$this->activity_id->HrefValue = "";

			// activity_name_ar
			$this->activity_name_ar->LinkCustomAttributes = "";
			$this->activity_name_ar->HrefValue = "";

			// activity_name_en
			$this->activity_name_en->LinkCustomAttributes = "";
			$this->activity_name_en->HrefValue = "";

			// activity_start_date
			$this->activity_start_date->LinkCustomAttributes = "";
			$this->activity_start_date->HrefValue = "";

			// activity_end_date
			$this->activity_end_date->LinkCustomAttributes = "";
			$this->activity_end_date->HrefValue = "";

			// activity_time_ar
			$this->activity_time_ar->LinkCustomAttributes = "";
			$this->activity_time_ar->HrefValue = "";

			// activity_time_en
			$this->activity_time_en->LinkCustomAttributes = "";
			$this->activity_time_en->HrefValue = "";

			// activity_description_ar
			$this->activity_description_ar->LinkCustomAttributes = "";
			$this->activity_description_ar->HrefValue = "";

			// activity_description_en
			$this->activity_description_en->LinkCustomAttributes = "";
			$this->activity_description_en->HrefValue = "";

			// activity_persons
			$this->activity_persons->LinkCustomAttributes = "";
			$this->activity_persons->HrefValue = "";

			// activity_hours
			$this->activity_hours->LinkCustomAttributes = "";
			$this->activity_hours->HrefValue = "";

			// activity_city
			$this->activity_city->LinkCustomAttributes = "";
			$this->activity_city->HrefValue = "";

			// activity_location_ar
			$this->activity_location_ar->LinkCustomAttributes = "";
			$this->activity_location_ar->HrefValue = "";

			// activity_location_en
			$this->activity_location_en->LinkCustomAttributes = "";
			$this->activity_location_en->HrefValue = "";

			// activity_location_map
			$this->activity_location_map->LinkCustomAttributes = "";
			$this->activity_location_map->HrefValue = "";

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

			// activity_organizer_ar
			$this->activity_organizer_ar->LinkCustomAttributes = "";
			$this->activity_organizer_ar->HrefValue = "";

			// activity_organizer_en
			$this->activity_organizer_en->LinkCustomAttributes = "";
			$this->activity_organizer_en->HrefValue = "";

			// activity_category_ar
			$this->activity_category_ar->LinkCustomAttributes = "";
			$this->activity_category_ar->HrefValue = "";

			// activity_category_en
			$this->activity_category_en->LinkCustomAttributes = "";
			$this->activity_category_en->HrefValue = "";

			// activity_type
			$this->activity_type->LinkCustomAttributes = "";
			$this->activity_type->HrefValue = "";

			// activity_gender_target
			$this->activity_gender_target->LinkCustomAttributes = "";
			$this->activity_gender_target->HrefValue = "";

			// activity_terms_and_conditions_ar
			$this->activity_terms_and_conditions_ar->LinkCustomAttributes = "";
			$this->activity_terms_and_conditions_ar->HrefValue = "";

			// activity_terms_and_conditions_en
			$this->activity_terms_and_conditions_en->LinkCustomAttributes = "";
			$this->activity_terms_and_conditions_en->HrefValue = "";

			// activity_active
			$this->activity_active->LinkCustomAttributes = "";
			$this->activity_active->HrefValue = "";

			// leader_username
			$this->leader_username->LinkCustomAttributes = "";
			$this->leader_username->HrefValue = "";
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
		if (!ew_CheckDateDef($this->activity_start_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->activity_start_date->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->activity_end_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->activity_end_date->FldErrMsg());
		}
		if (!ew_CheckInteger($this->activity_persons->FormValue)) {
			ew_AddMessage($gsFormError, $this->activity_persons->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("registered_users", $DetailTblVar) && $GLOBALS["registered_users"]->DetailEdit) {
			if (!isset($GLOBALS["registered_users_grid"])) $GLOBALS["registered_users_grid"] = new cregistered_users_grid(); // get detail page object
			$GLOBALS["registered_users_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->activity_image->OldUploadPath = "../images";
			$this->activity_image->UploadPath = $this->activity_image->OldUploadPath;
			$rsnew = array();

			// activity_name_ar
			$this->activity_name_ar->SetDbValueDef($rsnew, $this->activity_name_ar->CurrentValue, NULL, $this->activity_name_ar->ReadOnly);

			// activity_name_en
			$this->activity_name_en->SetDbValueDef($rsnew, $this->activity_name_en->CurrentValue, NULL, $this->activity_name_en->ReadOnly);

			// activity_start_date
			$this->activity_start_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->activity_start_date->CurrentValue, 0), NULL, $this->activity_start_date->ReadOnly);

			// activity_end_date
			$this->activity_end_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->activity_end_date->CurrentValue, 0), NULL, $this->activity_end_date->ReadOnly);

			// activity_time_ar
			$this->activity_time_ar->SetDbValueDef($rsnew, $this->activity_time_ar->CurrentValue, NULL, $this->activity_time_ar->ReadOnly);

			// activity_time_en
			$this->activity_time_en->SetDbValueDef($rsnew, $this->activity_time_en->CurrentValue, NULL, $this->activity_time_en->ReadOnly);

			// activity_description_ar
			$this->activity_description_ar->SetDbValueDef($rsnew, $this->activity_description_ar->CurrentValue, NULL, $this->activity_description_ar->ReadOnly);

			// activity_description_en
			$this->activity_description_en->SetDbValueDef($rsnew, $this->activity_description_en->CurrentValue, NULL, $this->activity_description_en->ReadOnly);

			// activity_persons
			$this->activity_persons->SetDbValueDef($rsnew, $this->activity_persons->CurrentValue, NULL, $this->activity_persons->ReadOnly);

			// activity_hours
			$this->activity_hours->SetDbValueDef($rsnew, $this->activity_hours->CurrentValue, NULL, $this->activity_hours->ReadOnly);

			// activity_city
			$this->activity_city->SetDbValueDef($rsnew, $this->activity_city->CurrentValue, NULL, $this->activity_city->ReadOnly);

			// activity_location_ar
			$this->activity_location_ar->SetDbValueDef($rsnew, $this->activity_location_ar->CurrentValue, NULL, $this->activity_location_ar->ReadOnly);

			// activity_location_en
			$this->activity_location_en->SetDbValueDef($rsnew, $this->activity_location_en->CurrentValue, NULL, $this->activity_location_en->ReadOnly);

			// activity_location_map
			$this->activity_location_map->SetDbValueDef($rsnew, $this->activity_location_map->CurrentValue, NULL, $this->activity_location_map->ReadOnly);

			// activity_image
			if ($this->activity_image->Visible && !$this->activity_image->ReadOnly && !$this->activity_image->Upload->KeepFile) {
				$this->activity_image->Upload->DbValue = $rsold['activity_image']; // Get original value
				if ($this->activity_image->Upload->FileName == "") {
					$rsnew['activity_image'] = NULL;
				} else {
					$rsnew['activity_image'] = $this->activity_image->Upload->FileName;
				}
			}

			// activity_organizer_ar
			$this->activity_organizer_ar->SetDbValueDef($rsnew, $this->activity_organizer_ar->CurrentValue, NULL, $this->activity_organizer_ar->ReadOnly);

			// activity_organizer_en
			$this->activity_organizer_en->SetDbValueDef($rsnew, $this->activity_organizer_en->CurrentValue, NULL, $this->activity_organizer_en->ReadOnly);

			// activity_category_ar
			$this->activity_category_ar->SetDbValueDef($rsnew, $this->activity_category_ar->CurrentValue, NULL, $this->activity_category_ar->ReadOnly);

			// activity_category_en
			$this->activity_category_en->SetDbValueDef($rsnew, $this->activity_category_en->CurrentValue, NULL, $this->activity_category_en->ReadOnly);

			// activity_type
			$this->activity_type->SetDbValueDef($rsnew, $this->activity_type->CurrentValue, NULL, $this->activity_type->ReadOnly);

			// activity_gender_target
			$this->activity_gender_target->SetDbValueDef($rsnew, $this->activity_gender_target->CurrentValue, NULL, $this->activity_gender_target->ReadOnly);

			// activity_terms_and_conditions_ar
			$this->activity_terms_and_conditions_ar->SetDbValueDef($rsnew, $this->activity_terms_and_conditions_ar->CurrentValue, NULL, $this->activity_terms_and_conditions_ar->ReadOnly);

			// activity_terms_and_conditions_en
			$this->activity_terms_and_conditions_en->SetDbValueDef($rsnew, $this->activity_terms_and_conditions_en->CurrentValue, NULL, $this->activity_terms_and_conditions_en->ReadOnly);

			// activity_active
			$this->activity_active->SetDbValueDef($rsnew, $this->activity_active->CurrentValue, NULL, $this->activity_active->ReadOnly);

			// leader_username
			$this->leader_username->SetDbValueDef($rsnew, $this->leader_username->CurrentValue, NULL, $this->leader_username->ReadOnly);
			if ($this->activity_image->Visible && !$this->activity_image->Upload->KeepFile) {
				$this->activity_image->UploadPath = "../images";
				if (!ew_Empty($this->activity_image->Upload->Value)) {
					if ($this->activity_image->Upload->FileName == $this->activity_image->Upload->DbValue) { // Overwrite if same file name
						$this->activity_image->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['activity_image'] = ew_UploadFileNameEx($this->activity_image->PhysicalUploadPath(), $rsnew['activity_image']); // Get new file name
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
					if ($this->activity_image->Visible && !$this->activity_image->Upload->KeepFile) {
						if (!ew_Empty($this->activity_image->Upload->Value)) {
							if (!$this->activity_image->Upload->SaveToFile($rsnew['activity_image'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
						if ($this->activity_image->Upload->DbValue <> "")
							@unlink($this->activity_image->OldPhysicalUploadPath() . $this->activity_image->Upload->DbValue);
					}
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("registered_users", $DetailTblVar) && $GLOBALS["registered_users"]->DetailEdit) {
						if (!isset($GLOBALS["registered_users_grid"])) $GLOBALS["registered_users_grid"] = new cregistered_users_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "registered_users"); // Load user level of detail table
						$EditRow = $GLOBALS["registered_users_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
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

		// activity_image
		ew_CleanUploadTempPath($this->activity_image, $this->activity_image->Upload->Index);
		return $EditRow;
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
			if (in_array("registered_users", $DetailTblVar)) {
				if (!isset($GLOBALS["registered_users_grid"]))
					$GLOBALS["registered_users_grid"] = new cregistered_users_grid;
				if ($GLOBALS["registered_users_grid"]->DetailEdit) {
					$GLOBALS["registered_users_grid"]->CurrentMode = "edit";
					$GLOBALS["registered_users_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["registered_users_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["registered_users_grid"]->setStartRecordNumber(1);
					$GLOBALS["registered_users_grid"]->activity_id->FldIsDetailKey = TRUE;
					$GLOBALS["registered_users_grid"]->activity_id->CurrentValue = $this->activity_id->CurrentValue;
					$GLOBALS["registered_users_grid"]->activity_id->setSessionValue($GLOBALS["registered_users_grid"]->activity_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("activitieslist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($activities_edit)) $activities_edit = new cactivities_edit();

// Page init
$activities_edit->Page_Init();

// Page main
$activities_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$activities_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = factivitiesedit = new ew_Form("factivitiesedit", "edit");

// Validate form
factivitiesedit.Validate = function() {
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
factivitiesedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factivitiesedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factivitiesedit.Lists["x_activity_city"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesedit.Lists["x_activity_city"].Options = <?php echo json_encode($activities_edit->activity_city->Options()) ?>;
factivitiesedit.Lists["x_activity_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesedit.Lists["x_activity_type"].Options = <?php echo json_encode($activities_edit->activity_type->Options()) ?>;
factivitiesedit.Lists["x_activity_gender_target"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesedit.Lists["x_activity_gender_target"].Options = <?php echo json_encode($activities_edit->activity_gender_target->Options()) ?>;
factivitiesedit.Lists["x_activity_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesedit.Lists["x_activity_active"].Options = <?php echo json_encode($activities_edit->activity_active->Options()) ?>;
factivitiesedit.Lists["x_leader_username"] = {"LinkField":"x_user_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_full_name_ar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
factivitiesedit.Lists["x_leader_username"].Data = "<?php echo $activities_edit->leader_username->LookupFilterQuery(FALSE, "edit") ?>";
factivitiesedit.AutoSuggests["x_leader_username"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $activities_edit->leader_username->LookupFilterQuery(TRUE, "edit"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $activities_edit->ShowPageHeader(); ?>
<?php
$activities_edit->ShowMessage();
?>
<form name="factivitiesedit" id="factivitiesedit" class="<?php echo $activities_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($activities_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $activities_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="activities">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($activities_edit->IsModal) ?>">
<?php if (!$activities_edit->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
<div class="ewEditDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_activitiesedit" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($activities->activity_id->Visible) { // activity_id ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_id" class="form-group">
		<label id="elh_activities_activity_id" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_id->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_id->CellAttributes() ?>>
<span id="el_activities_activity_id">
<span<?php echo $activities->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $activities->activity_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="activities" data-field="x_activity_id" name="x_activity_id" id="x_activity_id" value="<?php echo ew_HtmlEncode($activities->activity_id->CurrentValue) ?>">
<?php echo $activities->activity_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_id">
		<td class="col-sm-2"><span id="elh_activities_activity_id"><?php echo $activities->activity_id->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_id->CellAttributes() ?>>
<span id="el_activities_activity_id">
<span<?php echo $activities->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $activities->activity_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="activities" data-field="x_activity_id" name="x_activity_id" id="x_activity_id" value="<?php echo ew_HtmlEncode($activities->activity_id->CurrentValue) ?>">
<?php echo $activities->activity_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_name_ar->Visible) { // activity_name_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_name_ar" class="form-group">
		<label id="elh_activities_activity_name_ar" for="x_activity_name_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_name_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_name_ar->CellAttributes() ?>>
<span id="el_activities_activity_name_ar">
<input type="text" data-table="activities" data-field="x_activity_name_ar" name="x_activity_name_ar" id="x_activity_name_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_ar->EditValue ?>"<?php echo $activities->activity_name_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_name_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_name_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_name_ar"><?php echo $activities->activity_name_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_name_ar->CellAttributes() ?>>
<span id="el_activities_activity_name_ar">
<input type="text" data-table="activities" data-field="x_activity_name_ar" name="x_activity_name_ar" id="x_activity_name_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_ar->EditValue ?>"<?php echo $activities->activity_name_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_name_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_name_en->Visible) { // activity_name_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_name_en" class="form-group">
		<label id="elh_activities_activity_name_en" for="x_activity_name_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_name_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_name_en->CellAttributes() ?>>
<span id="el_activities_activity_name_en">
<input type="text" data-table="activities" data-field="x_activity_name_en" name="x_activity_name_en" id="x_activity_name_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_en->EditValue ?>"<?php echo $activities->activity_name_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_name_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_name_en">
		<td class="col-sm-2"><span id="elh_activities_activity_name_en"><?php echo $activities->activity_name_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_name_en->CellAttributes() ?>>
<span id="el_activities_activity_name_en">
<input type="text" data-table="activities" data-field="x_activity_name_en" name="x_activity_name_en" id="x_activity_name_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_name_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_name_en->EditValue ?>"<?php echo $activities->activity_name_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_name_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_start_date->Visible) { // activity_start_date ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_start_date" class="form-group">
		<label id="elh_activities_activity_start_date" for="x_activity_start_date" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_start_date->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_start_date->CellAttributes() ?>>
<span id="el_activities_activity_start_date">
<input type="text" data-table="activities" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_start_date->EditValue ?>"<?php echo $activities->activity_start_date->EditAttributes() ?>>
<?php if (!$activities->activity_start_date->ReadOnly && !$activities->activity_start_date->Disabled && !isset($activities->activity_start_date->EditAttrs["readonly"]) && !isset($activities->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiesedit", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $activities->activity_start_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_start_date">
		<td class="col-sm-2"><span id="elh_activities_activity_start_date"><?php echo $activities->activity_start_date->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_start_date->CellAttributes() ?>>
<span id="el_activities_activity_start_date">
<input type="text" data-table="activities" data-field="x_activity_start_date" name="x_activity_start_date" id="x_activity_start_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_start_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_start_date->EditValue ?>"<?php echo $activities->activity_start_date->EditAttributes() ?>>
<?php if (!$activities->activity_start_date->ReadOnly && !$activities->activity_start_date->Disabled && !isset($activities->activity_start_date->EditAttrs["readonly"]) && !isset($activities->activity_start_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiesedit", "x_activity_start_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $activities->activity_start_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_end_date->Visible) { // activity_end_date ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_end_date" class="form-group">
		<label id="elh_activities_activity_end_date" for="x_activity_end_date" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_end_date->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_end_date->CellAttributes() ?>>
<span id="el_activities_activity_end_date">
<input type="text" data-table="activities" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_end_date->EditValue ?>"<?php echo $activities->activity_end_date->EditAttributes() ?>>
<?php if (!$activities->activity_end_date->ReadOnly && !$activities->activity_end_date->Disabled && !isset($activities->activity_end_date->EditAttrs["readonly"]) && !isset($activities->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiesedit", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $activities->activity_end_date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_end_date">
		<td class="col-sm-2"><span id="elh_activities_activity_end_date"><?php echo $activities->activity_end_date->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_end_date->CellAttributes() ?>>
<span id="el_activities_activity_end_date">
<input type="text" data-table="activities" data-field="x_activity_end_date" name="x_activity_end_date" id="x_activity_end_date" placeholder="<?php echo ew_HtmlEncode($activities->activity_end_date->getPlaceHolder()) ?>" value="<?php echo $activities->activity_end_date->EditValue ?>"<?php echo $activities->activity_end_date->EditAttributes() ?>>
<?php if (!$activities->activity_end_date->ReadOnly && !$activities->activity_end_date->Disabled && !isset($activities->activity_end_date->EditAttrs["readonly"]) && !isset($activities->activity_end_date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("factivitiesedit", "x_activity_end_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $activities->activity_end_date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_time_ar->Visible) { // activity_time_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_time_ar" class="form-group">
		<label id="elh_activities_activity_time_ar" for="x_activity_time_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_time_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_time_ar->CellAttributes() ?>>
<span id="el_activities_activity_time_ar">
<input type="text" data-table="activities" data-field="x_activity_time_ar" name="x_activity_time_ar" id="x_activity_time_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_ar->EditValue ?>"<?php echo $activities->activity_time_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_time_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_time_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_time_ar"><?php echo $activities->activity_time_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_time_ar->CellAttributes() ?>>
<span id="el_activities_activity_time_ar">
<input type="text" data-table="activities" data-field="x_activity_time_ar" name="x_activity_time_ar" id="x_activity_time_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_ar->EditValue ?>"<?php echo $activities->activity_time_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_time_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_time_en->Visible) { // activity_time_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_time_en" class="form-group">
		<label id="elh_activities_activity_time_en" for="x_activity_time_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_time_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_time_en->CellAttributes() ?>>
<span id="el_activities_activity_time_en">
<input type="text" data-table="activities" data-field="x_activity_time_en" name="x_activity_time_en" id="x_activity_time_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_en->EditValue ?>"<?php echo $activities->activity_time_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_time_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_time_en">
		<td class="col-sm-2"><span id="elh_activities_activity_time_en"><?php echo $activities->activity_time_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_time_en->CellAttributes() ?>>
<span id="el_activities_activity_time_en">
<input type="text" data-table="activities" data-field="x_activity_time_en" name="x_activity_time_en" id="x_activity_time_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_time_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_time_en->EditValue ?>"<?php echo $activities->activity_time_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_time_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_description_ar->Visible) { // activity_description_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_description_ar" class="form-group">
		<label id="elh_activities_activity_description_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_description_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_description_ar->CellAttributes() ?>>
<span id="el_activities_activity_description_ar">
<?php ew_AppendClass($activities->activity_description_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_description_ar" name="x_activity_description_ar" id="x_activity_description_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_ar->getPlaceHolder()) ?>"<?php echo $activities->activity_description_ar->EditAttributes() ?>><?php echo $activities->activity_description_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_description_ar", 35, 4, <?php echo ($activities->activity_description_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_description_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_description_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_description_ar"><?php echo $activities->activity_description_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_description_ar->CellAttributes() ?>>
<span id="el_activities_activity_description_ar">
<?php ew_AppendClass($activities->activity_description_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_description_ar" name="x_activity_description_ar" id="x_activity_description_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_ar->getPlaceHolder()) ?>"<?php echo $activities->activity_description_ar->EditAttributes() ?>><?php echo $activities->activity_description_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_description_ar", 35, 4, <?php echo ($activities->activity_description_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_description_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_description_en->Visible) { // activity_description_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_description_en" class="form-group">
		<label id="elh_activities_activity_description_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_description_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_description_en->CellAttributes() ?>>
<span id="el_activities_activity_description_en">
<?php ew_AppendClass($activities->activity_description_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_description_en" name="x_activity_description_en" id="x_activity_description_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_en->getPlaceHolder()) ?>"<?php echo $activities->activity_description_en->EditAttributes() ?>><?php echo $activities->activity_description_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_description_en", 35, 4, <?php echo ($activities->activity_description_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_description_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_description_en">
		<td class="col-sm-2"><span id="elh_activities_activity_description_en"><?php echo $activities->activity_description_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_description_en->CellAttributes() ?>>
<span id="el_activities_activity_description_en">
<?php ew_AppendClass($activities->activity_description_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_description_en" name="x_activity_description_en" id="x_activity_description_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_description_en->getPlaceHolder()) ?>"<?php echo $activities->activity_description_en->EditAttributes() ?>><?php echo $activities->activity_description_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_description_en", 35, 4, <?php echo ($activities->activity_description_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_description_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_persons->Visible) { // activity_persons ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_persons" class="form-group">
		<label id="elh_activities_activity_persons" for="x_activity_persons" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_persons->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_persons->CellAttributes() ?>>
<span id="el_activities_activity_persons">
<input type="text" data-table="activities" data-field="x_activity_persons" name="x_activity_persons" id="x_activity_persons" size="30" placeholder="<?php echo ew_HtmlEncode($activities->activity_persons->getPlaceHolder()) ?>" value="<?php echo $activities->activity_persons->EditValue ?>"<?php echo $activities->activity_persons->EditAttributes() ?>>
</span>
<?php echo $activities->activity_persons->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_persons">
		<td class="col-sm-2"><span id="elh_activities_activity_persons"><?php echo $activities->activity_persons->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_persons->CellAttributes() ?>>
<span id="el_activities_activity_persons">
<input type="text" data-table="activities" data-field="x_activity_persons" name="x_activity_persons" id="x_activity_persons" size="30" placeholder="<?php echo ew_HtmlEncode($activities->activity_persons->getPlaceHolder()) ?>" value="<?php echo $activities->activity_persons->EditValue ?>"<?php echo $activities->activity_persons->EditAttributes() ?>>
</span>
<?php echo $activities->activity_persons->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_hours->Visible) { // activity_hours ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_hours" class="form-group">
		<label id="elh_activities_activity_hours" for="x_activity_hours" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_hours->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_hours->CellAttributes() ?>>
<span id="el_activities_activity_hours">
<textarea data-table="activities" data-field="x_activity_hours" name="x_activity_hours" id="x_activity_hours" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_hours->getPlaceHolder()) ?>"<?php echo $activities->activity_hours->EditAttributes() ?>><?php echo $activities->activity_hours->EditValue ?></textarea>
</span>
<?php echo $activities->activity_hours->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_hours">
		<td class="col-sm-2"><span id="elh_activities_activity_hours"><?php echo $activities->activity_hours->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_hours->CellAttributes() ?>>
<span id="el_activities_activity_hours">
<textarea data-table="activities" data-field="x_activity_hours" name="x_activity_hours" id="x_activity_hours" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_hours->getPlaceHolder()) ?>"<?php echo $activities->activity_hours->EditAttributes() ?>><?php echo $activities->activity_hours->EditValue ?></textarea>
</span>
<?php echo $activities->activity_hours->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_city->Visible) { // activity_city ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_city" class="form-group">
		<label id="elh_activities_activity_city" for="x_activity_city" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_city->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_city->CellAttributes() ?>>
<span id="el_activities_activity_city">
<select data-table="activities" data-field="x_activity_city" data-value-separator="<?php echo $activities->activity_city->DisplayValueSeparatorAttribute() ?>" id="x_activity_city" name="x_activity_city"<?php echo $activities->activity_city->EditAttributes() ?>>
<?php echo $activities->activity_city->SelectOptionListHtml("x_activity_city") ?>
</select>
</span>
<?php echo $activities->activity_city->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_city">
		<td class="col-sm-2"><span id="elh_activities_activity_city"><?php echo $activities->activity_city->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_city->CellAttributes() ?>>
<span id="el_activities_activity_city">
<select data-table="activities" data-field="x_activity_city" data-value-separator="<?php echo $activities->activity_city->DisplayValueSeparatorAttribute() ?>" id="x_activity_city" name="x_activity_city"<?php echo $activities->activity_city->EditAttributes() ?>>
<?php echo $activities->activity_city->SelectOptionListHtml("x_activity_city") ?>
</select>
</span>
<?php echo $activities->activity_city->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_location_ar->Visible) { // activity_location_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_location_ar" class="form-group">
		<label id="elh_activities_activity_location_ar" for="x_activity_location_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_location_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_location_ar->CellAttributes() ?>>
<span id="el_activities_activity_location_ar">
<textarea data-table="activities" data-field="x_activity_location_ar" name="x_activity_location_ar" id="x_activity_location_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_ar->getPlaceHolder()) ?>"<?php echo $activities->activity_location_ar->EditAttributes() ?>><?php echo $activities->activity_location_ar->EditValue ?></textarea>
</span>
<?php echo $activities->activity_location_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_location_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_location_ar"><?php echo $activities->activity_location_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_location_ar->CellAttributes() ?>>
<span id="el_activities_activity_location_ar">
<textarea data-table="activities" data-field="x_activity_location_ar" name="x_activity_location_ar" id="x_activity_location_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_ar->getPlaceHolder()) ?>"<?php echo $activities->activity_location_ar->EditAttributes() ?>><?php echo $activities->activity_location_ar->EditValue ?></textarea>
</span>
<?php echo $activities->activity_location_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_location_en->Visible) { // activity_location_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_location_en" class="form-group">
		<label id="elh_activities_activity_location_en" for="x_activity_location_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_location_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_location_en->CellAttributes() ?>>
<span id="el_activities_activity_location_en">
<textarea data-table="activities" data-field="x_activity_location_en" name="x_activity_location_en" id="x_activity_location_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_en->getPlaceHolder()) ?>"<?php echo $activities->activity_location_en->EditAttributes() ?>><?php echo $activities->activity_location_en->EditValue ?></textarea>
</span>
<?php echo $activities->activity_location_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_location_en">
		<td class="col-sm-2"><span id="elh_activities_activity_location_en"><?php echo $activities->activity_location_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_location_en->CellAttributes() ?>>
<span id="el_activities_activity_location_en">
<textarea data-table="activities" data-field="x_activity_location_en" name="x_activity_location_en" id="x_activity_location_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_en->getPlaceHolder()) ?>"<?php echo $activities->activity_location_en->EditAttributes() ?>><?php echo $activities->activity_location_en->EditValue ?></textarea>
</span>
<?php echo $activities->activity_location_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_location_map->Visible) { // activity_location_map ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_location_map" class="form-group">
		<label id="elh_activities_activity_location_map" for="x_activity_location_map" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_location_map->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_location_map->CellAttributes() ?>>
<span id="el_activities_activity_location_map">
<input type="text" data-table="activities" data-field="x_activity_location_map" name="x_activity_location_map" id="x_activity_location_map" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_map->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_map->EditValue ?>"<?php echo $activities->activity_location_map->EditAttributes() ?>>
</span>
<?php echo $activities->activity_location_map->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_location_map">
		<td class="col-sm-2"><span id="elh_activities_activity_location_map"><?php echo $activities->activity_location_map->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_location_map->CellAttributes() ?>>
<span id="el_activities_activity_location_map">
<input type="text" data-table="activities" data-field="x_activity_location_map" name="x_activity_location_map" id="x_activity_location_map" placeholder="<?php echo ew_HtmlEncode($activities->activity_location_map->getPlaceHolder()) ?>" value="<?php echo $activities->activity_location_map->EditValue ?>"<?php echo $activities->activity_location_map->EditAttributes() ?>>
</span>
<?php echo $activities->activity_location_map->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_image->Visible) { // activity_image ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_image" class="form-group">
		<label id="elh_activities_activity_image" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_image->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_image->CellAttributes() ?>>
<span id="el_activities_activity_image">
<div id="fd_x_activity_image">
<span title="<?php echo $activities->activity_image->FldTitle() ? $activities->activity_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($activities->activity_image->ReadOnly || $activities->activity_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="activities" data-field="x_activity_image" name="x_activity_image" id="x_activity_image"<?php echo $activities->activity_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_activity_image" id= "fn_x_activity_image" value="<?php echo $activities->activity_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_activity_image"] == "0") { ?>
<input type="hidden" name="fa_x_activity_image" id= "fa_x_activity_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_activity_image" id= "fa_x_activity_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_activity_image" id= "fs_x_activity_image" value="65535">
<input type="hidden" name="fx_x_activity_image" id= "fx_x_activity_image" value="<?php echo $activities->activity_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_activity_image" id= "fm_x_activity_image" value="<?php echo $activities->activity_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_activity_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $activities->activity_image->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_image">
		<td class="col-sm-2"><span id="elh_activities_activity_image"><?php echo $activities->activity_image->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_image->CellAttributes() ?>>
<span id="el_activities_activity_image">
<div id="fd_x_activity_image">
<span title="<?php echo $activities->activity_image->FldTitle() ? $activities->activity_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($activities->activity_image->ReadOnly || $activities->activity_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="activities" data-field="x_activity_image" name="x_activity_image" id="x_activity_image"<?php echo $activities->activity_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_activity_image" id= "fn_x_activity_image" value="<?php echo $activities->activity_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_activity_image"] == "0") { ?>
<input type="hidden" name="fa_x_activity_image" id= "fa_x_activity_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_activity_image" id= "fa_x_activity_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_activity_image" id= "fs_x_activity_image" value="65535">
<input type="hidden" name="fx_x_activity_image" id= "fx_x_activity_image" value="<?php echo $activities->activity_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_activity_image" id= "fm_x_activity_image" value="<?php echo $activities->activity_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_activity_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $activities->activity_image->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_organizer_ar->Visible) { // activity_organizer_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_organizer_ar" class="form-group">
		<label id="elh_activities_activity_organizer_ar" for="x_activity_organizer_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_organizer_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_organizer_ar->CellAttributes() ?>>
<span id="el_activities_activity_organizer_ar">
<input type="text" data-table="activities" data-field="x_activity_organizer_ar" name="x_activity_organizer_ar" id="x_activity_organizer_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_ar->EditValue ?>"<?php echo $activities->activity_organizer_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_organizer_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_organizer_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_organizer_ar"><?php echo $activities->activity_organizer_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_organizer_ar->CellAttributes() ?>>
<span id="el_activities_activity_organizer_ar">
<input type="text" data-table="activities" data-field="x_activity_organizer_ar" name="x_activity_organizer_ar" id="x_activity_organizer_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_ar->EditValue ?>"<?php echo $activities->activity_organizer_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_organizer_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_organizer_en->Visible) { // activity_organizer_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_organizer_en" class="form-group">
		<label id="elh_activities_activity_organizer_en" for="x_activity_organizer_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_organizer_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_organizer_en->CellAttributes() ?>>
<span id="el_activities_activity_organizer_en">
<input type="text" data-table="activities" data-field="x_activity_organizer_en" name="x_activity_organizer_en" id="x_activity_organizer_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_en->EditValue ?>"<?php echo $activities->activity_organizer_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_organizer_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_organizer_en">
		<td class="col-sm-2"><span id="elh_activities_activity_organizer_en"><?php echo $activities->activity_organizer_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_organizer_en->CellAttributes() ?>>
<span id="el_activities_activity_organizer_en">
<input type="text" data-table="activities" data-field="x_activity_organizer_en" name="x_activity_organizer_en" id="x_activity_organizer_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_organizer_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_organizer_en->EditValue ?>"<?php echo $activities->activity_organizer_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_organizer_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_category_ar->Visible) { // activity_category_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_category_ar" class="form-group">
		<label id="elh_activities_activity_category_ar" for="x_activity_category_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_category_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_category_ar->CellAttributes() ?>>
<span id="el_activities_activity_category_ar">
<input type="text" data-table="activities" data-field="x_activity_category_ar" name="x_activity_category_ar" id="x_activity_category_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_ar->EditValue ?>"<?php echo $activities->activity_category_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_category_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_category_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_category_ar"><?php echo $activities->activity_category_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_category_ar->CellAttributes() ?>>
<span id="el_activities_activity_category_ar">
<input type="text" data-table="activities" data-field="x_activity_category_ar" name="x_activity_category_ar" id="x_activity_category_ar" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_ar->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_ar->EditValue ?>"<?php echo $activities->activity_category_ar->EditAttributes() ?>>
</span>
<?php echo $activities->activity_category_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_category_en->Visible) { // activity_category_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_category_en" class="form-group">
		<label id="elh_activities_activity_category_en" for="x_activity_category_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_category_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_category_en->CellAttributes() ?>>
<span id="el_activities_activity_category_en">
<input type="text" data-table="activities" data-field="x_activity_category_en" name="x_activity_category_en" id="x_activity_category_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_en->EditValue ?>"<?php echo $activities->activity_category_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_category_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_category_en">
		<td class="col-sm-2"><span id="elh_activities_activity_category_en"><?php echo $activities->activity_category_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_category_en->CellAttributes() ?>>
<span id="el_activities_activity_category_en">
<input type="text" data-table="activities" data-field="x_activity_category_en" name="x_activity_category_en" id="x_activity_category_en" placeholder="<?php echo ew_HtmlEncode($activities->activity_category_en->getPlaceHolder()) ?>" value="<?php echo $activities->activity_category_en->EditValue ?>"<?php echo $activities->activity_category_en->EditAttributes() ?>>
</span>
<?php echo $activities->activity_category_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_type->Visible) { // activity_type ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_type" class="form-group">
		<label id="elh_activities_activity_type" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_type->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_type->CellAttributes() ?>>
<span id="el_activities_activity_type">
<div id="tp_x_activity_type" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_type" data-value-separator="<?php echo $activities->activity_type->DisplayValueSeparatorAttribute() ?>" name="x_activity_type" id="x_activity_type" value="{value}"<?php echo $activities->activity_type->EditAttributes() ?>></div>
<div id="dsl_x_activity_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_type->RadioButtonListHtml(FALSE, "x_activity_type") ?>
</div></div>
</span>
<?php echo $activities->activity_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_type">
		<td class="col-sm-2"><span id="elh_activities_activity_type"><?php echo $activities->activity_type->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_type->CellAttributes() ?>>
<span id="el_activities_activity_type">
<div id="tp_x_activity_type" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_type" data-value-separator="<?php echo $activities->activity_type->DisplayValueSeparatorAttribute() ?>" name="x_activity_type" id="x_activity_type" value="{value}"<?php echo $activities->activity_type->EditAttributes() ?>></div>
<div id="dsl_x_activity_type" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_type->RadioButtonListHtml(FALSE, "x_activity_type") ?>
</div></div>
</span>
<?php echo $activities->activity_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_gender_target->Visible) { // activity_gender_target ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_gender_target" class="form-group">
		<label id="elh_activities_activity_gender_target" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_gender_target->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_gender_target->CellAttributes() ?>>
<span id="el_activities_activity_gender_target">
<div id="tp_x_activity_gender_target" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_gender_target" data-value-separator="<?php echo $activities->activity_gender_target->DisplayValueSeparatorAttribute() ?>" name="x_activity_gender_target" id="x_activity_gender_target" value="{value}"<?php echo $activities->activity_gender_target->EditAttributes() ?>></div>
<div id="dsl_x_activity_gender_target" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_gender_target->RadioButtonListHtml(FALSE, "x_activity_gender_target") ?>
</div></div>
</span>
<?php echo $activities->activity_gender_target->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_gender_target">
		<td class="col-sm-2"><span id="elh_activities_activity_gender_target"><?php echo $activities->activity_gender_target->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_gender_target->CellAttributes() ?>>
<span id="el_activities_activity_gender_target">
<div id="tp_x_activity_gender_target" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_gender_target" data-value-separator="<?php echo $activities->activity_gender_target->DisplayValueSeparatorAttribute() ?>" name="x_activity_gender_target" id="x_activity_gender_target" value="{value}"<?php echo $activities->activity_gender_target->EditAttributes() ?>></div>
<div id="dsl_x_activity_gender_target" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_gender_target->RadioButtonListHtml(FALSE, "x_activity_gender_target") ?>
</div></div>
</span>
<?php echo $activities->activity_gender_target->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_terms_and_conditions_ar->Visible) { // activity_terms_and_conditions_ar ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_terms_and_conditions_ar" class="form-group">
		<label id="elh_activities_activity_terms_and_conditions_ar" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_terms_and_conditions_ar->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_terms_and_conditions_ar->CellAttributes() ?>>
<span id="el_activities_activity_terms_and_conditions_ar">
<?php ew_AppendClass($activities->activity_terms_and_conditions_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_terms_and_conditions_ar" name="x_activity_terms_and_conditions_ar" id="x_activity_terms_and_conditions_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_ar->getPlaceHolder()) ?>"<?php echo $activities->activity_terms_and_conditions_ar->EditAttributes() ?>><?php echo $activities->activity_terms_and_conditions_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_terms_and_conditions_ar", 35, 4, <?php echo ($activities->activity_terms_and_conditions_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_terms_and_conditions_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_terms_and_conditions_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_terms_and_conditions_ar"><?php echo $activities->activity_terms_and_conditions_ar->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_terms_and_conditions_ar->CellAttributes() ?>>
<span id="el_activities_activity_terms_and_conditions_ar">
<?php ew_AppendClass($activities->activity_terms_and_conditions_ar->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_terms_and_conditions_ar" name="x_activity_terms_and_conditions_ar" id="x_activity_terms_and_conditions_ar" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_ar->getPlaceHolder()) ?>"<?php echo $activities->activity_terms_and_conditions_ar->EditAttributes() ?>><?php echo $activities->activity_terms_and_conditions_ar->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_terms_and_conditions_ar", 35, 4, <?php echo ($activities->activity_terms_and_conditions_ar->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_terms_and_conditions_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_terms_and_conditions_en->Visible) { // activity_terms_and_conditions_en ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_terms_and_conditions_en" class="form-group">
		<label id="elh_activities_activity_terms_and_conditions_en" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_terms_and_conditions_en->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_terms_and_conditions_en->CellAttributes() ?>>
<span id="el_activities_activity_terms_and_conditions_en">
<?php ew_AppendClass($activities->activity_terms_and_conditions_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_terms_and_conditions_en" name="x_activity_terms_and_conditions_en" id="x_activity_terms_and_conditions_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_en->getPlaceHolder()) ?>"<?php echo $activities->activity_terms_and_conditions_en->EditAttributes() ?>><?php echo $activities->activity_terms_and_conditions_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_terms_and_conditions_en", 35, 4, <?php echo ($activities->activity_terms_and_conditions_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_terms_and_conditions_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_terms_and_conditions_en">
		<td class="col-sm-2"><span id="elh_activities_activity_terms_and_conditions_en"><?php echo $activities->activity_terms_and_conditions_en->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_terms_and_conditions_en->CellAttributes() ?>>
<span id="el_activities_activity_terms_and_conditions_en">
<?php ew_AppendClass($activities->activity_terms_and_conditions_en->EditAttrs["class"], "editor"); ?>
<textarea data-table="activities" data-field="x_activity_terms_and_conditions_en" name="x_activity_terms_and_conditions_en" id="x_activity_terms_and_conditions_en" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($activities->activity_terms_and_conditions_en->getPlaceHolder()) ?>"<?php echo $activities->activity_terms_and_conditions_en->EditAttributes() ?>><?php echo $activities->activity_terms_and_conditions_en->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("factivitiesedit", "x_activity_terms_and_conditions_en", 35, 4, <?php echo ($activities->activity_terms_and_conditions_en->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $activities->activity_terms_and_conditions_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->activity_active->Visible) { // activity_active ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_activity_active" class="form-group">
		<label id="elh_activities_activity_active" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->activity_active->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->activity_active->CellAttributes() ?>>
<span id="el_activities_activity_active">
<div id="tp_x_activity_active" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_active" data-value-separator="<?php echo $activities->activity_active->DisplayValueSeparatorAttribute() ?>" name="x_activity_active" id="x_activity_active" value="{value}"<?php echo $activities->activity_active->EditAttributes() ?>></div>
<div id="dsl_x_activity_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_active->RadioButtonListHtml(FALSE, "x_activity_active") ?>
</div></div>
</span>
<?php echo $activities->activity_active->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_active">
		<td class="col-sm-2"><span id="elh_activities_activity_active"><?php echo $activities->activity_active->FldCaption() ?></span></td>
		<td<?php echo $activities->activity_active->CellAttributes() ?>>
<span id="el_activities_activity_active">
<div id="tp_x_activity_active" class="ewTemplate"><input type="radio" data-table="activities" data-field="x_activity_active" data-value-separator="<?php echo $activities->activity_active->DisplayValueSeparatorAttribute() ?>" name="x_activity_active" id="x_activity_active" value="{value}"<?php echo $activities->activity_active->EditAttributes() ?>></div>
<div id="dsl_x_activity_active" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $activities->activity_active->RadioButtonListHtml(FALSE, "x_activity_active") ?>
</div></div>
</span>
<?php echo $activities->activity_active->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities->leader_username->Visible) { // leader_username ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
	<div id="r_leader_username" class="form-group">
		<label id="elh_activities_leader_username" class="<?php echo $activities_edit->LeftColumnClass ?>"><?php echo $activities->leader_username->FldCaption() ?></label>
		<div class="<?php echo $activities_edit->RightColumnClass ?>"><div<?php echo $activities->leader_username->CellAttributes() ?>>
<span id="el_activities_leader_username">
<?php
$wrkonchange = trim(" " . @$activities->leader_username->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$activities->leader_username->EditAttrs["onchange"] = "";
?>
<span id="as_x_leader_username" style="white-space: nowrap; z-index: 8740">
	<input type="text" name="sv_x_leader_username" id="sv_x_leader_username" value="<?php echo $activities->leader_username->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>"<?php echo $activities->leader_username->EditAttributes() ?>>
</span>
<input type="hidden" data-table="activities" data-field="x_leader_username" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $activities->leader_username->DisplayValueSeparatorAttribute() ?>" name="x_leader_username" id="x_leader_username" value="<?php echo ew_HtmlEncode($activities->leader_username->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
factivitiesedit.CreateAutoSuggest({"id":"x_leader_username","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($activities->leader_username->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_leader_username',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
<?php echo $activities->leader_username->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_leader_username">
		<td class="col-sm-2"><span id="elh_activities_leader_username"><?php echo $activities->leader_username->FldCaption() ?></span></td>
		<td<?php echo $activities->leader_username->CellAttributes() ?>>
<span id="el_activities_leader_username">
<?php
$wrkonchange = trim(" " . @$activities->leader_username->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$activities->leader_username->EditAttrs["onchange"] = "";
?>
<span id="as_x_leader_username" style="white-space: nowrap; z-index: 8740">
	<input type="text" name="sv_x_leader_username" id="sv_x_leader_username" value="<?php echo $activities->leader_username->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($activities->leader_username->getPlaceHolder()) ?>"<?php echo $activities->leader_username->EditAttributes() ?>>
</span>
<input type="hidden" data-table="activities" data-field="x_leader_username" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $activities->leader_username->DisplayValueSeparatorAttribute() ?>" name="x_leader_username" id="x_leader_username" value="<?php echo ew_HtmlEncode($activities->leader_username->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<script type="text/javascript">
factivitiesedit.CreateAutoSuggest({"id":"x_leader_username","forceSelect":false});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($activities->leader_username->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_leader_username',m:0,n:10,srch:true});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
</span>
<?php echo $activities->leader_username->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($activities_edit->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php
	if (in_array("registered_users", explode(",", $activities->getCurrentDetailTable())) && $registered_users->DetailEdit) {
?>
<?php if ($activities->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("registered_users", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "registered_usersgrid.php" ?>
<?php } ?>
<?php if (!$activities_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $activities_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $activities_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$activities_edit->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
factivitiesedit.Init();
</script>
<?php
$activities_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$activities_edit->Page_Terminate();
?>
