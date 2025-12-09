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

        $allEnrollments = $enrollments->getUserEnrollments($userId);

        $active  = [];
        $expired = [];

        $now = new \DateTime('now');

        foreach ($allEnrollments as $row) {
            if (empty($row['enrollment_date'])) {
                $active[] = $row;
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
                $active[] = $row;
            } else {
                $expired[] = $row;
            }
        }

        $data = [
            'title'          => 'My Enrollments',
            'activeEnrolled' => $active,
            'expiredEnrolled'=> $expired,
            'available'      => $courses->listNotEnrolledBy($userId),
        ];

        return view('student/enrollments/index', $data);
    }
}