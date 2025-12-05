<?php
namespace App\Models;

use CodeIgniter\Model;

class QuizModel extends Model
{
    protected $table = 'quizzes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    // Allow lesson link plus title, question, answer
    protected $allowedFields = ['lesson_id','title','question','answer','created_at','updated_at'];
    protected $useTimestamps = true;
}
