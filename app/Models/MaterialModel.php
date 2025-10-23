<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table            = 'materials';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['course_id', 'file_name', 'file_path', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Insert a new material record
     *
     * @param array $data
     * @return int|bool Insert ID on success, false on failure
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     *
     * @param int $course_id
     * @return array
     */
    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get material by ID with course information
     *
     * @param int $material_id
     * @return array|null
     */
    public function getMaterialWithCourse($material_id)
    {
        return $this->select('materials.*, courses.title as course_title, courses.teacher_id')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->where('materials.id', $material_id)
                    ->first();
    }

    /**
     * Delete material by ID
     *
     * @param int $material_id
     * @return bool
     */
    public function deleteMaterial($material_id)
    {
        return $this->delete($material_id);
    }

    /**
     * Get materials for courses that a student is enrolled in
     *
     * @param int $user_id
     * @return array
     */
    public function getMaterialsForStudent($user_id)
    {
        return $this->select('materials.*, courses.title as course_title')
                    ->join('courses', 'courses.id = materials.course_id')
                    ->join('enrollments', 'enrollments.course_id = courses.id')
                    ->where('enrollments.user_id', $user_id)
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();
    }
}
