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

$activities_view = NULL; // Initialize page object first

class cactivities_view extends cactivities {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'activities';

	// Page object name
	var $PageObjName = 'activities_view';

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
		$KeyUrl = "";
		if (@$_GET["activity_id"] <> "") {
			$this->RecKey["activity_id"] = $_GET["activity_id"];
			$KeyUrl .= "&amp;activity_id=" . urlencode($this->RecKey["activity_id"]);
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
				$this->Page_Terminate(ew_GetUrl("activitieslist.php"));
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
		if (@$_GET["activity_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= $_GET["activity_id"];
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
		$this->activity_id->SetVisibility();
		$this->activity_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->activity_name_ar->SetVisibility();
		$this->activity_name_en->SetVisibility();
		$this->activity_start_date->SetVisibility();
		$this->activity_end_date->SetVisibility();
		$this->activity_time_ar->SetVisibility();
		$this->activity_time_en->SetVisibility();
		$this->activity_description_ar->SetVisibility();
		$this->activity_description_en->SetVisibility();
		$this->activity_persons->SetVisibility();
		$this->activity_hours->SetVisibility();
		$this->activity_city->SetVisibility();
		$this->activity_location_ar->SetVisibility();
		$this->activity_location_en->SetVisibility();
		$this->activity_location_map->SetVisibility();
		$this->activity_image->SetVisibility();
		$this->activity_organizer_ar->SetVisibility();
		$this->activity_organizer_en->SetVisibility();
		$this->activity_category_ar->SetVisibility();
		$this->activity_category_en->SetVisibility();
		$this->activity_type->SetVisibility();
		$this->activity_gender_target->SetVisibility();
		$this->activity_terms_and_conditions_ar->SetVisibility();
		$this->activity_terms_and_conditions_en->SetVisibility();
		$this->activity_active->SetVisibility();
		$this->leader_username->SetVisibility();

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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Show as modal
					$row["modal"] = "1";
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "activitiesview.php")
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
	var $registered_users_Count;
	var $Recordset;

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
			if (@$_GET["activity_id"] <> "") {
				$this->activity_id->setQueryStringValue($_GET["activity_id"]);
				$this->RecKey["activity_id"] = $this->activity_id->QueryStringValue;
			} elseif (@$_POST["activity_id"] <> "") {
				$this->activity_id->setFormValue($_POST["activity_id"]);
				$this->RecKey["activity_id"] = $this->activity_id->FormValue;
			} else {
				$sReturnUrl = "activitieslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "activitieslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "activitieslist.php"; // Not page request, return to list
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

		// Set up detail parameters
		$this->SetupDetailParms();
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
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_registered_users"
		$item = &$option->Add("detail_registered_users");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("registered_users", "TblCaption");
		$body .= str_replace("%c", $this->registered_users_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("registered_userslist.php?" . EW_TABLE_SHOW_MASTER . "=activities&fk_activity_id=" . urlencode(strval($this->activity_id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["registered_users_grid"] && $GLOBALS["registered_users_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'registered_users')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=registered_users")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "registered_users";
		}
		if ($GLOBALS["registered_users_grid"] && $GLOBALS["registered_users_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'registered_users')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=registered_users")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "registered_users";
		}
		if ($GLOBALS["registered_users_grid"] && $GLOBALS["registered_users_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'registered_users')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=registered_users")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "registered_users";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'registered_users');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "registered_users";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
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
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		// activity_id
		// activity_name_ar
		// activity_name_en
		// activity_start_date
		// activity_end_date
		// activity_time_ar
		// activity_time_en
		// activity_description_ar
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

			// activity_name_en
			$this->activity_name_en->LinkCustomAttributes = "";
			$this->activity_name_en->HrefValue = "";
			$this->activity_name_en->TooltipValue = "";

			// activity_start_date
			$this->activity_start_date->LinkCustomAttributes = "";
			$this->activity_start_date->HrefValue = "";
			$this->activity_start_date->TooltipValue = "";

			// activity_end_date
			$this->activity_end_date->LinkCustomAttributes = "";
			$this->activity_end_date->HrefValue = "";
			$this->activity_end_date->TooltipValue = "";

			// activity_time_ar
			$this->activity_time_ar->LinkCustomAttributes = "";
			$this->activity_time_ar->HrefValue = "";
			$this->activity_time_ar->TooltipValue = "";

			// activity_time_en
			$this->activity_time_en->LinkCustomAttributes = "";
			$this->activity_time_en->HrefValue = "";
			$this->activity_time_en->TooltipValue = "";

			// activity_description_ar
			$this->activity_description_ar->LinkCustomAttributes = "";
			$this->activity_description_ar->HrefValue = "";
			$this->activity_description_ar->TooltipValue = "";

			// activity_description_en
			$this->activity_description_en->LinkCustomAttributes = "";
			$this->activity_description_en->HrefValue = "";
			$this->activity_description_en->TooltipValue = "";

			// activity_persons
			$this->activity_persons->LinkCustomAttributes = "";
			$this->activity_persons->HrefValue = "";
			$this->activity_persons->TooltipValue = "";

			// activity_hours
			$this->activity_hours->LinkCustomAttributes = "";
			$this->activity_hours->HrefValue = "";
			$this->activity_hours->TooltipValue = "";

			// activity_city
			$this->activity_city->LinkCustomAttributes = "";
			$this->activity_city->HrefValue = "";
			$this->activity_city->TooltipValue = "";

			// activity_location_ar
			$this->activity_location_ar->LinkCustomAttributes = "";
			$this->activity_location_ar->HrefValue = "";
			$this->activity_location_ar->TooltipValue = "";

			// activity_location_en
			$this->activity_location_en->LinkCustomAttributes = "";
			$this->activity_location_en->HrefValue = "";
			$this->activity_location_en->TooltipValue = "";

			// activity_location_map
			$this->activity_location_map->LinkCustomAttributes = "";
			$this->activity_location_map->HrefValue = "";
			$this->activity_location_map->TooltipValue = "";

			// activity_image
			$this->activity_image->LinkCustomAttributes = "";
			$this->activity_image->UploadPath = "../images";
			if (!ew_Empty($this->activity_image->Upload->DbValue)) {
				$this->activity_image->HrefValue = ew_GetFileUploadUrl($this->activity_image, $this->activity_image->Upload->DbValue); // Add prefix/suffix
				$this->activity_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->activity_image->HrefValue = ew_FullUrl($this->activity_image->HrefValue, "href");
			} else {
				$this->activity_image->HrefValue = "";
			}
			$this->activity_image->HrefValue2 = $this->activity_image->UploadPath . $this->activity_image->Upload->DbValue;
			$this->activity_image->TooltipValue = "";
			if ($this->activity_image->UseColorbox) {
				if (ew_Empty($this->activity_image->TooltipValue))
					$this->activity_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->activity_image->LinkAttrs["data-rel"] = "activities_x_activity_image";
				ew_AppendClass($this->activity_image->LinkAttrs["class"], "ewLightbox");
			}

			// activity_organizer_ar
			$this->activity_organizer_ar->LinkCustomAttributes = "";
			$this->activity_organizer_ar->HrefValue = "";
			$this->activity_organizer_ar->TooltipValue = "";

			// activity_organizer_en
			$this->activity_organizer_en->LinkCustomAttributes = "";
			$this->activity_organizer_en->HrefValue = "";
			$this->activity_organizer_en->TooltipValue = "";

			// activity_category_ar
			$this->activity_category_ar->LinkCustomAttributes = "";
			$this->activity_category_ar->HrefValue = "";
			$this->activity_category_ar->TooltipValue = "";

			// activity_category_en
			$this->activity_category_en->LinkCustomAttributes = "";
			$this->activity_category_en->HrefValue = "";
			$this->activity_category_en->TooltipValue = "";

			// activity_type
			$this->activity_type->LinkCustomAttributes = "";
			$this->activity_type->HrefValue = "";
			$this->activity_type->TooltipValue = "";

			// activity_gender_target
			$this->activity_gender_target->LinkCustomAttributes = "";
			$this->activity_gender_target->HrefValue = "";
			$this->activity_gender_target->TooltipValue = "";

			// activity_terms_and_conditions_ar
			$this->activity_terms_and_conditions_ar->LinkCustomAttributes = "";
			$this->activity_terms_and_conditions_ar->HrefValue = "";
			$this->activity_terms_and_conditions_ar->TooltipValue = "";

			// activity_terms_and_conditions_en
			$this->activity_terms_and_conditions_en->LinkCustomAttributes = "";
			$this->activity_terms_and_conditions_en->HrefValue = "";
			$this->activity_terms_and_conditions_en->TooltipValue = "";

			// activity_active
			$this->activity_active->LinkCustomAttributes = "";
			$this->activity_active->HrefValue = "";
			$this->activity_active->TooltipValue = "";

			// leader_username
			$this->leader_username->LinkCustomAttributes = "";
			$this->leader_username->HrefValue = "";
			$this->leader_username->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_activities\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_activities',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.factivitiesview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (registered_users)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("registered_users", explode(",", $this->getCurrentDetailTable()))) {
			global $registered_users;
			if (!isset($registered_users)) $registered_users = new cregistered_users;
			$rsdetail = $registered_users->LoadRs($registered_users->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$oldtbl = $Doc->Table;
					$Doc->Table = $registered_users;
					$registered_users->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
					$Doc->Table = $oldtbl;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
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

	// Set up detail parms based on QueryString
	function SetupDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("registered_users", $DetailTblVar)) {
				if (!isset($GLOBALS["registered_users_grid"]))
					$GLOBALS["registered_users_grid"] = new cregistered_users_grid;
				if ($GLOBALS["registered_users_grid"]->DetailView) {
					$GLOBALS["registered_users_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["registered_users_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["registered_users_grid"]->setStartRecordNumber(1);
					$GLOBALS["registered_users_grid"]->activity_id->FldIsDetailKey = TRUE;
					$GLOBALS["registered_users_grid"]->activity_id->CurrentValue = $this->activity_id->CurrentValue;
					$GLOBALS["registered_users_grid"]->activity_id->setSessionValue($GLOBALS["registered_users_grid"]->activity_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("activitieslist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($activities_view)) $activities_view = new cactivities_view();

// Page init
$activities_view->Page_Init();

// Page main
$activities_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$activities_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($activities->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = factivitiesview = new ew_Form("factivitiesview", "view");

// Form_CustomValidate event
factivitiesview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
factivitiesview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
factivitiesview.Lists["x_activity_city"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesview.Lists["x_activity_city"].Options = <?php echo json_encode($activities_view->activity_city->Options()) ?>;
factivitiesview.Lists["x_activity_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesview.Lists["x_activity_type"].Options = <?php echo json_encode($activities_view->activity_type->Options()) ?>;
factivitiesview.Lists["x_activity_gender_target"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesview.Lists["x_activity_gender_target"].Options = <?php echo json_encode($activities_view->activity_gender_target->Options()) ?>;
factivitiesview.Lists["x_activity_active"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
factivitiesview.Lists["x_activity_active"].Options = <?php echo json_encode($activities_view->activity_active->Options()) ?>;
factivitiesview.Lists["x_leader_username"] = {"LinkField":"x_user_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_full_name_ar","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"users"};
factivitiesview.Lists["x_leader_username"].Data = "<?php echo $activities_view->leader_username->LookupFilterQuery(FALSE, "view") ?>";
factivitiesview.AutoSuggests["x_leader_username"] = <?php echo json_encode(array("data" => "ajax=autosuggest&" . $activities_view->leader_username->LookupFilterQuery(TRUE, "view"))) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($activities->Export == "") { ?>
<div class="ewToolbar">
<?php $activities_view->ExportOptions->Render("body") ?>
<?php
	foreach ($activities_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $activities_view->ShowPageHeader(); ?>
<?php
$activities_view->ShowMessage();
?>
<form name="factivitiesview" id="factivitiesview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($activities_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $activities_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="activities">
<input type="hidden" name="modal" value="<?php echo intval($activities_view->IsModal) ?>">
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($activities->activity_id->Visible) { // activity_id ?>
	<tr id="r_activity_id">
		<td class="col-sm-2"><span id="elh_activities_activity_id"><?php echo $activities->activity_id->FldCaption() ?></span></td>
		<td data-name="activity_id"<?php echo $activities->activity_id->CellAttributes() ?>>
<span id="el_activities_activity_id">
<span<?php echo $activities->activity_id->ViewAttributes() ?>>
<?php echo $activities->activity_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_name_ar->Visible) { // activity_name_ar ?>
	<tr id="r_activity_name_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_name_ar"><?php echo $activities->activity_name_ar->FldCaption() ?></span></td>
		<td data-name="activity_name_ar"<?php echo $activities->activity_name_ar->CellAttributes() ?>>
<span id="el_activities_activity_name_ar">
<span<?php echo $activities->activity_name_ar->ViewAttributes() ?>>
<?php echo $activities->activity_name_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_name_en->Visible) { // activity_name_en ?>
	<tr id="r_activity_name_en">
		<td class="col-sm-2"><span id="elh_activities_activity_name_en"><?php echo $activities->activity_name_en->FldCaption() ?></span></td>
		<td data-name="activity_name_en"<?php echo $activities->activity_name_en->CellAttributes() ?>>
<span id="el_activities_activity_name_en">
<span<?php echo $activities->activity_name_en->ViewAttributes() ?>>
<?php echo $activities->activity_name_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_start_date->Visible) { // activity_start_date ?>
	<tr id="r_activity_start_date">
		<td class="col-sm-2"><span id="elh_activities_activity_start_date"><?php echo $activities->activity_start_date->FldCaption() ?></span></td>
		<td data-name="activity_start_date"<?php echo $activities->activity_start_date->CellAttributes() ?>>
<span id="el_activities_activity_start_date">
<span<?php echo $activities->activity_start_date->ViewAttributes() ?>>
<?php echo $activities->activity_start_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_end_date->Visible) { // activity_end_date ?>
	<tr id="r_activity_end_date">
		<td class="col-sm-2"><span id="elh_activities_activity_end_date"><?php echo $activities->activity_end_date->FldCaption() ?></span></td>
		<td data-name="activity_end_date"<?php echo $activities->activity_end_date->CellAttributes() ?>>
<span id="el_activities_activity_end_date">
<span<?php echo $activities->activity_end_date->ViewAttributes() ?>>
<?php echo $activities->activity_end_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_time_ar->Visible) { // activity_time_ar ?>
	<tr id="r_activity_time_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_time_ar"><?php echo $activities->activity_time_ar->FldCaption() ?></span></td>
		<td data-name="activity_time_ar"<?php echo $activities->activity_time_ar->CellAttributes() ?>>
<span id="el_activities_activity_time_ar">
<span<?php echo $activities->activity_time_ar->ViewAttributes() ?>>
<?php echo $activities->activity_time_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_time_en->Visible) { // activity_time_en ?>
	<tr id="r_activity_time_en">
		<td class="col-sm-2"><span id="elh_activities_activity_time_en"><?php echo $activities->activity_time_en->FldCaption() ?></span></td>
		<td data-name="activity_time_en"<?php echo $activities->activity_time_en->CellAttributes() ?>>
<span id="el_activities_activity_time_en">
<span<?php echo $activities->activity_time_en->ViewAttributes() ?>>
<?php echo $activities->activity_time_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_description_ar->Visible) { // activity_description_ar ?>
	<tr id="r_activity_description_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_description_ar"><?php echo $activities->activity_description_ar->FldCaption() ?></span></td>
		<td data-name="activity_description_ar"<?php echo $activities->activity_description_ar->CellAttributes() ?>>
<span id="el_activities_activity_description_ar">
<span<?php echo $activities->activity_description_ar->ViewAttributes() ?>>
<?php echo $activities->activity_description_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_description_en->Visible) { // activity_description_en ?>
	<tr id="r_activity_description_en">
		<td class="col-sm-2"><span id="elh_activities_activity_description_en"><?php echo $activities->activity_description_en->FldCaption() ?></span></td>
		<td data-name="activity_description_en"<?php echo $activities->activity_description_en->CellAttributes() ?>>
<span id="el_activities_activity_description_en">
<span<?php echo $activities->activity_description_en->ViewAttributes() ?>>
<?php echo $activities->activity_description_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_persons->Visible) { // activity_persons ?>
	<tr id="r_activity_persons">
		<td class="col-sm-2"><span id="elh_activities_activity_persons"><?php echo $activities->activity_persons->FldCaption() ?></span></td>
		<td data-name="activity_persons"<?php echo $activities->activity_persons->CellAttributes() ?>>
<span id="el_activities_activity_persons">
<span<?php echo $activities->activity_persons->ViewAttributes() ?>>
<?php echo $activities->activity_persons->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_hours->Visible) { // activity_hours ?>
	<tr id="r_activity_hours">
		<td class="col-sm-2"><span id="elh_activities_activity_hours"><?php echo $activities->activity_hours->FldCaption() ?></span></td>
		<td data-name="activity_hours"<?php echo $activities->activity_hours->CellAttributes() ?>>
<span id="el_activities_activity_hours">
<span<?php echo $activities->activity_hours->ViewAttributes() ?>>
<?php echo $activities->activity_hours->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_city->Visible) { // activity_city ?>
	<tr id="r_activity_city">
		<td class="col-sm-2"><span id="elh_activities_activity_city"><?php echo $activities->activity_city->FldCaption() ?></span></td>
		<td data-name="activity_city"<?php echo $activities->activity_city->CellAttributes() ?>>
<span id="el_activities_activity_city">
<span<?php echo $activities->activity_city->ViewAttributes() ?>>
<?php echo $activities->activity_city->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_location_ar->Visible) { // activity_location_ar ?>
	<tr id="r_activity_location_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_location_ar"><?php echo $activities->activity_location_ar->FldCaption() ?></span></td>
		<td data-name="activity_location_ar"<?php echo $activities->activity_location_ar->CellAttributes() ?>>
<span id="el_activities_activity_location_ar">
<span<?php echo $activities->activity_location_ar->ViewAttributes() ?>>
<?php echo $activities->activity_location_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_location_en->Visible) { // activity_location_en ?>
	<tr id="r_activity_location_en">
		<td class="col-sm-2"><span id="elh_activities_activity_location_en"><?php echo $activities->activity_location_en->FldCaption() ?></span></td>
		<td data-name="activity_location_en"<?php echo $activities->activity_location_en->CellAttributes() ?>>
<span id="el_activities_activity_location_en">
<span<?php echo $activities->activity_location_en->ViewAttributes() ?>>
<?php echo $activities->activity_location_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_location_map->Visible) { // activity_location_map ?>
	<tr id="r_activity_location_map">
		<td class="col-sm-2"><span id="elh_activities_activity_location_map"><?php echo $activities->activity_location_map->FldCaption() ?></span></td>
		<td data-name="activity_location_map"<?php echo $activities->activity_location_map->CellAttributes() ?>>
<span id="el_activities_activity_location_map">
<span<?php echo $activities->activity_location_map->ViewAttributes() ?>>
<?php echo $activities->activity_location_map->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_image->Visible) { // activity_image ?>
	<tr id="r_activity_image">
		<td class="col-sm-2"><span id="elh_activities_activity_image"><?php echo $activities->activity_image->FldCaption() ?></span></td>
		<td data-name="activity_image"<?php echo $activities->activity_image->CellAttributes() ?>>
<span id="el_activities_activity_image">
<span>
<?php echo ew_GetFileViewTag($activities->activity_image, $activities->activity_image->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_organizer_ar->Visible) { // activity_organizer_ar ?>
	<tr id="r_activity_organizer_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_organizer_ar"><?php echo $activities->activity_organizer_ar->FldCaption() ?></span></td>
		<td data-name="activity_organizer_ar"<?php echo $activities->activity_organizer_ar->CellAttributes() ?>>
<span id="el_activities_activity_organizer_ar">
<span<?php echo $activities->activity_organizer_ar->ViewAttributes() ?>>
<?php echo $activities->activity_organizer_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_organizer_en->Visible) { // activity_organizer_en ?>
	<tr id="r_activity_organizer_en">
		<td class="col-sm-2"><span id="elh_activities_activity_organizer_en"><?php echo $activities->activity_organizer_en->FldCaption() ?></span></td>
		<td data-name="activity_organizer_en"<?php echo $activities->activity_organizer_en->CellAttributes() ?>>
<span id="el_activities_activity_organizer_en">
<span<?php echo $activities->activity_organizer_en->ViewAttributes() ?>>
<?php echo $activities->activity_organizer_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_category_ar->Visible) { // activity_category_ar ?>
	<tr id="r_activity_category_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_category_ar"><?php echo $activities->activity_category_ar->FldCaption() ?></span></td>
		<td data-name="activity_category_ar"<?php echo $activities->activity_category_ar->CellAttributes() ?>>
<span id="el_activities_activity_category_ar">
<span<?php echo $activities->activity_category_ar->ViewAttributes() ?>>
<?php echo $activities->activity_category_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_category_en->Visible) { // activity_category_en ?>
	<tr id="r_activity_category_en">
		<td class="col-sm-2"><span id="elh_activities_activity_category_en"><?php echo $activities->activity_category_en->FldCaption() ?></span></td>
		<td data-name="activity_category_en"<?php echo $activities->activity_category_en->CellAttributes() ?>>
<span id="el_activities_activity_category_en">
<span<?php echo $activities->activity_category_en->ViewAttributes() ?>>
<?php echo $activities->activity_category_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_type->Visible) { // activity_type ?>
	<tr id="r_activity_type">
		<td class="col-sm-2"><span id="elh_activities_activity_type"><?php echo $activities->activity_type->FldCaption() ?></span></td>
		<td data-name="activity_type"<?php echo $activities->activity_type->CellAttributes() ?>>
<span id="el_activities_activity_type">
<span<?php echo $activities->activity_type->ViewAttributes() ?>>
<?php echo $activities->activity_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_gender_target->Visible) { // activity_gender_target ?>
	<tr id="r_activity_gender_target">
		<td class="col-sm-2"><span id="elh_activities_activity_gender_target"><?php echo $activities->activity_gender_target->FldCaption() ?></span></td>
		<td data-name="activity_gender_target"<?php echo $activities->activity_gender_target->CellAttributes() ?>>
<span id="el_activities_activity_gender_target">
<span<?php echo $activities->activity_gender_target->ViewAttributes() ?>>
<?php echo $activities->activity_gender_target->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_terms_and_conditions_ar->Visible) { // activity_terms_and_conditions_ar ?>
	<tr id="r_activity_terms_and_conditions_ar">
		<td class="col-sm-2"><span id="elh_activities_activity_terms_and_conditions_ar"><?php echo $activities->activity_terms_and_conditions_ar->FldCaption() ?></span></td>
		<td data-name="activity_terms_and_conditions_ar"<?php echo $activities->activity_terms_and_conditions_ar->CellAttributes() ?>>
<span id="el_activities_activity_terms_and_conditions_ar">
<span<?php echo $activities->activity_terms_and_conditions_ar->ViewAttributes() ?>>
<?php echo $activities->activity_terms_and_conditions_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_terms_and_conditions_en->Visible) { // activity_terms_and_conditions_en ?>
	<tr id="r_activity_terms_and_conditions_en">
		<td class="col-sm-2"><span id="elh_activities_activity_terms_and_conditions_en"><?php echo $activities->activity_terms_and_conditions_en->FldCaption() ?></span></td>
		<td data-name="activity_terms_and_conditions_en"<?php echo $activities->activity_terms_and_conditions_en->CellAttributes() ?>>
<span id="el_activities_activity_terms_and_conditions_en">
<span<?php echo $activities->activity_terms_and_conditions_en->ViewAttributes() ?>>
<?php echo $activities->activity_terms_and_conditions_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->activity_active->Visible) { // activity_active ?>
	<tr id="r_activity_active">
		<td class="col-sm-2"><span id="elh_activities_activity_active"><?php echo $activities->activity_active->FldCaption() ?></span></td>
		<td data-name="activity_active"<?php echo $activities->activity_active->CellAttributes() ?>>
<span id="el_activities_activity_active">
<span<?php echo $activities->activity_active->ViewAttributes() ?>>
<?php echo $activities->activity_active->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($activities->leader_username->Visible) { // leader_username ?>
	<tr id="r_leader_username">
		<td class="col-sm-2"><span id="elh_activities_leader_username"><?php echo $activities->leader_username->FldCaption() ?></span></td>
		<td data-name="leader_username"<?php echo $activities->leader_username->CellAttributes() ?>>
<span id="el_activities_leader_username">
<span<?php echo $activities->leader_username->ViewAttributes() ?>>
<?php echo $activities->leader_username->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("registered_users", explode(",", $activities->getCurrentDetailTable())) && $registered_users->DetailView) {
?>
<?php if ($activities->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("registered_users", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "registered_usersgrid.php" ?>
<?php } ?>
</form>
<?php if ($activities->Export == "") { ?>
<script type="text/javascript">
factivitiesview.Init();
</script>
<?php } ?>
<?php
$activities_view->ShowPageFooter();
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
$activities_view->Page_Terminate();
?>
