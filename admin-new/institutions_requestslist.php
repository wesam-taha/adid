<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "institutions_requestsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$institutions_requests_list = NULL; // Initialize page object first

class cinstitutions_requests_list extends cinstitutions_requests {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions_requests';

	// Page object name
	var $PageObjName = 'institutions_requests_list';

	// Grid form hidden field names
	var $FormName = 'finstitutions_requestslist';
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

		// Table object (institutions_requests)
		if (!isset($GLOBALS["institutions_requests"]) || get_class($GLOBALS["institutions_requests"]) == "cinstitutions_requests") {
			$GLOBALS["institutions_requests"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["institutions_requests"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "institutions_requestsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "institutions_requestsdelete.php";
		$this->MultiUpdateUrl = "institutions_requestsupdate.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'institutions_requests', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption finstitutions_requestslistsrch";

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
		$this->id->SetVisibility();
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->institutions_id->SetVisibility();
		$this->event_name->SetVisibility();
		$this->event_emirate->SetVisibility();
		$this->event_location->SetVisibility();
		$this->activity_start_date->SetVisibility();
		$this->activity_end_date->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();

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
		global $EW_EXPORT, $institutions_requests;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($institutions_requests);
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
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "finstitutions_requestslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->institutions_id->AdvancedSearch->ToJson(), ","); // Field institutions_id
		$sFilterList = ew_Concat($sFilterList, $this->event_name->AdvancedSearch->ToJson(), ","); // Field event_name
		$sFilterList = ew_Concat($sFilterList, $this->event_emirate->AdvancedSearch->ToJson(), ","); // Field event_emirate
		$sFilterList = ew_Concat($sFilterList, $this->event_location->AdvancedSearch->ToJson(), ","); // Field event_location
		$sFilterList = ew_Concat($sFilterList, $this->activity_start_date->AdvancedSearch->ToJson(), ","); // Field activity_start_date
		$sFilterList = ew_Concat($sFilterList, $this->activity_end_date->AdvancedSearch->ToJson(), ","); // Field activity_end_date
		$sFilterList = ew_Concat($sFilterList, $this->activity_time->AdvancedSearch->ToJson(), ","); // Field activity_time
		$sFilterList = ew_Concat($sFilterList, $this->activity_description->AdvancedSearch->ToJson(), ","); // Field activity_description
		$sFilterList = ew_Concat($sFilterList, $this->activity_gender_target->AdvancedSearch->ToJson(), ","); // Field activity_gender_target
		$sFilterList = ew_Concat($sFilterList, $this->no_of_persons_needed->AdvancedSearch->ToJson(), ","); // Field no_of_persons_needed
		$sFilterList = ew_Concat($sFilterList, $this->no_of_hours->AdvancedSearch->ToJson(), ","); // Field no_of_hours
		$sFilterList = ew_Concat($sFilterList, $this->mobile_phone->AdvancedSearch->ToJson(), ","); // Field mobile_phone
		$sFilterList = ew_Concat($sFilterList, $this->pobox->AdvancedSearch->ToJson(), ","); // Field pobox
		$sFilterList = ew_Concat($sFilterList, $this->admin_approval->AdvancedSearch->ToJson(), ","); // Field admin_approval
		$sFilterList = ew_Concat($sFilterList, $this->admin_comment->AdvancedSearch->ToJson(), ","); // Field admin_comment
		$sFilterList = ew_Concat($sFilterList, $this->email->AdvancedSearch->ToJson(), ","); // Field email
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "finstitutions_requestslistsrch", $filters);

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

		// Field id
		$this->id->AdvancedSearch->SearchValue = @$filter["x_id"];
		$this->id->AdvancedSearch->SearchOperator = @$filter["z_id"];
		$this->id->AdvancedSearch->SearchCondition = @$filter["v_id"];
		$this->id->AdvancedSearch->SearchValue2 = @$filter["y_id"];
		$this->id->AdvancedSearch->SearchOperator2 = @$filter["w_id"];
		$this->id->AdvancedSearch->Save();

		// Field institutions_id
		$this->institutions_id->AdvancedSearch->SearchValue = @$filter["x_institutions_id"];
		$this->institutions_id->AdvancedSearch->SearchOperator = @$filter["z_institutions_id"];
		$this->institutions_id->AdvancedSearch->SearchCondition = @$filter["v_institutions_id"];
		$this->institutions_id->AdvancedSearch->SearchValue2 = @$filter["y_institutions_id"];
		$this->institutions_id->AdvancedSearch->SearchOperator2 = @$filter["w_institutions_id"];
		$this->institutions_id->AdvancedSearch->Save();

		// Field event_name
		$this->event_name->AdvancedSearch->SearchValue = @$filter["x_event_name"];
		$this->event_name->AdvancedSearch->SearchOperator = @$filter["z_event_name"];
		$this->event_name->AdvancedSearch->SearchCondition = @$filter["v_event_name"];
		$this->event_name->AdvancedSearch->SearchValue2 = @$filter["y_event_name"];
		$this->event_name->AdvancedSearch->SearchOperator2 = @$filter["w_event_name"];
		$this->event_name->AdvancedSearch->Save();

		// Field event_emirate
		$this->event_emirate->AdvancedSearch->SearchValue = @$filter["x_event_emirate"];
		$this->event_emirate->AdvancedSearch->SearchOperator = @$filter["z_event_emirate"];
		$this->event_emirate->AdvancedSearch->SearchCondition = @$filter["v_event_emirate"];
		$this->event_emirate->AdvancedSearch->SearchValue2 = @$filter["y_event_emirate"];
		$this->event_emirate->AdvancedSearch->SearchOperator2 = @$filter["w_event_emirate"];
		$this->event_emirate->AdvancedSearch->Save();

		// Field event_location
		$this->event_location->AdvancedSearch->SearchValue = @$filter["x_event_location"];
		$this->event_location->AdvancedSearch->SearchOperator = @$filter["z_event_location"];
		$this->event_location->AdvancedSearch->SearchCondition = @$filter["v_event_location"];
		$this->event_location->AdvancedSearch->SearchValue2 = @$filter["y_event_location"];
		$this->event_location->AdvancedSearch->SearchOperator2 = @$filter["w_event_location"];
		$this->event_location->AdvancedSearch->Save();

		// Field activity_start_date
		$this->activity_start_date->AdvancedSearch->SearchValue = @$filter["x_activity_start_date"];
		$this->activity_start_date->AdvancedSearch->SearchOperator = @$filter["z_activity_start_date"];
		$this->activity_start_date->AdvancedSearch->SearchCondition = @$filter["v_activity_start_date"];
		$this->activity_start_date->AdvancedSearch->SearchValue2 = @$filter["y_activity_start_date"];
		$this->activity_start_date->AdvancedSearch->SearchOperator2 = @$filter["w_activity_start_date"];
		$this->activity_start_date->AdvancedSearch->Save();

		// Field activity_end_date
		$this->activity_end_date->AdvancedSearch->SearchValue = @$filter["x_activity_end_date"];
		$this->activity_end_date->AdvancedSearch->SearchOperator = @$filter["z_activity_end_date"];
		$this->activity_end_date->AdvancedSearch->SearchCondition = @$filter["v_activity_end_date"];
		$this->activity_end_date->AdvancedSearch->SearchValue2 = @$filter["y_activity_end_date"];
		$this->activity_end_date->AdvancedSearch->SearchOperator2 = @$filter["w_activity_end_date"];
		$this->activity_end_date->AdvancedSearch->Save();

		// Field activity_time
		$this->activity_time->AdvancedSearch->SearchValue = @$filter["x_activity_time"];
		$this->activity_time->AdvancedSearch->SearchOperator = @$filter["z_activity_time"];
		$this->activity_time->AdvancedSearch->SearchCondition = @$filter["v_activity_time"];
		$this->activity_time->AdvancedSearch->SearchValue2 = @$filter["y_activity_time"];
		$this->activity_time->AdvancedSearch->SearchOperator2 = @$filter["w_activity_time"];
		$this->activity_time->AdvancedSearch->Save();

		// Field activity_description
		$this->activity_description->AdvancedSearch->SearchValue = @$filter["x_activity_description"];
		$this->activity_description->AdvancedSearch->SearchOperator = @$filter["z_activity_description"];
		$this->activity_description->AdvancedSearch->SearchCondition = @$filter["v_activity_description"];
		$this->activity_description->AdvancedSearch->SearchValue2 = @$filter["y_activity_description"];
		$this->activity_description->AdvancedSearch->SearchOperator2 = @$filter["w_activity_description"];
		$this->activity_description->AdvancedSearch->Save();

		// Field activity_gender_target
		$this->activity_gender_target->AdvancedSearch->SearchValue = @$filter["x_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchOperator = @$filter["z_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchCondition = @$filter["v_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchValue2 = @$filter["y_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchOperator2 = @$filter["w_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->Save();

		// Field no_of_persons_needed
		$this->no_of_persons_needed->AdvancedSearch->SearchValue = @$filter["x_no_of_persons_needed"];
		$this->no_of_persons_needed->AdvancedSearch->SearchOperator = @$filter["z_no_of_persons_needed"];
		$this->no_of_persons_needed->AdvancedSearch->SearchCondition = @$filter["v_no_of_persons_needed"];
		$this->no_of_persons_needed->AdvancedSearch->SearchValue2 = @$filter["y_no_of_persons_needed"];
		$this->no_of_persons_needed->AdvancedSearch->SearchOperator2 = @$filter["w_no_of_persons_needed"];
		$this->no_of_persons_needed->AdvancedSearch->Save();

		// Field no_of_hours
		$this->no_of_hours->AdvancedSearch->SearchValue = @$filter["x_no_of_hours"];
		$this->no_of_hours->AdvancedSearch->SearchOperator = @$filter["z_no_of_hours"];
		$this->no_of_hours->AdvancedSearch->SearchCondition = @$filter["v_no_of_hours"];
		$this->no_of_hours->AdvancedSearch->SearchValue2 = @$filter["y_no_of_hours"];
		$this->no_of_hours->AdvancedSearch->SearchOperator2 = @$filter["w_no_of_hours"];
		$this->no_of_hours->AdvancedSearch->Save();

		// Field mobile_phone
		$this->mobile_phone->AdvancedSearch->SearchValue = @$filter["x_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchOperator = @$filter["z_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchCondition = @$filter["v_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchValue2 = @$filter["y_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->SearchOperator2 = @$filter["w_mobile_phone"];
		$this->mobile_phone->AdvancedSearch->Save();

		// Field pobox
		$this->pobox->AdvancedSearch->SearchValue = @$filter["x_pobox"];
		$this->pobox->AdvancedSearch->SearchOperator = @$filter["z_pobox"];
		$this->pobox->AdvancedSearch->SearchCondition = @$filter["v_pobox"];
		$this->pobox->AdvancedSearch->SearchValue2 = @$filter["y_pobox"];
		$this->pobox->AdvancedSearch->SearchOperator2 = @$filter["w_pobox"];
		$this->pobox->AdvancedSearch->Save();

		// Field admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = @$filter["x_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchOperator = @$filter["z_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchCondition = @$filter["v_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchValue2 = @$filter["y_admin_approval"];
		$this->admin_approval->AdvancedSearch->SearchOperator2 = @$filter["w_admin_approval"];
		$this->admin_approval->AdvancedSearch->Save();

		// Field admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = @$filter["x_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchOperator = @$filter["z_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchCondition = @$filter["v_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchValue2 = @$filter["y_admin_comment"];
		$this->admin_comment->AdvancedSearch->SearchOperator2 = @$filter["w_admin_comment"];
		$this->admin_comment->AdvancedSearch->Save();

		// Field email
		$this->email->AdvancedSearch->SearchValue = @$filter["x_email"];
		$this->email->AdvancedSearch->SearchOperator = @$filter["z_email"];
		$this->email->AdvancedSearch->SearchCondition = @$filter["v_email"];
		$this->email->AdvancedSearch->SearchValue2 = @$filter["y_email"];
		$this->email->AdvancedSearch->SearchOperator2 = @$filter["w_email"];
		$this->email->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->institutions_id, $Default, FALSE); // institutions_id
		$this->BuildSearchSql($sWhere, $this->event_name, $Default, FALSE); // event_name
		$this->BuildSearchSql($sWhere, $this->event_emirate, $Default, FALSE); // event_emirate
		$this->BuildSearchSql($sWhere, $this->event_location, $Default, FALSE); // event_location
		$this->BuildSearchSql($sWhere, $this->activity_start_date, $Default, FALSE); // activity_start_date
		$this->BuildSearchSql($sWhere, $this->activity_end_date, $Default, FALSE); // activity_end_date
		$this->BuildSearchSql($sWhere, $this->activity_time, $Default, FALSE); // activity_time
		$this->BuildSearchSql($sWhere, $this->activity_description, $Default, FALSE); // activity_description
		$this->BuildSearchSql($sWhere, $this->activity_gender_target, $Default, FALSE); // activity_gender_target
		$this->BuildSearchSql($sWhere, $this->no_of_persons_needed, $Default, FALSE); // no_of_persons_needed
		$this->BuildSearchSql($sWhere, $this->no_of_hours, $Default, FALSE); // no_of_hours
		$this->BuildSearchSql($sWhere, $this->mobile_phone, $Default, FALSE); // mobile_phone
		$this->BuildSearchSql($sWhere, $this->pobox, $Default, FALSE); // pobox
		$this->BuildSearchSql($sWhere, $this->admin_approval, $Default, FALSE); // admin_approval
		$this->BuildSearchSql($sWhere, $this->admin_comment, $Default, FALSE); // admin_comment
		$this->BuildSearchSql($sWhere, $this->email, $Default, FALSE); // email

		// Set up search parm
		if (!$Default && $sWhere <> "" && $this->Command == "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->institutions_id->AdvancedSearch->Save(); // institutions_id
			$this->event_name->AdvancedSearch->Save(); // event_name
			$this->event_emirate->AdvancedSearch->Save(); // event_emirate
			$this->event_location->AdvancedSearch->Save(); // event_location
			$this->activity_start_date->AdvancedSearch->Save(); // activity_start_date
			$this->activity_end_date->AdvancedSearch->Save(); // activity_end_date
			$this->activity_time->AdvancedSearch->Save(); // activity_time
			$this->activity_description->AdvancedSearch->Save(); // activity_description
			$this->activity_gender_target->AdvancedSearch->Save(); // activity_gender_target
			$this->no_of_persons_needed->AdvancedSearch->Save(); // no_of_persons_needed
			$this->no_of_hours->AdvancedSearch->Save(); // no_of_hours
			$this->mobile_phone->AdvancedSearch->Save(); // mobile_phone
			$this->pobox->AdvancedSearch->Save(); // pobox
			$this->admin_approval->AdvancedSearch->Save(); // admin_approval
			$this->admin_comment->AdvancedSearch->Save(); // admin_comment
			$this->email->AdvancedSearch->Save(); // email
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
		$this->BuildBasicSearchSQL($sWhere, $this->event_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->event_emirate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->event_location, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_time, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_gender_target, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->no_of_persons_needed, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->no_of_hours, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pobox, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->admin_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->email, $arKeywords, $type);
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
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->institutions_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->event_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->event_emirate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->event_location->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_start_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_end_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_time->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_description->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_gender_target->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_of_persons_needed->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_of_hours->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->mobile_phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pobox->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->admin_approval->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->admin_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->email->AdvancedSearch->IssetSession())
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
		$this->id->AdvancedSearch->UnsetSession();
		$this->institutions_id->AdvancedSearch->UnsetSession();
		$this->event_name->AdvancedSearch->UnsetSession();
		$this->event_emirate->AdvancedSearch->UnsetSession();
		$this->event_location->AdvancedSearch->UnsetSession();
		$this->activity_start_date->AdvancedSearch->UnsetSession();
		$this->activity_end_date->AdvancedSearch->UnsetSession();
		$this->activity_time->AdvancedSearch->UnsetSession();
		$this->activity_description->AdvancedSearch->UnsetSession();
		$this->activity_gender_target->AdvancedSearch->UnsetSession();
		$this->no_of_persons_needed->AdvancedSearch->UnsetSession();
		$this->no_of_hours->AdvancedSearch->UnsetSession();
		$this->mobile_phone->AdvancedSearch->UnsetSession();
		$this->pobox->AdvancedSearch->UnsetSession();
		$this->admin_approval->AdvancedSearch->UnsetSession();
		$this->admin_comment->AdvancedSearch->UnsetSession();
		$this->email->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->institutions_id->AdvancedSearch->Load();
		$this->event_name->AdvancedSearch->Load();
		$this->event_emirate->AdvancedSearch->Load();
		$this->event_location->AdvancedSearch->Load();
		$this->activity_start_date->AdvancedSearch->Load();
		$this->activity_end_date->AdvancedSearch->Load();
		$this->activity_time->AdvancedSearch->Load();
		$this->activity_description->AdvancedSearch->Load();
		$this->activity_gender_target->AdvancedSearch->Load();
		$this->no_of_persons_needed->AdvancedSearch->Load();
		$this->no_of_hours->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->pobox->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->email->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->institutions_id); // institutions_id
			$this->UpdateSort($this->event_name); // event_name
			$this->UpdateSort($this->event_emirate); // event_emirate
			$this->UpdateSort($this->event_location); // event_location
			$this->UpdateSort($this->activity_start_date); // activity_start_date
			$this->UpdateSort($this->activity_end_date); // activity_end_date
			$this->UpdateSort($this->admin_approval); // admin_approval
			$this->UpdateSort($this->admin_comment); // admin_comment
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
				$this->id->setSort("DESC");
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
				$this->id->setSort("");
				$this->institutions_id->setSort("");
				$this->event_name->setSort("");
				$this->event_emirate->setSort("");
				$this->event_location->setSort("");
				$this->activity_start_date->setSort("");
				$this->activity_end_date->setSort("");
				$this->admin_approval->setSort("");
				$this->admin_comment->setSort("");
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
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.finstitutions_requestslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"finstitutions_requestslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"finstitutions_requestslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.finstitutions_requestslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"finstitutions_requestslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"institutions_requestssrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"institutions_requests\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'institutions_requestssrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
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
		// id

		$this->id->AdvancedSearch->SearchValue = @$_GET["x_id"];
		if ($this->id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// institutions_id
		$this->institutions_id->AdvancedSearch->SearchValue = @$_GET["x_institutions_id"];
		if ($this->institutions_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->institutions_id->AdvancedSearch->SearchOperator = @$_GET["z_institutions_id"];

		// event_name
		$this->event_name->AdvancedSearch->SearchValue = @$_GET["x_event_name"];
		if ($this->event_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->event_name->AdvancedSearch->SearchOperator = @$_GET["z_event_name"];

		// event_emirate
		$this->event_emirate->AdvancedSearch->SearchValue = @$_GET["x_event_emirate"];
		if ($this->event_emirate->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->event_emirate->AdvancedSearch->SearchOperator = @$_GET["z_event_emirate"];

		// event_location
		$this->event_location->AdvancedSearch->SearchValue = @$_GET["x_event_location"];
		if ($this->event_location->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->event_location->AdvancedSearch->SearchOperator = @$_GET["z_event_location"];

		// activity_start_date
		$this->activity_start_date->AdvancedSearch->SearchValue = @$_GET["x_activity_start_date"];
		if ($this->activity_start_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_start_date->AdvancedSearch->SearchOperator = @$_GET["z_activity_start_date"];

		// activity_end_date
		$this->activity_end_date->AdvancedSearch->SearchValue = @$_GET["x_activity_end_date"];
		if ($this->activity_end_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_end_date->AdvancedSearch->SearchOperator = @$_GET["z_activity_end_date"];

		// activity_time
		$this->activity_time->AdvancedSearch->SearchValue = @$_GET["x_activity_time"];
		if ($this->activity_time->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_time->AdvancedSearch->SearchOperator = @$_GET["z_activity_time"];

		// activity_description
		$this->activity_description->AdvancedSearch->SearchValue = @$_GET["x_activity_description"];
		if ($this->activity_description->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_description->AdvancedSearch->SearchOperator = @$_GET["z_activity_description"];

		// activity_gender_target
		$this->activity_gender_target->AdvancedSearch->SearchValue = @$_GET["x_activity_gender_target"];
		if ($this->activity_gender_target->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_gender_target->AdvancedSearch->SearchOperator = @$_GET["z_activity_gender_target"];

		// no_of_persons_needed
		$this->no_of_persons_needed->AdvancedSearch->SearchValue = @$_GET["x_no_of_persons_needed"];
		if ($this->no_of_persons_needed->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->no_of_persons_needed->AdvancedSearch->SearchOperator = @$_GET["z_no_of_persons_needed"];

		// no_of_hours
		$this->no_of_hours->AdvancedSearch->SearchValue = @$_GET["x_no_of_hours"];
		if ($this->no_of_hours->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->no_of_hours->AdvancedSearch->SearchOperator = @$_GET["z_no_of_hours"];

		// mobile_phone
		$this->mobile_phone->AdvancedSearch->SearchValue = @$_GET["x_mobile_phone"];
		if ($this->mobile_phone->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->mobile_phone->AdvancedSearch->SearchOperator = @$_GET["z_mobile_phone"];

		// pobox
		$this->pobox->AdvancedSearch->SearchValue = @$_GET["x_pobox"];
		if ($this->pobox->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->pobox->AdvancedSearch->SearchOperator = @$_GET["z_pobox"];

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = @$_GET["x_admin_approval"];
		if ($this->admin_approval->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->admin_approval->AdvancedSearch->SearchOperator = @$_GET["z_admin_approval"];

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = @$_GET["x_admin_comment"];
		if ($this->admin_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->admin_comment->AdvancedSearch->SearchOperator = @$_GET["z_admin_comment"];

		// email
		$this->email->AdvancedSearch->SearchValue = @$_GET["x_email"];
		if ($this->email->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->email->AdvancedSearch->SearchOperator = @$_GET["z_email"];
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
		$this->id->setDbValue($row['id']);
		$this->institutions_id->setDbValue($row['institutions_id']);
		$this->event_name->setDbValue($row['event_name']);
		$this->event_emirate->setDbValue($row['event_emirate']);
		$this->event_location->setDbValue($row['event_location']);
		$this->activity_start_date->setDbValue($row['activity_start_date']);
		$this->activity_end_date->setDbValue($row['activity_end_date']);
		$this->activity_time->setDbValue($row['activity_time']);
		$this->activity_description->setDbValue($row['activity_description']);
		$this->activity_gender_target->setDbValue($row['activity_gender_target']);
		$this->no_of_persons_needed->setDbValue($row['no_of_persons_needed']);
		$this->no_of_hours->setDbValue($row['no_of_hours']);
		$this->mobile_phone->setDbValue($row['mobile_phone']);
		$this->pobox->setDbValue($row['pobox']);
		$this->admin_approval->setDbValue($row['admin_approval']);
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->email->setDbValue($row['email']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['institutions_id'] = NULL;
		$row['event_name'] = NULL;
		$row['event_emirate'] = NULL;
		$row['event_location'] = NULL;
		$row['activity_start_date'] = NULL;
		$row['activity_end_date'] = NULL;
		$row['activity_time'] = NULL;
		$row['activity_description'] = NULL;
		$row['activity_gender_target'] = NULL;
		$row['no_of_persons_needed'] = NULL;
		$row['no_of_hours'] = NULL;
		$row['mobile_phone'] = NULL;
		$row['pobox'] = NULL;
		$row['admin_approval'] = NULL;
		$row['admin_comment'] = NULL;
		$row['email'] = NULL;
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
		$this->institutions_id->DbValue = $row['institutions_id'];
		$this->event_name->DbValue = $row['event_name'];
		$this->event_emirate->DbValue = $row['event_emirate'];
		$this->event_location->DbValue = $row['event_location'];
		$this->activity_start_date->DbValue = $row['activity_start_date'];
		$this->activity_end_date->DbValue = $row['activity_end_date'];
		$this->activity_time->DbValue = $row['activity_time'];
		$this->activity_description->DbValue = $row['activity_description'];
		$this->activity_gender_target->DbValue = $row['activity_gender_target'];
		$this->no_of_persons_needed->DbValue = $row['no_of_persons_needed'];
		$this->no_of_hours->DbValue = $row['no_of_hours'];
		$this->mobile_phone->DbValue = $row['mobile_phone'];
		$this->pobox->DbValue = $row['pobox'];
		$this->admin_approval->DbValue = $row['admin_approval'];
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->email->DbValue = $row['email'];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// institutions_id
		// event_name
		// event_emirate
		// event_location
		// activity_start_date
		// activity_end_date
		// activity_time
		// activity_description
		// activity_gender_target
		// no_of_persons_needed
		// no_of_hours
		// mobile_phone
		// pobox
		// admin_approval
		// admin_comment
		// email

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// institutions_id
		if (strval($this->institutions_id->CurrentValue) <> "") {
			$sFilterWrk = "`institution_id`" . ew_SearchString("=", $this->institutions_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `institution_id`, `institutes_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `institutions`";
		$sWhereWrk = "";
		$this->institutions_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->institutions_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->institutions_id->ViewValue = $this->institutions_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->institutions_id->ViewValue = $this->institutions_id->CurrentValue;
			}
		} else {
			$this->institutions_id->ViewValue = NULL;
		}
		$this->institutions_id->ViewCustomAttributes = "";

		// event_name
		$this->event_name->ViewValue = $this->event_name->CurrentValue;
		$this->event_name->ViewCustomAttributes = "";

		// event_emirate
		if (strval($this->event_emirate->CurrentValue) <> "") {
			$this->event_emirate->ViewValue = $this->event_emirate->OptionCaption($this->event_emirate->CurrentValue);
		} else {
			$this->event_emirate->ViewValue = NULL;
		}
		$this->event_emirate->ViewCustomAttributes = "";

		// event_location
		$this->event_location->ViewValue = $this->event_location->CurrentValue;
		$this->event_location->ViewCustomAttributes = "";

		// activity_start_date
		$this->activity_start_date->ViewValue = $this->activity_start_date->CurrentValue;
		$this->activity_start_date->ViewValue = ew_FormatDateTime($this->activity_start_date->ViewValue, 0);
		$this->activity_start_date->ViewCustomAttributes = "";

		// activity_end_date
		$this->activity_end_date->ViewValue = $this->activity_end_date->CurrentValue;
		$this->activity_end_date->ViewValue = ew_FormatDateTime($this->activity_end_date->ViewValue, 0);
		$this->activity_end_date->ViewCustomAttributes = "";

		// activity_time
		$this->activity_time->ViewValue = $this->activity_time->CurrentValue;
		$this->activity_time->ViewCustomAttributes = "";

		// activity_description
		$this->activity_description->ViewValue = $this->activity_description->CurrentValue;
		$this->activity_description->ViewCustomAttributes = "";

		// activity_gender_target
		if (strval($this->activity_gender_target->CurrentValue) <> "") {
			$this->activity_gender_target->ViewValue = $this->activity_gender_target->OptionCaption($this->activity_gender_target->CurrentValue);
		} else {
			$this->activity_gender_target->ViewValue = NULL;
		}
		$this->activity_gender_target->ViewCustomAttributes = "";

		// no_of_persons_needed
		$this->no_of_persons_needed->ViewValue = $this->no_of_persons_needed->CurrentValue;
		$this->no_of_persons_needed->ViewCustomAttributes = "";

		// no_of_hours
		$this->no_of_hours->ViewValue = $this->no_of_hours->CurrentValue;
		$this->no_of_hours->ViewCustomAttributes = "";

		// mobile_phone
		$this->mobile_phone->ViewValue = $this->mobile_phone->CurrentValue;
		$this->mobile_phone->ViewCustomAttributes = "";

		// pobox
		$this->pobox->ViewValue = $this->pobox->CurrentValue;
		$this->pobox->ViewCustomAttributes = "";

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

		// email
		$this->email->ViewValue = $this->email->CurrentValue;
		$this->email->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// institutions_id
			$this->institutions_id->LinkCustomAttributes = "";
			$this->institutions_id->HrefValue = "";
			$this->institutions_id->TooltipValue = "";

			// event_name
			$this->event_name->LinkCustomAttributes = "";
			$this->event_name->HrefValue = "";
			$this->event_name->TooltipValue = "";

			// event_emirate
			$this->event_emirate->LinkCustomAttributes = "";
			$this->event_emirate->HrefValue = "";
			$this->event_emirate->TooltipValue = "";

			// event_location
			$this->event_location->LinkCustomAttributes = "";
			$this->event_location->HrefValue = "";
			$this->event_location->TooltipValue = "";

			// activity_start_date
			$this->activity_start_date->LinkCustomAttributes = "";
			$this->activity_start_date->HrefValue = "";
			$this->activity_start_date->TooltipValue = "";

			// activity_end_date
			$this->activity_end_date->LinkCustomAttributes = "";
			$this->activity_end_date->HrefValue = "";
			$this->activity_end_date->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";
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
		$this->id->AdvancedSearch->Load();
		$this->institutions_id->AdvancedSearch->Load();
		$this->event_name->AdvancedSearch->Load();
		$this->event_emirate->AdvancedSearch->Load();
		$this->event_location->AdvancedSearch->Load();
		$this->activity_start_date->AdvancedSearch->Load();
		$this->activity_end_date->AdvancedSearch->Load();
		$this->activity_time->AdvancedSearch->Load();
		$this->activity_description->AdvancedSearch->Load();
		$this->activity_gender_target->AdvancedSearch->Load();
		$this->no_of_persons_needed->AdvancedSearch->Load();
		$this->no_of_hours->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->pobox->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->email->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_institutions_requests\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_institutions_requests',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finstitutions_requestslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($institutions_requests_list)) $institutions_requests_list = new cinstitutions_requests_list();

// Page init
$institutions_requests_list->Page_Init();

// Page main
$institutions_requests_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_requests_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($institutions_requests->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = finstitutions_requestslist = new ew_Form("finstitutions_requestslist", "list");
finstitutions_requestslist.FormKeyCountName = '<?php echo $institutions_requests_list->FormKeyCountName ?>';

// Form_CustomValidate event
finstitutions_requestslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutions_requestslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstitutions_requestslist.Lists["x_institutions_id"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
finstitutions_requestslist.Lists["x_institutions_id"].Data = "<?php echo $institutions_requests_list->institutions_id->LookupFilterQuery(FALSE, "list") ?>";
finstitutions_requestslist.Lists["x_event_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestslist.Lists["x_event_emirate"].Options = <?php echo json_encode($institutions_requests_list->event_emirate->Options()) ?>;
finstitutions_requestslist.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutions_requestslist.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_requests_list->admin_approval->Options()) ?>;

// Form object for search
var CurrentSearchForm = finstitutions_requestslistsrch = new ew_Form("finstitutions_requestslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($institutions_requests->Export == "") { ?>
<div class="ewToolbar">
<?php if ($institutions_requests_list->TotalRecs > 0 && $institutions_requests_list->ExportOptions->Visible()) { ?>
<?php $institutions_requests_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($institutions_requests_list->SearchOptions->Visible()) { ?>
<?php $institutions_requests_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($institutions_requests_list->FilterOptions->Visible()) { ?>
<?php $institutions_requests_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $institutions_requests_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($institutions_requests_list->TotalRecs <= 0)
			$institutions_requests_list->TotalRecs = $institutions_requests->ListRecordCount();
	} else {
		if (!$institutions_requests_list->Recordset && ($institutions_requests_list->Recordset = $institutions_requests_list->LoadRecordset()))
			$institutions_requests_list->TotalRecs = $institutions_requests_list->Recordset->RecordCount();
	}
	$institutions_requests_list->StartRec = 1;
	if ($institutions_requests_list->DisplayRecs <= 0 || ($institutions_requests->Export <> "" && $institutions_requests->ExportAll)) // Display all records
		$institutions_requests_list->DisplayRecs = $institutions_requests_list->TotalRecs;
	if (!($institutions_requests->Export <> "" && $institutions_requests->ExportAll))
		$institutions_requests_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$institutions_requests_list->Recordset = $institutions_requests_list->LoadRecordset($institutions_requests_list->StartRec-1, $institutions_requests_list->DisplayRecs);

	// Set no record found message
	if ($institutions_requests->CurrentAction == "" && $institutions_requests_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$institutions_requests_list->setWarningMessage(ew_DeniedMsg());
		if ($institutions_requests_list->SearchWhere == "0=101")
			$institutions_requests_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$institutions_requests_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($institutions_requests_list->AuditTrailOnSearch && $institutions_requests_list->Command == "search" && !$institutions_requests_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $institutions_requests_list->getSessionWhere();
		$institutions_requests_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$institutions_requests_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($institutions_requests->Export == "" && $institutions_requests->CurrentAction == "") { ?>
<form name="finstitutions_requestslistsrch" id="finstitutions_requestslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($institutions_requests_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="finstitutions_requestslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="institutions_requests">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($institutions_requests_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($institutions_requests_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $institutions_requests_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($institutions_requests_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($institutions_requests_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($institutions_requests_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($institutions_requests_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $institutions_requests_list->ShowPageHeader(); ?>
<?php
$institutions_requests_list->ShowMessage();
?>
<?php if ($institutions_requests_list->TotalRecs > 0 || $institutions_requests->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($institutions_requests_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> institutions_requests">
<?php if ($institutions_requests->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($institutions_requests->CurrentAction <> "gridadd" && $institutions_requests->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($institutions_requests_list->Pager)) $institutions_requests_list->Pager = new cPrevNextPager($institutions_requests_list->StartRec, $institutions_requests_list->DisplayRecs, $institutions_requests_list->TotalRecs, $institutions_requests_list->AutoHidePager) ?>
<?php if ($institutions_requests_list->Pager->RecordCount > 0 && $institutions_requests_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($institutions_requests_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($institutions_requests_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $institutions_requests_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($institutions_requests_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($institutions_requests_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $institutions_requests_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $institutions_requests_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $institutions_requests_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $institutions_requests_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($institutions_requests_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="finstitutions_requestslist" id="finstitutions_requestslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($institutions_requests_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_requests_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions_requests">
<div id="gmp_institutions_requests" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($institutions_requests_list->TotalRecs > 0 || $institutions_requests->CurrentAction == "gridedit") { ?>
<table id="tbl_institutions_requestslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$institutions_requests_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$institutions_requests_list->RenderListOptions();

// Render list options (header, left)
$institutions_requests_list->ListOptions->Render("header", "left");
?>
<?php if ($institutions_requests->id->Visible) { // id ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->id) == "") { ?>
		<th data-name="id" class="<?php echo $institutions_requests->id->HeaderCellClass() ?>"><div id="elh_institutions_requests_id" class="institutions_requests_id"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $institutions_requests->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->id) ?>',1);"><div id="elh_institutions_requests_id" class="institutions_requests_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->institutions_id->Visible) { // institutions_id ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->institutions_id) == "") { ?>
		<th data-name="institutions_id" class="<?php echo $institutions_requests->institutions_id->HeaderCellClass() ?>"><div id="elh_institutions_requests_institutions_id" class="institutions_requests_institutions_id"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->institutions_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="institutions_id" class="<?php echo $institutions_requests->institutions_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->institutions_id) ?>',1);"><div id="elh_institutions_requests_institutions_id" class="institutions_requests_institutions_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->institutions_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->institutions_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->institutions_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_name->Visible) { // event_name ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->event_name) == "") { ?>
		<th data-name="event_name" class="<?php echo $institutions_requests->event_name->HeaderCellClass() ?>"><div id="elh_institutions_requests_event_name" class="institutions_requests_event_name"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->event_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="event_name" class="<?php echo $institutions_requests->event_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->event_name) ?>',1);"><div id="elh_institutions_requests_event_name" class="institutions_requests_event_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->event_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->event_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->event_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_emirate->Visible) { // event_emirate ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->event_emirate) == "") { ?>
		<th data-name="event_emirate" class="<?php echo $institutions_requests->event_emirate->HeaderCellClass() ?>"><div id="elh_institutions_requests_event_emirate" class="institutions_requests_event_emirate"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->event_emirate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="event_emirate" class="<?php echo $institutions_requests->event_emirate->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->event_emirate) ?>',1);"><div id="elh_institutions_requests_event_emirate" class="institutions_requests_event_emirate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->event_emirate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->event_emirate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->event_emirate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->event_location->Visible) { // event_location ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->event_location) == "") { ?>
		<th data-name="event_location" class="<?php echo $institutions_requests->event_location->HeaderCellClass() ?>"><div id="elh_institutions_requests_event_location" class="institutions_requests_event_location"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->event_location->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="event_location" class="<?php echo $institutions_requests->event_location->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->event_location) ?>',1);"><div id="elh_institutions_requests_event_location" class="institutions_requests_event_location">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->event_location->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->event_location->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->event_location->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_start_date->Visible) { // activity_start_date ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->activity_start_date) == "") { ?>
		<th data-name="activity_start_date" class="<?php echo $institutions_requests->activity_start_date->HeaderCellClass() ?>"><div id="elh_institutions_requests_activity_start_date" class="institutions_requests_activity_start_date"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_start_date" class="<?php echo $institutions_requests->activity_start_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->activity_start_date) ?>',1);"><div id="elh_institutions_requests_activity_start_date" class="institutions_requests_activity_start_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->activity_start_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->activity_start_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->activity_start_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->activity_end_date->Visible) { // activity_end_date ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->activity_end_date) == "") { ?>
		<th data-name="activity_end_date" class="<?php echo $institutions_requests->activity_end_date->HeaderCellClass() ?>"><div id="elh_institutions_requests_activity_end_date" class="institutions_requests_activity_end_date"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_end_date" class="<?php echo $institutions_requests->activity_end_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->activity_end_date) ?>',1);"><div id="elh_institutions_requests_activity_end_date" class="institutions_requests_activity_end_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->activity_end_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->activity_end_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->activity_end_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->admin_approval->Visible) { // admin_approval ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->admin_approval) == "") { ?>
		<th data-name="admin_approval" class="<?php echo $institutions_requests->admin_approval->HeaderCellClass() ?>"><div id="elh_institutions_requests_admin_approval" class="institutions_requests_admin_approval"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->admin_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_approval" class="<?php echo $institutions_requests->admin_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->admin_approval) ?>',1);"><div id="elh_institutions_requests_admin_approval" class="institutions_requests_admin_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->admin_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->admin_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->admin_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions_requests->admin_comment->Visible) { // admin_comment ?>
	<?php if ($institutions_requests->SortUrl($institutions_requests->admin_comment) == "") { ?>
		<th data-name="admin_comment" class="<?php echo $institutions_requests->admin_comment->HeaderCellClass() ?>"><div id="elh_institutions_requests_admin_comment" class="institutions_requests_admin_comment"><div class="ewTableHeaderCaption"><?php echo $institutions_requests->admin_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_comment" class="<?php echo $institutions_requests->admin_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions_requests->SortUrl($institutions_requests->admin_comment) ?>',1);"><div id="elh_institutions_requests_admin_comment" class="institutions_requests_admin_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions_requests->admin_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions_requests->admin_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions_requests->admin_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$institutions_requests_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($institutions_requests->ExportAll && $institutions_requests->Export <> "") {
	$institutions_requests_list->StopRec = $institutions_requests_list->TotalRecs;
} else {

	// Set the last record to display
	if ($institutions_requests_list->TotalRecs > $institutions_requests_list->StartRec + $institutions_requests_list->DisplayRecs - 1)
		$institutions_requests_list->StopRec = $institutions_requests_list->StartRec + $institutions_requests_list->DisplayRecs - 1;
	else
		$institutions_requests_list->StopRec = $institutions_requests_list->TotalRecs;
}
$institutions_requests_list->RecCnt = $institutions_requests_list->StartRec - 1;
if ($institutions_requests_list->Recordset && !$institutions_requests_list->Recordset->EOF) {
	$institutions_requests_list->Recordset->MoveFirst();
	$bSelectLimit = $institutions_requests_list->UseSelectLimit;
	if (!$bSelectLimit && $institutions_requests_list->StartRec > 1)
		$institutions_requests_list->Recordset->Move($institutions_requests_list->StartRec - 1);
} elseif (!$institutions_requests->AllowAddDeleteRow && $institutions_requests_list->StopRec == 0) {
	$institutions_requests_list->StopRec = $institutions_requests->GridAddRowCount;
}

// Initialize aggregate
$institutions_requests->RowType = EW_ROWTYPE_AGGREGATEINIT;
$institutions_requests->ResetAttrs();
$institutions_requests_list->RenderRow();
while ($institutions_requests_list->RecCnt < $institutions_requests_list->StopRec) {
	$institutions_requests_list->RecCnt++;
	if (intval($institutions_requests_list->RecCnt) >= intval($institutions_requests_list->StartRec)) {
		$institutions_requests_list->RowCnt++;

		// Set up key count
		$institutions_requests_list->KeyCount = $institutions_requests_list->RowIndex;

		// Init row class and style
		$institutions_requests->ResetAttrs();
		$institutions_requests->CssClass = "";
		if ($institutions_requests->CurrentAction == "gridadd") {
		} else {
			$institutions_requests_list->LoadRowValues($institutions_requests_list->Recordset); // Load row values
		}
		$institutions_requests->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$institutions_requests->RowAttrs = array_merge($institutions_requests->RowAttrs, array('data-rowindex'=>$institutions_requests_list->RowCnt, 'id'=>'r' . $institutions_requests_list->RowCnt . '_institutions_requests', 'data-rowtype'=>$institutions_requests->RowType));

		// Render row
		$institutions_requests_list->RenderRow();

		// Render list options
		$institutions_requests_list->RenderListOptions();
?>
	<tr<?php echo $institutions_requests->RowAttributes() ?>>
<?php

// Render list options (body, left)
$institutions_requests_list->ListOptions->Render("body", "left", $institutions_requests_list->RowCnt);
?>
	<?php if ($institutions_requests->id->Visible) { // id ?>
		<td data-name="id"<?php echo $institutions_requests->id->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_id" class="institutions_requests_id">
<span<?php echo $institutions_requests->id->ViewAttributes() ?>>
<?php echo $institutions_requests->id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->institutions_id->Visible) { // institutions_id ?>
		<td data-name="institutions_id"<?php echo $institutions_requests->institutions_id->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_institutions_id" class="institutions_requests_institutions_id">
<span<?php echo $institutions_requests->institutions_id->ViewAttributes() ?>>
<?php echo $institutions_requests->institutions_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->event_name->Visible) { // event_name ?>
		<td data-name="event_name"<?php echo $institutions_requests->event_name->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_event_name" class="institutions_requests_event_name">
<span<?php echo $institutions_requests->event_name->ViewAttributes() ?>>
<?php echo $institutions_requests->event_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->event_emirate->Visible) { // event_emirate ?>
		<td data-name="event_emirate"<?php echo $institutions_requests->event_emirate->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_event_emirate" class="institutions_requests_event_emirate">
<span<?php echo $institutions_requests->event_emirate->ViewAttributes() ?>>
<?php echo $institutions_requests->event_emirate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->event_location->Visible) { // event_location ?>
		<td data-name="event_location"<?php echo $institutions_requests->event_location->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_event_location" class="institutions_requests_event_location">
<span<?php echo $institutions_requests->event_location->ViewAttributes() ?>>
<?php echo $institutions_requests->event_location->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->activity_start_date->Visible) { // activity_start_date ?>
		<td data-name="activity_start_date"<?php echo $institutions_requests->activity_start_date->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_activity_start_date" class="institutions_requests_activity_start_date">
<span<?php echo $institutions_requests->activity_start_date->ViewAttributes() ?>>
<?php echo $institutions_requests->activity_start_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->activity_end_date->Visible) { // activity_end_date ?>
		<td data-name="activity_end_date"<?php echo $institutions_requests->activity_end_date->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_activity_end_date" class="institutions_requests_activity_end_date">
<span<?php echo $institutions_requests->activity_end_date->ViewAttributes() ?>>
<?php echo $institutions_requests->activity_end_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->admin_approval->Visible) { // admin_approval ?>
		<td data-name="admin_approval"<?php echo $institutions_requests->admin_approval->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_admin_approval" class="institutions_requests_admin_approval">
<span<?php echo $institutions_requests->admin_approval->ViewAttributes() ?>>
<?php echo $institutions_requests->admin_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions_requests->admin_comment->Visible) { // admin_comment ?>
		<td data-name="admin_comment"<?php echo $institutions_requests->admin_comment->CellAttributes() ?>>
<span id="el<?php echo $institutions_requests_list->RowCnt ?>_institutions_requests_admin_comment" class="institutions_requests_admin_comment">
<span<?php echo $institutions_requests->admin_comment->ViewAttributes() ?>>
<?php echo $institutions_requests->admin_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$institutions_requests_list->ListOptions->Render("body", "right", $institutions_requests_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($institutions_requests->CurrentAction <> "gridadd")
		$institutions_requests_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($institutions_requests->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($institutions_requests_list->Recordset)
	$institutions_requests_list->Recordset->Close();
?>
<?php if ($institutions_requests->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($institutions_requests->CurrentAction <> "gridadd" && $institutions_requests->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($institutions_requests_list->Pager)) $institutions_requests_list->Pager = new cPrevNextPager($institutions_requests_list->StartRec, $institutions_requests_list->DisplayRecs, $institutions_requests_list->TotalRecs, $institutions_requests_list->AutoHidePager) ?>
<?php if ($institutions_requests_list->Pager->RecordCount > 0 && $institutions_requests_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($institutions_requests_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($institutions_requests_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $institutions_requests_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($institutions_requests_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($institutions_requests_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $institutions_requests_list->PageUrl() ?>start=<?php echo $institutions_requests_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $institutions_requests_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $institutions_requests_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $institutions_requests_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $institutions_requests_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($institutions_requests_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($institutions_requests_list->TotalRecs == 0 && $institutions_requests->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($institutions_requests_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($institutions_requests->Export == "") { ?>
<script type="text/javascript">
finstitutions_requestslistsrch.FilterList = <?php echo $institutions_requests_list->GetFilterList() ?>;
finstitutions_requestslistsrch.Init();
finstitutions_requestslist.Init();
</script>
<?php } ?>
<?php
$institutions_requests_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($institutions_requests->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$institutions_requests_list->Page_Terminate();
?>
