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

$institutions_list = NULL; // Initialize page object first

class cinstitutions_list extends cinstitutions {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'institutions';

	// Page object name
	var $PageObjName = 'institutions_list';

	// Grid form hidden field names
	var $FormName = 'finstitutionslist';
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

		// Table object (institutions)
		if (!isset($GLOBALS["institutions"]) || get_class($GLOBALS["institutions"]) == "cinstitutions") {
			$GLOBALS["institutions"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["institutions"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "institutionsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "institutionsdelete.php";
		$this->MultiUpdateUrl = "institutionsupdate.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption finstitutionslistsrch";

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
		$this->institution_id->SetVisibility();
		$this->institution_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->full_name_ar->SetVisibility();
		$this->full_name_en->SetVisibility();
		$this->institution_type->SetVisibility();
		$this->institutes_name->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->forward_to_dep->SetVisibility();
		$this->eco_department_approval->SetVisibility();
		$this->eco_departmnet_comment->SetVisibility();

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
			$this->institution_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->institution_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "finstitutionslistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->institution_id->AdvancedSearch->ToJson(), ","); // Field institution_id
		$sFilterList = ew_Concat($sFilterList, $this->full_name_ar->AdvancedSearch->ToJson(), ","); // Field full_name_ar
		$sFilterList = ew_Concat($sFilterList, $this->full_name_en->AdvancedSearch->ToJson(), ","); // Field full_name_en
		$sFilterList = ew_Concat($sFilterList, $this->institution_type->AdvancedSearch->ToJson(), ","); // Field institution_type
		$sFilterList = ew_Concat($sFilterList, $this->institutes_name->AdvancedSearch->ToJson(), ","); // Field institutes_name
		$sFilterList = ew_Concat($sFilterList, $this->volunteering_type->AdvancedSearch->ToJson(), ","); // Field volunteering_type
		$sFilterList = ew_Concat($sFilterList, $this->licence_no->AdvancedSearch->ToJson(), ","); // Field licence_no
		$sFilterList = ew_Concat($sFilterList, $this->trade_licence->AdvancedSearch->ToJson(), ","); // Field trade_licence
		$sFilterList = ew_Concat($sFilterList, $this->tl_expiry_date->AdvancedSearch->ToJson(), ","); // Field tl_expiry_date
		$sFilterList = ew_Concat($sFilterList, $this->nationality_type->AdvancedSearch->ToJson(), ","); // Field nationality_type
		$sFilterList = ew_Concat($sFilterList, $this->nationality->AdvancedSearch->ToJson(), ","); // Field nationality
		$sFilterList = ew_Concat($sFilterList, $this->visa_expiry_date->AdvancedSearch->ToJson(), ","); // Field visa_expiry_date
		$sFilterList = ew_Concat($sFilterList, $this->unid->AdvancedSearch->ToJson(), ","); // Field unid
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
		$sFilterList = ew_Concat($sFilterList, $this->work_phone->AdvancedSearch->ToJson(), ","); // Field work_phone
		$sFilterList = ew_Concat($sFilterList, $this->mobile_phone->AdvancedSearch->ToJson(), ","); // Field mobile_phone
		$sFilterList = ew_Concat($sFilterList, $this->fax->AdvancedSearch->ToJson(), ","); // Field fax
		$sFilterList = ew_Concat($sFilterList, $this->pobbox->AdvancedSearch->ToJson(), ","); // Field pobbox
		$sFilterList = ew_Concat($sFilterList, $this->_email->AdvancedSearch->ToJson(), ","); // Field email
		$sFilterList = ew_Concat($sFilterList, $this->password->AdvancedSearch->ToJson(), ","); // Field password
		$sFilterList = ew_Concat($sFilterList, $this->admin_approval->AdvancedSearch->ToJson(), ","); // Field admin_approval
		$sFilterList = ew_Concat($sFilterList, $this->admin_comment->AdvancedSearch->ToJson(), ","); // Field admin_comment
		$sFilterList = ew_Concat($sFilterList, $this->forward_to_dep->AdvancedSearch->ToJson(), ","); // Field forward_to_dep
		$sFilterList = ew_Concat($sFilterList, $this->eco_department_approval->AdvancedSearch->ToJson(), ","); // Field eco_department_approval
		$sFilterList = ew_Concat($sFilterList, $this->eco_departmnet_comment->AdvancedSearch->ToJson(), ","); // Field eco_departmnet_comment
		$sFilterList = ew_Concat($sFilterList, $this->security_approval->AdvancedSearch->ToJson(), ","); // Field security_approval
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "finstitutionslistsrch", $filters);

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

		// Field institution_id
		$this->institution_id->AdvancedSearch->SearchValue = @$filter["x_institution_id"];
		$this->institution_id->AdvancedSearch->SearchOperator = @$filter["z_institution_id"];
		$this->institution_id->AdvancedSearch->SearchCondition = @$filter["v_institution_id"];
		$this->institution_id->AdvancedSearch->SearchValue2 = @$filter["y_institution_id"];
		$this->institution_id->AdvancedSearch->SearchOperator2 = @$filter["w_institution_id"];
		$this->institution_id->AdvancedSearch->Save();

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

		// Field institution_type
		$this->institution_type->AdvancedSearch->SearchValue = @$filter["x_institution_type"];
		$this->institution_type->AdvancedSearch->SearchOperator = @$filter["z_institution_type"];
		$this->institution_type->AdvancedSearch->SearchCondition = @$filter["v_institution_type"];
		$this->institution_type->AdvancedSearch->SearchValue2 = @$filter["y_institution_type"];
		$this->institution_type->AdvancedSearch->SearchOperator2 = @$filter["w_institution_type"];
		$this->institution_type->AdvancedSearch->Save();

		// Field institutes_name
		$this->institutes_name->AdvancedSearch->SearchValue = @$filter["x_institutes_name"];
		$this->institutes_name->AdvancedSearch->SearchOperator = @$filter["z_institutes_name"];
		$this->institutes_name->AdvancedSearch->SearchCondition = @$filter["v_institutes_name"];
		$this->institutes_name->AdvancedSearch->SearchValue2 = @$filter["y_institutes_name"];
		$this->institutes_name->AdvancedSearch->SearchOperator2 = @$filter["w_institutes_name"];
		$this->institutes_name->AdvancedSearch->Save();

		// Field volunteering_type
		$this->volunteering_type->AdvancedSearch->SearchValue = @$filter["x_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchOperator = @$filter["z_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchCondition = @$filter["v_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchValue2 = @$filter["y_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->SearchOperator2 = @$filter["w_volunteering_type"];
		$this->volunteering_type->AdvancedSearch->Save();

		// Field licence_no
		$this->licence_no->AdvancedSearch->SearchValue = @$filter["x_licence_no"];
		$this->licence_no->AdvancedSearch->SearchOperator = @$filter["z_licence_no"];
		$this->licence_no->AdvancedSearch->SearchCondition = @$filter["v_licence_no"];
		$this->licence_no->AdvancedSearch->SearchValue2 = @$filter["y_licence_no"];
		$this->licence_no->AdvancedSearch->SearchOperator2 = @$filter["w_licence_no"];
		$this->licence_no->AdvancedSearch->Save();

		// Field trade_licence
		$this->trade_licence->AdvancedSearch->SearchValue = @$filter["x_trade_licence"];
		$this->trade_licence->AdvancedSearch->SearchOperator = @$filter["z_trade_licence"];
		$this->trade_licence->AdvancedSearch->SearchCondition = @$filter["v_trade_licence"];
		$this->trade_licence->AdvancedSearch->SearchValue2 = @$filter["y_trade_licence"];
		$this->trade_licence->AdvancedSearch->SearchOperator2 = @$filter["w_trade_licence"];
		$this->trade_licence->AdvancedSearch->Save();

		// Field tl_expiry_date
		$this->tl_expiry_date->AdvancedSearch->SearchValue = @$filter["x_tl_expiry_date"];
		$this->tl_expiry_date->AdvancedSearch->SearchOperator = @$filter["z_tl_expiry_date"];
		$this->tl_expiry_date->AdvancedSearch->SearchCondition = @$filter["v_tl_expiry_date"];
		$this->tl_expiry_date->AdvancedSearch->SearchValue2 = @$filter["y_tl_expiry_date"];
		$this->tl_expiry_date->AdvancedSearch->SearchOperator2 = @$filter["w_tl_expiry_date"];
		$this->tl_expiry_date->AdvancedSearch->Save();

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

		// Field visa_expiry_date
		$this->visa_expiry_date->AdvancedSearch->SearchValue = @$filter["x_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchOperator = @$filter["z_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchCondition = @$filter["v_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchValue2 = @$filter["y_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->SearchOperator2 = @$filter["w_visa_expiry_date"];
		$this->visa_expiry_date->AdvancedSearch->Save();

		// Field unid
		$this->unid->AdvancedSearch->SearchValue = @$filter["x_unid"];
		$this->unid->AdvancedSearch->SearchOperator = @$filter["z_unid"];
		$this->unid->AdvancedSearch->SearchCondition = @$filter["v_unid"];
		$this->unid->AdvancedSearch->SearchValue2 = @$filter["y_unid"];
		$this->unid->AdvancedSearch->SearchOperator2 = @$filter["w_unid"];
		$this->unid->AdvancedSearch->Save();

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

		// Field forward_to_dep
		$this->forward_to_dep->AdvancedSearch->SearchValue = @$filter["x_forward_to_dep"];
		$this->forward_to_dep->AdvancedSearch->SearchOperator = @$filter["z_forward_to_dep"];
		$this->forward_to_dep->AdvancedSearch->SearchCondition = @$filter["v_forward_to_dep"];
		$this->forward_to_dep->AdvancedSearch->SearchValue2 = @$filter["y_forward_to_dep"];
		$this->forward_to_dep->AdvancedSearch->SearchOperator2 = @$filter["w_forward_to_dep"];
		$this->forward_to_dep->AdvancedSearch->Save();

		// Field eco_department_approval
		$this->eco_department_approval->AdvancedSearch->SearchValue = @$filter["x_eco_department_approval"];
		$this->eco_department_approval->AdvancedSearch->SearchOperator = @$filter["z_eco_department_approval"];
		$this->eco_department_approval->AdvancedSearch->SearchCondition = @$filter["v_eco_department_approval"];
		$this->eco_department_approval->AdvancedSearch->SearchValue2 = @$filter["y_eco_department_approval"];
		$this->eco_department_approval->AdvancedSearch->SearchOperator2 = @$filter["w_eco_department_approval"];
		$this->eco_department_approval->AdvancedSearch->Save();

		// Field eco_departmnet_comment
		$this->eco_departmnet_comment->AdvancedSearch->SearchValue = @$filter["x_eco_departmnet_comment"];
		$this->eco_departmnet_comment->AdvancedSearch->SearchOperator = @$filter["z_eco_departmnet_comment"];
		$this->eco_departmnet_comment->AdvancedSearch->SearchCondition = @$filter["v_eco_departmnet_comment"];
		$this->eco_departmnet_comment->AdvancedSearch->SearchValue2 = @$filter["y_eco_departmnet_comment"];
		$this->eco_departmnet_comment->AdvancedSearch->SearchOperator2 = @$filter["w_eco_departmnet_comment"];
		$this->eco_departmnet_comment->AdvancedSearch->Save();

		// Field security_approval
		$this->security_approval->AdvancedSearch->SearchValue = @$filter["x_security_approval"];
		$this->security_approval->AdvancedSearch->SearchOperator = @$filter["z_security_approval"];
		$this->security_approval->AdvancedSearch->SearchCondition = @$filter["v_security_approval"];
		$this->security_approval->AdvancedSearch->SearchValue2 = @$filter["y_security_approval"];
		$this->security_approval->AdvancedSearch->SearchOperator2 = @$filter["w_security_approval"];
		$this->security_approval->AdvancedSearch->Save();

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
		$this->BuildSearchSql($sWhere, $this->institution_id, $Default, FALSE); // institution_id
		$this->BuildSearchSql($sWhere, $this->full_name_ar, $Default, FALSE); // full_name_ar
		$this->BuildSearchSql($sWhere, $this->full_name_en, $Default, FALSE); // full_name_en
		$this->BuildSearchSql($sWhere, $this->institution_type, $Default, FALSE); // institution_type
		$this->BuildSearchSql($sWhere, $this->institutes_name, $Default, FALSE); // institutes_name
		$this->BuildSearchSql($sWhere, $this->volunteering_type, $Default, FALSE); // volunteering_type
		$this->BuildSearchSql($sWhere, $this->licence_no, $Default, FALSE); // licence_no
		$this->BuildSearchSql($sWhere, $this->trade_licence, $Default, FALSE); // trade_licence
		$this->BuildSearchSql($sWhere, $this->tl_expiry_date, $Default, FALSE); // tl_expiry_date
		$this->BuildSearchSql($sWhere, $this->nationality_type, $Default, FALSE); // nationality_type
		$this->BuildSearchSql($sWhere, $this->nationality, $Default, FALSE); // nationality
		$this->BuildSearchSql($sWhere, $this->visa_expiry_date, $Default, FALSE); // visa_expiry_date
		$this->BuildSearchSql($sWhere, $this->unid, $Default, FALSE); // unid
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
		$this->BuildSearchSql($sWhere, $this->work_phone, $Default, FALSE); // work_phone
		$this->BuildSearchSql($sWhere, $this->mobile_phone, $Default, FALSE); // mobile_phone
		$this->BuildSearchSql($sWhere, $this->fax, $Default, FALSE); // fax
		$this->BuildSearchSql($sWhere, $this->pobbox, $Default, FALSE); // pobbox
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->password, $Default, FALSE); // password
		$this->BuildSearchSql($sWhere, $this->admin_approval, $Default, FALSE); // admin_approval
		$this->BuildSearchSql($sWhere, $this->admin_comment, $Default, FALSE); // admin_comment
		$this->BuildSearchSql($sWhere, $this->forward_to_dep, $Default, FALSE); // forward_to_dep
		$this->BuildSearchSql($sWhere, $this->eco_department_approval, $Default, FALSE); // eco_department_approval
		$this->BuildSearchSql($sWhere, $this->eco_departmnet_comment, $Default, FALSE); // eco_departmnet_comment
		$this->BuildSearchSql($sWhere, $this->security_approval, $Default, FALSE); // security_approval
		$this->BuildSearchSql($sWhere, $this->security_comment, $Default, FALSE); // security_comment

		// Set up search parm
		if (!$Default && $sWhere <> "" && $this->Command == "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->institution_id->AdvancedSearch->Save(); // institution_id
			$this->full_name_ar->AdvancedSearch->Save(); // full_name_ar
			$this->full_name_en->AdvancedSearch->Save(); // full_name_en
			$this->institution_type->AdvancedSearch->Save(); // institution_type
			$this->institutes_name->AdvancedSearch->Save(); // institutes_name
			$this->volunteering_type->AdvancedSearch->Save(); // volunteering_type
			$this->licence_no->AdvancedSearch->Save(); // licence_no
			$this->trade_licence->AdvancedSearch->Save(); // trade_licence
			$this->tl_expiry_date->AdvancedSearch->Save(); // tl_expiry_date
			$this->nationality_type->AdvancedSearch->Save(); // nationality_type
			$this->nationality->AdvancedSearch->Save(); // nationality
			$this->visa_expiry_date->AdvancedSearch->Save(); // visa_expiry_date
			$this->unid->AdvancedSearch->Save(); // unid
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
			$this->work_phone->AdvancedSearch->Save(); // work_phone
			$this->mobile_phone->AdvancedSearch->Save(); // mobile_phone
			$this->fax->AdvancedSearch->Save(); // fax
			$this->pobbox->AdvancedSearch->Save(); // pobbox
			$this->_email->AdvancedSearch->Save(); // email
			$this->password->AdvancedSearch->Save(); // password
			$this->admin_approval->AdvancedSearch->Save(); // admin_approval
			$this->admin_comment->AdvancedSearch->Save(); // admin_comment
			$this->forward_to_dep->AdvancedSearch->Save(); // forward_to_dep
			$this->eco_department_approval->AdvancedSearch->Save(); // eco_department_approval
			$this->eco_departmnet_comment->AdvancedSearch->Save(); // eco_departmnet_comment
			$this->security_approval->AdvancedSearch->Save(); // security_approval
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
		$this->BuildBasicSearchSQL($sWhere, $this->full_name_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_name_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->institution_type, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->institutes_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->licence_no, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->trade_licence, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nationality_type, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nationality, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->visa_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->current_emirate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->emirates_id_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->emirates_id_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passport_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passport_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->place_of_work, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->work_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pobbox, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->admin_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->eco_departmnet_comment, $arKeywords, $type);
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
		if ($this->institution_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->full_name_ar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->full_name_en->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->institution_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->institutes_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->volunteering_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->licence_no->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->trade_licence->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->tl_expiry_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nationality_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->nationality->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->visa_expiry_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->unid->AdvancedSearch->IssetSession())
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
		if ($this->admin_approval->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->admin_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->forward_to_dep->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->eco_department_approval->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->eco_departmnet_comment->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->security_approval->AdvancedSearch->IssetSession())
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
		$this->institution_id->AdvancedSearch->UnsetSession();
		$this->full_name_ar->AdvancedSearch->UnsetSession();
		$this->full_name_en->AdvancedSearch->UnsetSession();
		$this->institution_type->AdvancedSearch->UnsetSession();
		$this->institutes_name->AdvancedSearch->UnsetSession();
		$this->volunteering_type->AdvancedSearch->UnsetSession();
		$this->licence_no->AdvancedSearch->UnsetSession();
		$this->trade_licence->AdvancedSearch->UnsetSession();
		$this->tl_expiry_date->AdvancedSearch->UnsetSession();
		$this->nationality_type->AdvancedSearch->UnsetSession();
		$this->nationality->AdvancedSearch->UnsetSession();
		$this->visa_expiry_date->AdvancedSearch->UnsetSession();
		$this->unid->AdvancedSearch->UnsetSession();
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
		$this->work_phone->AdvancedSearch->UnsetSession();
		$this->mobile_phone->AdvancedSearch->UnsetSession();
		$this->fax->AdvancedSearch->UnsetSession();
		$this->pobbox->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->password->AdvancedSearch->UnsetSession();
		$this->admin_approval->AdvancedSearch->UnsetSession();
		$this->admin_comment->AdvancedSearch->UnsetSession();
		$this->forward_to_dep->AdvancedSearch->UnsetSession();
		$this->eco_department_approval->AdvancedSearch->UnsetSession();
		$this->eco_departmnet_comment->AdvancedSearch->UnsetSession();
		$this->security_approval->AdvancedSearch->UnsetSession();
		$this->security_comment->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->institution_id->AdvancedSearch->Load();
		$this->full_name_ar->AdvancedSearch->Load();
		$this->full_name_en->AdvancedSearch->Load();
		$this->institution_type->AdvancedSearch->Load();
		$this->institutes_name->AdvancedSearch->Load();
		$this->volunteering_type->AdvancedSearch->Load();
		$this->licence_no->AdvancedSearch->Load();
		$this->trade_licence->AdvancedSearch->Load();
		$this->tl_expiry_date->AdvancedSearch->Load();
		$this->nationality_type->AdvancedSearch->Load();
		$this->nationality->AdvancedSearch->Load();
		$this->visa_expiry_date->AdvancedSearch->Load();
		$this->unid->AdvancedSearch->Load();
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
		$this->work_phone->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->pobbox->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->forward_to_dep->AdvancedSearch->Load();
		$this->eco_department_approval->AdvancedSearch->Load();
		$this->eco_departmnet_comment->AdvancedSearch->Load();
		$this->security_approval->AdvancedSearch->Load();
		$this->security_comment->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->institution_id); // institution_id
			$this->UpdateSort($this->full_name_ar); // full_name_ar
			$this->UpdateSort($this->full_name_en); // full_name_en
			$this->UpdateSort($this->institution_type); // institution_type
			$this->UpdateSort($this->institutes_name); // institutes_name
			$this->UpdateSort($this->admin_approval); // admin_approval
			$this->UpdateSort($this->admin_comment); // admin_comment
			$this->UpdateSort($this->forward_to_dep); // forward_to_dep
			$this->UpdateSort($this->eco_department_approval); // eco_department_approval
			$this->UpdateSort($this->eco_departmnet_comment); // eco_departmnet_comment
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
				$this->institution_id->setSort("DESC");
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
				$this->institution_id->setSort("");
				$this->full_name_ar->setSort("");
				$this->full_name_en->setSort("");
				$this->institution_type->setSort("");
				$this->institutes_name->setSort("");
				$this->admin_approval->setSort("");
				$this->admin_comment->setSort("");
				$this->forward_to_dep->setSort("");
				$this->eco_department_approval->setSort("");
				$this->eco_departmnet_comment->setSort("");
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
			$oListOpt->Body = "<input style='margin-left:20px;' type='checkbox' name='selecteduserid[]' value='".ew_HtmlEncode(preg_replace("/[^0-9]/","",$this->ViewUrl))."'><a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->institution_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.finstitutionslist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"finstitutionslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"finstitutionslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.finstitutionslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"finstitutionslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Advanced search button
		$item = &$this->SearchOptions->Add("advancedsearch");
		if (ew_IsMobile())
			$item->Body = "<a class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" href=\"institutionssrch.php\">" . $Language->Phrase("AdvancedSearchBtn") . "</a>";
		else
			$item->Body = "<button type=\"button\" class=\"btn btn-default ewAdvancedSearch\" title=\"" . $Language->Phrase("AdvancedSearch") . "\" data-table=\"institutions\" data-caption=\"" . $Language->Phrase("AdvancedSearch") . "\" onclick=\"ew_ModalDialogShow({lnk:this,btn:'SearchBtn',url:'institutionssrch.php'});\">" . $Language->Phrase("AdvancedSearchBtn") . "</button>";
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
		// institution_id

		$this->institution_id->AdvancedSearch->SearchValue = @$_GET["x_institution_id"];
		if ($this->institution_id->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->institution_id->AdvancedSearch->SearchOperator = @$_GET["z_institution_id"];

		// full_name_ar
		$this->full_name_ar->AdvancedSearch->SearchValue = @$_GET["x_full_name_ar"];
		if ($this->full_name_ar->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->full_name_ar->AdvancedSearch->SearchOperator = @$_GET["z_full_name_ar"];

		// full_name_en
		$this->full_name_en->AdvancedSearch->SearchValue = @$_GET["x_full_name_en"];
		if ($this->full_name_en->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->full_name_en->AdvancedSearch->SearchOperator = @$_GET["z_full_name_en"];

		// institution_type
		$this->institution_type->AdvancedSearch->SearchValue = @$_GET["x_institution_type"];
		if ($this->institution_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->institution_type->AdvancedSearch->SearchOperator = @$_GET["z_institution_type"];

		// institutes_name
		$this->institutes_name->AdvancedSearch->SearchValue = @$_GET["x_institutes_name"];
		if ($this->institutes_name->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->institutes_name->AdvancedSearch->SearchOperator = @$_GET["z_institutes_name"];

		// volunteering_type
		$this->volunteering_type->AdvancedSearch->SearchValue = @$_GET["x_volunteering_type"];
		if ($this->volunteering_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->volunteering_type->AdvancedSearch->SearchOperator = @$_GET["z_volunteering_type"];

		// licence_no
		$this->licence_no->AdvancedSearch->SearchValue = @$_GET["x_licence_no"];
		if ($this->licence_no->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->licence_no->AdvancedSearch->SearchOperator = @$_GET["z_licence_no"];

		// trade_licence
		$this->trade_licence->AdvancedSearch->SearchValue = @$_GET["x_trade_licence"];
		if ($this->trade_licence->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->trade_licence->AdvancedSearch->SearchOperator = @$_GET["z_trade_licence"];

		// tl_expiry_date
		$this->tl_expiry_date->AdvancedSearch->SearchValue = @$_GET["x_tl_expiry_date"];
		if ($this->tl_expiry_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->tl_expiry_date->AdvancedSearch->SearchOperator = @$_GET["z_tl_expiry_date"];

		// nationality_type
		$this->nationality_type->AdvancedSearch->SearchValue = @$_GET["x_nationality_type"];
		if ($this->nationality_type->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nationality_type->AdvancedSearch->SearchOperator = @$_GET["z_nationality_type"];

		// nationality
		$this->nationality->AdvancedSearch->SearchValue = @$_GET["x_nationality"];
		if ($this->nationality->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->nationality->AdvancedSearch->SearchOperator = @$_GET["z_nationality"];

		// visa_expiry_date
		$this->visa_expiry_date->AdvancedSearch->SearchValue = @$_GET["x_visa_expiry_date"];
		if ($this->visa_expiry_date->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->visa_expiry_date->AdvancedSearch->SearchOperator = @$_GET["z_visa_expiry_date"];

		// unid
		$this->unid->AdvancedSearch->SearchValue = @$_GET["x_unid"];
		if ($this->unid->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->unid->AdvancedSearch->SearchOperator = @$_GET["z_unid"];

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

		// admin_approval
		$this->admin_approval->AdvancedSearch->SearchValue = @$_GET["x_admin_approval"];
		if ($this->admin_approval->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->admin_approval->AdvancedSearch->SearchOperator = @$_GET["z_admin_approval"];

		// admin_comment
		$this->admin_comment->AdvancedSearch->SearchValue = @$_GET["x_admin_comment"];
		if ($this->admin_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->admin_comment->AdvancedSearch->SearchOperator = @$_GET["z_admin_comment"];

		// forward_to_dep
		$this->forward_to_dep->AdvancedSearch->SearchValue = @$_GET["x_forward_to_dep"];
		if ($this->forward_to_dep->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->forward_to_dep->AdvancedSearch->SearchOperator = @$_GET["z_forward_to_dep"];

		// eco_department_approval
		$this->eco_department_approval->AdvancedSearch->SearchValue = @$_GET["x_eco_department_approval"];
		if ($this->eco_department_approval->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->eco_department_approval->AdvancedSearch->SearchOperator = @$_GET["z_eco_department_approval"];

		// eco_departmnet_comment
		$this->eco_departmnet_comment->AdvancedSearch->SearchValue = @$_GET["x_eco_departmnet_comment"];
		if ($this->eco_departmnet_comment->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->eco_departmnet_comment->AdvancedSearch->SearchOperator = @$_GET["z_eco_departmnet_comment"];

		// security_approval
		$this->security_approval->AdvancedSearch->SearchValue = @$_GET["x_security_approval"];
		if ($this->security_approval->AdvancedSearch->SearchValue <> "" && $this->Command == "") $this->Command = "search";
		$this->security_approval->AdvancedSearch->SearchOperator = @$_GET["z_security_approval"];

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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("institution_id")) <> "")
			$this->institution_id->CurrentValue = $this->getKey("institution_id"); // institution_id
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
		$this->institution_id->AdvancedSearch->Load();
		$this->full_name_ar->AdvancedSearch->Load();
		$this->full_name_en->AdvancedSearch->Load();
		$this->institution_type->AdvancedSearch->Load();
		$this->institutes_name->AdvancedSearch->Load();
		$this->volunteering_type->AdvancedSearch->Load();
		$this->licence_no->AdvancedSearch->Load();
		$this->trade_licence->AdvancedSearch->Load();
		$this->tl_expiry_date->AdvancedSearch->Load();
		$this->nationality_type->AdvancedSearch->Load();
		$this->nationality->AdvancedSearch->Load();
		$this->visa_expiry_date->AdvancedSearch->Load();
		$this->unid->AdvancedSearch->Load();
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
		$this->work_phone->AdvancedSearch->Load();
		$this->mobile_phone->AdvancedSearch->Load();
		$this->fax->AdvancedSearch->Load();
		$this->pobbox->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
		$this->admin_approval->AdvancedSearch->Load();
		$this->admin_comment->AdvancedSearch->Load();
		$this->forward_to_dep->AdvancedSearch->Load();
		$this->eco_department_approval->AdvancedSearch->Load();
		$this->eco_departmnet_comment->AdvancedSearch->Load();
		$this->security_approval->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_institutions\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_institutions',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.finstitutionslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($institutions_list)) $institutions_list = new cinstitutions_list();

// Page init
$institutions_list->Page_Init();

// Page main
$institutions_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$institutions_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($institutions->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = finstitutionslist = new ew_Form("finstitutionslist", "list");
finstitutionslist.FormKeyCountName = '<?php echo $institutions_list->FormKeyCountName ?>';

// Form_CustomValidate event
finstitutionslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
finstitutionslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
finstitutionslist.Lists["x_institution_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionslist.Lists["x_institution_type"].Options = <?php echo json_encode($institutions_list->institution_type->Options()) ?>;
finstitutionslist.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionslist.Lists["x_admin_approval"].Options = <?php echo json_encode($institutions_list->admin_approval->Options()) ?>;
finstitutionslist.Lists["x_forward_to_dep"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
finstitutionslist.Lists["x_forward_to_dep"].Data = "<?php echo $institutions_list->forward_to_dep->LookupFilterQuery(FALSE, "list") ?>";
finstitutionslist.Lists["x_eco_department_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
finstitutionslist.Lists["x_eco_department_approval"].Options = <?php echo json_encode($institutions_list->eco_department_approval->Options()) ?>;

// Form object for search
var CurrentSearchForm = finstitutionslistsrch = new ew_Form("finstitutionslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php  include_once('institution_extra.php'); ?>

<?php } ?>
<?php if ($institutions->Export == "") { ?>
<div class="ewToolbar">
<?php if ($institutions_list->TotalRecs > 0 && $institutions_list->ExportOptions->Visible()) { ?>
<?php $institutions_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($institutions_list->SearchOptions->Visible()) { ?>
<?php $institutions_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($institutions_list->FilterOptions->Visible()) { ?>
<?php $institutions_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $institutions_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($institutions_list->TotalRecs <= 0)
			$institutions_list->TotalRecs = $institutions->ListRecordCount();
	} else {
		if (!$institutions_list->Recordset && ($institutions_list->Recordset = $institutions_list->LoadRecordset()))
			$institutions_list->TotalRecs = $institutions_list->Recordset->RecordCount();
	}
	$institutions_list->StartRec = 1;
	if ($institutions_list->DisplayRecs <= 0 || ($institutions->Export <> "" && $institutions->ExportAll)) // Display all records
		$institutions_list->DisplayRecs = $institutions_list->TotalRecs;
	if (!($institutions->Export <> "" && $institutions->ExportAll))
		$institutions_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$institutions_list->Recordset = $institutions_list->LoadRecordset($institutions_list->StartRec-1, $institutions_list->DisplayRecs);

	// Set no record found message
	if ($institutions->CurrentAction == "" && $institutions_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$institutions_list->setWarningMessage(ew_DeniedMsg());
		if ($institutions_list->SearchWhere == "0=101")
			$institutions_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$institutions_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($institutions_list->AuditTrailOnSearch && $institutions_list->Command == "search" && !$institutions_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $institutions_list->getSessionWhere();
		$institutions_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$institutions_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($institutions->Export == "" && $institutions->CurrentAction == "") { ?>
<form name="finstitutionslistsrch" id="finstitutionslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($institutions_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="finstitutionslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="institutions">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($institutions_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($institutions_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $institutions_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($institutions_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($institutions_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($institutions_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($institutions_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $institutions_list->ShowPageHeader(); ?>
<?php
$institutions_list->ShowMessage();
?>
<?php if ($institutions_list->TotalRecs > 0 || $institutions->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($institutions_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> institutions">
<?php if ($institutions->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($institutions->CurrentAction <> "gridadd" && $institutions->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($institutions_list->Pager)) $institutions_list->Pager = new cPrevNextPager($institutions_list->StartRec, $institutions_list->DisplayRecs, $institutions_list->TotalRecs, $institutions_list->AutoHidePager) ?>
<?php if ($institutions_list->Pager->RecordCount > 0 && $institutions_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($institutions_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($institutions_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $institutions_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($institutions_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($institutions_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $institutions_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $institutions_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $institutions_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $institutions_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($institutions_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="finstitutionslist" id="finstitutionslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<? echo $form; ?>
<?php if ($institutions_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $institutions_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="institutions">
<div id="gmp_institutions" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($institutions_list->TotalRecs > 0 || $institutions->CurrentAction == "gridedit") { ?>
<table id="tbl_institutionslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$institutions_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$institutions_list->RenderListOptions();

// Render list options (header, left)
$institutions_list->ListOptions->Render("header", "left");
?>
<?php if ($institutions->institution_id->Visible) { // institution_id ?>
	<?php if ($institutions->SortUrl($institutions->institution_id) == "") { ?>
		<th data-name="institution_id" class="<?php echo $institutions->institution_id->HeaderCellClass() ?>"><div id="elh_institutions_institution_id" class="institutions_institution_id"><div class="ewTableHeaderCaption"><?php echo $institutions->institution_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="institution_id" class="<?php echo $institutions->institution_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->institution_id) ?>',1);"><div id="elh_institutions_institution_id" class="institutions_institution_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->institution_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions->institution_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->institution_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->full_name_ar->Visible) { // full_name_ar ?>
	<?php if ($institutions->SortUrl($institutions->full_name_ar) == "") { ?>
		<th data-name="full_name_ar" class="<?php echo $institutions->full_name_ar->HeaderCellClass() ?>"><div id="elh_institutions_full_name_ar" class="institutions_full_name_ar"><div class="ewTableHeaderCaption"><?php echo $institutions->full_name_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="full_name_ar" class="<?php echo $institutions->full_name_ar->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->full_name_ar) ?>',1);"><div id="elh_institutions_full_name_ar" class="institutions_full_name_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->full_name_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions->full_name_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->full_name_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->full_name_en->Visible) { // full_name_en ?>
	<?php if ($institutions->SortUrl($institutions->full_name_en) == "") { ?>
		<th data-name="full_name_en" class="<?php echo $institutions->full_name_en->HeaderCellClass() ?>"><div id="elh_institutions_full_name_en" class="institutions_full_name_en"><div class="ewTableHeaderCaption"><?php echo $institutions->full_name_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="full_name_en" class="<?php echo $institutions->full_name_en->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->full_name_en) ?>',1);"><div id="elh_institutions_full_name_en" class="institutions_full_name_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->full_name_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions->full_name_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->full_name_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->institution_type->Visible) { // institution_type ?>
	<?php if ($institutions->SortUrl($institutions->institution_type) == "") { ?>
		<th data-name="institution_type" class="<?php echo $institutions->institution_type->HeaderCellClass() ?>"><div id="elh_institutions_institution_type" class="institutions_institution_type"><div class="ewTableHeaderCaption"><?php echo $institutions->institution_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="institution_type" class="<?php echo $institutions->institution_type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->institution_type) ?>',1);"><div id="elh_institutions_institution_type" class="institutions_institution_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->institution_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions->institution_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->institution_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->institutes_name->Visible) { // institutes_name ?>
	<?php if ($institutions->SortUrl($institutions->institutes_name) == "") { ?>
		<th data-name="institutes_name" class="<?php echo $institutions->institutes_name->HeaderCellClass() ?>"><div id="elh_institutions_institutes_name" class="institutions_institutes_name"><div class="ewTableHeaderCaption"><?php echo $institutions->institutes_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="institutes_name" class="<?php echo $institutions->institutes_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->institutes_name) ?>',1);"><div id="elh_institutions_institutes_name" class="institutions_institutes_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->institutes_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions->institutes_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->institutes_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->admin_approval->Visible) { // admin_approval ?>
	<?php if ($institutions->SortUrl($institutions->admin_approval) == "") { ?>
		<th data-name="admin_approval" class="<?php echo $institutions->admin_approval->HeaderCellClass() ?>"><div id="elh_institutions_admin_approval" class="institutions_admin_approval"><div class="ewTableHeaderCaption"><?php echo $institutions->admin_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_approval" class="<?php echo $institutions->admin_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->admin_approval) ?>',1);"><div id="elh_institutions_admin_approval" class="institutions_admin_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->admin_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions->admin_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->admin_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->admin_comment->Visible) { // admin_comment ?>
	<?php if ($institutions->SortUrl($institutions->admin_comment) == "") { ?>
		<th data-name="admin_comment" class="<?php echo $institutions->admin_comment->HeaderCellClass() ?>"><div id="elh_institutions_admin_comment" class="institutions_admin_comment"><div class="ewTableHeaderCaption"><?php echo $institutions->admin_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_comment" class="<?php echo $institutions->admin_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->admin_comment) ?>',1);"><div id="elh_institutions_admin_comment" class="institutions_admin_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->admin_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions->admin_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->admin_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->forward_to_dep->Visible) { // forward_to_dep ?>
	<?php if ($institutions->SortUrl($institutions->forward_to_dep) == "") { ?>
		<th data-name="forward_to_dep" class="<?php echo $institutions->forward_to_dep->HeaderCellClass() ?>"><div id="elh_institutions_forward_to_dep" class="institutions_forward_to_dep"><div class="ewTableHeaderCaption"><?php echo $institutions->forward_to_dep->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="forward_to_dep" class="<?php echo $institutions->forward_to_dep->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->forward_to_dep) ?>',1);"><div id="elh_institutions_forward_to_dep" class="institutions_forward_to_dep">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->forward_to_dep->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions->forward_to_dep->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->forward_to_dep->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->eco_department_approval->Visible) { // eco_department_approval ?>
	<?php if ($institutions->SortUrl($institutions->eco_department_approval) == "") { ?>
		<th data-name="eco_department_approval" class="<?php echo $institutions->eco_department_approval->HeaderCellClass() ?>"><div id="elh_institutions_eco_department_approval" class="institutions_eco_department_approval"><div class="ewTableHeaderCaption"><?php echo $institutions->eco_department_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="eco_department_approval" class="<?php echo $institutions->eco_department_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->eco_department_approval) ?>',1);"><div id="elh_institutions_eco_department_approval" class="institutions_eco_department_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->eco_department_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions->eco_department_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->eco_department_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($institutions->eco_departmnet_comment->Visible) { // eco_departmnet_comment ?>
	<?php if ($institutions->SortUrl($institutions->eco_departmnet_comment) == "") { ?>
		<th data-name="eco_departmnet_comment" class="<?php echo $institutions->eco_departmnet_comment->HeaderCellClass() ?>"><div id="elh_institutions_eco_departmnet_comment" class="institutions_eco_departmnet_comment"><div class="ewTableHeaderCaption"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="eco_departmnet_comment" class="<?php echo $institutions->eco_departmnet_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->eco_departmnet_comment) ?>',1);"><div id="elh_institutions_eco_departmnet_comment" class="institutions_eco_departmnet_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->eco_departmnet_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions->eco_departmnet_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->eco_departmnet_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>





<?php if ($institutions->security_approval->Visible) { // security_approval ?>
	<?php if ($institutions->SortUrl($institutions->security_approval) == "") { ?>
		<th data-name="security_approval" class="<?php echo $institutions->security_approval->HeaderCellClass() ?>"><div id="elh_institutions_security_approval" class="institutions_security_approval"><div class="ewTableHeaderCaption"><?php echo $institutions->security_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="security_approval" class="<?php echo $institutions->security_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->security_approval) ?>',1);"><div id="elh_institutions_security_approval" class="institutions_security_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->security_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($institutions->security_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->security_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>

<?php if ($institutions->security_comment->Visible) { // security_comment ?>
	<?php if ($institutions->SortUrl($institutions->security_comment) == "") { ?>
		<th data-name="security_comment" class="<?php echo $institutions->security_comment->HeaderCellClass() ?>"><div id="elh_institutions_security_comment" class="institutions_security_comment"><div class="ewTableHeaderCaption"><?php echo $institutions->security_comment->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="security_comment" class="<?php echo $institutions->security_comment->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $institutions->SortUrl($institutions->security_comment) ?>',1);"><div id="elh_institutions_security_comment" class="institutions_security_comment">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $institutions->security_comment->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($institutions->security_comment->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($institutions->security_comment->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>





<?php

// Render list options (header, right)
$institutions_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($institutions->ExportAll && $institutions->Export <> "") {
	$institutions_list->StopRec = $institutions_list->TotalRecs;
} else {

	// Set the last record to display
	if ($institutions_list->TotalRecs > $institutions_list->StartRec + $institutions_list->DisplayRecs - 1)
		$institutions_list->StopRec = $institutions_list->StartRec + $institutions_list->DisplayRecs - 1;
	else
		$institutions_list->StopRec = $institutions_list->TotalRecs;
}
$institutions_list->RecCnt = $institutions_list->StartRec - 1;
if ($institutions_list->Recordset && !$institutions_list->Recordset->EOF) {
	$institutions_list->Recordset->MoveFirst();
	$bSelectLimit = $institutions_list->UseSelectLimit;
	if (!$bSelectLimit && $institutions_list->StartRec > 1)
		$institutions_list->Recordset->Move($institutions_list->StartRec - 1);
} elseif (!$institutions->AllowAddDeleteRow && $institutions_list->StopRec == 0) {
	$institutions_list->StopRec = $institutions->GridAddRowCount;
}

// Initialize aggregate
$institutions->RowType = EW_ROWTYPE_AGGREGATEINIT;
$institutions->ResetAttrs();
$institutions_list->RenderRow();
while ($institutions_list->RecCnt < $institutions_list->StopRec) {
	$institutions_list->RecCnt++;
	if (intval($institutions_list->RecCnt) >= intval($institutions_list->StartRec)) {
		$institutions_list->RowCnt++;

		// Set up key count
		$institutions_list->KeyCount = $institutions_list->RowIndex;

		// Init row class and style
		$institutions->ResetAttrs();
		$institutions->CssClass = "";
		if ($institutions->CurrentAction == "gridadd") {
		} else {
			$institutions_list->LoadRowValues($institutions_list->Recordset); // Load row values
		}
		$institutions->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$institutions->RowAttrs = array_merge($institutions->RowAttrs, array('data-rowindex'=>$institutions_list->RowCnt, 'id'=>'r' . $institutions_list->RowCnt . '_institutions', 'data-rowtype'=>$institutions->RowType));

		// Render row
		$institutions_list->RenderRow();

		// Render list options
		$institutions_list->RenderListOptions();
?>
	<tr<?php echo $institutions->RowAttributes() ?>>
<?php

// Render list options (body, left)
$institutions_list->ListOptions->Render("body", "left", $institutions_list->RowCnt);
?>
	<?php if ($institutions->institution_id->Visible) { // institution_id ?>
		<td data-name="institution_id"<?php echo $institutions->institution_id->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_institution_id" class="institutions_institution_id">
<span<?php echo $institutions->institution_id->ViewAttributes() ?>>
<?php echo $institutions->institution_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->full_name_ar->Visible) { // full_name_ar ?>
		<td data-name="full_name_ar"<?php echo $institutions->full_name_ar->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_full_name_ar" class="institutions_full_name_ar">
<span<?php echo $institutions->full_name_ar->ViewAttributes() ?>>
<?php echo $institutions->full_name_ar->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->full_name_en->Visible) { // full_name_en ?>
		<td data-name="full_name_en"<?php echo $institutions->full_name_en->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_full_name_en" class="institutions_full_name_en">
<span<?php echo $institutions->full_name_en->ViewAttributes() ?>>
<?php echo $institutions->full_name_en->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->institution_type->Visible) { // institution_type ?>
		<td data-name="institution_type"<?php echo $institutions->institution_type->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_institution_type" class="institutions_institution_type">
<span<?php echo $institutions->institution_type->ViewAttributes() ?>>
<?php echo $institutions->institution_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->institutes_name->Visible) { // institutes_name ?>
		<td data-name="institutes_name"<?php echo $institutions->institutes_name->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_institutes_name" class="institutions_institutes_name">
<span<?php echo $institutions->institutes_name->ViewAttributes() ?>>
<?php echo $institutions->institutes_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->admin_approval->Visible) { // admin_approval ?>
		<td data-name="admin_approval"<?php echo $institutions->admin_approval->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_admin_approval" class="institutions_admin_approval">
<span<?php echo $institutions->admin_approval->ViewAttributes() ?>>
<?php echo $institutions->admin_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->admin_comment->Visible) { // admin_comment ?>
		<td data-name="admin_comment"<?php echo $institutions->admin_comment->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_admin_comment" class="institutions_admin_comment">
<span<?php echo $institutions->admin_comment->ViewAttributes() ?>>
<?php echo $institutions->admin_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->forward_to_dep->Visible) { // forward_to_dep ?>
		<td data-name="forward_to_dep"<?php echo $institutions->forward_to_dep->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_forward_to_dep" class="institutions_forward_to_dep">
<span<?php echo $institutions->forward_to_dep->ViewAttributes() ?>>
<?php echo $institutions->forward_to_dep->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->eco_department_approval->Visible) { // eco_department_approval ?>
		<td data-name="eco_department_approval"<?php echo $institutions->eco_department_approval->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_eco_department_approval" class="institutions_eco_department_approval">
<span<?php echo $institutions->eco_department_approval->ViewAttributes() ?>>
<?php echo $institutions->eco_department_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->eco_departmnet_comment->Visible) { // eco_departmnet_comment ?>
		<td data-name="eco_departmnet_comment"<?php echo $institutions->eco_departmnet_comment->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_eco_departmnet_comment" class="institutions_eco_departmnet_comment">
<span<?php echo $institutions->eco_departmnet_comment->ViewAttributes() ?>>
<?php echo $institutions->eco_departmnet_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>


	<?php if ($institutions->security_approval->Visible) { // security_approval ?>
		<td data-name="security_approval"<?php echo $institutions->security_approval->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_security_approval" class="institutions_security_approval">
<span<?php echo $institutions->security_approval->ViewAttributes() ?>>
<?php echo $institutions->security_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($institutions->security_comment->Visible) { // security_comment ?>
		<td data-name="security_comment"<?php echo $institutions->security_comment->CellAttributes() ?>>
<span id="el<?php echo $institutions_list->RowCnt ?>_institutions_security_comment" class="institutions_security_comment">
<span<?php echo $institutions->security_comment->ViewAttributes() ?>>
<?php echo $institutions->security_comment->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>



<?php

// Render list options (body, right)
$institutions_list->ListOptions->Render("body", "right", $institutions_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($institutions->CurrentAction <> "gridadd")
		$institutions_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($institutions->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($institutions_list->Recordset)
	$institutions_list->Recordset->Close();
?>
<?php if ($institutions->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($institutions->CurrentAction <> "gridadd" && $institutions->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($institutions_list->Pager)) $institutions_list->Pager = new cPrevNextPager($institutions_list->StartRec, $institutions_list->DisplayRecs, $institutions_list->TotalRecs, $institutions_list->AutoHidePager) ?>
<?php if ($institutions_list->Pager->RecordCount > 0 && $institutions_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($institutions_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($institutions_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $institutions_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($institutions_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($institutions_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $institutions_list->PageUrl() ?>start=<?php echo $institutions_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $institutions_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $institutions_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $institutions_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $institutions_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($institutions_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($institutions_list->TotalRecs == 0 && $institutions->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($institutions_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($institutions->Export == "") { ?>
<script type="text/javascript">
finstitutionslistsrch.FilterList = <?php echo $institutions_list->GetFilterList() ?>;
finstitutionslistsrch.Init();
finstitutionslist.Init();
</script>
<?php } ?>
<?php
$institutions_list->ShowPageFooter();
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
$institutions_list->Page_Terminate();
?>
