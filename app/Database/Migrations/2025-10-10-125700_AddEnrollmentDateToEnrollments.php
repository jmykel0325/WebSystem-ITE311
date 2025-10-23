<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnrollmentDateToEnrollments extends Migration
{
    public function up()
    {
        // Add enrollment_date column if it doesn't exist
        $fields = [
            'enrollment_date' => [
                'type' => 'DATETIME',
                'null' => false,
                'after' => 'course_id',
            ],
        ];

        try {
            $this->forge->addColumn('enrollments', $fields);
        } catch (\Throwable $e) {
            // Column may already exist; ignore to keep migration idempotent
        }
    }

    public function down()
    {
        try {
            $this->forge->dropColumn('enrollments', 'enrollment_date');
        } catch (\Throwable $e) {
            // Ignore if column already dropped/non-existent
        }
    }
}


