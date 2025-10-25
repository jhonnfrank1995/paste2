#!/usr/bin/env php
<?php

// ==============================================================================
// PasteX Pro - Cron Job Runner
// ==============================================================================
// Este script está diseñado para ser ejecutado por un servicio de cron del
// sistema operativo. Se encarga de ejecutar las tareas programadas.
// ==============================================================================

// Cambiar al directorio raíz del proyecto para que todas las rutas relativas funcionen
chdir(__DIR__);

// Arrancar la aplicación para tener acceso a la base de datos y otros servicios
require_once __DIR__ . '/bootstrap/app.php';

use App\Console\Kernel;
use App\Core\Logger;

if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

echo "Running scheduled tasks...\n";
Logger::info("Cron job started.");

$kernel = new Kernel();
$dueTasks = $kernel->getDueTasks();

if (empty($dueTasks)) {
    echo "No scheduled tasks are due to run.\n";
    Logger::info("No tasks due to run.");
    exit(0);
}

foreach ($dueTasks as $taskClass) {
    try {
        echo "Executing task: {$taskClass}...\n";
        $task = new $taskClass();
        
        if (method_exists($task, 'execute')) {
            $result = $task->execute();
            echo "Task {$taskClass} finished.\n";
            Logger::info("Task executed successfully: {$taskClass}", ['result' => $result]);
        } else {
            throw new Exception("Task class {$taskClass} does not have an execute method.");
        }

    } catch (Throwable $e) {
        echo "Error executing task {$taskClass}: " . $e->getMessage() . "\n";
        Logger::error("Task execution failed: {$taskClass}", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
}

echo "All due tasks have been processed.\n";
Logger::info("Cron job finished.");
exit(0);