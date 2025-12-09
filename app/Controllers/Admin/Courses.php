<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class Courses extends BaseController
{
    protected $courseModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        helper(['form', 'url']);
    }

    /**
     * Display list of all courses
     */
    public function index()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        // Optional semester filter (all/first/second)
        $selectedSemester = $this->request->getGet('semester');

        // Get all courses with teacher information
        $db = \Config\Database::connect();
        $builder = $db->table('courses')
                      ->select('courses.*, users.name as teacher_name, users.email as teacher_email')
                      ->join('users', 'users.id = courses.teacher_id', 'left');

        if (in_array($selectedSemester, ['first', 'second'], true)) {
            $builder->where('courses.semester', $selectedSemester);
        }

        $courses = $builder->orderBy('courses.created_at', 'DESC')
                           ->get()
                           ->getResultArray();

        // Get material and enrollment counts for each course
        foreach ($courses as &$course) {
            $materialCount = $db->table('materials')
                               ->where('course_id', $course['id'])
                               ->countAllResults();
            $course['material_count'] = $materialCount;

            // Count only approved enrollments (students officially enrolled)
            $enrollmentCount = $db->table('enrollments')
                                 ->select('COUNT(DISTINCT enrollments.id) as cnt', false)
                                 ->where('enrollments.course_id', $course['id'])
                                 ->where('enrollments.status', 'approved')
                                 ->get()
                                 ->getRow('cnt');
            $course['enrollment_count'] = (int) $enrollmentCount;
        }

        return view('admin/courses/index', [
            'title' => 'Manage Courses',
            'courses' => $courses,
            'selectedSemester' => $selectedSemester,
        ]);
    }

    /**
     * Show create course form
     */
    public function create()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        // Get all teachers
        $db = \Config\Database::connect();
        $teachers = $db->table('users')
                      ->where('role', 'teacher')
                      ->orderBy('name', 'ASC')
                      ->get()
                      ->getResultArray();

        return view('admin/courses/create', [
            'title' => 'Create New Course',
            'teachers' => $teachers
        ]);
    }

    /**
     * Store new course
     */
    public function store()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        // Validation rules
        $validationRules = [
            'course_number' => [
                'rules' => 'required|min_length[2]|max_length[50]',
                'errors' => [
                    'required' => 'Course number is required',
                    'min_length' => 'Course number must be at least 2 characters',
                    'max_length' => 'Course number cannot exceed 50 characters',
                ]
            ],
            'semester' => [
                'rules' => 'required|in_list[first,second]',
                'errors' => [
                    'required' => 'Please select a semester',
                    'in_list'  => 'Invalid semester selected',
                ]
            ],
            'school_year' => [
                'rules' => 'permit_empty|max_length[20]',
                'errors' => [
                    'max_length' => 'School year cannot exceed 20 characters',
                ]
            ],
            'start_date' => [
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Start date is not a valid date',
                ]
            ],
            'end_date' => [
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'End date is not a valid date',
                ]
            ],
            'days_pattern' => [
                'rules' => 'permit_empty|max_length[20]',
                'errors' => [
                    'max_length' => 'Schedule pattern cannot exceed 20 characters',
                ]
            ],
            'start_time' => [
                'rules' => 'permit_empty|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
                'errors' => [
                    'regex_match' => 'Start time must be in HH:MM or HH:MM:SS format.',
                ]
            ],
            'end_time' => [
                'rules' => 'permit_empty|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
                'errors' => [
                    'regex_match' => 'End time must be in HH:MM or HH:MM:SS format.',
                ]
            ],
            'title' => [
                'rules' => 'required|min_length[3]|max_length[255]|regex_match[/^[A-Za-z0-9 ]+$/]',
                'errors' => [
                    'required'    => 'Course title is required',
                    'min_length'  => 'Title must be at least 3 characters',
                    'max_length'  => 'Title cannot exceed 255 characters',
                    'regex_match' => 'Course title may only contain letters, numbers, and spaces (no special characters).',
                ]
            ],
            'description' => [
                'rules' => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'Description cannot exceed 1000 characters'
                ]
            ],
            'teacher_id' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please select a teacher',
                    'numeric' => 'Invalid teacher selection'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Normalize values
        $normalizedCourseNumber = strtoupper(trim($this->request->getPost('course_number')));
        $teacherId = (int) $this->request->getPost('teacher_id');

        // Ensure the same teacher cannot have the same course number twice
        $db = \Config\Database::connect();
        $existing = $db->table('courses')
                       ->where('course_number', $normalizedCourseNumber)
                       ->where('teacher_id', $teacherId)
                       ->get()
                       ->getRowArray();

        if ($existing) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', [
                               'course_number' => 'This teacher already has a course with this course number.',
                           ]);
        }

        // Prepare data
        $data = [
            'course_number' => $normalizedCourseNumber,
            'semester' => $this->request->getPost('semester'),
            'school_year' => trim((string) $this->request->getPost('school_year')) ?: null,
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'days_pattern' => strtoupper(trim($this->request->getPost('days_pattern') ?? '')) ?: null,
            'start_time' => $this->request->getPost('start_time') ?: null,
            'end_time' => $this->request->getPost('end_time') ?: null,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'teacher_id' => $teacherId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Insert course
        if ($this->courseModel->insert($data)) {
            return redirect()->to('admin/courses')->with('success', 'Course created successfully!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create course. Please try again.');
        }
    }

    /**
     * Show edit course form
     */
    public function edit($id)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $course = $this->courseModel->find($id);

        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found');
        }

        // Get all teachers
        $db = \Config\Database::connect();
        $teachers = $db->table('users')
                      ->where('role', 'teacher')
                      ->orderBy('name', 'ASC')
                      ->get()
                      ->getResultArray();

        return view('admin/courses/edit', [
            'title' => 'Edit Course',
            'course' => $course,
            'teachers' => $teachers
        ]);
    }

    /**
     * Update course
     */
    public function update($id)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $course = $this->courseModel->find($id);

        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found');
        }

        // Validation rules
        $validationRules = [
            'course_number' => [
                'rules' => 'required|min_length[2]|max_length[50]',
                'errors' => [
                    'required' => 'Course number is required',
                    'min_length' => 'Course number must be at least 2 characters',
                    'max_length' => 'Course number cannot exceed 50 characters',
                ]
            ],
            'title' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Course title is required',
                    'min_length' => 'Title must be at least 3 characters',
                    'max_length' => 'Title cannot exceed 255 characters'
                ]
            ],
            'description' => [
                'rules' => 'permit_empty|max_length[1000]',
                'errors' => [
                    'max_length' => 'Description cannot exceed 1000 characters'
                ]
            ],
            'teacher_id' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Please select a teacher',
                    'numeric' => 'Invalid teacher selection'
                ]
            ],
            'start_date' => [
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'Start date is not a valid date',
                ]
            ],
            'end_date' => [
                'rules' => 'permit_empty|valid_date',
                'errors' => [
                    'valid_date' => 'End date is not a valid date',
                ]
            ],
            'days_pattern' => [
                'rules' => 'permit_empty|max_length[20]',
                'errors' => [
                    'max_length' => 'Schedule pattern cannot exceed 20 characters',
                ]
            ],
            'start_time' => [
                'rules' => 'permit_empty|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
                'errors' => [
                    'regex_match' => 'Start time must be in HH:MM or HH:MM:SS format.',
                ]
            ],
            'end_time' => [
                'rules' => 'permit_empty|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
                'errors' => [
                    'regex_match' => 'End time must be in HH:MM or HH:MM:SS format.',
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Normalize values
        $normalizedCourseNumber = strtoupper(trim($this->request->getPost('course_number')));
        $teacherId = (int) $this->request->getPost('teacher_id');

        // Ensure the same teacher cannot have the same course number twice (excluding this course)
        $db = \Config\Database::connect();
        $existing = $db->table('courses')
                       ->where('course_number', $normalizedCourseNumber)
                       ->where('teacher_id', $teacherId)
                       ->where('id !=', (int) $id)
                       ->get()
                       ->getRowArray();

        if ($existing) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', [
                               'course_number' => 'This teacher already has a course with this course number.',
                           ]);
        }

        // Prepare data
        $data = [
            'course_number' => $normalizedCourseNumber,
            'semester' => $this->request->getPost('semester'),
            'start_date' => $this->request->getPost('start_date') ?: null,
            'end_date' => $this->request->getPost('end_date') ?: null,
            'days_pattern' => strtoupper(trim($this->request->getPost('days_pattern') ?? '')) ?: null,
            'start_time' => $this->request->getPost('start_time') ?: null,
            'end_time' => $this->request->getPost('end_time') ?: null,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'teacher_id' => $teacherId,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update course
        if ($this->courseModel->update($id, $data)) {
            return redirect()->to('admin/courses')->with('success', 'Course updated successfully!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update course. Please try again.');
        }
    }

    /**
     * Return JSON list of students enrolled in a course
     */
    public function enrolledStudents($id)
    {
        // Allow admin or teacher to view
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'teacher'], true)) {
            return $this->response->setStatusCode(403)->setJSON([
                'ok'    => false,
                'error' => 'Access denied',
            ]);
        }

        $db = \Config\Database::connect();

        // List only approved enrollments for this course (LEFT JOIN users)
        $students = $db->table('enrollments')
                       ->select('enrollments.id as enrollment_id, users.name, users.email, enrollments.enrollment_date, enrollments.status')
                       ->join('users', 'users.id = enrollments.user_id', 'left')
                       ->where('enrollments.course_id', (int)$id)
                       ->where('enrollments.status', 'approved')
                       ->orderBy('users.name', 'ASC')
                       ->get()
                       ->getResultArray();

        return $this->response->setJSON([
            'ok'       => true,
            'students' => $students,
        ]);
    }

    /**
     * Unenroll a single student (by enrollment ID).
     */
    public function unenrollStudent($enrollmentId)
    {
        if (!session()->get('isLoggedIn') || !in_array(session()->get('role'), ['admin', 'teacher'], true)) {
            return $this->response->setStatusCode(403)->setJSON([
                'ok'    => false,
                'error' => 'Access denied',
            ]);
        }

        $db = \Config\Database::connect();

        $deleted = $db->table('enrollments')
                      ->where('id', (int)$enrollmentId)
                      ->delete();

        return $this->response->setJSON([
            'ok' => (bool) $deleted,
        ]);
    }

    /**
     * Delete course
     */
    public function delete($id)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $course = $this->courseModel->find($id);

        if (!$course) {
            return redirect()->to('admin/courses')->with('error', 'Course not found');
        }

        // Delete associated materials files
        $db = \Config\Database::connect();
        $materials = $db->table('materials')
                       ->where('course_id', $id)
                       ->get()
                       ->getResultArray();

        foreach ($materials as $material) {
            $filePath = WRITEPATH . 'uploads/' . $material['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete materials records
        $db->table('materials')->where('course_id', $id)->delete();

        // Delete enrollments
        $db->table('enrollments')->where('course_id', $id)->delete();

        // Delete course
        if ($this->courseModel->delete($id)) {
            return redirect()->to('admin/courses')->with('success', 'Course and all related data deleted successfully!');
        } else {
            return redirect()->to('admin/courses')->with('error', 'Failed to delete course. Please try again.');
        }
    }
}
