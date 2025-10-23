<?php
namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends BaseController
{
    public function enroll()
    {
        if (! session('isLoggedIn')) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Unauthorized']);
        }

        if (session('role') !== 'student') {
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Forbidden']);
        }

        $courseId = (int) ($this->request->getPost('course_id'));
        if ($courseId <= 0) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Invalid course.']);
        }

        $courses = new CourseModel();
        $course  = $courses->select('id, title, description')->find($courseId);
        if (! $course) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Course not found.']);
        }

        $userId      = (int) (session('user_id') ?? session('id'));
        $enrollments = new EnrollmentModel();

        if ($enrollments->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'status'  => 'ok',
                'ok'      => true,
                'already' => true,
                'message' => 'Already enrolled.',
                'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? ''],
                'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
            ]);
        }

        $ok = $enrollments->enrollUser([
            'user_id'         => $userId,
            'course_id'       => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
        ]);

        if (! $ok) {
            return $this->response->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Could not enroll. Please try again.']);
        }

        return $this->response->setJSON([
            'status'  => 'ok',
            'ok'      => true,
            'message' => 'Enrollment successful.',
            'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? ''],
            'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
        ]);
    }

    public function unenroll()
    {
        if (! session('isLoggedIn')) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Please log in to unenroll.']);
        }

        if (session('role') !== 'student') {
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Only students can unenroll.']);
        }

        $courseId = (int) ($this->request->getPost('course_id'));
        if ($courseId <= 0) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNPROCESSABLE_ENTITY)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Invalid course.']);
        }

        $courses = new CourseModel();
        $course  = $courses->select('id, title, description')->find($courseId);
        if (! $course) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Course not found.']);
        }

        $userId      = (int) (session('user_id') ?? session('id'));
        $enrollments = new EnrollmentModel();

        // Check if user is actually enrolled
        if (! $enrollments->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'ok'      => false,
                'message' => 'You are not enrolled in this course.',
                'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
            ]);
        }

        $ok = $enrollments->unenrollUser($userId, $courseId);

        if (! $ok) {
            return $this->response->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Could not unenroll. Please try again.']);
        }

        return $this->response->setJSON([
            'status'  => 'ok',
            'ok'      => true,
            'message' => 'Successfully unenrolled from course.',
            'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? ''],
            'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
        ]);
    }
}


