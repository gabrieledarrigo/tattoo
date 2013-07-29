<?php

define('WEB_ROOT', '/web');

define('UPLOAD_PATH', WEB_ROOT . '/upload');

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../src/View',
));

$app->register(new FranMoreno\Silex\Provider\PagerfantaServiceProvider(), array(
    'pagerfanta.view.options' => array(
        'default_view' => 'twitter_bootstrap'
    )
));

//$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
//    'db.options' => array(
//        'driver' => 'pdo_mysql',
//        'dbname' => 'lvmmioky_pliz_tattoo',
//        'host' => 'mysql.netsons.com',
//        'user' => 'lvmmioky_pliz',
//        'password' => 'T4KBXq83XJ'
//    )
//));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'pliz_tattoo',
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => ''
    )
));

$app->register(new Silex\Provider\SessionServiceProvider());


//$app['security.encoder.digest'] = $app->share(function ($app) {
//    // use the sha1 algorithm
//    // don't base64 encode the password
//    // use only 1 iteration
//    return new MessageDigestPasswordEncoder('sha1', false, 1);
//});

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin',
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/admin/login_check',
                'default_target_path' => '/admin/tattoo',
                'always_use_default_target_path' => true
            ),
            'logout' => array('logout_path' => '/admin/logout'),
            'users' => array(
                'pliz' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==')
            )
        ),
)));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/log.txt',
));

$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'host' => 'mail.pliztattoo.com',
    'port' => '25',
    'username' => 'admin@pliztattoo.com',
    'password' => 'KCShHo0GsuaL',
    'encryption' => null,
    'auth_mode' => null
);

/**
 * =============================================================================
 *                      CONTROLLER AS A SERVICE                                *
 * =============================================================================
 */
$app['controllers.application'] = $app->share(function($app) {
            return new Controllers\ApplicationController($app);
        });

$app['controllers.tattoo'] = $app->share(function($app) {
            return new Controllers\TattooController($app, new Models\Tattoo($app));
        });

$app['controllers.sketch'] = $app->share(function($app) {
            return new Controllers\SketchController($app, new Models\Sketch($app));
        });

$app['controllers.instagram'] = $app->share(function($app) {
            return new Controllers\InstagramController($app);
        });

$app['controllers.biography'] = $app->share(function($app) {
            return new Controllers\BiographyController($app, new Models\Biography($app));
        });

$app['controllers.contact'] = $app->share(function($app) {
            return new Controllers\ContactController($app);
        });

$app['controllers.admin-tattoo'] = $app->share(function($app) {
            return new Controllers\AdminTattooController($app, new Models\Tattoo($app));
        });

$app['controllers.admin-sketch'] = $app->share(function($app) {
            return new Controllers\AdminSketchController($app, new Models\Sketch($app));
        });

$app['controllers.admin-biography'] = $app->share(function($app) {
            return new Controllers\AdminBiographyController($app, new Models\Biography($app));
        });