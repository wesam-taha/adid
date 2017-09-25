

mediasoftApp.angular.directive('map', ['$http', '$rootScope', function ($http, $rootScope) {
        return {
            restrict: 'EA',
            link: function (scope, element, attr) {


                $http.get(apiPath + "activities.php?type="+localStorageData('loginAs')+"&user_id=" + $rootScope.selectedID + '&view=map')
                        .success(function (response) {
                            scope.locations = response.locations;



                            var locations = scope.locations;
                            console.log(locations);
                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 7,
                                // center: new google.maps.LatLng(-33.92, 151.25),
                                center: new google.maps.LatLng(24.2486954, 54.1380923),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            function pinSymbol(color) {
                                return {
                                    path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
                                    fillColor: color,
                                    fillOpacity: 1,
                                    strokeColor: '#000',
                                    strokeWeight: 2,
                                    scale: 1,
                                };
                            }


                            for (i = 0; i < locations.length; i++) {
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i]['lat'], locations[i]['long']),
                                    map: map,
                                    icon: pinSymbol("#47c0e4"),
                                });


                                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                    return function () {
                                        infowindow.setContent('<div><b>' + locations[i]['activity_name_ar'] + '</b><br><a href="#/view/' + locations[i]['activity_id'] + '" class="button button-big active" > التفاصيل </a></div>');
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));
                            }



                        })


            }
        }
    }]);




mediasoftApp.angular.directive('mapen', ['$http', '$rootScope', function ($http, $rootScope) {
        return {
            restrict: 'EA',
            link: function (scope, element, attr) {


                $http.get(apiPath + "activities.php?type="+localStorageData('loginAs')+"&user_id=" + $rootScope.selectedID + '&view=map')
                        .success(function (response) {
                            scope.locations = response.locations;



                            var locations = scope.locations;
                            console.log(locations);
                            var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 7,
                                // center: new google.maps.LatLng(-33.92, 151.25),
                                center: new google.maps.LatLng(24.2486954, 54.1380923),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            });

                            var infowindow = new google.maps.InfoWindow();

                            var marker, i;

                            function pinSymbol(color) {
                                return {
                                    path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
                                    fillColor: color,
                                    fillOpacity: 1,
                                    strokeColor: '#000',
                                    strokeWeight: 2,
                                    scale: 1,
                                };
                            }


                            for (i = 0; i < locations.length; i++) {
                                marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(locations[i]['lat'], locations[i]['long']),
                                    map: map,
                                    icon: pinSymbol("#47c0e4"),
                                });


                                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                                    return function () {
                                        infowindow.setContent('<div><b>' + locations[i]['activity_name_en'] + '</b><br><a href="#/view/' + locations[i]['activity_id'] + '" class="button button-big active" > View Details </a></div>');
                                        infowindow.open(map, marker);
                                    }
                                })(marker, i));
                            }



                        })


            }
        }
    }]);



mediasoftApp.angular.directive('mdb', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: false,
                minValue: "1920-01-01",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.date_of_birth.value = element.val();
                }
            });

        }
    }
});


mediasoftApp.angular.directive('startd', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: false,
                minValue: "1920-01-01",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.activity_start_date.value = element.val();
                }
            });

        }
    }
});


mediasoftApp.angular.directive('endd', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: false,
                minValue: "1920-01-01",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.activity_end_date.value = element.val();
                }
            });

        }
    }
});




mediasoftApp.angular.directive('pasexdate', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: true,
                minValue: "1920-01-01",
                dateTimeFormat: "yyyy-MM-dd",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.passport_ex_date.value = element.val();
                }
            });

        }
    }
});




mediasoftApp.angular.directive('tlexdate', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: true,
                minValue: "1920-01-01",
                dateTimeFormat: "yyyy-MM-dd",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.tl_expiry_date.value = element.val();
                }
            });

        }
    }
});



mediasoftApp.angular.directive('vxdate', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: true,
                minValue: "1920-01-01",
                dateTimeFormat: "yyyy-MM-dd",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.visa_expiry_date.value = element.val();
                }
            });

        }
    }
});


mediasoftApp.angular.directive('idxdate', function () {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function (scope, element, attr, ngModel) {

            element.AnyPicker({
                mode: "datetime",
                showComponentLabel: true,
                minValue: "1920-01-01",
                dateTimeFormat: "yyyy-MM-dd",
                /*layout: "fixed",*/
                inputElement: element,
                inputChangeEvent: "onSet",
                dateTimeFormat: "yyyy-MM-dd",
                onSetOutput: function () {
                    scope.eid_expiry_date.value = element.val();
                }
            });

        }
    }
});



mediasoftApp.angular.directive('personalphotofileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
//.directive('photouploader', function ($http) {
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                handlerpath: '@',
                pickfilecaption: '@',
                maxfilesize: '@',
                chunksize: '@',
                plupload_root: '@',
                headers: '@',
                extensiontitle: '@',
                extensions: '@',
                maxallowedfiles: '@',
                filename: '@',
                filepath: '@',
                removehandler: '@',
                uploadfilecaption: '@',
                submitData: "&"
            },
            templateUrl: "views/templates/personalPhotoFileSelect.html?i="+Math.random(),
            link: function (scope, element, attrs) {

                scope.isAction = false;
                scope.tempfilename = "";
                scope.showProgress = false;

                scope.message = "";
                scope.messagecss = "alert-danger";

                scope.startUploading = false; // check whether actual uploading started
                scope.fileUploaded = false; // check whether all files uploaded

                scope.personalPhotoSelectedFiles = []; // selected file list
                scope.personalPhotoUploadedFiles = []; // uploaded file list

                scope.ProcessCompleted = false;

                $(function () {

                    var photouploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight',
                        browse_button: 'pickfiles',
                        container: 'plupload_container',
                        max_file_size: scope.max_file_size,
                        unique_names: true,
                        //chunk_size: scope.chunk_size,
                        url: scope.handlerpath,
                        flash_swf_url: scope.plupload_root + "/js/plupload.flash.swf",
                        silverlight_xap_url: scope.plupload_root + "/js/plupload.silverlight.xap",
                        headers: {UGID: "0", UName: "test"},
                        filters: [
                            {title: scope.extensiontitle, extensions: scope.extensions}
                        ]
                    });

                    $('#plupload_container').on({
                        click: function (e) {
                            console.log("upload started");
                            scope.$apply(function () {
                                scope.ProcessCompleted = false;
                                scope.startUploading = true;
                            });
                            photouploader.start();
                            e.preventDefault();
                            return false;
                        }
                    }, '#uploadfiles');

                    photouploader.bind('Init', function (up, params) { });


                    photouploader.init();
                    photouploader.bind('FilesAdded', function (up, files) {
                        if (scope.filename != "") {
                            scope.tempfilename = scope.filename;
                        }
                        console.log("temp file is " + scope.tempfilename)
                        // validation
                        console.log("uploaded files " + personalPhotoUploadedFiles);
                        if (personalPhotoUploadedFiles >= scope.maxallowedfiles) {
                            scope.$apply(function () {
                                scope.message = "You can't upload more files";
                            });
                            $.each(files, function (i, file) {
                                photouploader.removeFile(file);
                            });
                            return;
                        }
                        var _max_files = scope.maxallowedfiles - personalPhotoUploadedFiles;
                        console.log("item reached here " + scope.maxallowedfiles);
                        scope.$apply(function () {
                            scope.personalPhotoSelectedFiles = files;
                            if (scope.personalPhotoSelectedFiles.length > _max_files) {
                                scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";
                                $.each(files, function (i, file) {
                                    photouploader.removeFile(file);
                                });
                                scope.personalPhotoSelectedFiles = [];
                                $("#uploadfiles").hide();
                            } else {
                                scope.message = "";
                                for (var i = 0; i <= scope.personalPhotoSelectedFiles.length - 1; i++)
                                {
                                    scope.personalPhotoSelectedFiles[i].css = "progress-bar-danger";
                                    scope.personalPhotoSelectedFiles[i].percent = 0;
                                }
                                $("#pickfiles").hide();
                                //photouploader.start();
                            }
                        });

                        up.refresh();
                    });

                    photouploader.bind('UploadProgress', function (up, file) {
                        for (var i = 0; i <= scope.personalPhotoSelectedFiles.length - 1; i++)
                        {
                            if (file.id == scope.personalPhotoSelectedFiles[i].id) {
                                scope.$apply(function () {
                                    scope.personalPhotoSelectedFiles[i].percent = file.percent;
                                });
                            }
                        }
                    });

                    photouploader.bind('Error', function (up, err) {
                        $('#modalmsg').append("<div>Error: " + err.code +
                                ", Message: " + err.message +
                                (err.file ? ", File: " + err.file.name : "") +
                                "</div>"
                                );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    photouploader.bind('FileUploaded', function (up, file, info) {
                        var rpcResponse = JSON.parse(info.response);
                        var result;
                        scope.showProgress = false;
                        if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                            scope.$apply(function () {
                                scope.personalPhotoUploadedFiles.push(rpcResponse);
                            });
                            for (var i = 0; i <= scope.personalPhotoSelectedFiles.length - 1; i++)
                            {
                                if (file.id == scope.personalPhotoSelectedFiles[i].id) {
                                    scope.$apply(function () {
                                        scope.personalPhotoSelectedFiles[i].percent = 100;
                                        scope.personalPhotoSelectedFiles[i].css = "progress-bar-success";
                                    });
                                }
                            }
                            if (scope.personalPhotoSelectedFiles.length == scope.personalPhotoUploadedFiles.length) {
                                scope.ProcessCompleted = true;
                                $("#pickfiles").show();
                                scope.$apply(function () {
                                    scope.submitData({data: scope.personalPhotoUploadedFiles})
                                    // reset
                                    scope.startUploading = false;
                                    scope.fileUploaded = false;
                                    scope.personalPhotoSelectedFiles = [];
                                    scope.personalPhotoUploadedFiles = [];
                                });
                            }

                        } else {
                            var code;
                            var message;
                            if (typeof (rpcResponse.error) != 'undefined') {
                                code = rpcResponse.error.code;
                                message = rpcResponse.error.message;
                                if (message == undefined || message == "") {
                                    message = rpcResponse.error.data;
                                }
                            } else {
                                code = 0;
                                message = "Error uploading the file to the server";
                            }
                            photouploader.trigger("Error", {
                                code: code,
                                message: message,
                                file: ""
                            });
                        }
                    });
                });


                var removeSuccess = function (data, status) {
                    console.log(data)
                };
                var actionError = function (data, status, headers, config) {
                    scope.message = "Error occured";
                }


                scope.$on('$destroy', function () {

                });
            }
        };
    }]);




//////////////////////////////

mediasoftApp.angular.directive('visacopyfileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
//.directive('photouploader', function ($http) {
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                handlerpath: '@',
                pickfilecaption: '@',
                maxfilesize: '@',
                chunksize: '@',
                plupload_root: '@',
                headers: '@',
                extensiontitle: '@',
                extensions: '@',
                maxallowedfiles: '@',
                filename: '@',
                filepath: '@',
                removehandler: '@',
                uploadfilecaption: '@',
                submitData: "&"
            },
            templateUrl: "views/templates/visaCopyFileSelect.html?i="+Math.random(),
            link: function (scope, element, attrs) {

                scope.isAction = false;
                scope.tempfilename = "";
                scope.showProgress = false;

                scope.message = "";
                scope.messagecss = "alert-danger";

                scope.startUploading = false; // check whether actual uploading started
                scope.fileUploaded = false; // check whether all files uploaded

                scope.visaCopySelectedFiles = []; // selected file list
                scope.visaCopyUploadedFiles = []; // uploaded file list

                scope.ProcessCompleted = false;

                $(function () {

                    var photouploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight',
                        browse_button: 'pickfiles',
                        container: 'plupload_container',
                        max_file_size: scope.max_file_size,
                        unique_names: true,
                        //chunk_size: scope.chunk_size,
                        url: scope.handlerpath,
                        flash_swf_url: scope.plupload_root + "/js/plupload.flash.swf",
                        silverlight_xap_url: scope.plupload_root + "/js/plupload.silverlight.xap",
                        headers: {UGID: "0", UName: "test"},
                        filters: [
                            {title: scope.extensiontitle, extensions: scope.extensions}
                        ]
                    });

                    $('#plupload_container').on({
                        click: function (e) {
                            console.log("upload started");
                            scope.$apply(function () {
                                scope.ProcessCompleted = false;
                                scope.startUploading = true;
                            });
                            photouploader.start();
                            e.preventDefault();
                            return false;
                        }
                    }, '#uploadfiles');

                    photouploader.bind('Init', function (up, params) { });


                    photouploader.init();
                    photouploader.bind('FilesAdded', function (up, files) {
                        if (scope.filename != "") {
                            scope.tempfilename = scope.filename;
                        }
                        console.log("temp file is " + scope.tempfilename)
                        // validation
                        console.log("uploaded files " + visaCopyUploadedFiles);
                        if (visaCopyUploadedFiles >= scope.maxallowedfiles) {
                            scope.$apply(function () {
                                scope.message = "You can't upload more files";
                            });
                            $.each(files, function (i, file) {
                                photouploader.removeFile(file);
                            });
                            return;
                        }
                        var _max_files = scope.maxallowedfiles - visaCopyUploadedFiles;
                        console.log("item reached here " + scope.maxallowedfiles);
                        scope.$apply(function () {
                            scope.visaCopySelectedFiles = files;
                            if (scope.visaCopySelectedFiles.length > _max_files) {
                                scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";
                                $.each(files, function (i, file) {
                                    photouploader.removeFile(file);
                                });
                                scope.visaCopySelectedFiles = [];
                                $("#uploadfiles").hide();
                            } else {
                                scope.message = "";
                                for (var i = 0; i <= scope.visaCopySelectedFiles.length - 1; i++)
                                {
                                    scope.visaCopySelectedFiles[i].css = "progress-bar-danger";
                                    scope.visaCopySelectedFiles[i].percent = 0;
                                }
                                $("#pickfiles").hide();
                                //photouploader.start();
                            }
                        });

                        up.refresh();
                    });

                    photouploader.bind('UploadProgress', function (up, file) {
                        for (var i = 0; i <= scope.visaCopySelectedFiles.length - 1; i++)
                        {
                            if (file.id == scope.visaCopySelectedFiles[i].id) {
                                scope.$apply(function () {
                                    scope.visaCopySelectedFiles[i].percent = file.percent;
                                });
                            }
                        }
                    });

                    photouploader.bind('Error', function (up, err) {
                        $('#modalmsg').append("<div>Error: " + err.code +
                                ", Message: " + err.message +
                                (err.file ? ", File: " + err.file.name : "") +
                                "</div>"
                                );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    photouploader.bind('FileUploaded', function (up, file, info) {
                        var rpcResponse = JSON.parse(info.response);
                        var result;
                        scope.showProgress = false;
                        if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                            scope.$apply(function () {
                                scope.visaCopyUploadedFiles.push(rpcResponse);
                            });
                            for (var i = 0; i <= scope.visaCopySelectedFiles.length - 1; i++)
                            {
                                if (file.id == scope.visaCopySelectedFiles[i].id) {
                                    scope.$apply(function () {
                                        scope.visaCopySelectedFiles[i].percent = 100;
                                        scope.visaCopySelectedFiles[i].css = "progress-bar-success";
                                    });
                                }
                            }
                            if (scope.visaCopySelectedFiles.length == scope.visaCopyUploadedFiles.length) {
                                scope.ProcessCompleted = true;
                                $("#pickfiles").show();
                                scope.$apply(function () {
                                    scope.submitData({data: scope.visaCopyUploadedFiles})
                                    // reset
                                    scope.startUploading = false;
                                    scope.fileUploaded = false;
                                    scope.visaCopySelectedFiles = [];
                                    scope.visaCopyUploadedFiles = [];
                                });
                            }

                        } else {
                            var code;
                            var message;
                            if (typeof (rpcResponse.error) != 'undefined') {
                                code = rpcResponse.error.code;
                                message = rpcResponse.error.message;
                                if (message == undefined || message == "") {
                                    message = rpcResponse.error.data;
                                }
                            } else {
                                code = 0;
                                message = "Error uploading the file to the server";
                            }
                            photouploader.trigger("Error", {
                                code: code,
                                message: message,
                                file: ""
                            });
                        }
                    });
                });


                var removeSuccess = function (data, status) {
                    console.log(data)
                };
                var actionError = function (data, status, headers, config) {
                    scope.message = "Error occured";
                }


                scope.$on('$destroy', function () {

                });
            }
        };
    }]);





// this directive to be used with form. it can allow you to upload and attach one or more files with form
mediasoftApp.angular.directive('emiratesidsfileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
//.directive('photouploader', function ($http) {
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                handlerpath: '@',
                pickfilecaption: '@',
                maxfilesize: '@',
                chunksize: '@',
                plupload_root: '@',
                headers: '@',
                extensiontitle: '@',
                extensions: '@',
                maxallowedfiles: '@',
                filename: '@',
                filepath: '@',
                removehandler: '@',
                uploadfilecaption: '@',
                submitData: "&"
            },
            templateUrl: "views/templates/emiratesIDsFileSelect.html",
            link: function (scope, element, attrs) {

                scope.isAction = false;
                scope.tempfilename = "";
                scope.showProgress = false;

                scope.message = "";
                scope.messagecss = "alert-danger";

                scope.startUploading = false; // check whether actual uploading started
                scope.fileUploaded = false; // check whether all files uploaded

                scope.emiratesIDsSelectedFiles = []; // selected file list
                scope.emiratesIDsUploadedFiles = []; // uploaded file list

                scope.ProcessCompleted = false;

                $(function () {

                    var photouploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight',
                        browse_button: 'pickfiles',
                        container: 'plupload_container',
                        max_file_size: scope.max_file_size,
                        unique_names: true,
                        //chunk_size: scope.chunk_size,
                        url: scope.handlerpath,
                        flash_swf_url: scope.plupload_root + "/js/plupload.flash.swf",
                        silverlight_xap_url: scope.plupload_root + "/js/plupload.silverlight.xap",
                        headers: {UGID: "0", UName: "test"},
                        filters: [
                            {title: scope.extensiontitle, extensions: scope.extensions}
                        ]
                    });

                    $('#plupload_container').on({
                        click: function (e) {
                            console.log("upload started");
                            scope.$apply(function () {
                                scope.ProcessCompleted = false;
                                scope.startUploading = true;
                            });
                            photouploader.start();
                            e.preventDefault();
                            return false;
                        }
                    }, '#uploadfiles');

                    photouploader.bind('Init', function (up, params) { });

                    photouploader.init();
                    photouploader.bind('FilesAdded', function (up, files) {
                        if (scope.filename != "") {
                            scope.tempfilename = scope.filename;
                        }
                        console.log("temp file is " + scope.tempfilename)
                        // validation
                        console.log("uploaded files " + emiratesIDsUploadedFiles);
                        if (emiratesIDsUploadedFiles >= scope.maxallowedfiles) {
                            scope.$apply(function () {
                                scope.message = "You can't upload more files";
                            });
                            $.each(files, function (i, file) {
                                photouploader.removeFile(file);
                            });
                            return;
                        }
                        var _max_files = scope.maxallowedfiles - emiratesIDsUploadedFiles;
                        console.log("item reached here " + scope.maxallowedfiles);
                        scope.$apply(function () {
                            scope.emiratesIDsSelectedFiles = files;
                            if (scope.emiratesIDsSelectedFiles.length > _max_files) {
                                scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";
                                $.each(files, function (i, file) {
                                    photouploader.removeFile(file);
                                });
                                scope.emiratesIDsSelectedFiles = [];
                                $("#uploadfiles").hide();
                            } else {
                                scope.message = "";
                                for (var i = 0; i <= scope.emiratesIDsSelectedFiles.length - 1; i++)
                                {
                                    scope.emiratesIDsSelectedFiles[i].css = "progress-bar-danger";
                                    scope.emiratesIDsSelectedFiles[i].percent = 0;
                                }
                                $("#pickfiles").hide();
                                //photouploader.start();
                            }
                        });

                        up.refresh();
                    });

                    photouploader.bind('UploadProgress', function (up, file) {
                        for (var i = 0; i <= scope.emiratesIDsSelectedFiles.length - 1; i++)
                        {
                            if (file.id == scope.emiratesIDsSelectedFiles[i].id) {
                                scope.$apply(function () {
                                    scope.emiratesIDsSelectedFiles[i].percent = file.percent;
                                });
                            }
                        }
                    });

                    photouploader.bind('Error', function (up, err) {
                        $('#modalmsg').append("<div>Error: " + err.code +
                                ", Message: " + err.message +
                                (err.file ? ", File: " + err.file.name : "") +
                                "</div>"
                                );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    photouploader.bind('FileUploaded', function (up, file, info) {
                        var rpcResponse = JSON.parse(info.response);
                        var result;
                        scope.showProgress = false;
                        if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                            scope.$apply(function () {
                                scope.emiratesIDsUploadedFiles.push(rpcResponse);
                            });
                            for (var i = 0; i <= scope.emiratesIDsSelectedFiles.length - 1; i++)
                            {
                                if (file.id == scope.emiratesIDsSelectedFiles[i].id) {
                                    scope.$apply(function () {
                                        scope.emiratesIDsSelectedFiles[i].percent = 100;
                                        scope.emiratesIDsSelectedFiles[i].css = "progress-bar-success";
                                    });
                                }
                            }
                            if (scope.emiratesIDsSelectedFiles.length == scope.emiratesIDsUploadedFiles.length) {
                                scope.ProcessCompleted = true;
                                $("#pickfiles").show();
                                scope.$apply(function () {
                                    scope.submitData({data: scope.emiratesIDsUploadedFiles})
                                    // reset
                                    scope.startUploading = false;
                                    scope.fileUploaded = false;
                                    scope.emiratesIDsSelectedFiles = [];
                                    scope.emiratesIDsUploadedFiles = [];
                                });
                            }

                        } else {
                            var code;
                            var message;
                            if (typeof (rpcResponse.error) != 'undefined') {
                                code = rpcResponse.error.code;
                                message = rpcResponse.error.message;
                                if (message == undefined || message == "") {
                                    message = rpcResponse.error.data;
                                }
                            } else {
                                code = 0;
                                message = "Error uploading the file to the server";
                            }
                            photouploader.trigger("Error", {
                                code: code,
                                message: message,
                                file: ""
                            });
                        }
                    });
                });


                var removeSuccess = function (data, status) {
                    console.log(data)
                };
                var actionError = function (data, status, headers, config) {
                    scope.message = "Error occured";
                }


                scope.$on('$destroy', function () {

                });
            }
        };
    }]);



mediasoftApp.angular.directive('passporsfileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                handlerpath: '@',
                pickfilecaption: '@',
                maxfilesize: '@',
                chunksize: '@',
                plupload_root: '@',
                headers: '@',
                extensiontitle: '@',
                extensions: '@',
                maxallowedfiles: '@',
                filename: '@',
                filepath: '@',
                removehandler: '@',
                uploadfilecaption: '@',
                submitData: "&"
            },
            templateUrl: "views/templates/passportsFileSelect.html",
            link: function (scope, element, attrs) {

                scope.isAction = false;
                scope.tempfilename = "";
                scope.showProgress = false;

                scope.message = "";
                scope.messagecss = "alert-danger";

                scope.startUploading = false; // check whether actual uploading started
                scope.fileUploaded = false; // check whether all files uploaded

                scope.passportsSelectedFiles = []; // selected file list
                scope.passportsUploadedFiles = []; // uploaded file list

                scope.ProcessCompleted = false;

                $(function () {

                    var photouploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight',
                        browse_button: 'pickfiles',
                        container: 'plupload_container',
                        max_file_size: scope.max_file_size,
                        unique_names: true,
                        //chunk_size: scope.chunk_size,
                        url: scope.handlerpath,
                        flash_swf_url: scope.plupload_root + "/js/plupload.flash.swf",
                        silverlight_xap_url: scope.plupload_root + "/js/plupload.silverlight.xap",
                        headers: {UGID: "0", UName: "test"},
                        filters: [
                            {title: scope.extensiontitle, extensions: scope.extensions}
                        ]
                    });

                    $('#plupload_container').on({
                        click: function (e) {
                            console.log("upload started");
                            scope.$apply(function () {
                                scope.ProcessCompleted = false;
                                scope.startUploading = true;
                            });
                            photouploader.start();
                            e.preventDefault();
                            return false;
                        }
                    }, '#uploadfiles');

                    photouploader.bind('Init', function (up, params) { });
                    photouploader.init();
                    photouploader.bind('FilesAdded', function (up, files) {
                        if (scope.filename != "") {
                            scope.tempfilename = scope.filename;
                        }
                        console.log("temp file is " + scope.tempfilename)
                        // validation
                        console.log("uploaded files " + passportsUploadedFiles);
                        if (passportsUploadedFiles >= scope.maxallowedfiles) {
                            scope.$apply(function () {
                                scope.message = "You can't upload more files";
                            });
                            $.each(files, function (i, file) {
                                photouploader.removeFile(file);
                            });
                            return;
                        }
                        var _max_files = scope.maxallowedfiles - passportsUploadedFiles;
                        console.log("item reached here " + scope.maxallowedfiles);
                        scope.$apply(function () {
                            scope.passportsSelectedFiles = files;
                            if (scope.passportsSelectedFiles.length > _max_files) {
                                scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";
                                $.each(files, function (i, file) {
                                    photouploader.removeFile(file);
                                });
                                scope.passportsSelectedFiles = [];
                                $("#uploadfiles").hide();
                            } else {
                                scope.message = "";
                                for (var i = 0; i <= scope.passportsSelectedFiles.length - 1; i++)
                                {
                                    scope.passportsSelectedFiles[i].css = "progress-bar-danger";
                                    scope.passportsSelectedFiles[i].percent = 0;
                                }
                                $("#pickfiles").hide();
                                //photouploader.start();
                            }
                        });

                        up.refresh();
                    });

                    photouploader.bind('UploadProgress', function (up, file) {
                        for (var i = 0; i <= scope.passportsSelectedFiles.length - 1; i++)
                        {
                            if (file.id == scope.passportsSelectedFiles[i].id) {
                                scope.$apply(function () {
                                    scope.passportsSelectedFiles[i].percent = file.percent;
                                });
                            }
                        }
                    });

                    photouploader.bind('Error', function (up, err) {
                        $('#modalmsg').append("<div>Error: " + err.code +
                                ", Message: " + err.message +
                                (err.file ? ", File: " + err.file.name : "") +
                                "</div>"
                                );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    photouploader.bind('FileUploaded', function (up, file, info) {
                        var rpcResponse = JSON.parse(info.response);
                        var result;
                        scope.showProgress = false;
                        if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                            scope.$apply(function () {
                                scope.passportsUploadedFiles.push(rpcResponse);
                            });
                            for (var i = 0; i <= scope.passportsSelectedFiles.length - 1; i++)
                            {
                                if (file.id == scope.passportsSelectedFiles[i].id) {
                                    scope.$apply(function () {
                                        scope.passportsSelectedFiles[i].percent = 100;
                                        scope.passportsSelectedFiles[i].css = "progress-bar-success";
                                    });
                                }
                            }
                            if (scope.passportsSelectedFiles.length == scope.passportsUploadedFiles.length) {
                                scope.ProcessCompleted = true;
                                $("#pickfiles").show();
                                scope.$apply(function () {
                                    scope.submitData({data: scope.passportsUploadedFiles})
                                    // reset
                                    scope.startUploading = false;
                                    scope.fileUploaded = false;
                                    scope.passportsSelectedFiles = [];
                                    scope.passportsUploadedFiles = [];
                                });
                            }

                        } else {
                            var code;
                            var message;
                            if (typeof (rpcResponse.error) != 'undefined') {
                                code = rpcResponse.error.code;
                                message = rpcResponse.error.message;
                                if (message == undefined || message == "") {
                                    message = rpcResponse.error.data;
                                }
                            } else {
                                code = 0;
                                message = "Error uploading the file to the server";
                            }
                            photouploader.trigger("Error", {
                                code: code,
                                message: message,
                                file: ""
                            });
                        }
                    });
                });

                var removeSuccess = function (data, status) {
                    console.log(data)
                };
                var actionError = function (data, status, headers, config) {
                    scope.message = "Error occured";
                }


                scope.$on('$destroy', function () {

                });
            }
        };
    }]);





mediasoftApp.angular.directive('cvcopyfileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
//.directive('photouploader', function ($http) {
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                handlerpath: '@',
                pickfilecaption: '@',
                maxfilesize: '@',
                chunksize: '@',
                plupload_root: '@',
                headers: '@',
                extensiontitle: '@',
                extensions: '@',
                maxallowedfiles: '@',
                filename: '@',
                filepath: '@',
                removehandler: '@',
                uploadfilecaption: '@',
                submitData: "&"
            },
            templateUrl: "views/templates/cvCopyFileSelect.html?i="+Math.random(),
            link: function (scope, element, attrs) {

                scope.isAction = false;
                scope.tempfilename = "";
                scope.showProgress = false;

                scope.message = "";
                scope.messagecss = "alert-danger";

                scope.startUploading = false; // check whether actual uploading started
                scope.fileUploaded = false; // check whether all files uploaded

                scope.cvCopySelectedFiles = []; // selected file list
                scope.cvCopyUploadedFiles = []; // uploaded file list

                scope.ProcessCompleted = false;

                $(function () {

                    var photouploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight',
                        browse_button: 'pickfiles',
                        container: 'plupload_container',
                        max_file_size: scope.max_file_size,
                        unique_names: true,
                        //chunk_size: scope.chunk_size,
                        url: scope.handlerpath,
                        flash_swf_url: scope.plupload_root + "/js/plupload.flash.swf",
                        silverlight_xap_url: scope.plupload_root + "/js/plupload.silverlight.xap",
                        headers: {UGID: "0", UName: "test"},
                        filters: [
                            {title: scope.extensiontitle, extensions: scope.extensions}
                        ]
                    });

                    $('#plupload_container').on({
                        click: function (e) {
                            console.log("upload started");
                            scope.$apply(function () {
                                scope.ProcessCompleted = false;
                                scope.startUploading = true;
                            });
                            photouploader.start();
                            e.preventDefault();
                            return false;
                        }
                    }, '#uploadfiles');

                    photouploader.bind('Init', function (up, params) { });


                    photouploader.init();
                    photouploader.bind('FilesAdded', function (up, files) {
                        if (scope.filename != "") {
                            scope.tempfilename = scope.filename;
                        }
                        console.log("temp file is " + scope.tempfilename)
                        // validation
                        console.log("uploaded files " + cvCopyUploadedFiles);
                        if (cvCopyUploadedFiles >= scope.maxallowedfiles) {
                            scope.$apply(function () {
                                scope.message = "You can't upload more files";
                            });
                            $.each(files, function (i, file) {
                                photouploader.removeFile(file);
                            });
                            return;
                        }
                        var _max_files = scope.maxallowedfiles - cvCopyUploadedFiles;
                        console.log("item reached here " + scope.maxallowedfiles);
                        scope.$apply(function () {
                            scope.cvCopySelectedFiles = files;
                            if (scope.cvCopySelectedFiles.length > _max_files) {
                                scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";
                                $.each(files, function (i, file) {
                                    photouploader.removeFile(file);
                                });
                                scope.cvCopySelectedFiles = [];
                                $("#uploadfiles").hide();
                            } else {
                                scope.message = "";
                                for (var i = 0; i <= scope.cvCopySelectedFiles.length - 1; i++)
                                {
                                    scope.cvCopySelectedFiles[i].css = "progress-bar-danger";
                                    scope.cvCopySelectedFiles[i].percent = 0;
                                }
                                $("#pickfiles").hide();
                                //photouploader.start();
                            }
                        });


                        up.refresh();
                    });

                    photouploader.bind('UploadProgress', function (up, file) {
                        for (var i = 0; i <= scope.cvCopySelectedFiles.length - 1; i++)
                        {
                            if (file.id == scope.cvCopySelectedFiles[i].id) {
                                scope.$apply(function () {
                                    scope.cvCopySelectedFiles[i].percent = file.percent;
                                });
                            }
                        }
                    });

                    photouploader.bind('Error', function (up, err) {
                        $('#modalmsg').append("<div>Error: " + err.code +
                                ", Message: " + err.message +
                                (err.file ? ", File: " + err.file.name : "") +
                                "</div>"
                                );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    photouploader.bind('FileUploaded', function (up, file, info) {
                        var rpcResponse = JSON.parse(info.response);
                        var result;
                        scope.showProgress = false;
                        if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                            scope.$apply(function () {
                                scope.cvCopyUploadedFiles.push(rpcResponse);
                            });
                            for (var i = 0; i <= scope.cvCopySelectedFiles.length - 1; i++)
                            {
                                if (file.id == scope.cvCopySelectedFiles[i].id) {
                                    scope.$apply(function () {
                                        scope.cvCopySelectedFiles[i].percent = 100;
                                        scope.cvCopySelectedFiles[i].css = "progress-bar-success";
                                    });
                                }
                            }
                            if (scope.cvCopySelectedFiles.length == scope.cvCopyUploadedFiles.length) {
                                scope.ProcessCompleted = true;
                                $("#pickfiles").show();
                                scope.$apply(function () {
                                    scope.submitData({data: scope.cvCopyUploadedFiles})
                                    // reset
                                    scope.startUploading = false;
                                    scope.fileUploaded = false;
                                    scope.cvCopySelectedFiles = [];
                                    scope.cvCopyUploadedFiles = [];
                                });
                            }

                        } else {
                            var code;
                            var message;
                            if (typeof (rpcResponse.error) != 'undefined') {
                                code = rpcResponse.error.code;
                                message = rpcResponse.error.message;
                                if (message == undefined || message == "") {
                                    message = rpcResponse.error.data;
                                }
                            } else {
                                code = 0;
                                message = "Error uploading the file to the server";
                            }
                            photouploader.trigger("Error", {
                                code: code,
                                message: message,
                                file: ""
                            });
                        }
                    });
                });


                var removeSuccess = function (data, status) {
                    console.log(data)
                };
                var actionError = function (data, status, headers, config) {
                    scope.message = "Error occured";
                }


                scope.$on('$destroy', function () {

                });
            }
        };
    }]);





mediasoftApp.angular.directive('tradelicencefileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
//.directive('photouploader', function ($http) {
        return {
            restrict: 'EA',
            replace: true,
            scope: {
                handlerpath: '@',
                pickfilecaption: '@',
                maxfilesize: '@',
                chunksize: '@',
                plupload_root: '@',
                headers: '@',
                extensiontitle: '@',
                extensions: '@',
                maxallowedfiles: '@',
                filename: '@',
                filepath: '@',
                removehandler: '@',
                uploadfilecaption: '@',
                submitData: "&"
            },
            templateUrl: "views/templates/tradeLicenceFileSelect.html?i="+Math.random(),
            link: function (scope, element, attrs) {

                scope.isAction = false;
                scope.tempfilename = "";
                scope.showProgress = false;

                scope.message = "";
                scope.messagecss = "alert-danger";

                scope.startUploading = false; // check whether actual uploading started
                scope.fileUploaded = false; // check whether all files uploaded

                scope.tradeLicenceSelectedFiles = []; // selected file list
                scope.tradeLicenceUploadedFiles = []; // uploaded file list

                scope.ProcessCompleted = false;

                $(function () {

                    var photouploader = new plupload.Uploader({
                        runtimes: 'html5,flash,silverlight',
                        browse_button: 'pickfiles',
                        container: 'plupload_container',
                        max_file_size: scope.max_file_size,
                        unique_names: true,
                        //chunk_size: scope.chunk_size,
                        url: scope.handlerpath,
                        flash_swf_url: scope.plupload_root + "/js/plupload.flash.swf",
                        silverlight_xap_url: scope.plupload_root + "/js/plupload.silverlight.xap",
                        headers: {UGID: "0", UName: "test"},
                        filters: [
                            {title: scope.extensiontitle, extensions: scope.extensions}
                        ]
                    });

                    $('#plupload_container').on({
                        click: function (e) {
                            console.log("upload started");
                            scope.$apply(function () {
                                scope.ProcessCompleted = false;
                                scope.startUploading = true;
                            });
                            photouploader.start();
                            e.preventDefault();
                            return false;
                        }
                    }, '#uploadfiles');

                    photouploader.bind('Init', function (up, params) { });


                    photouploader.init();
                    photouploader.bind('FilesAdded', function (up, files) {
                        if (scope.filename != "") {
                            scope.tempfilename = scope.filename;
                        }
                        console.log("temp file is " + scope.tempfilename)
                        // validation
                        console.log("uploaded files " + tradeLicenceUploadedFiles);
                        if (tradeLicenceUploadedFiles >= scope.maxallowedfiles) {
                            scope.$apply(function () {
                                scope.message = "You can't upload more files";
                            });
                            $.each(files, function (i, file) {
                                photouploader.removeFile(file);
                            });
                            return;
                        }
                        var _max_files = scope.maxallowedfiles - tradeLicenceUploadedFiles;
                        console.log("item reached here " + scope.maxallowedfiles);
                        scope.$apply(function () {
                            scope.tradeLicenceSelectedFiles = files;
                            if (scope.tradeLicenceSelectedFiles.length > _max_files) {
                                scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";
                                $.each(files, function (i, file) {
                                    photouploader.removeFile(file);
                                });
                                scope.tradeLicenceSelectedFiles = [];
                                $("#uploadfiles").hide();
                            } else {
                                scope.message = "";
                                for (var i = 0; i <= scope.tradeLicenceSelectedFiles.length - 1; i++)
                                {
                                    scope.tradeLicenceSelectedFiles[i].css = "progress-bar-danger";
                                    scope.tradeLicenceSelectedFiles[i].percent = 0;
                                }
                                $("#pickfiles").hide();
                                //photouploader.start();
                            }
                        });

                        up.refresh();
                    });

                    photouploader.bind('UploadProgress', function (up, file) {
                        for (var i = 0; i <= scope.tradeLicenceSelectedFiles.length - 1; i++)
                        {
                            if (file.id == scope.tradeLicenceSelectedFiles[i].id) {
                                scope.$apply(function () {
                                    scope.tradeLicenceSelectedFiles[i].percent = file.percent;
                                });
                            }
                        }
                    });

                    photouploader.bind('Error', function (up, err) {
                        $('#modalmsg').append("<div>Error: " + err.code +
                                ", Message: " + err.message +
                                (err.file ? ", File: " + err.file.name : "") +
                                "</div>"
                                );
                        up.refresh(); // Reposition Flash/Silverlight
                    });

                    photouploader.bind('FileUploaded', function (up, file, info) {
                        var rpcResponse = JSON.parse(info.response);
                        var result;
                        scope.showProgress = false;
                        if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                            scope.$apply(function () {
                                scope.tradeLicenceUploadedFiles.push(rpcResponse);
                            });
                            for (var i = 0; i <= scope.tradeLicenceSelectedFiles.length - 1; i++)
                            {
                                if (file.id == scope.tradeLicenceSelectedFiles[i].id) {
                                    scope.$apply(function () {
                                        scope.tradeLicenceSelectedFiles[i].percent = 100;
                                        scope.tradeLicenceSelectedFiles[i].css = "progress-bar-success";
                                    });
                                }
                            }
                            if (scope.tradeLicenceSelectedFiles.length == scope.tradeLicenceUploadedFiles.length) {
                                scope.ProcessCompleted = true;
                                $("#pickfiles").show();
                                scope.$apply(function () {
                                    scope.submitData({data: scope.tradeLicenceUploadedFiles})
                                    // reset
                                    scope.startUploading = false;
                                    scope.fileUploaded = false;
                                    scope.tradeLicenceSelectedFiles = [];
                                    scope.tradeLicenceUploadedFiles = [];
                                });
                            }

                        } else {
                            var code;
                            var message;
                            if (typeof (rpcResponse.error) != 'undefined') {
                                code = rpcResponse.error.code;
                                message = rpcResponse.error.message;
                                if (message == undefined || message == "") {
                                    message = rpcResponse.error.data;
                                }
                            } else {
                                code = 0;
                                message = "Error uploading the file to the server";
                            }
                            photouploader.trigger("Error", {
                                code: code,
                                message: message,
                                file: ""
                            });
                        }
                    });
                });


                var removeSuccess = function (data, status) {
                    console.log(data)
                };
                var actionError = function (data, status, headers, config) {
                    scope.message = "Error occured";
                }


                scope.$on('$destroy', function () {

                });
            }
        };
    }]);







///////////////////////////////







///////////////////////////////


function performClick(elemId) {
   var elem = document.getElementById(elemId);
   if(elem && document.createEvent) {
      var evt = document.createEvent("MouseEvents");
      evt.initEvent("click", true, false);
      elem.dispatchEvent(evt);
   }
}