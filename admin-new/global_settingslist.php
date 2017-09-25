<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "global_settingsinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$global_settings_list = NULL; // Initialize page object first

class cglobal_settings_list extends cglobal_settings {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'global_settings';

	// Page object name
	var $PageObjName = 'global_settings_list';

	// Grid form hidden field names
	var $FormName = 'fglobal_settingslist';
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

		// Table object (global_settings)
		if (!isset($GLOBALS["global_settings"]) || get_class($GLOBALS["global_settings"]) == "cglobal_settings") {
			$GLOBALS["global_settings"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["global_settings"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "global_settingsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "global_settingsdelete.php";
		$this->MultiUpdateUrl = "global_settingsupdate.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'global_settings', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fglobal_settingslistsrch";

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
		$this->global_id->SetVisibility();
		$this->global_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->system_name_ar->SetVisibility();
		$this->system_name_en->SetVisibility();
		$this->contact_email->SetVisibility();
		$this->system_logo->SetVisibility();

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
		global $EW_EXPORT, $global_settings;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($global_settings);
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
			$this->global_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->global_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fglobal_settingslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->global_id->AdvancedSearch->ToJson(), ","); // Field global_id
		$sFilterList = ew_Concat($sFilterList, $this->system_name_ar->AdvancedSearch->ToJson(), ","); // Field system_name_ar
		$sFilterList = ew_Concat($sFilterList, $this->system_name_en->AdvancedSearch->ToJson(), ","); // Field system_name_en
		$sFilterList = ew_Concat($sFilterList, $this->contact_email->AdvancedSearch->ToJson(), ","); // Field contact_email
		$sFilterList = ew_Concat($sFilterList, $this->system_logo->AdvancedSearch->ToJson(), ","); // Field system_logo
		$sFilterList = ew_Concat($sFilterList, $this->contact_info_ar->AdvancedSearch->ToJson(), ","); // Field contact_info_ar
		$sFilterList = ew_Concat($sFilterList, $this->contact_info_en->AdvancedSearch->ToJson(), ","); // Field contact_info_en
		$sFilterList = ew_Concat($sFilterList, $this->about_us_ar->AdvancedSearch->ToJson(), ","); // Field about_us_ar
		$sFilterList = ew_Concat($sFilterList, $this->about_us_en->AdvancedSearch->ToJson(), ","); // Field about_us_en
		$sFilterList = ew_Concat($sFilterList, $this->twiiter->AdvancedSearch->ToJson(), ","); // Field twiiter
		$sFilterList = ew_Concat($sFilterList, $this->facebook->AdvancedSearch->ToJson(), ","); // Field facebook
		$sFilterList = ew_Concat($sFilterList, $this->instagram->AdvancedSearch->ToJson(), ","); // Field instagram
		$sFilterList = ew_Concat($sFilterList, $this->youtube->AdvancedSearch->ToJson(), ","); // Field youtube
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fglobal_settingslistsrch", $filters);

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

		// Field global_id
		$this->global_id->AdvancedSearch->SearchValue = @$filter["x_global_id"];
		$this->global_id->AdvancedSearch->SearchOperator = @$filter["z_global_id"];
		$this->global_id->AdvancedSearch->SearchCondition = @$filter["v_global_id"];
		$this->global_id->AdvancedSearch->SearchValue2 = @$filter["y_global_id"];
		$this->global_id->AdvancedSearch->SearchOperator2 = @$filter["w_global_id"];
		$this->global_id->AdvancedSearch->Save();

		// Field system_name_ar
		$this->system_name_ar->AdvancedSearch->SearchValue = @$filter["x_system_name_ar"];
		$this->system_name_ar->AdvancedSearch->SearchOperator = @$filter["z_system_name_ar"];
		$this->system_name_ar->AdvancedSearch->SearchCondition = @$filter["v_system_name_ar"];
		$this->system_name_ar->AdvancedSearch->SearchValue2 = @$filter["y_system_name_ar"];
		$this->system_name_ar->AdvancedSearch->SearchOperator2 = @$filter["w_system_name_ar"];
		$this->system_name_ar->AdvancedSearch->Save();

		// Field system_name_en
		$this->system_name_en->AdvancedSearch->SearchValue = @$filter["x_system_name_en"];
		$this->system_name_en->AdvancedSearch->SearchOperator = @$filter["z_system_name_en"];
		$this->system_name_en->AdvancedSearch->SearchCondition = @$filter["v_system_name_en"];
		$this->system_name_en->AdvancedSearch->SearchValue2 = @$filter["y_system_name_en"];
		$this->system_name_en->AdvancedSearch->SearchOperator2 = @$filter["w_system_name_en"];
		$this->system_name_en->AdvancedSearch->Save();

		// Field contact_email
		$this->contact_email->AdvancedSearch->SearchValue = @$filter["x_contact_email"];
		$this->contact_email->AdvancedSearch->SearchOperator = @$filter["z_contact_email"];
		$this->contact_email->AdvancedSearch->SearchCondition = @$filter["v_contact_email"];
		$this->contact_email->AdvancedSearch->SearchValue2 = @$filter["y_contact_email"];
		$this->contact_email->AdvancedSearch->SearchOperator2 = @$filter["w_contact_email"];
		$this->contact_email->AdvancedSearch->Save();

		// Field system_logo
		$this->system_logo->AdvancedSearch->SearchValue = @$filter["x_system_logo"];
		$this->system_logo->AdvancedSearch->SearchOperator = @$filter["z_system_logo"];
		$this->system_logo->AdvancedSearch->SearchCondition = @$filter["v_system_logo"];
		$this->system_logo->AdvancedSearch->SearchValue2 = @$filter["y_system_logo"];
		$this->system_logo->AdvancedSearch->SearchOperator2 = @$filter["w_system_logo"];
		$this->system_logo->AdvancedSearch->Save();

		// Field contact_info_ar
		$this->contact_info_ar->AdvancedSearch->SearchValue = @$filter["x_contact_info_ar"];
		$this->contact_info_ar->AdvancedSearch->SearchOperator = @$filter["z_contact_info_ar"];
		$this->contact_info_ar->AdvancedSearch->SearchCondition = @$filter["v_contact_info_ar"];
		$this->contact_info_ar->AdvancedSearch->SearchValue2 = @$filter["y_contact_info_ar"];
		$this->contact_info_ar->AdvancedSearch->SearchOperator2 = @$filter["w_contact_info_ar"];
		$this->contact_info_ar->AdvancedSearch->Save();

		// Field contact_info_en
		$this->contact_info_en->AdvancedSearch->SearchValue = @$filter["x_contact_info_en"];
		$this->contact_info_en->AdvancedSearch->SearchOperator = @$filter["z_contact_info_en"];
		$this->contact_info_en->AdvancedSearch->SearchCondition = @$filter["v_contact_info_en"];
		$this->contact_info_en->AdvancedSearch->SearchValue2 = @$filter["y_contact_info_en"];
		$this->contact_info_en->AdvancedSearch->SearchOperator2 = @$filter["w_contact_info_en"];
		$this->contact_info_en->AdvancedSearch->Save();

		// Field about_us_ar
		$this->about_us_ar->AdvancedSearch->SearchValue = @$filter["x_about_us_ar"];
		$this->about_us_ar->AdvancedSearch->SearchOperator = @$filter["z_about_us_ar"];
		$this->about_us_ar->AdvancedSearch->SearchCondition = @$filter["v_about_us_ar"];
		$this->about_us_ar->AdvancedSearch->SearchValue2 = @$filter["y_about_us_ar"];
		$this->about_us_ar->AdvancedSearch->SearchOperator2 = @$filter["w_about_us_ar"];
		$this->about_us_ar->AdvancedSearch->Save();

		// Field about_us_en
		$this->about_us_en->AdvancedSearch->SearchValue = @$filter["x_about_us_en"];
		$this->about_us_en->AdvancedSearch->SearchOperator = @$filter["z_about_us_en"];
		$this->about_us_en->AdvancedSearch->SearchCondition = @$filter["v_about_us_en"];
		$this->about_us_en->AdvancedSearch->SearchValue2 = @$filter["y_about_us_en"];
		$this->about_us_en->AdvancedSearch->SearchOperator2 = @$filter["w_about_us_en"];
		$this->about_us_en->AdvancedSearch->Save();

		// Field twiiter
		$this->twiiter->AdvancedSearch->SearchValue = @$filter["x_twiiter"];
		$this->twiiter->AdvancedSearch->SearchOperator = @$filter["z_twiiter"];
		$this->twiiter->AdvancedSearch->SearchCondition = @$filter["v_twiiter"];
		$this->twiiter->AdvancedSearch->SearchValue2 = @$filter["y_twiiter"];
		$this->twiiter->AdvancedSearch->SearchOperator2 = @$filter["w_twiiter"];
		$this->twiiter->AdvancedSearch->Save();

		// Field facebook
		$this->facebook->AdvancedSearch->SearchValue = @$filter["x_facebook"];
		$this->facebook->AdvancedSearch->SearchOperator = @$filter["z_facebook"];
		$this->facebook->AdvancedSearch->SearchCondition = @$filter["v_facebook"];
		$this->facebook->AdvancedSearch->SearchValue2 = @$filter["y_facebook"];
		$this->facebook->AdvancedSearch->SearchOperator2 = @$filter["w_facebook"];
		$this->facebook->AdvancedSearch->Save();

		// Field instagram
		$this->instagram->AdvancedSearch->SearchValue = @$filter["x_instagram"];
		$this->instagram->AdvancedSearch->SearchOperator = @$filter["z_instagram"];
		$this->instagram->AdvancedSearch->SearchCondition = @$filter["v_instagram"];
		$this->instagram->AdvancedSearch->SearchValue2 = @$filter["y_instagram"];
		$this->instagram->AdvancedSearch->SearchOperator2 = @$filter["w_instagram"];
		$this->instagram->AdvancedSearch->Save();

		// Field youtube
		$this->youtube->AdvancedSearch->SearchValue = @$filter["x_youtube"];
		$this->youtube->AdvancedSearch->SearchOperator = @$filter["z_youtube"];
		$this->youtube->AdvancedSearch->SearchCondition = @$filter["v_youtube"];
		$this->youtube->AdvancedSearch->SearchValue2 = @$filter["y_youtube"];
		$this->youtube->AdvancedSearch->SearchOperator2 = @$filter["w_youtube"];
		$this->youtube->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->global_id, $Default, FALSE); // global_id
		$this->BuildSearchSql($sWhere, $this->system_name_ar, $Default, FALSE); // system_name_ar
		$this->BuildSearchSql($sWhere, $this->system_name_en, $Default, FALSE); // system_name_en
		$this->BuildSearchSql($sWhere, $this->contact_email, $Default, FALSE); // contact_email
		$this->BuildSearchSql($sWhere, $this->system_logo, $Default, FALSE); // system_logo
		$this->BuildSearchSql($sWhere, $this->contact_info_ar, $Default, FALSE); // contact_info_ar
		$this->BuildSearchSql($sWhere, $this->contact_info_en, $Default, FALSE); // contact_info_en
		$this->BuildSearchSql($sWhere, $this->about_us_ar, $Default, FALSE); // about_us_ar
		$this->BuildSearchSql($sWhere, $this->about_us_en, $Default, FALSE); // about_us_en
		$this->BuildSearchSql($sWhere, $this->twiiter, $Default, FALSE); // twiiter
		$this->BuildSearchSql($sWhere, $this->facebook, $Default, FALSE); // facebook
		$this->BuildSearchSql($sWhere, $this->instagram, $Default, FALSE); // instagram
		$this->BuildSearchSql($sWhere, $this->youtube, $Default, FALSE); // youtube

		// Set up search parm
		if (!$Default && $sWhere <> "" && $this->Command == "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->global_id->AdvancedSearch->Save(); // global_id
			$this->system_name_ar->AdvancedSearch->Save(); // system_name_ar
			$this->system_name_en->AdvancedSearch->Save(); // system_name_en
			$this->contact_email->AdvancedSearch->Save(); // contact_email
			$this->system_logo->AdvancedSearch->Save(); // system_logo
			$this->contact_info_ar->AdvancedSearch->Save(); // contact_info_ar
			$this->contact_info_en->AdvancedSearch->Save(); // contact_info_en
			$this->about_us_ar->AdvancedSearch->Save(); // about_us_ar
			$this->about_us_en->AdvancedSearch->Save(); // about_us_en
			$this->twiiter->AdvancedSearch->Save(); // twiiter
			$this->facebook->AdvancedSearch->Save(); // facebook
			$this->instagram->AdvancedSearch->Save(); // instagram
			$this->youtube->AdvancedSearch->Save(); // youtube
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
		$this->BuildBasicSearchSQL($sWhere, $this->system_name_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->system_name_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contact_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->system_logo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contact_info_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contact_info_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->about_us_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->about_us_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->twiiter, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->facebook, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->instagram, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->youtube, $arKeywords, $type);
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
		if ($this->global_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->system_name_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->system_name_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->system_logo->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_info_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_info_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->about_us_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->about_us_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->twiiter->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->facebook->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->instagram->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->youtube->AdvancedSearch->IssetSession())
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
		$this->global_id->AdvancedSearch->UnsetSession();
		$this->system_name_ar->AdvancedSearch->UnsetSession();
		$this->system_name_en->AdvancedSearch->UnsetSession();
		$this->contact_email->AdvancedSearch->UnsetSession();
		$this->system_logo->AdvancedSearch->UnsetSession();
		$this->contact_info_ar->AdvancedSearch->UnsetSession();
		$this->contact_info_en->AdvancedSearch->UnsetSession();
		$this->about_us_ar->AdvancedSearch->UnsetSession();
		$this->about_us_en->AdvancedSearch->UnsetSession();
		$this->twiiter->AdvancedSearch->UnsetSession();
		$this->facebook->AdvancedSearch->UnsetSession();
		$this->instagram->AdvancedSearch->UnsetSession();
		$this->youtube->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->global_id->AdvancedSearch->Load();
		$this->system_name_ar->AdvancedSearch->Load();
		$this->system_name_en->AdvancedSearch->Load();
		$this->contact_email->AdvancedSearch->Load();
		$this->system_logo->AdvancedSearch->Load();
		$this->contact_info_ar->AdvancedSearch->Load();
		$this->contact_info_en->AdvancedSearch->Load();
		$this->about_us_ar->AdvancedSearch->Load();
		$this->about_us_en->AdvancedSearch->Load();
		$this->twiiter->AdvancedSearch->Load();
		$this->facebook->AdvancedSearch->Load();
		$this->instagram->AdvancedSearch->Load();
		$this->youtube->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->global_id); // global_id
			$this->UpdateSort($this->system_name_ar); // system_name_ar
			$this->UpdateSort($this->system_name_en); // system_name_en
			$this->UpdateSort($this->contact_email); // contact_email
			$this->UpdateSort($this->system_logo); // system_logo
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
				$this->global_id->setSort("DESC");
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
				$this->global_id->setSort("");
				$this->system_name_ar->setSort("");
				$this->system_name_en->setSort("");
				$this->contact_email->setSort("");
				$this->system_logo->setSort("");
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

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
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
		$item->Visible = FALSE;
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

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->global_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["action"];

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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fglobal_settingslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fglobal_settingslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fglobal_settingslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fglobal_settingslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"global_settingssrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"global_settings\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'global_settingssrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
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
		// global_id

		$this->global_id->AdvancedSearch->SearchValue = @$_GET["x_global_id"];
		if ($this->global_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->global_id->AdvancedSearch->SearchOperator = @$_GET["z_global_id"];

		// system_name_ar
		$this->system_name_ar->AdvancedSearch->SearchValue = @$_GET["x_system_name_ar"];
		if ($this->system_name_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->system_name_ar->AdvancedSearch->SearchOperator = @$_GET["z_system_name_ar"];

		// system_name_en
		$this->system_name_en->AdvancedSearch->SearchValue = @$_GET["x_system_name_en"];
		if ($this->system_name_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->system_name_en->AdvancedSearch->SearchOperator = @$_GET["z_system_name_en"];

		// contact_email
		$this->contact_email->AdvancedSearch->SearchValue = @$_GET["x_contact_email"];
		if ($this->contact_email->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->contact_email->AdvancedSearch->SearchOperator = @$_GET["z_contact_email"];

		// system_logo
		$this->system_logo->AdvancedSearch->SearchValue = @$_GET["x_system_logo"];
		if ($this->system_logo->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->system_logo->AdvancedSearch->SearchOperator = @$_GET["z_system_logo"];

		// contact_info_ar
		$this->contact_info_ar->AdvancedSearch->SearchValue = @$_GET["x_contact_info_ar"];
		if ($this->contact_info_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->contact_info_ar->AdvancedSearch->SearchOperator = @$_GET["z_contact_info_ar"];

		// contact_info_en
		$this->contact_info_en->AdvancedSearch->SearchValue = @$_GET["x_contact_info_en"];
		if ($this->contact_info_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->contact_info_en->AdvancedSearch->SearchOperator = @$_GET["z_contact_info_en"];

		// about_us_ar
		$this->about_us_ar->AdvancedSearch->SearchValue = @$_GET["x_about_us_ar"];
		if ($this->about_us_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->about_us_ar->AdvancedSearch->SearchOperator = @$_GET["z_about_us_ar"];

		// about_us_en
		$this->about_us_en->AdvancedSearch->SearchValue = @$_GET["x_about_us_en"];
		if ($this->about_us_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->about_us_en->AdvancedSearch->SearchOperator = @$_GET["z_about_us_en"];

		// twiiter
		$this->twiiter->AdvancedSearch->SearchValue = @$_GET["x_twiiter"];
		if ($this->twiiter->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->twiiter->AdvancedSearch->SearchOperator = @$_GET["z_twiiter"];

		// facebook
		$this->facebook->AdvancedSearch->SearchValue = @$_GET["x_facebook"];
		if ($this->facebook->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->facebook->AdvancedSearch->SearchOperator = @$_GET["z_facebook"];

		// instagram
		$this->instagram->AdvancedSearch->SearchValue = @$_GET["x_instagram"];
		if ($this->instagram->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->instagram->AdvancedSearch->SearchOperator = @$_GET["z_instagram"];

		// youtube
		$this->youtube->AdvancedSearch->SearchValue = @$_GET["x_youtube"];
		if ($this->youtube->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->youtube->AdvancedSearch->SearchOperator = @$_GET["z_youtube"];
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
		$this->global_id->setDbValue($row['global_id']);
		$this->system_name_ar->setDbValue($row['system_name_ar']);
		$this->system_name_en->setDbValue($row['system_name_en']);
		$this->contact_email->setDbValue($row['contact_email']);
		$this->system_logo->Upload->DbValue = $row['system_logo'];
		$this->system_logo->CurrentValue = $this->system_logo->Upload->DbValue;
		$this->contact_info_ar->setDbValue($row['contact_info_ar']);
		$this->contact_info_en->setDbValue($row['contact_info_en']);
		$this->about_us_ar->setDbValue($row['about_us_ar']);
		$this->about_us_en->setDbValue($row['about_us_en']);
		$this->twiiter->setDbValue($row['twiiter']);
		$this->facebook->setDbValue($row['facebook']);
		$this->instagram->setDbValue($row['instagram']);
		$this->youtube->setDbValue($row['youtube']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['global_id'] = NULL;
		$row['system_name_ar'] = NULL;
		$row['system_name_en'] = NULL;
		$row['contact_email'] = NULL;
		$row['system_logo'] = NULL;
		$row['contact_info_ar'] = NULL;
		$row['contact_info_en'] = NULL;
		$row['about_us_ar'] = NULL;
		$row['about_us_en'] = NULL;
		$row['twiiter'] = NULL;
		$row['facebook'] = NULL;
		$row['instagram'] = NULL;
		$row['youtube'] = NULL;
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
		$this->global_id->DbValue = $row['global_id'];
		$this->system_name_ar->DbValue = $row['system_name_ar'];
		$this->system_name_en->DbValue = $row['system_name_en'];
		$this->contact_email->DbValue = $row['contact_email'];
		$this->system_logo->Upload->DbValue = $row['system_logo'];
		$this->contact_info_ar->DbValue = $row['contact_info_ar'];
		$this->contact_info_en->DbValue = $row['contact_info_en'];
		$this->about_us_ar->DbValue = $row['about_us_ar'];
		$this->about_us_en->DbValue = $row['about_us_en'];
		$this->twiiter->DbValue = $row['twiiter'];
		$this->facebook->DbValue = $row['facebook'];
		$this->instagram->DbValue = $row['instagram'];
		$this->youtube->DbValue = $row['youtube'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("global_id")) <> "")
			$this->global_id->CurrentValue = $this->getKey("global_id"); // global_id
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
		// global_id
		// system_name_ar
		// system_name_en
		// contact_email
		// system_logo
		// contact_info_ar
		// contact_info_en
		// about_us_ar
		// about_us_en
		// twiiter
		// facebook
		// instagram
		// youtube

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// global_id
		$this->global_id->ViewValue = $this->global_id->CurrentValue;
		$this->global_id->ViewCustomAttributes = "";

		// system_name_ar
		$this->system_name_ar->ViewValue = $this->system_name_ar->CurrentValue;
		$this->system_name_ar->ViewCustomAttributes = "";

		// system_name_en
		$this->system_name_en->ViewValue = $this->system_name_en->CurrentValue;
		$this->system_name_en->ViewCustomAttributes = "";

		// contact_email
		$this->contact_email->ViewValue = $this->contact_email->CurrentValue;
		$this->contact_email->ViewCustomAttributes = "";

		// system_logo
		$this->system_logo->UploadPath = "../uploads";
		if (!ew_Empty($this->system_logo->Upload->DbValue)) {
			$this->system_logo->ImageWidth = 100;
			$this->system_logo->ImageHeight = 0;
			$this->system_logo->ImageAlt = $this->system_logo->FldAlt();
			$this->system_logo->ViewValue = $this->system_logo->Upload->DbValue;
		} else {
			$this->system_logo->ViewValue = "";
		}
		$this->system_logo->ViewCustomAttributes = "";

		// contact_info_ar
		$this->contact_info_ar->ViewValue = $this->contact_info_ar->CurrentValue;
		$this->contact_info_ar->ViewCustomAttributes = "";

		// contact_info_en
		$this->contact_info_en->ViewValue = $this->contact_info_en->CurrentValue;
		$this->contact_info_en->ViewCustomAttributes = "";

		// about_us_ar
		$this->about_us_ar->ViewValue = $this->about_us_ar->CurrentValue;
		$this->about_us_ar->ViewCustomAttributes = "";

		// about_us_en
		$this->about_us_en->ViewValue = $this->about_us_en->CurrentValue;
		$this->about_us_en->ViewCustomAttributes = "";

		// twiiter
		$this->twiiter->ViewValue = $this->twiiter->CurrentValue;
		$this->twiiter->ViewCustomAttributes = "";

		// facebook
		$this->facebook->ViewValue = $this->facebook->CurrentValue;
		$this->facebook->ViewCustomAttributes = "";

		// instagram
		$this->instagram->ViewValue = $this->instagram->CurrentValue;
		$this->instagram->ViewCustomAttributes = "";

		// youtube
		$this->youtube->ViewValue = $this->youtube->CurrentValue;
		$this->youtube->ViewCustomAttributes = "";

			// global_id
			$this->global_id->LinkCustomAttributes = "";
			$this->global_id->HrefValue = "";
			$this->global_id->TooltipValue = "";

			// system_name_ar
			$this->system_name_ar->LinkCustomAttributes = "";
			$this->system_name_ar->HrefValue = "";
			$this->system_name_ar->TooltipValue = "";

			// system_name_en
			$this->system_name_en->LinkCustomAttributes = "";
			$this->system_name_en->HrefValue = "";
			$this->system_name_en->TooltipValue = "";

			// contact_email
			$this->contact_email->LinkCustomAttributes = "";
			$this->contact_email->HrefValue = "";
			$this->contact_email->TooltipValue = "";

			// system_logo
			$this->system_logo->LinkCustomAttributes = "";
			$this->system_logo->UploadPath = "../uploads";
			if (!ew_Empty($this->system_logo->Upload->DbValue)) {
				$this->system_logo->HrefValue = ew_GetFileUploadUrl($this->system_logo, $this->system_logo->Upload->DbValue); // Add prefix/suffix
				$this->system_logo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->system_logo->HrefValue = ew_FullUrl($this->system_logo->HrefValue, "href");
			} else {
				$this->system_logo->HrefValue = "";
			}
			$this->system_logo->HrefValue2 = $this->system_logo->UploadPath . $this->system_logo->Upload->DbValue;
			$this->system_logo->TooltipValue = "";
			if ($this->system_logo->UseColorbox) {
				if (ew_Empty($this->system_logo->TooltipValue))
					$this->system_logo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->system_logo->LinkAttrs["data-rel"] = "global_settings_x" . $this->RowCnt . "_system_logo";
				ew_AppendClass($this->system_logo->LinkAttrs["class"], "ewLightbox");
			}
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
		$this->global_id->AdvancedSearch->Load();
		$this->system_name_ar->AdvancedSearch->Load();
		$this->system_name_en->AdvancedSearch->Load();
		$this->contact_email->AdvancedSearch->Load();
		$this->system_logo->AdvancedSearch->Load();
		$this->contact_info_ar->AdvancedSearch->Load();
		$this->contact_info_en->AdvancedSearch->Load();
		$this->about_us_ar->AdvancedSearch->Load();
		$this->about_us_en->AdvancedSearch->Load();
		$this->twiiter->AdvancedSearch->Load();
		$this->facebook->AdvancedSearch->Load();
		$this->instagram->AdvancedSearch->Load();
		$this->youtube->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_global_settings\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_global_settings',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fglobal_settingslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($global_settings_list)) $global_settings_list = new cglobal_settings_list();

// Page init
$global_settings_list->Page_Init();

// Page main
$global_settings_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$global_settings_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($global_settings->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fglobal_settingslist = new ew_Form("fglobal_settingslist", "list");
fglobal_settingslist.FormKeyCountName = '<?php echo $global_settings_list->FormKeyCountName ?>';

// Form_CustomValidate event
fglobal_settingslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fglobal_settingslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fglobal_settingslistsrch = new ew_Form("fglobal_settingslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($global_settings->Export == "") { ?>
<div class="ewToolbar">
<?php if ($global_settings_list->TotalRecs > 0 && $global_settings_list->ExportOptions->Visible()) { ?>
<?php $global_settings_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($global_settings_list->SearchOptions->Visible()) { ?>
<?php $global_settings_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($global_settings_list->FilterOptions->Visible()) { ?>
<?php $global_settings_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $global_settings_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($global_settings_list->TotalRecs <= 0)
			$global_settings_list->TotalRecs = $global_settings->ListRecordCount();
	} else {
		if (!$global_settings_list->Recordset && ($global_settings_list->Recordset = $global_settings_list->LoadRecordset()))
			$global_settings_list->TotalRecs = $global_settings_list->Recordset->RecordCount();
	}
	$global_settings_list->StartRec = 1;
	if ($global_settings_list->DisplayRecs <= 0 || ($global_settings->Export <> "" && $global_settings->ExportAll)) // Display all records
		$global_settings_list->DisplayRecs = $global_settings_list->TotalRecs;
	if (!($global_settings->Export <> "" && $global_settings->ExportAll))
		$global_settings_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$global_settings_list->Recordset = $global_settings_list->LoadRecordset($global_settings_list->StartRec-1, $global_settings_list->DisplayRecs);

	// Set no record found message
	if ($global_settings->CurrentAction == "" && $global_settings_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$global_settings_list->setWarningMessage(ew_DeniedMsg());
		if ($global_settings_list->SearchWhere == "0=101")
			$global_settings_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$global_settings_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($global_settings_list->AuditTrailOnSearch && $global_settings_list->Command == "search" && !$global_settings_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $global_settings_list->getSessionWhere();
		$global_settings_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$global_settings_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($global_settings->Export == "" && $global_settings->CurrentAction == "") { ?>
<form name="fglobal_settingslistsrch" id="fglobal_settingslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($global_settings_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fglobal_settingslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="global_settings">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($global_settings_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($global_settings_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $global_settings_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($global_settings_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($global_settings_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($global_settings_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($global_settings_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $global_settings_list->ShowPageHeader(); ?>
<?php
$global_settings_list->ShowMessage();
?>
<?php if ($global_settings_list->TotalRecs > 0 || $global_settings->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($global_settings_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> global_settings">
<?php if ($global_settings->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($global_settings->CurrentAction <> "gridadd" && $global_settings->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($global_settings_list->Pager)) $global_settings_list->Pager = new cPrevNextPager($global_settings_list->StartRec, $global_settings_list->DisplayRecs, $global_settings_list->TotalRecs, $global_settings_list->AutoHidePager) ?>
<?php if ($global_settings_list->Pager->RecordCount > 0 && $global_settings_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($global_settings_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($global_settings_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $global_settings_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($global_settings_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($global_settings_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $global_settings_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $global_settings_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $global_settings_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $global_settings_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($global_settings_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fglobal_settingslist" id="fglobal_settingslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($global_settings_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $global_settings_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="global_settings">
<div id="gmp_global_settings" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($global_settings_list->TotalRecs > 0 || $global_settings->CurrentAction == "gridedit") { ?>
<table id="tbl_global_settingslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$global_settings_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$global_settings_list->RenderListOptions();

// Render list options (header, left)
$global_settings_list->ListOptions->Render("header", "left");
?>
<?php if ($global_settings->global_id->Visible) { // global_id ?>
	<?php if ($global_settings->SortUrl($global_settings->global_id) == "") { ?>
		<th data-name="global_id" class="<?php echo $global_settings->global_id->HeaderCellClass() ?>"><div id="elh_global_settings_global_id" class="global_settings_global_id"><div class="ewTableHeaderCaption"><?php echo $global_settings->global_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="global_id" class="<?php echo $global_settings->global_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $global_settings->SortUrl($global_settings->global_id) ?>',1);"><div id="elh_global_settings_global_id" class="global_settings_global_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $global_settings->global_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($global_settings->global_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($global_settings->global_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($global_settings->system_name_ar->Visible) { // system_name_ar ?>
	<?php if ($global_settings->SortUrl($global_settings->system_name_ar) == "") { ?>
		<th data-name="system_name_ar" class="<?php echo $global_settings->system_name_ar->HeaderCellClass() ?>"><div id="elh_global_settings_system_name_ar" class="global_settings_system_name_ar"><div class="ewTableHeaderCaption"><?php echo $global_settings->system_name_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="system_name_ar" class="<?php echo $global_settings->system_name_ar->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $global_settings->SortUrl($global_settings->system_name_ar) ?>',1);"><div id="elh_global_settings_system_name_ar" class="global_settings_system_name_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $global_settings->system_name_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($global_settings->system_name_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($global_settings->system_name_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($global_settings->system_name_en->Visible) { // system_name_en ?>
	<?php if ($global_settings->SortUrl($global_settings->system_name_en) == "") { ?>
		<th data-name="system_name_en" class="<?php echo $global_settings->system_name_en->HeaderCellClass() ?>"><div id="elh_global_settings_system_name_en" class="global_settings_system_name_en"><div class="ewTableHeaderCaption"><?php echo $global_settings->system_name_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="system_name_en" class="<?php echo $global_settings->system_name_en->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $global_settings->SortUrl($global_settings->system_name_en) ?>',1);"><div id="elh_global_settings_system_name_en" class="global_settings_system_name_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $global_settings->system_name_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($global_settings->system_name_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($global_settings->system_name_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($global_settings->contact_email->Visible) { // contact_email ?>
	<?php if ($global_settings->SortUrl($global_settings->contact_email) == "") { ?>
		<th data-name="contact_email" class="<?php echo $global_settings->contact_email->HeaderCellClass() ?>"><div id="elh_global_settings_contact_email" class="global_settings_contact_email"><div class="ewTableHeaderCaption"><?php echo $global_settings->contact_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contact_email" class="<?php echo $global_settings->contact_email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $global_settings->SortUrl($global_settings->contact_email) ?>',1);"><div id="elh_global_settings_contact_email" class="global_settings_contact_email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $global_settings->contact_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($global_settings->contact_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($global_settings->contact_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($global_settings->system_logo->Visible) { // system_logo ?>
	<?php if ($global_settings->SortUrl($global_settings->system_logo) == "") { ?>
		<th data-name="system_logo" class="<?php echo $global_settings->system_logo->HeaderCellClass() ?>"><div id="elh_global_settings_system_logo" class="global_settings_system_logo"><div class="ewTableHeaderCaption"><?php echo $global_settings->system_logo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="system_logo" class="<?php echo $global_settings->system_logo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $global_settings->SortUrl($global_settings->system_logo) ?>',1);"><div id="elh_global_settings_system_logo" class="global_settings_system_logo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $global_settings->system_logo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($global_settings->system_logo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($global_settings->system_logo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$global_settings_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($global_settings->ExportAll && $global_settings->Export <> "") {
	$global_settings_list->StopRec = $global_settings_list->TotalRecs;
} else {

	// Set the last record to display
	if ($global_settings_list->TotalRecs > $global_settings_list->StartRec + $global_settings_list->DisplayRecs - 1)
		$global_settings_list->StopRec = $global_settings_list->StartRec + $global_settings_list->DisplayRecs - 1;
	else
		$global_settings_list->StopRec = $global_settings_list->TotalRecs;
}
$global_settings_list->RecCnt = $global_settings_list->StartRec - 1;
if ($global_settings_list->Recordset && !$global_settings_list->Recordset->EOF) {
	$global_settings_list->Recordset->MoveFirst();
	$bSelectLimit = $global_settings_list->UseSelectLimit;
	if (!$bSelectLimit && $global_settings_list->StartRec > 1)
		$global_settings_list->Recordset->Move($global_settings_list->StartRec - 1);
} elseif (!$global_settings->AllowAddDeleteRow && $global_settings_list->StopRec == 0) {
	$global_settings_list->StopRec = $global_settings->GridAddRowCount;
}

// Initialize aggregate
$global_settings->RowType = EW_ROWTYPE_AGGREGATEINIT;
$global_settings->ResetAttrs();
$global_settings_list->RenderRow();
while ($global_settings_list->RecCnt < $global_settings_list->StopRec) {
	$global_settings_list->RecCnt++;
	if (intval($global_settings_list->RecCnt) >= intval($global_settings_list->StartRec)) {
		$global_settings_list->RowCnt++;

		// Set up key count
		$global_settings_list->KeyCount = $global_settings_list->RowIndex;

		// Init row class and style
		$global_settings->ResetAttrs();
		$global_settings->CssClass = "";
		if ($global_settings->CurrentAction == "gridadd") {
		} else {
			$global_settings_list->LoadRowValues($global_settings_list->Recordset); // Load row values
		}
		$global_settings->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$global_settings->RowAttrs = array_merge($global_settings->RowAttrs, array('data-rowindex'=>$global_settings_list->RowCnt, 'id'=>'r' . $global_settings_list->RowCnt . '_global_settings', 'data-rowtype'=>$global_settings->RowType));

		// Render row
		$global_settings_list->RenderRow();

		// Render list options
		$global_settings_list->RenderListOptions();
?>
	<tr<?php echo $global_settings->RowAttributes() ?>>
<?php

// Render list options (body, left)
$global_settings_list->ListOptions->Render("body", "left", $global_settings_list->RowCnt);
?>
	<?php if ($global_settings->global_id->Visible) { // global_id ?>
		<td data-name="global_id"<?php echo $global_settings->global_id->CellAttributes() ?>>
<span id="el<?php echo $global_settings_list->RowCnt ?>_global_settings_global_id" class="global_settings_global_id">
<span<?php echo $global_settings->global_id->ViewAttributes() ?>>
<?php echo $global_settings->global_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($global_settings->system_name_ar->Visible) { // system_name_ar ?>
		<td data-name="system_name_ar"<?php echo $global_settings->system_name_ar->CellAttributes() ?>>
<span id="el<?php echo $global_settings_list->RowCnt ?>_global_settings_system_name_ar" class="global_settings_system_name_ar">
<span<?php echo $global_settings->system_name_ar->ViewAttributes() ?>>
<?php echo $global_settings->system_name_ar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($global_settings->system_name_en->Visible) { // system_name_en ?>
		<td data-name="system_name_en"<?php echo $global_settings->system_name_en->CellAttributes() ?>>
<span id="el<?php echo $global_settings_list->RowCnt ?>_global_settings_system_name_en" class="global_settings_system_name_en">
<span<?php echo $global_settings->system_name_en->ViewAttributes() ?>>
<?php echo $global_settings->system_name_en->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($global_settings->contact_email->Visible) { // contact_email ?>
		<td data-name="contact_email"<?php echo $global_settings->contact_email->CellAttributes() ?>>
<span id="el<?php echo $global_settings_list->RowCnt ?>_global_settings_contact_email" class="global_settings_contact_email">
<span<?php echo $global_settings->contact_email->ViewAttributes() ?>>
<?php echo $global_settings->contact_email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($global_settings->system_logo->Visible) { // system_logo ?>
		<td data-name="system_logo"<?php echo $global_settings->system_logo->CellAttributes() ?>>
<span id="el<?php echo $global_settings_list->RowCnt ?>_global_settings_system_logo" class="global_settings_system_logo">
<span>
<?php echo ew_GetFileViewTag($global_settings->system_logo, $global_settings->system_logo->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$global_settings_list->ListOptions->Render("body", "right", $global_settings_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($global_settings->CurrentAction <> "gridadd")
		$global_settings_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($global_settings->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($global_settings_list->Recordset)
	$global_settings_list->Recordset->Close();
?>
<?php if ($global_settings->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($global_settings->CurrentAction <> "gridadd" && $global_settings->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($global_settings_list->Pager)) $global_settings_list->Pager = new cPrevNextPager($global_settings_list->StartRec, $global_settings_list->DisplayRecs, $global_settings_list->TotalRecs, $global_settings_list->AutoHidePager) ?>
<?php if ($global_settings_list->Pager->RecordCount > 0 && $global_settings_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($global_settings_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($global_settings_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $global_settings_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($global_settings_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($global_settings_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $global_settings_list->PageUrl() ?>start=<?php echo $global_settings_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $global_settings_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $global_settings_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $global_settings_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $global_settings_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($global_settings_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($global_settings_list->TotalRecs == 0 && $global_settings->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($global_settings_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($global_settings->Export == "") { ?>
<script type="text/javascript">
fglobal_settingslistsrch.FilterList = <?php echo $global_settings_list->GetFilterList() ?>;
fglobal_settingslistsrch.Init();
fglobal_settingslist.Init();
</script>
<?php } ?>
<?php
$global_settings_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($global_settings->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$global_settings_list->Page_Terminate();
?>
