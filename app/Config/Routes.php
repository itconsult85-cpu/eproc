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
    $routes->get('companies/(:segment)/edit', 'Companies::edit/$1', ['filter' => 'permission:companies.edit']);
    $routes->post('companies/(:segment)', 'Companies::update/$1', ['filter' => 'permission:companies.edit']);
    $routes->post('companies/(:segment)/delete', 'Companies::delete/$1', ['filter' => 'permission:companies.delete']);
    $routes->get('products', 'Products::index', ['filter' => 'permission:products.view']);
    $routes->get('products/datatable', 'Products::datatable', ['filter' => 'permission:products.view']);
    $routes->get('products/new', 'Products::new', ['filter' => 'permission:products.create']);
    $routes->post('products', 'Products::create', ['filter' => 'permission:products.create']);
    $routes->get('products/(:segment)/edit', 'Products::edit/$1', ['filter' => 'permission:products.edit']);
    $routes->post('products/(:segment)', 'Products::update/$1', ['filter' => 'permission:products.edit']);
    $routes->post('products/(:segment)/delete', 'Products::delete/$1', ['filter' => 'permission:products.delete']);
    $routes->get('quotations', 'Quotations::index', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/datatable', 'Quotations::datatable', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/new', 'Quotations::new', ['filter' => 'permission:quotations.create']);
    $routes->post('quotations', 'Quotations::create', ['filter' => 'permission:quotations.create']);
    $routes->get('quotations/(:segment)/edit', 'Quotations::edit/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:segment)/update', 'Quotations::update/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:segment)/status', 'Quotations::changeStatus/$1', ['filter' => 'permission:quotations.status']);
    $routes->post('quotations/(:segment)/extend-validity', 'Quotations::extendValidity/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:segment)/negotiations', 'Quotations::proposeNegotiation/$1', ['filter' => 'permission:quotations.status']);
    $routes->post('quotations/(:segment)/negotiations/(:segment)/(:segment)', 'Quotations::respondNegotiation/$1/$2/$3', ['filter' => 'permission:quotations.status']);
    $routes->get('quotations/(:segment)', 'Quotations::show/$1', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/(:segment)/pdf', 'Quotations::pdf/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:segment)/proforma-invoice', 'Quotations::proformaInvoice/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:segment)/catalog/preview', 'Quotations::catalogPreview/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:segment)/catalog', 'Quotations::catalog/$1', ['filter' => 'permission:quotations.export']);
    $routes->post('quotations/(:segment)/delete', 'Quotations::delete/$1', ['filter' => 'permission:quotations.delete']);
    $routes->get('settings/quotation', 'Settings::quotation', ['filter' => 'permission:settings.quotation']);
    $routes->post('settings/quotation', 'Settings::saveQuotation', ['filter' => 'permission:settings.quotation']);
    $routes->get('users', 'Users::index', ['filter' => 'permission:users.manage']);
    $routes->get('users/new', 'Users::new', ['filter' => 'permission:users.manage']);
    $routes->post('users', 'Users::save', ['filter' => 'permission:users.manage']);
    $routes->get('users/(:segment)/edit', 'Users::edit/$1', ['filter' => 'permission:users.manage']);
    $routes->post('users/(:segment)', 'Users::save/$1', ['filter' => 'permission:users.manage']);
    $routes->post('users/(:segment)/delete', 'Users::delete/$1', ['filter' => 'permission:users.manage']);
});
