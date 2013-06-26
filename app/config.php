<?php

define('WEB_ROOT', '/web');
define('UPLOAD_PATH', WEB_ROOT . '/upload');

$app = new \Silex\Application();

$app['debug'] = true;

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());

$app->register(new \Silex\Provider\ValidatorServiceProvider());

$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/View',
));

$app->register(new \FranMoreno\Silex\Provider\PagerfantaServiceProvider(), array(
    'pagerfanta.view.options' => array(
        'default_view' => 'twitter_bootstrap'
    )
));

$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'pliz_tattoo',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'root'
    )
));

$app['controllers.tattoo'] = $app->share(function($app) {
            return new \Controllers\TattoController($app);
        });

$app['controllers.sketch'] = $app->share(function($app) {
            return new \Controllers\SketchController($app);
        });

$app['controllers.instagram'] = $app->share(function($app) {
            return new \Controllers\InstagramController($app);
        });

$app['controllers.biography'] = $app->share(function($app) {
            return new \Controllers\BiographyController($app);
        });

$app['controllers.admin-tattoo'] = $app->share(function($app) {
            return new \Controllers\AdminTattooController($app);
        });

$app['controllers.admin-sketch'] = $app->share(function($app) {
            return new \Controllers\AdminSketchController($app);
        });

$app['controllers.admin-biography'] = $app->share(function($app) {
            return new \Controllers\AdminBiographyController($app);
        });