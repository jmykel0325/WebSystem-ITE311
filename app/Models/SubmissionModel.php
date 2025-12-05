<?php
namespace App\Models;

use CodeIgniter\Model;

class SubmissionModel extends Model
{
    protected $table      = 'submissions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['quiz_id', 'user_id', 'submitted_at', 'score'];

    protected $useTimestamps = false;
}
