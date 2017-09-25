<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "view1info.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$view1_list = NULL; // Initialize page object first

class cview1_list extends cview1 {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'view1';

	// Page object name
	var $PageObjName = 'view1_list';

	// Grid form hidden field names
	var $FormName = 'fview1list';
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

		// Table object (view1)
		if (!isset($GLOBALS["view1"]) || get_class($GLOBALS["view1"]) == "cview1") {
			$GLOBALS["view1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view1add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view1delete.php";
		$this->MultiUpdateUrl = "view1update.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fview1listsrch";

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
		$this->eid_expiry_date->SetVisibility();
		$this->passport_ex_date->SetVisibility();
		$this->nationality_type->SetVisibility();
		$this->unid->SetVisibility();
		$this->visa_expiry_date->SetVisibility();
		$this->visa_copy->SetVisibility();
		$this->personal_photo->SetVisibility();
		$this->date_of_birth->SetVisibility();
		$this->gender->SetVisibility();
		$this->marital_status->SetVisibility();
		$this->blood_type->SetVisibility();
		$this->driving_licence->SetVisibility();
		$this->job->SetVisibility();
		$this->volunteering_type->SetVisibility();
		$this->overall_evaluation->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->security_approval->SetVisibility();
		$this->title_number->SetVisibility();
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->SetVisibility();

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
		global $EW_EXPORT, $view1;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($view1);
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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

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
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fview1listsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->user_id->AdvancedSearch->ToJson(), ","); // Field user_id
		$sFilterList = ew_Concat($sFilterList, $this->group_id->AdvancedSearch->ToJson(), ","); // Field group_id
		$sFilterList = ew_Concat($sFilterList, $this->full_name_ar->AdvancedSearch->ToJson(), ","); // Field full_name_ar
		$sFilterList = ew_Concat($sFilterList, $this->full_name_en->AdvancedSearch->ToJson(), ","); // Field full_name_en
		$sFilterList = ew_Concat($sFilterList, $this->emirates_id_number->AdvancedSearch->ToJson(), ","); // Field emirates_id_number
		$sFilterList = ew_Concat($sFilterList, $this->eid_expiry_date->AdvancedSearch->ToJson(), ","); // Field eid_expiry_date
		$sFilterList = ew_Concat($sFilterList, $this->emirates_id_copy->AdvancedSearch->ToJson(), ","); // Field emirates_id_copy
		$sFilterList = ew_Concat($sFilterList, $this->passport_number->AdvancedSearch->ToJson(), ","); // Field passport_number
		$sFilterList = ew_Concat($sFilterList, $this->passport_ex_date->AdvancedSearch->ToJson(), ","); // Field passport_ex_date
		$sFilterList = ew_Concat($sFilterList, $this->passport_copy->AdvancedSearch->ToJson(), ","); // Field passport_copy
		$sFilterList = ew_Concat($sFilterList, $this->nationality_type->AdvancedSearch->ToJson(), ","); // Field nationality_type
		$sFilterList = ew_Concat($sFilterList, $this->nationality->AdvancedSearch->ToJson(), ","); // Field nationality
		$sFilterList = ew_Concat($sFilterList, $this->unid->AdvancedSearch->ToJson(), ","); // Field unid
		$sFilterList = ew_Concat($sFilterList, $this->visa_expiry_date->AdvancedSearch->ToJson(), ","); // Field visa_expiry_date
		$sFilterList = ew_Concat($sFilterList, $this->visa_copy->AdvancedSearch->ToJson(), ","); // Field visa_copy
		$sFilterList = ew_Concat($sFilterList, $this->personal_photo->AdvancedSearch->ToJson(), ","); // Field personal_photo
		$sFilterList = ew_Concat($sFilterList, $this->current_emirate->AdvancedSearch->ToJson(), ","); // Field current_emirate
		$sFilterList = ew_Concat($sFilterList, $this->date_of_birth->AdvancedSearch->ToJson(), ","); // Field date_of_birth
		$sFilterList = ew_Concat($sFilterList, $this->full_address->AdvancedSearch->ToJson(), ","); // Field full_address
		$sFilterList = ew_Concat($sFilterList, $this->qualifications->AdvancedSearch->ToJson(), ","); // Field qualifications
		$sFilterList = ew_Concat($sFilterList, $this->cv->AdvancedSearch->ToJson(), ","); // Field cv
		$sFilterList = ew_Concat($sFilterList, $this->gender->AdvancedSearch->ToJson(), ","); // Field gender
		$sFilterList = ew_Concat($sFilterList, $this->marital_status->AdvancedSearch->ToJson(), ","); // Field marital_status
		$sFilterList = ew_Concat($sFilterList, $this->blood_type->AdvancedSearch->ToJson(), ","); // Field blood_type
		$sFilterList = ew_Concat($sFilterList, $this->driving_licence->AdvancedSearch->ToJson(), ","); // Field driving_licence
		$sFilterList = ew_Concat($sFilterList, $this->job->AdvancedSearch->ToJson(), ","); // Field job
		$sFilterList = ew_Concat($sFilterList, $this->volunteering_type->AdvancedSearch->ToJson(), ","); // Field volunteering_type
		$sFilterList = ew_Concat($sFilterList, $this->place_of_work->AdvancedSearch->ToJson(), ","); // Field place_of_work
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
		$sFilterList = ew_Concat($sFilterList, $this->admin_comment->AdvancedSearch->ToJson(), ","); // Field admin_comment
		$sFilterList = ew_Concat($sFilterList, $this->security_approval->AdvancedSearch->ToJson(), ","); // Field security_approval
		$sFilterList = ew_Concat($sFilterList, $this->security_comment->AdvancedSearch->ToJson(), ","); // Field security_comment
		$sFilterList = ew_Concat($sFilterList, $this->title_number->AdvancedSearch->ToJson(), ","); // Field title_number
		$sFilterList = ew_Concat($sFilterList, $this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->ToJson(), ","); // Field Date_Format(users.date_of_birth, '%Y-%m-%d')
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fview1listsrch", $filters);

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

		// Field personal_photo
		$this->personal_photo->AdvancedSearch->SearchValue = @$filter["x_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchOperator = @$filter["z_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchCondition = @$filter["v_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchValue2 = @$filter["y_personal_photo"];
		$this->personal_photo->AdvancedSearch->SearchOperator2 = @$filter["w_personal_photo"];
		$this->personal_photo->AdvancedSearch->Save();

		// Field current_emirate
		$this->current_emirate->AdvancedSearch->SearchValue = @$filter["x_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchOperator = @$filter["z_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchCondition = @$filter["v_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchValue2 = @$filter["y_current_emirate"];
		$this->current_emirate->AdvancedSearch->SearchOperator2 = @$filter["w_current_emirate"];
		$this->current_emirate->AdvancedSearch->Save();

		// Field date_of_birth
		$this->date_of_birth->AdvancedSearch->SearchValue = @$filter["x_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchOperator = @$filter["z_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchCondition = @$filter["v_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchValue2 = @$filter["y_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->SearchOperator2 = @$filter["w_date_of_birth"];
		$this->date_of_birth->AdvancedSearch->Save();

		// Field full_address
		$this->full_address->AdvancedSearch->SearchValue = @$filter["x_full_address"];
		$this->full_address->AdvancedSearch->SearchOperator = @$filter["z_full_address"];
		$this->full_address->AdvancedSearch->SearchCondition = @$filter["v_full_address"];
		$this->full_address->AdvancedSearch->SearchValue2 = @$filter["y_full_address"];
		$this->full_address->AdvancedSearch->SearchOperator2 = @$filter["w_full_address"];
		$this->full_address->AdvancedSearch->Save();

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

		// Field gender
		$this->gender->AdvancedSearch->SearchValue = @$filter["x_gender"];
		$this->gender->AdvancedSearch->SearchOperator = @$filter["z_gender"];
		$this->gender->AdvancedSearch->SearchCondition = @$filter["v_gender"];
		$this->gender->AdvancedSearch->SearchValue2 = @$filter["y_gender"];
		$this->gender->AdvancedSearch->SearchOperator2 = @$filter["w_gender"];
		$this->gender->AdvancedSearch->Save();

		// Field marital_status
		$this->marital_status->AdvancedSearch->SearchValue = @$filter["x_marital_status"];
		$this->marital_status->AdvancedSearch->SearchOperator = @$filter["z_marital_status"];
		$this->marital_status->AdvancedSearch->SearchCondition = @$filter["v_marital_status"];
		$this->marital_status->AdvancedSearch->SearchValue2 = @$filter["y_marital_status"];
		$this->marital_status->AdvancedSearch->SearchOperator2 = @$filter["w_marital_status"];
		$this->marital_status->AdvancedSearch->Save();

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

		// Field place_of_work
		$this->place_of_work->AdvancedSearch->SearchValue = @$filter["x_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchOperator = @$filter["z_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchCondition = @$filter["v_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchValue2 = @$filter["y_place_of_work"];
		$this->place_of_work->AdvancedSearch->SearchOperator2 = @$filter["w_place_of_work"];
		$this->place_of_work->AdvancedSearch->Save();

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

		// Field security_comment
		$this->security_comment->AdvancedSearch->SearchValue = @$filter["x_security_comment"];
		$this->security_comment->AdvancedSearch->SearchOperator = @$filter["z_security_comment"];
		$this->security_comment->AdvancedSearch->SearchCondition = @$filter["v_security_comment"];
		$this->security_comment->AdvancedSearch->SearchValue2 = @$filter["y_security_comment"];
		$this->security_comment->AdvancedSearch->SearchOperator2 = @$filter["w_security_comment"];
		$this->security_comment->AdvancedSearch->Save();

		// Field title_number
		$this->title_number->AdvancedSearch->SearchValue = @$filter["x_title_number"];
		$this->title_number->AdvancedSearch->SearchOperator = @$filter["z_title_number"];
		$this->title_number->AdvancedSearch->SearchCondition = @$filter["v_title_number"];
		$this->title_number->AdvancedSearch->SearchValue2 = @$filter["y_title_number"];
		$this->title_number->AdvancedSearch->SearchOperator2 = @$filter["w_title_number"];
		$this->title_number->AdvancedSearch->Save();

		// Field Date_Format(users.date_of_birth, '%Y-%m-%d')
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->SearchValue = @$filter["x_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"];
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->SearchOperator = @$filter["z_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"];
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->SearchCondition = @$filter["v_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"];
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->SearchValue2 = @$filter["y_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"];
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->SearchOperator2 = @$filter["w_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"];
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->group_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_name_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_name_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->emirates_id_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->emirates_id_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passport_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->passport_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->nationality, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->visa_copy, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->personal_photo, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->current_emirate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->qualifications, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->cv, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->place_of_work, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->home_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->work_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->mobile_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->fax, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->pobbox, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->total_voluntary_hours, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->admin_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->security_comment, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->user_id); // user_id
			$this->UpdateSort($this->eid_expiry_date); // eid_expiry_date
			$this->UpdateSort($this->passport_ex_date); // passport_ex_date
			$this->UpdateSort($this->nationality_type); // nationality_type
			$this->UpdateSort($this->unid); // unid
			$this->UpdateSort($this->visa_expiry_date); // visa_expiry_date
			$this->UpdateSort($this->visa_copy); // visa_copy
			$this->UpdateSort($this->personal_photo); // personal_photo
			$this->UpdateSort($this->date_of_birth); // date_of_birth
			$this->UpdateSort($this->gender); // gender
			$this->UpdateSort($this->marital_status); // marital_status
			$this->UpdateSort($this->blood_type); // blood_type
			$this->UpdateSort($this->driving_licence); // driving_licence
			$this->UpdateSort($this->job); // job
			$this->UpdateSort($this->volunteering_type); // volunteering_type
			$this->UpdateSort($this->overall_evaluation); // overall_evaluation
			$this->UpdateSort($this->admin_approval); // admin_approval
			$this->UpdateSort($this->security_approval); // security_approval
			$this->UpdateSort($this->title_number); // title_number
			$this->UpdateSort($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729); // Date_Format(users.date_of_birth, '%Y-%m-%d')
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
				$this->eid_expiry_date->setSort("");
				$this->passport_ex_date->setSort("");
				$this->nationality_type->setSort("");
				$this->unid->setSort("");
				$this->visa_expiry_date->setSort("");
				$this->visa_copy->setSort("");
				$this->personal_photo->setSort("");
				$this->date_of_birth->setSort("");
				$this->gender->setSort("");
				$this->marital_status->setSort("");
				$this->blood_type->setSort("");
				$this->driving_licence->setSort("");
				$this->job->setSort("");
				$this->volunteering_type->setSort("");
				$this->overall_evaluation->setSort("");
				$this->admin_approval->setSort("");
				$this->security_approval->setSort("");
				$this->title_number->setSort("");
				$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->setSort("");
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->user_id->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fview1listsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fview1listsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fview1list}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fview1listsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

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
		$this->emirates_id_number->setDbValue($row['emirates_id_number']);
		$this->eid_expiry_date->setDbValue($row['eid_expiry_date']);
		$this->emirates_id_copy->setDbValue($row['emirates_id_copy']);
		$this->passport_number->setDbValue($row['passport_number']);
		$this->passport_ex_date->setDbValue($row['passport_ex_date']);
		$this->passport_copy->setDbValue($row['passport_copy']);
		$this->nationality_type->setDbValue($row['nationality_type']);
		$this->nationality->setDbValue($row['nationality']);
		$this->unid->setDbValue($row['unid']);
		$this->visa_expiry_date->setDbValue($row['visa_expiry_date']);
		$this->visa_copy->setDbValue($row['visa_copy']);
		$this->personal_photo->setDbValue($row['personal_photo']);
		$this->current_emirate->setDbValue($row['current_emirate']);
		$this->date_of_birth->setDbValue($row['date_of_birth']);
		$this->full_address->setDbValue($row['full_address']);
		$this->qualifications->setDbValue($row['qualifications']);
		$this->cv->setDbValue($row['cv']);
		$this->gender->setDbValue($row['gender']);
		$this->marital_status->setDbValue($row['marital_status']);
		$this->blood_type->setDbValue($row['blood_type']);
		$this->driving_licence->setDbValue($row['driving_licence']);
		$this->job->setDbValue($row['job']);
		$this->volunteering_type->setDbValue($row['volunteering_type']);
		$this->place_of_work->setDbValue($row['place_of_work']);
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
		$this->admin_comment->setDbValue($row['admin_comment']);
		$this->security_approval->setDbValue($row['security_approval']);
		$this->security_comment->setDbValue($row['security_comment']);
		$this->title_number->setDbValue($row['title_number']);
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->setDbValue($row['Date_Format(users.date_of_birth, \'%Y-%m-%d\')']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['user_id'] = NULL;
		$row['group_id'] = NULL;
		$row['full_name_ar'] = NULL;
		$row['full_name_en'] = NULL;
		$row['emirates_id_number'] = NULL;
		$row['eid_expiry_date'] = NULL;
		$row['emirates_id_copy'] = NULL;
		$row['passport_number'] = NULL;
		$row['passport_ex_date'] = NULL;
		$row['passport_copy'] = NULL;
		$row['nationality_type'] = NULL;
		$row['nationality'] = NULL;
		$row['unid'] = NULL;
		$row['visa_expiry_date'] = NULL;
		$row['visa_copy'] = NULL;
		$row['personal_photo'] = NULL;
		$row['current_emirate'] = NULL;
		$row['date_of_birth'] = NULL;
		$row['full_address'] = NULL;
		$row['qualifications'] = NULL;
		$row['cv'] = NULL;
		$row['gender'] = NULL;
		$row['marital_status'] = NULL;
		$row['blood_type'] = NULL;
		$row['driving_licence'] = NULL;
		$row['job'] = NULL;
		$row['volunteering_type'] = NULL;
		$row['place_of_work'] = NULL;
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
		$row['admin_comment'] = NULL;
		$row['security_approval'] = NULL;
		$row['security_comment'] = NULL;
		$row['title_number'] = NULL;
		$row['Date_Format(users.date_of_birth, \'%Y-%m-%d\')'] = NULL;
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
		$this->emirates_id_number->DbValue = $row['emirates_id_number'];
		$this->eid_expiry_date->DbValue = $row['eid_expiry_date'];
		$this->emirates_id_copy->DbValue = $row['emirates_id_copy'];
		$this->passport_number->DbValue = $row['passport_number'];
		$this->passport_ex_date->DbValue = $row['passport_ex_date'];
		$this->passport_copy->DbValue = $row['passport_copy'];
		$this->nationality_type->DbValue = $row['nationality_type'];
		$this->nationality->DbValue = $row['nationality'];
		$this->unid->DbValue = $row['unid'];
		$this->visa_expiry_date->DbValue = $row['visa_expiry_date'];
		$this->visa_copy->DbValue = $row['visa_copy'];
		$this->personal_photo->DbValue = $row['personal_photo'];
		$this->current_emirate->DbValue = $row['current_emirate'];
		$this->date_of_birth->DbValue = $row['date_of_birth'];
		$this->full_address->DbValue = $row['full_address'];
		$this->qualifications->DbValue = $row['qualifications'];
		$this->cv->DbValue = $row['cv'];
		$this->gender->DbValue = $row['gender'];
		$this->marital_status->DbValue = $row['marital_status'];
		$this->blood_type->DbValue = $row['blood_type'];
		$this->driving_licence->DbValue = $row['driving_licence'];
		$this->job->DbValue = $row['job'];
		$this->volunteering_type->DbValue = $row['volunteering_type'];
		$this->place_of_work->DbValue = $row['place_of_work'];
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
		$this->admin_comment->DbValue = $row['admin_comment'];
		$this->security_approval->DbValue = $row['security_approval'];
		$this->security_comment->DbValue = $row['security_comment'];
		$this->title_number->DbValue = $row['title_number'];
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->DbValue = $row['Date_Format(users.date_of_birth, \'%Y-%m-%d\')'];
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
		// emirates_id_number
		// eid_expiry_date
		// emirates_id_copy
		// passport_number
		// passport_ex_date
		// passport_copy
		// nationality_type
		// nationality
		// unid
		// visa_expiry_date
		// visa_copy
		// personal_photo
		// current_emirate
		// date_of_birth
		// full_address
		// qualifications
		// cv
		// gender
		// marital_status
		// blood_type
		// driving_licence
		// job
		// volunteering_type
		// place_of_work
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
		// admin_comment
		// security_approval
		// security_comment
		// title_number
		// Date_Format(users.date_of_birth, '%Y-%m-%d')

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// user_id
		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// eid_expiry_date
		$this->eid_expiry_date->ViewValue = $this->eid_expiry_date->CurrentValue;
		$this->eid_expiry_date->ViewValue = ew_FormatDateTime($this->eid_expiry_date->ViewValue, 0);
		$this->eid_expiry_date->ViewCustomAttributes = "";

		// passport_ex_date
		$this->passport_ex_date->ViewValue = $this->passport_ex_date->CurrentValue;
		$this->passport_ex_date->ViewValue = ew_FormatDateTime($this->passport_ex_date->ViewValue, 0);
		$this->passport_ex_date->ViewCustomAttributes = "";

		// nationality_type
		$this->nationality_type->ViewValue = $this->nationality_type->CurrentValue;
		$this->nationality_type->ViewCustomAttributes = "";

		// unid
		$this->unid->ViewValue = $this->unid->CurrentValue;
		$this->unid->ViewCustomAttributes = "";

		// visa_expiry_date
		$this->visa_expiry_date->ViewValue = $this->visa_expiry_date->CurrentValue;
		$this->visa_expiry_date->ViewValue = ew_FormatDateTime($this->visa_expiry_date->ViewValue, 0);
		$this->visa_expiry_date->ViewCustomAttributes = "";

		// visa_copy
		$this->visa_copy->ViewValue = $this->visa_copy->CurrentValue;
		$this->visa_copy->ViewCustomAttributes = "";

		// personal_photo
		$this->personal_photo->ViewValue = $this->personal_photo->CurrentValue;
		$this->personal_photo->ViewCustomAttributes = "";

		// date_of_birth
		$this->date_of_birth->ViewValue = $this->date_of_birth->CurrentValue;
		$this->date_of_birth->ViewValue = ew_FormatDateTime($this->date_of_birth->ViewValue, 0);
		$this->date_of_birth->ViewCustomAttributes = "";

		// gender
		$this->gender->ViewValue = $this->gender->CurrentValue;
		$this->gender->ViewCustomAttributes = "";

		// marital_status
		$this->marital_status->ViewValue = $this->marital_status->CurrentValue;
		$this->marital_status->ViewCustomAttributes = "";

		// blood_type
		$this->blood_type->ViewValue = $this->blood_type->CurrentValue;
		$this->blood_type->ViewCustomAttributes = "";

		// driving_licence
		$this->driving_licence->ViewValue = $this->driving_licence->CurrentValue;
		$this->driving_licence->ViewCustomAttributes = "";

		// job
		$this->job->ViewValue = $this->job->CurrentValue;
		$this->job->ViewCustomAttributes = "";

		// volunteering_type
		$this->volunteering_type->ViewValue = $this->volunteering_type->CurrentValue;
		$this->volunteering_type->ViewCustomAttributes = "";

		// overall_evaluation
		$this->overall_evaluation->ViewValue = $this->overall_evaluation->CurrentValue;
		$this->overall_evaluation->ViewCustomAttributes = "";

		// admin_approval
		$this->admin_approval->ViewValue = $this->admin_approval->CurrentValue;
		$this->admin_approval->ViewCustomAttributes = "";

		// security_approval
		$this->security_approval->ViewValue = $this->security_approval->CurrentValue;
		$this->security_approval->ViewCustomAttributes = "";

		// title_number
		$this->title_number->ViewValue = $this->title_number->CurrentValue;
		$this->title_number->ViewCustomAttributes = "";

		// Date_Format(users.date_of_birth, '%Y-%m-%d')
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->ViewValue = $this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->CurrentValue;
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->ViewCustomAttributes = "";

			// user_id
			$this->user_id->LinkCustomAttributes = "";
			$this->user_id->HrefValue = "";
			$this->user_id->TooltipValue = "";

			// eid_expiry_date
			$this->eid_expiry_date->LinkCustomAttributes = "";
			$this->eid_expiry_date->HrefValue = "";
			$this->eid_expiry_date->TooltipValue = "";

			// passport_ex_date
			$this->passport_ex_date->LinkCustomAttributes = "";
			$this->passport_ex_date->HrefValue = "";
			$this->passport_ex_date->TooltipValue = "";

			// nationality_type
			$this->nationality_type->LinkCustomAttributes = "";
			$this->nationality_type->HrefValue = "";
			$this->nationality_type->TooltipValue = "";

			// unid
			$this->unid->LinkCustomAttributes = "";
			$this->unid->HrefValue = "";
			$this->unid->TooltipValue = "";

			// visa_expiry_date
			$this->visa_expiry_date->LinkCustomAttributes = "";
			$this->visa_expiry_date->HrefValue = "";
			$this->visa_expiry_date->TooltipValue = "";

			// visa_copy
			$this->visa_copy->LinkCustomAttributes = "";
			$this->visa_copy->HrefValue = "";
			$this->visa_copy->TooltipValue = "";

			// personal_photo
			$this->personal_photo->LinkCustomAttributes = "";
			$this->personal_photo->HrefValue = "";
			$this->personal_photo->TooltipValue = "";

			// date_of_birth
			$this->date_of_birth->LinkCustomAttributes = "";
			$this->date_of_birth->HrefValue = "";
			$this->date_of_birth->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// marital_status
			$this->marital_status->LinkCustomAttributes = "";
			$this->marital_status->HrefValue = "";
			$this->marital_status->TooltipValue = "";

			// blood_type
			$this->blood_type->LinkCustomAttributes = "";
			$this->blood_type->HrefValue = "";
			$this->blood_type->TooltipValue = "";

			// driving_licence
			$this->driving_licence->LinkCustomAttributes = "";
			$this->driving_licence->HrefValue = "";
			$this->driving_licence->TooltipValue = "";

			// job
			$this->job->LinkCustomAttributes = "";
			$this->job->HrefValue = "";
			$this->job->TooltipValue = "";

			// volunteering_type
			$this->volunteering_type->LinkCustomAttributes = "";
			$this->volunteering_type->HrefValue = "";
			$this->volunteering_type->TooltipValue = "";

			// overall_evaluation
			$this->overall_evaluation->LinkCustomAttributes = "";
			$this->overall_evaluation->HrefValue = "";
			$this->overall_evaluation->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";
			$this->security_approval->TooltipValue = "";

			// title_number
			$this->title_number->LinkCustomAttributes = "";
			$this->title_number->HrefValue = "";
			$this->title_number->TooltipValue = "";

			// Date_Format(users.date_of_birth, '%Y-%m-%d')
			$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->LinkCustomAttributes = "";
			$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->HrefValue = "";
			$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_view1\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_view1',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fview1list,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($view1_list)) $view1_list = new cview1_list();

// Page init
$view1_list->Page_Init();

// Page main
$view1_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$view1_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($view1->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fview1list = new ew_Form("fview1list", "list");
fview1list.FormKeyCountName = '<?php echo $view1_list->FormKeyCountName ?>';

// Form_CustomValidate event
fview1list.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fview1list.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fview1listsrch = new ew_Form("fview1listsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($view1->Export == "") { ?>
<div class="ewToolbar">
<?php if ($view1_list->TotalRecs > 0 && $view1_list->ExportOptions->Visible()) { ?>
<?php $view1_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($view1_list->SearchOptions->Visible()) { ?>
<?php $view1_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($view1_list->FilterOptions->Visible()) { ?>
<?php $view1_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $view1_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($view1_list->TotalRecs <= 0)
			$view1_list->TotalRecs = $view1->ListRecordCount();
	} else {
		if (!$view1_list->Recordset && ($view1_list->Recordset = $view1_list->LoadRecordset()))
			$view1_list->TotalRecs = $view1_list->Recordset->RecordCount();
	}
	$view1_list->StartRec = 1;
	if ($view1_list->DisplayRecs <= 0 || ($view1->Export <> "" && $view1->ExportAll)) // Display all records
		$view1_list->DisplayRecs = $view1_list->TotalRecs;
	if (!($view1->Export <> "" && $view1->ExportAll))
		$view1_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view1_list->Recordset = $view1_list->LoadRecordset($view1_list->StartRec-1, $view1_list->DisplayRecs);

	// Set no record found message
	if ($view1->CurrentAction == "" && $view1_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$view1_list->setWarningMessage(ew_DeniedMsg());
		if ($view1_list->SearchWhere == "0=101")
			$view1_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$view1_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$view1_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($view1->Export == "" && $view1->CurrentAction == "") { ?>
<form name="fview1listsrch" id="fview1listsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($view1_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fview1listsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view1">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($view1_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($view1_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $view1_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($view1_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($view1_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($view1_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($view1_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $view1_list->ShowPageHeader(); ?>
<?php
$view1_list->ShowMessage();
?>
<?php if ($view1_list->TotalRecs > 0 || $view1->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($view1_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> view1">
<?php if ($view1->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($view1->CurrentAction <> "gridadd" && $view1->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view1_list->Pager)) $view1_list->Pager = new cPrevNextPager($view1_list->StartRec, $view1_list->DisplayRecs, $view1_list->TotalRecs, $view1_list->AutoHidePager) ?>
<?php if ($view1_list->Pager->RecordCount > 0 && $view1_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view1_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view1_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view1_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view1_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view1_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view1_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fview1list" id="fview1list" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($view1_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $view1_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="view1">
<div id="gmp_view1" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($view1_list->TotalRecs > 0 || $view1->CurrentAction == "gridedit") { ?>
<table id="tbl_view1list" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$view1_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$view1_list->RenderListOptions();

// Render list options (header, left)
$view1_list->ListOptions->Render("header", "left");
?>
<?php if ($view1->user_id->Visible) { // user_id ?>
	<?php if ($view1->SortUrl($view1->user_id) == "") { ?>
		<th data-name="user_id" class="<?php echo $view1->user_id->HeaderCellClass() ?>"><div id="elh_view1_user_id" class="view1_user_id"><div class="ewTableHeaderCaption"><?php echo $view1->user_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_id" class="<?php echo $view1->user_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->user_id) ?>',1);"><div id="elh_view1_user_id" class="view1_user_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->user_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->user_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->user_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->eid_expiry_date->Visible) { // eid_expiry_date ?>
	<?php if ($view1->SortUrl($view1->eid_expiry_date) == "") { ?>
		<th data-name="eid_expiry_date" class="<?php echo $view1->eid_expiry_date->HeaderCellClass() ?>"><div id="elh_view1_eid_expiry_date" class="view1_eid_expiry_date"><div class="ewTableHeaderCaption"><?php echo $view1->eid_expiry_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="eid_expiry_date" class="<?php echo $view1->eid_expiry_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->eid_expiry_date) ?>',1);"><div id="elh_view1_eid_expiry_date" class="view1_eid_expiry_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->eid_expiry_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->eid_expiry_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->eid_expiry_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->passport_ex_date->Visible) { // passport_ex_date ?>
	<?php if ($view1->SortUrl($view1->passport_ex_date) == "") { ?>
		<th data-name="passport_ex_date" class="<?php echo $view1->passport_ex_date->HeaderCellClass() ?>"><div id="elh_view1_passport_ex_date" class="view1_passport_ex_date"><div class="ewTableHeaderCaption"><?php echo $view1->passport_ex_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="passport_ex_date" class="<?php echo $view1->passport_ex_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->passport_ex_date) ?>',1);"><div id="elh_view1_passport_ex_date" class="view1_passport_ex_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->passport_ex_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->passport_ex_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->passport_ex_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->nationality_type->Visible) { // nationality_type ?>
	<?php if ($view1->SortUrl($view1->nationality_type) == "") { ?>
		<th data-name="nationality_type" class="<?php echo $view1->nationality_type->HeaderCellClass() ?>"><div id="elh_view1_nationality_type" class="view1_nationality_type"><div class="ewTableHeaderCaption"><?php echo $view1->nationality_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nationality_type" class="<?php echo $view1->nationality_type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->nationality_type) ?>',1);"><div id="elh_view1_nationality_type" class="view1_nationality_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->nationality_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->nationality_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->nationality_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->unid->Visible) { // unid ?>
	<?php if ($view1->SortUrl($view1->unid) == "") { ?>
		<th data-name="unid" class="<?php echo $view1->unid->HeaderCellClass() ?>"><div id="elh_view1_unid" class="view1_unid"><div class="ewTableHeaderCaption"><?php echo $view1->unid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="unid" class="<?php echo $view1->unid->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->unid) ?>',1);"><div id="elh_view1_unid" class="view1_unid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->unid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->unid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->unid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->visa_expiry_date->Visible) { // visa_expiry_date ?>
	<?php if ($view1->SortUrl($view1->visa_expiry_date) == "") { ?>
		<th data-name="visa_expiry_date" class="<?php echo $view1->visa_expiry_date->HeaderCellClass() ?>"><div id="elh_view1_visa_expiry_date" class="view1_visa_expiry_date"><div class="ewTableHeaderCaption"><?php echo $view1->visa_expiry_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="visa_expiry_date" class="<?php echo $view1->visa_expiry_date->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->visa_expiry_date) ?>',1);"><div id="elh_view1_visa_expiry_date" class="view1_visa_expiry_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->visa_expiry_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->visa_expiry_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->visa_expiry_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->visa_copy->Visible) { // visa_copy ?>
	<?php if ($view1->SortUrl($view1->visa_copy) == "") { ?>
		<th data-name="visa_copy" class="<?php echo $view1->visa_copy->HeaderCellClass() ?>"><div id="elh_view1_visa_copy" class="view1_visa_copy"><div class="ewTableHeaderCaption"><?php echo $view1->visa_copy->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="visa_copy" class="<?php echo $view1->visa_copy->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->visa_copy) ?>',1);"><div id="elh_view1_visa_copy" class="view1_visa_copy">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->visa_copy->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->visa_copy->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->visa_copy->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->personal_photo->Visible) { // personal_photo ?>
	<?php if ($view1->SortUrl($view1->personal_photo) == "") { ?>
		<th data-name="personal_photo" class="<?php echo $view1->personal_photo->HeaderCellClass() ?>"><div id="elh_view1_personal_photo" class="view1_personal_photo"><div class="ewTableHeaderCaption"><?php echo $view1->personal_photo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="personal_photo" class="<?php echo $view1->personal_photo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->personal_photo) ?>',1);"><div id="elh_view1_personal_photo" class="view1_personal_photo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->personal_photo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->personal_photo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->personal_photo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->date_of_birth->Visible) { // date_of_birth ?>
	<?php if ($view1->SortUrl($view1->date_of_birth) == "") { ?>
		<th data-name="date_of_birth" class="<?php echo $view1->date_of_birth->HeaderCellClass() ?>"><div id="elh_view1_date_of_birth" class="view1_date_of_birth"><div class="ewTableHeaderCaption"><?php echo $view1->date_of_birth->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="date_of_birth" class="<?php echo $view1->date_of_birth->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->date_of_birth) ?>',1);"><div id="elh_view1_date_of_birth" class="view1_date_of_birth">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->date_of_birth->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->date_of_birth->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->date_of_birth->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->gender->Visible) { // gender ?>
	<?php if ($view1->SortUrl($view1->gender) == "") { ?>
		<th data-name="gender" class="<?php echo $view1->gender->HeaderCellClass() ?>"><div id="elh_view1_gender" class="view1_gender"><div class="ewTableHeaderCaption"><?php echo $view1->gender->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gender" class="<?php echo $view1->gender->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->gender) ?>',1);"><div id="elh_view1_gender" class="view1_gender">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->gender->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->gender->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->gender->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->marital_status->Visible) { // marital_status ?>
	<?php if ($view1->SortUrl($view1->marital_status) == "") { ?>
		<th data-name="marital_status" class="<?php echo $view1->marital_status->HeaderCellClass() ?>"><div id="elh_view1_marital_status" class="view1_marital_status"><div class="ewTableHeaderCaption"><?php echo $view1->marital_status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="marital_status" class="<?php echo $view1->marital_status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->marital_status) ?>',1);"><div id="elh_view1_marital_status" class="view1_marital_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->marital_status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->marital_status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->marital_status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->blood_type->Visible) { // blood_type ?>
	<?php if ($view1->SortUrl($view1->blood_type) == "") { ?>
		<th data-name="blood_type" class="<?php echo $view1->blood_type->HeaderCellClass() ?>"><div id="elh_view1_blood_type" class="view1_blood_type"><div class="ewTableHeaderCaption"><?php echo $view1->blood_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="blood_type" class="<?php echo $view1->blood_type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->blood_type) ?>',1);"><div id="elh_view1_blood_type" class="view1_blood_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->blood_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->blood_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->blood_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->driving_licence->Visible) { // driving_licence ?>
	<?php if ($view1->SortUrl($view1->driving_licence) == "") { ?>
		<th data-name="driving_licence" class="<?php echo $view1->driving_licence->HeaderCellClass() ?>"><div id="elh_view1_driving_licence" class="view1_driving_licence"><div class="ewTableHeaderCaption"><?php echo $view1->driving_licence->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="driving_licence" class="<?php echo $view1->driving_licence->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->driving_licence) ?>',1);"><div id="elh_view1_driving_licence" class="view1_driving_licence">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->driving_licence->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->driving_licence->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->driving_licence->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->job->Visible) { // job ?>
	<?php if ($view1->SortUrl($view1->job) == "") { ?>
		<th data-name="job" class="<?php echo $view1->job->HeaderCellClass() ?>"><div id="elh_view1_job" class="view1_job"><div class="ewTableHeaderCaption"><?php echo $view1->job->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="job" class="<?php echo $view1->job->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->job) ?>',1);"><div id="elh_view1_job" class="view1_job">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->job->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->job->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->job->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->volunteering_type->Visible) { // volunteering_type ?>
	<?php if ($view1->SortUrl($view1->volunteering_type) == "") { ?>
		<th data-name="volunteering_type" class="<?php echo $view1->volunteering_type->HeaderCellClass() ?>"><div id="elh_view1_volunteering_type" class="view1_volunteering_type"><div class="ewTableHeaderCaption"><?php echo $view1->volunteering_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="volunteering_type" class="<?php echo $view1->volunteering_type->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->volunteering_type) ?>',1);"><div id="elh_view1_volunteering_type" class="view1_volunteering_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->volunteering_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->volunteering_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->volunteering_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->overall_evaluation->Visible) { // overall_evaluation ?>
	<?php if ($view1->SortUrl($view1->overall_evaluation) == "") { ?>
		<th data-name="overall_evaluation" class="<?php echo $view1->overall_evaluation->HeaderCellClass() ?>"><div id="elh_view1_overall_evaluation" class="view1_overall_evaluation"><div class="ewTableHeaderCaption"><?php echo $view1->overall_evaluation->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="overall_evaluation" class="<?php echo $view1->overall_evaluation->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->overall_evaluation) ?>',1);"><div id="elh_view1_overall_evaluation" class="view1_overall_evaluation">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->overall_evaluation->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->overall_evaluation->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->overall_evaluation->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->admin_approval->Visible) { // admin_approval ?>
	<?php if ($view1->SortUrl($view1->admin_approval) == "") { ?>
		<th data-name="admin_approval" class="<?php echo $view1->admin_approval->HeaderCellClass() ?>"><div id="elh_view1_admin_approval" class="view1_admin_approval"><div class="ewTableHeaderCaption"><?php echo $view1->admin_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="admin_approval" class="<?php echo $view1->admin_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->admin_approval) ?>',1);"><div id="elh_view1_admin_approval" class="view1_admin_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->admin_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->admin_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->admin_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->security_approval->Visible) { // security_approval ?>
	<?php if ($view1->SortUrl($view1->security_approval) == "") { ?>
		<th data-name="security_approval" class="<?php echo $view1->security_approval->HeaderCellClass() ?>"><div id="elh_view1_security_approval" class="view1_security_approval"><div class="ewTableHeaderCaption"><?php echo $view1->security_approval->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="security_approval" class="<?php echo $view1->security_approval->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->security_approval) ?>',1);"><div id="elh_view1_security_approval" class="view1_security_approval">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->security_approval->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->security_approval->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->security_approval->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->title_number->Visible) { // title_number ?>
	<?php if ($view1->SortUrl($view1->title_number) == "") { ?>
		<th data-name="title_number" class="<?php echo $view1->title_number->HeaderCellClass() ?>"><div id="elh_view1_title_number" class="view1_title_number"><div class="ewTableHeaderCaption"><?php echo $view1->title_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title_number" class="<?php echo $view1->title_number->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->title_number) ?>',1);"><div id="elh_view1_title_number" class="view1_title_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->title_number->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($view1->title_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->title_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Visible) { // Date_Format(users.date_of_birth, '%Y-%m-%d') ?>
	<?php if ($view1->SortUrl($view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729) == "") { ?>
		<th data-name="Date_Format28users_date_of_birth2C_2725Y_25m_25d2729" class="<?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->HeaderCellClass() ?>"><div id="elh_view1_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729" class="view1_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"><div class="ewTableHeaderCaption"><?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Date_Format28users_date_of_birth2C_2725Y_25m_25d2729" class="<?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $view1->SortUrl($view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729) ?>',1);"><div id="elh_view1_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729" class="view1_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$view1_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view1->ExportAll && $view1->Export <> "") {
	$view1_list->StopRec = $view1_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view1_list->TotalRecs > $view1_list->StartRec + $view1_list->DisplayRecs - 1)
		$view1_list->StopRec = $view1_list->StartRec + $view1_list->DisplayRecs - 1;
	else
		$view1_list->StopRec = $view1_list->TotalRecs;
}
$view1_list->RecCnt = $view1_list->StartRec - 1;
if ($view1_list->Recordset && !$view1_list->Recordset->EOF) {
	$view1_list->Recordset->MoveFirst();
	$bSelectLimit = $view1_list->UseSelectLimit;
	if (!$bSelectLimit && $view1_list->StartRec > 1)
		$view1_list->Recordset->Move($view1_list->StartRec - 1);
} elseif (!$view1->AllowAddDeleteRow && $view1_list->StopRec == 0) {
	$view1_list->StopRec = $view1->GridAddRowCount;
}

// Initialize aggregate
$view1->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view1->ResetAttrs();
$view1_list->RenderRow();
while ($view1_list->RecCnt < $view1_list->StopRec) {
	$view1_list->RecCnt++;
	if (intval($view1_list->RecCnt) >= intval($view1_list->StartRec)) {
		$view1_list->RowCnt++;

		// Set up key count
		$view1_list->KeyCount = $view1_list->RowIndex;

		// Init row class and style
		$view1->ResetAttrs();
		$view1->CssClass = "";
		if ($view1->CurrentAction == "gridadd") {
		} else {
			$view1_list->LoadRowValues($view1_list->Recordset); // Load row values
		}
		$view1->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view1->RowAttrs = array_merge($view1->RowAttrs, array('data-rowindex'=>$view1_list->RowCnt, 'id'=>'r' . $view1_list->RowCnt . '_view1', 'data-rowtype'=>$view1->RowType));

		// Render row
		$view1_list->RenderRow();

		// Render list options
		$view1_list->RenderListOptions();
?>
	<tr<?php echo $view1->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view1_list->ListOptions->Render("body", "left", $view1_list->RowCnt);
?>
	<?php if ($view1->user_id->Visible) { // user_id ?>
		<td data-name="user_id"<?php echo $view1->user_id->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_user_id" class="view1_user_id">
<span<?php echo $view1->user_id->ViewAttributes() ?>>
<?php echo $view1->user_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->eid_expiry_date->Visible) { // eid_expiry_date ?>
		<td data-name="eid_expiry_date"<?php echo $view1->eid_expiry_date->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_eid_expiry_date" class="view1_eid_expiry_date">
<span<?php echo $view1->eid_expiry_date->ViewAttributes() ?>>
<?php echo $view1->eid_expiry_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->passport_ex_date->Visible) { // passport_ex_date ?>
		<td data-name="passport_ex_date"<?php echo $view1->passport_ex_date->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_passport_ex_date" class="view1_passport_ex_date">
<span<?php echo $view1->passport_ex_date->ViewAttributes() ?>>
<?php echo $view1->passport_ex_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->nationality_type->Visible) { // nationality_type ?>
		<td data-name="nationality_type"<?php echo $view1->nationality_type->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_nationality_type" class="view1_nationality_type">
<span<?php echo $view1->nationality_type->ViewAttributes() ?>>
<?php echo $view1->nationality_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->unid->Visible) { // unid ?>
		<td data-name="unid"<?php echo $view1->unid->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_unid" class="view1_unid">
<span<?php echo $view1->unid->ViewAttributes() ?>>
<?php echo $view1->unid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->visa_expiry_date->Visible) { // visa_expiry_date ?>
		<td data-name="visa_expiry_date"<?php echo $view1->visa_expiry_date->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_visa_expiry_date" class="view1_visa_expiry_date">
<span<?php echo $view1->visa_expiry_date->ViewAttributes() ?>>
<?php echo $view1->visa_expiry_date->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->visa_copy->Visible) { // visa_copy ?>
		<td data-name="visa_copy"<?php echo $view1->visa_copy->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_visa_copy" class="view1_visa_copy">
<span<?php echo $view1->visa_copy->ViewAttributes() ?>>
<?php echo $view1->visa_copy->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->personal_photo->Visible) { // personal_photo ?>
		<td data-name="personal_photo"<?php echo $view1->personal_photo->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_personal_photo" class="view1_personal_photo">
<span<?php echo $view1->personal_photo->ViewAttributes() ?>>
<?php echo $view1->personal_photo->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->date_of_birth->Visible) { // date_of_birth ?>
		<td data-name="date_of_birth"<?php echo $view1->date_of_birth->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_date_of_birth" class="view1_date_of_birth">
<span<?php echo $view1->date_of_birth->ViewAttributes() ?>>
<?php echo $view1->date_of_birth->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->gender->Visible) { // gender ?>
		<td data-name="gender"<?php echo $view1->gender->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_gender" class="view1_gender">
<span<?php echo $view1->gender->ViewAttributes() ?>>
<?php echo $view1->gender->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->marital_status->Visible) { // marital_status ?>
		<td data-name="marital_status"<?php echo $view1->marital_status->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_marital_status" class="view1_marital_status">
<span<?php echo $view1->marital_status->ViewAttributes() ?>>
<?php echo $view1->marital_status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->blood_type->Visible) { // blood_type ?>
		<td data-name="blood_type"<?php echo $view1->blood_type->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_blood_type" class="view1_blood_type">
<span<?php echo $view1->blood_type->ViewAttributes() ?>>
<?php echo $view1->blood_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->driving_licence->Visible) { // driving_licence ?>
		<td data-name="driving_licence"<?php echo $view1->driving_licence->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_driving_licence" class="view1_driving_licence">
<span<?php echo $view1->driving_licence->ViewAttributes() ?>>
<?php echo $view1->driving_licence->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->job->Visible) { // job ?>
		<td data-name="job"<?php echo $view1->job->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_job" class="view1_job">
<span<?php echo $view1->job->ViewAttributes() ?>>
<?php echo $view1->job->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->volunteering_type->Visible) { // volunteering_type ?>
		<td data-name="volunteering_type"<?php echo $view1->volunteering_type->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_volunteering_type" class="view1_volunteering_type">
<span<?php echo $view1->volunteering_type->ViewAttributes() ?>>
<?php echo $view1->volunteering_type->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->overall_evaluation->Visible) { // overall_evaluation ?>
		<td data-name="overall_evaluation"<?php echo $view1->overall_evaluation->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_overall_evaluation" class="view1_overall_evaluation">
<span<?php echo $view1->overall_evaluation->ViewAttributes() ?>>
<?php echo $view1->overall_evaluation->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->admin_approval->Visible) { // admin_approval ?>
		<td data-name="admin_approval"<?php echo $view1->admin_approval->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_admin_approval" class="view1_admin_approval">
<span<?php echo $view1->admin_approval->ViewAttributes() ?>>
<?php echo $view1->admin_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->security_approval->Visible) { // security_approval ?>
		<td data-name="security_approval"<?php echo $view1->security_approval->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_security_approval" class="view1_security_approval">
<span<?php echo $view1->security_approval->ViewAttributes() ?>>
<?php echo $view1->security_approval->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->title_number->Visible) { // title_number ?>
		<td data-name="title_number"<?php echo $view1->title_number->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_title_number" class="view1_title_number">
<span<?php echo $view1->title_number->ViewAttributes() ?>>
<?php echo $view1->title_number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Visible) { // Date_Format(users.date_of_birth, '%Y-%m-%d') ?>
		<td data-name="Date_Format28users_date_of_birth2C_2725Y_25m_25d2729"<?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->CellAttributes() ?>>
<span id="el<?php echo $view1_list->RowCnt ?>_view1_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729" class="view1_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729">
<span<?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->ViewAttributes() ?>>
<?php echo $view1->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$view1_list->ListOptions->Render("body", "right", $view1_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view1->CurrentAction <> "gridadd")
		$view1_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view1->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view1_list->Recordset)
	$view1_list->Recordset->Close();
?>
<?php if ($view1->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($view1->CurrentAction <> "gridadd" && $view1->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($view1_list->Pager)) $view1_list->Pager = new cPrevNextPager($view1_list->StartRec, $view1_list->DisplayRecs, $view1_list->TotalRecs, $view1_list->AutoHidePager) ?>
<?php if ($view1_list->Pager->RecordCount > 0 && $view1_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($view1_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($view1_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $view1_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($view1_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($view1_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $view1_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($view1_list->TotalRecs == 0 && $view1->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($view1_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($view1->Export == "") { ?>
<script type="text/javascript">
fview1listsrch.FilterList = <?php echo $view1_list->GetFilterList() ?>;
fview1listsrch.Init();
fview1list.Init();
</script>
<?php } ?>
<?php
$view1_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($view1->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$view1_list->Page_Terminate();
?>
