<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class TareaModel extends \Com\TaskVelocity\Core\BaseModel {

    public function mostrarTareas(): array {
        $baseConsulta = "SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
                . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
                . "ON ta.id_tarea=ut.id_tareaTAsoc ";
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $stmt = $this->pdo->prepare($baseConsulta . "GROUP BY ut.id_tareaTAsoc");
        } else {
            $stmt = $this->pdo->prepare($baseConsulta . "LEFT JOIN etiquetas et ON ta.id_etiqueta = et.id_etiqueta WHERE us.id_usuario = ? OR ut.id_usuarioTAsoc = ? GROUP BY ut.id_tareaTAsoc");

            $stmt->execute([$_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);
        }

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
            $usuarios[$index] = $usuariosTareas[$index]["username"];
        }

        return $usuarios;
    }

    /**
     * Busca la información de una tarea específica por el id pasado como parámetro
     * @param int $idTarea el id de la tarea
     * @return array|null Devuelve la información de la tarea si la encuentra o null
     */
    public function buscarTareaPorId(int $idTarea): ?array {
        $stmt = $this->pdo->prepare("SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta LEFT JOIN proyectos p "
                . "ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios_tareas ut "
                . "ON ta.id_usuario_tarea_prop=ut.id_usuarioTAsoc LEFT JOIN colores c ON ta.id_color_tarea = c.id_color "
                . "LEFT JOIN usuarios us ON ta.id_usuario_tarea_prop = us.id_usuario WHERE id_tarea = ? GROUP BY ut.id_tareaTAsoc");
        $stmt->execute([$idTarea]);

        $tareaEncontrada = $stmt->fetch();

        if (!empty($tareaEncontrada)) {
            for ($j = 0; $j < $tareaEncontrada["COUNT(id_usuarioTAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas JOIN usuarios"
                        . " ON usuarios_tareas.id_usuarioTAsoc = usuarios.id_usuario"
                        . " WHERE id_tareaTAsoc =" . $tareaEncontrada["id_tareaTAsoc"]);

                $usuariosProyectos = $stmt->fetchAll();

                $tareaEncontrada["nombresUsuarios"] = $this->mostrarUsernamesTarea($usuariosProyectos);
            }
        }

        return ($tareaEncontrada) ? $tareaEncontrada : null;
    }

    /**
     * Muestra los usuarios asociados a una tarea por el id de la tarea
     * @param int $idTarea el id de la tarea
     * @return array Retorna un array con los usuarios de la tarea asociada
     */
    public function mostrarUsuariosPorTarea(int $idTarea): array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios u JOIN usuarios_tareas ut "
                . "ON u.id_usuario = ut.id_usuarioTAsoc "
                . "WHERE ut.id_tareaTAsoc  = ?");
        $stmt->execute([$idTarea]);
        return $stmt->fetchAll();
    }

    /**
     * Muestra las tareas asociadas a un proyecto por el id del proyecto
     * @param int $idProyecto el id del proyecto
     * @return array Retorna un array con las tareas del proyecto asociado
     */
    public function mostrarTareasPorProyecto(int $idProyecto): array {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto "
                . "WHERE ta.id_proyecto = ?");
        $stmt->execute([$idProyecto]);
        return $stmt->fetchAll();
    }

    public function addTarea(string $nombreTarea, ?string $fechaLimite, string $idColorTarea, string $idProyecto, ?array $idUsuariosAsociados, string $descripcionTarea, string $idEtiqueta): bool {
        $stmt = $this->pdo->prepare("INSERT INTO tareas "
                . "(nombre_tarea, id_color_tarea, descripcion_tarea, fecha_limite_tarea, id_usuario_tarea_prop,id_etiqueta, id_proyecto) "
                . "VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Si la fecha límite no se especifica se cambia por null
        if ($fechaLimite == "") {
            $fechaLimite = null;
        }

        if ($stmt->execute([$nombreTarea, $idColorTarea, $descripcionTarea, $fechaLimite, $_SESSION["usuario"]["id_usuario"], $idEtiqueta, $idProyecto])) {
            // Se consigue el id de la tarea debido a que es la última tarea insertada
            $idTarea = $this->pdo->lastInsertId();
            $this->añadirPropietario((int) $_SESSION["usuario"]["id_usuario"], (int) $idTarea);
            if (!empty($idUsuariosAsociados)) {
                $this->addTareaUsuarios($idUsuariosAsociados, (int) $idTarea);
            }

            if (!empty($_FILES["imagen_tarea"]["name"])) {
                $modeloFiles = new \Com\TaskVelocity\Models\FilesModel();
                $modeloFiles->guardarImagen("tareas", "tarea", (int) $idTarea);
            }

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Creada la tarea con el id $idTarea", $_SESSION["usuario"]["id_usuario"]);
            return true;
        }
        return false;
    }

    private function añadirPropietario(int $idUsuario, int $idTarea): void {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios_tareas "
                . "(id_usuarioTAsoc, id_tareaTAsoc) VALUES(?, ?)");

        $stmt->execute([$idUsuario, $idTarea]);
    }

    private function addTareaUsuarios(array $idUsuarios, int $idTarea): void {
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_tareas "
                    . "(id_usuarioTAsoc, id_tareaTAsoc) VALUES(?, ?)");

            $stmt->execute([$idUsuario, $idTarea]);
        }
    }

    /**
     * Edita la tarea en la base de datos a partir de los datos pasados
     * @param string $nombreTarea el nombre de la tarea
     * @param string|null $fechaLimite la fecha límite de la tarea
     * @param string $idColorTarea el color de la tarea
     * @param string $idProyecto el id del proyecto
     * @param array|null $idUsuariosAsociados los ids de los usuarios
     * @param string $descripcionTarea la descripcion de la tarea
     * @param int $idTarea el id de la tarea a editar
     * @return bool Retorna true si se edito correctamente la tarea o false si no
     */
    public function editTarea(string $nombreTarea, ?string $fechaLimite, string $idColorTarea, string $idProyecto, ?array $idUsuariosAsociados, string $descripcionTarea, string $idEtiqueta, int $idTarea): bool {
        $stmt = $this->pdo->prepare("UPDATE tareas"
                . " SET nombre_tarea=?, id_color_tarea=?, descripcion_tarea=?, fecha_limite_tarea=?,id_etiqueta=?, id_proyecto=?"
                . " WHERE id_tarea=?");

        if ($stmt->execute([$nombreTarea, $idColorTarea, $descripcionTarea, $fechaLimite, $idEtiqueta, $idProyecto, $idTarea])) {
            // Se consigue el id del proyecto debido a que es la última tarea insertada
            if (!empty($idUsuariosAsociados)) {
                $this->editarUsuariosTareas($idUsuariosAsociados, $idTarea);
            }

            if (!empty($_FILES["imagen_tarea"]["name"])) {
                $modeloFiles = new \Com\TaskVelocity\Models\FilesModel();
                $modeloFiles->actualizarImagen("tareas", "tarea", $idTarea);
            }

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Editada la tarea con el id $idTarea", $_SESSION["usuario"]["id_usuario"]);
            return true;
        }
        return false;
    }

    private function editarUsuariosTareas(array $idUsuarios, int $idTarea) {
        // Elimina todos los usuarios del proyecto
        $stmt = $this->pdo->prepare("DELETE FROM usuarios_tareas "
                . "WHERE id_tareaTAsoc=?");

        $stmt->execute([$idTarea]);

        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_tareas "
                    . "(id_usuarioTAsoc, id_tareaTAsoc) "
                    . "VALUES(?, ?)");

            $stmt->execute([$idUsuario, $idTarea]);
        }
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tareas");
        return $stmt->fetchColumn();
    }

    public function deleteTarea(int $idTarea): bool {
        $valorDevuelto = false;

        if (!is_null($this->buscarTareaPorId($idTarea))) {
            $stmt = $this->pdo->prepare("DELETE FROM tareas WHERE id_tarea = ?");
            $stmt->execute([$idTarea]);
            $modeloFiles = new \Com\TaskVelocity\Models\FilesModel();
            if (!$modeloFiles->buscarImagen("tareas", "tarea", $idTarea) || $modeloFiles->eliminarImagen("tareas", "tarea", $idTarea)) {
                $modeloLog = new \Com\TaskVelocity\Models\LogModel();
                $modeloLog->crearLog("Eliminada la tarea con el id $idTarea", $_SESSION["usuario"]["id_usuario"]);
                $valorDevuelto = true;
            } else {
                $valorDevuelto = false;
            }
        }

        return $valorDevuelto;
    }
}
