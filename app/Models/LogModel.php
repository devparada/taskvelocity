<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class LogModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Muestra los registros de la base de datos
     * @return array Retorna el array de los registros
     */
    public function mostrarLogs(): array {
        $stmt = $this->pdo->query("SELECT * FROM logs l"
                . " JOIN usuarios u ON l.id_usuario_prop = u.id_usuario"
                . " ORDER BY fecha_log DESC");
        return $stmt->fetchAll();
    }

    /**
     * Muestra los 6 últimos registros de la base de datos
     * @return array Retorna el array de los registros
     */
    public function mostrarLogsInicio(): array {
        $stmt = $this->pdo->query("SELECT * FROM logs l"
                . " JOIN usuarios u ON l.id_usuario_prop = u.id_usuario"
                . " ORDER BY fecha_log DESC LIMIT 6");
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

    public function obtenerPaginas() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM logs l");

        $numeroPaginas = ceil($stmt->fetchColumn() / $_ENV["tabla.filasPagina"]);
        return $numeroPaginas;
    }

    public function consultarPagina(int $numeroPagina): array {
        $stmt = $this->pdo->query("SELECT * FROM logs l"
                . " JOIN usuarios u ON l.id_usuario_prop = u.id_usuario"
                . " ORDER BY l.fecha_log DESC LIMIT " . $numeroPagina * $_ENV["tabla.filasPagina"] . "," . $_ENV["tabla.filasPagina"]);

        return $stmt->fetchAll();
    }

    public function filtrarPorUsuario($idUsuario, $numeroPagina) {
        $stmt = $this->pdo->prepare("SELECT * FROM logs l"
                . " JOIN usuarios u ON l.id_usuario_prop = u.id_usuario"
                . " WHERE l.id_usuario_prop = ?"
                . " ORDER BY l.fecha_log DESC LIMIT " . $numeroPagina * $_ENV["tabla.filasPagina"] . "," . $_ENV["tabla.filasPagina"]);
        $stmt->execute([$idUsuario]);

        return $stmt->fetchAll();
    }
}
