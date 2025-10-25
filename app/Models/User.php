<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

class User
{
    /**
     * Encuentra un usuario por su ID, excluyendo datos sensibles.
     */
    public function find(int $id)
    {
        $stmt = DB::prepare("SELECT id, email, role, avatar, bio, created_at FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Encuentra un usuario por su ID, incluyendo el hash de la contraseña.
     */
    public function findWithPassword(int $id)
    {
        $stmt = DB::prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Encuentra un usuario por su dirección de email.
     */
    public function findByEmail(string $email)
    {
        $stmt = DB::prepare("SELECT * FROM users WHERE email = :email AND deleted_at IS NULL");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene una lista paginada de todos los usuarios para el panel de administración.
     */
    public function getAll(int $limit = 25, int $offset = 0): array
    {
        $stmt = DB::prepare("SELECT id, email, role, created_at FROM users WHERE deleted_at IS NULL ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cuenta el número total de usuarios no borrados.
     * Corregido para devolver siempre un entero.
     */
    public function countAll(): int
    {
        // ===============================================================
        //  FIX: Forzar el valor de retorno a ser un entero (int)
        // ===============================================================
        $stmt = DB::query("SELECT COUNT(id) FROM users WHERE deleted_at IS NULL");
        return (int) $stmt->fetchColumn();
        // ===============================================================
    }

    /**
     * Crea un nuevo usuario.
     */
    public function create(string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $stmt = DB::prepare(
            "INSERT INTO users (email, password_hash, role, created_at, updated_at) 
             VALUES (:email, :password, 'user', NOW(), NOW())"
        );
        $stmt->execute([':email' => $email, ':password' => $hash]);
        return (int)DB::lastInsertId();
    }

    /**
     * Actualiza el perfil de un usuario.
     */
    public function update(int $userId, array $data): bool
    {
        $stmt = DB::prepare("UPDATE users SET bio = :bio, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':bio' => $data['bio'], ':id' => $userId]);
    }
    
    /**
     * Actualiza la contraseña de un usuario.
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_ARGON2ID);
        $stmt = DB::prepare("UPDATE users SET password_hash = :hash, updated_at = NOW() WHERE id = :id");
        return $stmt->execute([':hash' => $hash, ':id' => $userId]);
    }

    /**
     * Actualiza un usuario desde el panel de administración.
     */
    public function adminUpdate(int $userId, array $data): bool
    {
        $params = [':id' => $userId, ':email' => $data['email'], ':role' => $data['role'], ':bio' => $data['bio']];
        $sql = "UPDATE users SET email = :email, role = :role, bio = :bio, updated_at = NOW()";

        if (!empty($data['password'])) {
            $sql .= ", password_hash = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
        }

        $sql .= " WHERE id = :id";
        
        $stmt = DB::prepare($sql);
        return $stmt->execute($params);
    }
}