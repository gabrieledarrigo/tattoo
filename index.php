<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/config.php';

$app->get('/', 'controllers.app:index');

$app->get('/instagram', 'controllers.instagram:getAll');
$app->get('/instagram/{id}', 'controllers.instagram:getAll');

// Tattoo routes.
$app->get('/tattoo', 'controllers.tattoo:getAll');
$app->get('/tattoo/{id}', 'controllers.tattoo:getItem');
$app->post('/tattoo/insert', 'controllers.tattoo:insertItem');
$app->post('/tattoo/update/{id}', 'controllers.tattoo:updateItem');
$app->delete('/tattoo/delete/{id}', 'controllers.tattoo:deleteItem');

// Sketch routes.
$app->get('/sketch', 'controllers.sketch:getAll');
$app->get('/sketch/{id}', 'controllers.sketch:getItem');
$app->post('/sketch/insert', 'controllers.sketch:insertItem');
$app->post('/sketch/update/{id}', 'controllers.sketch:updateItem');
$app->delete('/sketch/delete/{id}', 'controllers.sketch:deleteItem');
               
// Biography routes.
$app->get('/biography', 'controllers.biography:getAll');
$app->get('/biography/{id}', 'controllers.biography:getItem');
$app->post('/biography/insert', 'controllers.biography:insertItem');
$app->post('/biography/update/{id}', 'controllers.biography:updateItem');
$app->delete('/biography/delete/{id}', 'controllers.biography:deleteItem');

// Login routes.
$app->get('/admin', 'controllers.auth:access');
$app->get('/login', 'controllers.auth:login');
$app->get('/logout', 'controllers.auth:logout');

// Admin Routes.
$app->get('/admin/tattoo/insert', 'controllers.admin-tattoo:insert');
$app->get('/admin/tattoo/update', 'controllers.admin-tattoo:edit');
$app->get('/admin/tattoo/{page}', 'controllers.admin-tattoo:index')->value('page', 1);

$app->get('/admin/sketch/insert', 'controllers.admin-sketch:insert');
$app->get('/admin/sketch/update', 'controllers.admin-sketch:edit');
$app->get('/admin/sketch/{page}', 'controllers.admin-sketch:index')->value('page', 1);

$app->get('/admin/biography/insert', 'controllers.admin-biography:insert');
$app->get('/admin/biography/update/{id}', 'controllers.admin-biography:edit');
$app->get('/admin/biography/{page}', 'controllers.admin-biography:index')->value('page', 1);

// Let's start!
$app->run();