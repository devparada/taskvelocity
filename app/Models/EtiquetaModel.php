<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class EtiquetaModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Muestra las etiquetas de la base de datos
     * @return array Retorna un array con las etiquetas
     */
    public function mostrarEtiquetas(): array {
        $stmt = $this->pdo->query("SELECT * FROM etiquetas");
        return $stmt->fetchAll();
    }

    /**
     * Comprueba si el id de la etiqueta pasado existe en la base de datos
     * @param string $idEtiqueta el id de la etiqueta
     * @return bool Retorna true si encuentra la etiqueta sino false
     */
    public function comprobarEtiqueta(string $idEtiqueta): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM etiquetas WHERE id_etiqueta = ?");
        $stmt->execute([$idEtiqueta]);

        return ($stmt->fetch()) ? true : false;
    }
}
