<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDaysPatternToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'days_pattern' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'end_date',
            ],
        ];

        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'days_pattern');
    }
}
