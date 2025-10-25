<?php

declare(strict_types=1);

// -----------------------------------------------------------------------------
// PasteX Pro - Application Bootstrap File
// -----------------------------------------------------------------------------
// Este archivo es responsable de inicializar la aplicación:
// 1. Registrar el autoloader de Composer.
// 2. Cargar las variables de entorno desde el archivo .env.
// 3. Configurar el manejo de errores y la zona horaria.
// 4. Iniciar la sesión.
// 5. Preparar la aplicación para manejar la petición entrante.
// -----------------------------------------------------------------------------

// Paso 1: Registrar el Autoloader de Composer
// Esto permite que las clases de PHP se carguen automáticamente sin `require` manual.
require_once __DIR__ . '/../vendor/autoload.php';

// Paso 2: Cargar las Variables de Entorno
// La clase Env carga el archivo .env desde el directorio raíz del proyecto.
try {
    App\Core\Env::load(__DIR__ . '/..');
} catch (\Exception $e) {
    // Si .env no se puede cargar, la aplicación no puede continuar.
    http_response_code(500);
    echo "<b>Critical Error:</b> Could not load environment configuration. Please ensure a valid <code>.env</code> file exists.";
    exit(1);
}

// Paso 3: Configuración del Entorno y Manejo de Errores
$app_env = $_ENV['APP_ENV'] ?? 'production';
$app_debug = filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN);

// Establecer la zona horaria por defecto.
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');

// Configurar cómo se muestran los errores.
if ($app_debug) {
    // En modo desarrollo, mostrar todos los errores detalladamente.
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    // En modo producción, no mostrar errores al usuario final.
    ini_set('display_errors', '0');
    error_reporting(0);
    
    // Registrar los errores en el archivo de log.
    set_error_handler(function ($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return;
        }
        App\Core\Logger::error($message, ['file' => $file, 'line' => $line]);
    });

    set_exception_handler(function ($exception) {
        App\Core\Logger::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
        
        // Mostrar una página de error genérica en producción.
        http_response_code(500);
        App\Core\View::render('errors.500');
    });
}

// Paso 4: Iniciar la Sesión
// El constructor de la clase Session inicia y configura la sesión.
$session = new App\Core\Session();

// Paso 5: Cargar y registrar la configuración en el contenedor de la App
// Esto hace que la configuración sea accesible globalmente a través de App::get('config').
$config = [
    'app' => require __DIR__ . '/../config/app.php',
    'database' => require __DIR__ . '/../config/database.php',
    'mail' => require __DIR__ . '/../config/mail.php',
    'security' => require __DIR__ . '/../config/security.php',
    'cache' => require __DIR__ . '/../config/cache.php',
];
App\Core\App::bind('config', $config);

// La aplicación está ahora "arrancada" y lista para recibir la petición.
// El siguiente paso, manejado en public/index.php, será el enrutamiento.