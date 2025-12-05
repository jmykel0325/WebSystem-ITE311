<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDurationToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'duration_months' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'semester',
            ],
        ];

        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'duration_months');
    }
}
