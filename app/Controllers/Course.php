<?php
namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends BaseController
{
    public function index()
    {
        $coursesModel = new CourseModel();

        // Only show non-expired courses: no end_date or end_date strictly in the future
        $today = date('Y-m-d');

        $data = [
            'courses'    => $coursesModel
                ->groupStart()
                    ->where('end_date IS NULL', null, false)
                    ->orWhere('end_date', '')
                    ->orWhere('end_date >', $today)
                ->groupEnd()
                ->orderBy('title', 'ASC')
                ->findAll(),
            'searchTerm' => null,
        ];

        return view('courses/index', $data);
    }

    public function show(int $id)
    {
        $coursesModel = new CourseModel();

        $course = $coursesModel->find($id);

        if (! $course) {
            return redirect()
                ->to('/courses')
                ->with('error', 'The course you tried to view no longer exists.');
        }

        return view('courses/show', [
            'course' => $course,
        ]);
    }

    public function search()
    {
        $coursesModel = new CourseModel();

        // Only search among non-expired courses
        $today   = date('Y-m-d');
        $builder = $coursesModel
            ->groupStart()
                ->where('end_date IS NULL', null, false)
                ->orWhere('end_date', '')
                ->orWhere('end_date >', $today)
            ->groupEnd();

        // Accept both GET and POST parameters, common names: q or search
        $term = trim((string) ($this->request->getVar('q') ?? $this->request->getVar('search') ?? ''));

        if ($term !== '') {
            // Only search within the course title and require it to START WITH the term
            // Using 'after' side means pattern 'term%'
            $builder = $builder->like('title', $term, 'after');
        }

        $results = $builder->orderBy('title', 'ASC')->findAll();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'ok'      => true,
                'term'    => $term,
                'count'   => count($results),
                'courses' => $results,
            ]);
        }

        return view('courses/index', [
            'courses'    => $results,
            'searchTerm' => $term,
        ]);
    }

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
        $course  = $courses->select('id, title, description, course_number, end_date')->find($courseId);
        if (! $course) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Course not found.']);
        }

        // Disallow enrollment into expired courses
        $today = date('Y-m-d');
        if (!empty($course['end_date']) && $course['end_date'] < $today) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON([
                    'status'  => 'error',
                    'ok'      => false,
                    'message' => 'This course has already expired and is no longer available for enrollment.',
                    'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
                ]);
        }

        $userId      = (int) (session('user_id') ?? session('id'));
        $enrollments = new EnrollmentModel();

        // Check for any existing enrollment row (approved or pending)
        $existing = $enrollments->where('user_id', $userId)
                                 ->where('course_id', $courseId)
                                 ->orderBy('enrollment_date', 'DESC')
                                 ->first();

        if ($existing && ($existing['status'] ?? 'approved') === 'approved') {
            return $this->response->setJSON([
                'status'  => 'ok',
                'ok'      => true,
                'already' => true,
                'message' => 'Already enrolled.',
                'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? '', 'course_number' => $course['course_number'] ?? ''],
                'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
            ]);
        }

        if ($existing && ($existing['status'] ?? '') === 'pending') {
            return $this->response->setJSON([
                'status'  => 'ok',
                'ok'      => true,
                'already' => true,
                'pending' => true,
                'message' => 'Enrollment request already sent. Waiting for teacher approval.',
                'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? '', 'course_number' => $course['course_number'] ?? ''],
                'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
            ]);
        }

        $ok = $enrollments->enrollUser([
            'user_id'         => $userId,
            'course_id'       => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status'          => 'pending',
        ]);

        if (! $ok) {
            return $this->response->setJSON(['status' => 'error', 'ok' => false, 'message' => 'Could not enroll. Please try again.']);
        }

        // Optionally, you could notify the teacher here instead of the student.

        return $this->response->setJSON([
            'status'  => 'ok',
            'ok'      => true,
            'message' => 'Enrollment request sent. Waiting for teacher approval.',
            'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? '', 'course_number' => $course['course_number'] ?? ''],
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
        $course  = $courses->select('id, title, description, course_number')->find($courseId);
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
            'course'  => ['id' => $course['id'], 'title' => $course['title'], 'summary' => $course['description'] ?? '', 'course_number' => $course['course_number'] ?? ''],
            'csrf'    => ['name' => csrf_token(), 'hash' => csrf_hash()],
        ]);
    }
}


