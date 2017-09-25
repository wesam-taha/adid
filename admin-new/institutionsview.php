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

$institutions_view = NULL; // Initialize page object first

class cinstitutions_view extends cinstitutions {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions';

	// Page object name
	var $PageObjName = 'institutions_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
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
		$KeyUrl = "";
		if (@$_GET["institution_id"] <> "") {
			$this->RecKey["institution_id"] = $_GET["institution_id"];
			$KeyUrl .= "&amp;institution_id=" . urlencode($this->RecKey["institution_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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
		// Get export parameters

		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} elseif (@$_GET["cmd"] == "json") {
			$this->Export = $_GET["cmd"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["institution_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= $_GET["institution_id"];
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
		$this->institution_id->SetVisibility();
		$this->institution_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;
	var $MultiPages; // Multi pages object

	//
	// Page main
	//
	function Page_Main() {
		global $Language, $gbSkipHeaderFooter, $EW_EXPORT;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["institution_id"] <> "") {
				$this->institution_id->setQueryStringValue($_GET["institution_id"]);
				$this->RecKey["institution_id"] = $this->institution_id->QueryStringValue;
			} elseif (@$_POST["institution_id"] <> "") {
				$this->institution_id->setFormValue($_POST["institution_id"]);
				$this->RecKey["institution_id"] = $this->institution_id->FormValue;
			} else {
				$sReturnUrl = "institutionslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "institutionslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "institutionslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'AddBtn',url:'" . ew_HtmlEncode($this->CopyUrl) . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_UrlAddQuery($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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

			// institution_id
			$this->institution_id->LinkCustomAttributes = "";
			$this->institution_id->HrefValue = "";
			$this->institution_id->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = FALSE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_institutions\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_institutions',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finstitutionsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->ListRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetupStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("institutionslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($institutions_view)) $institutions_view = new cinstitutions_view();

// Page init
$institutions_view->Page_Init();

// Page main
$institutions_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($institutions->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = finstitutionsview = new ew_Form("finstitutionsview", "view");

// Form_CustomValidate event
finstitutionsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutionsview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
finstitutionsview.MultiPage = new ew_MultiPage("finstitutionsview");

// Dynamic selection lists
finstitutionsview.Lists["x_institution_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_institution_type"].Options = <?php echo json_encode($institutions_view->institution_type->Options()) ?>;
finstitutionsview.Lists["x_volunteering_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_volunteering_type"].Options = <?php echo json_encode($institutions_view->volunteering_type->Options()) ?>;
finstitutionsview.Lists["x_nationality_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_nationality_type"].Options = <?php echo json_encode($institutions_view->nationality_type->Options()) ?>;
finstitutionsview.Lists["x_current_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_current_emirate"].Options = <?php echo json_encode($institutions_view->current_emirate->Options()) ?>;
finstitutionsview.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_view->admin_approval->Options()) ?>;
finstitutionsview.Lists["x_forward_to_dep"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
finstitutionsview.Lists["x_forward_to_dep"].Data = "<?php echo $institutions_view->forward_to_dep->LookupFilterQuery(FALSE, "view") ?>";
finstitutionsview.Lists["x_eco_department_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_eco_department_approval"].Options = <?php echo json_encode($institutions_view->eco_department_approval->Options()) ?>;
finstitutionsview.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionsview.Lists["x_security_approval"].Options = <?php echo json_encode($institutions_view->security_approval->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
<div class="ewToolbar">
<?php $institutions_view->ExportOptions->Render("body") ?>
<?php
	foreach ($institutions_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $institutions_view->ShowPageHeader(); ?>
<?php
$institutions_view->ShowMessage();
?>
<form name="finstitutionsview" id="finstitutionsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions">
<input type="hidden" name="modal" value="<?php echo intval($institutions_view->IsModal) ?>">
<?php if ($institutions->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="institutions_view">
	<ul class="nav<?php echo $institutions_view->MultiPages->NavStyle() ?>">
		<li<?php echo $institutions_view->MultiPages->TabStyle("1") ?>><a href="#tab_institutions1" data-toggle="tab"><?php echo $institutions->PageCaption(1) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("2") ?>><a href="#tab_institutions2" data-toggle="tab"><?php echo $institutions->PageCaption(2) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("3") ?>><a href="#tab_institutions3" data-toggle="tab"><?php echo $institutions->PageCaption(3) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("4") ?>><a href="#tab_institutions4" data-toggle="tab"><?php echo $institutions->PageCaption(4) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("5") ?>><a href="#tab_institutions5" data-toggle="tab"><?php echo $institutions->PageCaption(5) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("6") ?>><a href="#tab_institutions6" data-toggle="tab"><?php echo $institutions->PageCaption(6) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("7") ?>><a href="#tab_institutions7" data-toggle="tab"><?php echo $institutions->PageCaption(7) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("8") ?>><a href="#tab_institutions8" data-toggle="tab"><?php echo $institutions->PageCaption(8) ?></a></li>
		<li<?php echo $institutions_view->MultiPages->TabStyle("9") ?>><a href="#tab_institutions9" data-toggle="tab"><?php echo $institutions->PageCaption(9) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("1") ?>" id="tab_institutions1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->institution_id->Visible) { // institution_id ?>
	<tr id="r_institution_id">
		<td class="col-sm-2"><span id="elh_institutions_institution_id"><?php echo $institutions->institution_id->FldCaption() ?></span></td>
		<td data-name="institution_id"<?php echo $institutions->institution_id->CellAttributes() ?>>
<span id="el_institutions_institution_id" data-page="1">
<span<?php echo $institutions->institution_id->ViewAttributes() ?>>
<?php echo $institutions->institution_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->full_name_ar->Visible) { // full_name_ar ?>
	<tr id="r_full_name_ar">
		<td class="col-sm-2"><span id="elh_institutions_full_name_ar"><?php echo $institutions->full_name_ar->FldCaption() ?></span></td>
		<td data-name="full_name_ar"<?php echo $institutions->full_name_ar->CellAttributes() ?>>
<span id="el_institutions_full_name_ar" data-page="1">
<span<?php echo $institutions->full_name_ar->ViewAttributes() ?>>
<?php echo $institutions->full_name_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->full_name_en->Visible) { // full_name_en ?>
	<tr id="r_full_name_en">
		<td class="col-sm-2"><span id="elh_institutions_full_name_en"><?php echo $institutions->full_name_en->FldCaption() ?></span></td>
		<td data-name="full_name_en"<?php echo $institutions->full_name_en->CellAttributes() ?>>
<span id="el_institutions_full_name_en" data-page="1">
<span<?php echo $institutions->full_name_en->ViewAttributes() ?>>
<?php echo $institutions->full_name_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->institution_type->Visible) { // institution_type ?>
	<tr id="r_institution_type">
		<td class="col-sm-2"><span id="elh_institutions_institution_type"><?php echo $institutions->institution_type->FldCaption() ?></span></td>
		<td data-name="institution_type"<?php echo $institutions->institution_type->CellAttributes() ?>>
<span id="el_institutions_institution_type" data-page="1">
<span<?php echo $institutions->institution_type->ViewAttributes() ?>>
<?php echo $institutions->institution_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->institutes_name->Visible) { // institutes_name ?>
	<tr id="r_institutes_name">
		<td class="col-sm-2"><span id="elh_institutions_institutes_name"><?php echo $institutions->institutes_name->FldCaption() ?></span></td>
		<td data-name="institutes_name"<?php echo $institutions->institutes_name->CellAttributes() ?>>
<span id="el_institutions_institutes_name" data-page="1">
<span<?php echo $institutions->institutes_name->ViewAttributes() ?>>
<?php echo $institutions->institutes_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->volunteering_type->Visible) { // volunteering_type ?>
	<tr id="r_volunteering_type">
		<td class="col-sm-2"><span id="elh_institutions_volunteering_type"><?php echo $institutions->volunteering_type->FldCaption() ?></span></td>
		<td data-name="volunteering_type"<?php echo $institutions->volunteering_type->CellAttributes() ?>>
<span id="el_institutions_volunteering_type" data-page="1">
<span<?php echo $institutions->volunteering_type->ViewAttributes() ?>>
<?php echo $institutions->volunteering_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("2") ?>" id="tab_institutions2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->licence_no->Visible) { // licence_no ?>
	<tr id="r_licence_no">
		<td class="col-sm-2"><span id="elh_institutions_licence_no"><?php echo $institutions->licence_no->FldCaption() ?></span></td>
		<td data-name="licence_no"<?php echo $institutions->licence_no->CellAttributes() ?>>
<span id="el_institutions_licence_no" data-page="2">
<span<?php echo $institutions->licence_no->ViewAttributes() ?>>
<?php echo $institutions->licence_no->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->trade_licence->Visible) { // trade_licence ?>
	<tr id="r_trade_licence">
		<td class="col-sm-2"><span id="elh_institutions_trade_licence"><?php echo $institutions->trade_licence->FldCaption() ?></span></td>
		<td data-name="trade_licence"<?php echo $institutions->trade_licence->CellAttributes() ?>>
<span id="el_institutions_trade_licence" data-page="2">
<span>
<?php echo ew_GetFileViewTag($institutions->trade_licence, $institutions->trade_licence->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->tl_expiry_date->Visible) { // tl_expiry_date ?>
	<tr id="r_tl_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_tl_expiry_date"><?php echo $institutions->tl_expiry_date->FldCaption() ?></span></td>
		<td data-name="tl_expiry_date"<?php echo $institutions->tl_expiry_date->CellAttributes() ?>>
<span id="el_institutions_tl_expiry_date" data-page="2">
<span<?php echo $institutions->tl_expiry_date->ViewAttributes() ?>>
<?php echo $institutions->tl_expiry_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("3") ?>" id="tab_institutions3">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->nationality_type->Visible) { // nationality_type ?>
	<tr id="r_nationality_type">
		<td class="col-sm-2"><span id="elh_institutions_nationality_type"><?php echo $institutions->nationality_type->FldCaption() ?></span></td>
		<td data-name="nationality_type"<?php echo $institutions->nationality_type->CellAttributes() ?>>
<span id="el_institutions_nationality_type" data-page="3">
<span<?php echo $institutions->nationality_type->ViewAttributes() ?>>
<?php echo $institutions->nationality_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->nationality->Visible) { // nationality ?>
	<tr id="r_nationality">
		<td class="col-sm-2"><span id="elh_institutions_nationality"><?php echo $institutions->nationality->FldCaption() ?></span></td>
		<td data-name="nationality"<?php echo $institutions->nationality->CellAttributes() ?>>
<span id="el_institutions_nationality" data-page="3">
<span<?php echo $institutions->nationality->ViewAttributes() ?>>
<?php echo $institutions->nationality->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->visa_expiry_date->Visible) { // visa_expiry_date ?>
	<tr id="r_visa_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_visa_expiry_date"><?php echo $institutions->visa_expiry_date->FldCaption() ?></span></td>
		<td data-name="visa_expiry_date"<?php echo $institutions->visa_expiry_date->CellAttributes() ?>>
<span id="el_institutions_visa_expiry_date" data-page="3">
<span<?php echo $institutions->visa_expiry_date->ViewAttributes() ?>>
<?php echo $institutions->visa_expiry_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->unid->Visible) { // unid ?>
	<tr id="r_unid">
		<td class="col-sm-2"><span id="elh_institutions_unid"><?php echo $institutions->unid->FldCaption() ?></span></td>
		<td data-name="unid"<?php echo $institutions->unid->CellAttributes() ?>>
<span id="el_institutions_unid" data-page="3">
<span<?php echo $institutions->unid->ViewAttributes() ?>>
<?php echo $institutions->unid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->visa_copy->Visible) { // visa_copy ?>
	<tr id="r_visa_copy">
		<td class="col-sm-2"><span id="elh_institutions_visa_copy"><?php echo $institutions->visa_copy->FldCaption() ?></span></td>
		<td data-name="visa_copy"<?php echo $institutions->visa_copy->CellAttributes() ?>>
<span id="el_institutions_visa_copy" data-page="3">
<span>
<?php echo ew_GetFileViewTag($institutions->visa_copy, $institutions->visa_copy->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->current_emirate->Visible) { // current_emirate ?>
	<tr id="r_current_emirate">
		<td class="col-sm-2"><span id="elh_institutions_current_emirate"><?php echo $institutions->current_emirate->FldCaption() ?></span></td>
		<td data-name="current_emirate"<?php echo $institutions->current_emirate->CellAttributes() ?>>
<span id="el_institutions_current_emirate" data-page="3">
<span<?php echo $institutions->current_emirate->ViewAttributes() ?>>
<?php echo $institutions->current_emirate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->full_address->Visible) { // full_address ?>
	<tr id="r_full_address">
		<td class="col-sm-2"><span id="elh_institutions_full_address"><?php echo $institutions->full_address->FldCaption() ?></span></td>
		<td data-name="full_address"<?php echo $institutions->full_address->CellAttributes() ?>>
<span id="el_institutions_full_address" data-page="3">
<span<?php echo $institutions->full_address->ViewAttributes() ?>>
<?php echo $institutions->full_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("4") ?>" id="tab_institutions4">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->emirates_id_number->Visible) { // emirates_id_number ?>
	<tr id="r_emirates_id_number">
		<td class="col-sm-2"><span id="elh_institutions_emirates_id_number"><?php echo $institutions->emirates_id_number->FldCaption() ?></span></td>
		<td data-name="emirates_id_number"<?php echo $institutions->emirates_id_number->CellAttributes() ?>>
<span id="el_institutions_emirates_id_number" data-page="4">
<span<?php echo $institutions->emirates_id_number->ViewAttributes() ?>>
<?php echo $institutions->emirates_id_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->eid_expiry_date->Visible) { // eid_expiry_date ?>
	<tr id="r_eid_expiry_date">
		<td class="col-sm-2"><span id="elh_institutions_eid_expiry_date"><?php echo $institutions->eid_expiry_date->FldCaption() ?></span></td>
		<td data-name="eid_expiry_date"<?php echo $institutions->eid_expiry_date->CellAttributes() ?>>
<span id="el_institutions_eid_expiry_date" data-page="4">
<span<?php echo $institutions->eid_expiry_date->ViewAttributes() ?>>
<?php echo $institutions->eid_expiry_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->emirates_id_copy->Visible) { // emirates_id_copy ?>
	<tr id="r_emirates_id_copy">
		<td class="col-sm-2"><span id="elh_institutions_emirates_id_copy"><?php echo $institutions->emirates_id_copy->FldCaption() ?></span></td>
		<td data-name="emirates_id_copy"<?php echo $institutions->emirates_id_copy->CellAttributes() ?>>
<span id="el_institutions_emirates_id_copy" data-page="4">
<span>
<?php echo ew_GetFileViewTag($institutions->emirates_id_copy, $institutions->emirates_id_copy->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->passport_number->Visible) { // passport_number ?>
	<tr id="r_passport_number">
		<td class="col-sm-2"><span id="elh_institutions_passport_number"><?php echo $institutions->passport_number->FldCaption() ?></span></td>
		<td data-name="passport_number"<?php echo $institutions->passport_number->CellAttributes() ?>>
<span id="el_institutions_passport_number" data-page="4">
<span<?php echo $institutions->passport_number->ViewAttributes() ?>>
<?php echo $institutions->passport_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->passport_ex_date->Visible) { // passport_ex_date ?>
	<tr id="r_passport_ex_date">
		<td class="col-sm-2"><span id="elh_institutions_passport_ex_date"><?php echo $institutions->passport_ex_date->FldCaption() ?></span></td>
		<td data-name="passport_ex_date"<?php echo $institutions->passport_ex_date->CellAttributes() ?>>
<span id="el_institutions_passport_ex_date" data-page="4">
<span<?php echo $institutions->passport_ex_date->ViewAttributes() ?>>
<?php echo $institutions->passport_ex_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->passport_copy->Visible) { // passport_copy ?>
	<tr id="r_passport_copy">
		<td class="col-sm-2"><span id="elh_institutions_passport_copy"><?php echo $institutions->passport_copy->FldCaption() ?></span></td>
		<td data-name="passport_copy"<?php echo $institutions->passport_copy->CellAttributes() ?>>
<span id="el_institutions_passport_copy" data-page="4">
<span>
<?php echo ew_GetFileViewTag($institutions->passport_copy, $institutions->passport_copy->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("5") ?>" id="tab_institutions5">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->place_of_work->Visible) { // place_of_work ?>
	<tr id="r_place_of_work">
		<td class="col-sm-2"><span id="elh_institutions_place_of_work"><?php echo $institutions->place_of_work->FldCaption() ?></span></td>
		<td data-name="place_of_work"<?php echo $institutions->place_of_work->CellAttributes() ?>>
<span id="el_institutions_place_of_work" data-page="5">
<span<?php echo $institutions->place_of_work->ViewAttributes() ?>>
<?php echo $institutions->place_of_work->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->work_phone->Visible) { // work_phone ?>
	<tr id="r_work_phone">
		<td class="col-sm-2"><span id="elh_institutions_work_phone"><?php echo $institutions->work_phone->FldCaption() ?></span></td>
		<td data-name="work_phone"<?php echo $institutions->work_phone->CellAttributes() ?>>
<span id="el_institutions_work_phone" data-page="5">
<span<?php echo $institutions->work_phone->ViewAttributes() ?>>
<?php echo $institutions->work_phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->mobile_phone->Visible) { // mobile_phone ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_institutions_mobile_phone"><?php echo $institutions->mobile_phone->FldCaption() ?></span></td>
		<td data-name="mobile_phone"<?php echo $institutions->mobile_phone->CellAttributes() ?>>
<span id="el_institutions_mobile_phone" data-page="5">
<span<?php echo $institutions->mobile_phone->ViewAttributes() ?>>
<?php echo $institutions->mobile_phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td class="col-sm-2"><span id="elh_institutions_fax"><?php echo $institutions->fax->FldCaption() ?></span></td>
		<td data-name="fax"<?php echo $institutions->fax->CellAttributes() ?>>
<span id="el_institutions_fax" data-page="5">
<span<?php echo $institutions->fax->ViewAttributes() ?>>
<?php echo $institutions->fax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->pobbox->Visible) { // pobbox ?>
	<tr id="r_pobbox">
		<td class="col-sm-2"><span id="elh_institutions_pobbox"><?php echo $institutions->pobbox->FldCaption() ?></span></td>
		<td data-name="pobbox"<?php echo $institutions->pobbox->CellAttributes() ?>>
<span id="el_institutions_pobbox" data-page="5">
<span<?php echo $institutions->pobbox->ViewAttributes() ?>>
<?php echo $institutions->pobbox->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("6") ?>" id="tab_institutions6">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->_email->Visible) { // email ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_institutions__email"><?php echo $institutions->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $institutions->_email->CellAttributes() ?>>
<span id="el_institutions__email" data-page="6">
<span<?php echo $institutions->_email->ViewAttributes() ?>>
<?php echo $institutions->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->password->Visible) { // password ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_institutions_password"><?php echo $institutions->password->FldCaption() ?></span></td>
		<td data-name="password"<?php echo $institutions->password->CellAttributes() ?>>
<span id="el_institutions_password" data-page="6">
<span<?php echo $institutions->password->ViewAttributes() ?>>
<?php echo $institutions->password->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("7") ?>" id="tab_institutions7">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->admin_approval->Visible) { // admin_approval ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_institutions_admin_approval"><?php echo $institutions->admin_approval->FldCaption() ?></span></td>
		<td data-name="admin_approval"<?php echo $institutions->admin_approval->CellAttributes() ?>>
<span id="el_institutions_admin_approval" data-page="7">
<span<?php echo $institutions->admin_approval->ViewAttributes() ?>>
<?php echo $institutions->admin_approval->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->admin_comment->Visible) { // admin_comment ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_institutions_admin_comment"><?php echo $institutions->admin_comment->FldCaption() ?></span></td>
		<td data-name="admin_comment"<?php echo $institutions->admin_comment->CellAttributes() ?>>
<span id="el_institutions_admin_comment" data-page="7">
<span<?php echo $institutions->admin_comment->ViewAttributes() ?>>
<?php echo $institutions->admin_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->forward_to_dep->Visible) { // forward_to_dep ?>
	<tr id="r_forward_to_dep">
		<td class="col-sm-2"><span id="elh_institutions_forward_to_dep"><?php echo $institutions->forward_to_dep->FldCaption() ?></span></td>
		<td data-name="forward_to_dep"<?php echo $institutions->forward_to_dep->CellAttributes() ?>>
<span id="el_institutions_forward_to_dep" data-page="7">
<span<?php echo $institutions->forward_to_dep->ViewAttributes() ?>>
<?php echo $institutions->forward_to_dep->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("8") ?>" id="tab_institutions8">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->eco_department_approval->Visible) { // eco_department_approval ?>
	<tr id="r_eco_department_approval">
		<td class="col-sm-2"><span id="elh_institutions_eco_department_approval"><?php echo $institutions->eco_department_approval->FldCaption() ?></span></td>
		<td data-name="eco_department_approval"<?php echo $institutions->eco_department_approval->CellAttributes() ?>>
<span id="el_institutions_eco_department_approval" data-page="8">
<span<?php echo $institutions->eco_department_approval->ViewAttributes() ?>>
<?php echo $institutions->eco_department_approval->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->eco_departmnet_comment->Visible) { // eco_departmnet_comment ?>
	<tr id="r_eco_departmnet_comment">
		<td class="col-sm-2"><span id="elh_institutions_eco_departmnet_comment"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?></span></td>
		<td data-name="eco_departmnet_comment"<?php echo $institutions->eco_departmnet_comment->CellAttributes() ?>>
<span id="el_institutions_eco_departmnet_comment" data-page="8">
<span<?php echo $institutions->eco_departmnet_comment->ViewAttributes() ?>>
<?php echo $institutions->eco_departmnet_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
		<div class="tab-pane<?php echo $institutions_view->MultiPages->PageStyle("9") ?>" id="tab_institutions9">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($institutions->security_approval->Visible) { // security_approval ?>
	<tr id="r_security_approval">
		<td class="col-sm-2"><span id="elh_institutions_security_approval"><?php echo $institutions->security_approval->FldCaption() ?></span></td>
		<td data-name="security_approval"<?php echo $institutions->security_approval->CellAttributes() ?>>
<span id="el_institutions_security_approval" data-page="9">
<span<?php echo $institutions->security_approval->ViewAttributes() ?>>
<?php echo $institutions->security_approval->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($institutions->security_comment->Visible) { // security_comment ?>
	<tr id="r_security_comment">
		<td class="col-sm-2"><span id="elh_institutions_security_comment"><?php echo $institutions->security_comment->FldCaption() ?></span></td>
		<td data-name="security_comment"<?php echo $institutions->security_comment->CellAttributes() ?>>
<span id="el_institutions_security_comment" data-page="9">
<span<?php echo $institutions->security_comment->ViewAttributes() ?>>
<?php echo $institutions->security_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($institutions->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
</form>
<?php if ($institutions->Export == "") { ?>
<script type="text/javascript">
finstitutionsview.Init();
</script>
<?php } ?>
<?php
$institutions_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($institutions->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$institutions_view->Page_Terminate();
?>
