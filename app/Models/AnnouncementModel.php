<?php
namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table         = 'announcements';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['title', 'content', 'is_active'];
    protected $useTimestamps = true; // uses created_at and updated_at
    protected $returnType    = 'array';
}
