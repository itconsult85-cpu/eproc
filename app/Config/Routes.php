<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->addPlaceholder('sha1', '[a-fA-F0-9]{40}');
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
    $routes->get('companies/(:sha1)/edit', 'Companies::edit/$1', ['filter' => 'permission:companies.edit']);
    $routes->post('companies/(:sha1)', 'Companies::update/$1', ['filter' => 'permission:companies.edit']);
    $routes->post('companies/(:sha1)/delete', 'Companies::delete/$1', ['filter' => 'permission:companies.delete']);
    $routes->get('products', 'Products::index', ['filter' => 'permission:products.view']);
    $routes->get('products/datatable', 'Products::datatable', ['filter' => 'permission:products.view']);
    $routes->get('products/new', 'Products::new', ['filter' => 'permission:products.create']);
    $routes->post('products', 'Products::create', ['filter' => 'permission:products.create']);
    $routes->get('products/(:sha1)/edit', 'Products::edit/$1', ['filter' => 'permission:products.edit']);
    $routes->post('products/(:sha1)', 'Products::update/$1', ['filter' => 'permission:products.edit']);
    $routes->post('products/(:sha1)/delete', 'Products::delete/$1', ['filter' => 'permission:products.delete']);
    $routes->get('quotations', 'Quotations::index', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/datatable', 'Quotations::datatable', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/new', 'Quotations::new', ['filter' => 'permission:quotations.create']);
    $routes->post('quotations', 'Quotations::create', ['filter' => 'permission:quotations.create']);
    $routes->get('quotations/(:sha1)/edit', 'Quotations::edit/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:sha1)/update', 'Quotations::update/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:sha1)/status', 'Quotations::changeStatus/$1', ['filter' => 'permission:quotations.status']);
    $routes->post('quotations/(:sha1)/extend-validity', 'Quotations::extendValidity/$1', ['filter' => 'permission:quotations.edit']);
    $routes->post('quotations/(:sha1)/negotiations', 'Quotations::proposeNegotiation/$1', ['filter' => 'permission:quotations.status']);
    $routes->post('quotations/(:sha1)/negotiations/(:sha1)/(:segment)', 'Quotations::respondNegotiation/$1/$2/$3', ['filter' => 'permission:quotations.status']);
    $routes->get('quotations/(:sha1)', 'Quotations::show/$1', ['filter' => 'permission:quotations.view']);
    $routes->get('quotations/(:sha1)/pdf', 'Quotations::pdf/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:sha1)/proforma-invoice', 'Quotations::proformaInvoice/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:sha1)/catalog/preview', 'Quotations::catalogPreview/$1', ['filter' => 'permission:quotations.export']);
    $routes->get('quotations/(:sha1)/catalog', 'Quotations::catalog/$1', ['filter' => 'permission:quotations.export']);
    $routes->post('quotations/(:sha1)/delete', 'Quotations::delete/$1', ['filter' => 'permission:quotations.delete']);
    $routes->get('settings/quotation', 'Settings::quotation', ['filter' => 'permission:settings.quotation']);
    $routes->post('settings/quotation', 'Settings::saveQuotation', ['filter' => 'permission:settings.quotation']);
    $routes->get('users', 'Users::index', ['filter' => 'permission:users.manage']);
    $routes->get('users/new', 'Users::new', ['filter' => 'permission:users.manage']);
    $routes->post('users', 'Users::save', ['filter' => 'permission:users.manage']);
    $routes->get('users/(:sha1)/edit', 'Users::edit/$1', ['filter' => 'permission:users.manage']);
    $routes->post('users/(:sha1)', 'Users::save/$1', ['filter' => 'permission:users.manage']);
    $routes->post('users/(:sha1)/delete', 'Users::delete/$1', ['filter' => 'permission:users.manage']);
});
