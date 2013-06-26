<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Pliz Tattoo Administration</title>
        <meta name="description" content="Pliz Tattoo Administration">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="{{ app.request.baseUrl }}/web/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ app.request.baseUrl }}/web/css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="{{ app.request.baseUrl }}/web/css/main.css">

        <script src="{{ app.request.baseUrl }}/web/js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>

        <div class="container">

            <div class="row">
                <div class="page-header span12">
                    <h1>Pliz Tatoo</h1>
                </div>

                <div class="span12 navbar">
                    <div class="navbar-inner ">
                        <span class="brand">{{title}}</span>
                        <ul class="nav">
                            <li>
                                <a href="{{ app.request.baseUrl }}/admin/tattoo">
                                    Tattoo
                                </a>
                            </li>
                            <li>
                                <a href="{{ app.request.baseUrl }}/admin/sketch">
                                    Sketch
                                </a>
                            </li>
                            <li>
                                <a href="{{ app.request.baseUrl }}/admin/biography">
                                    Biografia
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {% block content %} {% endblock %}

        </div> 

        {% block javascript %}
        <script src="{{ app.request.baseUrl }}/web/js/vendor/jquery-1.9.1.min.js"></script>
        <script src="{{ app.request.baseUrl }}/web/js/vendor/knockout-2.2.1.js"></script>
        <script src="{{ app.request.baseUrl }}/web/js/vendor/bootstrap.min.js"></script>
        {% endblock %}
    </body>
</html>