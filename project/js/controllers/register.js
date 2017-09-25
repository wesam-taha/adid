
// Form Controller

mediasoftApp.angular.controller('registerController', ['$rootScope', '$http', '$timeout', '$scope', '$routeParams', '$location', 'mediasoftHTTP', 'localService', 'jangularvalidate', '$filter', function ($rootScope, $http, $timeout, $scope, $routeParams, $location, mediasoftHTTP, localService, jangularvalidate, $filter) {


        $scope.errorMessage = 0;

        $scope.doLogin = function () {
            $scope.email = $('#user-email').val();
            $scope.password = $('#user-password').val();
            
            $scope.loginFn($scope.email,$scope.password,true);


        }


        $scope.clear = function(){ 
             $scope.message = "";
        }



        $scope.loginFn = function(email,password,redirect){

              $http.get(apiPath + "login.php?email=" + email + '&password=' + password + '&type=users')
                    .success(function (response) {
                        if (response != 0) {
                            $scope.logged = response.logged;
                            localStorageData('selectedID', $scope.logged['user_id']);
                            if(redirect === true){
                            $location.path('/home');
                            }
                            $scope.errorMessage = 0;
                        } else {
                            localStorageData('selectedID', 0);
                            $scope.errorMessage = 1;
                        }
                    })
        }

        $scope._ = function(code){
            try { 

            for(var i = 0; i < $scope.translations.length; i++)
                {
                  if($scope.translations[i].label_text == code)
                  { 
                    if($rootScope.currentLang == 'ar'){ 
                    return $scope.translations[i].genuine_text_ar;
                    }else{
                         return $scope.translations[i].genuine_text_en;
                    }    
                  }
                }

            }catch(err) {
                //console.log(err.message);
            }    

        }        



        $scope.emptyInputs = function () {
            $timeout(function () {
                $('#user-email').val('');
                $('#user-password').val('');
            }, 300);
        }

        $scope.openWindow = function (link) {
            window.open(link, '_system', 'location=yes');
        }

        $scope.getGlobalSettings = function () {
            $scope.globalDone = false;
            $http.get(apiPath + "global.php?user_id=" + $scope.userLoggedIn, {cache: true})
                    .success(function (response) {
                        $scope.globalDone = true;
                        $scope.global = response.global;
                        $scope.groups = response.groups;
                        $scope.translations = response.translations;
                    })

        }

        $rootScope.selectedID = localStorageData('selectedID') > 0 ? localStorageData('selectedID') : 0;

        $scope.nextVal = 1;
        // alert message
        $scope.messagecss = "alert-danger";
        $scope.message = "";
        // show loader
        $scope.showProcessing = false;

        // route param processing
        if (typeof $routeParams != 'undefined') {

            if (typeof $routeParams.status != 'undefined')
                $scope.message = localService.getStatusMessage($routeParams.status);

            if (typeof $routeParams.id != 'undefined')
                $rootScope.selectedID = $routeParams.id;
        }
        // edit or update settings
        if ($rootScope.selectedID > 0) {
            $scope.HeaderMessage = "Update Information";
        } else {
            $scope.HeaderMessage = "Add New Record";
        }

        // Core form variables
        $scope.Form = {};
        $scope.formConfig = {};
        // record object (initialize with default values)


        $scope.Info = {
            id: $rootScope.selectedID,
            group_id: "",
            full_name_ar: "",
            full_name_en: "",
            date_of_birth: "",
            email: "",
            password: "",
            emirates_id_number: "",
            passport_number: "",
            passport_ex_date: "",
            nationality: "",
            current_emirate: "",
            full_address: "",
            gender: "",
            marital_status: "",
            blood_type: "",
            driving_licence: "",
            job: "",
            place_of_work: "",
            volunteering_type: "",
            home_phone: "",
            work_phone: "",
            mobile_phone: "",
            fax: "",
            pobbox: "",
            passports: [],
            emiratesIDs: [],
            tradeLicence: [],
            personalPhoto: [],
            visaCopy: [],
            cvCopy: [],
            nationality_type: "",
            unid: "",
            eid_expiry_date: "",
            visa_expiry_date: "",

            qualifications: "",




        };
        $scope.Info.emiratesIDs = [];
        $scope.Info.passports = [];
        $scope.Info.tradeLicence = [];
        $scope.Info.personalPhoto = [];
        $scope.Info.visaCopy = [];
        $scope.Info.cvCopy = [];
        
        // success callback when data retrieved
        var loadInfoSuccess = function (data, status) {
            $scope.showProcessing = false;
            var isObj = data instanceof Object;
            if (!isObj) {
                $scope.message = "Error occured while processing your request";
            } else if (data.status == 'error') {
                $scope.message = data.message;
            } else {
                if (typeof data.record != "undefined") {
                    $scope.Info = data.record;
                    console.log($scope.Info);
                    // initialize and generate dynamic form
                    prepareForm();
                } else
                    $scope.message = "Failed to open records";
            }
        };

        // error handling callback
        var loadError = function (data, status, headers, config) {
            $scope.message = "Error occured";
        }

        // function responsible for loading record data in case of update operation.
        function fetchInfo() {
            $scope.showProcessing = true;
            var url = apiPath + "profile.php";
            mediasoftHTTP.actionProcess(url, [{
                    user_id: $rootScope.selectedID,
                    type: 'users',

                }])
                    .success(loadInfoSuccess)
                    .error(loadError);
        }

        if ($rootScope.selectedID > 0) {
            // in case of update first fetch data then prepare form after data loaded
            fetchInfo();
        } else {
            // in case of insert, directly initialize form
            prepareForm();
        }

        function prepareForm() {

            $scope.Form = {
                header: $scope.HeaderMessage,
                formAttributes: [
                    {
                        index: 0,
                        id: "group_id",
                        value: $scope.Info.group_id,
                        type: "dropdown",


                    },
                    {
                        index: 1,
                        id: "full_name_ar",
                        value: $scope.Info.full_name_ar,
                        type: "textbox",
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage_ar: 'الرجاء إدخال اسمك الكامل بالعربية',
                            requiredMessage: 'Please Enter Your Arabic Full Name',
                        }                        
                    },
                    {
                        index: 2,
                        id: "full_name_en",
                        value: $scope.Info.full_name_en,
                        currentLang:$rootScope.currentLang,
                        type: "textbox",
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage_ar: 'الرجاء إدخال اسمك الكامل بالإنكليزية',
                            requiredMessage: 'Please Enter Your English Full Name',
                        }  
                    },
                    {
                        index: 3,
                        id: "date_of_birth",
                        type: "textbox",
                        value: $scope.Info.date_of_birth,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage_ar: 'الرجاء إدخال تاريخ الميلاد',
                            requiredMessage: "Please Enter Your Date Of Birth",
                        }                          
                    },
                    {
                        index: 4,
                        id: "gender",
                        value: $scope.Info.gender,
                        type: "dropdown",
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Choose Your Gender",
                            requiredMessage_ar: 'الرجاء اختيار الجنس',
                        }                          
                    },
                    {
                        index: 5,
                        id: "email",
                        type: "textbox",
                        value: $scope.Info.email,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter Email Address",
                            requiredMessage_ar: 'الرجاء إدخال البريد الألكتروني'
                        }
                    },
                    {
                        index: 6,
                        id: "password",
                        type: "textbox",
                        value: $scope.Info.password,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter Account Password",
                            requiredMessage_ar: 'الرجاء إدخال كلمة المرور',
                        }
                    },
                    {
                        index: 7,
                        id: "personalPhoto",
                        template: localService.personalPhotoUploaderTemplate(),
                        settings: localService.getUploadOptions(),
                        type: "personalPhotoUploadFiles",
                        value: $scope.Info.personalPhoto,
                        maxfiles: 1,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Upload Personal Photo",
                            requiredMessage_ar: 'الرجاء تحميل صورة شخصية',
                        }
                    },
                    
                    {
                        index: 8,
                        id: "nationality_type",
                        type: "dropdown",
                        value: $scope.Info.nationality_type,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Choose Your Nationality Type",
                            requiredMessage_ar: 'الرجاء اختيار نوع الجنسية',

                        }                          
                    },
                    {
                        index: 9,
                        id: "nationality",
                        type: "dropdown",
                        value: $scope.Info.nationality,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter Nationality",
                            requiredMessage_ar: 'الرجاء ادخال الجنسية',

                        }
                     },
                     {
                        index: 10,
                        id: "unid",
                        type: "textbox",
                        value: $scope.Info.unid,
                    },   {
 
                        index: 11,
                        id: "visa_expiry_date",
                        type: "textbox",
                        value: $scope.Info.visa_expiry_date,
                    },
                     {
                        index: 12,
                        id: "visaCopy",
                        template: localService.visaCopyUploaderTemplate(),
                        settings: localService.getUploadOptions(),
                        type: "visaCopyUploadFiles",
                        value: $scope.Info.visaCopy,
                        maxfiles: 1,
                        validate: {
                            required: false,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Upload Visa Copy",
                            requiredMessage_ar: 'الرجاء تحميل صورة اللإقامة',
                        }
                    },  
                    {
                        index: 13,
                        id: "current_emirate",
                        value: $scope.Info.current_emirate,
                        type: "dropdown",
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Choose Your Current Emirate",
                            requiredMessage_ar: 'الرجاء اختيار الإمارة الحالية',
                        }                          
                    },
                    {
                        index: 14,
                        id: "full_address",
                        type: "textbox",
                        value: $scope.Info.full_address,
                    },

                      {
                        index: 15,
                        id: "emirates_id_number",
                        type: "textbox",
                        value: $scope.Info.emirates_id_number,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter Emirates ID Number",
                            requiredMessage_ar: 'الرجاء إدخال رقم الهوية',
                        }
                    },

                    {
                        index: 16,
                        id: "eid_expiry_date",
                        type: "textbox",
                        value: $scope.Info.eid_expiry_date,
                    }, 
                  
                    {
                        index: 17,
                        id: "emiratesIDs",
                        template: localService.emiratesIDsUploaderTemplate(),
                        settings: localService.getUploadOptions(),
                        type: "emiratesIDsUploadFiles",
                        value: $scope.Info.emiratesIDs,
                        maxfiles: 2,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Upload Emirates ID Copy",
                            requiredMessage_ar: 'الرجاء تحميل صورة الهوية',
                        }
                    },

                    {
                        index: 18,
                        id: "passport_number",
                        type: "textbox",
                        value: $scope.Info.passport_number,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter passport Number",
                            requiredMessage_ar: 'الرجاء إدخال رقم جواز السفر',
                        }
                    },
                    {
                        index: 19,
                        id: "passport_ex_date",
                        type: "textbox",
                        value: $scope.Info.passport_ex_date,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter passport expiery date",
                            requiredMessage_ar: 'الرجاء ادخال تاريخ انتهاء جواز السفر',

                        }
                    },

                    {
                        index: 20,
                        id: "passports",
                        display: "Select Files",
                        placeHolder: "Enter picturename",
                        template: localService.passportsUploaderTemplate(),
                        settings: localService.getUploadOptions(),
                        type: "passportUploadFiles",
                        value: $scope.Info.passports,
                        maxfiles: 3,
                        validate: {
                            required: true,
                            currentLang:$rootScope.currentLang,
                            type: 'string',
                            requiredMessage: "Please Upload passport copy",
                            requiredMessage_ar: 'الرجاء تحميل صورة جواز السفر',

                        }
                    },

                   
                    {
                        index: 21,
                        id: "marital_status",
                        value: $scope.Info.marital_status,
                        type: "dropdown"

                    },
                    {
                        index: 22,
                        id: "blood_type",
                        value: $scope.Info.blood_type,
                        type: "dropdown",
                        multiple:true,

                    },
                    {
                        index: 23,
                        id: "driving_licence",
                        value: $scope.Info.driving_licence,
                        type: "dropdown"

                    },
                    {
                        index: 24,
                        id: "job",
                        value: $scope.Info.job,
                        type: "dropdown"

                    },
                    {
                        index: 25,
                        id: "place_of_work",
                        type: "textbox",
                        value: $scope.Info.place_of_work,
                    },
                    {
                        index: 26,
                        id: "volunteering_type",
                        type: "dropdown",
                        value: $scope.Info.volunteering_type,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Choose Your Volunteering Type",
                            requiredMessage_ar: 'الرجاء اختيار نوع التطوع',

                        }                          
                    },
                    {
                        index: 27,
                        id: "home_phone",
                        type: "textbox",
                        value: $scope.Info.home_phone,
                    },
                    {
                        index: 28,
                        id: "work_phone",
                        type: "textbox",
                        value: $scope.Info.work_phone,
                    },
                    {
                        index: 29,
                        id: "mobile_phone",
                        type: "textbox",
                        value: $scope.Info.mobile_phone,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter Your mobile Number",
                            requiredMessage_ar: 'الرجاء ادخال رقم الجوال',

                        }                          
                    },
                    {
                        index: 30,
                        id: "fax",
                        type: "textbox",
                        value: $scope.Info.fax,
                    },
                    {
                        index: 31,
                        id: "pobbox",
                        type: "textbox",
                        value: $scope.Info.pobbox,
                    },
                    
                    
                    
                    
                    {
                        index: 32,
                        id: "cvCopy",
                        template: localService.cvCopyUploaderTemplate(),
                        settings: localService.getUploadOptions(),
                        type: "cvCopyUploadFiles",
                        value: $scope.Info.cvCopy,
                        maxfiles: 1,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Upload CV Copy",
                            requiredMessage_ar: 'الرجاء تحميل السيرة الذاتية',

                        }
                    }, 
                    {
                        index: 33,
                        id: "qualifications",
                        type: "textbox",
                        value: $scope.Info.qualifications,
                    }, 


                    
                ]
            };

            $scope.formConfig = $scope.Form.formAttributes;
            $scope.group_id = $scope.formConfig[0];
            $scope.full_name_ar = $scope.formConfig[1];
            $scope.full_name_en = $scope.formConfig[2];
            $scope.date_of_birth = $scope.formConfig[3];
            $scope.gender = $scope.formConfig[4];
            $scope.email = $scope.formConfig[5];
            $scope.password = $scope.formConfig[6];
            $scope.personalPhoto = $scope.formConfig[7];
            $scope.nationality_type = $scope.formConfig[8];
            $scope.nationality = $scope.formConfig[9];
            $scope.unid = $scope.formConfig[10];
            $scope.visa_expiry_date = $scope.formConfig[11];
            $scope.visaCopy = $scope.formConfig[12];
            $scope.current_emirate = $scope.formConfig[13];
            $scope.full_address = $scope.formConfig[14];
            $scope.emirates_id_number = $scope.formConfig[15];
            $scope.eid_expiry_date = $scope.formConfig[16];
            $scope.emiratesIDs = $scope.formConfig[17];
            $scope.passport_number = $scope.formConfig[18];
            $scope.passport_ex_date = $scope.formConfig[19];
            $scope.passports = $scope.formConfig[20];
            $scope.marital_status = $scope.formConfig[21];
            $scope.blood_type = $scope.formConfig[22];
            $scope.driving_licence = $scope.formConfig[23];
            $scope.job = $scope.formConfig[24];
            $scope.place_of_work = $scope.formConfig[25];
            $scope.volunteering_type = $scope.formConfig[26];
            $scope.home_phone = $scope.formConfig[27];
            $scope.work_phone = $scope.formConfig[28];
            $scope.mobile_phone = $scope.formConfig[29];
            $scope.fax = $scope.formConfig[30];
            $scope.pobbox = $scope.formConfig[31];
            $scope.cvCopy = $scope.formConfig[32];
            $scope.qualifications = $scope.formConfig[33];





            // $scope.tardeLicence  =  $scope.formConfig[12]; 






        }

        /* callback, retrieve uploaded filenames */
        $scope.getPassportsUploadedData = function (_data) {
            if(!angular.isDefined($scope.Info.passports)){ $scope.Info.passports = []; }
            angular.forEach(_data, function (item) {
                $scope.Info.passports.push(item);
            });
            passportsUploadedFiles = $scope.Info.passports.length;
            console.log("Passports Uploaded data is");
            console.log($scope.Info.passports);
        };




        $scope.getEmiratesIDsUploadedData = function (_data) {
            if(!angular.isDefined($scope.Info.emiratesIDs)){ $scope.Info.emiratesIDs = []; }
            angular.forEach(_data, function (item) {
                $scope.Info.emiratesIDs.push(item);
            });
            emiratesIDsUploadedFiles = $scope.Info.emiratesIDs.length;
            console.log("emiratesIDs uploaded data is");
            console.log($scope.Info.emiratesIDs);
        };

        $scope.getTradeLicenceUploadedData = function (_data) {
            if(!angular.isDefined($scope.Info.tradeLicence)){ $scope.Info.tradeLicence = []; }
            angular.forEach(_data, function (item) {
                console.log(item);
                $scope.Info.tradeLicence.push(item);
            });
            tradeLicenceUploadedFiles = $scope.Info.tradeLicence.length;
            console.log("tradeLicence uploaded data is");
            console.log($scope.Info.tradeLicence);
        };


        $scope.getPersonalPhotoUploadedData = function (_data) {
            if(!angular.isDefined($scope.Info.personalPhoto)){ $scope.Info.personalPhoto = []; }
            angular.forEach(_data, function (item) {
                console.log(item);
                $scope.Info.personalPhoto.push(item);
            });
            personalPhotoUploadedFiles = $scope.Info.personalPhoto.length;
            console.log("personalPhoto uploaded data is");
            console.log($scope.Info.personalPhoto);
        };



        $scope.getCvCopyUploadedData = function (_data) {
            if(!angular.isDefined($scope.Info.cvCopy)){ $scope.Info.cvCopy = []; }
            angular.forEach(_data, function (item) {
                console.log(item);
                $scope.Info.cvCopy.push(item);
            });
            cvCopyUploadedFiles = $scope.Info.cvCopy.length;
            console.log("cvCopy uploaded data is");
            console.log($scope.Info.cvCopy);
        };


        $scope.getVisaCopyUploadedData = function (_data) {
            if(!angular.isDefined($scope.Info.visaCopy)){ $scope.Info.visaCopy = []; }
            angular.forEach(_data, function (item) {
                console.log(item);
                $scope.Info.visaCopy.push(item);
            });
            visaCopyUploadedFiles = $scope.Info.visaCopy.length;
            console.log("visaCopy uploaded data is");
            console.log($scope.Info.visaCopy);
        };


        // form input validation in real time
        $scope.validate = function (obj) {
            if (typeof obj.validate != "undefined") {
                var output = jangularvalidate.validate(obj.validate, obj.value, obj.values);
                obj.css = output.css;
                obj.errorMsg = output.message;
                obj.isvalidated = output.isvalid;
            }
        };

        // remove selected file
        $scope.remove = function (filename, index, type) {
            if (filename != '') {
                if (type == 'passports') {
                    $scope.Info.passports.splice(index, 1);
                    passportsUploadedFiles = $scope.Info.passports.length;
                    } else if (type == 'emiratesIDs') {
                    $scope.Info.emiratesIDs.splice(index, 1);
                    emiratesIDsUploadedFiles = $scope.Info.emiratesIDs.length;
                    } else if (type == 'personalPhoto') {
                    $scope.Info.personalPhoto.splice(index, 1);
                    personalPhotoUploadedFiles = $scope.Info.personalPhoto.length;
                    } else if (type == 'cvCopy') {
                    $scope.Info.cvCopy.splice(index, 1);
                    cvCopyUploadedFiles = $scope.Info.cvCopy.length;
                    } else if (type == 'visaCopy') {
                    $scope.Info.visaCopy.splice(index, 1);
                    visaCopyUploadedFiles = $scope.Info.visaCopy.length;                    
                } else {
                    $scope.Info.tradeLicence.splice(index, 1);
                    tradeLicenceUploadedFiles = $scope.Info.tradeLicence.length;
                }

                // reset uploaded files counter
                mediasoftHTTP.actionProcess(localService.getUploadOptions().removehandler, [{
                        file: filename
                    }])
                        .success(removeSuccess)
                        .error(actionError);
            }
        };

        var removeSuccess = function (data, status) {
            console.log(data)

        };
        var actionError = function (data, status, headers, config) {
            $scope.message = "Error occured";
        }

        //**********************************************************/
        // Close Form Config
        //**********************************************************/
        var processSuccess = function (data, status) {
            $scope.showProcessing = false;
            var isObj = data instanceof Object;
            if (!isObj) {
                $scope.message = "Error occured while processing your request";
            } else if (data.status == 'error') {
                $scope.message = data.message;
            } else {
                if ($rootScope.selectedID > 0)
                    $location.path("/updateSuccess");
                else
                    $location.path("/addSuccess");
            }
        };

        var processError = function (data, status, headers, config) {
            $scope.message = "Error occured";
        }

        $scope.next = function () {
            $scope.nextVal++;
            console.log($scope.blood_type);

        }
        $scope.prev = function () {
            $scope.nextVal--;
        }

        /* form submission logic */
        $scope.create = function () {
            
            $scope.validationLog = jangularvalidate.formvalidate($scope.formConfig);
            if ($scope.validationLog.length == 0) {
                // validation ok
                // fetch data from form object

                // initialize object with default values
                var _data = [{
                        id: $rootScope.selectedID,
                        group_id: "",
                        full_name_ar: "",
                        full_name_en: "",
                        date_of_birth: "",
                        email: "",
                        password: "",
                        emirates_id_number: "",
                        passport_number: "",
                        passport_ex_date: "",
                        nationality: "",
                        current_emirate: "",
                        full_address: "",
                        gender: "",
                        marital_status: "",
                        blood_type: "",
                        driving_licence: "",
                        job: "",
                        place_of_work: "",
                        volunteering_type: "",
                        home_phone: "",
                        work_phone: "",
                        mobile_phone: "",
                        fax: "",
                        pobbox: "",
                        passports: $scope.Info.passports,
                        emiratesIDs: $scope.Info.emiratesIDs,
                        personalPhoto: $scope.Info.personalPhoto,
                        visaCopy: $scope.Info.visaCopy,
                        cvCopy: $scope.Info.cvCopy,
                        nationality_type: "",
                        unid: "",
                        eid_expiry_date: "",
                        qualifications: "",
                        visa_expiry_date: "",


                        //tradeLicence: $scope.Info.tradeLicence,
                    }];


                // fetch data and populate _data object with real input data
                for (var i = 0; i <= $scope.formConfig.length - 1; i++) {
                    var _val = $scope.formConfig[i];
                    console.log($scope.formConfig);
                    _data[0].user_id = $rootScope.selectedID;
                    _data[0].type = 'users';
                    switch (_val.id) {
                        case "group_id":
                            _data[0].group_id = _val.value;
                            break;
                        case "full_name_ar":
                            _data[0].full_name_ar = _val.value;
                            break;
                        case "full_name_en":
                            _data[0].full_name_en = _val.value;
                            break;
                        case "date_of_birth":
                            _data[0].date_of_birth = _val.value;
                            break;
                        case "email":
                            _data[0].email = _val.value;
                            break;
                        case "password":
                            _data[0].password = _val.value;
                            break;
                        case "emirates_id_number":
                            _data[0].emirates_id_number = _val.value;
                            break;
                        case "emiratesIDs":
                            _data[0].emiratesIDs = _val.value;
                            break;
                        case "passport_number":
                            _data[0].passport_number = _val.value;
                            break;
                        case "passport_ex_date":
                            _data[0].passport_ex_date = _val.value;
                            break;
                        case "blood_type":
                            _data[0].blood_type = _val.value;
                            break;
                        case "passports":
                            _data[0].passports = _val.value;
                            break;
                        case "nationality":
                            _data[0].nationality = _val.value;
                            break;
                        case "current_emirate":
                            _data[0].current_emirate = _val.value;
                            break;
                        case "full_address":
                            _data[0].full_address = _val.value;
                            break;
                        case "gender":
                            _data[0].gender = _val.value;
                            break;
                        case "marital_status":
                            _data[0].marital_status = _val.value;
                            break;
                        case "driving_licence":
                            _data[0].driving_licence = _val.value;
                            break;
                        case "job":
                            _data[0].job = _val.value;
                            break;
                        case "place_of_work":
                            _data[0].place_of_work = _val.value;
                            break;
                        case "volunteering_type":
                            _data[0].volunteering_type = _val.value;
                            break;
                        case "home_phone":
                            _data[0].home_phone = _val.value;
                            break;
                        case "work_phone":
                            _data[0].work_phone = _val.value;
                            break;
                        case "mobile_phone":
                            _data[0].mobile_phone = _val.value;
                            break;
                        case "fax":
                            _data[0].fax = _val.value;
                            break;
                        case "pobbox":
                            _data[0].pobbox = _val.value;
                            break;
                        case "personalPhoto":
                            _data[0].personalPhoto = $scope.Info.personalPhoto;
                            break;

                        case "visaCopy":
                            _data[0].visaCopy = $scope.Info.visaCopy;
                            break;
                        case "cvCopy":
                            _data[0].cvCopy = $scope.Info.cvCopy;
                            break;
                        case "nationality_type":
                            _data[0].nationality_type = _val.value;
                            break;
                        case "unid":
                            _data[0].unid = _val.value;
                            break;
                        case "eid_expiry_date":
                            _data[0].eid_expiry_date = _val.value;
                            break; 
                        case "qualifications":
                            _data[0].qualifications = _val.value;
                            break;  
                        case "visa_expiry_date":
                            _data[0].visa_expiry_date = _val.value;
                            break;  

                    }
                }

                // review data
                console.log('Submit Data');
                console.log(_data);

                // submit data to server
                // enable if you practically implement it
                $scope.showProcessing = true;
                var url = apiPath + "profile.php";
                mediasoftHTTP.actionProcess(url, _data)
                        .success(processSuccess)
                        .error(processError);
                console.log($rootScope.selectedID);
                setTimeout(function() {  
                $scope.loginFn(_data[0].email,_data[0].password,false);

                if ($rootScope.selectedID > 0) {
                    fetchInfo();
                    $location.path("updateSuccess");
                } else {
                    $location.path("addSuccess");
                }
                }, 1000);
            } else {
                $scope.message = "Validation Errors, Please fix it";
            }

        };
    }])



var isLocalStorageSupported = function () {
    try {
        localStorage.setItem('supportTest', '1');
        localStorage.removeItem('supportTest');
        return true;
    } catch (e) {
        return false;
    }
}();

var localStorageData = function (variable, data) {
    if (typeof data !== 'undefined') {
        /* Setting data to local storage */
        if (isLocalStorageSupported) {
            localStorage.setItem(variable, JSON.stringify(data));
        } else {
            setCookie(variable, JSON.stringify(data), 365);
        }
        return true;
    } else {
        /* Getting data from local storage */
        if (isLocalStorageSupported) {
            var data = localStorage.getItem(variable);
            return JSON.parse(data);
        } else {
            var data = getCookie(variable);
            try {
                return JSON.parse(data);
            } catch (e) {
                return null;
            }
        }
    }
};