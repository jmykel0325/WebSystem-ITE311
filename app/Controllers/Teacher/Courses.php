<?php
namespace App\Controllers\Teacher;

use App\Controllers\BaseController;

class Courses extends BaseController
{
    public function index()
    {
        $teacherId = session('user_id');
        $db = \Config\Database::connect();
        $courses = $db->table('courses')
            ->select('id, title, created_at')
            ->where('teacher_id', $teacherId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return view('teacher/courses/index', [
            'title' => 'My Courses',
            'courses' => $courses,
        ]);
    }
}
