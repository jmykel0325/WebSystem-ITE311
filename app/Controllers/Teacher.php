<?php
namespace App\Controllers;

class Teacher extends BaseController
{
    public function dashboard()
    {
        // Check if user is logged in and is a teacher
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        if (session()->get('role') !== 'teacher') {
            return redirect()->back()->with('error', 'Access denied');
        }

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // Get teacher's courses count
        $myCourses = $db->table('courses')
                       ->where('teacher_id', $userId)
                       ->countAllResults();

        // Get quizzes count for teacher's courses (quizzes are linked through lessons)
        $quizzes = $db->table('quizzes')
                     ->join('lessons', 'lessons.id = quizzes.lesson_id')
                     ->join('courses', 'courses.id = lessons.course_id')
                     ->where('courses.teacher_id', $userId)
                     ->countAllResults();

        // Get teacher's courses with details
        $courses = $db->table('courses')
                     ->select('courses.*, COUNT(DISTINCT enrollments.id) as student_count, COUNT(DISTINCT materials.id) as material_count')
                     ->join('enrollments', 'enrollments.course_id = courses.id AND enrollments.status = "approved"', 'left')
                     ->join('materials', 'materials.course_id = courses.id', 'left')
                     ->where('courses.teacher_id', $userId)
                     ->groupBy('courses.id')
                     ->get()
                     ->getResultArray();

        $stats = [
            'my_courses' => $myCourses,
            'quizzes' => $quizzes,
        ];

        return view('teacher/dashboard', [
            'title' => 'Teacher Dashboard',
            'stats' => $stats,
            'courses' => $courses
        ]);
    }

    public function showCourse($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'teacher') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $teacherId = session()->get('user_id');
        $db = \Config\Database::connect();

        $course = $db->table('courses')
                     ->where('id', (int)$id)
                     ->where('teacher_id', $teacherId)
                     ->get()
                     ->getRowArray();

        if (!$course) {
            return redirect()->to('teacher/dashboard')->with('error', 'Course not found');
        }

        $materials = $db->table('materials')
                        ->where('course_id', (int)$id)
                        ->orderBy('created_at', 'DESC')
                        ->get()
                        ->getResultArray();

        $materialCount = count($materials);

        $studentCount = $db->table('enrollments')
                           ->where('course_id', (int)$id)
                           ->where('status', 'approved')
                           ->countAllResults();

        return view('teacher/courses/show', [
            'title'        => 'Course Dashboard',
            'course'       => $course,
            'materials'    => $materials,
            'materialCount'=> $materialCount,
            'studentCount' => $studentCount,
        ]);
    }
}
