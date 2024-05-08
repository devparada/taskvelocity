<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class LogModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Muestra los registros de la base de datos 
     * @return array Retorna el array de los registros
     */
    public function mostrarLogs(): array {
        $stmt = $this->pdo->query("SELECT * FROM logs l "
                . "JOIN usuarios u ON l.id_usuario_prop = u.id_usuario");
        return $stmt->fetchAll();
    }

    /**
     * Crea el log y lo guarda en la base de datos
     * @param string $asunto el asunto del log
     * @param int $idUsuario el id del usuario
     * @return void
     */
    public function crearLog(string $asunto, int $idUsuario): void {
        $stmt = $this->pdo->prepare("INSERT INTO logs"
                . " (asunto, id_usuario_prop)"
                . " VALUES(?, ?)");
        $stmt->execute([$asunto, $idUsuario]);
    }
}
