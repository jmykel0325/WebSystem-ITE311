<?php
namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;

class Enrollments extends BaseController
{
    public function index()
    {
        // If your Lab 4 session keys are isLoggedIn/role, keep these guards.
        // Comment these two lines out only if you need to diagnose access issues.
        if (! session('isLoggedIn') || session('role') !== 'student') {
            return redirect()->to(site_url('login'))->with('error', 'Please log in as a student.');
        }

        $userId      = (int) session('user_id');
        $enrollments = new EnrollmentModel();
        $courses     = new CourseModel();

        $data = [
            'title'     => 'My Enrollments',
            'enrolled'  => $enrollments->getUserEnrollments($userId), // [{id,title,description,enrollment_date}]
            'available' => $courses->listNotEnrolledBy($userId),      // [{id,title,description}]
        ];

        return view('student/enrollments/index', $data);
    }
}