<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGradesTable extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('grades')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'student_id' => ['type' => 'INT', 'unsigned' => true],
                'course_id'  => ['type' => 'INT', 'unsigned' => true],
                'quiz_id'    => ['type' => 'INT', 'unsigned' => true],
                'score'      => ['type' => 'INT', 'null' => false, 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('student_id');
            $this->forge->addKey('course_id');
            $this->forge->addKey('quiz_id');
            $this->forge->createTable('grades', true);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('grades')) {
            $this->forge->dropTable('grades', true);
        }
    }
}
