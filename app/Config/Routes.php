<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// route homepage
$routes->setDefaultController('Homepage');
$routes->get('/', 'homepage::index');
$routes->group('', ['filter' => 'auth'], function ($routes) {});

// route auth with filter auth:page
$routes->group('', ['filter' => 'auth:page'], function ($routes) {
    $routes->get('login', 'auth::index_login');
    $routes->post('login', 'auth::login');
    $routes->get('register', 'auth::index_register');
    $routes->post('register', 'auth::register');
});

$routes->get('/logout', 'auth::logout');
