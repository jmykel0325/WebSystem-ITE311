<?php
namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    // DB uses user_id not student_id in existing migration
    protected $allowedFields = ['user_id','course_id','enrolled_at','created_at','updated_at'];
    protected $useTimestamps = false; // existing table does not have created_at/updated_at reliably
}
