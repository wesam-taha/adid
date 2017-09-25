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

$registered_users_add = NULL; // Initialize page object first

class cregistered_users_add extends cregistered_users {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'registered_users';

	// Page object name
	var $PageObjName = 'registered_users_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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

		// Set up master/detail parameters
		$this->SetupMasterParms();

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
					$this->Page_Terminate("registered_userslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "registered_userslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "registered_usersview.php")
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
		$this->activity_id->CurrentValue = NULL;
		$this->activity_id->OldValue = $this->activity_id->CurrentValue;
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->admin_approval->CurrentValue = NULL;
		$this->admin_approval->OldValue = $this->admin_approval->CurrentValue;
		$this->admin_comment->CurrentValue = NULL;
		$this->admin_comment->OldValue = $this->admin_comment->CurrentValue;
		$this->evaluation_rate->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->activity_id->FldIsDetailKey) {
			$this->activity_id->setFormValue($objForm->GetValue("x_activity_id"));
		}
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->admin_approval->FldIsDetailKey) {
			$this->admin_approval->setFormValue($objForm->GetValue("x_admin_approval"));
		}
		if (!$this->admin_comment->FldIsDetailKey) {
			$this->admin_comment->setFormValue($objForm->GetValue("x_admin_comment"));
		}
		if (!$this->evaluation_rate->FldIsDetailKey) {
			$this->evaluation_rate->setFormValue($objForm->GetValue("x_evaluation_rate"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->activity_id->CurrentValue = $this->activity_id->FormValue;
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->admin_approval->CurrentValue = $this->admin_approval->FormValue;
		$this->admin_comment->CurrentValue = $this->admin_comment->FormValue;
		$this->evaluation_rate->CurrentValue = $this->evaluation_rate->FormValue;
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
		$this->activity_id->setDbValue($row['activity_id']);
		$this->user_id->setDbValue($row['user_id']);
		$this->admin_approval->setDbValue($row['admin_approval']);
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->evaluation_rate->setDbValue($row['evaluation_rate']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['activity_id'] = NULL;
		$row['user_id'] = NULL;
		$row['admin_approval'] = NULL;
		$row['admin_comment'] = NULL;
		$row['evaluation_rate'] = NULL;
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
		$this->activity_id->DbValue = $row['activity_id'];
		$this->user_id->DbValue = $row['user_id'];
		$this->admin_approval->DbValue = $row['admin_approval'];
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->evaluation_rate->DbValue = $row['evaluation_rate'];
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// activity_id
			$this->activity_id->EditCustomAttributes = "";
			if ($this->activity_id->getSessionValue() <> "") {
				$this->activity_id->CurrentValue = $this->activity_id->getSessionValue();
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
			} else {
			if (trim(strval($this->activity_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`activity_id`" . ew_SearchString("=", $this->activity_id->CurrentValue, EW_DATATYPE_NUMBER, "");
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
				$this->activity_id->ViewValue = $this->activity_id->DisplayValue($arwrk);
			} else {
				$this->activity_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][2] = ew_FormatDateTime($arwrk[$rowcntwrk][2], 0);
			}
			$this->activity_id->EditValue = $arwrk;
			}

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			$this->user_id->EditValue = ew_HtmlEncode($this->user_id->CurrentValue);
			$this->user_id->PlaceHolder = ew_RemoveHtml($this->user_id->FldCaption());

			// admin_approval
			$this->admin_approval->EditCustomAttributes = "";
			$this->admin_approval->EditValue = $this->admin_approval->Options(FALSE);

			// admin_comment
			$this->admin_comment->EditAttrs["class"] = "form-control";
			$this->admin_comment->EditCustomAttributes = "";
			$this->admin_comment->EditValue = ew_HtmlEncode($this->admin_comment->CurrentValue);
			$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

			// evaluation_rate
			$this->evaluation_rate->EditAttrs["class"] = "form-control";
			$this->evaluation_rate->EditCustomAttributes = "";
			$this->evaluation_rate->EditValue = ew_HtmlEncode($this->evaluation_rate->CurrentValue);
			$this->evaluation_rate->PlaceHolder = ew_RemoveHtml($this->evaluation_rate->FldCaption());

			// Add refer script
			// activity_id

			$this->activity_id->LinkCustomAttributes = "";
			$this->activity_id->HrefValue = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";

			// evaluation_rate
			$this->evaluation_rate->LinkCustomAttributes = "";
			$this->evaluation_rate->HrefValue = "";
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
		if (!ew_CheckInteger($this->evaluation_rate->FormValue)) {
			ew_AddMessage($gsFormError, $this->evaluation_rate->FldErrMsg());
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

		// Check referential integrity for master table 'activities'
		$bValidMasterRecord = TRUE;
		$sMasterFilter = $this->SqlMasterFilter_activities();
		if (strval($this->activity_id->CurrentValue) <> "") {
			$sMasterFilter = str_replace("@activity_id@", ew_AdjustSql($this->activity_id->CurrentValue, "DB"), $sMasterFilter);
		} else {
			$bValidMasterRecord = FALSE;
		}
		if ($bValidMasterRecord) {
			if (!isset($GLOBALS["activities"])) $GLOBALS["activities"] = new cactivities();
			$rsmaster = $GLOBALS["activities"]->LoadRs($sMasterFilter);
			$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
			$rsmaster->Close();
		}
		if (!$bValidMasterRecord) {
			$sRelatedRecordMsg = str_replace("%t", "activities", $Language->Phrase("RelatedRecordRequired"));
			$this->setFailureMessage($sRelatedRecordMsg);
			return FALSE;
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// activity_id
		$this->activity_id->SetDbValueDef($rsnew, $this->activity_id->CurrentValue, NULL, FALSE);

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, NULL, FALSE);

		// admin_approval
		$this->admin_approval->SetDbValueDef($rsnew, $this->admin_approval->CurrentValue, NULL, FALSE);

		// admin_comment
		$this->admin_comment->SetDbValueDef($rsnew, $this->admin_comment->CurrentValue, NULL, FALSE);

		// evaluation_rate
		$this->evaluation_rate->SetDbValueDef($rsnew, $this->evaluation_rate->CurrentValue, NULL, strval($this->evaluation_rate->CurrentValue) == "");

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

	// Set up master/detail based on QueryString
	function SetupMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "activities") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_activity_id"] <> "") {
					$GLOBALS["activities"]->activity_id->setQueryStringValue($_GET["fk_activity_id"]);
					$this->activity_id->setQueryStringValue($GLOBALS["activities"]->activity_id->QueryStringValue);
					$this->activity_id->setSessionValue($this->activity_id->QueryStringValue);
					if (!is_numeric($GLOBALS["activities"]->activity_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "activities") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_activity_id"] <> "") {
					$GLOBALS["activities"]->activity_id->setFormValue($_POST["fk_activity_id"]);
					$this->activity_id->setFormValue($GLOBALS["activities"]->activity_id->FormValue);
					$this->activity_id->setSessionValue($this->activity_id->FormValue);
					if (!is_numeric($GLOBALS["activities"]->activity_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "activities") {
				if ($this->activity_id->CurrentValue == "") $this->activity_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("registered_userslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($registered_users_add)) $registered_users_add = new cregistered_users_add();

// Page init
$registered_users_add->Page_Init();

// Page main
$registered_users_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$registered_users_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fregistered_usersadd = new ew_Form("fregistered_usersadd", "add");

// Validate form
fregistered_usersadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_evaluation_rate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($registered_users->evaluation_rate->FldErrMsg()) ?>");

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
fregistered_usersadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fregistered_usersadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fregistered_usersadd.Lists["x_activity_id"] = {"LinkField":"x_activity_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_activity_name_ar","x_activity_start_date","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"activities"};
fregistered_usersadd.Lists["x_activity_id"].Data = "<?php echo $registered_users_add->activity_id->LookupFilterQuery(FALSE, "add") ?>";
fregistered_usersadd.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fregistered_usersadd.Lists["x_admin_approval"].Options = <?php echo json_encode($registered_users_add->admin_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $registered_users_add->ShowPageHeader(); ?>
<?php
$registered_users_add->ShowMessage();
?>
<form name="fregistered_usersadd" id="fregistered_usersadd" class="<?php echo $registered_users_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($registered_users_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $registered_users_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="registered_users">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($registered_users_add->IsModal) ?>">
<?php if ($registered_users->getCurrentMasterTable() == "activities") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="activities">
<input type="hidden" name="fk_activity_id" value="<?php echo $registered_users->activity_id->getSessionValue() ?>">
<?php } ?>
<?php if (!$registered_users_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_registered_usersadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($registered_users->activity_id->Visible) { // activity_id ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
	<div id="r_activity_id" class="form-group">
		<label id="elh_registered_users_activity_id" for="x_activity_id" class="<?php echo $registered_users_add->LeftColumnClass ?>"><?php echo $registered_users->activity_id->FldCaption() ?></label>
		<div class="<?php echo $registered_users_add->RightColumnClass ?>"><div<?php echo $registered_users->activity_id->CellAttributes() ?>>
<?php if ($registered_users->activity_id->getSessionValue() <> "") { ?>
<span id="el_registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->activity_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_activity_id" name="x_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_activity_id"><?php echo (strval($registered_users->activity_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x_activity_id" id="x_activity_id" value="<?php echo $registered_users->activity_id->CurrentValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $registered_users->activity_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_activity_id">
		<td class="col-sm-2"><span id="elh_registered_users_activity_id"><?php echo $registered_users->activity_id->FldCaption() ?></span></td>
		<td<?php echo $registered_users->activity_id->CellAttributes() ?>>
<?php if ($registered_users->activity_id->getSessionValue() <> "") { ?>
<span id="el_registered_users_activity_id">
<span<?php echo $registered_users->activity_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $registered_users->activity_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_activity_id" name="x_activity_id" value="<?php echo ew_HtmlEncode($registered_users->activity_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_registered_users_activity_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x_activity_id"><?php echo (strval($registered_users->activity_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $registered_users->activity_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($registered_users->activity_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_activity_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="registered_users" data-field="x_activity_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $registered_users->activity_id->DisplayValueSeparatorAttribute() ?>" name="x_activity_id" id="x_activity_id" value="<?php echo $registered_users->activity_id->CurrentValue ?>"<?php echo $registered_users->activity_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $registered_users->activity_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->user_id->Visible) { // user_id ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
	<div id="r_user_id" class="form-group">
		<label id="elh_registered_users_user_id" for="x_user_id" class="<?php echo $registered_users_add->LeftColumnClass ?>"><?php echo $registered_users->user_id->FldCaption() ?></label>
		<div class="<?php echo $registered_users_add->RightColumnClass ?>"><div<?php echo $registered_users->user_id->CellAttributes() ?>>
<span id="el_registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x_user_id" id="x_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
<?php echo $registered_users->user_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_user_id">
		<td class="col-sm-2"><span id="elh_registered_users_user_id"><?php echo $registered_users->user_id->FldCaption() ?></span></td>
		<td<?php echo $registered_users->user_id->CellAttributes() ?>>
<span id="el_registered_users_user_id">
<input type="text" data-table="registered_users" data-field="x_user_id" name="x_user_id" id="x_user_id" placeholder="<?php echo ew_HtmlEncode($registered_users->user_id->getPlaceHolder()) ?>" value="<?php echo $registered_users->user_id->EditValue ?>"<?php echo $registered_users->user_id->EditAttributes() ?>>
</span>
<?php echo $registered_users->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->admin_approval->Visible) { // admin_approval ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
	<div id="r_admin_approval" class="form-group">
		<label id="elh_registered_users_admin_approval" class="<?php echo $registered_users_add->LeftColumnClass ?>"><?php echo $registered_users->admin_approval->FldCaption() ?></label>
		<div class="<?php echo $registered_users_add->RightColumnClass ?>"><div<?php echo $registered_users->admin_approval->CellAttributes() ?>>
<span id="el_registered_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
<?php echo $registered_users->admin_approval->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_registered_users_admin_approval"><?php echo $registered_users->admin_approval->FldCaption() ?></span></td>
		<td<?php echo $registered_users->admin_approval->CellAttributes() ?>>
<span id="el_registered_users_admin_approval">
<div id="tp_x_admin_approval" class="ewTemplate"><input type="radio" data-table="registered_users" data-field="x_admin_approval" data-value-separator="<?php echo $registered_users->admin_approval->DisplayValueSeparatorAttribute() ?>" name="x_admin_approval" id="x_admin_approval" value="{value}"<?php echo $registered_users->admin_approval->EditAttributes() ?>></div>
<div id="dsl_x_admin_approval" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $registered_users->admin_approval->RadioButtonListHtml(FALSE, "x_admin_approval") ?>
</div></div>
</span>
<?php echo $registered_users->admin_approval->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->admin_comment->Visible) { // admin_comment ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
	<div id="r_admin_comment" class="form-group">
		<label id="elh_registered_users_admin_comment" for="x_admin_comment" class="<?php echo $registered_users_add->LeftColumnClass ?>"><?php echo $registered_users->admin_comment->FldCaption() ?></label>
		<div class="<?php echo $registered_users_add->RightColumnClass ?>"><div<?php echo $registered_users->admin_comment->CellAttributes() ?>>
<span id="el_registered_users_admin_comment">
<textarea data-table="registered_users" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>><?php echo $registered_users->admin_comment->EditValue ?></textarea>
</span>
<?php echo $registered_users->admin_comment->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_registered_users_admin_comment"><?php echo $registered_users->admin_comment->FldCaption() ?></span></td>
		<td<?php echo $registered_users->admin_comment->CellAttributes() ?>>
<span id="el_registered_users_admin_comment">
<textarea data-table="registered_users" data-field="x_admin_comment" name="x_admin_comment" id="x_admin_comment" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($registered_users->admin_comment->getPlaceHolder()) ?>"<?php echo $registered_users->admin_comment->EditAttributes() ?>><?php echo $registered_users->admin_comment->EditValue ?></textarea>
</span>
<?php echo $registered_users->admin_comment->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users->evaluation_rate->Visible) { // evaluation_rate ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
	<div id="r_evaluation_rate" class="form-group">
		<label id="elh_registered_users_evaluation_rate" for="x_evaluation_rate" class="<?php echo $registered_users_add->LeftColumnClass ?>"><?php echo $registered_users->evaluation_rate->FldCaption() ?></label>
		<div class="<?php echo $registered_users_add->RightColumnClass ?>"><div<?php echo $registered_users->evaluation_rate->CellAttributes() ?>>
<span id="el_registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x_evaluation_rate" id="x_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
<?php echo $registered_users->evaluation_rate->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_evaluation_rate">
		<td class="col-sm-2"><span id="elh_registered_users_evaluation_rate"><?php echo $registered_users->evaluation_rate->FldCaption() ?></span></td>
		<td<?php echo $registered_users->evaluation_rate->CellAttributes() ?>>
<span id="el_registered_users_evaluation_rate">
<input type="text" data-table="registered_users" data-field="x_evaluation_rate" name="x_evaluation_rate" id="x_evaluation_rate" size="30" placeholder="<?php echo ew_HtmlEncode($registered_users->evaluation_rate->getPlaceHolder()) ?>" value="<?php echo $registered_users->evaluation_rate->EditValue ?>"<?php echo $registered_users->evaluation_rate->EditAttributes() ?>>
</span>
<?php echo $registered_users->evaluation_rate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($registered_users_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$registered_users_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $registered_users_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $registered_users_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$registered_users_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fregistered_usersadd.Init();
</script>
<?php
$registered_users_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$registered_users_add->Page_Terminate();
?>
