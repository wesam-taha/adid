

var programServices = angular.module('programServices', []);
programServices.factory('localService', ['$http', 'mediasoftHTTP',
    function ($http, mediasoftHTTP) {

        var passportsUploaderTemplate = rootPath + "views/templates/passportsFileUpload.html?i=" + Math.random();
        var emiratesIDsUploaderTemplate = rootPath + "views/templates/emiratesIDsFileUpload.html?i=" + Math.random();
        var tardeLicenceUploaderTemplate = rootPath + "views/templates/tradeLicenceFileUpload.html?i=" + Math.random();
        var personalPhotoUploaderTemplate = rootPath + "views/templates/personalPhotoFileUpload.html?i=" + Math.random();
        var visaCopyUploaderTemplate = rootPath + "views/templates/visaCopyFileUpload.html?i=" + Math.random();
        var cvCopyUploaderTemplate = rootPath + "views/templates/cvCopyFileUpload.html?i=" + Math.random();



        var UploadOptions = {
            handlerpath: apiPath + "upload.php",
            pickfilecaption: "Select Images",
            uploadfilecaption: "Start Uploading",
            max_file_size: "50mb",
            chunksize: '4mb',
            plupload_root: '../../plugins/plupload',
            headers: {},
            extensiontitle: "Images Files",
            extensions: "jpg,jpeg,png",
            filepath: imagedirectoryPath,
            removehandler: apiPath + "removefiles.php", // used to remove previous photo from directory if current photo uploaded and record updated successfully in database

        };

        return {
            getUploadOptions: function () {
                return UploadOptions;
            },
            passportsUploaderTemplate: function () {
                return passportsUploaderTemplate;
            },
            emiratesIDsUploaderTemplate: function () {
                return emiratesIDsUploaderTemplate;
            },
            tardeLicenceUploaderTemplate: function () {
                return tardeLicenceUploaderTemplate;
            },

            personalPhotoUploaderTemplate: function () {
                return personalPhotoUploaderTemplate;
            },
            visaCopyUploaderTemplate: function () {
                return visaCopyUploaderTemplate;
            },
            cvCopyUploaderTemplate: function () {
                return cvCopyUploaderTemplate;
            },
                                                
            getStatusMessage: function (status) {
                var msg = "Record added successfully"; // default message
                switch (status) {
                    case 'updated':
                        msg = "Record updated successfully";
                        break;
                    case 'deleted':
                        msg = "Record deleted successfully";
                        break;
                }
                return msg;
            }
        };
    }]);

