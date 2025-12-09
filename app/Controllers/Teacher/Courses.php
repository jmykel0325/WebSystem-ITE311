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
            ->select('id, title, days_pattern, start_time, end_time, created_at')
            ->where('teacher_id', $teacherId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return view('teacher/courses/index', [
            'title' => 'My Courses',
            'courses' => $courses,
        ]);
    }

    /**
     * Return JSON list of approved students for a specific course owned by the current teacher.
     */
    public function students($courseId)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return $this->response->setStatusCode(403)->setJSON([
                'ok'    => false,
                'error' => 'Access denied',
            ]);
        }

        $teacherId = session('user_id');
        $db = \Config\Database::connect();

        // Ensure course belongs to this teacher
        $course = $db->table('courses')
                     ->where('id', (int)$courseId)
                     ->where('teacher_id', $teacherId)
                     ->get()
                     ->getRowArray();

        if (!$course) {
            return $this->response->setStatusCode(404)->setJSON([
                'ok'    => false,
                'error' => 'Course not found',
            ]);
        }

        $students = $db->table('enrollments')
                       ->select('enrollments.id as enrollment_id, users.name, users.email, enrollments.enrollment_date, enrollments.status')
                       ->join('users', 'users.id = enrollments.user_id', 'left')
                       ->where('enrollments.course_id', (int)$courseId)
                       ->where('enrollments.status', 'approved')
                       ->orderBy('users.name', 'ASC')
                       ->get()
                       ->getResultArray();

        return $this->response->setJSON([
            'ok'       => true,
            'students' => $students,
        ]);
    }
}
