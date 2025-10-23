<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/',        'Home::index');
$routes->get('about',    'Home::about');
$routes->get('contact',  'Home::contact');
$routes->get('announcements', 'Announcement::index', ['filter' => 'auth']);

$routes->get('register',  'Auth::register');
$routes->post('register', 'Auth::register');

$routes->get('login',  'Auth::login');
$routes->post('login', 'Auth::login');

$routes->get('dashboard', 'Auth::dashboard');
$routes->get('logout',    'Auth::logout');
$routes->get('debug',     'Auth::debug');

// Role dashboards (authorization enforced by RoleAuth on groups)
$routes->get('teacher/dashboard', 'Teacher::dashboard');
$routes->get('student/dashboard', 'Student::dashboard');
$routes->get('admin/dashboard',   'Admin\\Dashboard::index');

// AJAX enroll endpoint
$routes->post('course/enroll', 'Course::enroll');
$routes->post('course/unenroll', 'Course::unenroll');

// Admin (protected by RoleAuth)
$routes->group('admin', ['filter' => 'roleauth'], static function($routes) {
    // Default admin landing (support both '' and '/')
    $routes->get('', 'Admin\\Dashboard::index');
    $routes->get('/', 'Admin\\Dashboard::index');
    // Note: '/admin/dashboard' is defined above with filter and simple controller
    
    // Announcements management
    $routes->get('announcements', 'Admin\\Announcements::index');
    $routes->get('announcements/create', 'Admin\\Announcements::create');
    $routes->post('announcements/store', 'Admin\\Announcements::store');
    $routes->get('announcements/edit/(:num)', 'Admin\\Announcements::edit/$1');
    $routes->post('announcements/update/(:num)', 'Admin\\Announcements::update/$1');
    $routes->get('announcements/delete/(:num)', 'Admin\\Announcements::delete/$1');
    
    // Courses management
    $routes->get('courses', 'Admin\\Courses::index');
    $routes->get('courses/create', 'Admin\\Courses::create');
    $routes->post('courses/store', 'Admin\\Courses::store');
    $routes->get('courses/edit/(:num)', 'Admin\\Courses::edit/$1');
    $routes->post('courses/update/(:num)', 'Admin\\Courses::update/$1');
    $routes->get('courses/delete/(:num)', 'Admin\\Courses::delete/$1');
});

// Teacher (protected by RoleAuth)
$routes->group('teacher', ['filter' => 'roleauth'], static function($routes) {
    $routes->get('courses', 'Teacher\\Courses::index');
    $routes->get('quizzes', 'Teacher\\Quizzes::index');
    $routes->get('announcements', 'Teacher\\Announcements::index');
});

// Student pages
$routes->group('student', ['filter' => 'auth'], static function($routes) {
    $routes->get('enrollments', 'Student\Enrollments::index');
    $routes->get('grades', 'Student\\Grades::index');
    $routes->get('materials', 'Student::materials');
});

// Materials routes - Following instruction format
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');

// Additional route for listing materials by course
$routes->get('materials/list/(:num)', 'Materials::listByCourse/$1');

// Notification routes
$routes->get('/notifications', 'Notifications::get');
$routes->post('/notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');
$routes->delete('/notifications/delete/(:num)', 'Notifications::delete/$1');
