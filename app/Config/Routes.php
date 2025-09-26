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

// Admin
$routes->group('admin', ['filter' => 'role:admin'], static function($routes) {
    $routes->get('dashboard', 'Admin\\Dashboard::index');
});

// Teacher
$routes->group('teacher', ['filter' => 'role:teacher'], static function($routes) {
    $routes->get('courses', 'Teacher\\Courses::index');
    $routes->get('quizzes', 'Teacher\\Quizzes::index');
});

// Student
$routes->group('student', ['filter' => 'role:student'], static function($routes) {
    $routes->get('enrollments', 'Student\\Enrollments::index');
    $routes->post('enrollments/enroll', 'Student\\Enrollments::enroll');
    $routes->get('grades', 'Student\\Grades::index');
});
