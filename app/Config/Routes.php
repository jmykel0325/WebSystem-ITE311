<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'MyHome::index');      
$routes->get('myhome', 'MyHome::index'); 

