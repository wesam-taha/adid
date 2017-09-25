<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "members_titles_criteriainfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$members_titles_criteria_add = NULL; // Initialize page object first

class cmembers_titles_criteria_add extends cmembers_titles_criteria {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'members_titles_criteria';

	// Page object name
	var $PageObjName = 'members_titles_criteria_add';

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

		// Table object (members_titles_criteria)
		if (!isset($GLOBALS["members_titles_criteria"]) || get_class($GLOBALS["members_titles_criteria"]) == "cmembers_titles_criteria") {
			$GLOBALS["members_titles_criteria"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["members_titles_criteria"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'members_titles_criteria', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("members_titles_criterialist.php"));
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
		$this->level->SetVisibility();
		$this->hours_from->SetVisibility();
		$this->hours_to->SetVisibility();
		$this->title->SetVisibility();
		$this->title_en->SetVisibility();

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
		global $EW_EXPORT, $members_titles_criteria;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($members_titles_criteria);
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
					if ($pageName == "members_titles_criteriaview.php")
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
					$this->Page_Terminate("members_titles_criterialist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "members_titles_criterialist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "members_titles_criteriaview.php")
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
		$this->level->CurrentValue = NULL;
		$this->level->OldValue = $this->level->CurrentValue;
		$this->hours_from->CurrentValue = NULL;
		$this->hours_from->OldValue = $this->hours_from->CurrentValue;
		$this->hours_to->CurrentValue = NULL;
		$this->hours_to->OldValue = $this->hours_to->CurrentValue;
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->title_en->CurrentValue = NULL;
		$this->title_en->OldValue = $this->title_en->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->level->FldIsDetailKey) {
			$this->level->setFormValue($objForm->GetValue("x_level"));
		}
		if (!$this->hours_from->FldIsDetailKey) {
			$this->hours_from->setFormValue($objForm->GetValue("x_hours_from"));
		}
		if (!$this->hours_to->FldIsDetailKey) {
			$this->hours_to->setFormValue($objForm->GetValue("x_hours_to"));
		}
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->level->CurrentValue = $this->level->FormValue;
		$this->hours_from->CurrentValue = $this->hours_from->FormValue;
		$this->hours_to->CurrentValue = $this->hours_to->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->title_en->CurrentValue = $this->title_en->FormValue;
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
		$this->level->setDbValue($row['level']);
		$this->hours_from->setDbValue($row['hours_from']);
		$this->hours_to->setDbValue($row['hours_to']);
		$this->title->setDbValue($row['title']);
		$this->title_en->setDbValue($row['title_en']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['level'] = NULL;
		$row['hours_from'] = NULL;
		$row['hours_to'] = NULL;
		$row['title'] = NULL;
		$row['title_en'] = NULL;
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
		$this->level->DbValue = $row['level'];
		$this->hours_from->DbValue = $row['hours_from'];
		$this->hours_to->DbValue = $row['hours_to'];
		$this->title->DbValue = $row['title'];
		$this->title_en->DbValue = $row['title_en'];
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
		// level
		// hours_from
		// hours_to
		// title
		// title_en

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// level
		$this->level->ViewValue = $this->level->CurrentValue;
		$this->level->ViewCustomAttributes = "";

		// hours_from
		$this->hours_from->ViewValue = $this->hours_from->CurrentValue;
		$this->hours_from->ViewCustomAttributes = "";

		// hours_to
		$this->hours_to->ViewValue = $this->hours_to->CurrentValue;
		$this->hours_to->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// title_en
		$this->title_en->ViewValue = $this->title_en->CurrentValue;
		$this->title_en->ViewCustomAttributes = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";
			$this->level->TooltipValue = "";

			// hours_from
			$this->hours_from->LinkCustomAttributes = "";
			$this->hours_from->HrefValue = "";
			$this->hours_from->TooltipValue = "";

			// hours_to
			$this->hours_to->LinkCustomAttributes = "";
			$this->hours_to->HrefValue = "";
			$this->hours_to->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// level
			$this->level->EditAttrs["class"] = "form-control";
			$this->level->EditCustomAttributes = "";
			$this->level->EditValue = ew_HtmlEncode($this->level->CurrentValue);
			$this->level->PlaceHolder = ew_RemoveHtml($this->level->FldCaption());

			// hours_from
			$this->hours_from->EditAttrs["class"] = "form-control";
			$this->hours_from->EditCustomAttributes = "";
			$this->hours_from->EditValue = ew_HtmlEncode($this->hours_from->CurrentValue);
			$this->hours_from->PlaceHolder = ew_RemoveHtml($this->hours_from->FldCaption());

			// hours_to
			$this->hours_to->EditAttrs["class"] = "form-control";
			$this->hours_to->EditCustomAttributes = "";
			$this->hours_to->EditValue = ew_HtmlEncode($this->hours_to->CurrentValue);
			$this->hours_to->PlaceHolder = ew_RemoveHtml($this->hours_to->FldCaption());

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// title_en
			$this->title_en->EditAttrs["class"] = "form-control";
			$this->title_en->EditCustomAttributes = "";
			$this->title_en->EditValue = ew_HtmlEncode($this->title_en->CurrentValue);
			$this->title_en->PlaceHolder = ew_RemoveHtml($this->title_en->FldCaption());

			// Add refer script
			// level

			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";

			// hours_from
			$this->hours_from->LinkCustomAttributes = "";
			$this->hours_from->HrefValue = "";

			// hours_to
			$this->hours_to->LinkCustomAttributes = "";
			$this->hours_to->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
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
		if (!$this->level->FldIsDetailKey && !is_null($this->level->FormValue) && $this->level->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->level->FldCaption(), $this->level->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->level->FormValue)) {
			ew_AddMessage($gsFormError, $this->level->FldErrMsg());
		}
		if (!$this->hours_from->FldIsDetailKey && !is_null($this->hours_from->FormValue) && $this->hours_from->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hours_from->FldCaption(), $this->hours_from->ReqErrMsg));
		}
		if (!$this->hours_to->FldIsDetailKey && !is_null($this->hours_to->FormValue) && $this->hours_to->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hours_to->FldCaption(), $this->hours_to->ReqErrMsg));
		}
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->title_en->FldIsDetailKey && !is_null($this->title_en->FormValue) && $this->title_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_en->FldCaption(), $this->title_en->ReqErrMsg));
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

		// level
		$this->level->SetDbValueDef($rsnew, $this->level->CurrentValue, 0, FALSE);

		// hours_from
		$this->hours_from->SetDbValueDef($rsnew, $this->hours_from->CurrentValue, "", FALSE);

		// hours_to
		$this->hours_to->SetDbValueDef($rsnew, $this->hours_to->CurrentValue, "", FALSE);

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// title_en
		$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("members_titles_criterialist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($members_titles_criteria_add)) $members_titles_criteria_add = new cmembers_titles_criteria_add();

// Page init
$members_titles_criteria_add->Page_Init();

// Page main
$members_titles_criteria_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$members_titles_criteria_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fmembers_titles_criteriaadd = new ew_Form("fmembers_titles_criteriaadd", "add");

// Validate form
fmembers_titles_criteriaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_level");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->level->FldCaption(), $members_titles_criteria->level->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_level");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($members_titles_criteria->level->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hours_from");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->hours_from->FldCaption(), $members_titles_criteria->hours_from->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hours_to");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->hours_to->FldCaption(), $members_titles_criteria->hours_to->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->title->FldCaption(), $members_titles_criteria->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->title_en->FldCaption(), $members_titles_criteria->title_en->ReqErrMsg)) ?>");

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
fmembers_titles_criteriaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmembers_titles_criteriaadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $members_titles_criteria_add->ShowPageHeader(); ?>
<?php
$members_titles_criteria_add->ShowMessage();
?>
<form name="fmembers_titles_criteriaadd" id="fmembers_titles_criteriaadd" class="<?php echo $members_titles_criteria_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($members_titles_criteria_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $members_titles_criteria_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="members_titles_criteria">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($members_titles_criteria_add->IsModal) ?>">
<?php if (!$members_titles_criteria_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_members_titles_criteriaadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($members_titles_criteria->level->Visible) { // level ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
	<div id="r_level" class="form-group">
		<label id="elh_members_titles_criteria_level" for="x_level" class="<?php echo $members_titles_criteria_add->LeftColumnClass ?>"><?php echo $members_titles_criteria->level->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_titles_criteria_add->RightColumnClass ?>"><div<?php echo $members_titles_criteria->level->CellAttributes() ?>>
<span id="el_members_titles_criteria_level">
<input type="text" data-table="members_titles_criteria" data-field="x_level" name="x_level" id="x_level" size="30" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->level->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->level->EditValue ?>"<?php echo $members_titles_criteria->level->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->level->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_level">
		<td class="col-sm-2"><span id="elh_members_titles_criteria_level"><?php echo $members_titles_criteria->level->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_titles_criteria->level->CellAttributes() ?>>
<span id="el_members_titles_criteria_level">
<input type="text" data-table="members_titles_criteria" data-field="x_level" name="x_level" id="x_level" size="30" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->level->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->level->EditValue ?>"<?php echo $members_titles_criteria->level->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->level->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->hours_from->Visible) { // hours_from ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
	<div id="r_hours_from" class="form-group">
		<label id="elh_members_titles_criteria_hours_from" for="x_hours_from" class="<?php echo $members_titles_criteria_add->LeftColumnClass ?>"><?php echo $members_titles_criteria->hours_from->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_titles_criteria_add->RightColumnClass ?>"><div<?php echo $members_titles_criteria->hours_from->CellAttributes() ?>>
<span id="el_members_titles_criteria_hours_from">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_from" name="x_hours_from" id="x_hours_from" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_from->EditValue ?>"<?php echo $members_titles_criteria->hours_from->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->hours_from->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_hours_from">
		<td class="col-sm-2"><span id="elh_members_titles_criteria_hours_from"><?php echo $members_titles_criteria->hours_from->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_titles_criteria->hours_from->CellAttributes() ?>>
<span id="el_members_titles_criteria_hours_from">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_from" name="x_hours_from" id="x_hours_from" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_from->EditValue ?>"<?php echo $members_titles_criteria->hours_from->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->hours_from->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->hours_to->Visible) { // hours_to ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
	<div id="r_hours_to" class="form-group">
		<label id="elh_members_titles_criteria_hours_to" for="x_hours_to" class="<?php echo $members_titles_criteria_add->LeftColumnClass ?>"><?php echo $members_titles_criteria->hours_to->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_titles_criteria_add->RightColumnClass ?>"><div<?php echo $members_titles_criteria->hours_to->CellAttributes() ?>>
<span id="el_members_titles_criteria_hours_to">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_to" name="x_hours_to" id="x_hours_to" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_to->EditValue ?>"<?php echo $members_titles_criteria->hours_to->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->hours_to->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_hours_to">
		<td class="col-sm-2"><span id="elh_members_titles_criteria_hours_to"><?php echo $members_titles_criteria->hours_to->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_titles_criteria->hours_to->CellAttributes() ?>>
<span id="el_members_titles_criteria_hours_to">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_to" name="x_hours_to" id="x_hours_to" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_to->EditValue ?>"<?php echo $members_titles_criteria->hours_to->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->hours_to->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->title->Visible) { // title ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
	<div id="r_title" class="form-group">
		<label id="elh_members_titles_criteria_title" for="x_title" class="<?php echo $members_titles_criteria_add->LeftColumnClass ?>"><?php echo $members_titles_criteria->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_titles_criteria_add->RightColumnClass ?>"><div<?php echo $members_titles_criteria->title->CellAttributes() ?>>
<span id="el_members_titles_criteria_title">
<input type="text" data-table="members_titles_criteria" data-field="x_title" name="x_title" id="x_title" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title->EditValue ?>"<?php echo $members_titles_criteria->title->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->title->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_title">
		<td class="col-sm-2"><span id="elh_members_titles_criteria_title"><?php echo $members_titles_criteria->title->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_titles_criteria->title->CellAttributes() ?>>
<span id="el_members_titles_criteria_title">
<input type="text" data-table="members_titles_criteria" data-field="x_title" name="x_title" id="x_title" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title->EditValue ?>"<?php echo $members_titles_criteria->title->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->title->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->title_en->Visible) { // title_en ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
	<div id="r_title_en" class="form-group">
		<label id="elh_members_titles_criteria_title_en" for="x_title_en" class="<?php echo $members_titles_criteria_add->LeftColumnClass ?>"><?php echo $members_titles_criteria->title_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_titles_criteria_add->RightColumnClass ?>"><div<?php echo $members_titles_criteria->title_en->CellAttributes() ?>>
<span id="el_members_titles_criteria_title_en">
<input type="text" data-table="members_titles_criteria" data-field="x_title_en" name="x_title_en" id="x_title_en" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title_en->EditValue ?>"<?php echo $members_titles_criteria->title_en->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->title_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_title_en">
		<td class="col-sm-2"><span id="elh_members_titles_criteria_title_en"><?php echo $members_titles_criteria->title_en->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_titles_criteria->title_en->CellAttributes() ?>>
<span id="el_members_titles_criteria_title_en">
<input type="text" data-table="members_titles_criteria" data-field="x_title_en" name="x_title_en" id="x_title_en" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title_en->EditValue ?>"<?php echo $members_titles_criteria->title_en->EditAttributes() ?>>
</span>
<?php echo $members_titles_criteria->title_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$members_titles_criteria_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $members_titles_criteria_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $members_titles_criteria_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$members_titles_criteria_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fmembers_titles_criteriaadd.Init();
</script>
<?php
$members_titles_criteria_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$members_titles_criteria_add->Page_Terminate();
?>
