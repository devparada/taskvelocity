<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class ColorModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Muestra los colores de la base de datos
     * @return array Devuelve los colores
     */
    public function mostrarColores(): array {
        $stmt = $this->pdo->query("SELECT * FROM colores");
        return $stmt->fetchAll();
    }

    /**
     * Comprueba el id del color recibido como parámetro si existe en la base de datos
     * @param type $idColor el id del color
     * @return bool Devuelve true si el color existe y si no false
     */
    public function comprobarColor($idColor): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM colores WHERE id_color = ?");
        $stmt->execute([$idColor]);

        return ($stmt->fetch()) ? true : false;
    }

    /**
     * Comprueba si el color es un número
     * @param string $idColor el id del color
     * @return bool Devuelve true si es un número y si no false
     */
    public function comprobarColorNumero(string $idColor): bool {
        return (filter_var($idColor, FILTER_VALIDATE_INT)) ? true : false;
    }
}
