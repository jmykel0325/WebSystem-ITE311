<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            [
                'title'      => 'Welcome to the Portal',
                'content'    => "We are excited to launch the new LMS portal. Explore courses, quizzes, and announcements here.",
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'Midterm Schedule Posted',
                'content'    => "Midterm examinations will be held next week. Please check your course pages for specific schedules.",
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert batch into announcements table
        $this->db->table('announcements')->insertBatch($data);
    }
}
