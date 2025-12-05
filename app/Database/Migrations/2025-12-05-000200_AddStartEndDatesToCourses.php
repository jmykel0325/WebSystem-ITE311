<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStartEndDatesToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'duration_months',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'start_date',
            ],
        ];

        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'start_date');
        $this->forge->dropColumn('courses', 'end_date');
    }
}
