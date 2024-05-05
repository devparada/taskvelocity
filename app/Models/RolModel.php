<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class RolModel extends \Com\TaskVelocity\Core\BaseModel {

    public function mostrarRoles(): array {
        $stmt = $this->pdo->query("SELECT * FROM roles");
        return $stmt->fetchAll();
    }

    public function comprobarRol(string $idRol): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE id_rol = ?");
        $stmt->execute([$idRol]);

        if (!$stmt->fetch()) {
            return false;
        }
        return true;
    }
}
