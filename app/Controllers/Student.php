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

        $userId = (int) session()->get('user_id');

        // Use EnrollmentModel to fetch all approved enrollments with course data
        $enrollmentModel = new \App\Models\EnrollmentModel();
        $allEnrollments  = $enrollmentModel->getUserEnrollments($userId);

        $activeEnrollments = [];
        $now = new \DateTime('now');

        foreach ($allEnrollments as $row) {
            if (empty($row['enrollment_date'])) {
                $activeEnrollments[] = $row;
                continue;
            }

            $enrolledAt = new \DateTime($row['enrollment_date']);

            // Prefer course end_date if set; otherwise fall back to enrollment + 4 months
            if (!empty($row['end_date'])) {
                $expiry = new \DateTime($row['end_date']);
            } else {
                $expiry = clone $enrolledAt;
                $expiry->modify('+4 months');
            }

            if ($expiry >= $now) {
                $activeEnrollments[] = $row;
            }
        }

        // Count only active enrollments
        $activeCount = count($activeEnrollments);

        // Quizzes count based on active courses only
        $db       = \Config\Database::connect();
        $courseIds = array_unique(array_map(static fn($e) => (int) $e['course_id'], $activeEnrollments));

        $quizzesCount = 0;
        if (!empty($courseIds)) {
            $quizzesCount = $db->table('quizzes q')
                ->join('lessons l', 'l.id = q.lesson_id')
                ->join('courses c', 'c.id = l.course_id')
                ->whereIn('c.id', $courseIds)
                ->countAllResults();
        }

        $stats = [
            'enrolled' => $activeCount,
            'quizzes'  => $quizzesCount,
        ];

        return view('student/dashboard', [
            'title'           => 'Student Dashboard',
            'stats'           => $stats,
            'enrolledCourses' => $activeEnrollments,
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
