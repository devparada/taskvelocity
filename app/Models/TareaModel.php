<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class TareaModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarTareas(): array {
        $stmt = $this->pdo->query("SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
                . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
                . "ON ta.id_tarea=ut.id_tareaTAsoc GROUP BY ut.id_tareaTAsoc");
        $datos = $stmt->fetchAll();

        for ($i = 0; $i < count($datos); $i++) {
            for ($j = 0; $j < $datos[$i]["COUNT(id_usuarioTAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas JOIN usuarios"
                        . " ON usuarios_tareas.id_usuarioTAsoc = usuarios.id_usuario"
                        . " WHERE id_tareaTAsoc =" . $datos[$i]["id_tareaTAsoc"]);

                $usuariosTareas = $stmt->fetchAll();

                $datos[$i]["nombresUsuarios"] = $this->mostrarUsernamesTarea($usuariosTareas);
            }
        }
        return $datos;
    }

    private function mostrarUsernamesTarea(array $usuariosTareas): array {
        $usuarios = [];

        for ($index = 0; $index < count($usuariosTareas); $index++) {
            $usuarios[$index] = $usuariosTareas[$index]["username"] . " ";
        }

        return $usuarios;
    }

    public function buscarTareaPorId(int $idTarea): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
                . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c ON ta.id_color_tarea = c.id_color "
                . "WHERE id_tarea = ?");
        $stmt->execute([$idTarea]);

        $usuarioEncontrado = $stmt->fetch();

        if ($usuarioEncontrado) {
            return $usuarioEncontrado;
        } else {
            return null;
        }
    }

    public function addTarea(string $nombreTarea, ?string $fechaLimite, string $idColorTarea, string $idProyecto, array $id_usuarios_asociados, string $descripcionTarea): bool {
        $stmt = $this->pdo->prepare("INSERT INTO tareas "
                . "(nombre_tarea, id_color_tarea, descripcion_tarea, fecha_limite, id_usuario_tarea_prop, id_proyecto) "
                . "VALUES (?, ?, ?, ?, ?, ?)");

        // Si la fecha límite no se especifica se cambia por null
        if ($fechaLimite == "") {
            $fechaLimite = null;
        }

        if ($stmt->execute([$nombreTarea, $idColorTarea, $descripcionTarea, $fechaLimite, 1, $idProyecto])) {
            // Se consigue el id de la tarea debido a que es la última tarea insertada
            $idTarea = $this->pdo->lastInsertId();
            $this->addTareaUsuarios($id_usuarios_asociados, $idTarea);
            // $this->crearImagen($idTarea);
            return true;
        }
        return false;
    }

    private function addTareaUsuarios(array $idUsuarios, string $idTarea): void {
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_tareas "
                    . "(id_usuarioTAsoc, id_tareaTAsoc) VALUES(?, ?)");

            $stmt->execute([$idUsuario, $idTarea]);
        }
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tareas");
        return $stmt->fetchColumn();
    }

    public function deleteTarea(int $idTarea): bool {
        if (!is_null($this->buscarTareaPorId($idTarea))) {
            $stmt = $this->pdo->prepare("DELETE FROM tareas WHERE id_tarea = ?");
            $stmt->execute([$idTarea]);
            return true;
        }

        return false;
    }
}
