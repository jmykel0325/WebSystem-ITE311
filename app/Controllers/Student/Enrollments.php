<?php
namespace App\Controllers\Student;

use App\Controllers\BaseController;

class Enrollments extends BaseController
{
    public function index()
    {
        $studentId = session('user_id');
        $db = \Config\Database::connect();

        $my = $db->table('enrollments e')
            ->select('e.id, c.title AS course_title, e.enrolled_at')
            ->join('courses c', 'c.id = e.course_id')
            ->where('e.user_id', $studentId)
            ->orderBy('e.enrolled_at', 'DESC')
            ->get()->getResultArray();

        $available = $db->table('courses c')
            ->select('c.id, c.title, c.description')
            ->whereNotIn('c.id', function($builder) use ($studentId) {
                $builder->select('course_id')->from('enrollments')->where('user_id', $studentId);
            })
            ->get()->getResultArray();

        return view('student/enrollments/index', [
            'title'      => 'My Enrollments',
            'enrollments'=> $my,
            'available'  => $available,
        ]);
    }

    public function enroll()
    {
        if (! $this->request->is('post')) {
            return redirect()->back();
        }
        if (! service('security')->validateCSRF(false)) {
            return redirect()->back()->with('error', 'Invalid CSRF');
        }
        $studentId = session('user_id');
        $courseId = (int) $this->request->getPost('course_id');
        $db = \Config\Database::connect();
        // prevent duplicate
        $exists = $db->table('enrollments')->where(['user_id' => $studentId, 'course_id' => $courseId])->countAllResults();
        if ($exists) {
            return redirect()->back()->with('error', 'Already enrolled');
        }
        $db->table('enrollments')->insert([
            'user_id'    => $studentId,
            'course_id'  => $courseId,
            'enrolled_at'=> date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('success', 'Enrolled successfully');
    }
}
