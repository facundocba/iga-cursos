<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

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
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Añadimos esta línea para manejar todas las solicitudes OPTIONS
$routes->options('(:any)', 'Home::options');

// Rutas para la API
$routes->group('api', function ($routes) {
    // Rutas para cursos
    $routes->get('cursos', 'CursosController::index');
    $routes->get('cursos/(:num)', 'CursosController::show/$1');
    $routes->post('cursos', 'CursosController::create');
    $routes->put('cursos/(:num)', 'CursosController::update/$1');
    $routes->delete('cursos/(:num)', 'CursosController::delete/$1');

    // Rutas para compras
    $routes->get('compras', 'ComprasController::index');
    $routes->get('compras/estadisticas', 'ComprasController::estadisticas');
    $routes->get('compras/mis-compras', 'ComprasController::misCompras');
    $routes->post('compras', 'ComprasController::create');
    $routes->put('compras/(:num)/estado', 'ComprasController::actualizarEstado/$1');

    // Rutas para administradores
    $routes->post('admin/login', 'AdminController::login');
    $routes->get('admin/verificar-token', 'AdminController::verificarToken');
});

// Manejo de solicitudes OPTIONS para CORS
$routes->options('api/(:any)', function () {
    return true;
});

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
