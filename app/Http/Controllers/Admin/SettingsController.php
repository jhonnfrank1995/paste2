<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Models\Setting;

class SettingsController extends Controller
{
    private Setting $settingModel;

    public function __construct()
    {
        parent::__construct();
        $this->settingModel = $this->model('Setting');
    }

    public function index()
    {
        $settings = $this->settingModel->getAllAsAssoc();

        // ===============================================================
        //  FIX: Llamar a la vista 'admin.settings'
        // ===============================================================
        View::render('admin.settings', [
            'page_title' => 'Site Settings',
            'settings' => $settings
        ]);
    }

    public function save()
    {
        if (!hash_equals($this->session->getCsrfToken(), $this->request->input('_csrf'))) {
            $this->session->flash('error', 'Invalid security token.');
            return $this->response->redirect('/admin/settings');
        }

        $settingsData = $this->request->all();
        unset($settingsData['_csrf']);

        try {
            $this->settingModel->updateBatch($settingsData);
            $this->session->flash('success', 'Settings saved successfully.');
        } catch (\Exception $e) {
            error_log('Settings Save Error: ' . $e->getMessage());
            $this->session->flash('error', 'An error occurred while saving settings.');
        }

        return $this->response->redirect('/admin/settings');
    }
}