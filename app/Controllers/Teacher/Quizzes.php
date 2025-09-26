<?php
namespace App\Controllers\Teacher;

use App\Controllers\BaseController;

class Quizzes extends BaseController
{
    public function index()
    {
        $teacherId = session('user_id');
        $db = \Config\Database::connect();
        // Existing schema: quizzes -> lessons (lesson_id) -> courses (course_id, teacher_id)
        $quizzes = $db->table('quizzes q')
            ->select('q.id, q.question, l.title AS lesson_title, c.title AS course_title')
            ->join('lessons l', 'l.id = q.lesson_id')
            ->join('courses c', 'c.id = l.course_id')
            ->where('c.teacher_id', $teacherId)
            ->orderBy('q.created_at', 'DESC')
            ->get()->getResultArray();

        return view('teacher/quizzes/index', [
            'title' => 'My Quizzes',
            'quizzes' => $quizzes,
        ]);
    }
}
