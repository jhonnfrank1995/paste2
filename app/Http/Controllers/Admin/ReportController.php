<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Core\Controller;
use App\Core\View;
use App\Models\Report;

/**
 * Controlador para la gestión de reportes de abuso.
 */
class ReportController extends Controller
{
    private Report $reportModel;

    public function __construct()
    {
        parent::__construct();
        $this->reportModel = $this->model('Report');
    }

    /**
     * Muestra una lista de todos los reportes abiertos.
     */
    public function index()
    {
        $openReports = $this->reportModel->findOpenReports();
        
        View::render('admin.reports.index', [
            'page_title' => 'Abuse Reports',
            'reports' => $openReports
        ]);
    }
}