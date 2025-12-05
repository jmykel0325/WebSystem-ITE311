<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSemesterToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'semester' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'course_number',
            ],
        ];

        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'semester');
    }
}
