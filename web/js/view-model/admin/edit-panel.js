/**
 * Edit Panel view model
 **/
var panelViewModel = function() {
    var self = this;

    // Current section (tatoo, or biography, etc.)
    self.section = ko.observable('');

    self.getUrlSection = ko.computed(function() {
        return '/' + self.section();
    });

    // Store which is form's action (update or insert item).
    self.action = ko.observable('');

    // Observable that store form values.
    self.dataToSend = {
        id: ko.observable(''),
        title: ko.observable(''),
        description: ko.observable(''),
        published: ko.observable(''),
        image: ko.observable(''),
        file: ko.observable('')
    };

    // Message that show CRUD operation status.
    self.statusMessage = ko.observable('');
    self.statusMessageClass = ko.observable('');
    self.statusMessageIsVisible = ko.observable(false);
    self.loaderIsVisible = ko.observable(false);

    // Set message visibility and textual content.
    self.showStatusMessage = function(message, status) {
        self.statusMessage(message);
        self.statusMessageIsVisible(status);
    };

    // Check if actual user action is insert or update.
    self.checkAction = function() {
        self[self.action()]();
    };

    // Insert action.        
    self.insert = function() {
        var url = self.getUrlSection() + '/insert';
        self.sendData(url, 'POST');
    };

    // Update action.
    self.update = function() {
        var url = self.getUrlSection() + '/update/' + self.dataToSend.id();
        self.sendData(url, 'POST');
    };

    // Perform an ajax request to REST api. 
    // This method retrieve the form and send data to the server.
    self.sendData = function(url, method) {
        var formData = new FormData();
        formData.append('id', self.dataToSend.id());
        formData.append('title', self.dataToSend.title());
        formData.append('description', self.dataToSend.description());
        formData.append('published', self.dataToSend.published());

        // Check if file is present.
        if ($('#single-file')[0].files[0] !== undefined) {
            formData.append('file', $('#single-file')[0].files[0]);
        }

        $.ajax({
            url: url,
            type: method,
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
                self.statusMessageClass('alert alert-warning');
                self.loaderIsVisible(true);
                self.showStatusMessage('Caricamento in corso', true);
            },
            success: function(results) {
                if (results.code === 200) {
                    self.statusMessageClass('alert alert-success');
                    self.loaderIsVisible(false);
                    self.showStatusMessage('Inserimento avvenuto con successo', true);
                    masterViewModel.uploadSuccess();

                } else {
                    self.statusMessageClass('alert alert-danger');
                    self.showStatusMessage('Errore nell\'inserimento. Riprova', true);
                }
            }
        });
    };

    // If actual action is update then this method retrieve the data 
    // that must be modified from the server.
    self.loadData = function() {
        $.get(self.getUrlSection() + '/' + self.dataToSend.id(), function(results) {
            if (results) {
                self.dataToSend.id(results.id);
                self.dataToSend.title(results.title);
                self.dataToSend.description(results.description);
                self.dataToSend.published(results.published);
                self.dataToSend.image(results.image);
            }
        });
    };

    // Init action. Retrieve and set, if presents, the id of actual entity and
    // the section name (tattoo, biography, etc).
    self.init = function() {
        self.dataToSend.id($('form').attr('data-id'));
        self.section($('#section').attr('data-section'));
        self.action($('form').attr('data-action'));

        // If actual action for which the form was rendered is 'update'
        // then data to update are loaded.
        if (self.action() === 'update') {
            self.loadData();
        }
    };

    self.init();
};