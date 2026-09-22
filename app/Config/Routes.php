<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Home::index');
$routes->get('companies', 'Companies::index');
$routes->get('companies/datatable', 'Companies::datatable');
$routes->get('companies/new', 'Companies::new');
$routes->post('companies', 'Companies::create');
$routes->get('companies/(:num)/edit', 'Companies::edit/$1');
$routes->post('companies/(:num)', 'Companies::update/$1');
$routes->post('companies/(:num)/delete', 'Companies::delete/$1');
$routes->get('products', 'Products::index');
$routes->get('products/datatable', 'Products::datatable');
$routes->get('products/new', 'Products::new');
$routes->post('products', 'Products::create');
$routes->get('products/(:num)/edit', 'Products::edit/$1');
$routes->post('products/(:num)', 'Products::update/$1');
$routes->post('products/(:num)/delete', 'Products::delete/$1');
$routes->get('quotations', 'Quotations::index');
$routes->get('quotations/datatable', 'Quotations::datatable');
$routes->get('quotations/new', 'Quotations::new');
$routes->post('quotations', 'Quotations::create');
$routes->get('quotations/(:num)/edit', 'Quotations::edit/$1');
$routes->post('quotations/(:num)/update', 'Quotations::update/$1');
$routes->post('quotations/(:num)/status', 'Quotations::changeStatus/$1');
$routes->get('quotations/(:num)', 'Quotations::show/$1');
$routes->get('quotations/(:num)/pdf', 'Quotations::pdf/$1');
$routes->get('quotations/(:num)/catalog/preview', 'Quotations::catalogPreview/$1');
$routes->get('quotations/(:num)/catalog', 'Quotations::catalog/$1');
$routes->post('quotations/(:num)/delete', 'Quotations::delete/$1');
$routes->get('settings/quotation', 'Settings::quotation');
$routes->post('settings/quotation', 'Settings::saveQuotation');
