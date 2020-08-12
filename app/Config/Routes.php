<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
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
$routes->get('dashboard', 'Dashboard::index');
$routes->get('lecturer', 'Lecturer::index');
$routes->get('lecturer/list', 'Lecturer::getLecturerList');
$routes->post('lecturer/add', 'Lecturer::addLecturer');
$routes->post('lecturer/edit', 'Lecturer::editLecturer');
$routes->post('lecturer/delete', 'Lecturer::deleteLecturer');
$routes->post('lecturer/import', 'Lecturer::importLecturer');
$routes->post('lecturer/truncate', 'Lecturer::truncateLecturer');
$routes->get('class', 'StudentClass::index');
$routes->get('class/list', 'StudentClass::getClassList');
$routes->post('class/add', 'StudentClass::addClass');
$routes->post('class/edit', 'StudentClass::editClass');
$routes->post('class/delete', 'StudentClass::deleteClass');
$routes->post('class/import', 'StudentClass::importClass');
$routes->post('class/truncate', 'StudentClass::truncateClass');
$routes->get('schedule', 'Schedule::index');
$routes->get('schedule/list', 'Schedule::getScheduleList');
$routes->post('schedule/add', 'Schedule::addSchedule');
$routes->post('schedule/edit', 'Schedule::editSchedule');
$routes->post('schedule/delete', 'Schedule::deleteSchedule');
$routes->post('schedule/import', 'Schedule::importSchedule');
$routes->post('schedule/truncate', 'Schedule::truncateSchedule');
$routes->post('schedule/qr_code/regenerate', 'Schedule::regenerateScheduleQRCode');
$routes->get('attendance', 'Attendance::index');
$routes->get('attendance/list', 'Attendance::getAttendanceList');

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
