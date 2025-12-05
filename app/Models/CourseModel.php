<?php
namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['course_number', 'semester', 'duration_months', 'start_date', 'end_date', 'days_pattern', 'teacher_id', 'title', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    /**
     * Returns courses the given user is NOT enrolled in.
     */
    public function listNotEnrolledBy(int $userId): array
    {
        return $this->select('courses.*')
                    ->join(
                        'enrollments e',
                        'e.course_id = courses.id'
                        . ' AND e.user_id = ' . $this->db->escape($userId)
                        . ' AND e.status = ' . $this->db->escape('approved'),
                        'left'
                    )
                    ->where('e.id', null)
                    ->orderBy('courses.title', 'asc')
                    ->findAll();
    }
}
