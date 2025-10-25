<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

class ApiKey
{
    /**
     * Busca un usuario asociado a una API Key. La key proporcionada es en texto plano.
     * Este método se encarga de hashear la key para compararla con la base de datos.
     *
     * @param string $plainTextKey La API Key en texto plano.
     * @return array|false El array del usuario si se encuentra, o false.
     */
    public function findUserByApiKey(string $plainTextKey): array|false
    {
        $keyHash = hash('sha256', $plainTextKey);

        $stmt = DB::prepare(
            "SELECT u.* FROM users u
             JOIN api_keys ak ON u.id = ak.user_id
             WHERE ak.key_hash = :key_hash AND u.deleted_at IS NULL"
        );
        $stmt->execute([':key_hash' => $keyHash]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza el timestamp 'last_used_at' de una API key.
     *
     * @param string $plainTextKey La API Key en texto plano.
     */
    public function updateLastUsed(string $plainTextKey): void
    {
        $keyHash = hash('sha256', $plainTextKey);
        $stmt = DB::prepare("UPDATE api_keys SET last_used_at = NOW() WHERE key_hash = :key_hash");
        $stmt->execute([':key_hash' => $keyHash]);
    }

    /**
     * Crea una nueva API key para un usuario.
     *
     * @param int $userId El ID del usuario.
     * @param string $name Un nombre descriptivo para la key.
     * @return string La nueva API Key en texto plano (para mostrar al usuario una sola vez).
     */
    public function create(int $userId, string $name): string
    {
        $plainTextKey = 'pxp_' . bin2hex(random_bytes(24));
        $keyHash = hash('sha256', $plainTextKey);

        $stmt = DB::prepare(
            "INSERT INTO api_keys (user_id, name, key_hash, scopes, created_at) 
             VALUES (:user_id, :name, :key_hash, '[]', NOW())"
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':name' => $name,
            ':key_hash' => $keyHash
        ]);

        return $plainTextKey;
    }
}