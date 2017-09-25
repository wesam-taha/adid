<?php

// Global variable for table object
$view1 = NULL;

//
// Table class for view1
//
class cview1 extends cTable {
	var $user_id;
	var $group_id;
	var $full_name_ar;
	var $full_name_en;
	var $emirates_id_number;
	var $eid_expiry_date;
	var $emirates_id_copy;
	var $passport_number;
	var $passport_ex_date;
	var $passport_copy;
	var $nationality_type;
	var $nationality;
	var $unid;
	var $visa_expiry_date;
	var $visa_copy;
	var $personal_photo;
	var $current_emirate;
	var $date_of_birth;
	var $full_address;
	var $qualifications;
	var $cv;
	var $gender;
	var $marital_status;
	var $blood_type;
	var $driving_licence;
	var $job;
	var $volunteering_type;
	var $place_of_work;
	var $home_phone;
	var $work_phone;
	var $mobile_phone;
	var $fax;
	var $pobbox;
	var $_email;
	var $password;
	var $total_voluntary_hours;
	var $overall_evaluation;
	var $admin_approval;
	var $admin_comment;
	var $security_approval;
	var $security_comment;
	var $title_number;
	var $Date_Format28users_date_of_birth2C_2725Y_25m_25d2729;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view1';
		$this->TableName = 'view1';
		$this->TableType = 'VIEW';

		// Update Table
		$this->UpdateTable = "`view1`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 10;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// user_id
		$this->user_id = new cField('view1', 'view1', 'x_user_id', 'user_id', '`user_id`', '`user_id`', 3, -1, FALSE, '`user_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->user_id->Sortable = TRUE; // Allow sort
		$this->user_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['user_id'] = &$this->user_id;

		// group_id
		$this->group_id = new cField('view1', 'view1', 'x_group_id', 'group_id', '`group_id`', '`group_id`', 201, -1, FALSE, '`group_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->group_id->Sortable = TRUE; // Allow sort
		$this->fields['group_id'] = &$this->group_id;

		// full_name_ar
		$this->full_name_ar = new cField('view1', 'view1', 'x_full_name_ar', 'full_name_ar', '`full_name_ar`', '`full_name_ar`', 201, -1, FALSE, '`full_name_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->full_name_ar->Sortable = TRUE; // Allow sort
		$this->fields['full_name_ar'] = &$this->full_name_ar;

		// full_name_en
		$this->full_name_en = new cField('view1', 'view1', 'x_full_name_en', 'full_name_en', '`full_name_en`', '`full_name_en`', 201, -1, FALSE, '`full_name_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->full_name_en->Sortable = TRUE; // Allow sort
		$this->fields['full_name_en'] = &$this->full_name_en;

		// emirates_id_number
		$this->emirates_id_number = new cField('view1', 'view1', 'x_emirates_id_number', 'emirates_id_number', '`emirates_id_number`', '`emirates_id_number`', 201, -1, FALSE, '`emirates_id_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->emirates_id_number->Sortable = TRUE; // Allow sort
		$this->fields['emirates_id_number'] = &$this->emirates_id_number;

		// eid_expiry_date
		$this->eid_expiry_date = new cField('view1', 'view1', 'x_eid_expiry_date', 'eid_expiry_date', '`eid_expiry_date`', ew_CastDateFieldForLike('`eid_expiry_date`', 0, "DB"), 133, 0, FALSE, '`eid_expiry_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->eid_expiry_date->Sortable = TRUE; // Allow sort
		$this->eid_expiry_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['eid_expiry_date'] = &$this->eid_expiry_date;

		// emirates_id_copy
		$this->emirates_id_copy = new cField('view1', 'view1', 'x_emirates_id_copy', 'emirates_id_copy', '`emirates_id_copy`', '`emirates_id_copy`', 201, -1, FALSE, '`emirates_id_copy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->emirates_id_copy->Sortable = TRUE; // Allow sort
		$this->fields['emirates_id_copy'] = &$this->emirates_id_copy;

		// passport_number
		$this->passport_number = new cField('view1', 'view1', 'x_passport_number', 'passport_number', '`passport_number`', '`passport_number`', 201, -1, FALSE, '`passport_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->passport_number->Sortable = TRUE; // Allow sort
		$this->fields['passport_number'] = &$this->passport_number;

		// passport_ex_date
		$this->passport_ex_date = new cField('view1', 'view1', 'x_passport_ex_date', 'passport_ex_date', '`passport_ex_date`', ew_CastDateFieldForLike('`passport_ex_date`', 0, "DB"), 133, 0, FALSE, '`passport_ex_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->passport_ex_date->Sortable = TRUE; // Allow sort
		$this->passport_ex_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['passport_ex_date'] = &$this->passport_ex_date;

		// passport_copy
		$this->passport_copy = new cField('view1', 'view1', 'x_passport_copy', 'passport_copy', '`passport_copy`', '`passport_copy`', 201, -1, FALSE, '`passport_copy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->passport_copy->Sortable = TRUE; // Allow sort
		$this->fields['passport_copy'] = &$this->passport_copy;

		// nationality_type
		$this->nationality_type = new cField('view1', 'view1', 'x_nationality_type', 'nationality_type', '`nationality_type`', '`nationality_type`', 3, -1, FALSE, '`nationality_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nationality_type->Sortable = TRUE; // Allow sort
		$this->nationality_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['nationality_type'] = &$this->nationality_type;

		// nationality
		$this->nationality = new cField('view1', 'view1', 'x_nationality', 'nationality', '`nationality`', '`nationality`', 201, -1, FALSE, '`nationality`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->nationality->Sortable = TRUE; // Allow sort
		$this->fields['nationality'] = &$this->nationality;

		// unid
		$this->unid = new cField('view1', 'view1', 'x_unid', 'unid', '`unid`', '`unid`', 3, -1, FALSE, '`unid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->unid->Sortable = TRUE; // Allow sort
		$this->unid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['unid'] = &$this->unid;

		// visa_expiry_date
		$this->visa_expiry_date = new cField('view1', 'view1', 'x_visa_expiry_date', 'visa_expiry_date', '`visa_expiry_date`', ew_CastDateFieldForLike('`visa_expiry_date`', 0, "DB"), 133, 0, FALSE, '`visa_expiry_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->visa_expiry_date->Sortable = TRUE; // Allow sort
		$this->visa_expiry_date->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['visa_expiry_date'] = &$this->visa_expiry_date;

		// visa_copy
		$this->visa_copy = new cField('view1', 'view1', 'x_visa_copy', 'visa_copy', '`visa_copy`', '`visa_copy`', 200, -1, FALSE, '`visa_copy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->visa_copy->Sortable = TRUE; // Allow sort
		$this->fields['visa_copy'] = &$this->visa_copy;

		// personal_photo
		$this->personal_photo = new cField('view1', 'view1', 'x_personal_photo', 'personal_photo', '`personal_photo`', '`personal_photo`', 200, -1, FALSE, '`personal_photo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->personal_photo->Sortable = TRUE; // Allow sort
		$this->fields['personal_photo'] = &$this->personal_photo;

		// current_emirate
		$this->current_emirate = new cField('view1', 'view1', 'x_current_emirate', 'current_emirate', '`current_emirate`', '`current_emirate`', 201, -1, FALSE, '`current_emirate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->current_emirate->Sortable = TRUE; // Allow sort
		$this->fields['current_emirate'] = &$this->current_emirate;

		// date_of_birth
		$this->date_of_birth = new cField('view1', 'view1', 'x_date_of_birth', 'date_of_birth', '`date_of_birth`', ew_CastDateFieldForLike('`date_of_birth`', 0, "DB"), 133, 0, FALSE, '`date_of_birth`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->date_of_birth->Sortable = TRUE; // Allow sort
		$this->date_of_birth->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['date_of_birth'] = &$this->date_of_birth;

		// full_address
		$this->full_address = new cField('view1', 'view1', 'x_full_address', 'full_address', '`full_address`', '`full_address`', 201, -1, FALSE, '`full_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->full_address->Sortable = TRUE; // Allow sort
		$this->fields['full_address'] = &$this->full_address;

		// qualifications
		$this->qualifications = new cField('view1', 'view1', 'x_qualifications', 'qualifications', '`qualifications`', '`qualifications`', 201, -1, FALSE, '`qualifications`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->qualifications->Sortable = TRUE; // Allow sort
		$this->fields['qualifications'] = &$this->qualifications;

		// cv
		$this->cv = new cField('view1', 'view1', 'x_cv', 'cv', '`cv`', '`cv`', 201, -1, FALSE, '`cv`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->cv->Sortable = TRUE; // Allow sort
		$this->fields['cv'] = &$this->cv;

		// gender
		$this->gender = new cField('view1', 'view1', 'x_gender', 'gender', '`gender`', '`gender`', 3, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gender->Sortable = TRUE; // Allow sort
		$this->gender->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['gender'] = &$this->gender;

		// marital_status
		$this->marital_status = new cField('view1', 'view1', 'x_marital_status', 'marital_status', '`marital_status`', '`marital_status`', 3, -1, FALSE, '`marital_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->marital_status->Sortable = TRUE; // Allow sort
		$this->marital_status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['marital_status'] = &$this->marital_status;

		// blood_type
		$this->blood_type = new cField('view1', 'view1', 'x_blood_type', 'blood_type', '`blood_type`', '`blood_type`', 3, -1, FALSE, '`blood_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->blood_type->Sortable = TRUE; // Allow sort
		$this->blood_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['blood_type'] = &$this->blood_type;

		// driving_licence
		$this->driving_licence = new cField('view1', 'view1', 'x_driving_licence', 'driving_licence', '`driving_licence`', '`driving_licence`', 3, -1, FALSE, '`driving_licence`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->driving_licence->Sortable = TRUE; // Allow sort
		$this->driving_licence->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['driving_licence'] = &$this->driving_licence;

		// job
		$this->job = new cField('view1', 'view1', 'x_job', 'job', '`job`', '`job`', 3, -1, FALSE, '`job`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->job->Sortable = TRUE; // Allow sort
		$this->job->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['job'] = &$this->job;

		// volunteering_type
		$this->volunteering_type = new cField('view1', 'view1', 'x_volunteering_type', 'volunteering_type', '`volunteering_type`', '`volunteering_type`', 3, -1, FALSE, '`volunteering_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->volunteering_type->Sortable = TRUE; // Allow sort
		$this->volunteering_type->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['volunteering_type'] = &$this->volunteering_type;

		// place_of_work
		$this->place_of_work = new cField('view1', 'view1', 'x_place_of_work', 'place_of_work', '`place_of_work`', '`place_of_work`', 201, -1, FALSE, '`place_of_work`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->place_of_work->Sortable = TRUE; // Allow sort
		$this->fields['place_of_work'] = &$this->place_of_work;

		// home_phone
		$this->home_phone = new cField('view1', 'view1', 'x_home_phone', 'home_phone', '`home_phone`', '`home_phone`', 201, -1, FALSE, '`home_phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->home_phone->Sortable = TRUE; // Allow sort
		$this->fields['home_phone'] = &$this->home_phone;

		// work_phone
		$this->work_phone = new cField('view1', 'view1', 'x_work_phone', 'work_phone', '`work_phone`', '`work_phone`', 201, -1, FALSE, '`work_phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->work_phone->Sortable = TRUE; // Allow sort
		$this->fields['work_phone'] = &$this->work_phone;

		// mobile_phone
		$this->mobile_phone = new cField('view1', 'view1', 'x_mobile_phone', 'mobile_phone', '`mobile_phone`', '`mobile_phone`', 201, -1, FALSE, '`mobile_phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->mobile_phone->Sortable = TRUE; // Allow sort
		$this->fields['mobile_phone'] = &$this->mobile_phone;

		// fax
		$this->fax = new cField('view1', 'view1', 'x_fax', 'fax', '`fax`', '`fax`', 201, -1, FALSE, '`fax`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fax->Sortable = TRUE; // Allow sort
		$this->fields['fax'] = &$this->fax;

		// pobbox
		$this->pobbox = new cField('view1', 'view1', 'x_pobbox', 'pobbox', '`pobbox`', '`pobbox`', 201, -1, FALSE, '`pobbox`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->pobbox->Sortable = TRUE; // Allow sort
		$this->fields['pobbox'] = &$this->pobbox;

		// email
		$this->_email = new cField('view1', 'view1', 'x__email', 'email', '`email`', '`email`', 201, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->_email->Sortable = TRUE; // Allow sort
		$this->fields['email'] = &$this->_email;

		// password
		$this->password = new cField('view1', 'view1', 'x_password', 'password', '`password`', '`password`', 201, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->password->Sortable = TRUE; // Allow sort
		$this->fields['password'] = &$this->password;

		// total_voluntary_hours
		$this->total_voluntary_hours = new cField('view1', 'view1', 'x_total_voluntary_hours', 'total_voluntary_hours', '`total_voluntary_hours`', '`total_voluntary_hours`', 201, -1, FALSE, '`total_voluntary_hours`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->total_voluntary_hours->Sortable = TRUE; // Allow sort
		$this->fields['total_voluntary_hours'] = &$this->total_voluntary_hours;

		// overall_evaluation
		$this->overall_evaluation = new cField('view1', 'view1', 'x_overall_evaluation', 'overall_evaluation', '`overall_evaluation`', '`overall_evaluation`', 3, -1, FALSE, '`overall_evaluation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->overall_evaluation->Sortable = TRUE; // Allow sort
		$this->overall_evaluation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['overall_evaluation'] = &$this->overall_evaluation;

		// admin_approval
		$this->admin_approval = new cField('view1', 'view1', 'x_admin_approval', 'admin_approval', '`admin_approval`', '`admin_approval`', 3, -1, FALSE, '`admin_approval`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->admin_approval->Sortable = TRUE; // Allow sort
		$this->admin_approval->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['admin_approval'] = &$this->admin_approval;

		// admin_comment
		$this->admin_comment = new cField('view1', 'view1', 'x_admin_comment', 'admin_comment', '`admin_comment`', '`admin_comment`', 201, -1, FALSE, '`admin_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->admin_comment->Sortable = TRUE; // Allow sort
		$this->fields['admin_comment'] = &$this->admin_comment;

		// security_approval
		$this->security_approval = new cField('view1', 'view1', 'x_security_approval', 'security_approval', '`security_approval`', '`security_approval`', 3, -1, FALSE, '`security_approval`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->security_approval->Sortable = TRUE; // Allow sort
		$this->security_approval->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['security_approval'] = &$this->security_approval;

		// security_comment
		$this->security_comment = new cField('view1', 'view1', 'x_security_comment', 'security_comment', '`security_comment`', '`security_comment`', 201, -1, FALSE, '`security_comment`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->security_comment->Sortable = TRUE; // Allow sort
		$this->fields['security_comment'] = &$this->security_comment;

		// title_number
		$this->title_number = new cField('view1', 'view1', 'x_title_number', 'title_number', '`title_number`', '`title_number`', 3, -1, FALSE, '`title_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->title_number->Sortable = TRUE; // Allow sort
		$this->title_number->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['title_number'] = &$this->title_number;

		// Date_Format(users.date_of_birth, '%Y-%m-%d')
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729 = new cField('view1', 'view1', 'x_Date_Format28users_date_of_birth2C_2725Y_25m_25d2729', 'Date_Format(users.date_of_birth, \'%Y-%m-%d\')', '`Date_Format(users.date_of_birth, \'%Y-%m-%d\')`', '`Date_Format(users.date_of_birth, \'%Y-%m-%d\')`', 200, -1, FALSE, '`Date_Format(users.date_of_birth, \'%Y-%m-%d\')`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Sortable = TRUE; // Allow sort
		$this->fields['Date_Format(users.date_of_birth, \'%Y-%m-%d\')'] = &$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $this->LeftColumnClass);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`view1`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$sSql = $this->ListSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->user_id->setDbValue($conn->Insert_ID());
			$rs['user_id'] = $this->user_id->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('user_id', $rs))
				ew_AddFilter($where, ew_QuotedName('user_id', $this->DBID) . '=' . ew_QuotedValue($rs['user_id'], $this->user_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`user_id` = @user_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->user_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@user_id@", ew_AdjustSql($this->user_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "view1list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "view1view.php")
			return $Language->Phrase("View");
		elseif ($pageName == "view1edit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "view1add.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "view1list.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("view1view.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("view1view.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "view1add.php?" . $this->UrlParm($parm);
		else
			$url = "view1add.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("view1edit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("view1add.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view1delete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "user_id:" . ew_VarToJson($this->user_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->user_id->CurrentValue)) {
			$sUrl .= "user_id=" . urlencode($this->user_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["user_id"]))
				$arKeys[] = $_POST["user_id"];
			elseif (isset($_GET["user_id"]))
				$arKeys[] = $_GET["user_id"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->user_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->user_id->setDbValue($rs->fields('user_id'));
		$this->group_id->setDbValue($rs->fields('group_id'));
		$this->full_name_ar->setDbValue($rs->fields('full_name_ar'));
		$this->full_name_en->setDbValue($rs->fields('full_name_en'));
		$this->emirates_id_number->setDbValue($rs->fields('emirates_id_number'));
		$this->eid_expiry_date->setDbValue($rs->fields('eid_expiry_date'));
		$this->emirates_id_copy->setDbValue($rs->fields('emirates_id_copy'));
		$this->passport_number->setDbValue($rs->fields('passport_number'));
		$this->passport_ex_date->setDbValue($rs->fields('passport_ex_date'));
		$this->passport_copy->setDbValue($rs->fields('passport_copy'));
		$this->nationality_type->setDbValue($rs->fields('nationality_type'));
		$this->nationality->setDbValue($rs->fields('nationality'));
		$this->unid->setDbValue($rs->fields('unid'));
		$this->visa_expiry_date->setDbValue($rs->fields('visa_expiry_date'));
		$this->visa_copy->setDbValue($rs->fields('visa_copy'));
		$this->personal_photo->setDbValue($rs->fields('personal_photo'));
		$this->current_emirate->setDbValue($rs->fields('current_emirate'));
		$this->date_of_birth->setDbValue($rs->fields('date_of_birth'));
		$this->full_address->setDbValue($rs->fields('full_address'));
		$this->qualifications->setDbValue($rs->fields('qualifications'));
		$this->cv->setDbValue($rs->fields('cv'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->marital_status->setDbValue($rs->fields('marital_status'));
		$this->blood_type->setDbValue($rs->fields('blood_type'));
		$this->driving_licence->setDbValue($rs->fields('driving_licence'));
		$this->job->setDbValue($rs->fields('job'));
		$this->volunteering_type->setDbValue($rs->fields('volunteering_type'));
		$this->place_of_work->setDbValue($rs->fields('place_of_work'));
		$this->home_phone->setDbValue($rs->fields('home_phone'));
		$this->work_phone->setDbValue($rs->fields('work_phone'));
		$this->mobile_phone->setDbValue($rs->fields('mobile_phone'));
		$this->fax->setDbValue($rs->fields('fax'));
		$this->pobbox->setDbValue($rs->fields('pobbox'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->password->setDbValue($rs->fields('password'));
		$this->total_voluntary_hours->setDbValue($rs->fields('total_voluntary_hours'));
		$this->overall_evaluation->setDbValue($rs->fields('overall_evaluation'));
		$this->admin_approval->setDbValue($rs->fields('admin_approval'));
		$this->admin_comment->setDbValue($rs->fields('admin_comment'));
		$this->security_approval->setDbValue($rs->fields('security_approval'));
		$this->security_comment->setDbValue($rs->fields('security_comment'));
		$this->title_number->setDbValue($rs->fields('title_number'));
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->setDbValue($rs->fields('Date_Format(users.date_of_birth, \'%Y-%m-%d\')'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
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
		// user_id

		$this->user_id->ViewValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// group_id
		$this->group_id->ViewValue = $this->group_id->CurrentValue;
		$this->group_id->ViewCustomAttributes = "";

		// full_name_ar
		$this->full_name_ar->ViewValue = $this->full_name_ar->CurrentValue;
		$this->full_name_ar->ViewCustomAttributes = "";

		// full_name_en
		$this->full_name_en->ViewValue = $this->full_name_en->CurrentValue;
		$this->full_name_en->ViewCustomAttributes = "";

		// emirates_id_number
		$this->emirates_id_number->ViewValue = $this->emirates_id_number->CurrentValue;
		$this->emirates_id_number->ViewCustomAttributes = "";

		// eid_expiry_date
		$this->eid_expiry_date->ViewValue = $this->eid_expiry_date->CurrentValue;
		$this->eid_expiry_date->ViewValue = ew_FormatDateTime($this->eid_expiry_date->ViewValue, 0);
		$this->eid_expiry_date->ViewCustomAttributes = "";

		// emirates_id_copy
		$this->emirates_id_copy->ViewValue = $this->emirates_id_copy->CurrentValue;
		$this->emirates_id_copy->ViewCustomAttributes = "";

		// passport_number
		$this->passport_number->ViewValue = $this->passport_number->CurrentValue;
		$this->passport_number->ViewCustomAttributes = "";

		// passport_ex_date
		$this->passport_ex_date->ViewValue = $this->passport_ex_date->CurrentValue;
		$this->passport_ex_date->ViewValue = ew_FormatDateTime($this->passport_ex_date->ViewValue, 0);
		$this->passport_ex_date->ViewCustomAttributes = "";

		// passport_copy
		$this->passport_copy->ViewValue = $this->passport_copy->CurrentValue;
		$this->passport_copy->ViewCustomAttributes = "";

		// nationality_type
		$this->nationality_type->ViewValue = $this->nationality_type->CurrentValue;
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
		$this->visa_copy->ViewValue = $this->visa_copy->CurrentValue;
		$this->visa_copy->ViewCustomAttributes = "";

		// personal_photo
		$this->personal_photo->ViewValue = $this->personal_photo->CurrentValue;
		$this->personal_photo->ViewCustomAttributes = "";

		// current_emirate
		$this->current_emirate->ViewValue = $this->current_emirate->CurrentValue;
		$this->current_emirate->ViewCustomAttributes = "";

		// date_of_birth
		$this->date_of_birth->ViewValue = $this->date_of_birth->CurrentValue;
		$this->date_of_birth->ViewValue = ew_FormatDateTime($this->date_of_birth->ViewValue, 0);
		$this->date_of_birth->ViewCustomAttributes = "";

		// full_address
		$this->full_address->ViewValue = $this->full_address->CurrentValue;
		$this->full_address->ViewCustomAttributes = "";

		// qualifications
		$this->qualifications->ViewValue = $this->qualifications->CurrentValue;
		$this->qualifications->ViewCustomAttributes = "";

		// cv
		$this->cv->ViewValue = $this->cv->CurrentValue;
		$this->cv->ViewCustomAttributes = "";

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

		// place_of_work
		$this->place_of_work->ViewValue = $this->place_of_work->CurrentValue;
		$this->place_of_work->ViewCustomAttributes = "";

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
		$this->admin_approval->ViewValue = $this->admin_approval->CurrentValue;
		$this->admin_approval->ViewCustomAttributes = "";

		// admin_comment
		$this->admin_comment->ViewValue = $this->admin_comment->CurrentValue;
		$this->admin_comment->ViewCustomAttributes = "";

		// security_approval
		$this->security_approval->ViewValue = $this->security_approval->CurrentValue;
		$this->security_approval->ViewCustomAttributes = "";

		// security_comment
		$this->security_comment->ViewValue = $this->security_comment->CurrentValue;
		$this->security_comment->ViewCustomAttributes = "";

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
		$this->emirates_id_copy->HrefValue = "";
		$this->emirates_id_copy->TooltipValue = "";

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
		$this->passport_copy->HrefValue = "";
		$this->passport_copy->TooltipValue = "";

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
		$this->visa_copy->HrefValue = "";
		$this->visa_copy->TooltipValue = "";

		// personal_photo
		$this->personal_photo->LinkCustomAttributes = "";
		$this->personal_photo->HrefValue = "";
		$this->personal_photo->TooltipValue = "";

		// current_emirate
		$this->current_emirate->LinkCustomAttributes = "";
		$this->current_emirate->HrefValue = "";
		$this->current_emirate->TooltipValue = "";

		// date_of_birth
		$this->date_of_birth->LinkCustomAttributes = "";
		$this->date_of_birth->HrefValue = "";
		$this->date_of_birth->TooltipValue = "";

		// full_address
		$this->full_address->LinkCustomAttributes = "";
		$this->full_address->HrefValue = "";
		$this->full_address->TooltipValue = "";

		// qualifications
		$this->qualifications->LinkCustomAttributes = "";
		$this->qualifications->HrefValue = "";
		$this->qualifications->TooltipValue = "";

		// cv
		$this->cv->LinkCustomAttributes = "";
		$this->cv->HrefValue = "";
		$this->cv->TooltipValue = "";

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

		// place_of_work
		$this->place_of_work->LinkCustomAttributes = "";
		$this->place_of_work->HrefValue = "";
		$this->place_of_work->TooltipValue = "";

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

		// title_number
		$this->title_number->LinkCustomAttributes = "";
		$this->title_number->HrefValue = "";
		$this->title_number->TooltipValue = "";

		// Date_Format(users.date_of_birth, '%Y-%m-%d')
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->LinkCustomAttributes = "";
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->HrefValue = "";
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// user_id
		$this->user_id->EditAttrs["class"] = "form-control";
		$this->user_id->EditCustomAttributes = "";
		$this->user_id->EditValue = $this->user_id->CurrentValue;
		$this->user_id->ViewCustomAttributes = "";

		// group_id
		$this->group_id->EditAttrs["class"] = "form-control";
		$this->group_id->EditCustomAttributes = "";
		$this->group_id->EditValue = $this->group_id->CurrentValue;
		$this->group_id->PlaceHolder = ew_RemoveHtml($this->group_id->FldCaption());

		// full_name_ar
		$this->full_name_ar->EditAttrs["class"] = "form-control";
		$this->full_name_ar->EditCustomAttributes = "";
		$this->full_name_ar->EditValue = $this->full_name_ar->CurrentValue;
		$this->full_name_ar->PlaceHolder = ew_RemoveHtml($this->full_name_ar->FldCaption());

		// full_name_en
		$this->full_name_en->EditAttrs["class"] = "form-control";
		$this->full_name_en->EditCustomAttributes = "";
		$this->full_name_en->EditValue = $this->full_name_en->CurrentValue;
		$this->full_name_en->PlaceHolder = ew_RemoveHtml($this->full_name_en->FldCaption());

		// emirates_id_number
		$this->emirates_id_number->EditAttrs["class"] = "form-control";
		$this->emirates_id_number->EditCustomAttributes = "";
		$this->emirates_id_number->EditValue = $this->emirates_id_number->CurrentValue;
		$this->emirates_id_number->PlaceHolder = ew_RemoveHtml($this->emirates_id_number->FldCaption());

		// eid_expiry_date
		$this->eid_expiry_date->EditAttrs["class"] = "form-control";
		$this->eid_expiry_date->EditCustomAttributes = "";
		$this->eid_expiry_date->EditValue = ew_FormatDateTime($this->eid_expiry_date->CurrentValue, 8);
		$this->eid_expiry_date->PlaceHolder = ew_RemoveHtml($this->eid_expiry_date->FldCaption());

		// emirates_id_copy
		$this->emirates_id_copy->EditAttrs["class"] = "form-control";
		$this->emirates_id_copy->EditCustomAttributes = "";
		$this->emirates_id_copy->EditValue = $this->emirates_id_copy->CurrentValue;
		$this->emirates_id_copy->PlaceHolder = ew_RemoveHtml($this->emirates_id_copy->FldCaption());

		// passport_number
		$this->passport_number->EditAttrs["class"] = "form-control";
		$this->passport_number->EditCustomAttributes = "";
		$this->passport_number->EditValue = $this->passport_number->CurrentValue;
		$this->passport_number->PlaceHolder = ew_RemoveHtml($this->passport_number->FldCaption());

		// passport_ex_date
		$this->passport_ex_date->EditAttrs["class"] = "form-control";
		$this->passport_ex_date->EditCustomAttributes = "";
		$this->passport_ex_date->EditValue = ew_FormatDateTime($this->passport_ex_date->CurrentValue, 8);
		$this->passport_ex_date->PlaceHolder = ew_RemoveHtml($this->passport_ex_date->FldCaption());

		// passport_copy
		$this->passport_copy->EditAttrs["class"] = "form-control";
		$this->passport_copy->EditCustomAttributes = "";
		$this->passport_copy->EditValue = $this->passport_copy->CurrentValue;
		$this->passport_copy->PlaceHolder = ew_RemoveHtml($this->passport_copy->FldCaption());

		// nationality_type
		$this->nationality_type->EditAttrs["class"] = "form-control";
		$this->nationality_type->EditCustomAttributes = "";
		$this->nationality_type->EditValue = $this->nationality_type->CurrentValue;
		$this->nationality_type->PlaceHolder = ew_RemoveHtml($this->nationality_type->FldCaption());

		// nationality
		$this->nationality->EditAttrs["class"] = "form-control";
		$this->nationality->EditCustomAttributes = "";
		$this->nationality->EditValue = $this->nationality->CurrentValue;
		$this->nationality->PlaceHolder = ew_RemoveHtml($this->nationality->FldCaption());

		// unid
		$this->unid->EditAttrs["class"] = "form-control";
		$this->unid->EditCustomAttributes = "";
		$this->unid->EditValue = $this->unid->CurrentValue;
		$this->unid->PlaceHolder = ew_RemoveHtml($this->unid->FldCaption());

		// visa_expiry_date
		$this->visa_expiry_date->EditAttrs["class"] = "form-control";
		$this->visa_expiry_date->EditCustomAttributes = "";
		$this->visa_expiry_date->EditValue = ew_FormatDateTime($this->visa_expiry_date->CurrentValue, 8);
		$this->visa_expiry_date->PlaceHolder = ew_RemoveHtml($this->visa_expiry_date->FldCaption());

		// visa_copy
		$this->visa_copy->EditAttrs["class"] = "form-control";
		$this->visa_copy->EditCustomAttributes = "";
		$this->visa_copy->EditValue = $this->visa_copy->CurrentValue;
		$this->visa_copy->PlaceHolder = ew_RemoveHtml($this->visa_copy->FldCaption());

		// personal_photo
		$this->personal_photo->EditAttrs["class"] = "form-control";
		$this->personal_photo->EditCustomAttributes = "";
		$this->personal_photo->EditValue = $this->personal_photo->CurrentValue;
		$this->personal_photo->PlaceHolder = ew_RemoveHtml($this->personal_photo->FldCaption());

		// current_emirate
		$this->current_emirate->EditAttrs["class"] = "form-control";
		$this->current_emirate->EditCustomAttributes = "";
		$this->current_emirate->EditValue = $this->current_emirate->CurrentValue;
		$this->current_emirate->PlaceHolder = ew_RemoveHtml($this->current_emirate->FldCaption());

		// date_of_birth
		$this->date_of_birth->EditAttrs["class"] = "form-control";
		$this->date_of_birth->EditCustomAttributes = "";
		$this->date_of_birth->EditValue = ew_FormatDateTime($this->date_of_birth->CurrentValue, 8);
		$this->date_of_birth->PlaceHolder = ew_RemoveHtml($this->date_of_birth->FldCaption());

		// full_address
		$this->full_address->EditAttrs["class"] = "form-control";
		$this->full_address->EditCustomAttributes = "";
		$this->full_address->EditValue = $this->full_address->CurrentValue;
		$this->full_address->PlaceHolder = ew_RemoveHtml($this->full_address->FldCaption());

		// qualifications
		$this->qualifications->EditAttrs["class"] = "form-control";
		$this->qualifications->EditCustomAttributes = "";
		$this->qualifications->EditValue = $this->qualifications->CurrentValue;
		$this->qualifications->PlaceHolder = ew_RemoveHtml($this->qualifications->FldCaption());

		// cv
		$this->cv->EditAttrs["class"] = "form-control";
		$this->cv->EditCustomAttributes = "";
		$this->cv->EditValue = $this->cv->CurrentValue;
		$this->cv->PlaceHolder = ew_RemoveHtml($this->cv->FldCaption());

		// gender
		$this->gender->EditAttrs["class"] = "form-control";
		$this->gender->EditCustomAttributes = "";
		$this->gender->EditValue = $this->gender->CurrentValue;
		$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

		// marital_status
		$this->marital_status->EditAttrs["class"] = "form-control";
		$this->marital_status->EditCustomAttributes = "";
		$this->marital_status->EditValue = $this->marital_status->CurrentValue;
		$this->marital_status->PlaceHolder = ew_RemoveHtml($this->marital_status->FldCaption());

		// blood_type
		$this->blood_type->EditAttrs["class"] = "form-control";
		$this->blood_type->EditCustomAttributes = "";
		$this->blood_type->EditValue = $this->blood_type->CurrentValue;
		$this->blood_type->PlaceHolder = ew_RemoveHtml($this->blood_type->FldCaption());

		// driving_licence
		$this->driving_licence->EditAttrs["class"] = "form-control";
		$this->driving_licence->EditCustomAttributes = "";
		$this->driving_licence->EditValue = $this->driving_licence->CurrentValue;
		$this->driving_licence->PlaceHolder = ew_RemoveHtml($this->driving_licence->FldCaption());

		// job
		$this->job->EditAttrs["class"] = "form-control";
		$this->job->EditCustomAttributes = "";
		$this->job->EditValue = $this->job->CurrentValue;
		$this->job->PlaceHolder = ew_RemoveHtml($this->job->FldCaption());

		// volunteering_type
		$this->volunteering_type->EditAttrs["class"] = "form-control";
		$this->volunteering_type->EditCustomAttributes = "";
		$this->volunteering_type->EditValue = $this->volunteering_type->CurrentValue;
		$this->volunteering_type->PlaceHolder = ew_RemoveHtml($this->volunteering_type->FldCaption());

		// place_of_work
		$this->place_of_work->EditAttrs["class"] = "form-control";
		$this->place_of_work->EditCustomAttributes = "";
		$this->place_of_work->EditValue = $this->place_of_work->CurrentValue;
		$this->place_of_work->PlaceHolder = ew_RemoveHtml($this->place_of_work->FldCaption());

		// home_phone
		$this->home_phone->EditAttrs["class"] = "form-control";
		$this->home_phone->EditCustomAttributes = "";
		$this->home_phone->EditValue = $this->home_phone->CurrentValue;
		$this->home_phone->PlaceHolder = ew_RemoveHtml($this->home_phone->FldCaption());

		// work_phone
		$this->work_phone->EditAttrs["class"] = "form-control";
		$this->work_phone->EditCustomAttributes = "";
		$this->work_phone->EditValue = $this->work_phone->CurrentValue;
		$this->work_phone->PlaceHolder = ew_RemoveHtml($this->work_phone->FldCaption());

		// mobile_phone
		$this->mobile_phone->EditAttrs["class"] = "form-control";
		$this->mobile_phone->EditCustomAttributes = "";
		$this->mobile_phone->EditValue = $this->mobile_phone->CurrentValue;
		$this->mobile_phone->PlaceHolder = ew_RemoveHtml($this->mobile_phone->FldCaption());

		// fax
		$this->fax->EditAttrs["class"] = "form-control";
		$this->fax->EditCustomAttributes = "";
		$this->fax->EditValue = $this->fax->CurrentValue;
		$this->fax->PlaceHolder = ew_RemoveHtml($this->fax->FldCaption());

		// pobbox
		$this->pobbox->EditAttrs["class"] = "form-control";
		$this->pobbox->EditCustomAttributes = "";
		$this->pobbox->EditValue = $this->pobbox->CurrentValue;
		$this->pobbox->PlaceHolder = ew_RemoveHtml($this->pobbox->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = $this->_email->CurrentValue;
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// password
		$this->password->EditAttrs["class"] = "form-control";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = $this->password->CurrentValue;
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

		// total_voluntary_hours
		$this->total_voluntary_hours->EditAttrs["class"] = "form-control";
		$this->total_voluntary_hours->EditCustomAttributes = "";
		$this->total_voluntary_hours->EditValue = $this->total_voluntary_hours->CurrentValue;
		$this->total_voluntary_hours->PlaceHolder = ew_RemoveHtml($this->total_voluntary_hours->FldCaption());

		// overall_evaluation
		$this->overall_evaluation->EditAttrs["class"] = "form-control";
		$this->overall_evaluation->EditCustomAttributes = "";
		$this->overall_evaluation->EditValue = $this->overall_evaluation->CurrentValue;
		$this->overall_evaluation->PlaceHolder = ew_RemoveHtml($this->overall_evaluation->FldCaption());

		// admin_approval
		$this->admin_approval->EditAttrs["class"] = "form-control";
		$this->admin_approval->EditCustomAttributes = "";
		$this->admin_approval->EditValue = $this->admin_approval->CurrentValue;
		$this->admin_approval->PlaceHolder = ew_RemoveHtml($this->admin_approval->FldCaption());

		// admin_comment
		$this->admin_comment->EditAttrs["class"] = "form-control";
		$this->admin_comment->EditCustomAttributes = "";
		$this->admin_comment->EditValue = $this->admin_comment->CurrentValue;
		$this->admin_comment->PlaceHolder = ew_RemoveHtml($this->admin_comment->FldCaption());

		// security_approval
		$this->security_approval->EditAttrs["class"] = "form-control";
		$this->security_approval->EditCustomAttributes = "";
		$this->security_approval->EditValue = $this->security_approval->CurrentValue;
		$this->security_approval->PlaceHolder = ew_RemoveHtml($this->security_approval->FldCaption());

		// security_comment
		$this->security_comment->EditAttrs["class"] = "form-control";
		$this->security_comment->EditCustomAttributes = "";
		$this->security_comment->EditValue = $this->security_comment->CurrentValue;
		$this->security_comment->PlaceHolder = ew_RemoveHtml($this->security_comment->FldCaption());

		// title_number
		$this->title_number->EditAttrs["class"] = "form-control";
		$this->title_number->EditCustomAttributes = "";
		$this->title_number->EditValue = $this->title_number->CurrentValue;
		$this->title_number->PlaceHolder = ew_RemoveHtml($this->title_number->FldCaption());

		// Date_Format(users.date_of_birth, '%Y-%m-%d')
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->EditAttrs["class"] = "form-control";
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->EditCustomAttributes = "";
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->EditValue = $this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->CurrentValue;
		$this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->PlaceHolder = ew_RemoveHtml($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
					if ($this->group_id->Exportable) $Doc->ExportCaption($this->group_id);
					if ($this->full_name_ar->Exportable) $Doc->ExportCaption($this->full_name_ar);
					if ($this->full_name_en->Exportable) $Doc->ExportCaption($this->full_name_en);
					if ($this->emirates_id_number->Exportable) $Doc->ExportCaption($this->emirates_id_number);
					if ($this->eid_expiry_date->Exportable) $Doc->ExportCaption($this->eid_expiry_date);
					if ($this->emirates_id_copy->Exportable) $Doc->ExportCaption($this->emirates_id_copy);
					if ($this->passport_number->Exportable) $Doc->ExportCaption($this->passport_number);
					if ($this->passport_ex_date->Exportable) $Doc->ExportCaption($this->passport_ex_date);
					if ($this->passport_copy->Exportable) $Doc->ExportCaption($this->passport_copy);
					if ($this->nationality_type->Exportable) $Doc->ExportCaption($this->nationality_type);
					if ($this->nationality->Exportable) $Doc->ExportCaption($this->nationality);
					if ($this->unid->Exportable) $Doc->ExportCaption($this->unid);
					if ($this->visa_expiry_date->Exportable) $Doc->ExportCaption($this->visa_expiry_date);
					if ($this->visa_copy->Exportable) $Doc->ExportCaption($this->visa_copy);
					if ($this->personal_photo->Exportable) $Doc->ExportCaption($this->personal_photo);
					if ($this->current_emirate->Exportable) $Doc->ExportCaption($this->current_emirate);
					if ($this->date_of_birth->Exportable) $Doc->ExportCaption($this->date_of_birth);
					if ($this->full_address->Exportable) $Doc->ExportCaption($this->full_address);
					if ($this->qualifications->Exportable) $Doc->ExportCaption($this->qualifications);
					if ($this->cv->Exportable) $Doc->ExportCaption($this->cv);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->marital_status->Exportable) $Doc->ExportCaption($this->marital_status);
					if ($this->blood_type->Exportable) $Doc->ExportCaption($this->blood_type);
					if ($this->driving_licence->Exportable) $Doc->ExportCaption($this->driving_licence);
					if ($this->job->Exportable) $Doc->ExportCaption($this->job);
					if ($this->volunteering_type->Exportable) $Doc->ExportCaption($this->volunteering_type);
					if ($this->place_of_work->Exportable) $Doc->ExportCaption($this->place_of_work);
					if ($this->home_phone->Exportable) $Doc->ExportCaption($this->home_phone);
					if ($this->work_phone->Exportable) $Doc->ExportCaption($this->work_phone);
					if ($this->mobile_phone->Exportable) $Doc->ExportCaption($this->mobile_phone);
					if ($this->fax->Exportable) $Doc->ExportCaption($this->fax);
					if ($this->pobbox->Exportable) $Doc->ExportCaption($this->pobbox);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->total_voluntary_hours->Exportable) $Doc->ExportCaption($this->total_voluntary_hours);
					if ($this->overall_evaluation->Exportable) $Doc->ExportCaption($this->overall_evaluation);
					if ($this->admin_approval->Exportable) $Doc->ExportCaption($this->admin_approval);
					if ($this->admin_comment->Exportable) $Doc->ExportCaption($this->admin_comment);
					if ($this->security_approval->Exportable) $Doc->ExportCaption($this->security_approval);
					if ($this->security_comment->Exportable) $Doc->ExportCaption($this->security_comment);
					if ($this->title_number->Exportable) $Doc->ExportCaption($this->title_number);
					if ($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Exportable) $Doc->ExportCaption($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729);
				} else {
					if ($this->user_id->Exportable) $Doc->ExportCaption($this->user_id);
					if ($this->eid_expiry_date->Exportable) $Doc->ExportCaption($this->eid_expiry_date);
					if ($this->passport_ex_date->Exportable) $Doc->ExportCaption($this->passport_ex_date);
					if ($this->nationality_type->Exportable) $Doc->ExportCaption($this->nationality_type);
					if ($this->unid->Exportable) $Doc->ExportCaption($this->unid);
					if ($this->visa_expiry_date->Exportable) $Doc->ExportCaption($this->visa_expiry_date);
					if ($this->visa_copy->Exportable) $Doc->ExportCaption($this->visa_copy);
					if ($this->personal_photo->Exportable) $Doc->ExportCaption($this->personal_photo);
					if ($this->date_of_birth->Exportable) $Doc->ExportCaption($this->date_of_birth);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->marital_status->Exportable) $Doc->ExportCaption($this->marital_status);
					if ($this->blood_type->Exportable) $Doc->ExportCaption($this->blood_type);
					if ($this->driving_licence->Exportable) $Doc->ExportCaption($this->driving_licence);
					if ($this->job->Exportable) $Doc->ExportCaption($this->job);
					if ($this->volunteering_type->Exportable) $Doc->ExportCaption($this->volunteering_type);
					if ($this->overall_evaluation->Exportable) $Doc->ExportCaption($this->overall_evaluation);
					if ($this->admin_approval->Exportable) $Doc->ExportCaption($this->admin_approval);
					if ($this->security_approval->Exportable) $Doc->ExportCaption($this->security_approval);
					if ($this->title_number->Exportable) $Doc->ExportCaption($this->title_number);
					if ($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Exportable) $Doc->ExportCaption($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
						if ($this->group_id->Exportable) $Doc->ExportField($this->group_id);
						if ($this->full_name_ar->Exportable) $Doc->ExportField($this->full_name_ar);
						if ($this->full_name_en->Exportable) $Doc->ExportField($this->full_name_en);
						if ($this->emirates_id_number->Exportable) $Doc->ExportField($this->emirates_id_number);
						if ($this->eid_expiry_date->Exportable) $Doc->ExportField($this->eid_expiry_date);
						if ($this->emirates_id_copy->Exportable) $Doc->ExportField($this->emirates_id_copy);
						if ($this->passport_number->Exportable) $Doc->ExportField($this->passport_number);
						if ($this->passport_ex_date->Exportable) $Doc->ExportField($this->passport_ex_date);
						if ($this->passport_copy->Exportable) $Doc->ExportField($this->passport_copy);
						if ($this->nationality_type->Exportable) $Doc->ExportField($this->nationality_type);
						if ($this->nationality->Exportable) $Doc->ExportField($this->nationality);
						if ($this->unid->Exportable) $Doc->ExportField($this->unid);
						if ($this->visa_expiry_date->Exportable) $Doc->ExportField($this->visa_expiry_date);
						if ($this->visa_copy->Exportable) $Doc->ExportField($this->visa_copy);
						if ($this->personal_photo->Exportable) $Doc->ExportField($this->personal_photo);
						if ($this->current_emirate->Exportable) $Doc->ExportField($this->current_emirate);
						if ($this->date_of_birth->Exportable) $Doc->ExportField($this->date_of_birth);
						if ($this->full_address->Exportable) $Doc->ExportField($this->full_address);
						if ($this->qualifications->Exportable) $Doc->ExportField($this->qualifications);
						if ($this->cv->Exportable) $Doc->ExportField($this->cv);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->marital_status->Exportable) $Doc->ExportField($this->marital_status);
						if ($this->blood_type->Exportable) $Doc->ExportField($this->blood_type);
						if ($this->driving_licence->Exportable) $Doc->ExportField($this->driving_licence);
						if ($this->job->Exportable) $Doc->ExportField($this->job);
						if ($this->volunteering_type->Exportable) $Doc->ExportField($this->volunteering_type);
						if ($this->place_of_work->Exportable) $Doc->ExportField($this->place_of_work);
						if ($this->home_phone->Exportable) $Doc->ExportField($this->home_phone);
						if ($this->work_phone->Exportable) $Doc->ExportField($this->work_phone);
						if ($this->mobile_phone->Exportable) $Doc->ExportField($this->mobile_phone);
						if ($this->fax->Exportable) $Doc->ExportField($this->fax);
						if ($this->pobbox->Exportable) $Doc->ExportField($this->pobbox);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->total_voluntary_hours->Exportable) $Doc->ExportField($this->total_voluntary_hours);
						if ($this->overall_evaluation->Exportable) $Doc->ExportField($this->overall_evaluation);
						if ($this->admin_approval->Exportable) $Doc->ExportField($this->admin_approval);
						if ($this->admin_comment->Exportable) $Doc->ExportField($this->admin_comment);
						if ($this->security_approval->Exportable) $Doc->ExportField($this->security_approval);
						if ($this->security_comment->Exportable) $Doc->ExportField($this->security_comment);
						if ($this->title_number->Exportable) $Doc->ExportField($this->title_number);
						if ($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Exportable) $Doc->ExportField($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729);
					} else {
						if ($this->user_id->Exportable) $Doc->ExportField($this->user_id);
						if ($this->eid_expiry_date->Exportable) $Doc->ExportField($this->eid_expiry_date);
						if ($this->passport_ex_date->Exportable) $Doc->ExportField($this->passport_ex_date);
						if ($this->nationality_type->Exportable) $Doc->ExportField($this->nationality_type);
						if ($this->unid->Exportable) $Doc->ExportField($this->unid);
						if ($this->visa_expiry_date->Exportable) $Doc->ExportField($this->visa_expiry_date);
						if ($this->visa_copy->Exportable) $Doc->ExportField($this->visa_copy);
						if ($this->personal_photo->Exportable) $Doc->ExportField($this->personal_photo);
						if ($this->date_of_birth->Exportable) $Doc->ExportField($this->date_of_birth);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->marital_status->Exportable) $Doc->ExportField($this->marital_status);
						if ($this->blood_type->Exportable) $Doc->ExportField($this->blood_type);
						if ($this->driving_licence->Exportable) $Doc->ExportField($this->driving_licence);
						if ($this->job->Exportable) $Doc->ExportField($this->job);
						if ($this->volunteering_type->Exportable) $Doc->ExportField($this->volunteering_type);
						if ($this->overall_evaluation->Exportable) $Doc->ExportField($this->overall_evaluation);
						if ($this->admin_approval->Exportable) $Doc->ExportField($this->admin_approval);
						if ($this->security_approval->Exportable) $Doc->ExportField($this->security_approval);
						if ($this->title_number->Exportable) $Doc->ExportField($this->title_number);
						if ($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729->Exportable) $Doc->ExportField($this->Date_Format28users_date_of_birth2C_2725Y_25m_25d2729);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
