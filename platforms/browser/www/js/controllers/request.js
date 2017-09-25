// Form Controller

mediasoftApp.angular.controller('requestController', ['$rootScope', '$http', '$timeout', '$scope', '$routeParams', '$location', 'mediasoftHTTP', 'localService', 'jangularvalidate', '$filter', function ($rootScope, $http, $timeout, $scope, $routeParams, $location, mediasoftHTTP, localService, jangularvalidate, $filter) {


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

            $http.get(apiPath + "global.php?user_id=" + $scope.userLoggedIn, {cache: true})
                    .success(function (response) {

                        $scope.global = response.global;
                        $scope.groups = response.groups;
                        $scope.translations = response.translations;
                        console.log($scope.groups);
                    })

        }

        $rootScope.selectedID = localStorageData('selectedID') > 0 ? localStorageData('selectedID') : 0;

            $scope.regbutton = 'تقديم طلب الفعالية';
      

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
            event_name: "",
            event_emirate: "",
            event_location: "",
            activity_start_date: "",
            activity_end_date: "",
            activity_time: "",
            activity_description: "",
            activity_gender_target: "",
            no_of_persons_needed: "",
            no_of_hours: "",
            mobile_phone: "",
            fax: "",
            pobox: "",
        };


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
                    // initialize and generate dynamic form
                    prepareForm();
                } else
                    $scope.message = "Failed to open records";
            }
        };


        // in case of insert, directly initialize form
        prepareForm();


        function prepareForm() {
            $scope.Form = {
                header: $scope.HeaderMessage,
                formAttributes: [

                    {
                        index: 0,
                        id: "event_name",
                        value: $scope.Info.event_name,
                        type: "textbox",
                        validate: {
                            required: true,
                            currentLang:$rootScope.currentLang,
                            type: 'string',
                            requiredMessage: "Please Enter  Event Name",
                            requiredMessage_ar: 'الرجاء إدخال اسم الفعالية',

                        }                        
                    },

                    {
                        index: 1,
                        id: "event_emirate",
                        type: "dropdown",
                        value: $scope.Info.event_emirate,
                        validate: {
                            required: true,
                            currentLang:$rootScope.currentLang,
                            type: 'string',
                            requiredMessage: "Please Choose  Activity Emirate",
                            requiredMessage_ar: 'الرجاء اختيار الإمارة',

                        }                          
                    },
                    {
                        index: 2,
                        id: "event_location",
                        type: "textbox",
                        value: $scope.Info.event_location,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter  Activity Location",
                            requiredMessage_ar: 'الرجاء إدخال موقع الفعالية',

                        }                          
                    },
                    {
                        index: 3,
                        id: "activity_start_date",
                        type: "textbox",
                        value: $scope.Info.activity_start_date,
                        validate: {
                            required: true,
                            currentLang:$rootScope.currentLang,
                            type: 'string',
                            requiredMessage: "Please Enter  Activity Start Date",
                            requiredMessage_ar: 'الرجاء إدخال تاريخ بدء الفعالية',

                        }                          
                    },
                    {
                        index: 4,
                        id: "activity_end_date",
                        type: "textbox",
                        value: $scope.Info.activity_end_date,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter  Activity End Date",
                            requiredMessage_ar: 'الرجاء إدخال تاريخ انتهاء الفعالية',

                        }                          
                    },                                        
                    {
                        index: 5,
                        id: "activity_time",
                        type: "textbox",
                        value: $scope.Info.activity_time,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter  Activity Time",
                            requiredMessage_ar: 'الرجاء إدخال توقيت الفعالية',

                        }                          
                    },
                    {
                        index: 6,
                        id: "activity_description",
                        type: "textbox",
                        value: $scope.Info.activity_description,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter  Activity Description",
                            requiredMessage_ar: 'الرجاء إدخال تفاصيل الفعالية',

                        }                          
                    },
                    {
                        index: 7,
                        id: "activity_gender_target",
                        type: "dropdown",
                        value: $scope.Info.activity_gender_target,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter  Activity Gender Target",
                            requiredMessage_ar: 'الرجاء اختيار الفئة المستهدفة',

                        }                          
                    }, 
                    {
                        index: 8,
                        id: "no_of_persons_needed",
                        type: "textbox",
                        value: $scope.Info.no_of_persons_needed,
                        validate: {
                            required: true,
                            type: 'string',
                            requiredMessage: "Please Enter  No Of Persons Needed",
                            currentLang:$rootScope.currentLang,
                            requiredMessage_ar: 'الرجاء إدخال عدد الأشخاص المطلوب',

                        }                          
                    },
                    {
                        index: 9,
                        id: "no_of_hours",
                        type: "textbox",
                        value: $scope.Info.no_of_hours,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter No Of Hours",
                            requiredMessage_ar: 'الرجاء إدخال عدد الساعات المطلوب',

                        }                          
                    },
                    {
                        index: 10,
                        id: "mobile_phone",
                        type: "textbox",
                        value: $scope.Info.mobile_phone,
                        validate: {
                            required: true,
                            type: 'string',
                            currentLang:$rootScope.currentLang,
                            requiredMessage: "Please Enter Your Mobile No",
                            requiredMessage_ar: 'الرجاء إدخال رقم الجوال',

                        }                          
                    },
                    {
                        index: 11,
                        id: "fax",
                        type: "textbox",
                        value: $scope.Info.fax,
                    },

                    {
                        index: 12,
                        id: "pobox",
                        type: "textbox",
                        value: $scope.Info.pobox,
                    },




                ]
            };

            $scope.formConfig = $scope.Form.formAttributes;
            $scope.event_name = $scope.formConfig[0];
            $scope.event_emirate = $scope.formConfig[1];
            $scope.event_location = $scope.formConfig[2];
            $scope.activity_start_date = $scope.formConfig[3];
            $scope.activity_end_date = $scope.formConfig[4];
            $scope.activity_time = $scope.formConfig[5];
            $scope.activity_description = $scope.formConfig[6];
            $scope.activity_gender_target = $scope.formConfig[7];
            $scope.no_of_persons_needed = $scope.formConfig[8];
            $scope.no_of_hours = $scope.formConfig[9];
            $scope.mobile_phone = $scope.formConfig[10];
            $scope.fax = $scope.formConfig[11];
            $scope.pobox = $scope.formConfig[12];
   



            // $scope.tardeLicence  =  $scope.formConfig[12]; 






        }



        // form input validation in real time
        $scope.validate = function (obj) {
            if (typeof obj.validate != "undefined") {
                var output = jangularvalidate.validate(obj.validate, obj.value, obj.values);
                obj.css = output.css;
                obj.errorMsg = output.message;
                obj.isvalidated = output.isvalid;
            }
        };




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
                        institutions_id: $rootScope.selectedID,
                        event_name: "",
                        event_emirate: "",
                        event_location: "",
                        activity_start_date: "",
                        activity_end_date: "",
                        activity_time: "",
                        activity_description: "",
                        activity_gender_target: "",
                        no_of_persons_needed: "",
                        no_of_hours: "",
                        mobile_phone: "",
                        fax: "",
                        pobox: "",
                    }];

                // fetch data and populate _data object with real input data
                for (var i = 0; i <= $scope.formConfig.length - 1; i++) {
                    var _val = $scope.formConfig[i];
                    console.log($scope.formConfig);
                    _data[0].user_id = $rootScope.selectedID;
                    _data[0].type = 'users';

                    switch (_val.id) {
                        case "institutions_id":
                            _data[0].institutions_id = _val.value;
                            break;                        
                        case "event_name":
                            _data[0].event_name = _val.value;
                            break;
                        case "event_emirate":
                            _data[0].event_emirate = _val.value;
                            break;
                        case "event_location":
                            _data[0].event_location = _val.value;
                            break;
                        case "activity_start_date":
                            _data[0].activity_start_date = _val.value;
                            break;
                        case "activity_end_date":
                            _data[0].activity_end_date = _val.value;
                            break;
                        case "activity_time":
                            _data[0].activity_time = _val.value;
                            break;
                        case "activity_description":
                            _data[0].activity_description = _val.value;
                            break;
                        case "activity_gender_target":
                            _data[0].activity_gender_target = _val.value;
                            break;
                        case "no_of_persons_needed":
                            _data[0].no_of_persons_needed = _val.value;
                            break;
                        case "no_of_hours":
                            _data[0].no_of_hours = _val.value;
                            break;
                        case "mobile_phone":
                            _data[0].mobile_phone = _val.value;
                            break;
                        case "fax":
                            _data[0].fax = _val.value;
                            break;
                        case "pobox":
                            _data[0].pobox = _val.value;
                            break;
                        
                    }
                }

                // review data
                console.log('Submit Data');
                console.log(_data);

                // submit data to server
                // enable if you practically implement it
                $scope.showProcessing = true;
                var url = apiPath + "request.php";
                mediasoftHTTP.actionProcess(url, _data)
                        .success(processSuccess)
                        .error(processError);
                console.log($rootScope.selectedID);

                    $location.path("sendSuccess");

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