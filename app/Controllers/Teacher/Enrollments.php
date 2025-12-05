<?php
namespace App\Controllers\Teacher;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;

class Enrollments extends BaseController
{
    protected EnrollmentModel $enrollmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        if (session('role') !== 'teacher') {
            return redirect()->to('/teacher/dashboard')->with('error', 'Access denied.');
        }

        $teacherId = (int) session('user_id');

        $db = \Config\Database::connect();

        $pending = $db->table('enrollments e')
            ->select('e.id, e.user_id, e.course_id, e.enrollment_date, u.name AS student_name, c.title AS course_title')
            ->join('courses c', 'c.id = e.course_id')
            ->join('users u', 'u.id = e.user_id')
            ->where('c.teacher_id', $teacherId)
            ->where('e.status', 'pending')
            ->orderBy('e.enrollment_date', 'DESC')
            ->get()
            ->getResultArray();

        $approved = $db->table('enrollments e')
            ->select('e.id, e.user_id, e.course_id, e.enrollment_date, u.name AS student_name, c.title AS course_title')
            ->join('courses c', 'c.id = e.course_id')
            ->join('users u', 'u.id = e.user_id')
            ->where('c.teacher_id', $teacherId)
            ->where('e.status', 'approved')
            ->orderBy('e.enrollment_date', 'DESC')
            ->get()
            ->getResultArray();

        return view('teacher/enrollments/index', [
            'title'    => 'Manage Enrollments',
            'pending'  => $pending,
            'approved' => $approved,
        ]);
    }

    public function approve($id)
    {
        if (session('role') !== 'teacher') {
            return redirect()->to('/teacher/dashboard')->with('error', 'Access denied.');
        }

        $teacherId = (int) session('user_id');
        $enrollmentId = (int) $id;

        $db = \Config\Database::connect();

        $row = $db->table('enrollments e')
            ->select('e.id')
            ->join('courses c', 'c.id = e.course_id')
            ->where('e.id', $enrollmentId)
            ->where('c.teacher_id', $teacherId)
            ->get()
            ->getRowArray();

        if (! $row) {
            return redirect()->to('/teacher/enrollments')->with('error', 'Enrollment not found or not in your course.');
        }

        $this->enrollmentModel->update($enrollmentId, ['status' => 'approved']);

        return redirect()->to('/teacher/enrollments')->with('success', 'Enrollment approved.');
    }

    public function unenroll($id)
    {
        if (session('role') !== 'teacher') {
            return redirect()->to('/teacher/dashboard')->with('error', 'Access denied.');
        }

        $teacherId = (int) session('user_id');
        $enrollmentId = (int) $id;

        $db = \Config\Database::connect();

        $row = $db->table('enrollments e')
            ->select('e.id')
            ->join('courses c', 'c.id = e.course_id')
            ->where('e.id', $enrollmentId)
            ->where('c.teacher_id', $teacherId)
            ->get()
            ->getRowArray();

        if (! $row) {
            return redirect()->to('/teacher/enrollments')->with('error', 'Enrollment not found or not in your course.');
        }

        $this->enrollmentModel->delete($enrollmentId);

        return redirect()->to('/teacher/enrollments')->with('success', 'Student unenrolled from course.');
    }
}
