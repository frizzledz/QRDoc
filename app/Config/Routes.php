<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    
    // QR Code routes
    $routes->get('qrcode', 'QrCode::index');
    $routes->get('qrcode/generate', 'QrCode::generate');
    $routes->post('qrcode/save', 'QrCode::save');
    $routes->get('qrcode/pending', 'QrCode::pending');
    $routes->post('qrcode/verify/(:num)', 'QrCode::verify/$1');
    $routes->post('qrcode/reject/(:num)', 'QrCode::reject/$1');
    $routes->get('qrcode/scan', 'QrCode::scan');
    $routes->get('qrcode/scan/(:any)', 'QrCode::scan/$1');
    
    // Profile routes
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    
    // Add this new route
    $routes->post('qrcode/embedPdf', 'QrCode::embedPdf');
    
    // Add these new routes for imbue feature
    $routes->get('qrcode/imbue', 'QrCode::imbue');
    $routes->post('qrcode/embedCustomPdf', 'QrCode::embedCustomPdf');
});
