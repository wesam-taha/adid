angular.module('ng-mediasoft.uploader', [])
// this directive to be used for uploading / updating single file directly. can't be used with existing form where other fields exist. its real time photo uploader.
.directive('photouploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
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
            photoname: '@',
            updatehandler: '@',
            photowidth: '@',
            photoheight: '@',
            photocss: '@',
            photopath: '@',
            photoname: '@',
            defaultphoto: '@',
            removehandler: '@',
            photoid: '@'
        },
        templateUrl: "core/template/directive/singlephoto.html",
        link: function (scope, element, attrs) {
            scope.isAction = false;
            scope.path = scope.photopath + scope.photoname;
            scope.tempphoto = "";
            
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
                    headers:  { "x-csrf-token" : CSRF_TOKEN, UGID: "0", UName: "test" },
                    filters: [
                        { title: scope.extensiontitle, extensions: scope.extensions }
                    ]
                });

                photouploader.bind('Init', function (up, params) {  });
                $('#plupload_container').on({
                    click: function (e) {
                        photouploader.start();
                        e.preventDefault();
                        return false;
                    }
                }, '#pickfiles');
                photouploader.init();
                photouploader.bind('FilesAdded', function (up, files) {
                    if (scope.photoname != "") {
                        scope.tempphoto = scope.photoname;
                    }
                    console.log("temp photo is " + scope.tempphoto)
                    var count = 0;
                    $.each(files, function (i, file) {
                        count++;
                    });
                    if (count > 1) {
                        $.each(files, function (i, file) {
                            photouploader.removeFile(file);
                        });
                        alert("Please select only one photo!")
                        return false;
                    } else {
                        photouploader.start();
                    }
                    up.refresh();
                });
                photouploader.bind('UploadProgress', function (up, file) {
                    $('#' + file.id + " b").html(file.percent + "%");
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
                    if (typeof (rpcResponse) != 'undefined' && rpcResponse.result == 'OK') {
                        // uploaded successfully
                        if (rpcResponse.filetype == '.jpg' || rpcResponse.filetype == '.jpeg' || rpcResponse.filetype == '.png' || rpcResponse.filetype == '.gif') {
                            scope.photoname = rpcResponse.fname;
                            var _url = scope.photopath + "" + rpcResponse.fname;
                            scope.path = _url;
                            if (scope.updatehandler != "") {
                                updateRecord();
                            }
                        } else { /* normal */ }
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
                            file: File
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

            var actionSuccess = function (data, status) {
                console.log(data)
                if (scope.tempphoto != "" && typeof scope.removehandler != "undefined") {
                    console.log(scope.tempphoto + " to be removed " + scope.removehandler)
                    // remove temp photo
                    mediasoftHTTP.actionProcess(scope.removehandler, [{
                        icon: scope.tempphoto
                    }])
                    .success(removeSuccess)
                    .error(actionError);
                }
            };
     
            function updateRecord() {
                if (typeof scope.updatehandler != "undefined") {
                    mediasoftHTTP.actionProcess(scope.updatehandler, [{
                        id: scope.photoid,
                        icon: scope.photoname
                    }])
                    .success(actionSuccess)
                    .error(actionError);
                }
            }
            scope.$on('$destroy', function () {

            });
        }
    };
}])
// this directive to be used with form. it can upload photos in real time and return file name.
.directive('photouploaderv2', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
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
            photoname: '@',
            updatehandler: '@',
            photowidth: '@',
            photoheight: '@',
            photocss: '@',
            photopath: '@',
            photoname: '@',
            defaultphoto: '@',
            removehandler: '@',
            photoid: '@',
            directUpload: "&"
        },
        templateUrl: "core/template/directive/singlephoto.html",
        link: function (scope, element, attrs) {
           
            scope.isAction = false;
            scope.path = scope.photopath + scope.photoname;
            scope.tempphoto = "";
            scope.isRemove = false;
            scope.showProgress = false;
           
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
                    headers:  { UGID: "0", UName: "test" },
                    filters: [
                        { title: scope.extensiontitle, extensions: scope.extensions }
                    ]
                });

                photouploader.bind('Init', function (up, params) { });
                $('#plupload_container').on({
                    click: function (e) {
                        scope.showProgress = true;
                        photouploader.start();
                        e.preventDefault();
                        return false;
                    }
                }, '#pickfiles');
                photouploader.init();
                photouploader.bind('FilesAdded', function (up, files) {
                    if (scope.photoname != "") {
                        scope.tempphoto = scope.photoname;
                    }
                    console.log("temp photo is " + scope.tempphoto)
                    var count = 0;
                    $.each(files, function (i, file) {
                        count++;
                    });
                    if (count > 1) {
                        $.each(files, function (i, file) {
                            photouploader.removeFile(file);
                        });
                        alert("Please select only one photo!")
                        return false;
                    } else {
                        scope.showProgress = true;
                        photouploader.start();
                    }
                    up.refresh();
                });
                photouploader.bind('UploadProgress', function (up, file) {
                    $('#' + file.id + " b").html(file.percent + "%");
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
                        // uploaded successfully
                        if (rpcResponse.filetype == '.jpg' || rpcResponse.filetype == '.jpeg' || rpcResponse.filetype == '.png' || rpcResponse.filetype == '.gif') {
                            scope.photoname = rpcResponse.fname;
                            var _url = scope.photopath + "" + rpcResponse.fname;
                            scope.isRemove = true;
                             if (scope.tempphoto != "" && typeof scope.removehandler != "undefined") {
                                    console.log(scope.tempphoto + " to be removed " + scope.removehandler)
                                    // remove temp photo
                                    mediasoftHTTP.actionProcess(scope.removehandler, [{
                                        icon: scope.tempphoto
                                    }])
                                    .success(removeSuccess)
                                    .error(actionError);
                             }
                            scope.$apply(function () {
								scope.directUpload({filename:  rpcResponse.fname})
								scope.path = _url;
							});
                        } else { /* normal */ }
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
            
            
            scope.remove = function (filename) {
                if(filename != '') {
                    mediasoftHTTP.actionProcess(scope.removehandler, [{
                        icon: filename
                    }])
                    .success(removeSuccess)
                    .error(actionError);
                    // reset photo name
                    scope.photoname = '';
                    // hide remove button
                    scope.isRemove = false;
                    // reset filename
					scope.directUpload({filename:  ""})
					
                }
            };
    
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
}])
// this directive to be used with form. it can allow you to upload and attach one or more files with form
.directive('fileuploader', ['$http', 'mediasoftHTTP', function ($http, mediasoftHTTP) {
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
        templateUrl: "views/directive/fileuploader.html",
        link: function (scope, element, attrs) {

            scope.isAction = false;
            scope.tempfilename = "";
            scope.showProgress = false;
            
            scope.message = "";
			scope.messagecss="alert-danger";
			
			scope.startUploading = false; // check whether actual uploading started
			scope.fileUploaded = false; // check whether all files uploaded
			
			scope.selectedFiles = []; // selected file list
			scope.uploadedFiles = []; // uploaded file list
			
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
                    headers:  { UGID: "0", UName: "test" },
                    filters: [
                        { title: scope.extensiontitle, extensions: scope.extensions }
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
                
                /*$('#plupload_container').on({
                    click: function (e) {
                        scope.showProgress = true;
                        photouploader.start();
                        e.preventDefault();
                        return false;
                    }
                }, '#pickfiles'); */
                photouploader.init();
                photouploader.bind('FilesAdded', function (up, files) {
                    if (scope.filename != "") {
                        scope.tempfilename = scope.filename;
                    }
                    console.log("temp file is " + scope.tempfilename)
                    // validation
                    console.log("uploaded files " + uploadedFiles);
                    if(uploadedFiles >= scope.maxallowedfiles) {
                    	scope.$apply(function () {
						    scope.message = "You can't upload more files";
						});
                        $.each(files, function(i, file) {
                           photouploader.removeFile(file);
                        });
                        return;
                    }
                    var _max_files = scope.maxallowedfiles - uploadedFiles;
                    console.log("item reached here " + scope.maxallowedfiles);
                    scope.$apply(function () {
						scope.selectedFiles = files;
						if(scope.selectedFiles.length > _max_files) {
							scope.message = "You can't upload more than " + scope.maxallowedfiles + " files";	
							 $.each(files, function(i, file) {
                                photouploader.removeFile(file);
                            });
							scope.selectedFiles = [];
							$("#uploadfiles").hide();
						} else {
						    scope.message = "";
	    					for(var i=0; i<= scope.selectedFiles.length - 1; i++)
							{
								scope.selectedFiles[i].css = "progress-bar-danger";
								scope.selectedFiles[i].percent = 0;
							}
							$("#pickfiles").hide();
							//photouploader.start();
						}
					});
					
                    /* var count = 0;
                    $.each(files, function (i, file) {
                        count++;
                    });
                    if (count > parseInt(scope.maxallowedfiles, 10)) {
                        $.each(files, function (i, file) {
                            photouploader.removeFile(file);
                        });
                        alert("Please select " + scope.maxallowedfiles + " files!")
                        return false;
                    } else {
                        scope.showProgress = true;
                        photouploader.start();
                    }*/
                    up.refresh();
                });
                
                photouploader.bind('UploadProgress', function (up, file) {
                    for(var i=0; i<= scope.selectedFiles.length - 1; i++)
					{
						if(file.id == scope.selectedFiles[i].id) {
							scope.$apply(function () {
							    scope.selectedFiles[i].percent = file.percent;
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
							scope.uploadedFiles.push(rpcResponse);
						});
						for(var i=0; i<= scope.selectedFiles.length - 1; i++)
						{
							if(file.id == scope.selectedFiles[i].id) {
								scope.$apply(function () {
									scope.selectedFiles[i].percent = 100;
									scope.selectedFiles[i].css = "progress-bar-success";
								});
							}
						}
						if(scope.selectedFiles.length == scope.uploadedFiles.length) {
						   	scope.ProcessCompleted = true;
						   	$("#pickfiles").show();
							scope.$apply(function () {
								scope.submitData({data: scope.uploadedFiles})
								// reset
								scope.startUploading = false; 
                    			scope.fileUploaded = false;
                    			scope.selectedFiles = [];
                    			scope.uploadedFiles = [];
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
            
            
            /* scope.remove = function (filename, index) {
             
                if(filename != '') {
					scope.uploadedFiles.splice(index, 1);
				    mediasoftHTTP.actionProcess(scope.removehandler, [{
                        file: filename
                    }])
                    .success(removeSuccess)
                    .error(actionError);
                   
                    // reset arr
					scope.submitData({data: scope.uploadedFiles})
					
                }
            }; */
    
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