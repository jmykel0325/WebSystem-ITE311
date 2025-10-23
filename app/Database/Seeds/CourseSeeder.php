<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        // Get teacher user ID
        $teacher = $this->db->table('users')->where('role', 'teacher')->get()->getRowArray();
        $teacherId = $teacher ? $teacher['id'] : 1;
        
        // Sample courses
        $courses = [
            [
                'course_code' => 'CS101',
                'title'       => 'Introduction to Web Development',
                'description' => 'Learn the basics of HTML, CSS, and JavaScript',
                'teacher_id'  => $teacherId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'course_code' => 'IT201',
                'title'       => 'Database Management Systems',
                'description' => 'Understanding relational databases and SQL',
                'teacher_id'  => $teacherId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'course_code' => 'CS202',
                'title'       => 'Object-Oriented Programming',
                'description' => 'Master OOP concepts with PHP',
                'teacher_id'  => $teacherId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        // Insert courses
        foreach ($courses as $course) {
            $this->db->table('courses')->insert($course);
        }
        
        echo "✅ Courses created successfully!\n";
        
        // Enroll student in first course
        $student = $this->db->table('users')->where('role', 'student')->get()->getRowArray();
        if ($student) {
            $enrollment = [
                'user_id'         => $student['id'],
                'course_id'       => 1,
                'enrollment_date' => $now,
            ];
            $this->db->table('enrollments')->insert($enrollment);
            echo "✅ Student enrolled in first course!\n";
        }
    }
}
