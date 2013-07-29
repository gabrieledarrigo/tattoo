/**
 *  Uploader view model handle all upload action from the user
 */
var uploaderViewModel = function() {
    var self = this;

    // Store a reference to the image readed by FileReader.
    self.images = ko.observableArray([]);

    // Uploader and warning message visibility.
    //self.uploaderIsVisible = ko.observable(false);
    self.progressIsVisible = ko.observable(false);

    self.warningMessage = ko.observable('');
    self.warningMessageIsVisible = ko.observable(false);

    self.showWarningMessage = function(message, status) {
        self.warningMessage(message);
        self.warningMessageIsVisible(status);
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
        self.images.removeAll();
        $('#single-file').val('');
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

            if (self.checkImages(fileList[i]) === false) {
                continue;
            }

            // A preview of the image is showew with FileReader API.
            var img = $('<img />');
            img.file = fileList[i];

            var reader = new FileReader();

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

                    self.images.splice(0, 1, toPush);
                };
            })(img);
            // Read data from uploaded image.
            reader.readAsDataURL(fileList[i]);
        }

    };

};