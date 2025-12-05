<?php

namespace App\Controllers;

use App\Models\MaterialModel;

class Student extends BaseController
{
    /**
     * Display student dashboard
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function dashboard()
    {
        // Check if user is logged in and is a student
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        if (session()->get('role') !== 'student') {
            return redirect()->back()->with('error', 'Access denied');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // Cutoff date for active enrollments (4 months)
        $cutoff = date('Y-m-d H:i:s', strtotime('-4 months'));

        // Get enrolled courses count (only last 1 year)
        $enrolled = $db->table('enrollments')
                      ->where('user_id', $userId)
                      ->where('status', 'approved')
                      ->where('enrollment_date >=', $cutoff)
                      ->countAllResults();

        // Get quizzes count from enrolled courses (quizzes are linked through lessons)
        $quizzes = $db->table('quizzes')
                     ->join('lessons', 'lessons.id = quizzes.lesson_id')
                     ->join('courses', 'courses.id = lessons.course_id')
                     ->join('enrollments', 'enrollments.course_id = courses.id')
                     ->where('enrollments.user_id', $userId)
                     ->where('enrollments.status', 'approved')
                     ->where('enrollments.enrollment_date >=', $cutoff)
                     ->countAllResults();

        // Get enrolled courses with details (only last 1 year)
        $enrolledCourses = $db->table('courses')
                             ->select('courses.*, enrollments.enrollment_date, users.name as teacher_name')
                             ->join('enrollments', 'enrollments.course_id = courses.id')
                             ->join('users', 'users.id = courses.teacher_id', 'left')
                             ->where('enrollments.user_id', $userId)
                             ->where('enrollments.status', 'approved')
                             ->where('enrollments.enrollment_date >=', $cutoff)
                             ->orderBy('enrollments.enrollment_date', 'DESC')
                             ->get()
                             ->getResultArray();

        $stats = [
            'enrolled' => $enrolled,
            'quizzes' => $quizzes,
        ];

        return view('student/dashboard', [
            'title' => 'Student Dashboard',
            'stats' => $stats,
            'enrolledCourses' => $enrolledCourses
        ]);
    }

    /**
     * Display all materials from enrolled courses
     *
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function materials()
    {
        // Check if user is logged in and is a student
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        if (session()->get('role') !== 'student') {
            return redirect()->back()->with('error', 'Access denied');
        }

        $userId = session()->get('user_id');
        $materialModel = new MaterialModel();
        
        // Get all materials from courses the student is enrolled in
        $materials = $materialModel->getMaterialsForStudent($userId);

        return view('student/materials', [
            'title' => 'My Course Materials',
            'materials' => $materials
        ]);
    }
}
