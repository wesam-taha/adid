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

$users_view = NULL; // Initialize page object first

class cusers_view extends cusers {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = '{6A6E587E-A14E-4B9E-9243-D8812A8F089D}';

	// Table name
	var $TableName = 'users';

	// Page object name
	var $PageObjName = 'users_view';

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
		$KeyUrl = "";
		if (@$_GET["user_id"] <> "") {
			$this->RecKey["user_id"] = $_GET["user_id"];
			$KeyUrl .= "&amp;user_id=" . urlencode($this->RecKey["user_id"]);
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
				$this->Page_Terminate(ew_GetUrl("userslist.php"));
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
		if (@$_GET["user_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= $_GET["user_id"];
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
		$this->user_id->SetVisibility();
		$this->user_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->group_id->SetVisibility();
		$this->full_name_ar->SetVisibility();
		$this->full_name_en->SetVisibility();
		$this->date_of_birth->SetVisibility();
		$this->personal_photo->SetVisibility();
		$this->gender->SetVisibility();
		$this->blood_type->SetVisibility();
		$this->driving_licence->SetVisibility();
		$this->job->SetVisibility();
		$this->volunteering_type->SetVisibility();
		$this->marital_status->SetVisibility();
		$this->nationality_type->SetVisibility();
		$this->nationality->SetVisibility();
		$this->unid->SetVisibility();
		$this->visa_expiry_date->SetVisibility();
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
		$this->qualifications->SetVisibility();
		$this->cv->SetVisibility();
		$this->home_phone->SetVisibility();
		$this->work_phone->SetVisibility();
		$this->mobile_phone->SetVisibility();
		$this->fax->SetVisibility();
		$this->pobbox->SetVisibility();
		$this->_email->SetVisibility();
		$this->password->SetVisibility();
		$this->total_voluntary_hours->SetVisibility();
		$this->overall_evaluation->SetVisibility();
		$this->admin_approval->SetVisibility();
		$this->lastUpdatedBy->SetVisibility();
		$this->admin_comment->SetVisibility();
		$this->security_approval->SetVisibility();
		$this->approvedBy->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Show as modal
					$row["modal"] = "1";
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "usersview.php")
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
	var $user_attachments_Count;
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
			if (@$_GET["user_id"] <> "") {
				$this->user_id->setQueryStringValue($_GET["user_id"]);
				$this->RecKey["user_id"] = $this->user_id->QueryStringValue;
			} elseif (@$_POST["user_id"] <> "") {
				$this->user_id->setFormValue($_POST["user_id"]);
				$this->RecKey["user_id"] = $this->user_id->FormValue;
			} else {
				$sReturnUrl = "userslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "userslist.php"; // No matching record, return to list
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array_keys($EW_EXPORT))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "userslist.php"; // Not page request, return to list
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

		// "detail_user_attachments"
		$item = &$option->Add("detail_user_attachments");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("user_attachments", "TblCaption");
		$body .= str_replace("%c", $this->user_attachments_Count, $Language->Phrase("DetailCount"));
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("user_attachmentslist.php?" . EW_TABLE_SHOW_MASTER . "=users&fk_user_id=" . urlencode(strval($this->user_id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["user_attachments_grid"] && $GLOBALS["user_attachments_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'user_attachments')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "user_attachments";
		}
		if ($GLOBALS["user_attachments_grid"] && $GLOBALS["user_attachments_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'user_attachments')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "user_attachments";
		}
		if ($GLOBALS["user_attachments_grid"] && $GLOBALS["user_attachments_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'user_attachments')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=user_attachments")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "user_attachments";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'user_attachments');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "user_attachments";
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
		// security_owner

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

		// lastUpdatedBy
		if (strval($this->lastUpdatedBy->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->lastUpdatedBy->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `management`";
		$sWhereWrk = "";
		$this->lastUpdatedBy->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->lastUpdatedBy, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->lastUpdatedBy->ViewValue = $this->lastUpdatedBy->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->lastUpdatedBy->ViewValue = $this->lastUpdatedBy->CurrentValue;
			}
		} else {
			$this->lastUpdatedBy->ViewValue = NULL;
		}
		$this->lastUpdatedBy->ViewCustomAttributes = "";

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

		// approvedBy
		if (strval($this->approvedBy->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->approvedBy->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `username` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `management`";
		$sWhereWrk = "";
		$this->approvedBy->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->approvedBy, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->approvedBy->ViewValue = $this->approvedBy->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->approvedBy->ViewValue = $this->approvedBy->CurrentValue;
			}
		} else {
			$this->approvedBy->ViewValue = NULL;
		}
		$this->approvedBy->ViewCustomAttributes = "";

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

			// group_id
			$this->group_id->LinkCustomAttributes = "";
			$this->group_id->HrefValue = "";
			$this->group_id->TooltipValue = "";

			// full_name_ar
			$this->full_name_ar->LinkCustomAttributes = "";
			$this->full_name_ar->HrefValue = "";
			$this->full_name_ar->TooltipValue = "";

			// full_name_en
			$this->full_name_en->LinkCustomAttributes = "";
			$this->full_name_en->HrefValue = "";
			$this->full_name_en->TooltipValue = "";

			// date_of_birth
			$this->date_of_birth->LinkCustomAttributes = "";
			$this->date_of_birth->HrefValue = "";
			$this->date_of_birth->TooltipValue = "";

			// personal_photo
			$this->personal_photo->LinkCustomAttributes = "";
			$this->personal_photo->UploadPath = "../images";
			if (!ew_Empty($this->personal_photo->Upload->DbValue)) {
				$this->personal_photo->HrefValue = ew_GetFileUploadUrl($this->personal_photo, $this->personal_photo->Upload->DbValue); // Add prefix/suffix
				$this->personal_photo->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->personal_photo->HrefValue = ew_FullUrl($this->personal_photo->HrefValue, "href");
			} else {
				$this->personal_photo->HrefValue = "";
			}
			$this->personal_photo->HrefValue2 = $this->personal_photo->UploadPath . $this->personal_photo->Upload->DbValue;
			$this->personal_photo->TooltipValue = "";
			if ($this->personal_photo->UseColorbox) {
				if (ew_Empty($this->personal_photo->TooltipValue))
					$this->personal_photo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->personal_photo->LinkAttrs["data-rel"] = "users_x_personal_photo";
				ew_AppendClass($this->personal_photo->LinkAttrs["class"], "ewLightbox");
			}

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

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

			// marital_status
			$this->marital_status->LinkCustomAttributes = "";
			$this->marital_status->HrefValue = "";
			$this->marital_status->TooltipValue = "";

			// nationality_type
			$this->nationality_type->LinkCustomAttributes = "";
			$this->nationality_type->HrefValue = "";
			$this->nationality_type->TooltipValue = "";

			// nationality
			$this->nationality->LinkCustomAttributes = "";
			$this->nationality->HrefValue = "";
			$this->nationality->TooltipValue = "";

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
				$this->visa_copy->LinkAttrs["data-rel"] = "users_x_visa_copy";
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
				$this->emirates_id_copy->LinkAttrs["data-rel"] = "users_x_emirates_id_copy";
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
				$this->passport_copy->LinkAttrs["data-rel"] = "users_x_passport_copy";
				ew_AppendClass($this->passport_copy->LinkAttrs["class"], "ewLightbox");
			}

			// place_of_work
			$this->place_of_work->LinkCustomAttributes = "";
			$this->place_of_work->HrefValue = "";
			$this->place_of_work->TooltipValue = "";

			// qualifications
			$this->qualifications->LinkCustomAttributes = "";
			$this->qualifications->HrefValue = "";
			$this->qualifications->TooltipValue = "";

			// cv
			$this->cv->LinkCustomAttributes = "";
			$this->cv->HrefValue = "";
			$this->cv->HrefValue2 = $this->cv->UploadPath . $this->cv->Upload->DbValue;
			$this->cv->TooltipValue = "";

			// home_phone
			$this->home_phone->LinkCustomAttributes = "";
			$this->home_phone->HrefValue = "";
			$this->home_phone->TooltipValue = "";

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

			// total_voluntary_hours
			$this->total_voluntary_hours->LinkCustomAttributes = "";
			$this->total_voluntary_hours->HrefValue = "";
			$this->total_voluntary_hours->TooltipValue = "";

			// overall_evaluation
			$this->overall_evaluation->LinkCustomAttributes = "";
			$this->overall_evaluation->HrefValue = "";
			$this->overall_evaluation->TooltipValue = "";

			// admin_approval
			$this->admin_approval->LinkCustomAttributes = "";
			$this->admin_approval->HrefValue = "";
			$this->admin_approval->TooltipValue = "";

			// lastUpdatedBy
			$this->lastUpdatedBy->LinkCustomAttributes = "";
			$this->lastUpdatedBy->HrefValue = "";
			$this->lastUpdatedBy->TooltipValue = "";

			// admin_comment
			$this->admin_comment->LinkCustomAttributes = "";
			$this->admin_comment->HrefValue = "";
			$this->admin_comment->TooltipValue = "";

			// security_approval
			$this->security_approval->LinkCustomAttributes = "";
			$this->security_approval->HrefValue = "";
			$this->security_approval->TooltipValue = "";

			// approvedBy
			$this->approvedBy->LinkCustomAttributes = "";
			$this->approvedBy->HrefValue = "";
			$this->approvedBy->TooltipValue = "";

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
		$item->Body = "<button id=\"emf_users\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_users',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fusersview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export detail records (user_attachments)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("user_attachments", explode(",", $this->getCurrentDetailTable()))) {
			global $user_attachments;
			if (!isset($user_attachments)) $user_attachments = new cuser_attachments;
			$rsdetail = $user_attachments->LoadRs($user_attachments->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$oldtbl = $Doc->Table;
					$Doc->Table = $user_attachments;
					$user_attachments->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
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
			if (in_array("user_attachments", $DetailTblVar)) {
				if (!isset($GLOBALS["user_attachments_grid"]))
					$GLOBALS["user_attachments_grid"] = new cuser_attachments_grid;
				if ($GLOBALS["user_attachments_grid"]->DetailView) {
					$GLOBALS["user_attachments_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["user_attachments_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["user_attachments_grid"]->setStartRecordNumber(1);
					$GLOBALS["user_attachments_grid"]->_userid->FldIsDetailKey = TRUE;
					$GLOBALS["user_attachments_grid"]->_userid->CurrentValue = $this->user_id->CurrentValue;
					$GLOBALS["user_attachments_grid"]->_userid->setSessionValue($GLOBALS["user_attachments_grid"]->_userid->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("userslist.php"), "", $this->TableVar, TRUE);
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
if (!isset($users_view)) $users_view = new cusers_view();

// Page init
$users_view->Page_Init();

// Page main
$users_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$users_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fusersview = new ew_Form("fusersview", "view");

// Form_CustomValidate event
fusersview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fusersview.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Multi-Page
fusersview.MultiPage = new ew_MultiPage("fusersview");

// Dynamic selection lists
fusersview.Lists["x_group_id[]"] = {"LinkField":"x_institution_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_institutes_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"institutions"};
fusersview.Lists["x_group_id[]"].Data = "<?php echo $users_view->group_id->LookupFilterQuery(FALSE, "view") ?>";
fusersview.Lists["x_gender"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_gender"].Options = <?php echo json_encode($users_view->gender->Options()) ?>;
fusersview.Lists["x_blood_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_blood_type"].Options = <?php echo json_encode($users_view->blood_type->Options()) ?>;
fusersview.Lists["x_driving_licence"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_driving_licence"].Options = <?php echo json_encode($users_view->driving_licence->Options()) ?>;
fusersview.Lists["x_job"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_job"].Options = <?php echo json_encode($users_view->job->Options()) ?>;
fusersview.Lists["x_volunteering_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_volunteering_type"].Options = <?php echo json_encode($users_view->volunteering_type->Options()) ?>;
fusersview.Lists["x_marital_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_marital_status"].Options = <?php echo json_encode($users_view->marital_status->Options()) ?>;
fusersview.Lists["x_nationality_type"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_nationality_type"].Options = <?php echo json_encode($users_view->nationality_type->Options()) ?>;
fusersview.Lists["x_current_emirate"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_current_emirate"].Options = <?php echo json_encode($users_view->current_emirate->Options()) ?>;
fusersview.Lists["x_admin_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_admin_approval"].Options = <?php echo json_encode($users_view->admin_approval->Options()) ?>;
fusersview.Lists["x_lastUpdatedBy"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_username","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"management"};
fusersview.Lists["x_lastUpdatedBy"].Data = "<?php echo $users_view->lastUpdatedBy->LookupFilterQuery(FALSE, "view") ?>";
fusersview.Lists["x_security_approval"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fusersview.Lists["x_security_approval"].Options = <?php echo json_encode($users_view->security_approval->Options()) ?>;
fusersview.Lists["x_approvedBy"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_username","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"management"};
fusersview.Lists["x_approvedBy"].Data = "<?php echo $users_view->approvedBy->LookupFilterQuery(FALSE, "view") ?>";

// Form object for search
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
<?php $users_view->ExportOptions->Render("body") ?>
<?php
	foreach ($users_view->OtherOptions as &$option)
		$option->Render("body");
?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $users_view->ShowPageHeader(); ?>
<?php
$users_view->ShowMessage();
?>
<form name="fusersview" id="fusersview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($users_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $users_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="users">
<input type="hidden" name="modal" value="<?php echo intval($users_view->IsModal) ?>">
<?php if ($users->Export == "") { ?>
<div class="ewMultiPage">
<div class="nav-tabs-custom" id="users_view">
	<ul class="nav<?php echo $users_view->MultiPages->NavStyle() ?>">
		<li<?php echo $users_view->MultiPages->TabStyle("1") ?>><a href="#tab_users1" data-toggle="tab"><?php echo $users->PageCaption(1) ?></a></li>
		<li<?php echo $users_view->MultiPages->TabStyle("2") ?>><a href="#tab_users2" data-toggle="tab"><?php echo $users->PageCaption(2) ?></a></li>
		<li<?php echo $users_view->MultiPages->TabStyle("3") ?>><a href="#tab_users3" data-toggle="tab"><?php echo $users->PageCaption(3) ?></a></li>
		<li<?php echo $users_view->MultiPages->TabStyle("4") ?>><a href="#tab_users4" data-toggle="tab"><?php echo $users->PageCaption(4) ?></a></li>
		<li<?php echo $users_view->MultiPages->TabStyle("5") ?>><a href="#tab_users5" data-toggle="tab"><?php echo $users->PageCaption(5) ?></a></li>
		<li<?php echo $users_view->MultiPages->TabStyle("6") ?>><a href="#tab_users6" data-toggle="tab"><?php echo $users->PageCaption(6) ?></a></li>
		<li<?php echo $users_view->MultiPages->TabStyle("7") ?>><a href="#tab_users7" data-toggle="tab"><?php echo $users->PageCaption(7) ?></a></li>
	</ul>
	<div class="tab-content">
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("1") ?>" id="tab_users1">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->user_id->Visible) { // user_id ?>
	<tr id="r_user_id">
		<td class="col-sm-2"><span id="elh_users_user_id"><?php echo $users->user_id->FldCaption() ?></span></td>
		<td data-name="user_id"<?php echo $users->user_id->CellAttributes() ?>>
<span id="el_users_user_id" data-page="1">
<span<?php echo $users->user_id->ViewAttributes() ?>>
<?php echo $users->user_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->group_id->Visible) { // group_id ?>
	<tr id="r_group_id">
		<td class="col-sm-2"><span id="elh_users_group_id"><?php echo $users->group_id->FldCaption() ?></span></td>
		<td data-name="group_id"<?php echo $users->group_id->CellAttributes() ?>>
<span id="el_users_group_id" data-page="1">
<span<?php echo $users->group_id->ViewAttributes() ?>>
<?php echo $users->group_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->full_name_ar->Visible) { // full_name_ar ?>
	<tr id="r_full_name_ar">
		<td class="col-sm-2"><span id="elh_users_full_name_ar"><?php echo $users->full_name_ar->FldCaption() ?></span></td>
		<td data-name="full_name_ar"<?php echo $users->full_name_ar->CellAttributes() ?>>
<span id="el_users_full_name_ar" data-page="1">
<span<?php echo $users->full_name_ar->ViewAttributes() ?>>
<?php echo $users->full_name_ar->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->full_name_en->Visible) { // full_name_en ?>
	<tr id="r_full_name_en">
		<td class="col-sm-2"><span id="elh_users_full_name_en"><?php echo $users->full_name_en->FldCaption() ?></span></td>
		<td data-name="full_name_en"<?php echo $users->full_name_en->CellAttributes() ?>>
<span id="el_users_full_name_en" data-page="1">
<span<?php echo $users->full_name_en->ViewAttributes() ?>>
<?php echo $users->full_name_en->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->date_of_birth->Visible) { // date_of_birth ?>
	<tr id="r_date_of_birth">
		<td class="col-sm-2"><span id="elh_users_date_of_birth"><?php echo $users->date_of_birth->FldCaption() ?></span></td>
		<td data-name="date_of_birth"<?php echo $users->date_of_birth->CellAttributes() ?>>
<span id="el_users_date_of_birth" data-page="1">
<span<?php echo $users->date_of_birth->ViewAttributes() ?>>
<?php echo $users->date_of_birth->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->personal_photo->Visible) { // personal_photo ?>
	<tr id="r_personal_photo">
		<td class="col-sm-2"><span id="elh_users_personal_photo"><?php echo $users->personal_photo->FldCaption() ?></span></td>
		<td data-name="personal_photo"<?php echo $users->personal_photo->CellAttributes() ?>>
<span id="el_users_personal_photo" data-page="1">
<span>
<?php echo ew_GetFileViewTag($users->personal_photo, $users->personal_photo->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->gender->Visible) { // gender ?>
	<tr id="r_gender">
		<td class="col-sm-2"><span id="elh_users_gender"><?php echo $users->gender->FldCaption() ?></span></td>
		<td data-name="gender"<?php echo $users->gender->CellAttributes() ?>>
<span id="el_users_gender" data-page="1">
<span<?php echo $users->gender->ViewAttributes() ?>>
<?php echo $users->gender->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->blood_type->Visible) { // blood_type ?>
	<tr id="r_blood_type">
		<td class="col-sm-2"><span id="elh_users_blood_type"><?php echo $users->blood_type->FldCaption() ?></span></td>
		<td data-name="blood_type"<?php echo $users->blood_type->CellAttributes() ?>>
<span id="el_users_blood_type" data-page="1">
<span<?php echo $users->blood_type->ViewAttributes() ?>>
<?php echo $users->blood_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->driving_licence->Visible) { // driving_licence ?>
	<tr id="r_driving_licence">
		<td class="col-sm-2"><span id="elh_users_driving_licence"><?php echo $users->driving_licence->FldCaption() ?></span></td>
		<td data-name="driving_licence"<?php echo $users->driving_licence->CellAttributes() ?>>
<span id="el_users_driving_licence" data-page="1">
<span<?php echo $users->driving_licence->ViewAttributes() ?>>
<?php echo $users->driving_licence->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->job->Visible) { // job ?>
	<tr id="r_job">
		<td class="col-sm-2"><span id="elh_users_job"><?php echo $users->job->FldCaption() ?></span></td>
		<td data-name="job"<?php echo $users->job->CellAttributes() ?>>
<span id="el_users_job" data-page="1">
<span<?php echo $users->job->ViewAttributes() ?>>
<?php echo $users->job->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->volunteering_type->Visible) { // volunteering_type ?>
	<tr id="r_volunteering_type">
		<td class="col-sm-2"><span id="elh_users_volunteering_type"><?php echo $users->volunteering_type->FldCaption() ?></span></td>
		<td data-name="volunteering_type"<?php echo $users->volunteering_type->CellAttributes() ?>>
<span id="el_users_volunteering_type" data-page="1">
<span<?php echo $users->volunteering_type->ViewAttributes() ?>>
<?php echo $users->volunteering_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->marital_status->Visible) { // marital_status ?>
	<tr id="r_marital_status">
		<td class="col-sm-2"><span id="elh_users_marital_status"><?php echo $users->marital_status->FldCaption() ?></span></td>
		<td data-name="marital_status"<?php echo $users->marital_status->CellAttributes() ?>>
<span id="el_users_marital_status" data-page="1">
<span<?php echo $users->marital_status->ViewAttributes() ?>>
<?php echo $users->marital_status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("2") ?>" id="tab_users2">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->nationality_type->Visible) { // nationality_type ?>
	<tr id="r_nationality_type">
		<td class="col-sm-2"><span id="elh_users_nationality_type"><?php echo $users->nationality_type->FldCaption() ?></span></td>
		<td data-name="nationality_type"<?php echo $users->nationality_type->CellAttributes() ?>>
<span id="el_users_nationality_type" data-page="2">
<span<?php echo $users->nationality_type->ViewAttributes() ?>>
<?php echo $users->nationality_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->nationality->Visible) { // nationality ?>
	<tr id="r_nationality">
		<td class="col-sm-2"><span id="elh_users_nationality"><?php echo $users->nationality->FldCaption() ?></span></td>
		<td data-name="nationality"<?php echo $users->nationality->CellAttributes() ?>>
<span id="el_users_nationality" data-page="2">
<span<?php echo $users->nationality->ViewAttributes() ?>>
<?php echo $users->nationality->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->unid->Visible) { // unid ?>
	<tr id="r_unid">
		<td class="col-sm-2"><span id="elh_users_unid"><?php echo $users->unid->FldCaption() ?></span></td>
		<td data-name="unid"<?php echo $users->unid->CellAttributes() ?>>
<span id="el_users_unid" data-page="2">
<span<?php echo $users->unid->ViewAttributes() ?>>
<?php echo $users->unid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->visa_expiry_date->Visible) { // visa_expiry_date ?>
	<tr id="r_visa_expiry_date">
		<td class="col-sm-2"><span id="elh_users_visa_expiry_date"><?php echo $users->visa_expiry_date->FldCaption() ?></span></td>
		<td data-name="visa_expiry_date"<?php echo $users->visa_expiry_date->CellAttributes() ?>>
<span id="el_users_visa_expiry_date" data-page="2">
<span<?php echo $users->visa_expiry_date->ViewAttributes() ?>>
<?php echo $users->visa_expiry_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->visa_copy->Visible) { // visa_copy ?>
	<tr id="r_visa_copy">
		<td class="col-sm-2"><span id="elh_users_visa_copy"><?php echo $users->visa_copy->FldCaption() ?></span></td>
		<td data-name="visa_copy"<?php echo $users->visa_copy->CellAttributes() ?>>
<span id="el_users_visa_copy" data-page="2">
<span>
<?php echo ew_GetFileViewTag($users->visa_copy, $users->visa_copy->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->current_emirate->Visible) { // current_emirate ?>
	<tr id="r_current_emirate">
		<td class="col-sm-2"><span id="elh_users_current_emirate"><?php echo $users->current_emirate->FldCaption() ?></span></td>
		<td data-name="current_emirate"<?php echo $users->current_emirate->CellAttributes() ?>>
<span id="el_users_current_emirate" data-page="2">
<span<?php echo $users->current_emirate->ViewAttributes() ?>>
<?php echo $users->current_emirate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->full_address->Visible) { // full_address ?>
	<tr id="r_full_address">
		<td class="col-sm-2"><span id="elh_users_full_address"><?php echo $users->full_address->FldCaption() ?></span></td>
		<td data-name="full_address"<?php echo $users->full_address->CellAttributes() ?>>
<span id="el_users_full_address" data-page="2">
<span<?php echo $users->full_address->ViewAttributes() ?>>
<?php echo $users->full_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("3") ?>" id="tab_users3">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->emirates_id_number->Visible) { // emirates_id_number ?>
	<tr id="r_emirates_id_number">
		<td class="col-sm-2"><span id="elh_users_emirates_id_number"><?php echo $users->emirates_id_number->FldCaption() ?></span></td>
		<td data-name="emirates_id_number"<?php echo $users->emirates_id_number->CellAttributes() ?>>
<span id="el_users_emirates_id_number" data-page="3">
<span<?php echo $users->emirates_id_number->ViewAttributes() ?>>
<?php echo $users->emirates_id_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->eid_expiry_date->Visible) { // eid_expiry_date ?>
	<tr id="r_eid_expiry_date">
		<td class="col-sm-2"><span id="elh_users_eid_expiry_date"><?php echo $users->eid_expiry_date->FldCaption() ?></span></td>
		<td data-name="eid_expiry_date"<?php echo $users->eid_expiry_date->CellAttributes() ?>>
<span id="el_users_eid_expiry_date" data-page="3">
<span<?php echo $users->eid_expiry_date->ViewAttributes() ?>>
<?php echo $users->eid_expiry_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->emirates_id_copy->Visible) { // emirates_id_copy ?>
	<tr id="r_emirates_id_copy">
		<td class="col-sm-2"><span id="elh_users_emirates_id_copy"><?php echo $users->emirates_id_copy->FldCaption() ?></span></td>
		<td data-name="emirates_id_copy"<?php echo $users->emirates_id_copy->CellAttributes() ?>>
<span id="el_users_emirates_id_copy" data-page="3">
<span>
<?php echo ew_GetFileViewTag($users->emirates_id_copy, $users->emirates_id_copy->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->passport_number->Visible) { // passport_number ?>
	<tr id="r_passport_number">
		<td class="col-sm-2"><span id="elh_users_passport_number"><?php echo $users->passport_number->FldCaption() ?></span></td>
		<td data-name="passport_number"<?php echo $users->passport_number->CellAttributes() ?>>
<span id="el_users_passport_number" data-page="3">
<span<?php echo $users->passport_number->ViewAttributes() ?>>
<?php echo $users->passport_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->passport_ex_date->Visible) { // passport_ex_date ?>
	<tr id="r_passport_ex_date">
		<td class="col-sm-2"><span id="elh_users_passport_ex_date"><?php echo $users->passport_ex_date->FldCaption() ?></span></td>
		<td data-name="passport_ex_date"<?php echo $users->passport_ex_date->CellAttributes() ?>>
<span id="el_users_passport_ex_date" data-page="3">
<span<?php echo $users->passport_ex_date->ViewAttributes() ?>>
<?php echo $users->passport_ex_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->passport_copy->Visible) { // passport_copy ?>
	<tr id="r_passport_copy">
		<td class="col-sm-2"><span id="elh_users_passport_copy"><?php echo $users->passport_copy->FldCaption() ?></span></td>
		<td data-name="passport_copy"<?php echo $users->passport_copy->CellAttributes() ?>>
<span id="el_users_passport_copy" data-page="3">
<span>
<?php echo ew_GetFileViewTag($users->passport_copy, $users->passport_copy->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("4") ?>" id="tab_users4">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->place_of_work->Visible) { // place_of_work ?>
	<tr id="r_place_of_work">
		<td class="col-sm-2"><span id="elh_users_place_of_work"><?php echo $users->place_of_work->FldCaption() ?></span></td>
		<td data-name="place_of_work"<?php echo $users->place_of_work->CellAttributes() ?>>
<span id="el_users_place_of_work" data-page="4">
<span<?php echo $users->place_of_work->ViewAttributes() ?>>
<?php echo $users->place_of_work->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->qualifications->Visible) { // qualifications ?>
	<tr id="r_qualifications">
		<td class="col-sm-2"><span id="elh_users_qualifications"><?php echo $users->qualifications->FldCaption() ?></span></td>
		<td data-name="qualifications"<?php echo $users->qualifications->CellAttributes() ?>>
<span id="el_users_qualifications" data-page="4">
<span<?php echo $users->qualifications->ViewAttributes() ?>>
<?php echo $users->qualifications->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->cv->Visible) { // cv ?>
	<tr id="r_cv">
		<td class="col-sm-2"><span id="elh_users_cv"><?php echo $users->cv->FldCaption() ?></span></td>
		<td data-name="cv"<?php echo $users->cv->CellAttributes() ?>>
<span id="el_users_cv" data-page="4">
<span<?php echo $users->cv->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($users->cv, $users->cv->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->home_phone->Visible) { // home_phone ?>
	<tr id="r_home_phone">
		<td class="col-sm-2"><span id="elh_users_home_phone"><?php echo $users->home_phone->FldCaption() ?></span></td>
		<td data-name="home_phone"<?php echo $users->home_phone->CellAttributes() ?>>
<span id="el_users_home_phone" data-page="4">
<span<?php echo $users->home_phone->ViewAttributes() ?>>
<?php echo $users->home_phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->work_phone->Visible) { // work_phone ?>
	<tr id="r_work_phone">
		<td class="col-sm-2"><span id="elh_users_work_phone"><?php echo $users->work_phone->FldCaption() ?></span></td>
		<td data-name="work_phone"<?php echo $users->work_phone->CellAttributes() ?>>
<span id="el_users_work_phone" data-page="4">
<span<?php echo $users->work_phone->ViewAttributes() ?>>
<?php echo $users->work_phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->mobile_phone->Visible) { // mobile_phone ?>
	<tr id="r_mobile_phone">
		<td class="col-sm-2"><span id="elh_users_mobile_phone"><?php echo $users->mobile_phone->FldCaption() ?></span></td>
		<td data-name="mobile_phone"<?php echo $users->mobile_phone->CellAttributes() ?>>
<span id="el_users_mobile_phone" data-page="4">
<span<?php echo $users->mobile_phone->ViewAttributes() ?>>
<?php echo $users->mobile_phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->fax->Visible) { // fax ?>
	<tr id="r_fax">
		<td class="col-sm-2"><span id="elh_users_fax"><?php echo $users->fax->FldCaption() ?></span></td>
		<td data-name="fax"<?php echo $users->fax->CellAttributes() ?>>
<span id="el_users_fax" data-page="4">
<span<?php echo $users->fax->ViewAttributes() ?>>
<?php echo $users->fax->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->pobbox->Visible) { // pobbox ?>
	<tr id="r_pobbox">
		<td class="col-sm-2"><span id="elh_users_pobbox"><?php echo $users->pobbox->FldCaption() ?></span></td>
		<td data-name="pobbox"<?php echo $users->pobbox->CellAttributes() ?>>
<span id="el_users_pobbox" data-page="4">
<span<?php echo $users->pobbox->ViewAttributes() ?>>
<?php echo $users->pobbox->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("5") ?>" id="tab_users5">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->_email->Visible) { // email ?>
	<tr id="r__email">
		<td class="col-sm-2"><span id="elh_users__email"><?php echo $users->_email->FldCaption() ?></span></td>
		<td data-name="_email"<?php echo $users->_email->CellAttributes() ?>>
<span id="el_users__email" data-page="5">
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->password->Visible) { // password ?>
	<tr id="r_password">
		<td class="col-sm-2"><span id="elh_users_password"><?php echo $users->password->FldCaption() ?></span></td>
		<td data-name="password"<?php echo $users->password->CellAttributes() ?>>
<span id="el_users_password" data-page="5">
<span<?php echo $users->password->ViewAttributes() ?>>
<?php echo $users->password->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->total_voluntary_hours->Visible) { // total_voluntary_hours ?>
	<tr id="r_total_voluntary_hours">
		<td class="col-sm-2"><span id="elh_users_total_voluntary_hours"><?php echo $users->total_voluntary_hours->FldCaption() ?></span></td>
		<td data-name="total_voluntary_hours"<?php echo $users->total_voluntary_hours->CellAttributes() ?>>
<span id="el_users_total_voluntary_hours" data-page="5">
<span<?php echo $users->total_voluntary_hours->ViewAttributes() ?>>
<?php echo $users->total_voluntary_hours->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->overall_evaluation->Visible) { // overall_evaluation ?>
	<tr id="r_overall_evaluation">
		<td class="col-sm-2"><span id="elh_users_overall_evaluation"><?php echo $users->overall_evaluation->FldCaption() ?></span></td>
		<td data-name="overall_evaluation"<?php echo $users->overall_evaluation->CellAttributes() ?>>
<span id="el_users_overall_evaluation" data-page="5">
<span<?php echo $users->overall_evaluation->ViewAttributes() ?>>
<?php echo $users->overall_evaluation->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("6") ?>" id="tab_users6">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->admin_approval->Visible) { // admin_approval ?>
	<tr id="r_admin_approval">
		<td class="col-sm-2"><span id="elh_users_admin_approval"><?php echo $users->admin_approval->FldCaption() ?></span></td>
		<td data-name="admin_approval"<?php echo $users->admin_approval->CellAttributes() ?>>
<span id="el_users_admin_approval" data-page="6">
<span<?php echo $users->admin_approval->ViewAttributes() ?>>
<?php echo $users->admin_approval->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->lastUpdatedBy->Visible) { // lastUpdatedBy ?>
	<tr id="r_lastUpdatedBy">
		<td class="col-sm-2"><span id="elh_users_lastUpdatedBy"><?php echo $users->lastUpdatedBy->FldCaption() ?></span></td>
		<td data-name="lastUpdatedBy"<?php echo $users->lastUpdatedBy->CellAttributes() ?>>
<span id="el_users_lastUpdatedBy" data-page="6">
<span<?php echo $users->lastUpdatedBy->ViewAttributes() ?>>
<?php echo $users->lastUpdatedBy->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->admin_comment->Visible) { // admin_comment ?>
	<tr id="r_admin_comment">
		<td class="col-sm-2"><span id="elh_users_admin_comment"><?php echo $users->admin_comment->FldCaption() ?></span></td>
		<td data-name="admin_comment"<?php echo $users->admin_comment->CellAttributes() ?>>
<span id="el_users_admin_comment" data-page="6">
<span<?php echo $users->admin_comment->ViewAttributes() ?>>
<?php echo $users->admin_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
		<div class="tab-pane<?php echo $users_view->MultiPages->PageStyle("7") ?>" id="tab_users7">
<?php } ?>
<table class="table table-striped table-bordered table-hover table-condensed ewViewTable">
<?php if ($users->security_approval->Visible) { // security_approval ?>
	<tr id="r_security_approval">
		<td class="col-sm-2"><span id="elh_users_security_approval"><?php echo $users->security_approval->FldCaption() ?></span></td>
		<td data-name="security_approval"<?php echo $users->security_approval->CellAttributes() ?>>
<span id="el_users_security_approval" data-page="7">
<span<?php echo $users->security_approval->ViewAttributes() ?>>
<?php echo $users->security_approval->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->approvedBy->Visible) { // approvedBy ?>
	<tr id="r_approvedBy">
		<td class="col-sm-2"><span id="elh_users_approvedBy"><?php echo $users->approvedBy->FldCaption() ?></span></td>
		<td data-name="approvedBy"<?php echo $users->approvedBy->CellAttributes() ?>>
<span id="el_users_approvedBy" data-page="7">
<span<?php echo $users->approvedBy->ViewAttributes() ?>>
<?php echo $users->approvedBy->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($users->security_comment->Visible) { // security_comment ?>
	<tr id="r_security_comment">
		<td class="col-sm-2"><span id="elh_users_security_comment"><?php echo $users->security_comment->FldCaption() ?></span></td>
		<td data-name="security_comment"<?php echo $users->security_comment->CellAttributes() ?>>
<span id="el_users_security_comment" data-page="7">
<span<?php echo $users->security_comment->ViewAttributes() ?>>
<?php echo $users->security_comment->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($users->Export == "") { ?>
		</div>
<?php } ?>
<?php if ($users->Export == "") { ?>
	</div>
</div>
</div>
<?php } ?>
<?php
	if (in_array("user_attachments", explode(",", $users->getCurrentDetailTable())) && $user_attachments->DetailView) {
?>
<?php if ($users->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("user_attachments", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "user_attachmentsgrid.php" ?>
<?php } ?>
</form>
<?php if ($users->Export == "") { ?>
<script type="text/javascript">
fusersview.Init();
</script>
<?php } ?>
<?php
$users_view->ShowPageFooter();
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
$users_view->Page_Terminate();
?>
