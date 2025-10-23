<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Materials extends BaseController
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        
        // Check if CourseModel exists, if not use database directly
        if (class_exists('\App\Models\CourseModel')) {
            $this->courseModel = new \App\Models\CourseModel();
        } else {
            // Fallback: create a simple model
            $this->courseModel = model('CourseModel', false);
            if (!$this->courseModel) {
                // Last resort: use database directly
                $this->courseModel = null;
            }
        }
        
        helper(['form', 'url', 'filesystem']);
        log_message('info', 'Materials controller initialized');
    }

    /**
     * Display upload form and handle file upload
     *
     * @param int $course_id
     * @return mixed
     */
    public function upload($course_id)
    {
        // Check if user is logged in and is admin or teacher
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $role = session()->get('role');
        $userId = session()->get('user_id');

        // Get course details
        if ($this->courseModel) {
            $course = $this->courseModel->find($course_id);
        } else {
            // Fallback: get course directly from database
            $db = \Config\Database::connect();
            $course = $db->table('courses')->where('id', $course_id)->get()->getRowArray();
        }
        
        if (!$course) {
            log_message('error', 'Course not found: ' . $course_id);
            return redirect()->back()->with('error', 'Course not found');
        }
        
        log_message('info', 'Course found: ' . $course['title']);

        // Check permissions: admin can upload to any course, teacher only to their courses
        if ($role !== 'admin' && ($role !== 'teacher' || $course['teacher_id'] != $userId)) {
            return redirect()->back()->with('error', 'You do not have permission to upload materials to this course');
        }

        // Handle POST request (file upload)
        $method = strtolower($this->request->getMethod());
        log_message('info', 'Upload method called for course ' . $course_id . ' with method: ' . $method);
        
        if ($method === 'post') {
            log_message('info', 'Processing POST request for upload');
            return $this->processUpload($course_id);
        }

        // Display upload form
        log_message('info', 'Displaying upload form for course ' . $course_id);
        return view('materials/upload', [
            'title' => 'Upload Material',
            'course' => $course
        ]);
    }

    /**
     * Process the file upload
     *
     * @param int $course_id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    private function processUpload($course_id)
    {
        // Validation rules
        $validationRules = [
            'material_file' => [
                'label' => 'Material File',
                'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip]',
            ],
        ];

        if (!$this->validate($validationRules)) {
            log_message('error', 'Material upload validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('material_file');
        log_message('info', 'Processing file upload for course ' . $course_id);

        if ($file->isValid() && !$file->hasMoved()) {
            // Create uploads directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/materials';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = $file->getRandomName();
            
            // Move file to upload directory
            if ($file->move($uploadPath, $newName)) {
                // Prepare data for database
                $data = [
                    'course_id'  => $course_id,
                    'file_name'  => $file->getClientName(),
                    'file_path'  => 'materials/' . $newName,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                // Insert into database
                log_message('info', 'Attempting to insert material: ' . json_encode($data));
                $insertResult = $this->materialModel->insertMaterial($data);
                log_message('info', 'Insert result: ' . ($insertResult ? 'SUCCESS' : 'FAILED'));
                
                if ($insertResult) {
                    // Get course information
                    $course = null;
                    if ($this->courseModel) {
                        $course = $this->courseModel->find($course_id);
                    }
                    $courseName = $course ? $course['title'] : 'your enrolled course';
                    
                    // Notify all enrolled students
                    $enrollmentModel = new EnrollmentModel();
                    $enrolledStudents = $enrollmentModel->where('course_id', $course_id)->findAll();
                    
                    if (!empty($enrolledStudents)) {
                        $notificationModel = new NotificationModel();
                        $fileName = $file->getClientName();
                        $materialsLink = site_url('student/materials');
                        
                        foreach ($enrolledStudents as $enrollment) {
                            $notificationModel->createNotification(
                                $enrollment['user_id'],
                                "New material uploaded in {$courseName}: {$fileName}",
                                $materialsLink
                            );
                        }
                        
                        log_message('info', 'Notifications sent to ' . count($enrolledStudents) . ' students');
                    }
                    
                    return redirect()->back()->with('success', 'Material uploaded successfully!');
                } else {
                    // Delete uploaded file if database insert fails
                    $errors = $this->materialModel->errors();
                    log_message('error', 'Material insert failed: ' . json_encode($errors));
                    unlink($uploadPath . '/' . $newName);
                    return redirect()->back()->with('error', 'Failed to save material information: ' . json_encode($errors));
                }
            } else {
                return redirect()->back()->with('error', 'Failed to upload file');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid file or file already moved');
        }
    }

    /**
     * Delete a material
     *
     * @param int $material_id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($material_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $role = session()->get('role');
        $userId = session()->get('user_id');

        // Get material with course info
        $material = $this->materialModel->getMaterialWithCourse($material_id);

        if (!$material) {
            return redirect()->back()->with('error', 'Material not found');
        }

        // Check permissions
        if ($role !== 'admin' && ($role !== 'teacher' || $material['teacher_id'] != $userId)) {
            return redirect()->back()->with('error', 'You do not have permission to delete this material');
        }

        // Delete file from filesystem
        $filePath = WRITEPATH . 'uploads/' . $material['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete from database
        if ($this->materialModel->deleteMaterial($material_id)) {
            return redirect()->back()->with('success', 'Material deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to delete material');
        }
    }

    /**
     * Download a material file
     *
     * @param int $material_id
     * @return ResponseInterface
     */
    public function download($material_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');

        // Get material with course info
        $material = $this->materialModel->getMaterialWithCourse($material_id);

        if (!$material) {
            return redirect()->back()->with('error', 'Material not found');
        }

        // Check if user has access to this material
        $hasAccess = false;

        if ($role === 'admin') {
            $hasAccess = true;
        } elseif ($role === 'teacher' && $material['teacher_id'] == $userId) {
            $hasAccess = true;
        } elseif ($role === 'student') {
            // Check if student is enrolled in the course
            $db = \Config\Database::connect();
            $enrollment = $db->table('enrollments')
                            ->where('user_id', $userId)
                            ->where('course_id', $material['course_id'])
                            ->get()
                            ->getRowArray();
            
            if ($enrollment) {
                $hasAccess = true;
            }
        }

        if (!$hasAccess) {
            return redirect()->back()->with('error', 'You do not have permission to download this material. Please enroll in the course first.');
        }

        // Download the file
        $filePath = WRITEPATH . 'uploads/' . $material['file_path'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server');
        }

        return $this->response->download($filePath, null)->setFileName($material['file_name']);
    }

    /**
     * List materials for a specific course
     *
     * @param int $course_id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function listByCourse($course_id)
    {
        // Check if user is logged in
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        $course = $this->courseModel->find($course_id);
        
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        return view('materials/list', [
            'title' => 'Course Materials',
            'course' => $course,
            'materials' => $materials
        ]);
    }
}
