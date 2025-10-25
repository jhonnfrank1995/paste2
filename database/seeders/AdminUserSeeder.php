<?php

namespace Database\Seeders;

use App\Core\DB;

/**
 * Seeder para crear el usuario administrador inicial durante la instalación.
 */
class AdminUserSeeder
{
    /**
     * Ejecuta el seeder.
     *
     * @param string $email El email del administrador.
     * @param string $password La contraseña en texto plano.
     * @return bool
     */
    public function run(string $email, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_ARGON2ID);
        
        $pdo = DB::getInstance();
        
        // ===============================================================
        //  FIX DEFINITIVO: Usar placeholders únicos para la contraseña
        // ===============================================================
        $stmt = $pdo->prepare(
            "INSERT INTO users (email, password_hash, role, created_at, updated_at)
             VALUES (:email, :password_insert, 'admin', NOW(), NOW())
             ON DUPLICATE KEY UPDATE password_hash = :password_update, role = 'admin'"
        );
        
        // Línea 31 (aproximadamente)
        return $stmt->execute([
            ':email' => $email,
            ':password_insert' => $passwordHash,
            ':password_update' => $passwordHash, // Pasamos el mismo hash a ambos placeholders
        ]);
        // ===============================================================
    }
}