<?php

declare(strict_types=1);

namespace App.Services;

use App.Core.DB;
use PDO;

/**
 * Servicio para realizar búsquedas de texto completo en los pastes.
 * Utiliza el motor Full-Text Search (FTS) de MySQL/MariaDB.
 *
 * REQUISITO: Las tablas `pastes` (para el título) y `paste_contents` (para el contenido)
 * deben tener un índice FULLTEXT creado.
 *
 * Ejemplo de SQL para añadir los índices:
 * ALTER TABLE pastes ADD FULLTEXT(title);
 * ALTER TABLE paste_contents ADD FULLTEXT(content);
 */
class SearchService
{
    /**
     * Realiza una búsqueda de pastes públicos.
     *
     * @param string $query La cadena de búsqueda.
     * @param int $limit El número de resultados por página.
     * @param int $offset El desplazamiento para la paginación.
     * @return array Un array con los resultados y el total de coincidencias.
     */
    public function searchPublicPastes(string $query, int $limit = 20, int $offset = 0): array
    {
        if (empty(trim($query))) {
            return ['results' => [], 'total' => 0];
        }

        $pdo = DB::getInstance();

        // El modo booleano permite usar operadores como + y - en la búsqueda.
        // Añadimos '*' al final de cada palabra para permitir búsquedas de prefijos (ej. 'pyth' encuentra 'python').
        $booleanQuery = '';
        $words = preg_split('/[\s,]+/', $query, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($words as $word) {
            $booleanQuery .= '+' . $word . '* ';
        }
        $booleanQuery = trim($booleanQuery);

        // Primero, contamos el total de resultados para la paginación.
        $countSql = "
            SELECT COUNT(p.id)
            FROM pastes p
            INNER JOIN paste_contents pc ON p.id = pc.paste_id
            WHERE 
                p.visibility = 'public' AND
                p.deleted_at IS NULL AND
                (p.expires_at IS NULL OR p.expires_at > NOW()) AND
                (MATCH(p.title) AGAINST(:query IN BOOLEAN MODE) OR MATCH(pc.content) AGAINST(:query IN BOOLEAN MODE))
        ";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute([':query' => $booleanQuery]);
        $totalResults = (int) $countStmt->fetchColumn();

        if ($totalResults === 0) {
            return ['results' => [], 'total' => 0];
        }

        // Luego, obtenemos los resultados para la página actual, ordenados por relevancia.
        $sql = "
            SELECT 
                p.id, 
                p.title,
                p.language,
                p.created_at,
                (
                    (MATCH(p.title) AGAINST(:query IN BOOLEAN MODE)) * 2 + 
                    (MATCH(pc.content) AGAINST(:query IN BOOLEAN MODE))
                ) AS relevance
            FROM pastes p
            INNER JOIN paste_contents pc ON p.id = pc.paste_id
            WHERE 
                p.visibility = 'public' AND
                p.deleted_at IS NULL AND
                (p.expires_at IS NULL OR p.expires_at > NOW()) AND
                (MATCH(p.title) AGAINST(:query IN BOOLEAN MODE) OR MATCH(pc.content) AGAINST(:query IN BOOLEAN MODE))
            ORDER BY relevance DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':query', $booleanQuery);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['results' => $results, 'total' => $totalResults];
    }
}