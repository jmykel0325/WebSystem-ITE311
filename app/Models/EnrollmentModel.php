<?php
namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table         = 'enrollments';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'status'];

    protected $useTimestamps = false;

    public function enrollUser(array $data): bool
    {
        return (bool) $this->insert($data, true);
    }

    public function isAlreadyEnrolled(int $userId, int $courseId): bool
    {
        return (bool) $this->where('user_id', $userId)
                           ->where('course_id', $courseId)
                           ->where('status', 'approved')
                           ->countAllResults();
    }

    public function unenrollUser(int $userId, int $courseId): bool
    {
        return (bool) $this->where(['user_id' => $userId, 'course_id' => $courseId])->delete();
    }

    public function getUserEnrollments(int $userId): array
    {
        return $this->select('enrollments.*, courses.id as course_id, courses.title, courses.description, courses.course_number, enrollments.enrollment_date, users.name as teacher_name, users.email as teacher_email')
                    ->join('courses', 'courses.id = enrollments.course_id', 'inner')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
                    ->where('enrollments.user_id', $userId)
                    ->where('enrollments.status', 'approved')
                    ->orderBy('enrollment_date', 'desc')
                    ->findAll();
    }
}