<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Pages::login');
$routes->get('/dashboard', 'Pages::dashboard');
$routes->get('/inspect', 'Pages::inspect');
$routes->get('/query', 'Pages::query');
$routes->get('/test', 'Pages::test');
$routes->post('/read/item_query', 'Read::item_query');
$routes->post('/read/item_inspect', 'Read::item_inspect');
$routes->post('/read/save_inspection', 'Read::save_inspection');
$routes->post('itemsearch/search', 'ItemSearch::search');
$routes->post('inspector/register', 'Inspector::register');
$routes->post('inspector/login', 'Inspector::login');
$routes->post('inspector/nfc_login', 'Inspector::nfc_login');
$routes->post('inspector/logout', 'Inspector::logout');

$routes->setAutoRoute(true);