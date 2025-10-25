<?php
// PasteX Pro Installer - Step Controller

// Configuración inicial de errores
error_reporting(E_ALL);
ini_set('display_errors', '1');

define('INSTALLER_PATH', __DIR__);

// ===================================================================
//  FIX DEFINITIVO Y ÚNICO: CARGA DE AUTOLOADER
// ===================================================================
// Se asegura de que las dependencias de Composer estén disponibles
// para CUALQUIER petición que llegue a este archivo, incluyendo las
// peticiones AJAX a `?step=ajax_install`.
// Esta es la única corrección necesaria para el error de "Class not found".
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Si el autoloader no existe, detenemos todo, ya que nada funcionará.
    // Esto solo debería ocurrir si `composer install` no se ha ejecutado.
    die("Error Crítico: El archivo 'vendor/autoload.php' no se encuentra. Por favor, ejecuta 'composer install' en el directorio raíz del proyecto antes de continuar.");
}
// ===================================================================

session_start();

// El resto del script ahora puede asumir que todas las clases están disponibles.
require_once 'Installer.php';
$installer = new Installer();

$step = $_GET['step'] ?? 1;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

switch ($step) {
    case 1:
        $checks = $installer->checkRequirements();
        $all_ok = !$checks['has_errors'];
        include 'views/step1_checks.php';
        break;

    case 2:
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_step']) && $_POST['from_step'] == '1') {
            $_SESSION['installer_data'] = [];
        }
        $data = $_SESSION['installer_data']['db'] ?? [];
        include 'views/step2_database.php';
        break;

    case 3:
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_step']) && $_POST['from_step'] == '2') {
            $db_data = $_POST;
            $_SESSION['installer_data']['db'] = $db_data;
            
            $connection_result = $installer->checkDbConnection($db_data);
            if (!$connection_result['success']) {
                $_SESSION['error'] = $connection_result['message'];
                header('Location: index.php?step=2');
                exit;
            }
        }
        $data = $_SESSION['installer_data']['site'] ?? ['url' => $installer->guessUrl()];
        include 'views/step3_settings.php';
        break;
        
    case 4:
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_step']) && $_POST['from_step'] == '3') {
            $_SESSION['installer_data']['site'] = $_POST;
            header('Location: index.php?step=4');
            exit;
        }
        include 'views/step4_installing.php';
        break;

    case 5:
        if (!($_SESSION['install_complete'] ?? false)) {
            header('Location: index.php?step=1');
            exit;
        }
        $site_url = $_SESSION['installer_data']['site']['url'] ?? '/';
        session_destroy();
        include 'views/step5_complete.php';
        break;
        
    case 'ajax_install':
        ini_set('display_errors', '0');
        ob_start(); 

        header('Content-Type: application/json');

        try {
            if (!isset($_SESSION['installer_data'])) {
                throw new Exception('Session data lost. Please restart the installation.');
            }
            
            $result = $installer->run($_SESSION['installer_data']);
            
            if ($result['success']) {
                $_SESSION['install_complete'] = true;
            }

        } catch (\Throwable $e) {
            $result = [
                'success' => false,
                'message' => 'Critical Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine()
            ];
        }

        ob_end_clean();
        echo json_encode($result);
        exit;

    default:
        include 'views/step1_checks.php';
        break;
}