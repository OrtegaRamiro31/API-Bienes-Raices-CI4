<?php

namespace Config;

use App\Controllers\AuthController;
use App\Controllers\BlogsController;
use App\Controllers\PropiedadesController;
use App\Controllers\PropiedadesVendedorController;
use App\Controllers\RolesController;
use App\Controllers\VendedorController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

$routes->get('api/propiedades', [PropiedadesController::class, 'index']);
$routes->get('api/propiedades/(:num)', [PropiedadesController::class, 'show/$1']);
$routes->post('api/propiedades', [PropiedadesController::class, 'create'], ['filter' => 'authFilter']);
$routes->put('api/propiedades/(:num)', [PropiedadesController::class, 'update/$1'], ['filter' => 'authFilter']);
$routes->delete('api/propiedades/(:num)', [PropiedadesController::class, 'delete/$1'], ['filter' => 'authFilter']);

$routes->get('api/vendedores', [VendedorController::class, 'index']);
$routes->get('api/vendedores/(:num)', [VendedorController::class, 'show/$1']);
$routes->post('api/vendedores', [VendedorController::class, 'create'], ['filter' => 'authFilter']);
$routes->put('api/vendedores/(:num)', [VendedorController::class, 'update/$1'], ['filter' => 'authFilter']);
$routes->delete('api/vendedores/(:num)', [VendedorController::class, 'delete/$1'], ['filter' => 'authFilter']);

$routes->get('api/vendedores/propiedades', [PropiedadesVendedorController::class, 'index']);
$routes->get('api/vendedores/propiedades/(:num)', [PropiedadesVendedorController::class, 'show/$1']);

$routes->get('api/vendedores/(:num)/roles/', [VendedorController::class, 'showSellerRole/$1']);
$routes->get('api/vendedores-roles', [VendedorController::class, 'showAll']);

$routes->get('api/roles', [RolesController::class, 'index']);
$routes->get('api/roles/(:num)/vendedor', [RolesController::class, 'getSellerRoles/$1']);

$routes->get('api/blogs', [BlogsController::class, 'index']);
$routes->post('api/blogs', [BlogsController::class, 'create'], ['filter' => 'authFilter']);
$routes->get('api/blogs/(:num)', [BlogsController::class, 'show/$1']);
$routes->put('api/blogs/(:num)', [BlogsController::class, 'update/$1']);

$routes->post('api/login', [AuthController::class, 'login']);
$routes->post('api/logout', [AuthController::class, 'logout'], ['filter' => 'authFilter']);

// $routes->get('propiedades', [PropiedadesController::class, 'index']);
// $routes->get('propiedades/(:num)', [PropiedadesController::class, 'show/$1']);
// $routes->post('propiedades/', [PropiedadesController::class, 'create']);
// $routes->put('propiedades/(:num)', [PropiedadesController::class, 'update/$1']);
//$routes->resource('/', ['controller => PropiedadesController']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
