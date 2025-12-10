<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/',        'Home::index');
$routes->get('about',    'Home::about');
$routes->get('contact',  'Home::contact');
$routes->get('announcements', 'Announcement::index', ['filter' => 'auth']);

// Public courses listing and search
$routes->get('courses', 'Course::index');
$routes->match(['get', 'post'], 'courses/search', 'Course::search');
$routes->get('courses/show/(:num)', 'Course::show/$1');

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
    
    // Users management
    $routes->get('users', 'Admin\\Users::index');
    $routes->get('users/search', 'Admin\\Users::search');
    $routes->get('users/create', 'Admin\\Users::create');
    $routes->post('users/store', 'Admin\\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\\Users::delete/$1');
    $routes->get('users/restore/(:num)', 'Admin\\Users::restore/$1');
    
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
    $routes->get('courses/enrolled-students/(:num)', 'Admin\\Courses::enrolledStudents/$1');
    $routes->get('courses/unenroll-student/(:num)', 'Admin\\Courses::unenrollStudent/$1');
});

// Teacher (protected by RoleAuth)
$routes->group('teacher', ['namespace' => 'App\\Controllers\\Teacher', 'filter' => 'roleauth'], static function($routes) {
    $routes->get('courses', 'Courses::index');
    $routes->get('courses/show/(:num)', '\App\\Controllers\\Teacher::showCourse/$1');
    $routes->get('courses/students/(:num)', 'Courses::students/$1');
    // Quiz management
    $routes->get('quizzes',               'Quizzes::index');
    $routes->get('quizzes/create',        'Quizzes::create');
    $routes->post('quizzes/store',        'Quizzes::store');
    $routes->get('quizzes/edit/(:num)',   'Quizzes::edit/$1');
    $routes->post('quizzes/update/(:num)','Quizzes::update/$1');
    $routes->get('quizzes/delete/(:num)', 'Quizzes::delete/$1');
    $routes->get('quizzes/manage/(:num)', 'Quizzes::manage/$1');
    $routes->get('quizzes/scores/(:num)', 'Quizzes::scores/$1');
    $routes->get('announcements', 'Announcements::index');

    // Enrollment management (teacher approves/unenrolls students)
    $routes->get('enrollments', 'Enrollments::index');
    $routes->get('enrollments/approve/(:num)', 'Enrollments::approve/$1');
    $routes->get('enrollments/unenroll/(:num)', 'Enrollments::unenroll/$1');
    $routes->get('enrollments/reenroll/(:num)', 'Enrollments::reenroll/$1');
});

// Student pages
$routes->group('student', ['namespace' => 'App\\Controllers\\Student', 'filter' => 'auth'], static function($routes) {
    $routes->get('enrollments', 'Enrollments::index');
    $routes->get('grades', 'Grades::index');
    $routes->get('materials', '\App\\Controllers\\Student::materials');
    // Quizzes access
    $routes->get('quizzes',                'Quizzes::index');
    $routes->get('quizzes/show/(:num)',    'Quizzes::show/$1');
    $routes->post('quizzes/submit/(:num)', 'Quizzes::submit/$1');
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
