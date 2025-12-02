<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        // Require admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userModel = new UserModel();
        $users = $userModel->orderBy('id', 'asc')->findAll();

        return view('admin/users/index', [
            'title' => 'Manage Users',
            'users' => $users,
        ]);
    }
}
