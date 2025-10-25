<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\PruneExpiredPastes;
use Cron\CronExpression;

/**
 * El Kernel de la consola define los comandos y las tareas programadas de la aplicación.
 */
class Kernel
{
    /**
     * Los comandos de la aplicación que deben ser registrados.
     *
     * La clave es el nombre del comando (usado en la CLI) y el valor es la clase del comando.
     *
     * @var array
     */
    protected array $commands = [
        'pastes:prune' => PruneExpiredPastes::class,
        // Aquí se podrían añadir más comandos, por ejemplo:
        // 'search:reindex' => ReindexSearch::class,
        // 'backups:run' => RunBackups::class,
    ];

    /**
     * Define la programación de las tareas de la aplicación.
     *
     * @return array
     */
    protected function schedule(): array
    {
        // Se define un array de tareas programadas.
        // Cada tarea es un array que contiene la clase del comando y su frecuencia en formato cron.
        return [
            [PruneExpiredPastes::class, '*/5 * * * *'], // Ejecutar cada 5 minutos
            // Ejemplo de otras tareas:
            // [RecalculateTrends::class, '0 * * * *'],    // Ejecutar cada hora
            // [RotateLogs::class, '0 0 * * *'],       // Ejecutar a medianoche todos los días
        ];
    }

    /**
     * Obtiene todos los comandos registrados en el kernel.
     *
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * Obtiene las tareas programadas que deben ejecutarse en el momento actual.
     *
     * @return array
     */
    public function getDueTasks(): array
    {
        $dueTasks = [];
        $scheduledTasks = $this->schedule();
        
        foreach ($scheduledTasks as $task) {
            $commandClass = $task[0];
            $expression = $task[1];

            $cron = new CronExpression($expression);
            
            // Comprueba si la expresión cron coincide con la fecha y hora actual.
            if ($cron->isDue()) {
                // Si coincide, se añade la clase del comando a la lista de tareas pendientes.
                $dueTasks[] = $commandClass;
            }
        }

        return $dueTasks;
    }
}