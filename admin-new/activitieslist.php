<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "activitiesinfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "registered_usersgridcls.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$activities_list = NULL; // Initialize page object first

class cactivities_list extends cactivities {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'activities';

	// Page object name
	var $PageObjName = 'activities_list';

	// Grid form hidden field names
	var $FormName = 'factivitieslist';
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

		// Table object (activities)
		if (!isset($GLOBALS["activities"]) || get_class($GLOBALS["activities"]) == "cactivities") {
			$GLOBALS["activities"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["activities"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "activitiesadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "activitiesdelete.php";
		$this->MultiUpdateUrl = "activitiesupdate.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'activities', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption factivitieslistsrch";

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
		$this->activity_id->SetVisibility();
		$this->activity_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->activity_name_ar->SetVisibility();
		$this->activity_start_date->SetVisibility();
		$this->activity_end_date->SetVisibility();
		$this->activity_city->SetVisibility();
		$this->activity_active->SetVisibility();

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

			// Process auto fill for detail table 'registered_users'
			if (@$_POST["grid"] == "fregistered_usersgrid") {
				if (!isset($GLOBALS["registered_users_grid"])) $GLOBALS["registered_users_grid"] = new cregistered_users_grid;
				$GLOBALS["registered_users_grid"]->Page_Init();
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
		global $EW_EXPORT, $activities;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($activities);
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
	var $registered_users_Count;
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
			$this->activity_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->activity_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "factivitieslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->activity_id->AdvancedSearch->ToJson(), ","); // Field activity_id
		$sFilterList = ew_Concat($sFilterList, $this->activity_name_ar->AdvancedSearch->ToJson(), ","); // Field activity_name_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_name_en->AdvancedSearch->ToJson(), ","); // Field activity_name_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_start_date->AdvancedSearch->ToJson(), ","); // Field activity_start_date
		$sFilterList = ew_Concat($sFilterList, $this->activity_end_date->AdvancedSearch->ToJson(), ","); // Field activity_end_date
		$sFilterList = ew_Concat($sFilterList, $this->activity_time_ar->AdvancedSearch->ToJson(), ","); // Field activity_time_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_time_en->AdvancedSearch->ToJson(), ","); // Field activity_time_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_description_ar->AdvancedSearch->ToJson(), ","); // Field activity_description_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_description_en->AdvancedSearch->ToJson(), ","); // Field activity_description_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_persons->AdvancedSearch->ToJson(), ","); // Field activity_persons
		$sFilterList = ew_Concat($sFilterList, $this->activity_hours->AdvancedSearch->ToJson(), ","); // Field activity_hours
		$sFilterList = ew_Concat($sFilterList, $this->activity_city->AdvancedSearch->ToJson(), ","); // Field activity_city
		$sFilterList = ew_Concat($sFilterList, $this->activity_location_ar->AdvancedSearch->ToJson(), ","); // Field activity_location_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_location_en->AdvancedSearch->ToJson(), ","); // Field activity_location_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_location_map->AdvancedSearch->ToJson(), ","); // Field activity_location_map
		$sFilterList = ew_Concat($sFilterList, $this->activity_image->AdvancedSearch->ToJson(), ","); // Field activity_image
		$sFilterList = ew_Concat($sFilterList, $this->activity_organizer_ar->AdvancedSearch->ToJson(), ","); // Field activity_organizer_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_organizer_en->AdvancedSearch->ToJson(), ","); // Field activity_organizer_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_category_ar->AdvancedSearch->ToJson(), ","); // Field activity_category_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_category_en->AdvancedSearch->ToJson(), ","); // Field activity_category_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_type->AdvancedSearch->ToJson(), ","); // Field activity_type
		$sFilterList = ew_Concat($sFilterList, $this->activity_gender_target->AdvancedSearch->ToJson(), ","); // Field activity_gender_target
		$sFilterList = ew_Concat($sFilterList, $this->activity_terms_and_conditions_ar->AdvancedSearch->ToJson(), ","); // Field activity_terms_and_conditions_ar
		$sFilterList = ew_Concat($sFilterList, $this->activity_terms_and_conditions_en->AdvancedSearch->ToJson(), ","); // Field activity_terms_and_conditions_en
		$sFilterList = ew_Concat($sFilterList, $this->activity_active->AdvancedSearch->ToJson(), ","); // Field activity_active
		$sFilterList = ew_Concat($sFilterList, $this->leader_username->AdvancedSearch->ToJson(), ","); // Field leader_username
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "factivitieslistsrch", $filters);

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

		// Field activity_id
		$this->activity_id->AdvancedSearch->SearchValue = @$filter["x_activity_id"];
		$this->activity_id->AdvancedSearch->SearchOperator = @$filter["z_activity_id"];
		$this->activity_id->AdvancedSearch->SearchCondition = @$filter["v_activity_id"];
		$this->activity_id->AdvancedSearch->SearchValue2 = @$filter["y_activity_id"];
		$this->activity_id->AdvancedSearch->SearchOperator2 = @$filter["w_activity_id"];
		$this->activity_id->AdvancedSearch->Save();

		// Field activity_name_ar
		$this->activity_name_ar->AdvancedSearch->SearchValue = @$filter["x_activity_name_ar"];
		$this->activity_name_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_name_ar"];
		$this->activity_name_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_name_ar"];
		$this->activity_name_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_name_ar"];
		$this->activity_name_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_name_ar"];
		$this->activity_name_ar->AdvancedSearch->Save();

		// Field activity_name_en
		$this->activity_name_en->AdvancedSearch->SearchValue = @$filter["x_activity_name_en"];
		$this->activity_name_en->AdvancedSearch->SearchOperator = @$filter["z_activity_name_en"];
		$this->activity_name_en->AdvancedSearch->SearchCondition = @$filter["v_activity_name_en"];
		$this->activity_name_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_name_en"];
		$this->activity_name_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_name_en"];
		$this->activity_name_en->AdvancedSearch->Save();

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

		// Field activity_time_ar
		$this->activity_time_ar->AdvancedSearch->SearchValue = @$filter["x_activity_time_ar"];
		$this->activity_time_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_time_ar"];
		$this->activity_time_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_time_ar"];
		$this->activity_time_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_time_ar"];
		$this->activity_time_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_time_ar"];
		$this->activity_time_ar->AdvancedSearch->Save();

		// Field activity_time_en
		$this->activity_time_en->AdvancedSearch->SearchValue = @$filter["x_activity_time_en"];
		$this->activity_time_en->AdvancedSearch->SearchOperator = @$filter["z_activity_time_en"];
		$this->activity_time_en->AdvancedSearch->SearchCondition = @$filter["v_activity_time_en"];
		$this->activity_time_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_time_en"];
		$this->activity_time_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_time_en"];
		$this->activity_time_en->AdvancedSearch->Save();

		// Field activity_description_ar
		$this->activity_description_ar->AdvancedSearch->SearchValue = @$filter["x_activity_description_ar"];
		$this->activity_description_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_description_ar"];
		$this->activity_description_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_description_ar"];
		$this->activity_description_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_description_ar"];
		$this->activity_description_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_description_ar"];
		$this->activity_description_ar->AdvancedSearch->Save();

		// Field activity_description_en
		$this->activity_description_en->AdvancedSearch->SearchValue = @$filter["x_activity_description_en"];
		$this->activity_description_en->AdvancedSearch->SearchOperator = @$filter["z_activity_description_en"];
		$this->activity_description_en->AdvancedSearch->SearchCondition = @$filter["v_activity_description_en"];
		$this->activity_description_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_description_en"];
		$this->activity_description_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_description_en"];
		$this->activity_description_en->AdvancedSearch->Save();

		// Field activity_persons
		$this->activity_persons->AdvancedSearch->SearchValue = @$filter["x_activity_persons"];
		$this->activity_persons->AdvancedSearch->SearchOperator = @$filter["z_activity_persons"];
		$this->activity_persons->AdvancedSearch->SearchCondition = @$filter["v_activity_persons"];
		$this->activity_persons->AdvancedSearch->SearchValue2 = @$filter["y_activity_persons"];
		$this->activity_persons->AdvancedSearch->SearchOperator2 = @$filter["w_activity_persons"];
		$this->activity_persons->AdvancedSearch->Save();

		// Field activity_hours
		$this->activity_hours->AdvancedSearch->SearchValue = @$filter["x_activity_hours"];
		$this->activity_hours->AdvancedSearch->SearchOperator = @$filter["z_activity_hours"];
		$this->activity_hours->AdvancedSearch->SearchCondition = @$filter["v_activity_hours"];
		$this->activity_hours->AdvancedSearch->SearchValue2 = @$filter["y_activity_hours"];
		$this->activity_hours->AdvancedSearch->SearchOperator2 = @$filter["w_activity_hours"];
		$this->activity_hours->AdvancedSearch->Save();

		// Field activity_city
		$this->activity_city->AdvancedSearch->SearchValue = @$filter["x_activity_city"];
		$this->activity_city->AdvancedSearch->SearchOperator = @$filter["z_activity_city"];
		$this->activity_city->AdvancedSearch->SearchCondition = @$filter["v_activity_city"];
		$this->activity_city->AdvancedSearch->SearchValue2 = @$filter["y_activity_city"];
		$this->activity_city->AdvancedSearch->SearchOperator2 = @$filter["w_activity_city"];
		$this->activity_city->AdvancedSearch->Save();

		// Field activity_location_ar
		$this->activity_location_ar->AdvancedSearch->SearchValue = @$filter["x_activity_location_ar"];
		$this->activity_location_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_location_ar"];
		$this->activity_location_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_location_ar"];
		$this->activity_location_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_location_ar"];
		$this->activity_location_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_location_ar"];
		$this->activity_location_ar->AdvancedSearch->Save();

		// Field activity_location_en
		$this->activity_location_en->AdvancedSearch->SearchValue = @$filter["x_activity_location_en"];
		$this->activity_location_en->AdvancedSearch->SearchOperator = @$filter["z_activity_location_en"];
		$this->activity_location_en->AdvancedSearch->SearchCondition = @$filter["v_activity_location_en"];
		$this->activity_location_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_location_en"];
		$this->activity_location_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_location_en"];
		$this->activity_location_en->AdvancedSearch->Save();

		// Field activity_location_map
		$this->activity_location_map->AdvancedSearch->SearchValue = @$filter["x_activity_location_map"];
		$this->activity_location_map->AdvancedSearch->SearchOperator = @$filter["z_activity_location_map"];
		$this->activity_location_map->AdvancedSearch->SearchCondition = @$filter["v_activity_location_map"];
		$this->activity_location_map->AdvancedSearch->SearchValue2 = @$filter["y_activity_location_map"];
		$this->activity_location_map->AdvancedSearch->SearchOperator2 = @$filter["w_activity_location_map"];
		$this->activity_location_map->AdvancedSearch->Save();

		// Field activity_image
		$this->activity_image->AdvancedSearch->SearchValue = @$filter["x_activity_image"];
		$this->activity_image->AdvancedSearch->SearchOperator = @$filter["z_activity_image"];
		$this->activity_image->AdvancedSearch->SearchCondition = @$filter["v_activity_image"];
		$this->activity_image->AdvancedSearch->SearchValue2 = @$filter["y_activity_image"];
		$this->activity_image->AdvancedSearch->SearchOperator2 = @$filter["w_activity_image"];
		$this->activity_image->AdvancedSearch->Save();

		// Field activity_organizer_ar
		$this->activity_organizer_ar->AdvancedSearch->SearchValue = @$filter["x_activity_organizer_ar"];
		$this->activity_organizer_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_organizer_ar"];
		$this->activity_organizer_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_organizer_ar"];
		$this->activity_organizer_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_organizer_ar"];
		$this->activity_organizer_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_organizer_ar"];
		$this->activity_organizer_ar->AdvancedSearch->Save();

		// Field activity_organizer_en
		$this->activity_organizer_en->AdvancedSearch->SearchValue = @$filter["x_activity_organizer_en"];
		$this->activity_organizer_en->AdvancedSearch->SearchOperator = @$filter["z_activity_organizer_en"];
		$this->activity_organizer_en->AdvancedSearch->SearchCondition = @$filter["v_activity_organizer_en"];
		$this->activity_organizer_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_organizer_en"];
		$this->activity_organizer_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_organizer_en"];
		$this->activity_organizer_en->AdvancedSearch->Save();

		// Field activity_category_ar
		$this->activity_category_ar->AdvancedSearch->SearchValue = @$filter["x_activity_category_ar"];
		$this->activity_category_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_category_ar"];
		$this->activity_category_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_category_ar"];
		$this->activity_category_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_category_ar"];
		$this->activity_category_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_category_ar"];
		$this->activity_category_ar->AdvancedSearch->Save();

		// Field activity_category_en
		$this->activity_category_en->AdvancedSearch->SearchValue = @$filter["x_activity_category_en"];
		$this->activity_category_en->AdvancedSearch->SearchOperator = @$filter["z_activity_category_en"];
		$this->activity_category_en->AdvancedSearch->SearchCondition = @$filter["v_activity_category_en"];
		$this->activity_category_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_category_en"];
		$this->activity_category_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_category_en"];
		$this->activity_category_en->AdvancedSearch->Save();

		// Field activity_type
		$this->activity_type->AdvancedSearch->SearchValue = @$filter["x_activity_type"];
		$this->activity_type->AdvancedSearch->SearchOperator = @$filter["z_activity_type"];
		$this->activity_type->AdvancedSearch->SearchCondition = @$filter["v_activity_type"];
		$this->activity_type->AdvancedSearch->SearchValue2 = @$filter["y_activity_type"];
		$this->activity_type->AdvancedSearch->SearchOperator2 = @$filter["w_activity_type"];
		$this->activity_type->AdvancedSearch->Save();

		// Field activity_gender_target
		$this->activity_gender_target->AdvancedSearch->SearchValue = @$filter["x_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchOperator = @$filter["z_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchCondition = @$filter["v_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchValue2 = @$filter["y_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->SearchOperator2 = @$filter["w_activity_gender_target"];
		$this->activity_gender_target->AdvancedSearch->Save();

		// Field activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchValue = @$filter["x_activity_terms_and_conditions_ar"];
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchOperator = @$filter["z_activity_terms_and_conditions_ar"];
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchCondition = @$filter["v_activity_terms_and_conditions_ar"];
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchValue2 = @$filter["y_activity_terms_and_conditions_ar"];
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchOperator2 = @$filter["w_activity_terms_and_conditions_ar"];
		$this->activity_terms_and_conditions_ar->AdvancedSearch->Save();

		// Field activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchValue = @$filter["x_activity_terms_and_conditions_en"];
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchOperator = @$filter["z_activity_terms_and_conditions_en"];
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchCondition = @$filter["v_activity_terms_and_conditions_en"];
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchValue2 = @$filter["y_activity_terms_and_conditions_en"];
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchOperator2 = @$filter["w_activity_terms_and_conditions_en"];
		$this->activity_terms_and_conditions_en->AdvancedSearch->Save();

		// Field activity_active
		$this->activity_active->AdvancedSearch->SearchValue = @$filter["x_activity_active"];
		$this->activity_active->AdvancedSearch->SearchOperator = @$filter["z_activity_active"];
		$this->activity_active->AdvancedSearch->SearchCondition = @$filter["v_activity_active"];
		$this->activity_active->AdvancedSearch->SearchValue2 = @$filter["y_activity_active"];
		$this->activity_active->AdvancedSearch->SearchOperator2 = @$filter["w_activity_active"];
		$this->activity_active->AdvancedSearch->Save();

		// Field leader_username
		$this->leader_username->AdvancedSearch->SearchValue = @$filter["x_leader_username"];
		$this->leader_username->AdvancedSearch->SearchOperator = @$filter["z_leader_username"];
		$this->leader_username->AdvancedSearch->SearchCondition = @$filter["v_leader_username"];
		$this->leader_username->AdvancedSearch->SearchValue2 = @$filter["y_leader_username"];
		$this->leader_username->AdvancedSearch->SearchOperator2 = @$filter["w_leader_username"];
		$this->leader_username->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->activity_id, $Default, FALSE); // activity_id
		$this->BuildSearchSql($sWhere, $this->activity_name_ar, $Default, FALSE); // activity_name_ar
		$this->BuildSearchSql($sWhere, $this->activity_name_en, $Default, FALSE); // activity_name_en
		$this->BuildSearchSql($sWhere, $this->activity_start_date, $Default, FALSE); // activity_start_date
		$this->BuildSearchSql($sWhere, $this->activity_end_date, $Default, FALSE); // activity_end_date
		$this->BuildSearchSql($sWhere, $this->activity_time_ar, $Default, FALSE); // activity_time_ar
		$this->BuildSearchSql($sWhere, $this->activity_time_en, $Default, FALSE); // activity_time_en
		$this->BuildSearchSql($sWhere, $this->activity_description_ar, $Default, FALSE); // activity_description_ar
		$this->BuildSearchSql($sWhere, $this->activity_description_en, $Default, FALSE); // activity_description_en
		$this->BuildSearchSql($sWhere, $this->activity_persons, $Default, FALSE); // activity_persons
		$this->BuildSearchSql($sWhere, $this->activity_hours, $Default, FALSE); // activity_hours
		$this->BuildSearchSql($sWhere, $this->activity_city, $Default, FALSE); // activity_city
		$this->BuildSearchSql($sWhere, $this->activity_location_ar, $Default, FALSE); // activity_location_ar
		$this->BuildSearchSql($sWhere, $this->activity_location_en, $Default, FALSE); // activity_location_en
		$this->BuildSearchSql($sWhere, $this->activity_location_map, $Default, FALSE); // activity_location_map
		$this->BuildSearchSql($sWhere, $this->activity_image, $Default, FALSE); // activity_image
		$this->BuildSearchSql($sWhere, $this->activity_organizer_ar, $Default, FALSE); // activity_organizer_ar
		$this->BuildSearchSql($sWhere, $this->activity_organizer_en, $Default, FALSE); // activity_organizer_en
		$this->BuildSearchSql($sWhere, $this->activity_category_ar, $Default, FALSE); // activity_category_ar
		$this->BuildSearchSql($sWhere, $this->activity_category_en, $Default, FALSE); // activity_category_en
		$this->BuildSearchSql($sWhere, $this->activity_type, $Default, FALSE); // activity_type
		$this->BuildSearchSql($sWhere, $this->activity_gender_target, $Default, FALSE); // activity_gender_target
		$this->BuildSearchSql($sWhere, $this->activity_terms_and_conditions_ar, $Default, FALSE); // activity_terms_and_conditions_ar
		$this->BuildSearchSql($sWhere, $this->activity_terms_and_conditions_en, $Default, FALSE); // activity_terms_and_conditions_en
		$this->BuildSearchSql($sWhere, $this->activity_active, $Default, FALSE); // activity_active
		$this->BuildSearchSql($sWhere, $this->leader_username, $Default, FALSE); // leader_username

		// Set up search parm
		if (!$Default && $sWhere <> "" && $this->Command == "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->activity_id->AdvancedSearch->Save(); // activity_id
			$this->activity_name_ar->AdvancedSearch->Save(); // activity_name_ar
			$this->activity_name_en->AdvancedSearch->Save(); // activity_name_en
			$this->activity_start_date->AdvancedSearch->Save(); // activity_start_date
			$this->activity_end_date->AdvancedSearch->Save(); // activity_end_date
			$this->activity_time_ar->AdvancedSearch->Save(); // activity_time_ar
			$this->activity_time_en->AdvancedSearch->Save(); // activity_time_en
			$this->activity_description_ar->AdvancedSearch->Save(); // activity_description_ar
			$this->activity_description_en->AdvancedSearch->Save(); // activity_description_en
			$this->activity_persons->AdvancedSearch->Save(); // activity_persons
			$this->activity_hours->AdvancedSearch->Save(); // activity_hours
			$this->activity_city->AdvancedSearch->Save(); // activity_city
			$this->activity_location_ar->AdvancedSearch->Save(); // activity_location_ar
			$this->activity_location_en->AdvancedSearch->Save(); // activity_location_en
			$this->activity_location_map->AdvancedSearch->Save(); // activity_location_map
			$this->activity_image->AdvancedSearch->Save(); // activity_image
			$this->activity_organizer_ar->AdvancedSearch->Save(); // activity_organizer_ar
			$this->activity_organizer_en->AdvancedSearch->Save(); // activity_organizer_en
			$this->activity_category_ar->AdvancedSearch->Save(); // activity_category_ar
			$this->activity_category_en->AdvancedSearch->Save(); // activity_category_en
			$this->activity_type->AdvancedSearch->Save(); // activity_type
			$this->activity_gender_target->AdvancedSearch->Save(); // activity_gender_target
			$this->activity_terms_and_conditions_ar->AdvancedSearch->Save(); // activity_terms_and_conditions_ar
			$this->activity_terms_and_conditions_en->AdvancedSearch->Save(); // activity_terms_and_conditions_en
			$this->activity_active->AdvancedSearch->Save(); // activity_active
			$this->leader_username->AdvancedSearch->Save(); // leader_username
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
		$this->BuildBasicSearchSQL($sWhere, $this->activity_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_name_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_name_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_start_date, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_end_date, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_time_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_time_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_description_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_hours, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_city, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_location_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_location_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_location_map, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_image, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_organizer_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_organizer_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_category_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_category_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_type, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_gender_target, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_terms_and_conditions_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_terms_and_conditions_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->activity_active, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->leader_username, $arKeywords, $type);
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
		if ($this->activity_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_name_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_name_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_start_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_end_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_time_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_time_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_description_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_description_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_persons->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_hours->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_city->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_location_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_location_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_location_map->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_image->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_organizer_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_organizer_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_category_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_category_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_gender_target->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_terms_and_conditions_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_terms_and_conditions_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->activity_active->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->leader_username->AdvancedSearch->IssetSession())
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
		$this->activity_id->AdvancedSearch->UnsetSession();
		$this->activity_name_ar->AdvancedSearch->UnsetSession();
		$this->activity_name_en->AdvancedSearch->UnsetSession();
		$this->activity_start_date->AdvancedSearch->UnsetSession();
		$this->activity_end_date->AdvancedSearch->UnsetSession();
		$this->activity_time_ar->AdvancedSearch->UnsetSession();
		$this->activity_time_en->AdvancedSearch->UnsetSession();
		$this->activity_description_ar->AdvancedSearch->UnsetSession();
		$this->activity_description_en->AdvancedSearch->UnsetSession();
		$this->activity_persons->AdvancedSearch->UnsetSession();
		$this->activity_hours->AdvancedSearch->UnsetSession();
		$this->activity_city->AdvancedSearch->UnsetSession();
		$this->activity_location_ar->AdvancedSearch->UnsetSession();
		$this->activity_location_en->AdvancedSearch->UnsetSession();
		$this->activity_location_map->AdvancedSearch->UnsetSession();
		$this->activity_image->AdvancedSearch->UnsetSession();
		$this->activity_organizer_ar->AdvancedSearch->UnsetSession();
		$this->activity_organizer_en->AdvancedSearch->UnsetSession();
		$this->activity_category_ar->AdvancedSearch->UnsetSession();
		$this->activity_category_en->AdvancedSearch->UnsetSession();
		$this->activity_type->AdvancedSearch->UnsetSession();
		$this->activity_gender_target->AdvancedSearch->UnsetSession();
		$this->activity_terms_and_conditions_ar->AdvancedSearch->UnsetSession();
		$this->activity_terms_and_conditions_en->AdvancedSearch->UnsetSession();
		$this->activity_active->AdvancedSearch->UnsetSession();
		$this->leader_username->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->activity_id->AdvancedSearch->Load();
		$this->activity_name_ar->AdvancedSearch->Load();
		$this->activity_name_en->AdvancedSearch->Load();
		$this->activity_start_date->AdvancedSearch->Load();
		$this->activity_end_date->AdvancedSearch->Load();
		$this->activity_time_ar->AdvancedSearch->Load();
		$this->activity_time_en->AdvancedSearch->Load();
		$this->activity_description_ar->AdvancedSearch->Load();
		$this->activity_description_en->AdvancedSearch->Load();
		$this->activity_persons->AdvancedSearch->Load();
		$this->activity_hours->AdvancedSearch->Load();
		$this->activity_city->AdvancedSearch->Load();
		$this->activity_location_ar->AdvancedSearch->Load();
		$this->activity_location_en->AdvancedSearch->Load();
		$this->activity_location_map->AdvancedSearch->Load();
		$this->activity_image->AdvancedSearch->Load();
		$this->activity_organizer_ar->AdvancedSearch->Load();
		$this->activity_organizer_en->AdvancedSearch->Load();
		$this->activity_category_ar->AdvancedSearch->Load();
		$this->activity_category_en->AdvancedSearch->Load();
		$this->activity_type->AdvancedSearch->Load();
		$this->activity_gender_target->AdvancedSearch->Load();
		$this->activity_terms_and_conditions_ar->AdvancedSearch->Load();
		$this->activity_terms_and_conditions_en->AdvancedSearch->Load();
		$this->activity_active->AdvancedSearch->Load();
		$this->leader_username->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->activity_id); // activity_id
			$this->UpdateSort($this->activity_name_ar); // activity_name_ar
			$this->UpdateSort($this->activity_start_date); // activity_start_date
			$this->UpdateSort($this->activity_end_date); // activity_end_date
			$this->UpdateSort($this->activity_city); // activity_city
			$this->UpdateSort($this->activity_active); // activity_active
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
				$this->activity_id->setSort("DESC");
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
				$this->activity_id->setSort("");
				$this->activity_name_ar->setSort("");
				$this->activity_start_date->setSort("");
				$this->activity_end_date->setSort("");
				$this->activity_city->setSort("");
				$this->activity_active->setSort("");
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

		// "detail_registered_users"
		$item = &$this->ListOptions->Add("detail_registered_users");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'registered_users') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["registered_users_grid"])) $GLOBALS["registered_users_grid"] = new cregistered_users_grid;

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
		$pages->Add("registered_users");
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
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_registered_users"
		$oListOpt = &$this->ListOptions->Items["detail_registered_users"];
		if ($Security->AllowList(CurrentProjectID() . 'registered_users')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("registered_users", "TblCaption");
			$body .= "&nbsp;" . str_replace("%c", $this->registered_users_Count, $Language->Phrase("DetailCount"));
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("registered_userslist.php?" . EW_TABLE_SHOW_MASTER . "=activities&fk_activity_id=" . urlencode(strval($this->activity_id->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["registered_users_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'registered_users')) {
				$caption = $Language->Phrase("MasterDetailViewLink");
				$url = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=registered_users");
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "registered_users";
			}
			if ($GLOBALS["registered_users_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'registered_users')) {
				$caption = $Language->Phrase("MasterDetailEditLink");
				$url = $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=registered_users");
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "registered_users";
			}
			if ($GLOBALS["registered_users_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'registered_users')) {
				$caption = $Language->Phrase("MasterDetailCopyLink");
				$url = $this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=registered_users");
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . ew_HtmlImageAndText($caption) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "registered_users";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->activity_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item = &$option->Add("detailadd_registered_users");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=registered_users");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["registered_users"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["registered_users"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'registered_users') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "registered_users";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.factivitieslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"factivitieslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"factivitieslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.factivitieslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"factivitieslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"activitiessrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"activities\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'activitiessrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
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
		// activity_id

		$this->activity_id->AdvancedSearch->SearchValue = @$_GET["x_activity_id"];
		if ($this->activity_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_id->AdvancedSearch->SearchOperator = @$_GET["z_activity_id"];

		// activity_name_ar
		$this->activity_name_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_name_ar"];
		if ($this->activity_name_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_name_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_name_ar"];

		// activity_name_en
		$this->activity_name_en->AdvancedSearch->SearchValue = @$_GET["x_activity_name_en"];
		if ($this->activity_name_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_name_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_name_en"];

		// activity_start_date
		$this->activity_start_date->AdvancedSearch->SearchValue = @$_GET["x_activity_start_date"];
		if ($this->activity_start_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_start_date->AdvancedSearch->SearchOperator = @$_GET["z_activity_start_date"];

		// activity_end_date
		$this->activity_end_date->AdvancedSearch->SearchValue = @$_GET["x_activity_end_date"];
		if ($this->activity_end_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_end_date->AdvancedSearch->SearchOperator = @$_GET["z_activity_end_date"];

		// activity_time_ar
		$this->activity_time_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_time_ar"];
		if ($this->activity_time_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_time_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_time_ar"];

		// activity_time_en
		$this->activity_time_en->AdvancedSearch->SearchValue = @$_GET["x_activity_time_en"];
		if ($this->activity_time_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_time_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_time_en"];

		// activity_description_ar
		$this->activity_description_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_description_ar"];
		if ($this->activity_description_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_description_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_description_ar"];

		// activity_description_en
		$this->activity_description_en->AdvancedSearch->SearchValue = @$_GET["x_activity_description_en"];
		if ($this->activity_description_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_description_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_description_en"];

		// activity_persons
		$this->activity_persons->AdvancedSearch->SearchValue = @$_GET["x_activity_persons"];
		if ($this->activity_persons->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_persons->AdvancedSearch->SearchOperator = @$_GET["z_activity_persons"];

		// activity_hours
		$this->activity_hours->AdvancedSearch->SearchValue = @$_GET["x_activity_hours"];
		if ($this->activity_hours->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_hours->AdvancedSearch->SearchOperator = @$_GET["z_activity_hours"];

		// activity_city
		$this->activity_city->AdvancedSearch->SearchValue = @$_GET["x_activity_city"];
		if ($this->activity_city->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_city->AdvancedSearch->SearchOperator = @$_GET["z_activity_city"];

		// activity_location_ar
		$this->activity_location_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_location_ar"];
		if ($this->activity_location_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_location_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_location_ar"];

		// activity_location_en
		$this->activity_location_en->AdvancedSearch->SearchValue = @$_GET["x_activity_location_en"];
		if ($this->activity_location_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_location_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_location_en"];

		// activity_location_map
		$this->activity_location_map->AdvancedSearch->SearchValue = @$_GET["x_activity_location_map"];
		if ($this->activity_location_map->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_location_map->AdvancedSearch->SearchOperator = @$_GET["z_activity_location_map"];

		// activity_image
		$this->activity_image->AdvancedSearch->SearchValue = @$_GET["x_activity_image"];
		if ($this->activity_image->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_image->AdvancedSearch->SearchOperator = @$_GET["z_activity_image"];

		// activity_organizer_ar
		$this->activity_organizer_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_organizer_ar"];
		if ($this->activity_organizer_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_organizer_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_organizer_ar"];

		// activity_organizer_en
		$this->activity_organizer_en->AdvancedSearch->SearchValue = @$_GET["x_activity_organizer_en"];
		if ($this->activity_organizer_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_organizer_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_organizer_en"];

		// activity_category_ar
		$this->activity_category_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_category_ar"];
		if ($this->activity_category_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_category_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_category_ar"];

		// activity_category_en
		$this->activity_category_en->AdvancedSearch->SearchValue = @$_GET["x_activity_category_en"];
		if ($this->activity_category_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_category_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_category_en"];

		// activity_type
		$this->activity_type->AdvancedSearch->SearchValue = @$_GET["x_activity_type"];
		if ($this->activity_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_type->AdvancedSearch->SearchOperator = @$_GET["z_activity_type"];

		// activity_gender_target
		$this->activity_gender_target->AdvancedSearch->SearchValue = @$_GET["x_activity_gender_target"];
		if ($this->activity_gender_target->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_gender_target->AdvancedSearch->SearchOperator = @$_GET["z_activity_gender_target"];

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchValue = @$_GET["x_activity_terms_and_conditions_ar"];
		if ($this->activity_terms_and_conditions_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_terms_and_conditions_ar->AdvancedSearch->SearchOperator = @$_GET["z_activity_terms_and_conditions_ar"];

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchValue = @$_GET["x_activity_terms_and_conditions_en"];
		if ($this->activity_terms_and_conditions_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_terms_and_conditions_en->AdvancedSearch->SearchOperator = @$_GET["z_activity_terms_and_conditions_en"];

		// activity_active
		$this->activity_active->AdvancedSearch->SearchValue = @$_GET["x_activity_active"];
		if ($this->activity_active->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->activity_active->AdvancedSearch->SearchOperator = @$_GET["z_activity_active"];

		// leader_username
		$this->leader_username->AdvancedSearch->SearchValue = @$_GET["x_leader_username"];
		if ($this->leader_username->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->leader_username->AdvancedSearch->SearchOperator = @$_GET["z_leader_username"];
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
		$this->activity_id->setDbValue($row['activity_id']);
		$this->activity_name_ar->setDbValue($row['activity_name_ar']);
		$this->activity_name_en->setDbValue($row['activity_name_en']);
		$this->activity_start_date->setDbValue($row['activity_start_date']);
		$this->activity_end_date->setDbValue($row['activity_end_date']);
		$this->activity_time_ar->setDbValue($row['activity_time_ar']);
		$this->activity_time_en->setDbValue($row['activity_time_en']);
		$this->activity_description_ar->setDbValue($row['activity_description_ar']);
		$this->activity_description_en->setDbValue($row['activity_description_en']);
		$this->activity_persons->setDbValue($row['activity_persons']);
		$this->activity_hours->setDbValue($row['activity_hours']);
		$this->activity_city->setDbValue($row['activity_city']);
		$this->activity_location_ar->setDbValue($row['activity_location_ar']);
		$this->activity_location_en->setDbValue($row['activity_location_en']);
		$this->activity_location_map->setDbValue($row['activity_location_map']);
		$this->activity_image->Upload->DbValue = $row['activity_image'];
		$this->activity_image->CurrentValue = $this->activity_image->Upload->DbValue;
		$this->activity_organizer_ar->setDbValue($row['activity_organizer_ar']);
		$this->activity_organizer_en->setDbValue($row['activity_organizer_en']);
		$this->activity_category_ar->setDbValue($row['activity_category_ar']);
		$this->activity_category_en->setDbValue($row['activity_category_en']);
		$this->activity_type->setDbValue($row['activity_type']);
		$this->activity_gender_target->setDbValue($row['activity_gender_target']);
		$this->activity_terms_and_conditions_ar->setDbValue($row['activity_terms_and_conditions_ar']);
		$this->activity_terms_and_conditions_en->setDbValue($row['activity_terms_and_conditions_en']);
		$this->activity_active->setDbValue($row['activity_active']);
		$this->leader_username->setDbValue($row['leader_username']);
		if (!isset($GLOBALS["registered_users_grid"])) $GLOBALS["registered_users_grid"] = new cregistered_users_grid;
		$sDetailFilter = $GLOBALS["registered_users"]->SqlDetailFilter_activities();
		$sDetailFilter = str_replace("@activity_id@", ew_AdjustSql($this->activity_id->DbValue, "DB"), $sDetailFilter);
		$GLOBALS["registered_users"]->setCurrentMasterTable("activities");
		$sDetailFilter = $GLOBALS["registered_users"]->ApplyUserIDFilters($sDetailFilter);
		$this->registered_users_Count = $GLOBALS["registered_users"]->LoadRecordCount($sDetailFilter);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['activity_id'] = NULL;
		$row['activity_name_ar'] = NULL;
		$row['activity_name_en'] = NULL;
		$row['activity_start_date'] = NULL;
		$row['activity_end_date'] = NULL;
		$row['activity_time_ar'] = NULL;
		$row['activity_time_en'] = NULL;
		$row['activity_description_ar'] = NULL;
		$row['activity_description_en'] = NULL;
		$row['activity_persons'] = NULL;
		$row['activity_hours'] = NULL;
		$row['activity_city'] = NULL;
		$row['activity_location_ar'] = NULL;
		$row['activity_location_en'] = NULL;
		$row['activity_location_map'] = NULL;
		$row['activity_image'] = NULL;
		$row['activity_organizer_ar'] = NULL;
		$row['activity_organizer_en'] = NULL;
		$row['activity_category_ar'] = NULL;
		$row['activity_category_en'] = NULL;
		$row['activity_type'] = NULL;
		$row['activity_gender_target'] = NULL;
		$row['activity_terms_and_conditions_ar'] = NULL;
		$row['activity_terms_and_conditions_en'] = NULL;
		$row['activity_active'] = NULL;
		$row['leader_username'] = NULL;
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
		$this->activity_id->DbValue = $row['activity_id'];
		$this->activity_name_ar->DbValue = $row['activity_name_ar'];
		$this->activity_name_en->DbValue = $row['activity_name_en'];
		$this->activity_start_date->DbValue = $row['activity_start_date'];
		$this->activity_end_date->DbValue = $row['activity_end_date'];
		$this->activity_time_ar->DbValue = $row['activity_time_ar'];
		$this->activity_time_en->DbValue = $row['activity_time_en'];
		$this->activity_description_ar->DbValue = $row['activity_description_ar'];
		$this->activity_description_en->DbValue = $row['activity_description_en'];
		$this->activity_persons->DbValue = $row['activity_persons'];
		$this->activity_hours->DbValue = $row['activity_hours'];
		$this->activity_city->DbValue = $row['activity_city'];
		$this->activity_location_ar->DbValue = $row['activity_location_ar'];
		$this->activity_location_en->DbValue = $row['activity_location_en'];
		$this->activity_location_map->DbValue = $row['activity_location_map'];
		$this->activity_image->Upload->DbValue = $row['activity_image'];
		$this->activity_organizer_ar->DbValue = $row['activity_organizer_ar'];
		$this->activity_organizer_en->DbValue = $row['activity_organizer_en'];
		$this->activity_category_ar->DbValue = $row['activity_category_ar'];
		$this->activity_category_en->DbValue = $row['activity_category_en'];
		$this->activity_type->DbValue = $row['activity_type'];
		$this->activity_gender_target->DbValue = $row['activity_gender_target'];
		$this->activity_terms_and_conditions_ar->DbValue = $row['activity_terms_and_conditions_ar'];
		$this->activity_terms_and_conditions_en->DbValue = $row['activity_terms_and_conditions_en'];
		$this->activity_active->DbValue = $row['activity_active'];
		$this->leader_username->DbValue = $row['leader_username'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("activity_id")) <> "")
			$this->activity_id->CurrentValue = $this->getKey("activity_id"); // activity_id
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
		// activity_id
		// activity_name_ar
		// activity_name_en
		// activity_start_date
		// activity_end_date
		// activity_time_ar
		// activity_time_en
		// activity_description_ar

		$this->activity_description_ar->CellCssStyle = "white-space: nowrap;";

		// activity_description_en
		// activity_persons
		// activity_hours
		// activity_city
		// activity_location_ar
		// activity_location_en
		// activity_location_map
		// activity_image
		// activity_organizer_ar
		// activity_organizer_en
		// activity_category_ar
		// activity_category_en
		// activity_type
		// activity_gender_target
		// activity_terms_and_conditions_ar
		// activity_terms_and_conditions_en
		// activity_active
		// leader_username

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// activity_id
		$this->activity_id->ViewValue = $this->activity_id->CurrentValue;
		$this->activity_id->ViewCustomAttributes = "";

		// activity_name_ar
		$this->activity_name_ar->ViewValue = $this->activity_name_ar->CurrentValue;
		$this->activity_name_ar->ViewCustomAttributes = "";

		// activity_name_en
		$this->activity_name_en->ViewValue = $this->activity_name_en->CurrentValue;
		$this->activity_name_en->ViewCustomAttributes = "";

		// activity_start_date
		$this->activity_start_date->ViewValue = $this->activity_start_date->CurrentValue;
		$this->activity_start_date->ViewValue = ew_FormatDateTime($this->activity_start_date->ViewValue, 0);
		$this->activity_start_date->ViewCustomAttributes = "";

		// activity_end_date
		$this->activity_end_date->ViewValue = $this->activity_end_date->CurrentValue;
		$this->activity_end_date->ViewValue = ew_FormatDateTime($this->activity_end_date->ViewValue, 0);
		$this->activity_end_date->ViewCustomAttributes = "";

		// activity_time_ar
		$this->activity_time_ar->ViewValue = $this->activity_time_ar->CurrentValue;
		$this->activity_time_ar->ViewCustomAttributes = "";

		// activity_time_en
		$this->activity_time_en->ViewValue = $this->activity_time_en->CurrentValue;
		$this->activity_time_en->ViewCustomAttributes = "";

		// activity_description_ar
		$this->activity_description_ar->ViewValue = $this->activity_description_ar->CurrentValue;
		$this->activity_description_ar->ViewCustomAttributes = "";

		// activity_description_en
		$this->activity_description_en->ViewValue = $this->activity_description_en->CurrentValue;
		$this->activity_description_en->ViewCustomAttributes = "";

		// activity_persons
		$this->activity_persons->ViewValue = $this->activity_persons->CurrentValue;
		$this->activity_persons->ViewCustomAttributes = "";

		// activity_hours
		$this->activity_hours->ViewValue = $this->activity_hours->CurrentValue;
		$this->activity_hours->ViewCustomAttributes = "";

		// activity_city
		if (strval($this->activity_city->CurrentValue) <> "") {
			$this->activity_city->ViewValue = $this->activity_city->OptionCaption($this->activity_city->CurrentValue);
		} else {
			$this->activity_city->ViewValue = NULL;
		}
		$this->activity_city->ViewCustomAttributes = "";

		// activity_location_ar
		$this->activity_location_ar->ViewValue = $this->activity_location_ar->CurrentValue;
		$this->activity_location_ar->ViewCustomAttributes = "";

		// activity_location_en
		$this->activity_location_en->ViewValue = $this->activity_location_en->CurrentValue;
		$this->activity_location_en->ViewCustomAttributes = "";

		// activity_location_map
		$this->activity_location_map->ViewValue = $this->activity_location_map->CurrentValue;
		$this->activity_location_map->ViewCustomAttributes = "";

		// activity_image
		$this->activity_image->UploadPath = "../images";
		if (!ew_Empty($this->activity_image->Upload->DbValue)) {
			$this->activity_image->ImageWidth = 100;
			$this->activity_image->ImageHeight = 0;
			$this->activity_image->ImageAlt = $this->activity_image->FldAlt();
			$this->activity_image->ViewValue = $this->activity_image->Upload->DbValue;
		} else {
			$this->activity_image->ViewValue = "";
		}
		$this->activity_image->ViewCustomAttributes = "";

		// activity_organizer_ar
		$this->activity_organizer_ar->ViewValue = $this->activity_organizer_ar->CurrentValue;
		$this->activity_organizer_ar->ViewCustomAttributes = "";

		// activity_organizer_en
		$this->activity_organizer_en->ViewValue = $this->activity_organizer_en->CurrentValue;
		$this->activity_organizer_en->ViewCustomAttributes = "";

		// activity_category_ar
		$this->activity_category_ar->ViewValue = $this->activity_category_ar->CurrentValue;
		$this->activity_category_ar->ViewCustomAttributes = "";

		// activity_category_en
		$this->activity_category_en->ViewValue = $this->activity_category_en->CurrentValue;
		$this->activity_category_en->ViewCustomAttributes = "";

		// activity_type
		if (strval($this->activity_type->CurrentValue) <> "") {
			$this->activity_type->ViewValue = $this->activity_type->OptionCaption($this->activity_type->CurrentValue);
		} else {
			$this->activity_type->ViewValue = NULL;
		}
		$this->activity_type->ViewCustomAttributes = "";

		// activity_gender_target
		if (strval($this->activity_gender_target->CurrentValue) <> "") {
			$this->activity_gender_target->ViewValue = $this->activity_gender_target->OptionCaption($this->activity_gender_target->CurrentValue);
		} else {
			$this->activity_gender_target->ViewValue = NULL;
		}
		$this->activity_gender_target->ViewCustomAttributes = "";

		// activity_terms_and_conditions_ar
		$this->activity_terms_and_conditions_ar->ViewValue = $this->activity_terms_and_conditions_ar->CurrentValue;
		$this->activity_terms_and_conditions_ar->ViewCustomAttributes = "";

		// activity_terms_and_conditions_en
		$this->activity_terms_and_conditions_en->ViewValue = $this->activity_terms_and_conditions_en->CurrentValue;
		$this->activity_terms_and_conditions_en->ViewCustomAttributes = "";

		// activity_active
		if (strval($this->activity_active->CurrentValue) <> "") {
			$this->activity_active->ViewValue = $this->activity_active->OptionCaption($this->activity_active->CurrentValue);
		} else {
			$this->activity_active->ViewValue = NULL;
		}
		$this->activity_active->ViewCustomAttributes = "";

		// leader_username
		$this->leader_username->ViewValue = $this->leader_username->CurrentValue;
		if (strval($this->leader_username->CurrentValue) <> "") {
			$sFilterWrk = "`user_id`" . ew_SearchString("=", $this->leader_username->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `user_id`, `full_name_ar` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `users`";
		$sWhereWrk = "";
		$this->leader_username->LookupFilters = array("dx1" => '`full_name_ar`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->leader_username, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->leader_username->ViewValue = $this->leader_username->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->leader_username->ViewValue = $this->leader_username->CurrentValue;
			}
		} else {
			$this->leader_username->ViewValue = NULL;
		}
		$this->leader_username->ViewCustomAttributes = "";

			// activity_id
			$this->activity_id->LinkCustomAttributes = "";
			$this->activity_id->HrefValue = "";
			$this->activity_id->TooltipValue = "";

			// activity_name_ar
			$this->activity_name_ar->LinkCustomAttributes = "";
			$this->activity_name_ar->HrefValue = "";
			$this->activity_name_ar->TooltipValue = "";

			// activity_start_date
			$this->activity_start_date->LinkCustomAttributes = "";
			$this->activity_start_date->HrefValue = "";
			$this->activity_start_date->TooltipValue = "";

			// activity_end_date
			$this->activity_end_date->LinkCustomAttributes = "";
			$this->activity_end_date->HrefValue = "";
			$this->activity_end_date->TooltipValue = "";

			// activity_city
			$this->activity_city->LinkCustomAttributes = "";
			$this->activity_city->HrefValue = "";
			$this->activity_city->TooltipValue = "";

			// activity_active
			$this->activity_active->LinkCustomAttributes = "";
			$this->activity_active->HrefValue = "";
			$this->activity_active->TooltipValue = "";
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
		$this->activity_id->AdvancedSearch->Load();
		$this->activity_name_ar->AdvancedSearch->Load();
		$this->activity_name_en->AdvancedSearch->Load();
		$this->activity_start_date->AdvancedSearch->Load();
		$this->activity_end_date->AdvancedSearch->Load();
		$this->activity_time_ar->AdvancedSearch->Load();
		$this->activity_time_en->AdvancedSearch->Load();
		$this->activity_description_ar->AdvancedSearch->Load();
		$this->activity_description_en->AdvancedSearch->Load();
		$this->activity_persons->AdvancedSearch->Load();
		$this->activity_hours->AdvancedSearch->Load();
		$this->activity_city->AdvancedSearch->Load();
		$this->activity_location_ar->AdvancedSearch->Load();
		$this->activity_location_en->AdvancedSearch->Load();
		$this->activity_location_map->AdvancedSearch->Load();
		$this->activity_image->AdvancedSearch->Load();
		$this->activity_organizer_ar->AdvancedSearch->Load();
		$this->activity_organizer_en->AdvancedSearch->Load();
		$this->activity_category_ar->AdvancedSearch->Load();
		$this->activity_category_en->AdvancedSearch->Load();
		$this->activity_type->AdvancedSearch->Load();
		$this->activity_gender_target->AdvancedSearch->Load();
		$this->activity_terms_and_conditions_ar->AdvancedSearch->Load();
		$this->activity_terms_and_conditions_en->AdvancedSearch->Load();
		$this->activity_active->AdvancedSearch->Load();
		$this->leader_username->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_activities\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_activities',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.factivitieslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($activities_list)) $activities_list = new cactivities_list();

// Page init
$activities_list->Page_Init();

// Page main
$activities_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$activities_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($activities->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = factivitieslist = new ew_Form("factivitieslist", "list");
factivitieslist.FormKeyCountName = '<?php echo $activities_list->FormKeyCountName ?>';

// Form_CustomValidate event
factivitieslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factivitieslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factivitieslist.Lists["x_activity_city"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitieslist.Lists["x_activity_city"].Options = <?php echo json_encode($activities_list->activity_city->Options()) ?>;
factivitieslist.Lists["x_activity_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitieslist.Lists["x_activity_active"].Options = <?php echo json_encode($activities_list->activity_active->Options()) ?>;

// Form object for search
var CurrentSearchForm = factivitieslistsrch = new ew_Form("factivitieslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($activities->Export == "") { ?>
<div class="ewToolbar">
<?php if ($activities_list->TotalRecs > 0 && $activities_list->ExportOptions->Visible()) { ?>
<?php $activities_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($activities_list->SearchOptions->Visible()) { ?>
<?php $activities_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($activities_list->FilterOptions->Visible()) { ?>
<?php $activities_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $activities_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($activities_list->TotalRecs <= 0)
			$activities_list->TotalRecs = $activities->ListRecordCount();
	} else {
		if (!$activities_list->Recordset && ($activities_list->Recordset = $activities_list->LoadRecordset()))
			$activities_list->TotalRecs = $activities_list->Recordset->RecordCount();
	}
	$activities_list->StartRec = 1;
	if ($activities_list->DisplayRecs <= 0 || ($activities->Export <> "" && $activities->ExportAll)) // Display all records
		$activities_list->DisplayRecs = $activities_list->TotalRecs;
	if (!($activities->Export <> "" && $activities->ExportAll))
		$activities_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$activities_list->Recordset = $activities_list->LoadRecordset($activities_list->StartRec-1, $activities_list->DisplayRecs);

	// Set no record found message
	if ($activities->CurrentAction == "" && $activities_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$activities_list->setWarningMessage(ew_DeniedMsg());
		if ($activities_list->SearchWhere == "0=101")
			$activities_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$activities_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($activities_list->AuditTrailOnSearch && $activities_list->Command == "search" && !$activities_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $activities_list->getSessionWhere();
		$activities_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$activities_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($activities->Export == "" && $activities->CurrentAction == "") { ?>
<form name="factivitieslistsrch" id="factivitieslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($activities_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="factivitieslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="activities">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($activities_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($activities_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $activities_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($activities_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($activities_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($activities_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($activities_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $activities_list->ShowPageHeader(); ?>
<?php
$activities_list->ShowMessage();
?>
<?php if ($activities_list->TotalRecs > 0 || $activities->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($activities_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> activities">
<?php if ($activities->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($activities->CurrentAction <> "gridadd" && $activities->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($activities_list->Pager)) $activities_list->Pager = new cPrevNextPager($activities_list->StartRec, $activities_list->DisplayRecs, $activities_list->TotalRecs, $activities_list->AutoHidePager) ?>
<?php if ($activities_list->Pager->RecordCount > 0 && $activities_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($activities_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($activities_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $activities_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($activities_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($activities_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $activities_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $activities_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $activities_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $activities_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($activities_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="factivitieslist" id="factivitieslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($activities_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $activities_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="activities">
<div id="gmp_activities" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($activities_list->TotalRecs > 0 || $activities->CurrentAction == "gridedit") { ?>
<table id="tbl_activitieslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$activities_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$activities_list->RenderListOptions();

// Render list options (header, left)
$activities_list->ListOptions->Render("header", "left");
?>
<?php if ($activities->activity_id->Visible) { // activity_id ?>
	<?php if ($activities->SortUrl($activities->activity_id) == "") { ?>
		<th data-name="activity_id" class="<?php echo $activities->activity_id->HeaderCellClass() ?>"><div id="elh_activities_activity_id" class="activities_activity_id"><div class="ewTableHeaderCaption"><?php echo $activities->activity_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_id" class="<?php echo $activities->activity_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $activities->SortUrl($activities->activity_id) ?>',1);"><div id="elh_activities_activity_id" class="activities_activity_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $activities->activity_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($activities->activity_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($activities->activity_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($activities->activity_name_ar->Visible) { // activity_name_ar ?>
	<?php if ($activities->SortUrl($activities->activity_name_ar) == "") { ?>
		<th data-name="activity_name_ar" class="<?php echo $activities->activity_name_ar->HeaderCellClass() ?>"><div id="elh_activities_activity_name_ar" class="activities_activity_name_ar"><div class="ewTableHeaderCaption"><?php echo $activities->activity_name_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_name_ar" class="<?php echo $activities->activity_name_ar->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $activities->SortUrl($activities->activity_name_ar) ?>',1);"><div id="elh_activities_activity_name_ar" class="activities_activity_name_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $activities->activity_name_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($activities->activity_name_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($activities->activity_name_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($activities->activity_start_date->Visible) { // activity_start_date ?>
	<?php if ($activities->SortUrl($activities->activity_start_date) == "") { ?>
		<th data-name="activity_start_date" class="<?php echo $activities->activity_start_date->HeaderCellClass() ?>"><div id="elh_activities_activity_start_date" class="activities_activity_start_date"><div class="ewTableHeaderCaption"><?php echo $activities->activity_start_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_start_date" class="<?php echo $activities->activity_start_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $activities->SortUrl($activities->activity_start_date) ?>',1);"><div id="elh_activities_activity_start_date" class="activities_activity_start_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $activities->activity_start_date->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($activities->activity_start_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($activities->activity_start_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($activities->activity_end_date->Visible) { // activity_end_date ?>
	<?php if ($activities->SortUrl($activities->activity_end_date) == "") { ?>
		<th data-name="activity_end_date" class="<?php echo $activities->activity_end_date->HeaderCellClass() ?>"><div id="elh_activities_activity_end_date" class="activities_activity_end_date"><div class="ewTableHeaderCaption"><?php echo $activities->activity_end_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_end_date" class="<?php echo $activities->activity_end_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $activities->SortUrl($activities->activity_end_date) ?>',1);"><div id="elh_activities_activity_end_date" class="activities_activity_end_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $activities->activity_end_date->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($activities->activity_end_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($activities->activity_end_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($activities->activity_city->Visible) { // activity_city ?>
	<?php if ($activities->SortUrl($activities->activity_city) == "") { ?>
		<th data-name="activity_city" class="<?php echo $activities->activity_city->HeaderCellClass() ?>"><div id="elh_activities_activity_city" class="activities_activity_city"><div class="ewTableHeaderCaption"><?php echo $activities->activity_city->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_city" class="<?php echo $activities->activity_city->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $activities->SortUrl($activities->activity_city) ?>',1);"><div id="elh_activities_activity_city" class="activities_activity_city">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $activities->activity_city->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($activities->activity_city->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($activities->activity_city->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($activities->activity_active->Visible) { // activity_active ?>
	<?php if ($activities->SortUrl($activities->activity_active) == "") { ?>
		<th data-name="activity_active" class="<?php echo $activities->activity_active->HeaderCellClass() ?>"><div id="elh_activities_activity_active" class="activities_activity_active"><div class="ewTableHeaderCaption"><?php echo $activities->activity_active->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="activity_active" class="<?php echo $activities->activity_active->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $activities->SortUrl($activities->activity_active) ?>',1);"><div id="elh_activities_activity_active" class="activities_activity_active">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $activities->activity_active->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($activities->activity_active->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($activities->activity_active->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$activities_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($activities->ExportAll && $activities->Export <> "") {
	$activities_list->StopRec = $activities_list->TotalRecs;
} else {

	// Set the last record to display
	if ($activities_list->TotalRecs > $activities_list->StartRec + $activities_list->DisplayRecs - 1)
		$activities_list->StopRec = $activities_list->StartRec + $activities_list->DisplayRecs - 1;
	else
		$activities_list->StopRec = $activities_list->TotalRecs;
}
$activities_list->RecCnt = $activities_list->StartRec - 1;
if ($activities_list->Recordset && !$activities_list->Recordset->EOF) {
	$activities_list->Recordset->MoveFirst();
	$bSelectLimit = $activities_list->UseSelectLimit;
	if (!$bSelectLimit && $activities_list->StartRec > 1)
		$activities_list->Recordset->Move($activities_list->StartRec - 1);
} elseif (!$activities->AllowAddDeleteRow && $activities_list->StopRec == 0) {
	$activities_list->StopRec = $activities->GridAddRowCount;
}

// Initialize aggregate
$activities->RowType = EW_ROWTYPE_AGGREGATEINIT;
$activities->ResetAttrs();
$activities_list->RenderRow();
while ($activities_list->RecCnt < $activities_list->StopRec) {
	$activities_list->RecCnt++;
	if (intval($activities_list->RecCnt) >= intval($activities_list->StartRec)) {
		$activities_list->RowCnt++;

		// Set up key count
		$activities_list->KeyCount = $activities_list->RowIndex;

		// Init row class and style
		$activities->ResetAttrs();
		$activities->CssClass = "";
		if ($activities->CurrentAction == "gridadd") {
		} else {
			$activities_list->LoadRowValues($activities_list->Recordset); // Load row values
		}
		$activities->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$activities->RowAttrs = array_merge($activities->RowAttrs, array('data-rowindex'=>$activities_list->RowCnt, 'id'=>'r' . $activities_list->RowCnt . '_activities', 'data-rowtype'=>$activities->RowType));

		// Render row
		$activities_list->RenderRow();

		// Render list options
		$activities_list->RenderListOptions();
?>
	<tr<?php echo $activities->RowAttributes() ?>>
<?php

// Render list options (body, left)
$activities_list->ListOptions->Render("body", "left", $activities_list->RowCnt);
?>
	<?php if ($activities->activity_id->Visible) { // activity_id ?>
		<td data-name="activity_id"<?php echo $activities->activity_id->CellAttributes() ?>>
<span id="el<?php echo $activities_list->RowCnt ?>_activities_activity_id" class="activities_activity_id">
<span<?php echo $activities->activity_id->ViewAttributes() ?>>
<?php echo $activities->activity_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($activities->activity_name_ar->Visible) { // activity_name_ar ?>
		<td data-name="activity_name_ar"<?php echo $activities->activity_name_ar->CellAttributes() ?>>
<span id="el<?php echo $activities_list->RowCnt ?>_activities_activity_name_ar" class="activities_activity_name_ar">
<span<?php echo $activities->activity_name_ar->ViewAttributes() ?>>
<?php echo $activities->activity_name_ar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($activities->activity_start_date->Visible) { // activity_start_date ?>
		<td data-name="activity_start_date"<?php echo $activities->activity_start_date->CellAttributes() ?>>
<span id="el<?php echo $activities_list->RowCnt ?>_activities_activity_start_date" class="activities_activity_start_date">
<span<?php echo $activities->activity_start_date->ViewAttributes() ?>>
<?php echo $activities->activity_start_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($activities->activity_end_date->Visible) { // activity_end_date ?>
		<td data-name="activity_end_date"<?php echo $activities->activity_end_date->CellAttributes() ?>>
<span id="el<?php echo $activities_list->RowCnt ?>_activities_activity_end_date" class="activities_activity_end_date">
<span<?php echo $activities->activity_end_date->ViewAttributes() ?>>
<?php echo $activities->activity_end_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($activities->activity_city->Visible) { // activity_city ?>
		<td data-name="activity_city"<?php echo $activities->activity_city->CellAttributes() ?>>
<span id="el<?php echo $activities_list->RowCnt ?>_activities_activity_city" class="activities_activity_city">
<span<?php echo $activities->activity_city->ViewAttributes() ?>>
<?php echo $activities->activity_city->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($activities->activity_active->Visible) { // activity_active ?>
		<td data-name="activity_active"<?php echo $activities->activity_active->CellAttributes() ?>>
<span id="el<?php echo $activities_list->RowCnt ?>_activities_activity_active" class="activities_activity_active">
<span<?php echo $activities->activity_active->ViewAttributes() ?>>
<?php echo $activities->activity_active->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$activities_list->ListOptions->Render("body", "right", $activities_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($activities->CurrentAction <> "gridadd")
		$activities_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($activities->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($activities_list->Recordset)
	$activities_list->Recordset->Close();
?>
<?php if ($activities->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($activities->CurrentAction <> "gridadd" && $activities->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($activities_list->Pager)) $activities_list->Pager = new cPrevNextPager($activities_list->StartRec, $activities_list->DisplayRecs, $activities_list->TotalRecs, $activities_list->AutoHidePager) ?>
<?php if ($activities_list->Pager->RecordCount > 0 && $activities_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($activities_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($activities_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $activities_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($activities_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($activities_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $activities_list->PageUrl() ?>start=<?php echo $activities_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $activities_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $activities_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $activities_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $activities_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($activities_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($activities_list->TotalRecs == 0 && $activities->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($activities_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($activities->Export == "") { ?>
<script type="text/javascript">
factivitieslistsrch.FilterList = <?php echo $activities_list->GetFilterList() ?>;
factivitieslistsrch.Init();
factivitieslist.Init();
</script>
<?php } ?>
<?php
$activities_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($activities->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$activities_list->Page_Terminate();
?>
