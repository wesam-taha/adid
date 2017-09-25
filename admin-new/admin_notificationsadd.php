<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "admin_notificationsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$admin_notifications_add = NULL; // Initialize page object first

class cadmin_notifications_add extends cadmin_notifications {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'admin_notifications';

	// Page object name
	var $PageObjName = 'admin_notifications_add';

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

		// Table object (admin_notifications)
		if (!isset($GLOBALS["admin_notifications"]) || get_class($GLOBALS["admin_notifications"]) == "cadmin_notifications") {
			$GLOBALS["admin_notifications"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["admin_notifications"];
		}

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'admin_notifications', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("admin_notificationslist.php"));
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
		$this->user->SetVisibility();
		$this->institution->SetVisibility();
		$this->date->SetVisibility();
		$this->title_ar->SetVisibility();
		$this->message_ar->SetVisibility();
		$this->title_en->SetVisibility();
		$this->message_en->SetVisibility();

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
		global $EW_EXPORT, $admin_notifications;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($admin_notifications);
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
					if ($pageName == "admin_notificationsview.php")
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
					$this->Page_Terminate("admin_notificationslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "admin_notificationslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "admin_notificationsview.php")
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
		$this->user->CurrentValue = NULL;
		$this->user->OldValue = $this->user->CurrentValue;
		$this->institution->CurrentValue = NULL;
		$this->institution->OldValue = $this->institution->CurrentValue;
		$this->date->CurrentValue = NULL;
		$this->date->OldValue = $this->date->CurrentValue;
		$this->title_ar->CurrentValue = NULL;
		$this->title_ar->OldValue = $this->title_ar->CurrentValue;
		$this->message_ar->CurrentValue = NULL;
		$this->message_ar->OldValue = $this->message_ar->CurrentValue;
		$this->title_en->CurrentValue = NULL;
		$this->title_en->OldValue = $this->title_en->CurrentValue;
		$this->message_en->CurrentValue = NULL;
		$this->message_en->OldValue = $this->message_en->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
		if (!$this->institution->FldIsDetailKey) {
			$this->institution->setFormValue($objForm->GetValue("x_institution"));
		}
		if (!$this->date->FldIsDetailKey) {
			$this->date->setFormValue($objForm->GetValue("x_date"));
			$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		}
		if (!$this->title_ar->FldIsDetailKey) {
			$this->title_ar->setFormValue($objForm->GetValue("x_title_ar"));
		}
		if (!$this->message_ar->FldIsDetailKey) {
			$this->message_ar->setFormValue($objForm->GetValue("x_message_ar"));
		}
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
		if (!$this->message_en->FldIsDetailKey) {
			$this->message_en->setFormValue($objForm->GetValue("x_message_en"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->user->CurrentValue = $this->user->FormValue;
		$this->institution->CurrentValue = $this->institution->FormValue;
		$this->date->CurrentValue = $this->date->FormValue;
		$this->date->CurrentValue = ew_UnFormatDateTime($this->date->CurrentValue, 0);
		$this->title_ar->CurrentValue = $this->title_ar->FormValue;
		$this->message_ar->CurrentValue = $this->message_ar->FormValue;
		$this->title_en->CurrentValue = $this->title_en->FormValue;
		$this->message_en->CurrentValue = $this->message_en->FormValue;
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
		$this->user->setDbValue($row['user']);
		$this->institution->setDbValue($row['institution']);
		$this->date->setDbValue($row['date']);
		$this->title_ar->setDbValue($row['title_ar']);
		$this->message_ar->setDbValue($row['message_ar']);
		$this->title_en->setDbValue($row['title_en']);
		$this->message_en->setDbValue($row['message_en']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['user'] = NULL;
		$row['institution'] = NULL;
		$row['date'] = NULL;
		$row['title_ar'] = NULL;
		$row['message_ar'] = NULL;
		$row['title_en'] = NULL;
		$row['message_en'] = NULL;
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
		$this->user->DbValue = $row['user'];
		$this->institution->DbValue = $row['institution'];
		$this->date->DbValue = $row['date'];
		$this->title_ar->DbValue = $row['title_ar'];
		$this->message_ar->DbValue = $row['message_ar'];
		$this->title_en->DbValue = $row['title_en'];
		$this->message_en->DbValue = $row['message_en'];
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
		// user
		// institution
		// date
		// title_ar
		// message_ar
		// title_en
		// message_en

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// user
		$this->user->ViewValue = $this->user->CurrentValue;
		$this->user->ViewCustomAttributes = "";

		// institution
		if (strval($this->institution->CurrentValue) <> "") {
			$arwrk = explode(",", $this->institution->CurrentValue);
			$sFilterWrk = "";
			foreach ($arwrk as $wrk) {
				if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
				$sFilterWrk .= "`institution_id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
			}
		$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
		$sWhereWrk = "";
		$this->institution->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->institution, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institution->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = $rswrk->fields('DispFld');
					$this->institution->ViewValue .= $this->institution->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->institution->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->Close();
			} else {
				$this->institution->ViewValue = $this->institution->CurrentValue;
			}
		} else {
			$this->institution->ViewValue = NULL;
		}
		$this->institution->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 0);
		$this->date->ViewCustomAttributes = "";

		// title_ar
		$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
		$this->title_ar->ViewCustomAttributes = "";

		// message_ar
		$this->message_ar->ViewValue = $this->message_ar->CurrentValue;
		$this->message_ar->ViewCustomAttributes = "";

		// title_en
		$this->title_en->ViewValue = $this->title_en->CurrentValue;
		$this->title_en->ViewCustomAttributes = "";

		// message_en
		$this->message_en->ViewValue = $this->message_en->CurrentValue;
		$this->message_en->ViewCustomAttributes = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// institution
			$this->institution->LinkCustomAttributes = "";
			$this->institution->HrefValue = "";
			$this->institution->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";
			$this->title_ar->TooltipValue = "";

			// message_ar
			$this->message_ar->LinkCustomAttributes = "";
			$this->message_ar->HrefValue = "";
			$this->message_ar->TooltipValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";

			// message_en
			$this->message_en->LinkCustomAttributes = "";
			$this->message_en->HrefValue = "";
			$this->message_en->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user
			$this->user->EditAttrs["class"] = "form-control";
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->CurrentValue);
			$this->user->PlaceHolder = ew_RemoveHtml($this->user->FldCaption());

			// institution
			$this->institution->EditCustomAttributes = "";
			if (trim(strval($this->institution->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$arwrk = explode(",", $this->institution->CurrentValue);
				$sFilterWrk = "";
				foreach ($arwrk as $wrk) {
					if ($sFilterWrk <> "") $sFilterWrk .= " OR ";
					$sFilterWrk .= "`institution_id`" . ew_SearchString("=", trim($wrk), EW_DATATYPE_NUMBER, "");
				}
			}
			$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `institutions`";
			$sWhereWrk = "";
			$this->institution->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->institution, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->institution->ViewValue = "";
				$ari = 0;
				while (!$rswrk->EOF) {
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->institution->ViewValue .= $this->institution->DisplayValue($arwrk);
					$rswrk->MoveNext();
					if (!$rswrk->EOF) $this->institution->ViewValue .= ew_ViewOptionSeparator($ari); // Separate Options
					$ari++;
				}
				$rswrk->MoveFirst();
			} else {
				$this->institution->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->institution->EditValue = $arwrk;

			// date
			$this->date->EditAttrs["class"] = "form-control";
			$this->date->EditCustomAttributes = "";
			$this->date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date->CurrentValue, 8));
			$this->date->PlaceHolder = ew_RemoveHtml($this->date->FldCaption());

			// title_ar
			$this->title_ar->EditAttrs["class"] = "form-control";
			$this->title_ar->EditCustomAttributes = "";
			$this->title_ar->EditValue = ew_HtmlEncode($this->title_ar->CurrentValue);
			$this->title_ar->PlaceHolder = ew_RemoveHtml($this->title_ar->FldCaption());

			// message_ar
			$this->message_ar->EditAttrs["class"] = "form-control";
			$this->message_ar->EditCustomAttributes = "";
			$this->message_ar->EditValue = ew_HtmlEncode($this->message_ar->CurrentValue);
			$this->message_ar->PlaceHolder = ew_RemoveHtml($this->message_ar->FldCaption());

			// title_en
			$this->title_en->EditAttrs["class"] = "form-control";
			$this->title_en->EditCustomAttributes = "";
			$this->title_en->EditValue = ew_HtmlEncode($this->title_en->CurrentValue);
			$this->title_en->PlaceHolder = ew_RemoveHtml($this->title_en->FldCaption());

			// message_en
			$this->message_en->EditAttrs["class"] = "form-control";
			$this->message_en->EditCustomAttributes = "";
			$this->message_en->EditValue = ew_HtmlEncode($this->message_en->CurrentValue);
			$this->message_en->PlaceHolder = ew_RemoveHtml($this->message_en->FldCaption());

			// Add refer script
			// user

			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";

			// institution
			$this->institution->LinkCustomAttributes = "";
			$this->institution->HrefValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";

			// message_ar
			$this->message_ar->LinkCustomAttributes = "";
			$this->message_ar->HrefValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";

			// message_en
			$this->message_en->LinkCustomAttributes = "";
			$this->message_en->HrefValue = "";
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
		if (!ew_CheckDateDef($this->date->FormValue)) {
			ew_AddMessage($gsFormError, $this->date->FldErrMsg());
		}
		if (!$this->message_ar->FldIsDetailKey && !is_null($this->message_ar->FormValue) && $this->message_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->message_ar->FldCaption(), $this->message_ar->ReqErrMsg));
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

		// user
		$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, NULL, FALSE);

		// institution
		$this->institution->SetDbValueDef($rsnew, $this->institution->CurrentValue, NULL, FALSE);

		// date
		$this->date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date->CurrentValue, 0), NULL, FALSE);

		// title_ar
		$this->title_ar->SetDbValueDef($rsnew, $this->title_ar->CurrentValue, NULL, FALSE);

		// message_ar
		$this->message_ar->SetDbValueDef($rsnew, $this->message_ar->CurrentValue, NULL, FALSE);

		// title_en
		$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, NULL, FALSE);

		// message_en
		$this->message_en->SetDbValueDef($rsnew, $this->message_en->CurrentValue, NULL, FALSE);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("admin_notificationslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_institution":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `institution_id` AS `LinkFld`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
			$sWhereWrk = "";
			$this->institution->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`institution_id` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->institution, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($admin_notifications_add)) $admin_notifications_add = new cadmin_notifications_add();

// Page init
$admin_notifications_add->Page_Init();

// Page main
$admin_notifications_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$admin_notifications_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fadmin_notificationsadd = new ew_Form("fadmin_notificationsadd", "add");

// Validate form
fadmin_notificationsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($admin_notifications->date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_message_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $admin_notifications->message_ar->FldCaption(), $admin_notifications->message_ar->ReqErrMsg)) ?>");

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
fadmin_notificationsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fadmin_notificationsadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fadmin_notificationsadd.Lists["x_institution[]"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
fadmin_notificationsadd.Lists["x_institution[]"].Data = "<?php echo $admin_notifications_add->institution->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $admin_notifications_add->ShowPageHeader(); ?>
<?php
$admin_notifications_add->ShowMessage();
?>
<form name="fadmin_notificationsadd" id="fadmin_notificationsadd" class="<?php echo $admin_notifications_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($admin_notifications_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $admin_notifications_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="admin_notifications">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($admin_notifications_add->IsModal) ?>">
<?php if (!$admin_notifications_add->IsMobileOrModal) { ?>
<div class="ewDesktop"><!-- desktop -->
<?php } ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
<div class="ewAddDiv"><!-- page* -->
<?php } else { ?>
<table id="tbl_admin_notificationsadd" class="table table-striped table-bordered table-hover table-condensed ewDesktopTable"><!-- table* -->
<?php } ?>
<?php if ($admin_notifications->user->Visible) { // user ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_user" class="form-group">
		<label id="elh_admin_notifications_user" for="x_user" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->user->FldCaption() ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->user->CellAttributes() ?>>
<span id="el_admin_notifications_user">
<input type="text" data-table="admin_notifications" data-field="x_user" name="x_user" id="x_user" placeholder="<?php echo ew_HtmlEncode($admin_notifications->user->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->user->EditValue ?>"<?php echo $admin_notifications->user->EditAttributes() ?>>
</span>
<?php echo $admin_notifications->user->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_user">
		<td class="col-sm-2"><span id="elh_admin_notifications_user"><?php echo $admin_notifications->user->FldCaption() ?></span></td>
		<td<?php echo $admin_notifications->user->CellAttributes() ?>>
<span id="el_admin_notifications_user">
<input type="text" data-table="admin_notifications" data-field="x_user" name="x_user" id="x_user" placeholder="<?php echo ew_HtmlEncode($admin_notifications->user->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->user->EditValue ?>"<?php echo $admin_notifications->user->EditAttributes() ?>>
</span>
<?php echo $admin_notifications->user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications->institution->Visible) { // institution ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_institution" class="form-group">
		<label id="elh_admin_notifications_institution" for="x_institution" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->institution->FldCaption() ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->institution->CellAttributes() ?>>
<span id="el_admin_notifications_institution">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $admin_notifications->institution->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_institution" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $admin_notifications->institution->CheckBoxListHtml(TRUE, "x_institution[]") ?>
		</div>
	</div>
	<div id="tp_x_institution" class="ewTemplate"><input type="checkbox" data-table="admin_notifications" data-field="x_institution" data-value-separator="<?php echo $admin_notifications->institution->DisplayValueSeparatorAttribute() ?>" name="x_institution[]" id="x_institution[]" value="{value}"<?php echo $admin_notifications->institution->EditAttributes() ?>></div>
</div>
</span>
<?php echo $admin_notifications->institution->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_institution">
		<td class="col-sm-2"><span id="elh_admin_notifications_institution"><?php echo $admin_notifications->institution->FldCaption() ?></span></td>
		<td<?php echo $admin_notifications->institution->CellAttributes() ?>>
<span id="el_admin_notifications_institution">
<div class="ewDropdownList has-feedback">
	<span onclick="" class="form-control dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<?php echo $admin_notifications->institution->ViewValue ?>
	</span>
	<span class="glyphicon glyphicon-remove form-control-feedback ewDropdownListClear"></span>
	<span class="form-control-feedback"><span class="caret"></span></span>
	<div id="dsl_x_institution" data-repeatcolumn="1" class="dropdown-menu">
		<div class="ewItems" style="position: relative; overflow-x: hidden;">
<?php echo $admin_notifications->institution->CheckBoxListHtml(TRUE, "x_institution[]") ?>
		</div>
	</div>
	<div id="tp_x_institution" class="ewTemplate"><input type="checkbox" data-table="admin_notifications" data-field="x_institution" data-value-separator="<?php echo $admin_notifications->institution->DisplayValueSeparatorAttribute() ?>" name="x_institution[]" id="x_institution[]" value="{value}"<?php echo $admin_notifications->institution->EditAttributes() ?>></div>
</div>
</span>
<?php echo $admin_notifications->institution->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications->date->Visible) { // date ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_date" class="form-group">
		<label id="elh_admin_notifications_date" for="x_date" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->date->FldCaption() ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->date->CellAttributes() ?>>
<span id="el_admin_notifications_date">
<input type="text" data-table="admin_notifications" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($admin_notifications->date->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->date->EditValue ?>"<?php echo $admin_notifications->date->EditAttributes() ?>>
<?php if (!$admin_notifications->date->ReadOnly && !$admin_notifications->date->Disabled && !isset($admin_notifications->date->EditAttrs["readonly"]) && !isset($admin_notifications->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fadmin_notificationsadd", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $admin_notifications->date->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_date">
		<td class="col-sm-2"><span id="elh_admin_notifications_date"><?php echo $admin_notifications->date->FldCaption() ?></span></td>
		<td<?php echo $admin_notifications->date->CellAttributes() ?>>
<span id="el_admin_notifications_date">
<input type="text" data-table="admin_notifications" data-field="x_date" name="x_date" id="x_date" placeholder="<?php echo ew_HtmlEncode($admin_notifications->date->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->date->EditValue ?>"<?php echo $admin_notifications->date->EditAttributes() ?>>
<?php if (!$admin_notifications->date->ReadOnly && !$admin_notifications->date->Disabled && !isset($admin_notifications->date->EditAttrs["readonly"]) && !isset($admin_notifications->date->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fadmin_notificationsadd", "x_date", {"ignoreReadonly":true,"useCurrent":false,"format":0});
</script>
<?php } ?>
</span>
<?php echo $admin_notifications->date->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications->title_ar->Visible) { // title_ar ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_title_ar" class="form-group">
		<label id="elh_admin_notifications_title_ar" for="x_title_ar" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->title_ar->FldCaption() ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->title_ar->CellAttributes() ?>>
<span id="el_admin_notifications_title_ar">
<input type="text" data-table="admin_notifications" data-field="x_title_ar" name="x_title_ar" id="x_title_ar" placeholder="<?php echo ew_HtmlEncode($admin_notifications->title_ar->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->title_ar->EditValue ?>"<?php echo $admin_notifications->title_ar->EditAttributes() ?>>
</span>
<?php echo $admin_notifications->title_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_title_ar">
		<td class="col-sm-2"><span id="elh_admin_notifications_title_ar"><?php echo $admin_notifications->title_ar->FldCaption() ?></span></td>
		<td<?php echo $admin_notifications->title_ar->CellAttributes() ?>>
<span id="el_admin_notifications_title_ar">
<input type="text" data-table="admin_notifications" data-field="x_title_ar" name="x_title_ar" id="x_title_ar" placeholder="<?php echo ew_HtmlEncode($admin_notifications->title_ar->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->title_ar->EditValue ?>"<?php echo $admin_notifications->title_ar->EditAttributes() ?>>
</span>
<?php echo $admin_notifications->title_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications->message_ar->Visible) { // message_ar ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_message_ar" class="form-group">
		<label id="elh_admin_notifications_message_ar" for="x_message_ar" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->message_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->message_ar->CellAttributes() ?>>
<span id="el_admin_notifications_message_ar">
<textarea data-table="admin_notifications" data-field="x_message_ar" name="x_message_ar" id="x_message_ar" cols="50" rows="8" placeholder="<?php echo ew_HtmlEncode($admin_notifications->message_ar->getPlaceHolder()) ?>"<?php echo $admin_notifications->message_ar->EditAttributes() ?>><?php echo $admin_notifications->message_ar->EditValue ?></textarea>
</span>
<?php echo $admin_notifications->message_ar->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_message_ar">
		<td class="col-sm-2"><span id="elh_admin_notifications_message_ar"><?php echo $admin_notifications->message_ar->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $admin_notifications->message_ar->CellAttributes() ?>>
<span id="el_admin_notifications_message_ar">
<textarea data-table="admin_notifications" data-field="x_message_ar" name="x_message_ar" id="x_message_ar" cols="50" rows="8" placeholder="<?php echo ew_HtmlEncode($admin_notifications->message_ar->getPlaceHolder()) ?>"<?php echo $admin_notifications->message_ar->EditAttributes() ?>><?php echo $admin_notifications->message_ar->EditValue ?></textarea>
</span>
<?php echo $admin_notifications->message_ar->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications->title_en->Visible) { // title_en ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_title_en" class="form-group">
		<label id="elh_admin_notifications_title_en" for="x_title_en" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->title_en->FldCaption() ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->title_en->CellAttributes() ?>>
<span id="el_admin_notifications_title_en">
<input type="text" data-table="admin_notifications" data-field="x_title_en" name="x_title_en" id="x_title_en" placeholder="<?php echo ew_HtmlEncode($admin_notifications->title_en->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->title_en->EditValue ?>"<?php echo $admin_notifications->title_en->EditAttributes() ?>>
</span>
<?php echo $admin_notifications->title_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_title_en">
		<td class="col-sm-2"><span id="elh_admin_notifications_title_en"><?php echo $admin_notifications->title_en->FldCaption() ?></span></td>
		<td<?php echo $admin_notifications->title_en->CellAttributes() ?>>
<span id="el_admin_notifications_title_en">
<input type="text" data-table="admin_notifications" data-field="x_title_en" name="x_title_en" id="x_title_en" placeholder="<?php echo ew_HtmlEncode($admin_notifications->title_en->getPlaceHolder()) ?>" value="<?php echo $admin_notifications->title_en->EditValue ?>"<?php echo $admin_notifications->title_en->EditAttributes() ?>>
</span>
<?php echo $admin_notifications->title_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications->message_en->Visible) { // message_en ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
	<div id="r_message_en" class="form-group">
		<label id="elh_admin_notifications_message_en" for="x_message_en" class="<?php echo $admin_notifications_add->LeftColumnClass ?>"><?php echo $admin_notifications->message_en->FldCaption() ?></label>
		<div class="<?php echo $admin_notifications_add->RightColumnClass ?>"><div<?php echo $admin_notifications->message_en->CellAttributes() ?>>
<span id="el_admin_notifications_message_en">
<textarea data-table="admin_notifications" data-field="x_message_en" name="x_message_en" id="x_message_en" cols="50" rows="8" placeholder="<?php echo ew_HtmlEncode($admin_notifications->message_en->getPlaceHolder()) ?>"<?php echo $admin_notifications->message_en->EditAttributes() ?>><?php echo $admin_notifications->message_en->EditValue ?></textarea>
</span>
<?php echo $admin_notifications->message_en->CustomMsg ?></div></div>
	</div>
<?php } else { ?>
	<tr id="r_message_en">
		<td class="col-sm-2"><span id="elh_admin_notifications_message_en"><?php echo $admin_notifications->message_en->FldCaption() ?></span></td>
		<td<?php echo $admin_notifications->message_en->CellAttributes() ?>>
<span id="el_admin_notifications_message_en">
<textarea data-table="admin_notifications" data-field="x_message_en" name="x_message_en" id="x_message_en" cols="50" rows="8" placeholder="<?php echo ew_HtmlEncode($admin_notifications->message_en->getPlaceHolder()) ?>"<?php echo $admin_notifications->message_en->EditAttributes() ?>><?php echo $admin_notifications->message_en->EditValue ?></textarea>
</span>
<?php echo $admin_notifications->message_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php } ?>
<?php if ($admin_notifications_add->IsMobileOrModal) { ?>
</div><!-- /page* -->
<?php } else { ?>
</table><!-- /table* -->
<?php } ?>
<?php if (!$admin_notifications_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $admin_notifications_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $admin_notifications_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
<?php if (!$admin_notifications_add->IsMobileOrModal) { ?>
</div><!-- /desktop -->
<?php } ?>
</form>
<script type="text/javascript">
fadmin_notificationsadd.Init();
</script>
<?php
$admin_notifications_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$admin_notifications_add->Page_Terminate();
?>
