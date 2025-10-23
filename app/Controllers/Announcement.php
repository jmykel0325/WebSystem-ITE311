<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AnnouncementModel;

class Announcement extends Controller
{
    public function index()
    {
        $model = new AnnouncementModel();
        $announcements = $model
            ->where('is_active', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();
        
        $data = [
            'title' => 'Announcements',
            'announcements' => $announcements
        ];
        
        return view('announcements', $data);
    }
}
