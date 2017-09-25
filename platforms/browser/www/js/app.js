var rootPath = "";
var templatePath = rootPath + "views";
var apiPath = "http://aladheid.ae/api/";
var defaultimagePath = "http://aladheid.ae/images/nothumb.png";
var imagedirectoryPath = "http://aladheid.ae/images/";
var passportsUploadedFiles = 0; // count stats of uploaded files
var emiratesIDsUploadedFiles = 0; // count stats of uploaded files
var tradeLicenceUploadedFiles = 0; // count stats of uploaded files
var personalPhotoUploadedFiles = 0; // count stats of uploaded files
var cvCopyUploadedFiles = 0; // count stats of uploaded files
var visaCopyUploadedFiles = 0; // count stats of uploaded files

var mediasoftApp = {};

mediasoftApp.angular = angular.module('mediasoftApp', ['ngSanitize','ksSwiper', 'ngRoute', 'httpServices', 'programServices', 'jugnoon-validate', 'jPagination', 'localytics.directives']);

mediasoftApp.fw7 = {app: new Framework7({swipePanel: 'left', swipePanelActiveArea: 50, animateNavBackIcon: true}), options: {dynamicNavbar: true, domCache: true}, views: []};



mediasoftApp.angular.run(function ($rootScope, $templateCache) {
    $rootScope.currentLang = localStorageData('currentLang') == null ? localStorageData('currentLang', 'en') : localStorageData('currentLang');
    $rootScope.isHome = true;
    $rootScope.isHomePage = true
    $rootScope.baseURL = imagedirectoryPath;

    $rootScope.$on('$viewContentLoaded', function () {
        $templateCache.removeAll();
    });
});


mediasoftApp.angular.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
                when('/', {
                    templateUrl: rootPath + "views/lang.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/home', {
                    templateUrl: rootPath + "views/home.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/about', {
                    templateUrl: rootPath + "views/about.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/contact', {
                    templateUrl: rootPath + "views/contact.html?i=" + Math.random(),
                    controller: 'mainController'
                }).                                
                when('/profile/:id', {
                    templateUrl: rootPath + "views/register.html?i=" + Math.random(),
                    controller: 'registerController'
                }).
                when('/loginView', {
                    templateUrl: rootPath + "views/loginView.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/login', {
                    templateUrl: rootPath + "views/login.html?i=" + Math.random(),
                    controller: 'registerController'
                }).
                when('/ins-login', {
                    templateUrl: rootPath + "views/ins-login.html?i=" + Math.random(),
                    controller: 'insRegisterController'
                }).  
                when('/ins-register', {
                    templateUrl: rootPath + "views/ins-register.html?i=" + Math.random(),
                    controller: 'insRegisterController'
                }). 
                
                when('/register', {
                    templateUrl: rootPath + "views/register.html?i=" + Math.random(),
                    controller: 'registerController'
                }).
                when('/addSuccess', {
                    templateUrl: rootPath + "views/addSuccess.html?i=" + Math.random(),
                    controller: 'registerController'
                }).
                when('/sendSuccess', {
                    templateUrl: rootPath + "views/sendSuccess.html?i=" + Math.random(),
                    controller: 'registerController'
                }).
                when('/honor', {
                    templateUrl: rootPath + "views/honor.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/updateSuccess', {
                    templateUrl: rootPath + "views/updateSuccess.html?i=" + Math.random(),
                    controller: 'registerController'
                }).
                when('/all', {
                    templateUrl: rootPath + "views/activities.html?i=" + Math.random(),
                    controller: 'mainController'
                }).                
                when('/map', {
                    templateUrl: rootPath + "views/map.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/view/:id', {
                    templateUrl: rootPath + "views/view.html?i=" + Math.random(),
                    controller: 'mainController'
                }).   
                when('/team/:id', {
                    templateUrl: rootPath + "views/team.html?i=" + Math.random(),
                    controller: 'mainController'
                }).                 
                when('/join/:id', {
                    templateUrl: rootPath + "views/joinSuccess.html?i=" + Math.random(),
                    controller: 'mainController'
                }).  
                when('/achievements', {
                    templateUrl: rootPath + "views/achievements.html?i=" + Math.random(),
                    controller: 'mainController'
                }).  
                when('/achievements/:id', {
                    templateUrl: rootPath + "views/achievementsDetails.html?i=" + Math.random(),
                    controller: 'mainController'
                }).  

                when('/tasks', {
                    templateUrl: rootPath + "views/tasks.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/notifications', {
                    templateUrl: rootPath + "views/notifications.html?i=" + Math.random(),
                    controller: 'mainController'
                }).                
                when('/hours', {
                    templateUrl: rootPath + "views/hours.html?i=" + Math.random(),
                    controller: 'mainController'
                }).
                when('/request', {
                    templateUrl: rootPath + "views/request.html?i=" + Math.random(),
                    controller: 'requestController'
                }).                
                                
                otherwise({
                    redirectTo: '/'
                });
    }])



.filter('range', function(){
    return function(n) {
      var res = [];
      for (var i = 0; i < n; i++) {
        res.push(i);
      }
      return res;
    };
  });


// Optional helper controller
mediasoftApp.angular.controller('mainController', ['$timeout', '$http', '$rootScope', '$scope', '$routeParams', '$location', 'mediasoftHTTP', 'localService', 'jangularvalidate', '$filter', function ($timeout, $http, $rootScope, $scope, $routeParams, $location, mediasoftHTTP, localService, jangularvalidate, $filter) {




        $rootScope.loginAs = localStorageData('loginAs');

        $rootScope.selectedID = localStorageData('selectedID') > 0 ? localStorageData('selectedID') : 0;

        $scope.getAccountInfoDone = false;
        $scope.getAccountInfo = function () {

            $http.get(apiPath + "login.php?type="+localStorageData('loginAs')+"&user_id=" + $rootScope.selectedID)
                    .success(function (response) {
                        $scope.getAccountInfoDone = true;
                        $scope.admin_approval = response.logged['admin_approval'];
                        $scope.security_approval = response.logged['security_approval'];
                    })
        }


        $scope.joinActivity = function(){ 
            var activityId = $routeParams.id;
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "join.php?lang="+$rootScope.currentLang+"&activity_id="+activityId+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    $scope.joinDetails = response.result;
                    $scope.joinAgain();
            })

        }


        $scope.getHonor = function(){ 
            var activityId = $routeParams.id;
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "honor.php?lang="+$rootScope.currentLang)
                .success(function (response) {
                    $scope.honorList = response.honor;
                    console.log($scope.honorList);
            })
        }


        $scope.joinAgain = function(){ 
            var activityId = $routeParams.id;
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "join.php?lang="+$rootScope.currentLang+"&activity_id="+activityId+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    $scope.joinDetails = response.result;
            })
        }


        $scope.getAchievements = function(){ 
            //var activityId = $routeParams.id;
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "achievements.php?lang="+$rootScope.currentLang+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    $scope.achievements = response.achievements.achievements;
                    console.log($scope.achievements);
            })
        }


        $scope.getAchievementsDetails = function(){ 
            var activityId = $routeParams.id;
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "achievements.php?lang="+$rootScope.currentLang+"&user_id=" + $rootScope.selectedID+"&activity_id=" + activityId)
                .success(function (response) {
                    $scope.achievements = response.achievements.achievements;
                    $scope.evaluations = response.achievements.evaluations;

                    console.log($scope.achievements);
            })
        }



        $scope.globalDone = false;
         $scope.getGlobal = function(){ 
               $http.get(apiPath + "global.php")
                .success(function (response) {
                    $scope.globalDone = true;
                    $scope.global = response.global;
                    $scope.translations = response.translations;

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
                console.log(err.message);
            } 

        }

        $scope.getMyTasks = function(){ 
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "tasks.php?lang="+$rootScope.currentLang+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    $scope.tasks = response.result;
            })
        }

        $scope.loadingHoursDone = false;
        $scope.getMyHours = function(){ 
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "hours.php?lang="+$rootScope.currentLang+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    $scope.loadingHoursDone = true;
                    $scope.approvedHours = response.approved;
                    $scope.onholdHours = response.onhold;
                    $scope.userInfo = response.userInfo;
                    $scope.slogan = response.slogan;

            })
        }

        $scope.selectedStars = [];

        $scope.getActivitiesDone = false;
        $scope.getActivities = function () {
           var id = $routeParams['id'];
            id == '' ? link = apiPath + "activities.php?view=all&type="+localStorageData('loginAs')+"&user_id="+$rootScope.selectedID : link=apiPath + "activities.php?view=all&type="+localStorageData('loginAs')+"&id="+id+"&user_id="+$rootScope.selectedID
            $http.get(link, {
                cache: false
            })
            .success(function (response) {
                $scope.activities = response.activities;
                $scope.team = response.team;
                $scope.rating = response.rating;
                $scope.ratingV = response.ratingV;
                $scope.subscribed = response.subscribed;
                $scope.activityStatus = response.status;
                $scope.getActivitiesDone = true;
            });
        }

        $scope.selectedStars = [];
        $scope.setSelectedStars = function(id,user_id,index) { 
         $scope.selectedStars[user_id].push({'id': id, 'user_id': user_id, value:index+1});
         console.log($scope.selectedStars);
         //$scope.selectedStars[id][user_id] = index + 1;   

        }
        

        $scope.getSelectedStars = function(id, user_id) { 
            var value = '';
            for (i = 0; i < $scope.ratingV.length; i++){ 
                if($scope.ratingV[i].rating_type == id && $scope.ratingV[i].user_id == user_id ){
                   return $scope.ratingV[i].rating_value;
                }
            }
        }


        $scope.change2Arabic = function (flag) {
            localStorageData('currentLang', 'ar')
            $rootScope.currentLang = localStorageData('currentLang');
            if(flag){ $location.path('/home'); }
        }

        $scope.openMapWindow = function (longlat) {
            window.open('http://maps.apple.com/?q='+longlat, '_system', 'location=yes');
        }

        $scope.openWindow = function (link) {
            window.open(link, '_system', 'location=yes');
        }

        $scope.change2English = function (flag) {
            localStorageData('currentLang', 'en')
            $rootScope.currentLang = localStorageData('currentLang');
            if(flag){ $location.path('/home'); }
        }

        $scope.change2Lang = function () {
            $location.path('/');
        }

        $scope.change2Home = function () {
            $location.path('/home');
        }

        $scope.gotoLoginView = function (lt) {
            localStorageData('loginType', lt);
            $timeout(function () {
                $location.path('/loginView');
            }, 100);
        }

        $rootScope.gotoLoginView = function (lt) {
            localStorageData('loginType', lt);
            $timeout(function () {
                $location.path('/loginView');
            }, 100);
        }

        $scope.gotoLogin = function () {
            $location.path('/login');
        }

        $scope.gotoRegister = function () {
            $location.path('/register');
        }


        $scope.gotoInsLogin = function () {
            $location.path('/ins-login');
        }

        $scope.gotoInsRegister = function () {
            $location.path('/ins-register');
        }

        $scope.goto = function (page) {
            $location.path('/' + page);
        }


        $scope.chooseLoginOrRegister = function (loginAs) {
            localStorageData('loginAs', loginAs);
            $scope.loginAs = localStorageData('loginAs');
            $scope.loginType = localStorageData('loginType');
            if ($scope.loginType == 'login') {
                console.log($scope.loginAs);
                if($scope.loginAs == 'users'){
                $scope.gotoLogin();
                }else{
                $scope.gotoInsLogin();    
                }
            }
            if ($scope.loginType == 'register') {
                if($scope.loginAs == 'users'){
                $scope.gotoRegister();
                }else{
                $scope.gotoInsRegister();    
                }
            }


        }


$scope.submitManageForm = function(id){

    var admin_approval = $('#admin_approval_'+id).val();
    var admin_comment = $('#admin_comment'+id).val();

   $http.get(apiPath + "giveHours.php?id="+id+"&admin_approval="+admin_approval+"&admin_comment="+admin_comment)
    .success(function (response) {
        if( $rootScope.currentLang == 'ar'){
        alert('تم تحديث الملف الشخصي للمتطوع');
         }else{
        alert('Volunteer Profile has beed updated');
    }
})

}







 $scope.exportReport = function(){ 
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "report.php?lang="+$rootScope.currentLang+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    if( $rootScope.currentLang == 'ar'){
                        alert('تم تصدير التقرير لبريدك الإلكتروني بنجاح');
                         }else{
                        alert('Report has been sent to your email successfully');
                    }
            })
        }



        $scope.loadingNotificationsDone = false;
        $scope.getNotifications = function(){ 
            var user_id = $rootScope.selectedID;
               $http.get(apiPath + "notifications.php?lang="+$rootScope.currentLang+"&user_id=" + $rootScope.selectedID)
                .success(function (response) {
                    $scope.loadingNotificationsDone = true;
                    $scope.notifications = response.notifications;

            })
        }






















 $scope.getGlobal();















    }]);





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






