<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

class Setting
{
    /**
     * Obtiene todas las configuraciones y las devuelve como un array asociativo.
     *
     * @return array ['setting_key' => 'setting_value', ...]
     */
    public function getAllAsAssoc(): array
    {
        $stmt = DB::query("SELECT `key`, `value` FROM settings");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * Obtiene el valor de una configuración específica.
     *
     * @param string $key La clave de la configuración.
     * @param mixed $default El valor a devolver si la clave no se encuentra.
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $stmt = DB::prepare("SELECT `value` FROM settings WHERE `key` = :key");
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetchColumn();
        return $result === false ? $default : $result;
    }

    /**
     * Actualiza un lote de configuraciones en una sola transacción.
     *
     * @param array $settings Array asociativo de configuraciones a actualizar.
     */
    public function updateBatch(array $settings): void
    {
        $pdo = DB::getInstance();
        $pdo->beginTransaction();
        
        try {
            $stmt = $pdo->prepare("UPDATE settings SET `value` = :value WHERE `key` = :key");
            foreach ($settings as $key => $value) {
                $stmt->execute([':key' => $key, ':value' => $value]);
            }
            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e; // Relanzar la excepción para que el controlador la maneje.
        }
    }
}