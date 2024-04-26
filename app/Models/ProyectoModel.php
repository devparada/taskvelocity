<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class ProyectoModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarProyectos(): array {
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $stmt = $this->pdo->query("SELECT *,  COUNT(id_usuarioPAsoc) FROM proyectos pr LEFT JOIN usuarios us"
                    . " ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up"
                    . " ON pr.id_proyecto = up.id_proyectoPAsoc GROUP BY id_proyectoPAsoc");
        } else {
            $stmt = $this->pdo->prepare("SELECT *,  COUNT(id_usuarioPAsoc) FROM proyectos pr LEFT JOIN usuarios us"
                    . " ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up"
                    . " ON pr.id_proyecto = up.id_proyectoPAsoc WHERE id_usuario_proyecto_prop = ? GROUP BY id_proyectoPAsoc");
            $stmt->execute([$_SESSION["usuario"]["id_usuario"]]);
        }
        $datos = $stmt->fetchAll();

        for ($i = 0; $i < count($datos); $i++) {
            for ($j = 0; $j < $datos[$i]["COUNT(id_usuarioPAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_proyectos JOIN usuarios"
                        . " ON usuarios_proyectos.id_usuarioPAsoc = usuarios.id_usuario"
                        . " WHERE id_proyectoPAsoc =" . $datos[$i]["id_proyectoPAsoc"]);

                $usuariosProyectos = $stmt->fetchAll();

                $datos[$i]["nombresUsuarios"] = $this->mostrarUsernameProyecto($usuariosProyectos);
            }
        }
        return $datos;
    }

    private function mostrarUsernameProyecto(array $usuariosProyectos): array {
        $usuarios = [];

        for ($index = 0; $index < count($usuariosProyectos); $index++) {
            $usuarios[$index] = $usuariosProyectos[$index]["username"] . " ";
        }

        return $usuarios;
    }

    public function buscarProyectoPorId(int $idProyecto): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM proyectos pr LEFT JOIN usuarios us"
                . " ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up"
                . " ON pr.id_proyecto = up.id_proyectoPAsoc WHERE id_proyecto = ?");
        $stmt->execute([$idProyecto]);

        $usuarioEncontrado = $stmt->fetch();

        if ($usuarioEncontrado) {
            return $usuarioEncontrado;
        } else {
            return null;
        }
    }

    public function addProyecto(string $nombreProyecto, string $descripcionProyecto, ?string $fechaLimiteProyecto, array $idUsuariosAsociados): bool {
        $stmt = $this->pdo->prepare("INSERT INTO proyectos "
                . "(nombre_proyecto, descripcion_proyecto, fecha_limite_proyecto, id_usuario_proyecto_prop) "
                . "VALUES(?, ?, ?, ?)");

        // Si la fecha límite del proyecto no se especifica se cambia por null
        if ($fechaLimiteProyecto == "") {
            $fechaLimiteProyecto = null;
        }

        if ($stmt->execute([$nombreProyecto, $descripcionProyecto, $fechaLimiteProyecto, 1])) {
            // Se consigue el id del proyecto debido a que es la última tarea insertada
            $idProyecto = $this->pdo->lastInsertId();
            $this->addProyectoUsuarios($idUsuariosAsociados, $idProyecto);
            // $this->crearImagen($idProyecto);
            return true;
        }
        return false;
    }

    private function addProyectoUsuarios(array $idUsuarios, string $idProyecto): void {
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_proyectos "
                    . "(id_usuarioPAsoc, id_proyectoPAsoc) "
                    . "VALUES(?, ?)");

            $stmt->execute([$idUsuario, $idProyecto]);
        }
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM proyectos");
        return $stmt->fetchColumn();
    }

    public function deleteProyecto(int $idProyecto): bool {
        if (!is_null($this->buscarProyectoPorId($idProyecto))) {
            $stmt = $this->pdo->prepare("DELETE FROM proyectos WHERE id_proyecto = ?");
            $stmt->execute([$idProyecto]);
            return true;
        }

        return false;
    }
}
