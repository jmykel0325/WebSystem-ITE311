<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $stats = [
            'users'       => (int) $db->table('users')->countAllResults(),
            'courses'     => (int) $db->table('courses')->countAllResults(),
            'lessons'     => (int) $db->table('lessons')->countAllResults(),
            'quizzes'     => (int) $db->table('quizzes')->countAllResults(),
            'enrollments' => (int) $db->table('enrollments')->countAllResults(),
            'materials'   => (int) $db->table('materials')->countAllResults(),
        ];

        return view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
        ]);
    }
}
