<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/',        'Home::index');
$routes->get('about',    'Home::about');
$routes->get('contact',  'Home::contact');

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
$routes->group('admin', ['filter' => 'auth'], static function($routes) {
    $routes->get('dashboard', 'Admin\\Dashboard::index');
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
