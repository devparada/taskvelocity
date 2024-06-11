<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class RolModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Muestra los roles de la base de datos
     * @return array Devuelve los roles de la base de datos
     */
    public function mostrarRoles(): array {
        $stmt = $this->pdo->query("SELECT * FROM roles");
        return $stmt->fetchAll();
    }

    /**
     * Comprueba el id del rol pasado como parámetro en la base de datos
     * @param string $idRol el id del rol
     * @return bool Devuelve true si el rol existe o si no false
     */
    public function comprobarRol(string $idRol): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE id_rol = ?");
        $stmt->execute([$idRol]);

        return ($stmt->fetch()) ? true : false;
    }
}
