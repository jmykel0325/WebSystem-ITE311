<?php
namespace App\Controllers\Teacher;

use App\Controllers\BaseController;

class Announcements extends BaseController
{
    public function index()
    {
        // For now, teachers viewing their area can reuse the public announcements page
        return redirect()->to(site_url('announcements'));
    }
}
