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

    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        return view('admin/users/create', [
            'title'      => 'Add User',
            'validation' => session('validation') ?? \Config\Services::validation(),
        ]);
    }

    public function store()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        // Validation rules per requirements
        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[50]|regex_match[/^[A-Za-z\s]+$/]',
                'errors' => [
                    'required'    => 'Name is required.',
                    'min_length'  => 'Name must be at least 3 characters.',
                    'max_length'  => 'Name must not exceed 50 characters.',
                    'regex_match' => 'Name may contain letters and spaces only.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required'   => 'Email is required.',
                    'valid_email'=> 'Please enter a valid email address.',
                    'is_unique'  => 'This email address is already in use.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]|max_length[32]',
                'errors' => [
                    'required'   => 'Password is required.',
                    'min_length' => 'Password must be at least 6 characters long.',
                    'max_length' => 'Password must not exceed 32 characters.',
                ],
            ],
            'role' => [
                'rules'  => 'required|in_list[admin,teacher,student]',
                'errors' => [
                    'required' => 'Role is required.',
                    'in_list'  => 'Invalid role selected.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('validation', $this->validator)
                ->with('error', 'Please correct the errors below and try again.');
        }

        $userModel = new UserModel();
        $userModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
        ]);

        return redirect()->to(site_url('admin/users'))
            ->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User not found');
        }

        return view('admin/users/edit', [
            'title' => 'Edit User',
            'user'  => $user,
        ]);
    }

    public function update($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User not found');
        }

        // Treat "self" as the currently logged-in user editing their own record
        $isSelfAdmin = ((int) session('user_id') === (int) $user['id']
            || (string) session('email') === (string) $user['email']);

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
        ];

        if (! $isSelfAdmin) {
            $rules['role'] = 'required|in_list[admin,teacher,student]';
        }

        if ($this->request->getPost('password')) {
            $rules['password'] = 'permit_empty|min_length[6]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id'    => $id,
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        if ($isSelfAdmin) {
            // Do not allow changing own role if current user is admin
            $data['role'] = $user['role'];
        } else {
            $data['role'] = $this->request->getPost('role');
        }

        $userModel->save($data);

        return redirect()->to(site_url('admin/users'))
            ->with('success', 'User updated successfully.');
    }

    public function delete($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        // Prevent an admin from deleting their own account
        if ((int) session('user_id') === (int) $id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User not found');
        }

        // Soft delete: mark role as deleted but keep the record
        $userModel->save([
            'id'   => $id,
            'role' => 'deleted',
        ]);

        return redirect()->to(site_url('admin/users'))
            ->with('success', 'User account marked as deleted.');
    }

    public function restore($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        $userModel = new UserModel();
        $user      = $userModel->find($id);

        if (! $user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('User not found');
        }

        // Only restore accounts currently marked as deleted
        if (($user['role'] ?? '') !== 'deleted') {
            return redirect()->back()->with('error', 'Only deleted accounts can be restored.');
        }

        // Restore to a safe default role; admin can change it later via Edit
        $userModel->save([
            'id'   => $id,
            'role' => 'student',
        ]);

        return redirect()->to(site_url('admin/users'))
            ->with('success', 'User account has been restored.');
    }
}
