<?php
namespace App\Controllers\Student;

use App\Controllers\BaseController;

class Grades extends BaseController
{
    public function index()
    {
        $studentId = session('user_id');
        $db = \Config\Database::connect();
        $grades = $db->table('grades g')
            ->select('g.score, g.quiz_id, c.title AS course_title')
            ->join('courses c', 'c.id = g.course_id')
            ->where('g.student_id', $studentId)
            ->orderBy('g.created_at', 'DESC')
            ->get()->getResultArray();

        return view('student/grades/index', [
            'title' => 'My Grades',
            'grades' => $grades,
        ]);
    }
}
