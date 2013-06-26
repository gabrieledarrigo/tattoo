{% extends "admin/layout.php" %}

{% block content %}
<div id="tattoo" class="row">
    <div class="span5 alert alert-danger" data-bind="visible: statusMessageIsVisible">
        <h4 data-bind="text: statusMessage"></h4>
        <br/>
        <div class="btn btn-large btn-danger" data-bind="click: deleteItem">
            Conferma
        </div>
        <div class="btn btn-large btn-inverse">
            Annulla
        </div>
    </div>

    <table class="span12 table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Descrizione</th>
                <th>Immagine</th>
                <th>Pubblicato</th>
                <th>Data pubblicazione</th>
                <th>Modifica</th>
                <th>Cancella</th>
            </tr>
        </thead>
        <tbody>
            {% for key, item in items %}
            <tr data-id="{{ item['id'] }}">
                <td>{{ item['id'] }}</td>
                <td>{{ item['title'] }}</td>
                <td>{{ item['description'] }}</td>
                <td>
                    {% if item['image'] %}
                    <img src="{{ app.request.baseUrl ~ item['image']}}" width="90" heigth="90" class="img-rounded"/>
                    {% else %}
                    Nessun immagine.
                    {% endif %}
                </td>
                <td>{{ item['published'] }}</td>
                <td>{{ item['date'] }}</td>
                <td>
                    <a href="{{ app.request.baseUrl }}/admin/tattoo/update/{{ item['id'] }}" class="btn btn-inverse">
                        Modifica
                    </a>
                </td>
                <td>
                    <div class="btn btn-danger" data-id="{{ item['id'] }}" data-bind="click: firstStep">
                        Cancella
                    </div>
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>

    <div class="span12 pagination">
        {{ pagerfanta(items) }}

        <a href="{{ app.request.baseUrl }}/admin/tattoo/insert" class="btn btn-large btn-success" >
            Aggiungi Tattoo
        </a>
    </div>
</div>
{% endblock %}

{% block javascript %}
{{ parent() }}
<script>
    var tableViewModel = function() {
        var self = this;

        self.id = ko.observable('');

        self.statusMessage = ko.observable('');
        self.statusMessageIsVisible = ko.observable(false);

        self.showStatusMessage = function(message, status) {
            self.statusMessage(message);
            self.statusMessageIsVisible(status);
        };

        self.firstStep = function(data, event) {
            self.id($(event.currentTarget).attr('data-id'));
            self.showStatusMessage('Sei sicuro di voler cancellare l\'elemento?', true);
        };

        self.deleteItem = function() {
            var url = '/tattoo/delete/' + self.id();

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

    };

    ko.applyBindings(new tableViewModel());
</script>
{% endblock %}