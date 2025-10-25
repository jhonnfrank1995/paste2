<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\User;
use App\Models\Paste;

class UserController extends Controller
{
    private User $userModel;
    private Paste $pasteModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('User');
        $this->pasteModel = $this->model('Paste');
    }

    public function dashboard()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) {
            return $this->response->redirect('/login');
        }

        // ===============================================================
        //  FIX: Forzar el ID de usuario a ser un entero (int)
        // ===============================================================
        // La sesión puede devolver el ID como string, pero el modelo espera un int.
        // Hacemos un "casting" explícito a (int).
        $userPastes = $this->pasteModel->findByUserId((int) $userId);
        // ===============================================================

        View::render('user.dashboard', [
            'pastes' => $userPastes,
            'page_title' => 'My Dashboard'
        ]);
    }

    public function editProfile()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) { return $this->response->redirect('/login'); }
        $user = $this->userModel->find((int)$userId);
        View::render('user.edit-profile', ['user' => $user, 'page_title' => 'Edit Profile']);
    }

    public function updateProfile()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) { return $this->response->redirect('/login'); }
        if (!hash_equals($this->session->getCsrfToken(), $this->request->input('_csrf'))) {
            $this->session->flash('error', 'Invalid security token.');
            return $this->response->redirect('/user/profile');
        }
        $rules = ['bio' => 'max:1000'];
        if (!$this->validator->validate($this->request->all(), $rules)) {
            $this->session->flash('errors', $this->validator->getErrors());
            $this->session->flash('old', $this->request->all());
            return $this->response->redirect('/user/profile');
        }
        $data = ['bio' => $this->request->input('bio', '')];
        if ($this->userModel->update((int)$userId, $data)) {
            $this->session->flash('success', 'Your profile has been updated successfully.');
        } else {
            $this->session->flash('error', 'There was an issue updating your profile.');
        }
        return $this->response->redirect('/user/profile');
    }
    
    public function showChangePasswordForm()
    {
        View::render('user.change-password', ['page_title' => 'Change Password']);
    }
    
    public function updatePassword()
    {
        $userId = $this->session->get('user_id');
        if (!$userId) { return $this->response->redirect('/login'); }
        if (!hash_equals($this->session->getCsrfToken(), $this->request->input('_csrf'))) {
            $this->session->flash('error', 'Invalid security token.');
            return $this->response->redirect('/user/password');
        }
        $rules = ['current_password' => 'required', 'new_password' => 'required|min:8|confirmed'];
        if (!$this->validator->validate($this->request->all(), $rules)) {
            $this->session->flash('errors', $this->validator->getErrors());
            return $this->response->redirect('/user/password');
        }
        $user = $this->userModel->findWithPassword((int)$userId);
        if (!password_verify($this->request->input('current_password'), $user['password_hash'])) {
            $this->session->flash('error', 'Your current password does not match.');
            return $this->response->redirect('/user/password');
        }
        if ($this->userModel->updatePassword((int)$userId, $this->request->input('new_password'))) {
            $this->session->flash('success', 'Your password has been changed successfully.');
            $this->session->regenerate();
        } else {
            $this->session->flash('error', 'Could not change your password.');
        }
        return $this->response->redirect('/user/password');
    }
}