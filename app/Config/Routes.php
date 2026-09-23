<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('setup', 'Auth::setup');
$routes->post('setup', 'Auth::createFirstAdmin');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);
$routes->get('password', 'Auth::password', ['filter' => 'auth']);
$routes->post('password', 'Auth::updatePassword', ['filter' => 'auth']);
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Home::index', ['filter' => 'permission:dashboard.view']);
    $routes->get('dashboard', 'Home::index', ['filter' => 'permission:dashboard.view']);
    $routes->get('companies', 'Companies::index', ['filter' => 'permission:companies.view']);
    $routes->get('companies/datatable', 'Companies::datatable', ['filter' => 'permission:companies.view']);
    $routes->get('companies/new', 'Companies::new', ['filter' => 'permission:companies.create']);
    $routes->post('companies', 'Companies::create', ['filter' => 'permission:companies.create']);
    $routes->get('companies/(:num)/edit', 'Companies::edit/$1', ['filter' => 'permission:companies.edit']);
    $routes->post('companies/(:num)', 'Companies::update/$1', ['filter' => 'permission:companies.edit']);
    $routes->post('companies/(:num)/delete', 'Companies::delete/$1', ['filter' => 'permission:companies.delete']);
    $routes->get('products', 'Products::index', ['filter' => 'permission:products.view']);
    $routes->get('products/datatable', 'Products::datatable', ['filter' => 'permission:products.view']);
    $routes->get('products/new', 'Products::new', ['filter' => 'permission:products.create']);
    $routes->post('products', 'Products::create', ['filter' => 'permission:products.create']);
    $routes->get('products/(:num)/edit', 'Products::edit/$1', ['filter' => 'permission:products.edit']);
    $routes->post('products/(:num)', 'Products::update/$1', ['filter' => 'permission:products.edit']);
    $routes->post('products/(:num)/delete', 'Products::delete/$1', ['filter' => 'permission:products.delete']);
    $routes->get('quotations', 'Quotations::index', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/datatable', 'Quotations::datatable', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/new', 'Quotations::new', ['filter' => 'permission:quotations.create']);
    $routes->post('quotations', 'Quotations::create', ['filter' => 'permission:quotations.create']);
    $routes->get('quotations/(:num)/edit', 'Quotations::edit/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:num)/update', 'Quotations::update/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:num)/status', 'Quotations::changeStatus/$1', ['filter' => 'permission:quotations.status']);
    $routes->get('quotations/(:num)', 'Quotations::show/$1', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/(:num)/pdf', 'Quotations::pdf/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:num)/catalog/preview', 'Quotations::catalogPreview/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:num)/catalog', 'Quotations::catalog/$1', ['filter' => 'permission:quotations.export']);
    $routes->post('quotations/(:num)/delete', 'Quotations::delete/$1', ['filter' => 'permission:quotations.delete']);
    $routes->get('settings/quotation', 'Settings::quotation', ['filter' => 'permission:settings.quotation']);
    $routes->post('settings/quotation', 'Settings::saveQuotation', ['filter' => 'permission:settings.quotation']);
    $routes->get('users', 'Users::index', ['filter' => 'permission:users.manage']);
    $routes->get('users/new', 'Users::new', ['filter' => 'permission:users.manage']);
    $routes->post('users', 'Users::save', ['filter' => 'permission:users.manage']);
    $routes->get('users/(:num)/edit', 'Users::edit/$1', ['filter' => 'permission:users.manage']);
    $routes->post('users/(:num)', 'Users::save/$1', ['filter' => 'permission:users.manage']);
    $routes->post('users/(:num)/delete', 'Users::delete/$1', ['filter' => 'permission:users.manage']);
});