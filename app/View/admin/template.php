{% extends "admin/layout.php" %}

{% block content %}



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
        <tr>
            <td>{{ item['id'] }}</td>
            <td>{{ item['title'] }}</td>
            <td>{{ item['description'] }}</td>
            <td>
                <img src="http://upload.wikimedia.org/wikipedia/commons/4/41/Siberischer_tiger_de_edit02.jpg" width="100" heigth="100" title="" alt="" class="img-rounded"/>
            </td>
            <td>{{ item['date'] }}</td>
            <td>{{ item['published'] }}</td>
            <td>
                <div class="btn btn-inverse">
                    Modifica
                </div>
            </td>
            <td>
                <div class="btn btn-danger">
                    Cancella
                </div>
            </td>
        </tr>
        {% endfor %}
    </tbody>
</table>

<div class="span4">
    <div class="btn btn-large btn-success">
        Aggiungi 
    </div>
</div>



<!--    <form id="frmBiography" method="post" action="{{ app.request.baseUrl }}/biography/insert">
        <label>Titolo</label>
        <input type="text" name="inptTitle" required="required"/>
        <label>Descrizione</label>
        <input type="text" name="inptDescription" required="required"/><br/>
        <input type="submit" value="invia" class="btn"/>
    </form>-->

{% endblock %}