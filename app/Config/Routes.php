<?php /** @noinspection ALL */

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
$routes->get('signout', 'Authentication::signOut');
$routes->post('signin', 'Authentication::signIn');

$routes->get('profile', 'Profile::index');
$routes->post('profile', 'Profile::editProfile');

$routes->get('dashboard', 'Dashboard::index');

$routes->get('semester', 'Semester::index');
$routes->get('semester/list', 'Semester::getSemesterList');
$routes->post('semester/add', 'Semester::addSemester');
$routes->post('semester/edit', 'Semester::editSemester');
$routes->post('semester/delete', 'Semester::deleteSemester');
$routes->post('semester/import', 'Semester::importSemester');
$routes->post('semester/truncate', 'Semester::truncateSemester');

$routes->get('study-program', 'StudyProgram::index');
$routes->get('study-program/list', 'StudyProgram::getStudyProgramList');
$routes->post('study-program/add', 'StudyProgram::addStudyProgram');
$routes->post('study-program/edit', 'StudyProgram::editStudyProgram');
$routes->post('study-program/delete', 'StudyProgram::deleteStudyProgram');
$routes->post('study-program/import', 'StudyProgram::importStudyProgram');
$routes->post('study-program/truncate', 'StudyProgram::truncateStudyProgram');

$routes->get('study-program-admin', 'StudyProgramAdmin::index');
$routes->get('study-program-admin/list', 'StudyProgramAdmin::getAdminList');
$routes->post('study-program-admin/add', 'StudyProgramAdmin::addAdmin');
$routes->post('study-program-admin/edit', 'StudyProgramAdmin::editAdmin');
$routes->post('study-program-admin/resetpassword', 'StudyProgramAdmin::resetAdminPassword');
$routes->post('study-program-admin/delete', 'StudyProgramAdmin::deleteAdmin');
$routes->post('study-program-admin/truncate', 'StudyProgramAdmin::truncateAdmin');

$routes->get('class', 'StudentClass::index');
$routes->get('class/list', 'StudentClass::getClassList');
$routes->post('class/add', 'StudentClass::addClass');
$routes->post('class/edit', 'StudentClass::editClass');
$routes->post('class/delete', 'StudentClass::deleteClass');
$routes->post('class/import', 'StudentClass::importClass');
$routes->post('class/truncate', 'StudentClass::truncateClass');

$routes->get('lecturer', 'Lecturer::index');
$routes->get('lecturer/list', 'Lecturer::getLecturerList');
$routes->post('lecturer/add', 'Lecturer::addLecturer');
$routes->post('lecturer/edit', 'Lecturer::editLecturer');
$routes->post('lecturer/delete', 'Lecturer::deleteLecturer');
$routes->post('lecturer/import', 'Lecturer::importLecturer');
$routes->post('lecturer/truncate', 'Lecturer::truncateLecturer');

$routes->get('student', 'Student::index');
$routes->get('student/list', 'Student::getStudentList');
$routes->post('student/filtered-list', 'Student::::getFilteredStudentList');
$routes->post('student/add', 'Student::addStudent');
$routes->post('student/add-batch', 'Student::addBatchStudent');
$routes->post('student/edit', 'Student::editStudent');
$routes->post('student/delete', 'Student::deleteStudent');
$routes->post('student/import', 'Student::importStudent');
// $routes->post('student/filtered-import', 'Student::filteredImportStudent');
$routes->post('student/truncate', 'Student::truncateStudent');

$routes->get('schedule', 'Schedule::index');
$routes->get('schedule/list', 'Schedule::getScheduleList');
$routes->post('schedule/filtered-list', 'Schedule::getFilteredScheduleList');
$routes->post('schedule/add', 'Schedule::addSchedule');
$routes->post('schedule/add-batch', 'Schedule::addBatchSchedule');
$routes->post('schedule/edit', 'Schedule::editSchedule');
$routes->post('schedule/code/renew', 'Schedule::renewScheduleCode');
$routes->post('schedule/delete', 'Schedule::deleteSchedule');
$routes->post('schedule/import', 'Schedule::importSchedule');
// $routes->post('schedule/filtered-import', 'Schedule::filteredImportSchedule');
$routes->post('schedule/truncate', 'Schedule::truncateSchedule');

$routes->get('student-attendance', 'StudentAttendance::index');
$routes->get('student-attendance/list', 'StudentAttendance::getAttendanceList');

$routes->get('lecturer-attendance', 'LecturerAttendance::index');
$routes->get('lecturer-attendance/list', 'LecturerAttendance::getAttendanceList');

/**
 * --------------------------------------------------------------------
 * REST API Route Definitions
 * --------------------------------------------------------------------
 */
$routes->get('rest/authorize', 'Rest/Authorization::authorize');
$routes->post('rest/token', 'Rest/Authorization::token');
$routes->get('rest/users', 'Rest/Users::getUsers');

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
