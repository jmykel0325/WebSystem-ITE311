<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToEnrollments extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
                'default'    => 'approved',
            ],
        ];

        $this->forge->addColumn('enrollments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('enrollments', 'status');
    }
}
