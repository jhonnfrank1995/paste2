<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\DB;

class PruneExpiredPastes
{
/**
* Marca como borrados los pastes que ya han expirado.
* Devuelve el número de pastes afectados.
*/
public function execute(): int
{
$stmt = DB::prepare(
"UPDATE pastes SET deleted_at = NOW()
WHERE expires_at IS NOT NULL AND expires_at < NOW() AND deleted_at IS NULL"
);
$stmt->execute();

return $stmt->rowCount();
}
}