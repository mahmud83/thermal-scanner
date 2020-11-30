<?php /** @noinspection PhpIncludeInspection */

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Authentication');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('/', 'Authentication::index');
$routes->get('signin', 'Authentication::index');
$routes->post('signin', 'Authentication::signIn');
$routes->get('signout', 'Authentication::signOut');

$routes->get('profile', 'Profile::index');
$routes->post('profile', 'Profile::editProfile');

$routes->get('verify', 'Authentication::verifyConnection');

$routes->get('dashboard', 'Dashboard::index');

$routes->get('detection', 'DetectionHistory::index');
$routes->get('detection/list', 'DetectionHistory::getDetectionList');

/**
 * --------------------------------------------------------------------
 * REST API Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('api/ack', 'Rest/Authentication::getAck');
$routes->post('api/ack', 'Rest/Authentication::sendAck');
$routes->post('api/ack/reset', 'Rest/Authentication::resetAck');

$routes->get('api/log/reset', 'Rest/SystemLog::resetLog');
$routes->get('api/log/list', 'Rest/SystemLog::getLogList');
$routes->get('api/log/latest', 'Rest/SystemLog::getLatestLog');
$routes->get('api/log', 'Rest/SystemLog::getLatestLog');
$routes->post('api/log', 'Rest/SystemLog::saveLog');

$routes->get('api/detection/list', 'Rest/Detection::getDetectionList');
$routes->get('api/detection/latest', 'Rest/Detection::getLatestDetection');
$routes->get('api/detection', 'Rest/Detection::getLatestDetection');
$routes->post('api/detection', 'Rest/Detection::saveDetection');
$routes->post('api/detection/delete', 'Rest/Detection::deleteDetection');
$routes->post('api/detection/reset', 'Rest/Detection::resetDetection');

/**
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
