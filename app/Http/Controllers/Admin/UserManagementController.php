<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Models\User;

class UserManagementController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $page = (int)($this->request->input('page', 1));
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $users = $this->userModel->getAll($perPage, $offset);
        $totalUsers = $this->userModel->countAll();
        $totalPages = ceil($totalUsers / $perPage);

        View::render('admin.users.index', [
            'page_title' => 'Manage Users',
            'users' => $users,
            'totalUsers' => $totalUsers,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function edit(int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->session->flash('error', 'User not found.');
            return $this->response->redirect('/admin/users');
        }
        View::render('admin.users.edit', [
            'page_title' => 'Edit User - ' . htmlspecialchars($user['email']),
            'user' => $user
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'email' => 'required|email',
            'role' => 'in:user,moderator,admin',
        ];
        if (!empty($this->request->input('password'))) {
            $rules['password'] = 'min:8';
        }
        if (!$this->validator->validate($this->request->all(), $rules)) {
            $this->session->flash('errors', $this->validator->getErrors());
            return $this->response->redirect('/admin/users/edit/' . $id);
        }
        $data = [
            'email' => $this->request->input('email'),
            'role' => $this->request->input('role'),
            'bio' => $this->request->input('bio', ''),
        ];
        if (!empty($this->request->input('password'))) {
            $data['password'] = $this->request->input('password');
        }
        if ($this->userModel->adminUpdate($id, $data)) {
            $this->session->flash('success', 'User updated successfully.');
        } else {
            $this->session->flash('error', 'Failed to update user.');
        }
        return $this->response->redirect('/admin/users/edit/' . $id);
    }
}