<?php
/**
 * Define all routes for the application.
 */

// Front end application routes.
$app->get('/', 'controllers.application:index');
$app->get('/template/{name}', 'controllers.application:loadAjaxTemplate');

// Instagram routes.
$app->get('/instagram', 'controllers.instagram:getAll');
$app->get('/instagram/{id}', 'controllers.instagram:getItem');

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

// Contact route.
$app->post('/contact', 'controllers.contact:send');

// Login routes.
$app->get('/admin', 'controllers.admin-tattoo:index')->value('page', 1);
$app->get('/login', 'controllers.application:login');

// Admin Routes.
$app->get('/admin/tattoo/insert', 'controllers.admin-tattoo:insert');
$app->get('/admin/tattoo/update/{id}', 'controllers.admin-tattoo:edit');
$app->get('/admin/tattoo/{page}', 'controllers.admin-tattoo:index')->value('page', 1);

$app->get('/admin/sketch/insert', 'controllers.admin-sketch:insert');
$app->get('/admin/sketch/update/{id}', 'controllers.admin-sketch:edit');
$app->get('/admin/sketch/{page}', 'controllers.admin-sketch:index')->value('page', 1);

$app->get('/admin/biography/insert', 'controllers.admin-biography:insert');
$app->get('/admin/biography/update/{id}', 'controllers.admin-biography:edit');
$app->get('/admin/biography/{page}', 'controllers.admin-biography:index')->value('page', 1);