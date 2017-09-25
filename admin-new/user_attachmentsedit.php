<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "user_attachmentsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$user_attachments_edit = NULL; // Initialize page object first

class cuser_attachments_edit extends cuser_attachments {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'user_attachments';

	// Page object name
	var $PageObjName = 'user_attachments_edit';

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

		// Table object (user_attachments)
		if (!isset($GLOBALS["user_attachments"]) || get_class($GLOBALS["user_attachments"]) == "cuser_attachments") {
			$GLOBALS["user_attachments"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["user_attachments"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'user_attachments', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("user_attachmentslist.php"));
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
		$this->_userid->SetVisibility();
		$this->description->SetVisibility();
		$this->hours->SetVisibility();
		$this->file->SetVisibility();

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
		global $EW_EXPORT, $user_attachments;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($user_attachments);
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
					if ($pageName == "user_attachmentsview.php")
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
		if (@$_GET["id"] <> "") {
			$this->id->setQueryStringValue($_GET["id"]);
		}

		// Set up master detail parameters
		$this->SetupMasterParms();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "") {
			$this->Page_Terminate("user_attachmentslist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("user_attachmentslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "user_attachmentslist.php")
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
		$this->file->Upload->Index = $objForm->Index;
		$this->file->Upload->UploadFile();
		$this->file->CurrentValue = $this->file->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->_userid->FldIsDetailKey) {
			$this->_userid->setFormValue($objForm->GetValue("x__userid"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->hours->FldIsDetailKey) {
			$this->hours->setFormValue($objForm->GetValue("x_hours"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->_userid->CurrentValue = $this->_userid->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->hours->CurrentValue = $this->hours->FormValue;
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
		$this->_userid->setDbValue($row['userid']);
		$this->description->setDbValue($row['description']);
		$this->hours->setDbValue($row['hours']);
		$this->file->Upload->DbValue = $row['file'];
		$this->file->CurrentValue = $this->file->Upload->DbValue;
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['userid'] = NULL;
		$row['description'] = NULL;
		$row['hours'] = NULL;
		$row['file'] = NULL;
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
		$this->_userid->DbValue = $row['userid'];
		$this->description->DbValue = $row['description'];
		$this->hours->DbValue = $row['hours'];
		$this->file->Upload->DbValue = $row['file'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// userid
		// description
		// hours
		// file

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// userid
		$this->_userid->ViewValue = $this->_userid->CurrentValue;
		$this->_userid->ViewCustomAttributes = "";

		// description
		$this->description->ViewValue = $this->description->CurrentValue;
		$this->description->ViewCustomAttributes = "";

		// hours
		$this->hours->ViewValue = $this->hours->CurrentValue;
		$this->hours->ViewCustomAttributes = "";

		// file
		$this->file->UploadPath = "../images";
		if (!ew_Empty($this->file->Upload->DbValue)) {
			$this->file->ViewValue = $this->file->Upload->DbValue;
		} else {
			$this->file->ViewValue = "";
		}
		$this->file->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// userid
			$this->_userid->LinkCustomAttributes = "";
			$this->_userid->HrefValue = "";
			$this->_userid->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// hours
			$this->hours->LinkCustomAttributes = "";
			$this->hours->HrefValue = "";
			$this->hours->TooltipValue = "";

			// file
			$this->file->LinkCustomAttributes = "";
			$this->file->HrefValue = "";
			$this->file->HrefValue2 = $this->file->UploadPath . $this->file->Upload->DbValue;
			$this->file->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// userid
			$this->_userid->EditAttrs["class"] = "form-control";
			$this->_userid->EditCustomAttributes = "";
			if ($this->_userid->getSessionValue() <> "") {
				$this->_userid->CurrentValue = $this->_userid->getSessionValue();
			$this->_userid->ViewValue = $this->_userid->CurrentValue;
			$this->_userid->ViewCustomAttributes = "";
			} else {
			$this->_userid->EditValue = ew_HtmlEncode($this->_userid->CurrentValue);
			$this->_userid->PlaceHolder = ew_RemoveHtml($this->_userid->FldCaption());
			}

			// description
			$this->description->EditAttrs["class"] = "form-control";
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);
			$this->description->PlaceHolder = ew_RemoveHtml($this->description->FldCaption());

			// hours
			$this->hours->EditAttrs["class"] = "form-control";
			$this->hours->EditCustomAttributes = "";
			$this->hours->EditValue = ew_HtmlEncode($this->hours->CurrentValue);
			$this->hours->PlaceHolder = ew_RemoveHtml($this->hours->FldCaption());

			// file
			$this->file->EditAttrs["class"] = "form-control";
			$this->file->EditCustomAttributes = "";
			$this->file->UploadPath = "../images";
			if (!ew_Empty($this->file->Upload->DbValue)) {
				$this->file->EditValue = $this->file->Upload->DbValue;
			} else {
				$this->file->EditValue = "";
			}
			if (!ew_Empty($this->file->CurrentValue))
				$this->file->Upload->FileName = $this->file->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->file);

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// userid
			$this->_userid->LinkCustomAttributes = "";
			$this->_userid->HrefValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";

			// hours
			$this->hours->LinkCustomAttributes = "";
			$this->hours->HrefValue = "";

			// file
			$this->file->LinkCustomAttributes = "";
			$this->file->HrefValue = "";
			$this->file->HrefValue2 = $this->file->UploadPath . $this->file->Upload->DbValue;
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
		if (!ew_CheckInteger($this->_userid->FormValue)) {
			ew_AddMessage($gsFormError, $this->_userid->FldErrMsg());
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$this->file->OldUploadPath = "../images";
			$this->file->UploadPath = $this->file->OldUploadPath;
			$rsnew = array();

			// userid
			$this->_userid->SetDbValueDef($rsnew, $this->_userid->CurrentValue, NULL, $this->_userid->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

			// hours
			$this->hours->SetDbValueDef($rsnew, $this->hours->CurrentValue, NULL, $this->hours->ReadOnly);

			// file
			if ($this->file->Visible && !$this->file->ReadOnly && !$this->file->Upload->KeepFile) {
				$this->file->Upload->DbValue = $rsold['file']; // Get original value
				if ($this->file->Upload->FileName == "") {
					$rsnew['file'] = NULL;
				} else {
					$rsnew['file'] = $this->file->Upload->FileName;
				}
			}

			// Check referential integrity for master table 'users'
			$bValidMasterRecord = TRUE;
			$sMasterFilter = $this->SqlMasterFilter_users();
			$KeyValue = isset($rsnew['userid']) ? $rsnew['userid'] : $rsold['userid'];
			if (strval($KeyValue) <> "") {
				$sMasterFilter = str_replace("@user_id@", ew_AdjustSql($KeyValue), $sMasterFilter);
			} else {
				$bValidMasterRecord = FALSE;
			}
			if ($bValidMasterRecord) {
				if (!isset($GLOBALS["users"])) $GLOBALS["users"] = new cusers();
				$rsmaster = $GLOBALS["users"]->LoadRs($sMasterFilter);
				$bValidMasterRecord = ($rsmaster && !$rsmaster->EOF);
				$rsmaster->Close();
			}
			if (!$bValidMasterRecord) {
				$sRelatedRecordMsg = str_replace("%t", "users", $Language->Phrase("RelatedRecordRequired"));
				$this->setFailureMessage($sRelatedRecordMsg);
				$rs->Close();
				return FALSE;
			}
			if ($this->file->Visible && !$this->file->Upload->KeepFile) {
				$this->file->UploadPath = "../images";
				if (!ew_Empty($this->file->Upload->Value)) {
					if ($this->file->Upload->FileName == $this->file->Upload->DbValue) { // Overwrite if same file name
						$this->file->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['file'] = ew_UploadFileNameEx($this->file->PhysicalUploadPath(), $rsnew['file']); // Get new file name
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
					if ($this->file->Visible && !$this->file->Upload->KeepFile) {
						if (!ew_Empty($this->file->Upload->Value)) {
							if (!$this->file->Upload->SaveToFile($rsnew['file'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
						if ($this->file->Upload->DbValue <> "")
							@unlink($this->file->OldPhysicalUploadPath() . $this->file->Upload->DbValue);
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

		// file
		ew_CleanUploadTempPath($this->file, $this->file->Upload->Index);
		return $EditRow;
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
			if ($sMasterTblVar == "users") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_user_id"] <> "") {
					$GLOBALS["users"]->user_id->setQueryStringValue($_GET["fk_user_id"]);
					$this->_userid->setQueryStringValue($GLOBALS["users"]->user_id->QueryStringValue);
					$this->_userid->setSessionValue($this->_userid->QueryStringValue);
					if (!is_numeric($GLOBALS["users"]->user_id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "users") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_user_id"] <> "") {
					$GLOBALS["users"]->user_id->setFormValue($_POST["fk_user_id"]);
					$this->_userid->setFormValue($GLOBALS["users"]->user_id->FormValue);
					$this->_userid->setSessionValue($this->_userid->FormValue);
					if (!is_numeric($GLOBALS["users"]->user_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "users") {
				if ($this->_userid->CurrentValue == "") $this->_userid->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("user_attachmentslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($user_attachments_edit)) $user_attachments_edit = new cuser_attachments_edit();

// Page init
$user_attachments_edit->Page_Init();

// Page main
$user_attachments_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$user_attachments_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fuser_attachmentsedit = new ew_Form("fuser_attachmentsedit", "edit");

// Validate form
fuser_attachmentsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "__userid");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($user_attachments->_userid->FldErrMsg()) ?>");

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
fuser_attachmentsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuser_attachmentsedit.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $user_attachments_edit->ShowPageHeader(); ?>
<?php
$user_attachments_edit->ShowMessage();
?>
<form name="fuser_attachmentsedit" id="fuser_attachmentsedit" class="<?php echo $user_attachments_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($user_attachments_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $user_attachments_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="user_attachments">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<input type="hidden" name="modal" value="<?php echo intval($user_attachments_edit->IsModal) ?>">
<?php if ($user_attachments->getCurrentMasterTable() == "users") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="users">
<input type="hidden" name="fk_user_id" value="<?php echo $user_attachments->_userid->getSessionValue() ?>">
<?php } ?>
<?php if (!$user_attachments_edit->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
<div class="ewEditDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_user_attachmentsedit" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($user_attachments->id->Visible) { // id ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
	<div id="r_id" class="form-group">
		<label id="elh_user_attachments_id" class="<?php echo $user_attachments_edit->LeftColumnClass ?>"><?php echo $user_attachments->id->FldCaption() ?></label>
		<div class="<?php echo $user_attachments_edit->RightColumnClass ?>"><div<?php echo $user_attachments->id->CellAttributes() ?>>
<span id="el_user_attachments_id">
<span<?php echo $user_attachments->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($user_attachments->id->CurrentValue) ?>">
<?php echo $user_attachments->id->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_id">
		<td class="col-sm-2"><span id="elh_user_attachments_id"><?php echo $user_attachments->id->FldCaption() ?></span></td>
		<td<?php echo $user_attachments->id->CellAttributes() ?>>
<span id="el_user_attachments_id">
<span<?php echo $user_attachments->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="user_attachments" data-field="x_id" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($user_attachments->id->CurrentValue) ?>">
<?php echo $user_attachments->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($user_attachments->_userid->Visible) { // userid ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
	<div id="r__userid" class="form-group">
		<label id="elh_user_attachments__userid" for="x__userid" class="<?php echo $user_attachments_edit->LeftColumnClass ?>"><?php echo $user_attachments->_userid->FldCaption() ?></label>
		<div class="<?php echo $user_attachments_edit->RightColumnClass ?>"><div<?php echo $user_attachments->_userid->CellAttributes() ?>>
<?php if ($user_attachments->_userid->getSessionValue() <> "") { ?>
<span id="el_user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->_userid->ViewValue ?></p></span>
</span>
<input type="hidden" id="x__userid" name="x__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->CurrentValue) ?>">
<?php } else { ?>
<span id="el_user_attachments__userid">
<input type="text" data-table="user_attachments" data-field="x__userid" name="x__userid" id="x__userid" size="30" placeholder="<?php echo ew_HtmlEncode($user_attachments->_userid->getPlaceHolder()) ?>" value="<?php echo $user_attachments->_userid->EditValue ?>"<?php echo $user_attachments->_userid->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $user_attachments->_userid->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r__userid">
		<td class="col-sm-2"><span id="elh_user_attachments__userid"><?php echo $user_attachments->_userid->FldCaption() ?></span></td>
		<td<?php echo $user_attachments->_userid->CellAttributes() ?>>
<?php if ($user_attachments->_userid->getSessionValue() <> "") { ?>
<span id="el_user_attachments__userid">
<span<?php echo $user_attachments->_userid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $user_attachments->_userid->ViewValue ?></p></span>
</span>
<input type="hidden" id="x__userid" name="x__userid" value="<?php echo ew_HtmlEncode($user_attachments->_userid->CurrentValue) ?>">
<?php } else { ?>
<span id="el_user_attachments__userid">
<input type="text" data-table="user_attachments" data-field="x__userid" name="x__userid" id="x__userid" size="30" placeholder="<?php echo ew_HtmlEncode($user_attachments->_userid->getPlaceHolder()) ?>" value="<?php echo $user_attachments->_userid->EditValue ?>"<?php echo $user_attachments->_userid->EditAttributes() ?>>
</span>
<?php } ?>
<?php echo $user_attachments->_userid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($user_attachments->description->Visible) { // description ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
	<div id="r_description" class="form-group">
		<label id="elh_user_attachments_description" for="x_description" class="<?php echo $user_attachments_edit->LeftColumnClass ?>"><?php echo $user_attachments->description->FldCaption() ?></label>
		<div class="<?php echo $user_attachments_edit->RightColumnClass ?>"><div<?php echo $user_attachments->description->CellAttributes() ?>>
<span id="el_user_attachments_description">
<textarea data-table="user_attachments" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user_attachments->description->getPlaceHolder()) ?>"<?php echo $user_attachments->description->EditAttributes() ?>><?php echo $user_attachments->description->EditValue ?></textarea>
</span>
<?php echo $user_attachments->description->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_description">
		<td class="col-sm-2"><span id="elh_user_attachments_description"><?php echo $user_attachments->description->FldCaption() ?></span></td>
		<td<?php echo $user_attachments->description->CellAttributes() ?>>
<span id="el_user_attachments_description">
<textarea data-table="user_attachments" data-field="x_description" name="x_description" id="x_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($user_attachments->description->getPlaceHolder()) ?>"<?php echo $user_attachments->description->EditAttributes() ?>><?php echo $user_attachments->description->EditValue ?></textarea>
</span>
<?php echo $user_attachments->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($user_attachments->hours->Visible) { // hours ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
	<div id="r_hours" class="form-group">
		<label id="elh_user_attachments_hours" for="x_hours" class="<?php echo $user_attachments_edit->LeftColumnClass ?>"><?php echo $user_attachments->hours->FldCaption() ?></label>
		<div class="<?php echo $user_attachments_edit->RightColumnClass ?>"><div<?php echo $user_attachments->hours->CellAttributes() ?>>
<span id="el_user_attachments_hours">
<input type="text" data-table="user_attachments" data-field="x_hours" name="x_hours" id="x_hours" placeholder="<?php echo ew_HtmlEncode($user_attachments->hours->getPlaceHolder()) ?>" value="<?php echo $user_attachments->hours->EditValue ?>"<?php echo $user_attachments->hours->EditAttributes() ?>>
</span>
<?php echo $user_attachments->hours->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_hours">
		<td class="col-sm-2"><span id="elh_user_attachments_hours"><?php echo $user_attachments->hours->FldCaption() ?></span></td>
		<td<?php echo $user_attachments->hours->CellAttributes() ?>>
<span id="el_user_attachments_hours">
<input type="text" data-table="user_attachments" data-field="x_hours" name="x_hours" id="x_hours" placeholder="<?php echo ew_HtmlEncode($user_attachments->hours->getPlaceHolder()) ?>" value="<?php echo $user_attachments->hours->EditValue ?>"<?php echo $user_attachments->hours->EditAttributes() ?>>
</span>
<?php echo $user_attachments->hours->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($user_attachments->file->Visible) { // file ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
	<div id="r_file" class="form-group">
		<label id="elh_user_attachments_file" class="<?php echo $user_attachments_edit->LeftColumnClass ?>"><?php echo $user_attachments->file->FldCaption() ?></label>
		<div class="<?php echo $user_attachments_edit->RightColumnClass ?>"><div<?php echo $user_attachments->file->CellAttributes() ?>>
<span id="el_user_attachments_file">
<div id="fd_x_file">
<span title="<?php echo $user_attachments->file->FldTitle() ? $user_attachments->file->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($user_attachments->file->ReadOnly || $user_attachments->file->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="user_attachments" data-field="x_file" name="x_file" id="x_file"<?php echo $user_attachments->file->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_file" id= "fn_x_file" value="<?php echo $user_attachments->file->Upload->FileName ?>">
<?php if (@$_POST["fa_x_file"] == "0") { ?>
<input type="hidden" name="fa_x_file" id= "fa_x_file" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_file" id= "fa_x_file" value="1">
<?php } ?>
<input type="hidden" name="fs_x_file" id= "fs_x_file" value="65535">
<input type="hidden" name="fx_x_file" id= "fx_x_file" value="<?php echo $user_attachments->file->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file" id= "fm_x_file" value="<?php echo $user_attachments->file->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $user_attachments->file->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_file">
		<td class="col-sm-2"><span id="elh_user_attachments_file"><?php echo $user_attachments->file->FldCaption() ?></span></td>
		<td<?php echo $user_attachments->file->CellAttributes() ?>>
<span id="el_user_attachments_file">
<div id="fd_x_file">
<span title="<?php echo $user_attachments->file->FldTitle() ? $user_attachments->file->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($user_attachments->file->ReadOnly || $user_attachments->file->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="user_attachments" data-field="x_file" name="x_file" id="x_file"<?php echo $user_attachments->file->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_file" id= "fn_x_file" value="<?php echo $user_attachments->file->Upload->FileName ?>">
<?php if (@$_POST["fa_x_file"] == "0") { ?>
<input type="hidden" name="fa_x_file" id= "fa_x_file" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_file" id= "fa_x_file" value="1">
<?php } ?>
<input type="hidden" name="fs_x_file" id= "fs_x_file" value="65535">
<input type="hidden" name="fx_x_file" id= "fx_x_file" value="<?php echo $user_attachments->file->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file" id= "fm_x_file" value="<?php echo $user_attachments->file->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $user_attachments->file->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($user_attachments_edit->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$user_attachments_edit->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $user_attachments_edit->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $user_attachments_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$user_attachments_edit->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fuser_attachmentsedit.Init();
</script>
<?php
$user_attachments_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$user_attachments_edit->Page_Terminate();
?>
