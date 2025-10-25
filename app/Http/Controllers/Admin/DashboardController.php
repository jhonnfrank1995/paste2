<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;

/**
 * Controlador para el panel principal de administraci¨®n.
 */
class DashboardController extends Controller
{
    public function index()
    {
        // En una implementaci¨®n real, estos datos vendr¨ªan de un modelo o servicio de estad¨ªsticas.
        $stats = [
            'total_pastes' => $this->model('Paste')->countAll(),
            'total_users' => $this->model('User')->countAll(),
            'pastes_last_24h' => 58, // Placeholder
            'views_last_7d' => 12500, // Placeholder
        ];

        // ===============================================================
        //  FIX: Llamar a la vista correcta
        // ===============================================================
        // La vista se llama 'admin.dashboard', no 'admin.dashboard.index'.
        View::render('admin.dashboard', [
            'page_title' => 'Admin Dashboard',
            'stats' => $stats
        ]);
        // ===============================================================
    }
}