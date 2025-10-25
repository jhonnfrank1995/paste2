<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;
use Exception;

class Paste
{
    /**
     * Encuentra un paste por su ID, con opción de incluir el contenido.
     * También verifica que no esté borrado o expirado.
     */
    public function findById(string $id, bool $withContent = true)
    {
        $fields = $withContent ? "p.*, pc.content" : "p.*, pc.encryption_meta";
        $join = $withContent ? "JOIN paste_contents pc ON p.id = pc.paste_id" : "LEFT JOIN paste_contents pc ON p.id = pc.paste_id";

        $sql = "SELECT {$fields} FROM pastes p {$join}
                WHERE p.id = :id AND p.deleted_at IS NULL 
                AND (p.expires_at IS NULL OR p.expires_at > NOW())";
        
        $stmt = DB::prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Encuentra todos los pastes pertenecientes a un ID de usuario específico.
     */
    public function findByUserId(int $userId, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT id, title, language, visibility, created_at, size_bytes, views_count
                FROM pastes WHERE user_id = :user_id AND deleted_at IS NULL
                ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = DB::prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene los pastes públicos más recientes para la página de inicio.
     */
    public function getRecentPublic(int $limit = 15): array
    {
        $sql = "SELECT id, title, language, created_at, size_bytes 
                FROM pastes WHERE visibility = 'public' AND deleted_at IS NULL
                AND (expires_at IS NULL OR expires_at > NOW())
                ORDER BY created_at DESC LIMIT :limit";
        $stmt = DB::prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene una lista paginada de todos los pastes para el panel de administración.
     */
    public function getAll(int $limit = 25, int $offset = 0): array
    {
        $sql = "SELECT p.id, p.title, p.language, p.visibility, p.created_at, u.email as user_email
                FROM pastes p LEFT JOIN users u ON p.user_id = u.id
                WHERE p.deleted_at IS NULL ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = DB::prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cuenta el número total de pastes no borrados.
     * Corregido para devolver siempre un entero.
     */
    public function countAll(): int
    {
        $stmt = DB::query("SELECT COUNT(id) FROM pastes WHERE deleted_at IS NULL");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Crea un nuevo paste y su contenido en una transacción.
     */
    public function create(array $data): array
    {
        $pdo = DB::getInstance();
        try {
            $pdo->beginTransaction();
            
            $id = bin2hex(random_bytes(8));
            $edit_token = $data['user_id'] ? null : bin2hex(random_bytes(16));

            $stmt = $pdo->prepare(
                "INSERT INTO pastes (id, user_id, title, language, visibility, has_password, password_hash, 
                 burn_after_read, expires_at, size_bytes, edit_token, created_at, updated_at) 
                 VALUES (:id, :user_id, :title, :language, :visibility, :has_password, :password_hash, 
                 :burn, :expires_at, :size, :edit_token, NOW(), NOW())"
            );
            
            $password_hash = !empty($data['password']) ? password_hash($data['password'], PASSWORD_ARGON2ID) : null;
            
            $stmt->execute([
                ':id' => $id,
                ':user_id' => $data['user_id'],
                ':title' => $data['title'],
                ':language' => $data['language'],
                ':visibility' => $data['visibility'],
                ':has_password' => !empty($data['password']),
                ':password_hash' => $password_hash,
                ':burn' => ($data['expiration'] === 'burn_after_read'),
                ':expires_at' => $this->calculateExpiry($data['expiration']),
                ':size' => strlen($data['content']),
                ':edit_token' => $edit_token
            ]);
            
            $stmtContent = $pdo->prepare("INSERT INTO paste_contents (paste_id, content) VALUES (:id, :content)");
            $stmtContent->execute([':id' => $id, ':content' => $data['content']]);
            
            $pdo->commit();
            
            $response = ['status' => 'success', 'id' => $id];
            if ($edit_token) $response['edit_token'] = $edit_token;
            return $response;

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
            return ['status' => 'error', 'message' => 'Could not create the paste.'];
        }
    }
    
    /**
     * Actualiza un paste desde el panel de administración.
     */
    public function adminUpdate(string $id, array $data): bool
    {
        $pdo = DB::getInstance();
        try {
            $pdo->beginTransaction();

            $pasteStmt = $pdo->prepare(
                "UPDATE pastes SET title = :title, language = :language, visibility = :visibility, updated_at = NOW() WHERE id = :id"
            );
            $pasteStmt->execute([
                ':title' => $data['title'],
                ':language' => $data['language'],
                ':visibility' => $data['visibility'],
                ':id' => $id,
            ]);

            $contentStmt = $pdo->prepare("UPDATE paste_contents SET content = :content WHERE paste_id = :id");
            $contentStmt->execute([':content' => $data['content'], ':id' => $id]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    /**
     * Realiza un borrado lógico (soft delete) de un paste.
     */
    public function delete(string $id): bool
    {
        $stmt = DB::prepare("UPDATE pastes SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Incrementa el contador de vistas de un paste.
     */
    public function incrementViewCount(string $id): void
    {
        // Esta operación es "fire-and-forget", no necesita ser transaccional.
        $stmt = DB::prepare("UPDATE pastes SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Calcula el timestamp de expiración a partir de una cadena de texto.
     */
    private function calculateExpiry(?string $expiration): ?string
    {
        if (empty($expiration) || in_array($expiration, ['never', 'burn_after_read'])) {
            return null;
        }
        try {
            $intervalMapping = [
                '10m' => '10 minutes',
                '1h' => '1 hour',
                '1d' => '1 day',
                '1w' => '1 week',
                '1m' => '1 month' // Asumimos '1m' es 1 mes
            ];

            $interval = $intervalMapping[$expiration] ?? null;

            if ($interval) {
                return (new \DateTimeImmutable())->modify('+' . $interval)->format('Y-m-d H:i:s');
            }
            return null;
        } catch(Exception) {
            // Si el formato es inválido, no expira.
            return null;
        }
    }
}