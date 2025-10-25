<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

class Tag
{
    /**
     * Procesa una cadena de tags, los crea si no existen y devuelve sus IDs.
     *
     * @param string $tagString Una cadena de tags separados por comas.
     * @return array Un array de IDs de los tags.
     */
    public function processTags(string $tagString): array
    {
        $tagNames = array_unique(array_filter(array_map('trim', explode(',', $tagString))));
        if (empty($tagNames)) {
            return [];
        }

        $tagIds = [];
        $pdo = DB::getInstance();
        $findStmt = $pdo->prepare("SELECT id FROM tags WHERE name = :name");
        $insertStmt = $pdo->prepare("INSERT INTO tags (name) VALUES (:name)");

        foreach ($tagNames as $name) {
            $findStmt->execute([':name' => $name]);
            $id = $findStmt->fetchColumn();
            if ($id) {
                $tagIds[] = (int)$id;
            } else {
                $insertStmt->execute([':name' => $name]);
                $tagIds[] = (int)$pdo->lastInsertId();
            }
        }
        return $tagIds;
    }

    /**
     * Sincroniza los tags de un paste. Elimina los viejos y añade los nuevos.
     *
     * @param string $pasteId
     * @param array $tagIds
     */
    public function syncTagsForPaste(string $pasteId, array $tagIds): void
    {
        $pdo = DB::getInstance();
        $pdo->beginTransaction();
        try {
            // Eliminar asociaciones viejas
            $deleteStmt = $pdo->prepare("DELETE FROM paste_tag WHERE paste_id = :paste_id");
            $deleteStmt->execute([':paste_id' => $pasteId]);
            
            // Añadir asociaciones nuevas
            if (!empty($tagIds)) {
                $insertStmt = $pdo->prepare("INSERT INTO paste_tag (paste_id, tag_id) VALUES (:paste_id, :tag_id)");
                foreach ($tagIds as $tagId) {
                    $insertStmt->execute([':paste_id' => $pasteId, ':tag_id' => $tagId]);
                }
            }
            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            error_log("Tag sync failed: " . $e->getMessage());
        }
    }
}