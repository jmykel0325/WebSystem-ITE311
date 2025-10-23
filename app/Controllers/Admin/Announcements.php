<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnnouncementModel;

class Announcements extends BaseController
{
    public function index()
    {
        $model = new AnnouncementModel();
        $announcements = $model->orderBy('created_at', 'DESC')->findAll();
        
        $data = [
            'title' => 'Manage Announcements',
            'announcements' => $announcements
        ];
        
        return view('admin/announcements', $data);
    }
    
    public function create()
    {
        $data = [
            'title' => 'Create Announcement',
            'announcement' => null,
        ];
        
        return view('admin/announcement_form', $data);
    }
    
    public function store()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[3]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AnnouncementModel();
        $model->save([
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('admin/announcements')->with('success', 'Announcement created successfully!');
    }
    
    public function edit($id)
    {
        $model = new AnnouncementModel();
        $announcement = $model->find($id);

        if (! $announcement) {
            return redirect()->to('admin/announcements')->with('errors', ['not_found' => 'Announcement not found.']);
        }
        
        $data = [
            'title' => 'Edit Announcement',
            'announcement' => $announcement
        ];
        
        return view('admin/announcement_form', $data);
    }
    
    public function update($id)
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[3]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AnnouncementModel();
        $model->update($id, [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to('admin/announcements')->with('success', 'Announcement updated successfully!');
    }
    
    public function delete($id)
    {
        $model = new AnnouncementModel();
        $model->delete($id);

        return redirect()->to('admin/announcements')->with('success', 'Announcement deleted successfully!');
    }
}
