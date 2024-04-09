<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class ColorModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarColores(): array {
        $stmt = $this->pdo->query("SELECT * FROM colores");
        return $stmt->fetchAll();
    }

    public function comprobarColor($idColor): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM colores WHERE id_color = ?");
        $stmt->execute([$idColor]);

        if (!$stmt->fetch()) {
            return false;
        }
        return true;
    }
}
