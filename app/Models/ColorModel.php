<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class ColorModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarColores(): array {
        $stmt = $this->pdo->query("SELECT * FROM colores");
        return $stmt->fetchAll();
    }
}
