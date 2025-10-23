<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourseNumberToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'course_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'id'
            ],
        ];
        
        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'course_number');
    }
}
