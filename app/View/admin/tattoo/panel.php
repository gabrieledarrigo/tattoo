{% extends "admin/layout.php" %}

{% block content %}
<div class="row">
    <div data-bind="with: panel" class="span12">
        <div data-bind="visible: statusMessageIsVisible, css: statuMessageClass">
            <h4 data-bind="text: statusMessage"></h4>
            <br/>
            <a href="/admin/tattoo" class="btn btn-large success">
                Torna all'elenco di Tattoo
            </a>
        </div>
    </div>

    <div class="span6" data-bind="with: panel">
        <header>
            <h2>{{title}}</h2>
        </header>

        <form id="form-tattoo" enctype="multipart/form-data" method="post" 
              data-action="{{ action }}"
              data-bind="submit: checkAction"
              data-id="{{ id }}">

            <label>Titolo</label>
            <input class="input-xxlarge" type="text" name="title" required="required"
                   placeholder="Scrivi qui il titolo"
                   data-bind="value: dataToSend.title"/>

            <label>Descrizione</label>
            <textarea class="input-xxlarge" name="description" rows="12" required="required" 
                      placeholder="Scrivi qui la descrizione"
                      data-bind="value: dataToSend.description">
            </textarea>

            <br/>

            <label>Pubblicato</label>

            <label class="radio">
                <input type="radio" name="published" value="SI" required="required" 
                       data-bind="checked: dataToSend.published"/>
                SI
            </label>

            <label class="radio">
                <input type="radio" name="published" value="NO" required="required"
                       data-bind="checked: dataToSend.published"/>
                NO
            </label>

            <div class="btn btn btn-large btn-primary" data-bind="click: $root.showUploader">
                Carica file
            </div>

            <input id="single-file" type="file"
                   data-bind="event : {change: $root.uploader.fileHandler}"/>

            <br/>
            <br/>

            <input type="hidden" name="id" data-bind="value: dataToSend.id"/>

            <input type="submit" value="Aggiungi Tattoo" class="btn btn-large btn-success"/>
        </form>
    </div>

    <div class="span6" data-bind="if: panel.dataToSend.image, visible: $root.previewIsVisible">
        <div class="image-uploaded">
            <h2>Immagine Caricata</h2>
            <img data-bind="attr : {src : panel.dataToSend.image()}"/>
        </div>
    </div>

    <div class="span6" data-bind="with: uploader">

        <div id="uploader-panel" data-bind="visible: uploaderIsVisible">
            <h2>Immagini caricate</h2>

            <div class="alert alert-error" data-bind="visible: warningMessageIsVisible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Errore</strong> Puoi caricare solo immagini .jpg, .bmp, .gif
            </div>

            <div id="progress-panel">
                <h4>Percentuale caricamento file</h4>

                <div class="progress progress-striped active">
                    <div class="bar" style="width: 0%;"></div>
                </div>
            </div>

            <div class="image-list">
                <ul class="thumbnails" data-bind="foreach: images">
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img data-bind="attr : {src : src, title: name}">
                        </a>

                        <h4>Titolo</h4>
                        <input type="text" data-bind="value: name" />

                        <h4>Dimensioni</h4>
                        <p data-bind="text: size + ' Kilobyte'"></p>

                        <div class="btn btn-danger" data-bind="click: $parent.removeImage">
                            Rimuovi
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="span12">
        <a href="/admin/tattoo" class="btn btn-large btn-warning pull-right">
            Torna all'elenco
        </a>
    </div>
</div>
{% endblock %}

{% block javascript %}
{{ parent() }}
<script>
    /**
     * Edit Panel view model
     * 
     **/
    var panelViewModel = function() {
        var self = this;

        // Observable that store form values.
        self.dataToSend = {
            id: ko.observable(''),
            title: ko.observable(''),
            description: ko.observable(''),
            published: ko.observable(''),
            image: ko.observable(''),
            file: ko.observable('')
        };

        // Store which is form action (update or insert item).
        self.action = ko.observable('');

        // Message that show CRUD operation status.
        self.statuMessageClass = ko.observable('span5 alert alert-danger');
        self.statusMessage = ko.observable('');
        self.statusMessageIsVisible = ko.observable(false);

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
            var url = '/biography/insert';
            self.sendData(url, 'POST');
        };

        // Update action.
        self.update = function() {
            var url = '/biography/update/' + self.dataToSend.id();
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
                success: function(results) {
                    if (results.code === 200) {
                        self.statuMessageClass('alert alert-success');
                        self.showStatusMessage('Inserimento avvenuto con successo', true);

                    } else {
                        self.statuMessageClass('span5 alert alert-danger');
                        self.showStatusMessage('Errore nell\'inserimento. Riprova', true);
                    }
                }
            });
        };

        // If actual action is update then this method retrieve the data 
        // that must be modified from the server.
        self.loadData = function() {
            $.get('/biography/' + self.dataToSend.id(), function(results) {
                if (results) {
                    self.dataToSend.id(results.id);
                    self.dataToSend.title(results.title);
                    self.dataToSend.description(results.description);
                    self.dataToSend.published(results.published);
                    self.dataToSend.image(results.image);
                }
            });
        };

        // Init action. Retrieve and set, if presents, the id of actual entity.
        self.init = function() {
            self.dataToSend.id($('form').attr('data-id'));
            self.action($('form').attr('data-action'));

            // If actual action for which the form was rendered is 'update'
            // then data to update are loaded.
            if (self.action() === 'update') {
                self.loadData();
            }
        };

        self.init();

    };

    // Uploader view model handle all upload action from the user
    var uploaderViewModel = function() {
        var self = this;

        // Store a reference to the image readed by FileReader.
        self.images = ko.observableArray([]);

        // Uploader and warning message visibility.
        self.uploaderIsVisible = ko.observable(false);
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

        // Hide current image.
        self.removeImage = function(data, event) {
            self.images.removeAll();
            self.uploaderIsVisible(false);
        };

        // Remove the mime type notation (fore example .jpeg, .gif)
        // from the file name.
        self.setImageName = function(name) {
            return name.split('.')[0];
        };

        // Handle file upload.
        self.fileHandler = function(data, event) {
            self.uploaderIsVisible(true);

            var originalEvent = event;

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
                            src: event.target.result
                        };
                        // Check if uploader is for single or multiple files.
                        if (originalEvent.currentTarget.id === 'single-file') {
                            self.images.splice(0, 1, toPush);
                        } else {
                            self.images.push(toPush);
                        }

                    };
                })(img);
                // Read data from uploaded image.
                reader.readAsDataURL(fileList[i]);
            }

        };

    };
    // Master view model.
    // Se visibility of sub view model.
    var biographyViewModel = {
        panel : new panelViewModel(),
        uploader : new uploaderViewModel(),
        panelIsVisible : ko.observable(true),
        previewIsVisible : ko.observable(true),
        uploaderIsVisible : ko.observable(false),
        progressIsVisible : ko.observable(false),
        showUploader: function() {
            $('#single-file').click();
            biographyViewModel.uploaderIsVisible(true);
            biographyViewModel.previewIsVisible(false);
        }
    };

    ko.applyBindings(biographyViewModel);
</script>
{% endblock %}