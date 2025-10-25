<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

class Comment
{
    /**
     * Obtiene los comentarios aprobados para un paste específico, paginados.
     *
     * @param string $pasteId ID del paste.
     * @param int $limit Número de comentarios por página.
     * @param int $offset Desplazamiento.
     * @return array
     */
    public function findApprovedByPasteId(string $pasteId, int $limit = 20, int $offset = 0): array
    {
        $stmt = DB::prepare(
            "SELECT c.id, c.body, c.created_at, u.email AS user_email, u.avatar 
             FROM comments c
             LEFT JOIN users u ON c.user_id = u.id
             WHERE c.paste_id = :paste_id AND c.status = 'approved'
             ORDER BY c.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':paste_id', $pasteId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', o, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo comentario.
     *
     * @param string $pasteId ID del paste.
     * @param ?int $userId ID del usuario (null si es anónimo).
     * @param string $body Contenido del comentario.
     * @param string $ipHash Hash del IP del comentarista.
     * @param string $status Estado inicial ('pending' o 'approved').
     * @return int El ID del nuevo comentario.
     */
    public function create(string $pasteId, ?int $userId, string $body, string $ipHash, string $status = 'pending'): int
    {
        $stmt = DB::prepare(
            "INSERT INTO comments (paste_id, user_id, body, ip_hash, status, created_at)
             VALUES (:paste_id, :user_id, :body, :ip_hash, :status, NOW())"
        );
        $stmt->execute([
            ':paste_id' => $pasteId,
            ':user_id' => $userId,
            ':body' => $body,
            ':ip_hash' => $ipHash,
            ':status' => $status
        ]);
        return (int)DB::lastInsertId();
    }
}