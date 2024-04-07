<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class UsuarioModel extends \Com\Daw2\Core\BaseModel {

    function mostrarUsuarios(): array {
        $stmt = $this->pdo->query("SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color");
        return $stmt->fetchAll();
    }
}
