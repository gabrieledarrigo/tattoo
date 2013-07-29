/**
 * View model that handle items behavior
 * on admin's index table.
 */
var indexViewModel = function() {
    var self = this;

    self.id = ko.observable('');
    self.section = ko.observable('');

    // Return the absolute path of the section.
    self.getUrlSection = ko.computed(function() {
        return '/' + self.section();
    });

    // Message status shown during user's intercation
    self.statusMessage = ko.observable('');
    self.statusMessageIsVisible = ko.observable(false);

    // Function that control status message visibility.
    self.showStatusMessage = function(message, status) {
        self.statusMessage(message);
        self.statusMessageIsVisible(status);
    };
    
    // Hide status message.
    self.hideStatusMessage = function() {
         self.statusMessageIsVisible(false);
    }

    // Message show to the user before deleting an item.
    self.firstStep = function(data, event) {
        self.id($(event.currentTarget).attr('data-id'));
        self.showStatusMessage('Sei sicuro di voler cancellare l\'elemento?', true);
    };

    // Delete an item.
    self.deleteItem = function() {
        var url = self.getUrlSection() + '/delete/' + self.id();

        $.ajax({
            url: url,
            type: 'DELETE',
            success: function(result) {
                if (result.code === 200) {
                    $('tr[data-id=' + self.id() + ']').remove();
                    self.showStatusMessage('', false);
                }
            }
        });
    };

    // Init the status of the view model setting
    // actual section (tatto, sketch etc.)
    self.init = function() {
        self.section($('#section').attr('data-section'));
    };

    self.init();

};

ko.applyBindings(new indexViewModel());