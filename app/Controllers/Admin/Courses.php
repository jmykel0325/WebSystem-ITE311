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

        // Get all courses with teacher information
        $db = \Config\Database::connect();
        $courses = $db->table('courses')
                     ->select('courses.*, users.name as teacher_name, users.email as teacher_email')
                     ->join('users', 'users.id = courses.teacher_id', 'left')
                     ->orderBy('courses.created_at', 'DESC')
                     ->get()
                     ->getResultArray();

        // Get material counts for each course
        foreach ($courses as &$course) {
            $materialCount = $db->table('materials')
                               ->where('course_id', $course['id'])
                               ->countAllResults();
            $course['material_count'] = $materialCount;

            $enrollmentCount = $db->table('enrollments')
                                 ->where('course_id', $course['id'])
                                 ->countAllResults();
            $course['enrollment_count'] = $enrollmentCount;
        }

        return view('admin/courses/index', [
            'title' => 'Manage Courses',
            'courses' => $courses
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
            'course_code' => [
                'rules' => 'required|min_length[2]|max_length[50]|is_unique[courses.course_code]',
                'errors' => [
                    'required' => 'Course code is required',
                    'min_length' => 'Course code must be at least 2 characters',
                    'max_length' => 'Course code cannot exceed 50 characters',
                    'is_unique' => 'This course code is already in use. Please choose a different one.'
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
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'course_code' => strtoupper(trim($this->request->getPost('course_code'))),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'teacher_id' => $this->request->getPost('teacher_id'),
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
            'course_code' => [
                'rules' => 'required|min_length[2]|max_length[50]|is_unique[courses.course_code,id,' . $id . ']',
                'errors' => [
                    'required' => 'Course code is required',
                    'min_length' => 'Course code must be at least 2 characters',
                    'max_length' => 'Course code cannot exceed 50 characters',
                    'is_unique' => 'This course code is already in use. Please choose a different one.'
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
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'course_code' => strtoupper(trim($this->request->getPost('course_code'))),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'teacher_id' => $this->request->getPost('teacher_id'),
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
