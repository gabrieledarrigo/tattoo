/**
 * Uploader view model:
 * handle all upload action from the user.
 */
var multipleUploaderViewModel = function() {
    var self = this;

    // Store a reference to the images readed by FileReader.
    self.images = ko.observableArray([]);

    // Current section (tatoo, or biography, etc.)
    self.section = ko.observable('');

    self.getUrlSection = ko.computed(function() {
        return '/' + self.section();
    });

    // Subdivide image's array into an array of row.
    // Each row is composed by a maximum number of four images.
    self.rows = ko.computed(function() {
        var itemsPerRow = 4;
        var rowIndex = 0;
        var rows = [];

        for (var i = 0; i < self.images().length; i += 1) {
            if (!rows[rowIndex]) {
                rows[rowIndex] = [];
            }
            // Push into the row thw image.
            rows[rowIndex].push(self.images()[i]);

            // If maximum number item per row is reached than 
            // a new row is created.
            if (rows[rowIndex].length === itemsPerRow) {
                rowIndex += 1;
            }
        }

        return rows;
    });

    // Uploader and warning message visibility.
    self.uploaderIsVisible = ko.observable(true);
    self.progressIsVisible = ko.observable(false);

    self.warningMessage = ko.observable('');
    self.warningMessageIsVisible = ko.observable(false);

    self.statusMessage = ko.observable('');
    self.statusMessageClass = ko.observable('');
    self.statusMessageIsVisible = ko.observable(false);

    self.loaderIsVisible = ko.observable(false);

    // Set message visibility and textual content.
    self.showStatusMessage = function(message, status) {
        self.statusMessage(message);
        self.statusMessageIsVisible(status);
    };

    // Set warning message visibility.
    self.showWarningMessage = function(message, status) {
        self.warningMessage(message);
        self.warningMessageIsVisible(status);
    };

    // Visibility of upload button.
    self.uploadButtonIsVisible = ko.computed(function() {
        if (self.images().length > 0) {
            return true;
        } else {
            return false;
        }
    });

    // Trigger the upload browser window.
    self.showUploader = function() {
        $('#multiple-file').click();
    };

    // Check if uploaded file is an image.
    self.checkImages = function(item) {
        var imageType = /image.*/;

        if (!item.type.match(imageType)) {
            self.showWarningMessage('', true);
            return false;
        }
    };

    // Hide current image and empty the file input.
    self.removeImage = function(data, event) {
        self.images.remove(data);
        $('#multiple-file').val('');
    };

    // Remove the mime type notation (fore example .jpeg, .gif)
    // from the file name.
    self.setImageName = function(name) {
        return name.split('.')[0];
    };

    // Handle file upload.
    self.fileHandler = function(data, event) {

        // Retrieve fileList object.
        var fileList = event.currentTarget.files;

        // Cycle all uploaded images.
        for (var i = 0; i < fileList.length; i += 1) {

            // Check image type.
            if (self.checkImages(fileList[i]) === false) {
                continue;
            }

            // A preview of the image is shown with FileReader API.
            var img = $('<img />');
            img.file = fileList[i];

            var reader = new FileReader();

            // Attach onload event on file reader object.
            // Image is readed in data:URL through file reader.  
            reader.onload = (function(img) {
                return function(event) {
                    img.src = event.target.result;
                    // Set object that will be pushed into the observable.
                    var toPush = {
                        name: self.setImageName(img.file.name),
                        type: img.file.type,
                        size: img.file.size,
                        src: event.target.result,
                        file: img.file
                    };
                    // Store image information into the observable.
                    self.images.push(toPush);
                };
            })(img);

            // Read the data from uploaded image.
            reader.readAsDataURL(fileList[i]);
        }
    };

    // Send data to the server.
    self.sendData = function() {
        var formData = new FormData();

        // Append data to send to the server.
        for (var i = 0; i < self.images().length; i += 1) {
            formData.append('title[]', self.images()[i].name);
            formData.append('file[]', self.images()[i].file);
        }

        // Asynchronous file uploading
        $.ajax({
            url: self.getUrlSection() + '/insert',
            type: 'POST',
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        if (event.lengthComputable) {
                            var percentual = (event.loaded / event.total) * 100;
                            $('#progress-panel .bar').show().width(percentual + '%');
                        }
                    });
                }
                return xhr;
            },
            beforeSend: function() {
                self.progressIsVisible(true);
                self.statusMessageClass('alert alert-warning');
                self.loaderIsVisible(true);
                self.showStatusMessage('Caricamento in corso', true);
            },
            success: function(results) {
                self.images.removeAll();
                self.progressIsVisible(false);
                $('#multiple-file').val('');

                if (results.code === 200) {
                    self.statusMessageClass('alert alert-success');
                    self.loaderIsVisible(false);
                    self.showStatusMessage('Inserimento avvenuto con successo', true);
                } else {
                    self.statusMessageClass('alert alert-danger');
                    self.showStatusMessage('Errore nell\'inserimento. Riprova', true);
                }
            }
        });
    };

    // Init action. Retrieve and set the section name (tattoo, biography, etc).
    self.init = function() {
        self.section($('#section').attr('data-section'));
    };

    self.init();
};

ko.applyBindings(new multipleUploaderViewModel());