<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveCourseCodeFromCourses extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('courses', 'course_code');
    }

    public function down()
    {
        $this->forge->addColumn('courses', [
            'course_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);
    }
}
