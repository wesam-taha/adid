<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "members_titles_criteriainfo.php" ?>
<?php include_once "managementinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$members_titles_criteria_list = NULL; // Initialize page object first

class cmembers_titles_criteria_list extends cmembers_titles_criteria {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'members_titles_criteria';

	// Page object name
	var $PageObjName = 'members_titles_criteria_list';

	// Grid form hidden field names
	var $FormName = 'fmembers_titles_criterialist';
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

		// Table object (members_titles_criteria)
		if (!isset($GLOBALS["members_titles_criteria"]) || get_class($GLOBALS["members_titles_criteria"]) == "cmembers_titles_criteria") {
			$GLOBALS["members_titles_criteria"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["members_titles_criteria"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "members_titles_criteriaadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "members_titles_criteriadelete.php";
		$this->MultiUpdateUrl = "members_titles_criteriaupdate.php";

		// Table object (management)
		if (!isset($GLOBALS['management'])) $GLOBALS['management'] = new cmanagement();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'members_titles_criteria', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fmembers_titles_criterialistsrch";

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
		// Create form object

		$objForm = new cFormObj();

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
		$this->level->SetVisibility();
		$this->hours_from->SetVisibility();
		$this->hours_to->SetVisibility();
		$this->title->SetVisibility();
		$this->title_en->SetVisibility();

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
		global $EW_EXPORT, $members_titles_criteria;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($members_titles_criteria);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
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

	// Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_level") && $objForm->HasValue("o_level") && $this->level->CurrentValue <> $this->level->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_hours_from") && $objForm->HasValue("o_hours_from") && $this->hours_from->CurrentValue <> $this->hours_from->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_hours_to") && $objForm->HasValue("o_hours_to") && $this->hours_to->CurrentValue <> $this->hours_to->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_title") && $objForm->HasValue("o_title") && $this->title->CurrentValue <> $this->title->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_title_en") && $objForm->HasValue("o_title_en") && $this->title_en->CurrentValue <> $this->title_en->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = isset($UserProfile) ? $UserProfile->GetSearchFilters(CurrentUserName(), "fmembers_titles_criterialistsrch") : "";
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->id->AdvancedSearch->ToJson(), ","); // Field id
		$sFilterList = ew_Concat($sFilterList, $this->level->AdvancedSearch->ToJson(), ","); // Field level
		$sFilterList = ew_Concat($sFilterList, $this->hours_from->AdvancedSearch->ToJson(), ","); // Field hours_from
		$sFilterList = ew_Concat($sFilterList, $this->hours_to->AdvancedSearch->ToJson(), ","); // Field hours_to
		$sFilterList = ew_Concat($sFilterList, $this->title->AdvancedSearch->ToJson(), ","); // Field title
		$sFilterList = ew_Concat($sFilterList, $this->title_en->AdvancedSearch->ToJson(), ","); // Field title_en
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fmembers_titles_criterialistsrch", $filters);

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

		// Field level
		$this->level->AdvancedSearch->SearchValue = @$filter["x_level"];
		$this->level->AdvancedSearch->SearchOperator = @$filter["z_level"];
		$this->level->AdvancedSearch->SearchCondition = @$filter["v_level"];
		$this->level->AdvancedSearch->SearchValue2 = @$filter["y_level"];
		$this->level->AdvancedSearch->SearchOperator2 = @$filter["w_level"];
		$this->level->AdvancedSearch->Save();

		// Field hours_from
		$this->hours_from->AdvancedSearch->SearchValue = @$filter["x_hours_from"];
		$this->hours_from->AdvancedSearch->SearchOperator = @$filter["z_hours_from"];
		$this->hours_from->AdvancedSearch->SearchCondition = @$filter["v_hours_from"];
		$this->hours_from->AdvancedSearch->SearchValue2 = @$filter["y_hours_from"];
		$this->hours_from->AdvancedSearch->SearchOperator2 = @$filter["w_hours_from"];
		$this->hours_from->AdvancedSearch->Save();

		// Field hours_to
		$this->hours_to->AdvancedSearch->SearchValue = @$filter["x_hours_to"];
		$this->hours_to->AdvancedSearch->SearchOperator = @$filter["z_hours_to"];
		$this->hours_to->AdvancedSearch->SearchCondition = @$filter["v_hours_to"];
		$this->hours_to->AdvancedSearch->SearchValue2 = @$filter["y_hours_to"];
		$this->hours_to->AdvancedSearch->SearchOperator2 = @$filter["w_hours_to"];
		$this->hours_to->AdvancedSearch->Save();

		// Field title
		$this->title->AdvancedSearch->SearchValue = @$filter["x_title"];
		$this->title->AdvancedSearch->SearchOperator = @$filter["z_title"];
		$this->title->AdvancedSearch->SearchCondition = @$filter["v_title"];
		$this->title->AdvancedSearch->SearchValue2 = @$filter["y_title"];
		$this->title->AdvancedSearch->SearchOperator2 = @$filter["w_title"];
		$this->title->AdvancedSearch->Save();

		// Field title_en
		$this->title_en->AdvancedSearch->SearchValue = @$filter["x_title_en"];
		$this->title_en->AdvancedSearch->SearchOperator = @$filter["z_title_en"];
		$this->title_en->AdvancedSearch->SearchCondition = @$filter["v_title_en"];
		$this->title_en->AdvancedSearch->SearchValue2 = @$filter["y_title_en"];
		$this->title_en->AdvancedSearch->SearchOperator2 = @$filter["w_title_en"];
		$this->title_en->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->hours_from, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->hours_to, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->title, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->title_en, $arKeywords, $type);
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
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->level); // level
			$this->UpdateSort($this->hours_from); // hours_from
			$this->UpdateSort($this->hours_to); // hours_to
			$this->UpdateSort($this->title); // title
			$this->UpdateSort($this->title_en); // title_en
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
				$this->id->setSort("");
				$this->level->setSort("");
				$this->hours_from->setSort("");
				$this->hours_to->setSort("");
				$this->title->setSort("");
				$this->title_en->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssClass = "text-nowrap";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

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
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->id->CurrentValue . "\">";
		}
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
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.fmembers_titles_criterialist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fmembers_titles_criterialistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fmembers_titles_criterialistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fmembers_titles_criterialist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fmembers_titles_criterialistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->level->CurrentValue = NULL;
		$this->level->OldValue = $this->level->CurrentValue;
		$this->hours_from->CurrentValue = NULL;
		$this->hours_from->OldValue = $this->hours_from->CurrentValue;
		$this->hours_to->CurrentValue = NULL;
		$this->hours_to->OldValue = $this->hours_to->CurrentValue;
		$this->title->CurrentValue = NULL;
		$this->title->OldValue = $this->title->CurrentValue;
		$this->title_en->CurrentValue = NULL;
		$this->title_en->OldValue = $this->title_en->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->level->FldIsDetailKey) {
			$this->level->setFormValue($objForm->GetValue("x_level"));
		}
		$this->level->setOldValue($objForm->GetValue("o_level"));
		if (!$this->hours_from->FldIsDetailKey) {
			$this->hours_from->setFormValue($objForm->GetValue("x_hours_from"));
		}
		$this->hours_from->setOldValue($objForm->GetValue("o_hours_from"));
		if (!$this->hours_to->FldIsDetailKey) {
			$this->hours_to->setFormValue($objForm->GetValue("x_hours_to"));
		}
		$this->hours_to->setOldValue($objForm->GetValue("o_hours_to"));
		if (!$this->title->FldIsDetailKey) {
			$this->title->setFormValue($objForm->GetValue("x_title"));
		}
		$this->title->setOldValue($objForm->GetValue("o_title"));
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
		$this->title_en->setOldValue($objForm->GetValue("o_title_en"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->level->CurrentValue = $this->level->FormValue;
		$this->hours_from->CurrentValue = $this->hours_from->FormValue;
		$this->hours_to->CurrentValue = $this->hours_to->FormValue;
		$this->title->CurrentValue = $this->title->FormValue;
		$this->title_en->CurrentValue = $this->title_en->FormValue;
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
		$this->level->setDbValue($row['level']);
		$this->hours_from->setDbValue($row['hours_from']);
		$this->hours_to->setDbValue($row['hours_to']);
		$this->title->setDbValue($row['title']);
		$this->title_en->setDbValue($row['title_en']);
	}

	// Return a row with NULL values
	function NullRow() {
		$row = array();
		$row['id'] = NULL;
		$row['level'] = NULL;
		$row['hours_from'] = NULL;
		$row['hours_to'] = NULL;
		$row['title'] = NULL;
		$row['title_en'] = NULL;
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
		$this->level->DbValue = $row['level'];
		$this->hours_from->DbValue = $row['hours_from'];
		$this->hours_to->DbValue = $row['hours_to'];
		$this->title->DbValue = $row['title'];
		$this->title_en->DbValue = $row['title_en'];
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
		// level
		// hours_from
		// hours_to
		// title
		// title_en

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id
		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// level
		$this->level->ViewValue = $this->level->CurrentValue;
		$this->level->ViewCustomAttributes = "";

		// hours_from
		$this->hours_from->ViewValue = $this->hours_from->CurrentValue;
		$this->hours_from->ViewCustomAttributes = "";

		// hours_to
		$this->hours_to->ViewValue = $this->hours_to->CurrentValue;
		$this->hours_to->ViewCustomAttributes = "";

		// title
		$this->title->ViewValue = $this->title->CurrentValue;
		$this->title->ViewCustomAttributes = "";

		// title_en
		$this->title_en->ViewValue = $this->title_en->CurrentValue;
		$this->title_en->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";
			$this->level->TooltipValue = "";

			// hours_from
			$this->hours_from->LinkCustomAttributes = "";
			$this->hours_from->HrefValue = "";
			$this->hours_from->TooltipValue = "";

			// hours_to
			$this->hours_to->LinkCustomAttributes = "";
			$this->hours_to->HrefValue = "";
			$this->hours_to->TooltipValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";
			$this->title->TooltipValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			// level

			$this->level->EditAttrs["class"] = "form-control";
			$this->level->EditCustomAttributes = "";
			$this->level->EditValue = ew_HtmlEncode($this->level->CurrentValue);
			$this->level->PlaceHolder = ew_RemoveHtml($this->level->FldCaption());

			// hours_from
			$this->hours_from->EditAttrs["class"] = "form-control";
			$this->hours_from->EditCustomAttributes = "";
			$this->hours_from->EditValue = ew_HtmlEncode($this->hours_from->CurrentValue);
			$this->hours_from->PlaceHolder = ew_RemoveHtml($this->hours_from->FldCaption());

			// hours_to
			$this->hours_to->EditAttrs["class"] = "form-control";
			$this->hours_to->EditCustomAttributes = "";
			$this->hours_to->EditValue = ew_HtmlEncode($this->hours_to->CurrentValue);
			$this->hours_to->PlaceHolder = ew_RemoveHtml($this->hours_to->FldCaption());

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// title_en
			$this->title_en->EditAttrs["class"] = "form-control";
			$this->title_en->EditCustomAttributes = "";
			$this->title_en->EditValue = ew_HtmlEncode($this->title_en->CurrentValue);
			$this->title_en->PlaceHolder = ew_RemoveHtml($this->title_en->FldCaption());

			// Add refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";

			// hours_from
			$this->hours_from->LinkCustomAttributes = "";
			$this->hours_from->HrefValue = "";

			// hours_to
			$this->hours_to->LinkCustomAttributes = "";
			$this->hours_to->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// level
			$this->level->EditAttrs["class"] = "form-control";
			$this->level->EditCustomAttributes = "";
			$this->level->EditValue = ew_HtmlEncode($this->level->CurrentValue);
			$this->level->PlaceHolder = ew_RemoveHtml($this->level->FldCaption());

			// hours_from
			$this->hours_from->EditAttrs["class"] = "form-control";
			$this->hours_from->EditCustomAttributes = "";
			$this->hours_from->EditValue = ew_HtmlEncode($this->hours_from->CurrentValue);
			$this->hours_from->PlaceHolder = ew_RemoveHtml($this->hours_from->FldCaption());

			// hours_to
			$this->hours_to->EditAttrs["class"] = "form-control";
			$this->hours_to->EditCustomAttributes = "";
			$this->hours_to->EditValue = ew_HtmlEncode($this->hours_to->CurrentValue);
			$this->hours_to->PlaceHolder = ew_RemoveHtml($this->hours_to->FldCaption());

			// title
			$this->title->EditAttrs["class"] = "form-control";
			$this->title->EditCustomAttributes = "";
			$this->title->EditValue = ew_HtmlEncode($this->title->CurrentValue);
			$this->title->PlaceHolder = ew_RemoveHtml($this->title->FldCaption());

			// title_en
			$this->title_en->EditAttrs["class"] = "form-control";
			$this->title_en->EditCustomAttributes = "";
			$this->title_en->EditValue = ew_HtmlEncode($this->title_en->CurrentValue);
			$this->title_en->PlaceHolder = ew_RemoveHtml($this->title_en->FldCaption());

			// Edit refer script
			// id

			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";

			// hours_from
			$this->hours_from->LinkCustomAttributes = "";
			$this->hours_from->HrefValue = "";

			// hours_to
			$this->hours_to->LinkCustomAttributes = "";
			$this->hours_to->HrefValue = "";

			// title
			$this->title->LinkCustomAttributes = "";
			$this->title->HrefValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
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
		if (!$this->level->FldIsDetailKey && !is_null($this->level->FormValue) && $this->level->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->level->FldCaption(), $this->level->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->level->FormValue)) {
			ew_AddMessage($gsFormError, $this->level->FldErrMsg());
		}
		if (!$this->hours_from->FldIsDetailKey && !is_null($this->hours_from->FormValue) && $this->hours_from->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hours_from->FldCaption(), $this->hours_from->ReqErrMsg));
		}
		if (!$this->hours_to->FldIsDetailKey && !is_null($this->hours_to->FormValue) && $this->hours_to->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hours_to->FldCaption(), $this->hours_to->ReqErrMsg));
		}
		if (!$this->title->FldIsDetailKey && !is_null($this->title->FormValue) && $this->title->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title->FldCaption(), $this->title->ReqErrMsg));
		}
		if (!$this->title_en->FldIsDetailKey && !is_null($this->title_en->FormValue) && $this->title_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_en->FldCaption(), $this->title_en->ReqErrMsg));
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
			$rsnew = array();

			// level
			$this->level->SetDbValueDef($rsnew, $this->level->CurrentValue, 0, $this->level->ReadOnly);

			// hours_from
			$this->hours_from->SetDbValueDef($rsnew, $this->hours_from->CurrentValue, "", $this->hours_from->ReadOnly);

			// hours_to
			$this->hours_to->SetDbValueDef($rsnew, $this->hours_to->CurrentValue, "", $this->hours_to->ReadOnly);

			// title
			$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", $this->title->ReadOnly);

			// title_en
			$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", $this->title_en->ReadOnly);

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
		return $EditRow;
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

		// level
		$this->level->SetDbValueDef($rsnew, $this->level->CurrentValue, 0, FALSE);

		// hours_from
		$this->hours_from->SetDbValueDef($rsnew, $this->hours_from->CurrentValue, "", FALSE);

		// hours_to
		$this->hours_to->SetDbValueDef($rsnew, $this->hours_to->CurrentValue, "", FALSE);

		// title
		$this->title->SetDbValueDef($rsnew, $this->title->CurrentValue, "", FALSE);

		// title_en
		$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", FALSE);

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
		$item->Body = "<button id=\"emf_members_titles_criteria\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_members_titles_criteria',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fmembers_titles_criterialist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($members_titles_criteria_list)) $members_titles_criteria_list = new cmembers_titles_criteria_list();

// Page init
$members_titles_criteria_list->Page_Init();

// Page main
$members_titles_criteria_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$members_titles_criteria_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($members_titles_criteria->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fmembers_titles_criterialist = new ew_Form("fmembers_titles_criterialist", "list");
fmembers_titles_criterialist.FormKeyCountName = '<?php echo $members_titles_criteria_list->FormKeyCountName ?>';

// Validate form
fmembers_titles_criterialist.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_level");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->level->FldCaption(), $members_titles_criteria->level->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_level");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($members_titles_criteria->level->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_hours_from");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->hours_from->FldCaption(), $members_titles_criteria->hours_from->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hours_to");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->hours_to->FldCaption(), $members_titles_criteria->hours_to->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->title->FldCaption(), $members_titles_criteria->title->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $members_titles_criteria->title_en->FldCaption(), $members_titles_criteria->title_en->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fmembers_titles_criterialist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "level", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hours_from", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hours_to", false)) return false;
	if (ew_ValueChanged(fobj, infix, "title", false)) return false;
	if (ew_ValueChanged(fobj, infix, "title_en", false)) return false;
	return true;
}

// Form_CustomValidate event
fmembers_titles_criterialist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fmembers_titles_criterialist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fmembers_titles_criterialistsrch = new ew_Form("fmembers_titles_criterialistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($members_titles_criteria->Export == "") { ?>
<div class="ewToolbar">
<?php if ($members_titles_criteria_list->TotalRecs > 0 && $members_titles_criteria_list->ExportOptions->Visible()) { ?>
<?php $members_titles_criteria_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($members_titles_criteria_list->SearchOptions->Visible()) { ?>
<?php $members_titles_criteria_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($members_titles_criteria_list->FilterOptions->Visible()) { ?>
<?php $members_titles_criteria_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($members_titles_criteria->CurrentAction == "gridadd") {
	$members_titles_criteria->CurrentFilter = "0=1";
	$members_titles_criteria_list->StartRec = 1;
	$members_titles_criteria_list->DisplayRecs = $members_titles_criteria->GridAddRowCount;
	$members_titles_criteria_list->TotalRecs = $members_titles_criteria_list->DisplayRecs;
	$members_titles_criteria_list->StopRec = $members_titles_criteria_list->DisplayRecs;
} else {
	$bSelectLimit = $members_titles_criteria_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($members_titles_criteria_list->TotalRecs <= 0)
			$members_titles_criteria_list->TotalRecs = $members_titles_criteria->ListRecordCount();
	} else {
		if (!$members_titles_criteria_list->Recordset && ($members_titles_criteria_list->Recordset = $members_titles_criteria_list->LoadRecordset()))
			$members_titles_criteria_list->TotalRecs = $members_titles_criteria_list->Recordset->RecordCount();
	}
	$members_titles_criteria_list->StartRec = 1;
	if ($members_titles_criteria_list->DisplayRecs <= 0 || ($members_titles_criteria->Export <> "" && $members_titles_criteria->ExportAll)) // Display all records
		$members_titles_criteria_list->DisplayRecs = $members_titles_criteria_list->TotalRecs;
	if (!($members_titles_criteria->Export <> "" && $members_titles_criteria->ExportAll))
		$members_titles_criteria_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$members_titles_criteria_list->Recordset = $members_titles_criteria_list->LoadRecordset($members_titles_criteria_list->StartRec-1, $members_titles_criteria_list->DisplayRecs);

	// Set no record found message
	if ($members_titles_criteria->CurrentAction == "" && $members_titles_criteria_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$members_titles_criteria_list->setWarningMessage(ew_DeniedMsg());
		if ($members_titles_criteria_list->SearchWhere == "0=101")
			$members_titles_criteria_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$members_titles_criteria_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$members_titles_criteria_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($members_titles_criteria->Export == "" && $members_titles_criteria->CurrentAction == "") { ?>
<form name="fmembers_titles_criterialistsrch" id="fmembers_titles_criterialistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($members_titles_criteria_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fmembers_titles_criterialistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="members_titles_criteria">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($members_titles_criteria_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($members_titles_criteria_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $members_titles_criteria_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($members_titles_criteria_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($members_titles_criteria_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($members_titles_criteria_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($members_titles_criteria_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $members_titles_criteria_list->ShowPageHeader(); ?>
<?php
$members_titles_criteria_list->ShowMessage();
?>
<?php if ($members_titles_criteria_list->TotalRecs > 0 || $members_titles_criteria->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($members_titles_criteria_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> members_titles_criteria">
<?php if ($members_titles_criteria->Export == "") { ?>
<div class="box-header ewGridUpperPanel">
<?php if ($members_titles_criteria->CurrentAction <> "gridadd" && $members_titles_criteria->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($members_titles_criteria_list->Pager)) $members_titles_criteria_list->Pager = new cPrevNextPager($members_titles_criteria_list->StartRec, $members_titles_criteria_list->DisplayRecs, $members_titles_criteria_list->TotalRecs, $members_titles_criteria_list->AutoHidePager) ?>
<?php if ($members_titles_criteria_list->Pager->RecordCount > 0 && $members_titles_criteria_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($members_titles_criteria_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($members_titles_criteria_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $members_titles_criteria_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($members_titles_criteria_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($members_titles_criteria_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($members_titles_criteria_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fmembers_titles_criterialist" id="fmembers_titles_criterialist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($members_titles_criteria_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $members_titles_criteria_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="members_titles_criteria">
<div id="gmp_members_titles_criteria" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($members_titles_criteria_list->TotalRecs > 0 || $members_titles_criteria->CurrentAction == "gridedit") { ?>
<table id="tbl_members_titles_criterialist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$members_titles_criteria_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$members_titles_criteria_list->RenderListOptions();

// Render list options (header, left)
$members_titles_criteria_list->ListOptions->Render("header", "left");
?>
<?php if ($members_titles_criteria->id->Visible) { // id ?>
	<?php if ($members_titles_criteria->SortUrl($members_titles_criteria->id) == "") { ?>
		<th data-name="id" class="<?php echo $members_titles_criteria->id->HeaderCellClass() ?>"><div id="elh_members_titles_criteria_id" class="members_titles_criteria_id"><div class="ewTableHeaderCaption"><?php echo $members_titles_criteria->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id" class="<?php echo $members_titles_criteria->id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $members_titles_criteria->SortUrl($members_titles_criteria->id) ?>',1);"><div id="elh_members_titles_criteria_id" class="members_titles_criteria_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $members_titles_criteria->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($members_titles_criteria->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($members_titles_criteria->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->level->Visible) { // level ?>
	<?php if ($members_titles_criteria->SortUrl($members_titles_criteria->level) == "") { ?>
		<th data-name="level" class="<?php echo $members_titles_criteria->level->HeaderCellClass() ?>"><div id="elh_members_titles_criteria_level" class="members_titles_criteria_level"><div class="ewTableHeaderCaption"><?php echo $members_titles_criteria->level->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="level" class="<?php echo $members_titles_criteria->level->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $members_titles_criteria->SortUrl($members_titles_criteria->level) ?>',1);"><div id="elh_members_titles_criteria_level" class="members_titles_criteria_level">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $members_titles_criteria->level->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($members_titles_criteria->level->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($members_titles_criteria->level->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->hours_from->Visible) { // hours_from ?>
	<?php if ($members_titles_criteria->SortUrl($members_titles_criteria->hours_from) == "") { ?>
		<th data-name="hours_from" class="<?php echo $members_titles_criteria->hours_from->HeaderCellClass() ?>"><div id="elh_members_titles_criteria_hours_from" class="members_titles_criteria_hours_from"><div class="ewTableHeaderCaption"><?php echo $members_titles_criteria->hours_from->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hours_from" class="<?php echo $members_titles_criteria->hours_from->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $members_titles_criteria->SortUrl($members_titles_criteria->hours_from) ?>',1);"><div id="elh_members_titles_criteria_hours_from" class="members_titles_criteria_hours_from">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $members_titles_criteria->hours_from->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($members_titles_criteria->hours_from->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($members_titles_criteria->hours_from->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->hours_to->Visible) { // hours_to ?>
	<?php if ($members_titles_criteria->SortUrl($members_titles_criteria->hours_to) == "") { ?>
		<th data-name="hours_to" class="<?php echo $members_titles_criteria->hours_to->HeaderCellClass() ?>"><div id="elh_members_titles_criteria_hours_to" class="members_titles_criteria_hours_to"><div class="ewTableHeaderCaption"><?php echo $members_titles_criteria->hours_to->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hours_to" class="<?php echo $members_titles_criteria->hours_to->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $members_titles_criteria->SortUrl($members_titles_criteria->hours_to) ?>',1);"><div id="elh_members_titles_criteria_hours_to" class="members_titles_criteria_hours_to">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $members_titles_criteria->hours_to->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($members_titles_criteria->hours_to->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($members_titles_criteria->hours_to->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->title->Visible) { // title ?>
	<?php if ($members_titles_criteria->SortUrl($members_titles_criteria->title) == "") { ?>
		<th data-name="title" class="<?php echo $members_titles_criteria->title->HeaderCellClass() ?>"><div id="elh_members_titles_criteria_title" class="members_titles_criteria_title"><div class="ewTableHeaderCaption"><?php echo $members_titles_criteria->title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title" class="<?php echo $members_titles_criteria->title->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $members_titles_criteria->SortUrl($members_titles_criteria->title) ?>',1);"><div id="elh_members_titles_criteria_title" class="members_titles_criteria_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $members_titles_criteria->title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($members_titles_criteria->title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($members_titles_criteria->title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($members_titles_criteria->title_en->Visible) { // title_en ?>
	<?php if ($members_titles_criteria->SortUrl($members_titles_criteria->title_en) == "") { ?>
		<th data-name="title_en" class="<?php echo $members_titles_criteria->title_en->HeaderCellClass() ?>"><div id="elh_members_titles_criteria_title_en" class="members_titles_criteria_title_en"><div class="ewTableHeaderCaption"><?php echo $members_titles_criteria->title_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title_en" class="<?php echo $members_titles_criteria->title_en->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $members_titles_criteria->SortUrl($members_titles_criteria->title_en) ?>',1);"><div id="elh_members_titles_criteria_title_en" class="members_titles_criteria_title_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $members_titles_criteria->title_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($members_titles_criteria->title_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($members_titles_criteria->title_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$members_titles_criteria_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($members_titles_criteria->ExportAll && $members_titles_criteria->Export <> "") {
	$members_titles_criteria_list->StopRec = $members_titles_criteria_list->TotalRecs;
} else {

	// Set the last record to display
	if ($members_titles_criteria_list->TotalRecs > $members_titles_criteria_list->StartRec + $members_titles_criteria_list->DisplayRecs - 1)
		$members_titles_criteria_list->StopRec = $members_titles_criteria_list->StartRec + $members_titles_criteria_list->DisplayRecs - 1;
	else
		$members_titles_criteria_list->StopRec = $members_titles_criteria_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($members_titles_criteria_list->FormKeyCountName) && ($members_titles_criteria->CurrentAction == "gridadd" || $members_titles_criteria->CurrentAction == "gridedit" || $members_titles_criteria->CurrentAction == "F")) {
		$members_titles_criteria_list->KeyCount = $objForm->GetValue($members_titles_criteria_list->FormKeyCountName);
		$members_titles_criteria_list->StopRec = $members_titles_criteria_list->StartRec + $members_titles_criteria_list->KeyCount - 1;
	}
}
$members_titles_criteria_list->RecCnt = $members_titles_criteria_list->StartRec - 1;
if ($members_titles_criteria_list->Recordset && !$members_titles_criteria_list->Recordset->EOF) {
	$members_titles_criteria_list->Recordset->MoveFirst();
	$bSelectLimit = $members_titles_criteria_list->UseSelectLimit;
	if (!$bSelectLimit && $members_titles_criteria_list->StartRec > 1)
		$members_titles_criteria_list->Recordset->Move($members_titles_criteria_list->StartRec - 1);
} elseif (!$members_titles_criteria->AllowAddDeleteRow && $members_titles_criteria_list->StopRec == 0) {
	$members_titles_criteria_list->StopRec = $members_titles_criteria->GridAddRowCount;
}

// Initialize aggregate
$members_titles_criteria->RowType = EW_ROWTYPE_AGGREGATEINIT;
$members_titles_criteria->ResetAttrs();
$members_titles_criteria_list->RenderRow();
if ($members_titles_criteria->CurrentAction == "gridadd")
	$members_titles_criteria_list->RowIndex = 0;
if ($members_titles_criteria->CurrentAction == "gridedit")
	$members_titles_criteria_list->RowIndex = 0;
while ($members_titles_criteria_list->RecCnt < $members_titles_criteria_list->StopRec) {
	$members_titles_criteria_list->RecCnt++;
	if (intval($members_titles_criteria_list->RecCnt) >= intval($members_titles_criteria_list->StartRec)) {
		$members_titles_criteria_list->RowCnt++;
		if ($members_titles_criteria->CurrentAction == "gridadd" || $members_titles_criteria->CurrentAction == "gridedit" || $members_titles_criteria->CurrentAction == "F") {
			$members_titles_criteria_list->RowIndex++;
			$objForm->Index = $members_titles_criteria_list->RowIndex;
			if ($objForm->HasValue($members_titles_criteria_list->FormActionName))
				$members_titles_criteria_list->RowAction = strval($objForm->GetValue($members_titles_criteria_list->FormActionName));
			elseif ($members_titles_criteria->CurrentAction == "gridadd")
				$members_titles_criteria_list->RowAction = "insert";
			else
				$members_titles_criteria_list->RowAction = "";
		}

		// Set up key count
		$members_titles_criteria_list->KeyCount = $members_titles_criteria_list->RowIndex;

		// Init row class and style
		$members_titles_criteria->ResetAttrs();
		$members_titles_criteria->CssClass = "";
		if ($members_titles_criteria->CurrentAction == "gridadd") {
			$members_titles_criteria_list->LoadDefaultValues(); // Load default values
		} else {
			$members_titles_criteria_list->LoadRowValues($members_titles_criteria_list->Recordset); // Load row values
		}
		$members_titles_criteria->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($members_titles_criteria->CurrentAction == "gridadd") // Grid add
			$members_titles_criteria->RowType = EW_ROWTYPE_ADD; // Render add
		if ($members_titles_criteria->CurrentAction == "gridadd" && $members_titles_criteria->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$members_titles_criteria_list->RestoreCurrentRowFormValues($members_titles_criteria_list->RowIndex); // Restore form values
		if ($members_titles_criteria->CurrentAction == "gridedit") { // Grid edit
			if ($members_titles_criteria->EventCancelled) {
				$members_titles_criteria_list->RestoreCurrentRowFormValues($members_titles_criteria_list->RowIndex); // Restore form values
			}
			if ($members_titles_criteria_list->RowAction == "insert")
				$members_titles_criteria->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$members_titles_criteria->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($members_titles_criteria->CurrentAction == "gridedit" && ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT || $members_titles_criteria->RowType == EW_ROWTYPE_ADD) && $members_titles_criteria->EventCancelled) // Update failed
			$members_titles_criteria_list->RestoreCurrentRowFormValues($members_titles_criteria_list->RowIndex); // Restore form values
		if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) // Edit row
			$members_titles_criteria_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$members_titles_criteria->RowAttrs = array_merge($members_titles_criteria->RowAttrs, array('data-rowindex'=>$members_titles_criteria_list->RowCnt, 'id'=>'r' . $members_titles_criteria_list->RowCnt . '_members_titles_criteria', 'data-rowtype'=>$members_titles_criteria->RowType));

		// Render row
		$members_titles_criteria_list->RenderRow();

		// Render list options
		$members_titles_criteria_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($members_titles_criteria_list->RowAction <> "delete" && $members_titles_criteria_list->RowAction <> "insertdelete" && !($members_titles_criteria_list->RowAction == "insert" && $members_titles_criteria->CurrentAction == "F" && $members_titles_criteria_list->EmptyRow())) {
?>
	<tr<?php echo $members_titles_criteria->RowAttributes() ?>>
<?php

// Render list options (body, left)
$members_titles_criteria_list->ListOptions->Render("body", "left", $members_titles_criteria_list->RowCnt);
?>
	<?php if ($members_titles_criteria->id->Visible) { // id ?>
		<td data-name="id"<?php echo $members_titles_criteria->id->CellAttributes() ?>>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="members_titles_criteria" data-field="x_id" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_id" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($members_titles_criteria->id->OldValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_id" class="form-group members_titles_criteria_id">
<span<?php echo $members_titles_criteria->id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $members_titles_criteria->id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_id" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_id" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($members_titles_criteria->id->CurrentValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_id" class="members_titles_criteria_id">
<span<?php echo $members_titles_criteria->id->ViewAttributes() ?>>
<?php echo $members_titles_criteria->id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->level->Visible) { // level ?>
		<td data-name="level"<?php echo $members_titles_criteria->level->CellAttributes() ?>>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_level" class="form-group members_titles_criteria_level">
<input type="text" data-table="members_titles_criteria" data-field="x_level" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_level" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_level" size="30" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->level->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->level->EditValue ?>"<?php echo $members_titles_criteria->level->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_level" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_level" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_level" value="<?php echo ew_HtmlEncode($members_titles_criteria->level->OldValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_level" class="form-group members_titles_criteria_level">
<input type="text" data-table="members_titles_criteria" data-field="x_level" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_level" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_level" size="30" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->level->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->level->EditValue ?>"<?php echo $members_titles_criteria->level->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_level" class="members_titles_criteria_level">
<span<?php echo $members_titles_criteria->level->ViewAttributes() ?>>
<?php echo $members_titles_criteria->level->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->hours_from->Visible) { // hours_from ?>
		<td data-name="hours_from"<?php echo $members_titles_criteria->hours_from->CellAttributes() ?>>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_hours_from" class="form-group members_titles_criteria_hours_from">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_from" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_from->EditValue ?>"<?php echo $members_titles_criteria->hours_from->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_hours_from" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" value="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->OldValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_hours_from" class="form-group members_titles_criteria_hours_from">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_from" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_from->EditValue ?>"<?php echo $members_titles_criteria->hours_from->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_hours_from" class="members_titles_criteria_hours_from">
<span<?php echo $members_titles_criteria->hours_from->ViewAttributes() ?>>
<?php echo $members_titles_criteria->hours_from->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->hours_to->Visible) { // hours_to ?>
		<td data-name="hours_to"<?php echo $members_titles_criteria->hours_to->CellAttributes() ?>>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_hours_to" class="form-group members_titles_criteria_hours_to">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_to" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_to->EditValue ?>"<?php echo $members_titles_criteria->hours_to->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_hours_to" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" value="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->OldValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_hours_to" class="form-group members_titles_criteria_hours_to">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_to" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_to->EditValue ?>"<?php echo $members_titles_criteria->hours_to->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_hours_to" class="members_titles_criteria_hours_to">
<span<?php echo $members_titles_criteria->hours_to->ViewAttributes() ?>>
<?php echo $members_titles_criteria->hours_to->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->title->Visible) { // title ?>
		<td data-name="title"<?php echo $members_titles_criteria->title->CellAttributes() ?>>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_title" class="form-group members_titles_criteria_title">
<input type="text" data-table="members_titles_criteria" data-field="x_title" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_title" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_title" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title->EditValue ?>"<?php echo $members_titles_criteria->title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_title" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_title" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($members_titles_criteria->title->OldValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_title" class="form-group members_titles_criteria_title">
<input type="text" data-table="members_titles_criteria" data-field="x_title" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_title" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_title" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title->EditValue ?>"<?php echo $members_titles_criteria->title->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_title" class="members_titles_criteria_title">
<span<?php echo $members_titles_criteria->title->ViewAttributes() ?>>
<?php echo $members_titles_criteria->title->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->title_en->Visible) { // title_en ?>
		<td data-name="title_en"<?php echo $members_titles_criteria->title_en->CellAttributes() ?>>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_title_en" class="form-group members_titles_criteria_title_en">
<input type="text" data-table="members_titles_criteria" data-field="x_title_en" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title_en->EditValue ?>"<?php echo $members_titles_criteria->title_en->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_title_en" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" value="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->OldValue) ?>">
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_title_en" class="form-group members_titles_criteria_title_en">
<input type="text" data-table="members_titles_criteria" data-field="x_title_en" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title_en->EditValue ?>"<?php echo $members_titles_criteria->title_en->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $members_titles_criteria_list->RowCnt ?>_members_titles_criteria_title_en" class="members_titles_criteria_title_en">
<span<?php echo $members_titles_criteria->title_en->ViewAttributes() ?>>
<?php echo $members_titles_criteria->title_en->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$members_titles_criteria_list->ListOptions->Render("body", "right", $members_titles_criteria_list->RowCnt);
?>
	</tr>
<?php if ($members_titles_criteria->RowType == EW_ROWTYPE_ADD || $members_titles_criteria->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fmembers_titles_criterialist.UpdateOpts(<?php echo $members_titles_criteria_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($members_titles_criteria->CurrentAction <> "gridadd")
		if (!$members_titles_criteria_list->Recordset->EOF) $members_titles_criteria_list->Recordset->MoveNext();
}
?>
<?php
	if ($members_titles_criteria->CurrentAction == "gridadd" || $members_titles_criteria->CurrentAction == "gridedit") {
		$members_titles_criteria_list->RowIndex = '$rowindex$';
		$members_titles_criteria_list->LoadDefaultValues();

		// Set row properties
		$members_titles_criteria->ResetAttrs();
		$members_titles_criteria->RowAttrs = array_merge($members_titles_criteria->RowAttrs, array('data-rowindex'=>$members_titles_criteria_list->RowIndex, 'id'=>'r0_members_titles_criteria', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($members_titles_criteria->RowAttrs["class"], "ewTemplate");
		$members_titles_criteria->RowType = EW_ROWTYPE_ADD;

		// Render row
		$members_titles_criteria_list->RenderRow();

		// Render list options
		$members_titles_criteria_list->RenderListOptions();
		$members_titles_criteria_list->StartRowCnt = 0;
?>
	<tr<?php echo $members_titles_criteria->RowAttributes() ?>>
<?php

// Render list options (body, left)
$members_titles_criteria_list->ListOptions->Render("body", "left", $members_titles_criteria_list->RowIndex);
?>
	<?php if ($members_titles_criteria->id->Visible) { // id ?>
		<td data-name="id">
<input type="hidden" data-table="members_titles_criteria" data-field="x_id" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_id" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($members_titles_criteria->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->level->Visible) { // level ?>
		<td data-name="level">
<span id="el$rowindex$_members_titles_criteria_level" class="form-group members_titles_criteria_level">
<input type="text" data-table="members_titles_criteria" data-field="x_level" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_level" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_level" size="30" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->level->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->level->EditValue ?>"<?php echo $members_titles_criteria->level->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_level" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_level" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_level" value="<?php echo ew_HtmlEncode($members_titles_criteria->level->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->hours_from->Visible) { // hours_from ?>
		<td data-name="hours_from">
<span id="el$rowindex$_members_titles_criteria_hours_from" class="form-group members_titles_criteria_hours_from">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_from" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_from->EditValue ?>"<?php echo $members_titles_criteria->hours_from->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_hours_from" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_from" value="<?php echo ew_HtmlEncode($members_titles_criteria->hours_from->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->hours_to->Visible) { // hours_to ?>
		<td data-name="hours_to">
<span id="el$rowindex$_members_titles_criteria_hours_to" class="form-group members_titles_criteria_hours_to">
<input type="text" data-table="members_titles_criteria" data-field="x_hours_to" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->hours_to->EditValue ?>"<?php echo $members_titles_criteria->hours_to->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_hours_to" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_hours_to" value="<?php echo ew_HtmlEncode($members_titles_criteria->hours_to->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->title->Visible) { // title ?>
		<td data-name="title">
<span id="el$rowindex$_members_titles_criteria_title" class="form-group members_titles_criteria_title">
<input type="text" data-table="members_titles_criteria" data-field="x_title" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_title" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_title" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title->EditValue ?>"<?php echo $members_titles_criteria->title->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_title" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_title" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_title" value="<?php echo ew_HtmlEncode($members_titles_criteria->title->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($members_titles_criteria->title_en->Visible) { // title_en ?>
		<td data-name="title_en">
<span id="el$rowindex$_members_titles_criteria_title_en" class="form-group members_titles_criteria_title_en">
<input type="text" data-table="members_titles_criteria" data-field="x_title_en" name="x<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" id="x<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" placeholder="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->getPlaceHolder()) ?>" value="<?php echo $members_titles_criteria->title_en->EditValue ?>"<?php echo $members_titles_criteria->title_en->EditAttributes() ?>>
</span>
<input type="hidden" data-table="members_titles_criteria" data-field="x_title_en" name="o<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" id="o<?php echo $members_titles_criteria_list->RowIndex ?>_title_en" value="<?php echo ew_HtmlEncode($members_titles_criteria->title_en->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$members_titles_criteria_list->ListOptions->Render("body", "right", $members_titles_criteria_list->RowCnt);
?>
<script type="text/javascript">
fmembers_titles_criterialist.UpdateOpts(<?php echo $members_titles_criteria_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($members_titles_criteria->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $members_titles_criteria_list->FormKeyCountName ?>" id="<?php echo $members_titles_criteria_list->FormKeyCountName ?>" value="<?php echo $members_titles_criteria_list->KeyCount ?>">
<?php echo $members_titles_criteria_list->MultiSelectKey ?>
<?php } ?>
<?php if ($members_titles_criteria->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $members_titles_criteria_list->FormKeyCountName ?>" id="<?php echo $members_titles_criteria_list->FormKeyCountName ?>" value="<?php echo $members_titles_criteria_list->KeyCount ?>">
<?php echo $members_titles_criteria_list->MultiSelectKey ?>
<?php } ?>
<?php if ($members_titles_criteria->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($members_titles_criteria_list->Recordset)
	$members_titles_criteria_list->Recordset->Close();
?>
<?php if ($members_titles_criteria->Export == "") { ?>
<div class="box-footer ewGridLowerPanel">
<?php if ($members_titles_criteria->CurrentAction <> "gridadd" && $members_titles_criteria->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($members_titles_criteria_list->Pager)) $members_titles_criteria_list->Pager = new cPrevNextPager($members_titles_criteria_list->StartRec, $members_titles_criteria_list->DisplayRecs, $members_titles_criteria_list->TotalRecs, $members_titles_criteria_list->AutoHidePager) ?>
<?php if ($members_titles_criteria_list->Pager->RecordCount > 0 && $members_titles_criteria_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($members_titles_criteria_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($members_titles_criteria_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $members_titles_criteria_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($members_titles_criteria_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($members_titles_criteria_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $members_titles_criteria_list->PageUrl() ?>start=<?php echo $members_titles_criteria_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $members_titles_criteria_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($members_titles_criteria_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($members_titles_criteria_list->TotalRecs == 0 && $members_titles_criteria->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($members_titles_criteria_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($members_titles_criteria->Export == "") { ?>
<script type="text/javascript">
fmembers_titles_criterialistsrch.FilterList = <?php echo $members_titles_criteria_list->GetFilterList() ?>;
fmembers_titles_criterialistsrch.Init();
fmembers_titles_criterialist.Init();
</script>
<?php } ?>
<?php
$members_titles_criteria_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($members_titles_criteria->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$members_titles_criteria_list->Page_Terminate();
?>
