<?php
namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $table = 'grades';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['student_id','course_id','quiz_id','score','created_at','updated_at'];
    protected $useTimestamps = true;
}
