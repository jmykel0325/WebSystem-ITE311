<?php
namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['teacher_id','title','description','created_at','updated_at'];
    protected $useTimestamps = true;
}
