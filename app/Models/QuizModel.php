<?php
namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table = 'quizzes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    // Existing schema differs; limit to readable fields
    protected $allowedFields = ['lesson_id','question','answer','created_at','updated_at'];
    protected $useTimestamps = true;
}
