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

// AJAX enroll endpoint
$routes->post('course/enroll', 'Course::enroll');
$routes->post('course/unenroll', 'Course::unenroll');

// Admin
$routes->group('admin', ['filter' => 'role:admin'], static function($routes) {
    // Default admin landing (support both '' and '/')
    $routes->get('', 'Admin\\Dashboard::index');
    $routes->get('/', 'Admin\\Dashboard::index');
    $routes->get('dashboard', 'Admin\\Dashboard::index');
    
    // Announcements management
    $routes->get('announcements', 'Admin\\Announcements::index');
    $routes->get('announcements/create', 'Admin\\Announcements::create');
    $routes->post('announcements/store', 'Admin\\Announcements::store');
    $routes->get('announcements/edit/(:num)', 'Admin\\Announcements::edit/$1');
    $routes->post('announcements/update/(:num)', 'Admin\\Announcements::update/$1');
    $routes->get('announcements/delete/(:num)', 'Admin\\Announcements::delete/$1');
});

// Teacher
$routes->group('teacher', ['filter' => 'auth'], static function($routes) {
    $routes->get('courses', 'Teacher\\Courses::index');
    $routes->get('quizzes', 'Teacher\\Quizzes::index');
});

// Student pages
$routes->group('student', ['filter' => 'auth'], static function($routes) {
    $routes->get('enrollments', 'Student\Enrollments::index');
    $routes->get('grades', 'Student\\Grades::index');
});
