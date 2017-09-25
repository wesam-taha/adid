<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "rating_valuesinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$rating_values_add = NULL; // Initialize page object first

class crating_values_add extends crating_values {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'rating_values';

	// Page object name
	var $PageObjName = 'rating_values_add';

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

		// Table object (rating_values)
		if (!isset($GLOBALS["rating_values"]) || get_class($GLOBALS["rating_values"]) == "crating_values") {
			$GLOBALS["rating_values"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["rating_values"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'rating_values', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("rating_valueslist.php"));
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
		$this->event_id->SetVisibility();
		$this->rated_by->SetVisibility();
		$this->rating_type->SetVisibility();
		$this->rating_value->SetVisibility();

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
		global $EW_EXPORT, $rating_values;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($rating_values);
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
					if ($pageName == "rating_valuesview.php")
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
					$this->Page_Terminate("rating_valueslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "rating_valueslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "rating_valuesview.php")
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
		$this->user_id->CurrentValue = NULL;
		$this->user_id->OldValue = $this->user_id->CurrentValue;
		$this->event_id->CurrentValue = NULL;
		$this->event_id->OldValue = $this->event_id->CurrentValue;
		$this->rated_by->CurrentValue = NULL;
		$this->rated_by->OldValue = $this->rated_by->CurrentValue;
		$this->rating_type->CurrentValue = NULL;
		$this->rating_type->OldValue = $this->rating_type->CurrentValue;
		$this->rating_value->CurrentValue = NULL;
		$this->rating_value->OldValue = $this->rating_value->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user_id->FldIsDetailKey) {
			$this->user_id->setFormValue($objForm->GetValue("x_user_id"));
		}
		if (!$this->event_id->FldIsDetailKey) {
			$this->event_id->setFormValue($objForm->GetValue("x_event_id"));
		}
		if (!$this->rated_by->FldIsDetailKey) {
			$this->rated_by->setFormValue($objForm->GetValue("x_rated_by"));
		}
		if (!$this->rating_type->FldIsDetailKey) {
			$this->rating_type->setFormValue($objForm->GetValue("x_rating_type"));
		}
		if (!$this->rating_value->FldIsDetailKey) {
			$this->rating_value->setFormValue($objForm->GetValue("x_rating_value"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->user_id->CurrentValue = $this->user_id->FormValue;
		$this->event_id->CurrentValue = $this->event_id->FormValue;
		$this->rated_by->CurrentValue = $this->rated_by->FormValue;
		$this->rating_type->CurrentValue = $this->rating_type->FormValue;
		$this->rating_value->CurrentValue = $this->rating_value->FormValue;
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
		$this->user_id->setDbValue($row['user_id']);
		$this->event_id->setDbValue($row['event_id']);
		$this->rated_by->setDbValue($row['rated_by']);
		$this->rating_type->setDbValue($row['rating_type']);
		$this->rating_value->setDbValue($row['rating_value']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['user_id'] = NULL;
		$row['event_id'] = NULL;
		$row['rated_by'] = NULL;
		$row['rating_type'] = NULL;
		$row['rating_value'] = NULL;
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
		$this->user_id->DbValue = $row['user_id'];
		$this->event_id->DbValue = $row['event_id'];
		$this->rated_by->DbValue = $row['rated_by'];
		$this->rating_type->DbValue = $row['rating_type'];
		$this->rating_value->DbValue = $row['rating_value'];
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
		// user_id
		// event_id
		// rated_by
		// rating_type
		// rating_value

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// user_id
		if (strval($this->user_id->CurrentValue) <> "") {
			$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->user_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->user_id->ViewValue = $this->user_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->user_id->ViewValue = $this->user_id->CurrentValue;
			}
		} else {
			$this->user_id->ViewValue = NULL;
		}
		$this->user_id->ViewCustomAttributes = "";

		// event_id
		if (strval($this->event_id->CurrentValue) <> "") {
			$sFilterWrk = "`activity_id`" . ew_SearchString("=", $this->event_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `activity_id`, `activity_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `activities`";
		$sWhereWrk = "";
		$this->event_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->event_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->event_id->ViewValue = $this->event_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->event_id->ViewValue = $this->event_id->CurrentValue;
			}
		} else {
			$this->event_id->ViewValue = NULL;
		}
		$this->event_id->ViewCustomAttributes = "";

		// rated_by
		if (strval($this->rated_by->CurrentValue) <> "") {
			$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->rated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->rated_by->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rated_by, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->rated_by->ViewValue = $this->rated_by->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rated_by->ViewValue = $this->rated_by->CurrentValue;
			}
		} else {
			$this->rated_by->ViewValue = NULL;
		}
		$this->rated_by->ViewCustomAttributes = "";

		// rating_type
		if (strval($this->rating_type->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->rating_type->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rating_criteria_list`";
		$sWhereWrk = "";
		$this->rating_type->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->rating_type, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->rating_type->ViewValue = $this->rating_type->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->rating_type->ViewValue = $this->rating_type->CurrentValue;
			}
		} else {
			$this->rating_type->ViewValue = NULL;
		}
		$this->rating_type->ViewCustomAttributes = "";

		// rating_value
		$this->rating_value->ViewValue = $this->rating_value->CurrentValue;
		$this->rating_value->ViewCustomAttributes = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// event_id
			$this->event_id->LinkCustomAttributes = "";
			$this->event_id->HrefValue = "";
			$this->event_id->TooltipValue = "";

			// rated_by
			$this->rated_by->LinkCustomAttributes = "";
			$this->rated_by->HrefValue = "";
			$this->rated_by->TooltipValue = "";

			// rating_type
			$this->rating_type->LinkCustomAttributes = "";
			$this->rating_type->HrefValue = "";
			$this->rating_type->TooltipValue = "";

			// rating_value
			$this->rating_value->LinkCustomAttributes = "";
			$this->rating_value->HrefValue = "";
			$this->rating_value->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user_id
			$this->user_id->EditAttrs["class"] = "form-control";
			$this->user_id->EditCustomAttributes = "";
			if (trim(strval($this->user_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->user_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->user_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->user_id->EditValue = $arwrk;

			// event_id
			$this->event_id->EditAttrs["class"] = "form-control";
			$this->event_id->EditCustomAttributes = "";
			if (trim(strval($this->event_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`activity_id`" . ew_SearchString("=", $this->event_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `activity_id`, `activity_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `activities`";
			$sWhereWrk = "";
			$this->event_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->event_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->event_id->EditValue = $arwrk;

			// rated_by
			$this->rated_by->EditAttrs["class"] = "form-control";
			$this->rated_by->EditCustomAttributes = "";
			if (trim(strval($this->rated_by->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->rated_by->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `users`";
			$sWhereWrk = "";
			$this->rated_by->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->rated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->rated_by->EditValue = $arwrk;

			// rating_type
			$this->rating_type->EditAttrs["class"] = "form-control";
			$this->rating_type->EditCustomAttributes = "";
			if (trim(strval($this->rating_type->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->rating_type->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `rating_criteria_list`";
			$sWhereWrk = "";
			$this->rating_type->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->rating_type, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->rating_type->EditValue = $arwrk;

			// rating_value
			$this->rating_value->EditAttrs["class"] = "form-control";
			$this->rating_value->EditCustomAttributes = "";
			$this->rating_value->EditValue = ew_HtmlEncode($this->rating_value->CurrentValue);
			$this->rating_value->PlaceHolder = ew_RemoveHtml($this->rating_value->FldCaption());

			// Add refer script
			// user_id

			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";

			// event_id
			$this->event_id->LinkCustomAttributes = "";
			$this->event_id->HrefValue = "";

			// rated_by
			$this->rated_by->LinkCustomAttributes = "";
			$this->rated_by->HrefValue = "";

			// rating_type
			$this->rating_type->LinkCustomAttributes = "";
			$this->rating_type->HrefValue = "";

			// rating_value
			$this->rating_value->LinkCustomAttributes = "";
			$this->rating_value->HrefValue = "";
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
		if (!$this->user_id->FldIsDetailKey && !is_null($this->user_id->FormValue) && $this->user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->user_id->FldCaption(), $this->user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->rating_value->FormValue)) {
			ew_AddMessage($gsFormError, $this->rating_value->FldErrMsg());
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

		// user_id
		$this->user_id->SetDbValueDef($rsnew, $this->user_id->CurrentValue, "", FALSE);

		// event_id
		$this->event_id->SetDbValueDef($rsnew, $this->event_id->CurrentValue, NULL, FALSE);

		// rated_by
		$this->rated_by->SetDbValueDef($rsnew, $this->rated_by->CurrentValue, NULL, FALSE);

		// rating_type
		$this->rating_type->SetDbValueDef($rsnew, $this->rating_type->CurrentValue, NULL, FALSE);

		// rating_value
		$this->rating_value->SetDbValueDef($rsnew, $this->rating_value->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("rating_valueslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_user_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `user_id` AS `LinkFld`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->user_id->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`user_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->user_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_event_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `activity_id` AS `LinkFld`, `activity_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `activities`";
			$sWhereWrk = "";
			$this->event_id->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`activity_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->event_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_rated_by":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `user_id` AS `LinkFld`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
			$sWhereWrk = "";
			$this->rated_by->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`user_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rated_by, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_rating_type":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `rating_criteria_list`";
			$sWhereWrk = "";
			$this->rating_type->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->rating_type, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($rating_values_add)) $rating_values_add = new crating_values_add();

// Page init
$rating_values_add->Page_Init();

// Page main
$rating_values_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$rating_values_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = frating_valuesadd = new ew_Form("frating_valuesadd", "add");

// Validate form
frating_valuesadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $rating_values->user_id->FldCaption(), $rating_values->user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rating_value");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($rating_values->rating_value->FldErrMsg()) ?>");

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
frating_valuesadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
frating_valuesadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
frating_valuesadd.Lists["x_user_id"] = {"LinkField":"x_user_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_full_name_ar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frating_valuesadd.Lists["x_user_id"].Data = "<?php echo $rating_values_add->user_id->LookupFilterQuery(FALSE, "add") ?>";
frating_valuesadd.Lists["x_event_id"] = {"LinkField":"x_activity_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_activity_name_ar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"activities"};
frating_valuesadd.Lists["x_event_id"].Data = "<?php echo $rating_values_add->event_id->LookupFilterQuery(FALSE, "add") ?>";
frating_valuesadd.Lists["x_rated_by"] = {"LinkField":"x_user_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_full_name_ar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
frating_valuesadd.Lists["x_rated_by"].Data = "<?php echo $rating_values_add->rated_by->LookupFilterQuery(FALSE, "add") ?>";
frating_valuesadd.Lists["x_rating_type"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"rating_criteria_list"};
frating_valuesadd.Lists["x_rating_type"].Data = "<?php echo $rating_values_add->rating_type->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $rating_values_add->ShowPageHeader(); ?>
<?php
$rating_values_add->ShowMessage();
?>
<form name="frating_valuesadd" id="frating_valuesadd" class="<?php echo $rating_values_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($rating_values_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $rating_values_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="rating_values">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($rating_values_add->IsModal) ?>">
<?php if (!$rating_values_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_rating_valuesadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($rating_values->user_id->Visible) { // user_id ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
	<div id="r_user_id" class="form-group">
		<label id="elh_rating_values_user_id" for="x_user_id" class="<?php echo $rating_values_add->LeftColumnClass ?>"><?php echo $rating_values->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $rating_values_add->RightColumnClass ?>"><div<?php echo $rating_values->user_id->CellAttributes() ?>>
<span id="el_rating_values_user_id">
<select data-table="rating_values" data-field="x_user_id" data-value-separator="<?php echo $rating_values->user_id->DisplayValueSeparatorAttribute() ?>" id="x_user_id" name="x_user_id"<?php echo $rating_values->user_id->EditAttributes() ?>>
<?php echo $rating_values->user_id->SelectOptionListHtml("x_user_id") ?>
</select>
</span>
<?php echo $rating_values->user_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_user_id">
		<td class="col-sm-2"><span id="elh_rating_values_user_id"><?php echo $rating_values->user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $rating_values->user_id->CellAttributes() ?>>
<span id="el_rating_values_user_id">
<select data-table="rating_values" data-field="x_user_id" data-value-separator="<?php echo $rating_values->user_id->DisplayValueSeparatorAttribute() ?>" id="x_user_id" name="x_user_id"<?php echo $rating_values->user_id->EditAttributes() ?>>
<?php echo $rating_values->user_id->SelectOptionListHtml("x_user_id") ?>
</select>
</span>
<?php echo $rating_values->user_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($rating_values->event_id->Visible) { // event_id ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
	<div id="r_event_id" class="form-group">
		<label id="elh_rating_values_event_id" for="x_event_id" class="<?php echo $rating_values_add->LeftColumnClass ?>"><?php echo $rating_values->event_id->FldCaption() ?></label>
		<div class="<?php echo $rating_values_add->RightColumnClass ?>"><div<?php echo $rating_values->event_id->CellAttributes() ?>>
<span id="el_rating_values_event_id">
<select data-table="rating_values" data-field="x_event_id" data-value-separator="<?php echo $rating_values->event_id->DisplayValueSeparatorAttribute() ?>" id="x_event_id" name="x_event_id"<?php echo $rating_values->event_id->EditAttributes() ?>>
<?php echo $rating_values->event_id->SelectOptionListHtml("x_event_id") ?>
</select>
</span>
<?php echo $rating_values->event_id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_event_id">
		<td class="col-sm-2"><span id="elh_rating_values_event_id"><?php echo $rating_values->event_id->FldCaption() ?></span></td>
		<td<?php echo $rating_values->event_id->CellAttributes() ?>>
<span id="el_rating_values_event_id">
<select data-table="rating_values" data-field="x_event_id" data-value-separator="<?php echo $rating_values->event_id->DisplayValueSeparatorAttribute() ?>" id="x_event_id" name="x_event_id"<?php echo $rating_values->event_id->EditAttributes() ?>>
<?php echo $rating_values->event_id->SelectOptionListHtml("x_event_id") ?>
</select>
</span>
<?php echo $rating_values->event_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($rating_values->rated_by->Visible) { // rated_by ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
	<div id="r_rated_by" class="form-group">
		<label id="elh_rating_values_rated_by" for="x_rated_by" class="<?php echo $rating_values_add->LeftColumnClass ?>"><?php echo $rating_values->rated_by->FldCaption() ?></label>
		<div class="<?php echo $rating_values_add->RightColumnClass ?>"><div<?php echo $rating_values->rated_by->CellAttributes() ?>>
<span id="el_rating_values_rated_by">
<select data-table="rating_values" data-field="x_rated_by" data-value-separator="<?php echo $rating_values->rated_by->DisplayValueSeparatorAttribute() ?>" id="x_rated_by" name="x_rated_by"<?php echo $rating_values->rated_by->EditAttributes() ?>>
<?php echo $rating_values->rated_by->SelectOptionListHtml("x_rated_by") ?>
</select>
</span>
<?php echo $rating_values->rated_by->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_rated_by">
		<td class="col-sm-2"><span id="elh_rating_values_rated_by"><?php echo $rating_values->rated_by->FldCaption() ?></span></td>
		<td<?php echo $rating_values->rated_by->CellAttributes() ?>>
<span id="el_rating_values_rated_by">
<select data-table="rating_values" data-field="x_rated_by" data-value-separator="<?php echo $rating_values->rated_by->DisplayValueSeparatorAttribute() ?>" id="x_rated_by" name="x_rated_by"<?php echo $rating_values->rated_by->EditAttributes() ?>>
<?php echo $rating_values->rated_by->SelectOptionListHtml("x_rated_by") ?>
</select>
</span>
<?php echo $rating_values->rated_by->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($rating_values->rating_type->Visible) { // rating_type ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
	<div id="r_rating_type" class="form-group">
		<label id="elh_rating_values_rating_type" for="x_rating_type" class="<?php echo $rating_values_add->LeftColumnClass ?>"><?php echo $rating_values->rating_type->FldCaption() ?></label>
		<div class="<?php echo $rating_values_add->RightColumnClass ?>"><div<?php echo $rating_values->rating_type->CellAttributes() ?>>
<span id="el_rating_values_rating_type">
<select data-table="rating_values" data-field="x_rating_type" data-value-separator="<?php echo $rating_values->rating_type->DisplayValueSeparatorAttribute() ?>" id="x_rating_type" name="x_rating_type"<?php echo $rating_values->rating_type->EditAttributes() ?>>
<?php echo $rating_values->rating_type->SelectOptionListHtml("x_rating_type") ?>
</select>
</span>
<?php echo $rating_values->rating_type->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_rating_type">
		<td class="col-sm-2"><span id="elh_rating_values_rating_type"><?php echo $rating_values->rating_type->FldCaption() ?></span></td>
		<td<?php echo $rating_values->rating_type->CellAttributes() ?>>
<span id="el_rating_values_rating_type">
<select data-table="rating_values" data-field="x_rating_type" data-value-separator="<?php echo $rating_values->rating_type->DisplayValueSeparatorAttribute() ?>" id="x_rating_type" name="x_rating_type"<?php echo $rating_values->rating_type->EditAttributes() ?>>
<?php echo $rating_values->rating_type->SelectOptionListHtml("x_rating_type") ?>
</select>
</span>
<?php echo $rating_values->rating_type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($rating_values->rating_value->Visible) { // rating_value ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
	<div id="r_rating_value" class="form-group">
		<label id="elh_rating_values_rating_value" for="x_rating_value" class="<?php echo $rating_values_add->LeftColumnClass ?>"><?php echo $rating_values->rating_value->FldCaption() ?></label>
		<div class="<?php echo $rating_values_add->RightColumnClass ?>"><div<?php echo $rating_values->rating_value->CellAttributes() ?>>
<span id="el_rating_values_rating_value">
<input type="text" data-table="rating_values" data-field="x_rating_value" name="x_rating_value" id="x_rating_value" size="30" placeholder="<?php echo ew_HtmlEncode($rating_values->rating_value->getPlaceHolder()) ?>" value="<?php echo $rating_values->rating_value->EditValue ?>"<?php echo $rating_values->rating_value->EditAttributes() ?>>
</span>
<?php echo $rating_values->rating_value->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_rating_value">
		<td class="col-sm-2"><span id="elh_rating_values_rating_value"><?php echo $rating_values->rating_value->FldCaption() ?></span></td>
		<td<?php echo $rating_values->rating_value->CellAttributes() ?>>
<span id="el_rating_values_rating_value">
<input type="text" data-table="rating_values" data-field="x_rating_value" name="x_rating_value" id="x_rating_value" size="30" placeholder="<?php echo ew_HtmlEncode($rating_values->rating_value->getPlaceHolder()) ?>" value="<?php echo $rating_values->rating_value->EditValue ?>"<?php echo $rating_values->rating_value->EditAttributes() ?>>
</span>
<?php echo $rating_values->rating_value->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($rating_values_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$rating_values_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $rating_values_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $rating_values_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$rating_values_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
frating_valuesadd.Init();
</script>
<?php
$rating_values_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$rating_values_add->Page_Terminate();
?>
