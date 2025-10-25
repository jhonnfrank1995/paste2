<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Models\Paste;

class PasteManagementController extends Controller
{
    private Paste $pasteModel;

    public function __construct()
    {
        parent::__construct();
        $this->pasteModel = $this->model('Paste');
    }

    public function index()
    {
        $page = (int)($this->request->input('page', 1));
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $pastes = $this->pasteModel->getAll($perPage, $offset);
        $totalPastes = $this->pasteModel->countAll();
        $totalPages = ceil($totalPastes / $perPage);

        View::render('admin.pastes.index', [
            'page_title' => 'Manage Pastes',
            'pastes' => $pastes,
            'totalPastes' => $totalPastes,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function edit(string $id)
    {
        $paste = $this->pasteModel->findById($id);
        if (!$paste) {
            $this->session->flash('error', 'Paste not found.');
            return $this->response->redirect('/admin/pastes');
        }
        View::render('admin.pastes.edit', [
            'page_title' => 'Edit Paste - ' . htmlspecialchars($id),
            'paste' => $paste
        ]);
    }

    public function update(string $id)
    {
        $data = [
            'title' => $this->request->input('title'),
            'language' => $this->request->input('language'),
            'visibility' => $this->request->input('visibility'),
            'content' => $this->request->input('content')
        ];
        if ($this->pasteModel->adminUpdate($id, $data)) {
            $this->session->flash('success', 'Paste updated successfully.');
        } else {
            $this->session->flash('error', 'Failed to update paste.');
        }
        return $this->response->redirect('/admin/pastes/edit/' . $id);
    }

    public function destroy(string $id)
    {
        if ($this->pasteModel->delete($id)) {
            $this->session->flash('success', 'Paste has been deleted.');
        } else {
            $this->session->flash('error', 'Could not delete the paste.');
        }
        return $this->response->redirect('/admin/pastes');
    }
}