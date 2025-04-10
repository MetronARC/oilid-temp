<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Pages::login');
$routes->get('/dashboard', 'Pages::dashboard');
$routes->get('/inspect', 'Pages::inspect');
$routes->get('/query', 'Pages::query');
$routes->post('/read/item_query', 'Read::item_query');
$routes->post('/read/item_inspect', 'Read::item_inspect');

$routes->setAutoRoute(true);