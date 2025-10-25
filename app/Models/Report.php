<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB;
use PDO;

class Report
{
    /**
     * Crea un nuevo reporte de abuso.
     *
     * @param string $pasteId
     * @param string $reporterIpHash
     * @param string $reason
     * @param string $note
     * @return int ID del nuevo reporte.
     */
    public function create(string $pasteId, string $reporterIpHash, string $reason, string $note): int
    {
        $stmt = DB::prepare(
            "INSERT INTO reports (paste_id, reporter_ip_hash, reason, note, status, created_at)
             VALUES (:paste_id, :ip_hash, :reason, :note, 'open', NOW())"
        );
        $stmt->execute([
            ':paste_id' => $pasteId,
            ':ip_hash' => $reporterIpHash,
            ':reason' => $reason,
            ':note' => $note
        ]);
        return (int)DB::lastInsertId();
    }

    /**
     * Obtiene todos los reportes abiertos para el panel de admin.
     *
     * @return array
     */
    public function findOpenReports(): array
    {
        $stmt = DB::query(
            "SELECT r.id, r.paste_id, r.reason, r.note, r.status, r.created_at, p.title as paste_title
             FROM reports r
             JOIN pastes p ON r.paste_id = p.id
             WHERE r.status = 'open' ORDER BY r.created_at ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}