<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\User;
use App\Services\MailerService;

class AuthController extends Controller
{
private User $userModel;

public function __construct()
{
parent::__construct();
$this->userModel = $this->model('User');
}

public function showLoginForm() { View::render('auth.login'); }
public function showRegisterForm() { View::render('auth.register'); }

public function register()
{
$rules = [
'email' => 'required|email',
'password' => 'required|min:8|confirmed',
];

if (!$this->validator->validate($this->request->all(), $rules)) {
$this->session->flash('errors', $this->validator->getErrors());
$this->session->flash('old', $this->request->all());
return $this->response->redirect('/register');
}

if ($this->userModel->findByEmail($this->request->input('email'))) {
$this->session->flash('error', 'An account with this email already exists.');
return $this->response->redirect('/register');
}

$userId = $this->userModel->create(
$this->request->input('email'),
$this->request->input('password')
);

// TODO: Enviar email de verificación si es necesario

$this->session->set('user_id', $userId);
$this->session->set('user', ['id' => $userId, 'email' => $this->request->input('email'), 'role' => 'user']);
$this->session->regenerate();

return $this->response->redirect('/');
}

public function login()
{
$rules = [
'email' => 'required|email',
'password' => 'required',
];
if (!$this->validator->validate($this->request->all(), $rules)) {
$this->session->flash('error', 'Invalid credentials.');
return $this->response->redirect('/login');
}

$user = $this->userModel->findByEmail($this->request->input('email'));

if (!$user || !password_verify($this->request->input('password'), $user['password_hash'])) {
$this->session->flash('error', 'Invalid credentials.');
return $this->response->redirect('/login');
}

$this->session->set('user_id', $user['id']);
$this->session->set('user', ['id' => $user['id'], 'email' => $user['email'], 'role' => $user['role']]);
$this->session->regenerate();

return $this->response->redirect('/');
}

public function logout()
{
$this->session->destroy();
return $this->response->redirect('/');
}
}