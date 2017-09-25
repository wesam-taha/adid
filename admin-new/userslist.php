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

$users_list = NULL; // Initialize page object first

class cusers_list extends cusers {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_list';

	// Grid form hidden field names
	var $FormName = 'fuserslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Table object (users)
		if (!isset($GLOBALS["users"]) || get_class($GLOBALS["users"]) == "cusers") {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "usersadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "usersdelete.php";
		$this->MultiUpdateUrl = "usersupdate.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fuserslistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
		$this->user_id->SetVisibility();
		$this->user_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->full_name_ar->SetVisibility();
		$this->full_name_en->SetVisibility();
		$this->_email->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->security_approval->SetVisibility();
		$this->security_comment->SetVisibility();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $user_attachments_Count;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->user_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->user_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fuserslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->user_id->AdvancedSearch->ToJson(), ","); // Field user_id
		$sFilterList = ew_Concat($sFilterList, $this->group_id->AdvancedSearch->ToJson(), ","); // Field group_id
		$sFilterList = ew_Concat($sFilterList, $this->full_name_ar->AdvancedSearch->ToJson(), ","); // Field full_name_ar
		$sFilterList = ew_Concat($sFilterList, $this->full_name_en->AdvancedSearch->ToJson(), ","); // Field full_name_en
		$sFilterList = ew_Concat($sFilterList, $this->date_of_birth->AdvancedSearch->ToJson(), ","); // Field date_of_birth
		$sFilterList = ew_Concat($sFilterList, $this->personal_photo->AdvancedSearch->ToJson(), ","); // Field personal_photo
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJson(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->blood_type->AdvancedSearch->ToJson(), ","); // Field blood_type
		$sFilterList = ew_Concat($sFilterList, $this->driving_licence->AdvancedSearch->ToJson(), ","); // Field driving_licence
		$sFilterList = ew_Concat($sFilterList, $this->job->AdvancedSearch->ToJson(), ","); // Field job
		$sFilterList = ew_Concat($sFilterList, $this->volunteering_type->AdvancedSearch->ToJson(), ","); // Field volunteering_type
		$sFilterList = ew_Concat($sFilterList, $this->marital_status->AdvancedSearch->ToJson(), ","); // Field marital_status
		$sFilterList = ew_Concat($sFilterList, $this->nationality_type->AdvancedSearch->ToJson(), ","); // Field nationality_type
		$sFilterList = ew_Concat($sFilterList, $this->nationality->AdvancedSearch->ToJson(), ","); // Field nationality
		$sFilterList = ew_Concat($sFilterList, $this->unid->AdvancedSearch->ToJson(), ","); // Field unid
		$sFilterList = ew_Concat($sFilterList, $this->visa_expiry_date->AdvancedSearch->ToJson(), ","); // Field visa_expiry_date
		$sFilterList = ew_Concat($sFilterList, $this->visa_copy->AdvancedSearch->ToJson(), ","); // Field visa_copy
		$sFilterList = ew_Concat($sFilterList, $this->current_emirate->AdvancedSearch->ToJson(), ","); // Field current_emirate
		$sFilterList = ew_Concat($sFilterList, $this->full_address->AdvancedSearch->ToJson(), ","); // Field full_address
		$sFilterList = ew_Concat($sFilterList, $this->emirates_id_number->AdvancedSearch->ToJson(), ","); // Field emirates_id_number
		$sFilterList = ew_Concat($sFilterList, $this->eid_expiry_date->AdvancedSearch->ToJson(), ","); // Field eid_expiry_date
		$sFilterList = ew_Concat($sFilterList, $this->emirates_id_copy->AdvancedSearch->ToJson(), ","); // Field emirates_id_copy
		$sFilterList = ew_Concat($sFilterList, $this->passport_number->AdvancedSearch->ToJson(), ","); // Field passport_number
		$sFilterList = ew_Concat($sFilterList, $this->passport_ex_date->AdvancedSearch->ToJson(), ","); // Field passport_ex_date
		$sFilterList = ew_Concat($sFilterList, $this->passport_copy->AdvancedSearch->ToJson(), ","); // Field passport_copy
		$sFilterList = ew_Concat($sFilterList, $this->place_of_work->AdvancedSearch->ToJson(), ","); // Field place_of_work
		$sFilterList = ew_Concat($sFilterList, $this->qualifications->AdvancedSearch->ToJson(), ","); // Field qualifications
		$sFilterList = ew_Concat($sFilterList, $this->cv->AdvancedSearch->ToJson(), ","); // Field cv
		$sFilterList = ew_Concat($sFilterList, $this->home_phone->AdvancedSearch->ToJson(), ","); // Field home_phone
		$sFilterList = ew_Concat($sFilterList, $this->work_phone->AdvancedSearch->ToJson(), ","); // Field work_phone
		$sFilterList = ew_Concat($sFilterList, $this->mobile_phone->AdvancedSearch->ToJson(), ","); // Field mobile_phone
		$sFilterList = ew_Concat($sFilterList, $this->fax->AdvancedSearch->ToJson(), ","); // Field fax
		$sFilterList = ew_Concat($sFilterList, $this->pobbox->AdvancedSearch->ToJson(), ","); // Field pobbox
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->password->AdvancedSearch->ToJson(), ","); // Field password
		$sFilterList = ew_Concat($sFilterList, $this->total_voluntary_hours->AdvancedSearch->ToJson(), ","); // Field total_voluntary_hours
		$sFilterList = ew_Concat($sFilterList, $this->overall_evaluation->AdvancedSearch->ToJson(), ","); // Field overall_evaluation
		$sFilterList = ew_Concat($sFilterList, $this->admin_approval->AdvancedSearch->ToJson(), ","); // Field admin_approval
		$sFilterList = ew_Concat($sFilterList, $this->lastUpdatedBy->AdvancedSearch->ToJson(), ","); // Field lastUpdatedBy
		$sFilterList = ew_Concat($sFilterList, $this->admin_comment->AdvancedSearch->ToJson(), ","); // Field admin_comment
		$sFilterList = ew_Concat($sFilterList, $this->security_approval->AdvancedSearch->ToJson(), ","); // Field security_approval
		$sFilterList = ew_Concat($sFilterList, $this->approvedBy->AdvancedSearch->ToJson(), ","); // Field approvedBy
		$sFilterList = ew_Concat($sFilterList, $this->security_comment->AdvancedSearch->ToJson(), ","); // Field security_comment
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fuserslistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field user_id
		$this->user_id->AdvancedSearch->SearchValue = @$filter["x_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator = @$filter["z_user_id"];
		$this->user_id->AdvancedSearch->SearchCondition = @$filter["v_user_id"];
		$this->user_id->AdvancedSearch->SearchValue2 = @$filter["y_user_id"];
		$this->user_id->AdvancedSearch->SearchOperator2 = @$filter["w_user_id"];
		$this->user_id->AdvancedSearch->Save();

		// Field group_id
		$this->group_id->AdvancedSearch->SearchValue = @$filter["x_group_id"];
		$this->group_id->AdvancedSearch->SearchOperator = @$filter["z_group_id"];
		$this->group_id->AdvancedSearch->SearchCondition = @$filter["v_group_id"];
		$this->group_id->AdvancedSearch->SearchValue2 = @$filter["y_group_id"];
		$this->group_id->AdvancedSearch->SearchOperator2 = @$filter["w_group_id"];
		$this->group_id->AdvancedSearch->Save();

		// Field full_name_ar
		$this->full_name_ar->AdvancedSearch->SearchValue = @$filter["x_full_name_ar"];
		$this->full_name_ar->AdvancedSearch->SearchOperator = @$filter["z_full_name_ar"];
		$this->full_name_ar->AdvancedSearch->SearchCondition = @$filter["v_full_name_ar"];
		$this->full_name_ar->AdvancedSearch->SearchValue2 = @$filter["y_full_name_ar"];
		$this->full_name_ar->AdvancedSearch->SearchOperator2 = @$filter["w_full_name_ar"];
		$this->full_name_ar->AdvancedSearch->Save();

		// Field full_name_en
		$this->full_name_en->AdvancedSearch->SearchValue = @$filter["x_full_name_en"];
		$this->full_name_en->AdvancedSearch->SearchOperator = @$filter["z_full_name_en"];
		$this->full_name_en->AdvancedSearch->SearchCondition = @$filter["v_full_name_en"];
		$this->full_name_en->AdvancedSearch->SearchValue2 = @$filter["y_full_name_en"];
		$this->full_name_en->AdvancedSearch->SearchOperator2 = @$filter["w_full_name_en"];
		$this->full_name_en->AdvancedSearch->Save();

		// Field date_of_birth
		$this->date_of_birth->AdvancedSearch->SearchValue = @$filter["x_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchOperator = @$filter["z_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchCondition = @$filter["v_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchValue2 = @$filter["y_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchOperator2 = @$filter["w_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->Save();

		// Field personal_photo
		$this->personal_photo->AdvancedSearch->SearchValue = @$filter["x_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchOperator = @$filter["z_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchCondition = @$filter["v_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchValue2 = @$filter["y_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchOperator2 = @$filter["w_personal_photo"];
		$this->personal_photo->AdvancedSearch->Save();

		// Field gender
		$this->gender->AdvancedSearch->SearchValue = @$filter["x_gender"];
		$this->gender->AdvancedSearch->SearchOperator = @$filter["z_gender"];
		$this->gender->AdvancedSearch->SearchCondition = @$filter["v_gender"];
		$this->gender->AdvancedSearch->SearchValue2 = @$filter["y_gender"];
		$this->gender->AdvancedSearch->SearchOperator2 = @$filter["w_gender"];
		$this->gender->AdvancedSearch->Save();

		// Field blood_type
		$this->blood_type->AdvancedSearch->SearchValue = @$filter["x_blood_type"];
		$this->blood_type->AdvancedSearch->SearchOperator = @$filter["z_blood_type"];
		$this->blood_type->AdvancedSearch->SearchCondition = @$filter["v_blood_type"];
		$this->blood_type->AdvancedSearch->SearchValue2 = @$filter["y_blood_type"];
		$this->blood_type->AdvancedSearch->SearchOperator2 = @$filter["w_blood_type"];
		$this->blood_type->AdvancedSearch->Save();

		// Field driving_licence
		$this->driving_licence->AdvancedSearch->SearchValue = @$filter["x_driving_licence"];
		$this->driving_licence->AdvancedSearch->SearchOperator = @$filter["z_driving_licence"];
		$this->driving_licence->AdvancedSearch->SearchCondition = @$filter["v_driving_licence"];
		$this->driving_licence->AdvancedSearch->SearchValue2 = @$filter["y_driving_licence"];
		$this->driving_licence->AdvancedSearch->SearchOperator2 = @$filter["w_driving_licence"];
		$this->driving_licence->AdvancedSearch->Save();

		// Field job
		$this->job->AdvancedSearch->SearchValue = @$filter["x_job"];
		$this->job->AdvancedSearch->SearchOperator = @$filter["z_job"];
		$this->job->AdvancedSearch->SearchCondition = @$filter["v_job"];
		$this->job->AdvancedSearch->SearchValue2 = @$filter["y_job"];
		$this->job->AdvancedSearch->SearchOperator2 = @$filter["w_job"];
		$this->job->AdvancedSearch->Save();

		// Field volunteering_type
		$this->volunteering_type->AdvancedSearch->SearchValue = @$filter["x_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchOperator = @$filter["z_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchCondition = @$filter["v_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchValue2 = @$filter["y_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchOperator2 = @$filter["w_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->Save();

		// Field marital_status
		$this->marital_status->AdvancedSearch->SearchValue = @$filter["x_marital_status"];
		$this->marital_status->AdvancedSearch->SearchOperator = @$filter["z_marital_status"];
		$this->marital_status->AdvancedSearch->SearchCondition = @$filter["v_marital_status"];
		$this->marital_status->AdvancedSearch->SearchValue2 = @$filter["y_marital_status"];
		$this->marital_status->AdvancedSearch->SearchOperator2 = @$filter["w_marital_status"];
		$this->marital_status->AdvancedSearch->Save();

		// Field nationality_type
		$this->nationality_type->AdvancedSearch->SearchValue = @$filter["x_nationality_type"];
		$this->nationality_type->AdvancedSearch->SearchOperator = @$filter["z_nationality_type"];
		$this->nationality_type->AdvancedSearch->SearchCondition = @$filter["v_nationality_type"];
		$this->nationality_type->AdvancedSearch->SearchValue2 = @$filter["y_nationality_type"];
		$this->nationality_type->AdvancedSearch->SearchOperator2 = @$filter["w_nationality_type"];
		$this->nationality_type->AdvancedSearch->Save();

		// Field nationality
		$this->nationality->AdvancedSearch->SearchValue = @$filter["x_nationality"];
		$this->nationality->AdvancedSearch->SearchOperator = @$filter["z_nationality"];
		$this->nationality->AdvancedSearch->SearchCondition = @$filter["v_nationality"];
		$this->nationality->AdvancedSearch->SearchValue2 = @$filter["y_nationality"];
		$this->nationality->AdvancedSearch->SearchOperator2 = @$filter["w_nationality"];
		$this->nationality->AdvancedSearch->Save();

		// Field unid
		$this->unid->AdvancedSearch->SearchValue = @$filter["x_unid"];
		$this->unid->AdvancedSearch->SearchOperator = @$filter["z_unid"];
		$this->unid->AdvancedSearch->SearchCondition = @$filter["v_unid"];
		$this->unid->AdvancedSearch->SearchValue2 = @$filter["y_unid"];
		$this->unid->AdvancedSearch->SearchOperator2 = @$filter["w_unid"];
		$this->unid->AdvancedSearch->Save();

		// Field visa_expiry_date
		$this->visa_expiry_date->AdvancedSearch->SearchValue = @$filter["x_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchOperator = @$filter["z_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchCondition = @$filter["v_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchValue2 = @$filter["y_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchOperator2 = @$filter["w_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->Save();

		// Field visa_copy
		$this->visa_copy->AdvancedSearch->SearchValue = @$filter["x_visa_copy"];
		$this->visa_copy->AdvancedSearch->SearchOperator = @$filter["z_visa_copy"];
		$this->visa_copy->AdvancedSearch->SearchCondition = @$filter["v_visa_copy"];
		$this->visa_copy->AdvancedSearch->SearchValue2 = @$filter["y_visa_copy"];
		$this->visa_copy->AdvancedSearch->SearchOperator2 = @$filter["w_visa_copy"];
		$this->visa_copy->AdvancedSearch->Save();

		// Field current_emirate
		$this->current_emirate->AdvancedSearch->SearchValue = @$filter["x_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchOperator = @$filter["z_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchCondition = @$filter["v_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchValue2 = @$filter["y_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchOperator2 = @$filter["w_current_emirate"];
		$this->current_emirate->AdvancedSearch->Save();

		// Field full_address
		$this->full_address->AdvancedSearch->SearchValue = @$filter["x_full_address"];
		$this->full_address->AdvancedSearch->SearchOperator = @$filter["z_full_address"];
		$this->full_address->AdvancedSearch->SearchCondition = @$filter["v_full_address"];
		$this->full_address->AdvancedSearch->SearchValue2 = @$filter["y_full_address"];
		$this->full_address->AdvancedSearch->SearchOperator2 = @$filter["w_full_address"];
		$this->full_address->AdvancedSearch->Save();

		// Field emirates_id_number
		$this->emirates_id_number->AdvancedSearch->SearchValue = @$filter["x_emirates_id_number"];
		$this->emirates_id_number->AdvancedSearch->SearchOperator = @$filter["z_emirates_id_number"];
		$this->emirates_id_number->AdvancedSearch->SearchCondition = @$filter["v_emirates_id_number"];
		$this->emirates_id_number->AdvancedSearch->SearchValue2 = @$filter["y_emirates_id_number"];
		$this->emirates_id_number->AdvancedSearch->SearchOperator2 = @$filter["w_emirates_id_number"];
		$this->emirates_id_number->AdvancedSearch->Save();

		// Field eid_expiry_date
		$this->eid_expiry_date->AdvancedSearch->SearchValue = @$filter["x_eid_expiry_date"];
		$this->eid_expiry_date->AdvancedSearch->SearchOperator = @$filter["z_eid_expiry_date"];
		$this->eid_expiry_date->AdvancedSearch->SearchCondition = @$filter["v_eid_expiry_date"];
		$this->eid_expiry_date->AdvancedSearch->SearchValue2 = @$filter["y_eid_expiry_date"];
		$this->eid_expiry_date->AdvancedSearch->SearchOperator2 = @$filter["w_eid_expiry_date"];
		$this->eid_expiry_date->AdvancedSearch->Save();

		// Field emirates_id_copy
		$this->emirates_id_copy->AdvancedSearch->SearchValue = @$filter["x_emirates_id_copy"];
		$this->emirates_id_copy->AdvancedSearch->SearchOperator = @$filter["z_emirates_id_copy"];
		$this->emirates_id_copy->AdvancedSearch->SearchCondition = @$filter["v_emirates_id_copy"];
		$this->emirates_id_copy->AdvancedSearch->SearchValue2 = @$filter["y_emirates_id_copy"];
		$this->emirates_id_copy->AdvancedSearch->SearchOperator2 = @$filter["w_emirates_id_copy"];
		$this->emirates_id_copy->AdvancedSearch->Save();

		// Field passport_number
		$this->passport_number->AdvancedSearch->SearchValue = @$filter["x_passport_number"];
		$this->passport_number->AdvancedSearch->SearchOperator = @$filter["z_passport_number"];
		$this->passport_number->AdvancedSearch->SearchCondition = @$filter["v_passport_number"];
		$this->passport_number->AdvancedSearch->SearchValue2 = @$filter["y_passport_number"];
		$this->passport_number->AdvancedSearch->SearchOperator2 = @$filter["w_passport_number"];
		$this->passport_number->AdvancedSearch->Save();

		// Field passport_ex_date
		$this->passport_ex_date->AdvancedSearch->SearchValue = @$filter["x_passport_ex_date"];
		$this->passport_ex_date->AdvancedSearch->SearchOperator = @$filter["z_passport_ex_date"];
		$this->passport_ex_date->AdvancedSearch->SearchCondition = @$filter["v_passport_ex_date"];
		$this->passport_ex_date->AdvancedSearch->SearchValue2 = @$filter["y_passport_ex_date"];
		$this->passport_ex_date->AdvancedSearch->SearchOperator2 = @$filter["w_passport_ex_date"];
		$this->passport_ex_date->AdvancedSearch->Save();

		// Field passport_copy
		$this->passport_copy->AdvancedSearch->SearchValue = @$filter["x_passport_copy"];
		$this->passport_copy->AdvancedSearch->SearchOperator = @$filter["z_passport_copy"];
		$this->passport_copy->AdvancedSearch->SearchCondition = @$filter["v_passport_copy"];
		$this->passport_copy->AdvancedSearch->SearchValue2 = @$filter["y_passport_copy"];
		$this->passport_copy->AdvancedSearch->SearchOperator2 = @$filter["w_passport_copy"];
		$this->passport_copy->AdvancedSearch->Save();

		// Field place_of_work
		$this->place_of_work->AdvancedSearch->SearchValue = @$filter["x_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchOperator = @$filter["z_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchCondition = @$filter["v_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchValue2 = @$filter["y_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchOperator2 = @$filter["w_place_of_work"];
		$this->place_of_work->AdvancedSearch->Save();

		// Field qualifications
		$this->qualifications->AdvancedSearch->SearchValue = @$filter["x_qualifications"];
		$this->qualifications->AdvancedSearch->SearchOperator = @$filter["z_qualifications"];
		$this->qualifications->AdvancedSearch->SearchCondition = @$filter["v_qualifications"];
		$this->qualifications->AdvancedSearch->SearchValue2 = @$filter["y_qualifications"];
		$this->qualifications->AdvancedSearch->SearchOperator2 = @$filter["w_qualifications"];
		$this->qualifications->AdvancedSearch->Save();

		// Field cv
		$this->cv->AdvancedSearch->SearchValue = @$filter["x_cv"];
		$this->cv->AdvancedSearch->SearchOperator = @$filter["z_cv"];
		$this->cv->AdvancedSearch->SearchCondition = @$filter["v_cv"];
		$this->cv->AdvancedSearch->SearchValue2 = @$filter["y_cv"];
		$this->cv->AdvancedSearch->SearchOperator2 = @$filter["w_cv"];
		$this->cv->AdvancedSearch->Save();

		// Field home_phone
		$this->home_phone->AdvancedSearch->SearchValue = @$filter["x_home_phone"];
		$this->home_phone->AdvancedSearch->SearchOperator = @$filter["z_home_phone"];
		$this->home_phone->AdvancedSearch->SearchCondition = @$filter["v_home_phone"];
		$this->home_phone->AdvancedSearch->SearchValue2 = @$filter["y_home_phone"];
		$this->home_phone->AdvancedSearch->SearchOperator2 = @$filter["w_home_phone"];
		$this->home_phone->AdvancedSearch->Save();

		// Field work_phone
		$this->work_phone->AdvancedSearch->SearchValue = @$filter["x_work_phone"];
		$this->work_phone->AdvancedSearch->SearchOperator = @$filter["z_work_phone"];
		$this->work_phone->AdvancedSearch->SearchCondition = @$filter["v_work_phone"];
		$this->work_phone->AdvancedSearch->SearchValue2 = @$filter["y_work_phone"];
		$this->work_phone->AdvancedSearch->SearchOperator2 = @$filter["w_work_phone"];
		$this->work_phone->AdvancedSearch->Save();

		// Field mobile_phone
		$this->mobile_phone->AdvancedSearch->SearchValue = @$filter["x_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchOperator = @$filter["z_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchCondition = @$filter["v_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchValue2 = @$filter["y_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchOperator2 = @$filter["w_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->Save();

		// Field fax
		$this->fax->AdvancedSearch->SearchValue = @$filter["x_fax"];
		$this->fax->AdvancedSearch->SearchOperator = @$filter["z_fax"];
		$this->fax->AdvancedSearch->SearchCondition = @$filter["v_fax"];
		$this->fax->AdvancedSearch->SearchValue2 = @$filter["y_fax"];
		$this->fax->AdvancedSearch->SearchOperator2 = @$filter["w_fax"];
		$this->fax->AdvancedSearch->Save();

		// Field pobbox
		$this->pobbox->AdvancedSearch->SearchValue = @$filter["x_pobbox"];
		$this->pobbox->AdvancedSearch->SearchOperator = @$filter["z_pobbox"];
		$this->pobbox->AdvancedSearch->SearchCondition = @$filter["v_pobbox"];
		$this->pobbox->AdvancedSearch->SearchValue2 = @$filter["y_pobbox"];
		$this->pobbox->AdvancedSearch->SearchOperator2 = @$filter["w_pobbox"];
		$this->pobbox->AdvancedSearch->Save();

		// Field email
		$this->_email->AdvancedSearch->SearchValue = @$filter["x__email"];
		$this->_email->AdvancedSearch->SearchOperator = @$filter["z__email"];
		$this->_email->AdvancedSearch->SearchCondition = @$filter["v__email"];
		$this->_email->AdvancedSearch->SearchValue2 = @$filter["y__email"];
		$this->_email->AdvancedSearch->SearchOperator2 = @$filter["w__email"];
		$this->_email->AdvancedSearch->Save();

		// Field password
		$this->password->AdvancedSearch->SearchValue = @$filter["x_password"];
		$this->password->AdvancedSearch->SearchOperator = @$filter["z_password"];
		$this->password->AdvancedSearch->SearchCondition = @$filter["v_password"];
		$this->password->AdvancedSearch->SearchValue2 = @$filter["y_password"];
		$this->password->AdvancedSearch->SearchOperator2 = @$filter["w_password"];
		$this->password->AdvancedSearch->Save();

		// Field total_voluntary_hours
		$this->total_voluntary_hours->AdvancedSearch->SearchValue = @$filter["x_total_voluntary_hours"];
		$this->total_voluntary_hours->AdvancedSearch->SearchOperator = @$filter["z_total_voluntary_hours"];
		$this->total_voluntary_hours->AdvancedSearch->SearchCondition = @$filter["v_total_voluntary_hours"];
		$this->total_voluntary_hours->AdvancedSearch->SearchValue2 = @$filter["y_total_voluntary_hours"];
		$this->total_voluntary_hours->AdvancedSearch->SearchOperator2 = @$filter["w_total_voluntary_hours"];
		$this->total_voluntary_hours->AdvancedSearch->Save();

		// Field overall_evaluation
		$this->overall_evaluation->AdvancedSearch->SearchValue = @$filter["x_overall_evaluation"];
		$this->overall_evaluation->AdvancedSearch->SearchOperator = @$filter["z_overall_evaluation"];
		$this->overall_evaluation->AdvancedSearch->SearchCondition = @$filter["v_overall_evaluation"];
		$this->overall_evaluation->AdvancedSearch->SearchValue2 = @$filter["y_overall_evaluation"];
		$this->overall_evaluation->AdvancedSearch->SearchOperator2 = @$filter["w_overall_evaluation"];
		$this->overall_evaluation->AdvancedSearch->Save();

		// Field admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = @$filter["x_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchOperator = @$filter["z_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchCondition = @$filter["v_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchValue2 = @$filter["y_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchOperator2 = @$filter["w_admin_approval"];
		$this->admin_approval->AdvancedSearch->Save();

		// Field lastUpdatedBy
		$this->lastUpdatedBy->AdvancedSearch->SearchValue = @$filter["x_lastUpdatedBy"];
		$this->lastUpdatedBy->AdvancedSearch->SearchOperator = @$filter["z_lastUpdatedBy"];
		$this->lastUpdatedBy->AdvancedSearch->SearchCondition = @$filter["v_lastUpdatedBy"];
		$this->lastUpdatedBy->AdvancedSearch->SearchValue2 = @$filter["y_lastUpdatedBy"];
		$this->lastUpdatedBy->AdvancedSearch->SearchOperator2 = @$filter["w_lastUpdatedBy"];
		$this->lastUpdatedBy->AdvancedSearch->Save();

		// Field admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = @$filter["x_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchOperator = @$filter["z_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchCondition = @$filter["v_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchValue2 = @$filter["y_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchOperator2 = @$filter["w_admin_comment"];
		$this->admin_comment->AdvancedSearch->Save();

		// Field security_approval
		$this->security_approval->AdvancedSearch->SearchValue = @$filter["x_security_approval"];
		$this->security_approval->AdvancedSearch->SearchOperator = @$filter["z_security_approval"];
		$this->security_approval->AdvancedSearch->SearchCondition = @$filter["v_security_approval"];
		$this->security_approval->AdvancedSearch->SearchValue2 = @$filter["y_security_approval"];
		$this->security_approval->AdvancedSearch->SearchOperator2 = @$filter["w_security_approval"];
		$this->security_approval->AdvancedSearch->Save();

		// Field approvedBy
		$this->approvedBy->AdvancedSearch->SearchValue = @$filter["x_approvedBy"];
		$this->approvedBy->AdvancedSearch->SearchOperator = @$filter["z_approvedBy"];
		$this->approvedBy->AdvancedSearch->SearchCondition = @$filter["v_approvedBy"];
		$this->approvedBy->AdvancedSearch->SearchValue2 = @$filter["y_approvedBy"];
		$this->approvedBy->AdvancedSearch->SearchOperator2 = @$filter["w_approvedBy"];
		$this->approvedBy->AdvancedSearch->Save();

		// Field security_comment
		$this->security_comment->AdvancedSearch->SearchValue = @$filter["x_security_comment"];
		$this->security_comment->AdvancedSearch->SearchOperator = @$filter["z_security_comment"];
		$this->security_comment->AdvancedSearch->SearchCondition = @$filter["v_security_comment"];
		$this->security_comment->AdvancedSearch->SearchValue2 = @$filter["y_security_comment"];
		$this->security_comment->AdvancedSearch->SearchOperator2 = @$filter["w_security_comment"];
		$this->security_comment->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->user_id, $Default, FALSE); // user_id
		$this->BuildSearchSql($sWhere, $this->group_id, $Default, TRUE); // group_id
		$this->BuildSearchSql($sWhere, $this->full_name_ar, $Default, FALSE); // full_name_ar
		$this->BuildSearchSql($sWhere, $this->full_name_en, $Default, FALSE); // full_name_en
		$this->BuildSearchSql($sWhere, $this->date_of_birth, $Default, FALSE); // date_of_birth
		$this->BuildSearchSql($sWhere, $this->personal_photo, $Default, FALSE); // personal_photo
		$this->BuildSearchSql($sWhere, $this->gender, $Default, FALSE); // gender
		$this->BuildSearchSql($sWhere, $this->blood_type, $Default, FALSE); // blood_type
		$this->BuildSearchSql($sWhere, $this->driving_licence, $Default, FALSE); // driving_licence
		$this->BuildSearchSql($sWhere, $this->job, $Default, FALSE); // job
		$this->BuildSearchSql($sWhere, $this->volunteering_type, $Default, FALSE); // volunteering_type
		$this->BuildSearchSql($sWhere, $this->marital_status, $Default, FALSE); // marital_status
		$this->BuildSearchSql($sWhere, $this->nationality_type, $Default, FALSE); // nationality_type
		$this->BuildSearchSql($sWhere, $this->nationality, $Default, FALSE); // nationality
		$this->BuildSearchSql($sWhere, $this->unid, $Default, FALSE); // unid
		$this->BuildSearchSql($sWhere, $this->visa_expiry_date, $Default, FALSE); // visa_expiry_date
		$this->BuildSearchSql($sWhere, $this->visa_copy, $Default, FALSE); // visa_copy
		$this->BuildSearchSql($sWhere, $this->current_emirate, $Default, FALSE); // current_emirate
		$this->BuildSearchSql($sWhere, $this->full_address, $Default, FALSE); // full_address
		$this->BuildSearchSql($sWhere, $this->emirates_id_number, $Default, FALSE); // emirates_id_number
		$this->BuildSearchSql($sWhere, $this->eid_expiry_date, $Default, FALSE); // eid_expiry_date
		$this->BuildSearchSql($sWhere, $this->emirates_id_copy, $Default, FALSE); // emirates_id_copy
		$this->BuildSearchSql($sWhere, $this->passport_number, $Default, FALSE); // passport_number
		$this->BuildSearchSql($sWhere, $this->passport_ex_date, $Default, FALSE); // passport_ex_date
		$this->BuildSearchSql($sWhere, $this->passport_copy, $Default, FALSE); // passport_copy
		$this->BuildSearchSql($sWhere, $this->place_of_work, $Default, FALSE); // place_of_work
		$this->BuildSearchSql($sWhere, $this->qualifications, $Default, FALSE); // qualifications
		$this->BuildSearchSql($sWhere, $this->cv, $Default, FALSE); // cv
		$this->BuildSearchSql($sWhere, $this->home_phone, $Default, FALSE); // home_phone
		$this->BuildSearchSql($sWhere, $this->work_phone, $Default, FALSE); // work_phone
		$this->BuildSearchSql($sWhere, $this->mobile_phone, $Default, FALSE); // mobile_phone
		$this->BuildSearchSql($sWhere, $this->fax, $Default, FALSE); // fax
		$this->BuildSearchSql($sWhere, $this->pobbox, $Default, FALSE); // pobbox
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->password, $Default, FALSE); // password
		$this->BuildSearchSql($sWhere, $this->total_voluntary_hours, $Default, FALSE); // total_voluntary_hours
		$this->BuildSearchSql($sWhere, $this->overall_evaluation, $Default, FALSE); // overall_evaluation
		$this->BuildSearchSql($sWhere, $this->admin_approval, $Default, FALSE); // admin_approval
		$this->BuildSearchSql($sWhere, $this->lastUpdatedBy, $Default, FALSE); // lastUpdatedBy
		$this->BuildSearchSql($sWhere, $this->admin_comment, $Default, FALSE); // admin_comment
		$this->BuildSearchSql($sWhere, $this->security_approval, $Default, FALSE); // security_approval
		$this->BuildSearchSql($sWhere, $this->approvedBy, $Default, FALSE); // approvedBy
		$this->BuildSearchSql($sWhere, $this->security_comment, $Default, FALSE); // security_comment

		// Set up search parm
		if (!$Default && $sWhere <> "" && $this->Command == "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->user_id->AdvancedSearch->Save(); // user_id
			$this->group_id->AdvancedSearch->Save(); // group_id
			$this->full_name_ar->AdvancedSearch->Save(); // full_name_ar
			$this->full_name_en->AdvancedSearch->Save(); // full_name_en
			$this->date_of_birth->AdvancedSearch->Save(); // date_of_birth
			$this->personal_photo->AdvancedSearch->Save(); // personal_photo
			$this->gender->AdvancedSearch->Save(); // gender
			$this->blood_type->AdvancedSearch->Save(); // blood_type
			$this->driving_licence->AdvancedSearch->Save(); // driving_licence
			$this->job->AdvancedSearch->Save(); // job
			$this->volunteering_type->AdvancedSearch->Save(); // volunteering_type
			$this->marital_status->AdvancedSearch->Save(); // marital_status
			$this->nationality_type->AdvancedSearch->Save(); // nationality_type
			$this->nationality->AdvancedSearch->Save(); // nationality
			$this->unid->AdvancedSearch->Save(); // unid
			$this->visa_expiry_date->AdvancedSearch->Save(); // visa_expiry_date
			$this->visa_copy->AdvancedSearch->Save(); // visa_copy
			$this->current_emirate->AdvancedSearch->Save(); // current_emirate
			$this->full_address->AdvancedSearch->Save(); // full_address
			$this->emirates_id_number->AdvancedSearch->Save(); // emirates_id_number
			$this->eid_expiry_date->AdvancedSearch->Save(); // eid_expiry_date
			$this->emirates_id_copy->AdvancedSearch->Save(); // emirates_id_copy
			$this->passport_number->AdvancedSearch->Save(); // passport_number
			$this->passport_ex_date->AdvancedSearch->Save(); // passport_ex_date
			$this->passport_copy->AdvancedSearch->Save(); // passport_copy
			$this->place_of_work->AdvancedSearch->Save(); // place_of_work
			$this->qualifications->AdvancedSearch->Save(); // qualifications
			$this->cv->AdvancedSearch->Save(); // cv
			$this->home_phone->AdvancedSearch->Save(); // home_phone
			$this->work_phone->AdvancedSearch->Save(); // work_phone
			$this->mobile_phone->AdvancedSearch->Save(); // mobile_phone
			$this->fax->AdvancedSearch->Save(); // fax
			$this->pobbox->AdvancedSearch->Save(); // pobbox
			$this->_email->AdvancedSearch->Save(); // email
			$this->password->AdvancedSearch->Save(); // password
			$this->total_voluntary_hours->AdvancedSearch->Save(); // total_voluntary_hours
			$this->overall_evaluation->AdvancedSearch->Save(); // overall_evaluation
			$this->admin_approval->AdvancedSearch->Save(); // admin_approval
			$this->lastUpdatedBy->AdvancedSearch->Save(); // lastUpdatedBy
			$this->admin_comment->AdvancedSearch->Save(); // admin_comment
			$this->security_approval->AdvancedSearch->Save(); // security_approval
			$this->approvedBy->AdvancedSearch->Save(); // approvedBy
			$this->security_comment->AdvancedSearch->Save(); // security_comment
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = $Fld->FldParm();
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->group_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_name_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_name_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->personal_photo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nationality, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->visa_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->current_emirate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->emirates_id_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->emirates_id_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passport_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passport_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->place_of_work, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->qualifications, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cv, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->home_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->work_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pobbox, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->total_voluntary_hours, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->lastUpdatedBy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->admin_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->approvedBy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->security_comment, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && $this->Command == "") $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->user_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->group_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->full_name_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->full_name_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->date_of_birth->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->personal_photo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->gender->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->blood_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->driving_licence->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->job->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->volunteering_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->marital_status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nationality_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nationality->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->unid->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->visa_expiry_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->visa_copy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->current_emirate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->full_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->emirates_id_number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->eid_expiry_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->emirates_id_copy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->passport_number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->passport_ex_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->passport_copy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->place_of_work->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->qualifications->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->cv->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->home_phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->work_phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mobile_phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->fax->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pobbox->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->password->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->total_voluntary_hours->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->overall_evaluation->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->admin_approval->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->lastUpdatedBy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->admin_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->security_approval->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->approvedBy->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->security_comment->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->user_id->AdvancedSearch->UnsetSession();
		$this->group_id->AdvancedSearch->UnsetSession();
		$this->full_name_ar->AdvancedSearch->UnsetSession();
		$this->full_name_en->AdvancedSearch->UnsetSession();
		$this->date_of_birth->AdvancedSearch->UnsetSession();
		$this->personal_photo->AdvancedSearch->UnsetSession();
		$this->gender->AdvancedSearch->UnsetSession();
		$this->blood_type->AdvancedSearch->UnsetSession();
		$this->driving_licence->AdvancedSearch->UnsetSession();
		$this->job->AdvancedSearch->UnsetSession();
		$this->volunteering_type->AdvancedSearch->UnsetSession();
		$this->marital_status->AdvancedSearch->UnsetSession();
		$this->nationality_type->AdvancedSearch->UnsetSession();
		$this->nationality->AdvancedSearch->UnsetSession();
		$this->unid->AdvancedSearch->UnsetSession();
		$this->visa_expiry_date->AdvancedSearch->UnsetSession();
		$this->visa_copy->AdvancedSearch->UnsetSession();
		$this->current_emirate->AdvancedSearch->UnsetSession();
		$this->full_address->AdvancedSearch->UnsetSession();
		$this->emirates_id_number->AdvancedSearch->UnsetSession();
		$this->eid_expiry_date->AdvancedSearch->UnsetSession();
		$this->emirates_id_copy->AdvancedSearch->UnsetSession();
		$this->passport_number->AdvancedSearch->UnsetSession();
		$this->passport_ex_date->AdvancedSearch->UnsetSession();
		$this->passport_copy->AdvancedSearch->UnsetSession();
		$this->place_of_work->AdvancedSearch->UnsetSession();
		$this->qualifications->AdvancedSearch->UnsetSession();
		$this->cv->AdvancedSearch->UnsetSession();
		$this->home_phone->AdvancedSearch->UnsetSession();
		$this->work_phone->AdvancedSearch->UnsetSession();
		$this->mobile_phone->AdvancedSearch->UnsetSession();
		$this->fax->AdvancedSearch->UnsetSession();
		$this->pobbox->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->password->AdvancedSearch->UnsetSession();
		$this->total_voluntary_hours->AdvancedSearch->UnsetSession();
		$this->overall_evaluation->AdvancedSearch->UnsetSession();
		$this->admin_approval->AdvancedSearch->UnsetSession();
		$this->lastUpdatedBy->AdvancedSearch->UnsetSession();
		$this->admin_comment->AdvancedSearch->UnsetSession();
		$this->security_approval->AdvancedSearch->UnsetSession();
		$this->approvedBy->AdvancedSearch->UnsetSession();
		$this->security_comment->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->user_id->AdvancedSearch->Load();
		$this->group_id->AdvancedSearch->Load();
		$this->full_name_ar->AdvancedSearch->Load();
		$this->full_name_en->AdvancedSearch->Load();
		$this->date_of_birth->AdvancedSearch->Load();
		$this->personal_photo->AdvancedSearch->Load();
		$this->gender->AdvancedSearch->Load();
		$this->blood_type->AdvancedSearch->Load();
		$this->driving_licence->AdvancedSearch->Load();
		$this->job->AdvancedSearch->Load();
		$this->volunteering_type->AdvancedSearch->Load();
		$this->marital_status->AdvancedSearch->Load();
		$this->nationality_type->AdvancedSearch->Load();
		$this->nationality->AdvancedSearch->Load();
		$this->unid->AdvancedSearch->Load();
		$this->visa_expiry_date->AdvancedSearch->Load();
		$this->visa_copy->AdvancedSearch->Load();
		$this->current_emirate->AdvancedSearch->Load();
		$this->full_address->AdvancedSearch->Load();
		$this->emirates_id_number->AdvancedSearch->Load();
		$this->eid_expiry_date->AdvancedSearch->Load();
		$this->emirates_id_copy->AdvancedSearch->Load();
		$this->passport_number->AdvancedSearch->Load();
		$this->passport_ex_date->AdvancedSearch->Load();
		$this->passport_copy->AdvancedSearch->Load();
		$this->place_of_work->AdvancedSearch->Load();
		$this->qualifications->AdvancedSearch->Load();
		$this->cv->AdvancedSearch->Load();
		$this->home_phone->AdvancedSearch->Load();
		$this->work_phone->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->pobbox->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->total_voluntary_hours->AdvancedSearch->Load();
		$this->overall_evaluation->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->lastUpdatedBy->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->security_approval->AdvancedSearch->Load();
		$this->approvedBy->AdvancedSearch->Load();
		$this->security_comment->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->full_name_ar); // full_name_ar
			$this->UpdateSort($this->full_name_en); // full_name_en
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->admin_approval); // admin_approval
			$this->UpdateSort($this->admin_comment); // admin_comment
			$this->UpdateSort($this->security_approval); // security_approval
			$this->UpdateSort($this->security_comment); // security_comment
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
				$this->user_id->setSort("DESC");
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->user_id->setSort("");
				$this->full_name_ar->setSort("");
				$this->full_name_en->setSort("");
				$this->_email->setSort("");
				$this->admin_approval->setSort("");
				$this->admin_comment->setSort("");
				$this->security_approval->setSort("");
				$this->security_comment->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "detail_user_attachments"
		$item = &$this->ListOptions->Add("detail_user_attachments");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'user_attachments') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["user_attachments_grid"])) $GLOBALS["user_attachments_grid"] = new cuser_attachments_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssClass = "text-nowrap";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("user_attachments");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<input style='margin-left:20px;' type='checkbox' name='selecteduserid[]' value='".ew_HtmlEncode(preg_replace("/[^0-9]/","",$this->ViewUrl))."'> <a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a> ";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_user_attachments"
		$oListOpt = &$this->ListOptions->Items["detail_user_attachments"];
		if ($Security->AllowList(CurrentProjectID() . 'user_attachments')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("user_attachments", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->user_attachments_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("user_attachmentslist.php?" . EW_TABLE_SHOW_MASTER . "=users&fk_user_id=" . urlencode(strval($this->user_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["user_attachments_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'user_attachments')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "user_attachments";
			}
			if ($GLOBALS["user_attachments_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'user_attachments')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "user_attachments";
			}
			if ($GLOBALS["user_attachments_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'user_attachments')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "user_attachments";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->user_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_user_attachments");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["user_attachments"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["user_attachments"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'user_attachments') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "user_attachments";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$caption = $Language->Phrase("AddMasterDetailLink");
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fuserslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fuserslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fuserslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fuserslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fuserslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"userssrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"users\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'userssrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// user_id

		$this->user_id->AdvancedSearch->SearchValue = @$_GET["x_user_id"];
		if ($this->user_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->user_id->AdvancedSearch->SearchOperator = @$_GET["z_user_id"];

		// group_id
		$this->group_id->AdvancedSearch->SearchValue = @$_GET["x_group_id"];
		if ($this->group_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->group_id->AdvancedSearch->SearchOperator = @$_GET["z_group_id"];
		if (is_array($this->group_id->AdvancedSearch->SearchValue)) $this->group_id->AdvancedSearch->SearchValue = implode(",", $this->group_id->AdvancedSearch->SearchValue);
		if (is_array($this->group_id->AdvancedSearch->SearchValue2)) $this->group_id->AdvancedSearch->SearchValue2 = implode(",", $this->group_id->AdvancedSearch->SearchValue2);

		// full_name_ar
		$this->full_name_ar->AdvancedSearch->SearchValue = @$_GET["x_full_name_ar"];
		if ($this->full_name_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->full_name_ar->AdvancedSearch->SearchOperator = @$_GET["z_full_name_ar"];

		// full_name_en
		$this->full_name_en->AdvancedSearch->SearchValue = @$_GET["x_full_name_en"];
		if ($this->full_name_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->full_name_en->AdvancedSearch->SearchOperator = @$_GET["z_full_name_en"];

		// date_of_birth
		$this->date_of_birth->AdvancedSearch->SearchValue = @$_GET["x_date_of_birth"];
		if ($this->date_of_birth->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->date_of_birth->AdvancedSearch->SearchOperator = @$_GET["z_date_of_birth"];

		// personal_photo
		$this->personal_photo->AdvancedSearch->SearchValue = @$_GET["x_personal_photo"];
		if ($this->personal_photo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->personal_photo->AdvancedSearch->SearchOperator = @$_GET["z_personal_photo"];

		// gender
		$this->gender->AdvancedSearch->SearchValue = @$_GET["x_gender"];
		if ($this->gender->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->gender->AdvancedSearch->SearchOperator = @$_GET["z_gender"];

		// blood_type
		$this->blood_type->AdvancedSearch->SearchValue = @$_GET["x_blood_type"];
		if ($this->blood_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->blood_type->AdvancedSearch->SearchOperator = @$_GET["z_blood_type"];

		// driving_licence
		$this->driving_licence->AdvancedSearch->SearchValue = @$_GET["x_driving_licence"];
		if ($this->driving_licence->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->driving_licence->AdvancedSearch->SearchOperator = @$_GET["z_driving_licence"];

		// job
		$this->job->AdvancedSearch->SearchValue = @$_GET["x_job"];
		if ($this->job->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->job->AdvancedSearch->SearchOperator = @$_GET["z_job"];

		// volunteering_type
		$this->volunteering_type->AdvancedSearch->SearchValue = @$_GET["x_volunteering_type"];
		if ($this->volunteering_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->volunteering_type->AdvancedSearch->SearchOperator = @$_GET["z_volunteering_type"];

		// marital_status
		$this->marital_status->AdvancedSearch->SearchValue = @$_GET["x_marital_status"];
		if ($this->marital_status->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->marital_status->AdvancedSearch->SearchOperator = @$_GET["z_marital_status"];

		// nationality_type
		$this->nationality_type->AdvancedSearch->SearchValue = @$_GET["x_nationality_type"];
		if ($this->nationality_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nationality_type->AdvancedSearch->SearchOperator = @$_GET["z_nationality_type"];

		// nationality
		$this->nationality->AdvancedSearch->SearchValue = @$_GET["x_nationality"];
		if ($this->nationality->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nationality->AdvancedSearch->SearchOperator = @$_GET["z_nationality"];

		// unid
		$this->unid->AdvancedSearch->SearchValue = @$_GET["x_unid"];
		if ($this->unid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->unid->AdvancedSearch->SearchOperator = @$_GET["z_unid"];

		// visa_expiry_date
		$this->visa_expiry_date->AdvancedSearch->SearchValue = @$_GET["x_visa_expiry_date"];
		if ($this->visa_expiry_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->visa_expiry_date->AdvancedSearch->SearchOperator = @$_GET["z_visa_expiry_date"];

		// visa_copy
		$this->visa_copy->AdvancedSearch->SearchValue = @$_GET["x_visa_copy"];
		if ($this->visa_copy->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->visa_copy->AdvancedSearch->SearchOperator = @$_GET["z_visa_copy"];

		// current_emirate
		$this->current_emirate->AdvancedSearch->SearchValue = @$_GET["x_current_emirate"];
		if ($this->current_emirate->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->current_emirate->AdvancedSearch->SearchOperator = @$_GET["z_current_emirate"];

		// full_address
		$this->full_address->AdvancedSearch->SearchValue = @$_GET["x_full_address"];
		if ($this->full_address->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->full_address->AdvancedSearch->SearchOperator = @$_GET["z_full_address"];

		// emirates_id_number
		$this->emirates_id_number->AdvancedSearch->SearchValue = @$_GET["x_emirates_id_number"];
		if ($this->emirates_id_number->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->emirates_id_number->AdvancedSearch->SearchOperator = @$_GET["z_emirates_id_number"];

		// eid_expiry_date
		$this->eid_expiry_date->AdvancedSearch->SearchValue = @$_GET["x_eid_expiry_date"];
		if ($this->eid_expiry_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->eid_expiry_date->AdvancedSearch->SearchOperator = @$_GET["z_eid_expiry_date"];

		// emirates_id_copy
		$this->emirates_id_copy->AdvancedSearch->SearchValue = @$_GET["x_emirates_id_copy"];
		if ($this->emirates_id_copy->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->emirates_id_copy->AdvancedSearch->SearchOperator = @$_GET["z_emirates_id_copy"];

		// passport_number
		$this->passport_number->AdvancedSearch->SearchValue = @$_GET["x_passport_number"];
		if ($this->passport_number->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->passport_number->AdvancedSearch->SearchOperator = @$_GET["z_passport_number"];

		// passport_ex_date
		$this->passport_ex_date->AdvancedSearch->SearchValue = @$_GET["x_passport_ex_date"];
		if ($this->passport_ex_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->passport_ex_date->AdvancedSearch->SearchOperator = @$_GET["z_passport_ex_date"];

		// passport_copy
		$this->passport_copy->AdvancedSearch->SearchValue = @$_GET["x_passport_copy"];
		if ($this->passport_copy->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->passport_copy->AdvancedSearch->SearchOperator = @$_GET["z_passport_copy"];

		// place_of_work
		$this->place_of_work->AdvancedSearch->SearchValue = @$_GET["x_place_of_work"];
		if ($this->place_of_work->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->place_of_work->AdvancedSearch->SearchOperator = @$_GET["z_place_of_work"];

		// qualifications
		$this->qualifications->AdvancedSearch->SearchValue = @$_GET["x_qualifications"];
		if ($this->qualifications->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->qualifications->AdvancedSearch->SearchOperator = @$_GET["z_qualifications"];

		// cv
		$this->cv->AdvancedSearch->SearchValue = @$_GET["x_cv"];
		if ($this->cv->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->cv->AdvancedSearch->SearchOperator = @$_GET["z_cv"];

		// home_phone
		$this->home_phone->AdvancedSearch->SearchValue = @$_GET["x_home_phone"];
		if ($this->home_phone->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->home_phone->AdvancedSearch->SearchOperator = @$_GET["z_home_phone"];

		// work_phone
		$this->work_phone->AdvancedSearch->SearchValue = @$_GET["x_work_phone"];
		if ($this->work_phone->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->work_phone->AdvancedSearch->SearchOperator = @$_GET["z_work_phone"];

		// mobile_phone
		$this->mobile_phone->AdvancedSearch->SearchValue = @$_GET["x_mobile_phone"];
		if ($this->mobile_phone->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mobile_phone->AdvancedSearch->SearchOperator = @$_GET["z_mobile_phone"];

		// fax
		$this->fax->AdvancedSearch->SearchValue = @$_GET["x_fax"];
		if ($this->fax->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->fax->AdvancedSearch->SearchOperator = @$_GET["z_fax"];

		// pobbox
		$this->pobbox->AdvancedSearch->SearchValue = @$_GET["x_pobbox"];
		if ($this->pobbox->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->pobbox->AdvancedSearch->SearchOperator = @$_GET["z_pobbox"];

		// email
		$this->_email->AdvancedSearch->SearchValue = @$_GET["x__email"];
		if ($this->_email->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// password
		$this->password->AdvancedSearch->SearchValue = @$_GET["x_password"];
		if ($this->password->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->password->AdvancedSearch->SearchOperator = @$_GET["z_password"];

		// total_voluntary_hours
		$this->total_voluntary_hours->AdvancedSearch->SearchValue = @$_GET["x_total_voluntary_hours"];
		if ($this->total_voluntary_hours->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->total_voluntary_hours->AdvancedSearch->SearchOperator = @$_GET["z_total_voluntary_hours"];

		// overall_evaluation
		$this->overall_evaluation->AdvancedSearch->SearchValue = @$_GET["x_overall_evaluation"];
		if ($this->overall_evaluation->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->overall_evaluation->AdvancedSearch->SearchOperator = @$_GET["z_overall_evaluation"];

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = @$_GET["x_admin_approval"];
		if ($this->admin_approval->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->admin_approval->AdvancedSearch->SearchOperator = @$_GET["z_admin_approval"];

		// lastUpdatedBy
		$this->lastUpdatedBy->AdvancedSearch->SearchValue = @$_GET["x_lastUpdatedBy"];
		if ($this->lastUpdatedBy->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->lastUpdatedBy->AdvancedSearch->SearchOperator = @$_GET["z_lastUpdatedBy"];

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = @$_GET["x_admin_comment"];
		if ($this->admin_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->admin_comment->AdvancedSearch->SearchOperator = @$_GET["z_admin_comment"];

		// security_approval
		$this->security_approval->AdvancedSearch->SearchValue = @$_GET["x_security_approval"];
		if ($this->security_approval->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->security_approval->AdvancedSearch->SearchOperator = @$_GET["z_security_approval"];

		// approvedBy
		$this->approvedBy->AdvancedSearch->SearchValue = @$_GET["x_approvedBy"];
		if ($this->approvedBy->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->approvedBy->AdvancedSearch->SearchOperator = @$_GET["z_approvedBy"];

		// security_comment
		$this->security_comment->AdvancedSearch->SearchValue = @$_GET["x_security_comment"];
		if ($this->security_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->security_comment->AdvancedSearch->SearchOperator = @$_GET["z_security_comment"];
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
		if (!isset($GLOBALS["user_attachments_grid"])) $GLOBALS["user_attachments_grid"] = new cuser_attachments_grid;
		$sDetailFilter = $GLOBALS["user_attachments"]->SqlDetailFilter_users();
		$sDetailFilter = str_replace("@_userid@", ew_AdjustSql($this->user_id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["user_attachments"]->setCurrentMasterTable("users");
		$sDetailFilter = $GLOBALS["user_attachments"]->ApplyUserIDFilters($sDetailFilter);
		$this->user_attachments_Count = $GLOBALS["user_attachments"]->LoadRecordCount($sDetailFilter);
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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

		$this->title_number->CellCssStyle = "white-space: nowrap;";

		// security_owner
		$this->security_owner->CellCssStyle = "white-space: nowrap;";
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

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// full_name_ar
			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";
			$this->full_name_ar->TooltipValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";
			$this->full_name_en->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->user_id->AdvancedSearch->Load();
		$this->group_id->AdvancedSearch->Load();
		$this->full_name_ar->AdvancedSearch->Load();
		$this->full_name_en->AdvancedSearch->Load();
		$this->date_of_birth->AdvancedSearch->Load();
		$this->personal_photo->AdvancedSearch->Load();
		$this->gender->AdvancedSearch->Load();
		$this->blood_type->AdvancedSearch->Load();
		$this->driving_licence->AdvancedSearch->Load();
		$this->job->AdvancedSearch->Load();
		$this->volunteering_type->AdvancedSearch->Load();
		$this->marital_status->AdvancedSearch->Load();
		$this->nationality_type->AdvancedSearch->Load();
		$this->nationality->AdvancedSearch->Load();
		$this->unid->AdvancedSearch->Load();
		$this->visa_expiry_date->AdvancedSearch->Load();
		$this->visa_copy->AdvancedSearch->Load();
		$this->current_emirate->AdvancedSearch->Load();
		$this->full_address->AdvancedSearch->Load();
		$this->emirates_id_number->AdvancedSearch->Load();
		$this->eid_expiry_date->AdvancedSearch->Load();
		$this->emirates_id_copy->AdvancedSearch->Load();
		$this->passport_number->AdvancedSearch->Load();
		$this->passport_ex_date->AdvancedSearch->Load();
		$this->passport_copy->AdvancedSearch->Load();
		$this->place_of_work->AdvancedSearch->Load();
		$this->qualifications->AdvancedSearch->Load();
		$this->cv->AdvancedSearch->Load();
		$this->home_phone->AdvancedSearch->Load();
		$this->work_phone->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->pobbox->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->total_voluntary_hours->AdvancedSearch->Load();
		$this->overall_evaluation->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->lastUpdatedBy->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->security_approval->AdvancedSearch->Load();
		$this->approvedBy->AdvancedSearch->Load();
		$this->security_comment->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_users\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_users',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fuserslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

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

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetupStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($users_list)) $users_list = new cusers_list();

// Page init
$users_list->Page_Init();

// Page main
$users_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fuserslist = new ew_Form("fuserslist", "list");
fuserslist.FormKeyCountName = '<?php echo $users_list->FormKeyCountName ?>';

// Form_CustomValidate event
fuserslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fuserslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fuserslist.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserslist.Lists["x_admin_approval"].Options = <?php echo json_encode($users_list->admin_approval->Options()) ?>;
fuserslist.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fuserslist.Lists["x_security_approval"].Options = <?php echo json_encode($users_list->security_approval->Options()) ?>;

// Form object for search
var CurrentSearchForm = fuserslistsrch = new ew_Form("fuserslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php  include_once('users_extra.php'); ?>
<script>
</script>
<?php } ?>
<?php if ($users->Export == "") { ?>
<div class="ewToolbar">
<?php if ($users_list->TotalRecs > 0 && $users_list->ExportOptions->Visible()) { ?>
<?php $users_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($users_list->SearchOptions->Visible()) { ?>
<?php $users_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($users_list->FilterOptions->Visible()) { ?>
<?php $users_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $users_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($users_list->TotalRecs <= 0)
			$users_list->TotalRecs = $users->ListRecordCount();
	} else {
		if (!$users_list->Recordset && ($users_list->Recordset = $users_list->LoadRecordset()))
			$users_list->TotalRecs = $users_list->Recordset->RecordCount();
	}
	$users_list->StartRec = 1;
	if ($users_list->DisplayRecs <= 0 || ($users->Export <> "" && $users->ExportAll)) // Display all records
		$users_list->DisplayRecs = $users_list->TotalRecs;
	if (!($users->Export <> "" && $users->ExportAll))
		$users_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$users_list->Recordset = $users_list->LoadRecordset($users_list->StartRec-1, $users_list->DisplayRecs);

	// Set no record found message
	if ($users->CurrentAction == "" && $users_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$users_list->setWarningMessage(ew_DeniedMsg());
		if ($users_list->SearchWhere == "0=101")
			$users_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$users_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($users_list->AuditTrailOnSearch && $users_list->Command == "search" && !$users_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $users_list->getSessionWhere();
		$users_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$users_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($users->Export == "" && $users->CurrentAction == "") { ?>
<form name="fuserslistsrch" id="fuserslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($users_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fuserslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="users">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($users_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($users_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $users_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($users_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($users_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $users_list->ShowPageHeader(); ?>
<?php
$users_list->ShowMessage();
?>
<?php if ($users_list->TotalRecs > 0 || $users->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($users_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> users">
<?php if ($users->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($users->CurrentAction <> "gridadd" && $users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($users_list->Pager)) $users_list->Pager = new cPrevNextPager($users_list->StartRec, $users_list->DisplayRecs, $users_list->TotalRecs, $users_list->AutoHidePager) ?>
<?php if ($users_list->Pager->RecordCount > 0 && $users_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($users_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($users_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $users_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($users_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($users_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $users_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $users_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fuserslist" id="fuserslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<? echo $form; ?>

<?php if ($users_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<div id="gmp_users" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($users_list->TotalRecs > 0 || $users->CurrentAction == "gridedit") { ?>
<table id="tbl_userslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$users_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$users_list->RenderListOptions();

// Render list options (header, left)
$users_list->ListOptions->Render("header", "left");
?>
<?php if ($users->user_id->Visible) { // user_id ?>
	<?php if ($users->SortUrl($users->user_id) == "") { ?>
		<th data-name="user_id" class="<?php echo $users->user_id->HeaderCellClass() ?>"><div id="elh_users_user_id" class="users_user_id"><div class="ewTableHeaderCaption"><?php echo $users->user_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_id" class="<?php echo $users->user_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->user_id) ?>',1);"><div id="elh_users_user_id" class="users_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
	<?php if ($users->SortUrl($users->full_name_ar) == "") { ?>
		<th data-name="full_name_ar" class="<?php echo $users->full_name_ar->HeaderCellClass() ?>"><div id="elh_users_full_name_ar" class="users_full_name_ar"><div class="ewTableHeaderCaption"><?php echo $users->full_name_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="full_name_ar" class="<?php echo $users->full_name_ar->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->full_name_ar) ?>',1);"><div id="elh_users_full_name_ar" class="users_full_name_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->full_name_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->full_name_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->full_name_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
	<?php if ($users->SortUrl($users->full_name_en) == "") { ?>
		<th data-name="full_name_en" class="<?php echo $users->full_name_en->HeaderCellClass() ?>"><div id="elh_users_full_name_en" class="users_full_name_en"><div class="ewTableHeaderCaption"><?php echo $users->full_name_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="full_name_en" class="<?php echo $users->full_name_en->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->full_name_en) ?>',1);"><div id="elh_users_full_name_en" class="users_full_name_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->full_name_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->full_name_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->full_name_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
	<?php if ($users->SortUrl($users->_email) == "") { ?>
		<th data-name="_email" class="<?php echo $users->_email->HeaderCellClass() ?>"><div id="elh_users__email" class="users__email"><div class="ewTableHeaderCaption"><?php echo $users->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email" class="<?php echo $users->_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->_email) ?>',1);"><div id="elh_users__email" class="users__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
	<?php if ($users->SortUrl($users->admin_approval) == "") { ?>
		<th data-name="admin_approval" class="<?php echo $users->admin_approval->HeaderCellClass() ?>"><div id="elh_users_admin_approval" class="users_admin_approval"><div class="ewTableHeaderCaption"><?php echo $users->admin_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_approval" class="<?php echo $users->admin_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->admin_approval) ?>',1);"><div id="elh_users_admin_approval" class="users_admin_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->admin_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->admin_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->admin_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
	<?php if ($users->SortUrl($users->admin_comment) == "") { ?>
		<th data-name="admin_comment" class="<?php echo $users->admin_comment->HeaderCellClass() ?>"><div id="elh_users_admin_comment" class="users_admin_comment"><div class="ewTableHeaderCaption"><?php echo $users->admin_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_comment" class="<?php echo $users->admin_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->admin_comment) ?>',1);"><div id="elh_users_admin_comment" class="users_admin_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->admin_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->admin_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->admin_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->security_approval->Visible) { // security_approval ?>
	<?php if ($users->SortUrl($users->security_approval) == "") { ?>
		<th data-name="security_approval" class="<?php echo $users->security_approval->HeaderCellClass() ?>"><div id="elh_users_security_approval" class="users_security_approval"><div class="ewTableHeaderCaption"><?php echo $users->security_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="security_approval" class="<?php echo $users->security_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->security_approval) ?>',1);"><div id="elh_users_security_approval" class="users_security_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->security_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($users->security_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->security_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
	<?php if ($users->SortUrl($users->security_comment) == "") { ?>
		<th data-name="security_comment" class="<?php echo $users->security_comment->HeaderCellClass() ?>"><div id="elh_users_security_comment" class="users_security_comment"><div class="ewTableHeaderCaption"><?php echo $users->security_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="security_comment" class="<?php echo $users->security_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $users->SortUrl($users->security_comment) ?>',1);"><div id="elh_users_security_comment" class="users_security_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $users->security_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($users->security_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($users->security_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$users_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($users->ExportAll && $users->Export <> "") {
	$users_list->StopRec = $users_list->TotalRecs;
} else {

	// Set the last record to display
	if ($users_list->TotalRecs > $users_list->StartRec + $users_list->DisplayRecs - 1)
		$users_list->StopRec = $users_list->StartRec + $users_list->DisplayRecs - 1;
	else
		$users_list->StopRec = $users_list->TotalRecs;
}
$users_list->RecCnt = $users_list->StartRec - 1;
if ($users_list->Recordset && !$users_list->Recordset->EOF) {
	$users_list->Recordset->MoveFirst();
	$bSelectLimit = $users_list->UseSelectLimit;
	if (!$bSelectLimit && $users_list->StartRec > 1)
		$users_list->Recordset->Move($users_list->StartRec - 1);
} elseif (!$users->AllowAddDeleteRow && $users_list->StopRec == 0) {
	$users_list->StopRec = $users->GridAddRowCount;
}

// Initialize aggregate
$users->RowType = EW_ROWTYPE_AGGREGATEINIT;
$users->ResetAttrs();
$users_list->RenderRow();
while ($users_list->RecCnt < $users_list->StopRec) {
	$users_list->RecCnt++;
	if (intval($users_list->RecCnt) >= intval($users_list->StartRec)) {
		$users_list->RowCnt++;

		// Set up key count
		$users_list->KeyCount = $users_list->RowIndex;

		// Init row class and style
		$users->ResetAttrs();
		$users->CssClass = "";
		if ($users->CurrentAction == "gridadd") {
		} else {
			$users_list->LoadRowValues($users_list->Recordset); // Load row values
		}
		$users->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$users->RowAttrs = array_merge($users->RowAttrs, array('data-rowindex'=>$users_list->RowCnt, 'id'=>'r' . $users_list->RowCnt . '_users', 'data-rowtype'=>$users->RowType));

		// Render row
		$users_list->RenderRow();

		// Render list options
		$users_list->RenderListOptions();
?>
	<tr<?php echo $users->RowAttributes() ?>>
<?php

// Render list options (body, left)
$users_list->ListOptions->Render("body", "left", $users_list->RowCnt);
?>
	<?php if ($users->user_id->Visible) { // user_id ?>
		<td data-name="user_id"<?php echo $users->user_id->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_user_id" class="users_user_id">
<span<?php echo $users->user_id->ViewAttributes() ?>>
<?php echo $users->user_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
		<td data-name="full_name_ar"<?php echo $users->full_name_ar->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_full_name_ar" class="users_full_name_ar">
<span<?php echo $users->full_name_ar->ViewAttributes() ?>>
<?php echo $users->full_name_ar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->full_name_en->Visible) { // full_name_en ?>
		<td data-name="full_name_en"<?php echo $users->full_name_en->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_full_name_en" class="users_full_name_en">
<span<?php echo $users->full_name_en->ViewAttributes() ?>>
<?php echo $users->full_name_en->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $users->_email->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users__email" class="users__email">
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->admin_approval->Visible) { // admin_approval ?>
		<td data-name="admin_approval"<?php echo $users->admin_approval->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_admin_approval" class="users_admin_approval">
<span<?php echo $users->admin_approval->ViewAttributes() ?>>
<?php echo $users->admin_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->admin_comment->Visible) { // admin_comment ?>
		<td data-name="admin_comment"<?php echo $users->admin_comment->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_admin_comment" class="users_admin_comment">
<span<?php echo $users->admin_comment->ViewAttributes() ?>>
<?php echo $users->admin_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->security_approval->Visible) { // security_approval ?>
		<td data-name="security_approval"<?php echo $users->security_approval->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_security_approval" class="users_security_approval">
<span<?php echo $users->security_approval->ViewAttributes() ?>>
<?php echo $users->security_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($users->security_comment->Visible) { // security_comment ?>
		<td data-name="security_comment"<?php echo $users->security_comment->CellAttributes() ?>>
<span id="el<?php echo $users_list->RowCnt ?>_users_security_comment" class="users_security_comment">
<span<?php echo $users->security_comment->ViewAttributes() ?>>
<?php echo $users->security_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$users_list->ListOptions->Render("body", "right", $users_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($users->CurrentAction <> "gridadd")
		$users_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($users->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($users_list->Recordset)
	$users_list->Recordset->Close();
?>
<?php if ($users->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($users->CurrentAction <> "gridadd" && $users->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($users_list->Pager)) $users_list->Pager = new cPrevNextPager($users_list->StartRec, $users_list->DisplayRecs, $users_list->TotalRecs, $users_list->AutoHidePager) ?>
<?php if ($users_list->Pager->RecordCount > 0 && $users_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($users_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($users_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $users_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($users_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($users_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $users_list->PageUrl() ?>start=<?php echo $users_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $users_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $users_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $users_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $users_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($users_list->TotalRecs == 0 && $users->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($users_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">
fuserslistsrch.FilterList = <?php echo $users_list->GetFilterList() ?>;
fuserslistsrch.Init();
fuserslist.Init();
</script>
<?php } ?>
<?php
$users_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$users_list->Page_Terminate();
?>
