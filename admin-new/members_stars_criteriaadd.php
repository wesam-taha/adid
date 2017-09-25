<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "members_stars_criteriainfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$members_stars_criteria_add = NULL; // Initialize page object first

class cmembers_stars_criteria_add extends cmembers_stars_criteria {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'members_stars_criteria';

	// Page object name
	var $PageObjName = 'members_stars_criteria_add';

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

		// Table object (members_stars_criteria)
		if (!isset($GLOBALS["members_stars_criteria"]) || get_class($GLOBALS["members_stars_criteria"]) == "cmembers_stars_criteria") {
			$GLOBALS["members_stars_criteria"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["members_stars_criteria"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'members_stars_criteria', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("members_stars_criterialist.php"));
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
		$this->rate_from->SetVisibility();
		$this->rate_to->SetVisibility();
		$this->number_of_stars->SetVisibility();

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
		global $EW_EXPORT, $members_stars_criteria;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($members_stars_criteria);
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
					if ($pageName == "members_stars_criteriaview.php")
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
					$this->Page_Terminate("members_stars_criterialist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "members_stars_criterialist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "members_stars_criteriaview.php")
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
		$this->rate_from->CurrentValue = NULL;
		$this->rate_from->OldValue = $this->rate_from->CurrentValue;
		$this->rate_to->CurrentValue = NULL;
		$this->rate_to->OldValue = $this->rate_to->CurrentValue;
		$this->number_of_stars->CurrentValue = NULL;
		$this->number_of_stars->OldValue = $this->number_of_stars->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->rate_from->FldIsDetailKey) {
			$this->rate_from->setFormValue($objForm->GetValue("x_rate_from"));
		}
		if (!$this->rate_to->FldIsDetailKey) {
			$this->rate_to->setFormValue($objForm->GetValue("x_rate_to"));
		}
		if (!$this->number_of_stars->FldIsDetailKey) {
			$this->number_of_stars->setFormValue($objForm->GetValue("x_number_of_stars"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->rate_from->CurrentValue = $this->rate_from->FormValue;
		$this->rate_to->CurrentValue = $this->rate_to->FormValue;
		$this->number_of_stars->CurrentValue = $this->number_of_stars->FormValue;
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
		$this->rate_from->setDbValue($row['rate_from']);
		$this->rate_to->setDbValue($row['rate_to']);
		$this->number_of_stars->setDbValue($row['number_of_stars']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['rate_from'] = NULL;
		$row['rate_to'] = NULL;
		$row['number_of_stars'] = NULL;
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
		$this->rate_from->DbValue = $row['rate_from'];
		$this->rate_to->DbValue = $row['rate_to'];
		$this->number_of_stars->DbValue = $row['number_of_stars'];
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
		// rate_from
		// rate_to
		// number_of_stars

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// rate_from
		$this->rate_from->ViewValue = $this->rate_from->CurrentValue;
		$this->rate_from->ViewCustomAttributes = "";

		// rate_to
		$this->rate_to->ViewValue = $this->rate_to->CurrentValue;
		$this->rate_to->ViewCustomAttributes = "";

		// number_of_stars
		$this->number_of_stars->ViewValue = $this->number_of_stars->CurrentValue;
		$this->number_of_stars->ViewCustomAttributes = "";

			// rate_from
			$this->rate_from->LinkCustomAttributes = "";
			$this->rate_from->HrefValue = "";
			$this->rate_from->TooltipValue = "";

			// rate_to
			$this->rate_to->LinkCustomAttributes = "";
			$this->rate_to->HrefValue = "";
			$this->rate_to->TooltipValue = "";

			// number_of_stars
			$this->number_of_stars->LinkCustomAttributes = "";
			$this->number_of_stars->HrefValue = "";
			$this->number_of_stars->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// rate_from
			$this->rate_from->EditAttrs["class"] = "form-control";
			$this->rate_from->EditCustomAttributes = "";
			$this->rate_from->EditValue = ew_HtmlEncode($this->rate_from->CurrentValue);
			$this->rate_from->PlaceHolder = ew_RemoveHtml($this->rate_from->FldCaption());

			// rate_to
			$this->rate_to->EditAttrs["class"] = "form-control";
			$this->rate_to->EditCustomAttributes = "";
			$this->rate_to->EditValue = ew_HtmlEncode($this->rate_to->CurrentValue);
			$this->rate_to->PlaceHolder = ew_RemoveHtml($this->rate_to->FldCaption());

			// number_of_stars
			$this->number_of_stars->EditAttrs["class"] = "form-control";
			$this->number_of_stars->EditCustomAttributes = "";
			$this->number_of_stars->EditValue = ew_HtmlEncode($this->number_of_stars->CurrentValue);
			$this->number_of_stars->PlaceHolder = ew_RemoveHtml($this->number_of_stars->FldCaption());

			// Add refer script
			// rate_from

			$this->rate_from->LinkCustomAttributes = "";
			$this->rate_from->HrefValue = "";

			// rate_to
			$this->rate_to->LinkCustomAttributes = "";
			$this->rate_to->HrefValue = "";

			// number_of_stars
			$this->number_of_stars->LinkCustomAttributes = "";
			$this->number_of_stars->HrefValue = "";
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
		if (!$this->rate_from->FldIsDetailKey && !is_null($this->rate_from->FormValue) && $this->rate_from->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rate_from->FldCaption(), $this->rate_from->ReqErrMsg));
		}
		if (!$this->rate_to->FldIsDetailKey && !is_null($this->rate_to->FormValue) && $this->rate_to->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rate_to->FldCaption(), $this->rate_to->ReqErrMsg));
		}
		if (!$this->number_of_stars->FldIsDetailKey && !is_null($this->number_of_stars->FormValue) && $this->number_of_stars->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->number_of_stars->FldCaption(), $this->number_of_stars->ReqErrMsg));
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

		// rate_from
		$this->rate_from->SetDbValueDef($rsnew, $this->rate_from->CurrentValue, "", FALSE);

		// rate_to
		$this->rate_to->SetDbValueDef($rsnew, $this->rate_to->CurrentValue, "", FALSE);

		// number_of_stars
		$this->number_of_stars->SetDbValueDef($rsnew, $this->number_of_stars->CurrentValue, "", FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("members_stars_criterialist.php"), "", $this->TableVar, TRUE);
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
if (!isset($members_stars_criteria_add)) $members_stars_criteria_add = new cmembers_stars_criteria_add();

// Page init
$members_stars_criteria_add->Page_Init();

// Page main
$members_stars_criteria_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$members_stars_criteria_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fmembers_stars_criteriaadd = new ew_Form("fmembers_stars_criteriaadd", "add");

// Validate form
fmembers_stars_criteriaadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_rate_from");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_stars_criteria->rate_from->FldCaption(), $members_stars_criteria->rate_from->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rate_to");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_stars_criteria->rate_to->FldCaption(), $members_stars_criteria->rate_to->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_number_of_stars");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_stars_criteria->number_of_stars->FldCaption(), $members_stars_criteria->number_of_stars->ReqErrMsg)) ?>");

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
fmembers_stars_criteriaadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmembers_stars_criteriaadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $members_stars_criteria_add->ShowPageHeader(); ?>
<?php
$members_stars_criteria_add->ShowMessage();
?>
<form name="fmembers_stars_criteriaadd" id="fmembers_stars_criteriaadd" class="<?php echo $members_stars_criteria_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($members_stars_criteria_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $members_stars_criteria_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="members_stars_criteria">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($members_stars_criteria_add->IsModal) ?>">
<?php if (!$members_stars_criteria_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($members_stars_criteria_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_members_stars_criteriaadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($members_stars_criteria->rate_from->Visible) { // rate_from ?>
<?php if ($members_stars_criteria_add->IsMobileOrModal) { ?>
	<div id="r_rate_from" class="form-group">
		<label id="elh_members_stars_criteria_rate_from" for="x_rate_from" class="<?php echo $members_stars_criteria_add->LeftColumnClass ?>"><?php echo $members_stars_criteria->rate_from->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_stars_criteria_add->RightColumnClass ?>"><div<?php echo $members_stars_criteria->rate_from->CellAttributes() ?>>
<span id="el_members_stars_criteria_rate_from">
<input type="text" data-table="members_stars_criteria" data-field="x_rate_from" name="x_rate_from" id="x_rate_from" placeholder="<?php echo ew_HtmlEncode($members_stars_criteria->rate_from->getPlaceHolder()) ?>" value="<?php echo $members_stars_criteria->rate_from->EditValue ?>"<?php echo $members_stars_criteria->rate_from->EditAttributes() ?>>
</span>
<?php echo $members_stars_criteria->rate_from->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_rate_from">
		<td class="col-sm-2"><span id="elh_members_stars_criteria_rate_from"><?php echo $members_stars_criteria->rate_from->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_stars_criteria->rate_from->CellAttributes() ?>>
<span id="el_members_stars_criteria_rate_from">
<input type="text" data-table="members_stars_criteria" data-field="x_rate_from" name="x_rate_from" id="x_rate_from" placeholder="<?php echo ew_HtmlEncode($members_stars_criteria->rate_from->getPlaceHolder()) ?>" value="<?php echo $members_stars_criteria->rate_from->EditValue ?>"<?php echo $members_stars_criteria->rate_from->EditAttributes() ?>>
</span>
<?php echo $members_stars_criteria->rate_from->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_stars_criteria->rate_to->Visible) { // rate_to ?>
<?php if ($members_stars_criteria_add->IsMobileOrModal) { ?>
	<div id="r_rate_to" class="form-group">
		<label id="elh_members_stars_criteria_rate_to" for="x_rate_to" class="<?php echo $members_stars_criteria_add->LeftColumnClass ?>"><?php echo $members_stars_criteria->rate_to->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_stars_criteria_add->RightColumnClass ?>"><div<?php echo $members_stars_criteria->rate_to->CellAttributes() ?>>
<span id="el_members_stars_criteria_rate_to">
<input type="text" data-table="members_stars_criteria" data-field="x_rate_to" name="x_rate_to" id="x_rate_to" placeholder="<?php echo ew_HtmlEncode($members_stars_criteria->rate_to->getPlaceHolder()) ?>" value="<?php echo $members_stars_criteria->rate_to->EditValue ?>"<?php echo $members_stars_criteria->rate_to->EditAttributes() ?>>
</span>
<?php echo $members_stars_criteria->rate_to->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_rate_to">
		<td class="col-sm-2"><span id="elh_members_stars_criteria_rate_to"><?php echo $members_stars_criteria->rate_to->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_stars_criteria->rate_to->CellAttributes() ?>>
<span id="el_members_stars_criteria_rate_to">
<input type="text" data-table="members_stars_criteria" data-field="x_rate_to" name="x_rate_to" id="x_rate_to" placeholder="<?php echo ew_HtmlEncode($members_stars_criteria->rate_to->getPlaceHolder()) ?>" value="<?php echo $members_stars_criteria->rate_to->EditValue ?>"<?php echo $members_stars_criteria->rate_to->EditAttributes() ?>>
</span>
<?php echo $members_stars_criteria->rate_to->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_stars_criteria->number_of_stars->Visible) { // number_of_stars ?>
<?php if ($members_stars_criteria_add->IsMobileOrModal) { ?>
	<div id="r_number_of_stars" class="form-group">
		<label id="elh_members_stars_criteria_number_of_stars" for="x_number_of_stars" class="<?php echo $members_stars_criteria_add->LeftColumnClass ?>"><?php echo $members_stars_criteria->number_of_stars->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $members_stars_criteria_add->RightColumnClass ?>"><div<?php echo $members_stars_criteria->number_of_stars->CellAttributes() ?>>
<span id="el_members_stars_criteria_number_of_stars">
<input type="text" data-table="members_stars_criteria" data-field="x_number_of_stars" name="x_number_of_stars" id="x_number_of_stars" placeholder="<?php echo ew_HtmlEncode($members_stars_criteria->number_of_stars->getPlaceHolder()) ?>" value="<?php echo $members_stars_criteria->number_of_stars->EditValue ?>"<?php echo $members_stars_criteria->number_of_stars->EditAttributes() ?>>
</span>
<?php echo $members_stars_criteria->number_of_stars->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_number_of_stars">
		<td class="col-sm-2"><span id="elh_members_stars_criteria_number_of_stars"><?php echo $members_stars_criteria->number_of_stars->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $members_stars_criteria->number_of_stars->CellAttributes() ?>>
<span id="el_members_stars_criteria_number_of_stars">
<input type="text" data-table="members_stars_criteria" data-field="x_number_of_stars" name="x_number_of_stars" id="x_number_of_stars" placeholder="<?php echo ew_HtmlEncode($members_stars_criteria->number_of_stars->getPlaceHolder()) ?>" value="<?php echo $members_stars_criteria->number_of_stars->EditValue ?>"<?php echo $members_stars_criteria->number_of_stars->EditAttributes() ?>>
</span>
<?php echo $members_stars_criteria->number_of_stars->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($members_stars_criteria_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$members_stars_criteria_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $members_stars_criteria_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $members_stars_criteria_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$members_stars_criteria_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fmembers_stars_criteriaadd.Init();
</script>
<?php
$members_stars_criteria_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$members_stars_criteria_add->Page_Terminate();
?>
