<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Models\Paste;

class PasteController extends Controller
{
private Paste $pasteModel;

public function __construct()
{
parent::__construct();
$this->pasteModel = $this->model('Paste');
}

public function home()
{
$recentPastes = $this->pasteModel->getRecentPublic();
View::render('home', ['recentPastes' => $recentPastes]);
}

public function create() { View::render('paste.create'); }

public function store()
{
if (!hash_equals($this->session->getCsrfToken(), $this->request->input('_csrf'))) {
$this->session->flash('error', 'Invalid security token.');
return $this->response->redirect('/new');
}

$maxSize = $_ENV['MAX_PASTE_SIZE_KB'] ?? 2048;
$rules = [
'content' => "required|max:" . ($maxSize * 1024),
'title' => 'max:255',
];

if (!$this->validator->validate($this->request->all(), $rules)) {
$this->session->flash('errors', $this->validator->getErrors());
$this->session->flash('old', $this->request->all());
return $this->response->redirect('/new');
}

$userId = $this->session->get('user_id');

$data = [
'user_id' => $userId,
'title' => $this->request->input('title', 'Untitled'),
'content' => $this->request->input('content'),
'language' => $this->request->input('language', 'plaintext'),
'visibility' => $this->request->input('visibility', 'public'),
'expiration' => $this->request->input('expiration', 'never'),
'password' => $this->request->input('password'),
];

if ($data['visibility'] === 'private' && !$userId) {
$this->session->flash('error', 'You must be logged in to create a private paste.');
return $this->response->redirect('/new');
}

$result = $this->pasteModel->create($data);

if ($result['status'] === 'success') {
$url = '/p/' . $result['id'];
if(isset($result['edit_token'])) {
$this->session->flash('edit_token', $result['edit_token']);
}
return $this->response->redirect($url);
} else {
$this->session->flash('error', $result['message']);
return $this->response->redirect('/new');
}
}

public function show(string $id)
{
$paste = $this->pasteModel->findById($id);

if (!$paste) {
return View::render('errors.404', [], 404);
}

// Lógica de acceso
$userId = $this->session->get('user_id');
if ($paste['visibility'] === 'private' && $paste['user_id'] != $userId) {
return View::render('errors.403', [], 403);
}

if ($paste['has_password']) {
$accessKey = 'paste_access_' . $paste['id'];
if (!$this->session->has($accessKey)) {
return View::render('paste.password', ['paste' => $paste]);
}
}

// Lógica de "Burn after read"
if ($paste['burn_after_read']) {
$this->pasteModel->delete($paste['id']);
}

$this->pasteModel->incrementViewCount($id);

View::render('paste.show', ['paste' => $paste]);
}

public function unlock(string $id)
{
$paste = $this->pasteModel->findById($id, false); // No incluir contenido
$password = $this->request->input('password');

if ($paste && $password && password_verify($password, $paste['password_hash'])) {
$this->session->set('paste_access_' . $id, true);
return $this->response->redirect('/p/' . $id);
}

$this->session->flash('error', 'Invalid password.');
return $this->response->redirect('/p/' . $id);
}

public function raw(string $id)
{
$paste = $this->pasteModel->findById($id);
// Mismas comprobaciones de acceso que en show()
if (!$paste) {
return $this->response->plain('Paste not found.', 404);
}

// TODO: Añadir lógica de acceso (privado, contraseña)

return $this->response->plain($paste['content']);
}
}