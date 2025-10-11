<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'teacher_id' => 2, // Assuming teacher has ID 2
                'title' => 'Introduction to Programming',
                'description' => 'Learn the fundamentals of programming with hands-on exercises and real-world examples.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'teacher_id' => 2,
                'title' => 'Web Development Basics',
                'description' => 'Master HTML, CSS, and JavaScript to build modern websites.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'teacher_id' => 2,
                'title' => 'Database Design',
                'description' => 'Learn how to design and implement efficient databases using MySQL.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'teacher_id' => 2,
                'title' => 'PHP and CodeIgniter Framework',
                'description' => 'Build dynamic web applications using PHP and the CodeIgniter framework.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Only insert if courses table is empty
        if ($this->db->table('courses')->countAll() == 0) {
            $this->db->table('courses')->insertBatch($data);
        }
    }
}
