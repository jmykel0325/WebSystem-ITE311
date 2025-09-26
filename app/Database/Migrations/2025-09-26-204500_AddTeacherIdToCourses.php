<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeacherIdToCourses extends Migration
{
    public function up()
    {
        // Add teacher_id to courses if it does not exist
        $fields = $this->db->getFieldNames('courses');
        if (! in_array('teacher_id', $fields, true)) {
            $fields = [
                'teacher_id' => [
                    'type'       => 'INT',
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                ],
            ];
            $this->forge->addColumn('courses', $fields);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('courses');
        if (in_array('teacher_id', $fields, true)) {
            $this->forge->dropColumn('courses', 'teacher_id');
        }
    }
}
