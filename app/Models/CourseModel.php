<?php
namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['course_number', 'teacher_id', 'title', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    /**
     * Returns courses the given user is NOT enrolled in.
     */
    public function listNotEnrolledBy(int $userId): array
    {
        // LEFT JOIN enrollments and filter where no row exists for this user
        return $this->select('courses.*')
                    ->join('enrollments e', 'e.course_id = courses.id AND e.user_id = '.$this->db->escape($userId), 'left')
                    ->where('e.id', null)
                    ->orderBy('courses.title', 'asc')
                    ->findAll();
    }
}
