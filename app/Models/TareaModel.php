<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class TareaModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Consulta base para los métodos que recogen datos
     */
    private const baseConsulta = "SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p "
            . "ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
            . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c "
            . "ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
            . "ON ta.id_tarea=ut.id_tareaTAsoc ";

    /**
     * Recoge el valor del id de rol de admin de UsuarioController
     */
    private const ROL_ADMIN_USUARIOS = \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN;

    public function mostrarTareas(): array {
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $stmt = $this->pdo->query(self::baseConsulta . "GROUP BY ut.id_tareaTAsoc");
        } else {
            $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas et ON ta.id_etiqueta = et.id_etiqueta "
                    . "WHERE us.id_usuario = ? OR ut.id_usuarioTAsoc = ? GROUP BY ut.id_tareaTAsoc "
                    . "ORDER BY ta.id_etiqueta, fecha_limite_tarea ASC");

            $stmt->execute([$_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);
        }

        $tareas = $stmt->fetchAll();

        $tareasUsuarios = $this->recogerNombresUsuarios($tareas);

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            return $tareasUsuarios;
        } else {
            $tareasProyecto = $this->agruparTareaProyecto($tareasUsuarios);

            return $tareasProyecto;
        }
    }

    private function recogerNombresUsuarios(array $datos) {
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

    /**
     * Agrupa las tareas por proyecto
     * @param array $tareas las tareas
     * @return array Devuelve un array con arrays de cada proyecto y dentro las tareas
     */
    private function agruparTareaProyecto(array $tareas) {
        $tareasGrupo = [];

        foreach ($tareas as $tarea) {
            $nombreProyecto = $tarea["nombre_proyecto"];
            $tareasGrupo[$nombreProyecto][] = $tarea;
        }

        return $tareasGrupo;
    }

    public function addTareasProyecto($idTareas, $idProyecto): bool {
        foreach ($idTareas as $idTarea) {
            $stmt = $this->pdo->prepare("UPDATE tareas SET id_proyecto = ? WHERE id_tarea = ?");
            $stmt->execute([$idProyecto, $idTarea]);
            return true;
        }
        return false;
    }

    public function esPropietario(int $idTarea): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas t WHERE t.id_tarea = ? AND t.id_usuario_tarea_prop = ?");
        $stmt->execute([$idTarea, $_SESSION["usuario"]["id_usuario"]]);

        return (!is_null($stmt->fetch()));
    }

    public function mostrarTareasPorEtiqueta(string $idEtiqueta): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas as et "
                . "ON ta.id_etiqueta=et.id_etiqueta WHERE ta.id_etiqueta=? AND (us.id_usuario = ? OR ut.id_usuarioTAsoc = ?) GROUP BY ut.id_tareaTAsoc");

        $stmt->execute([$idEtiqueta, $_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);

        $datos = $stmt->fetchAll();

        $datosFinal = $this->recogerNombresUsuarios($datos);

        $grupos = $this->agruparTareaProyecto($datosFinal);

        return $grupos;
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
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE id_tarea = ?");
        $stmt->execute([$idTarea]);

        $tareaEncontrada = $stmt->fetch();

        if ($tareaEncontrada["id_tarea"] != null && $tareaEncontrada["id_tareaTAsoc"] != null) {
            $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas JOIN usuarios"
                    . " ON usuarios_tareas.id_usuarioTAsoc = usuarios.id_usuario"
                    . " WHERE id_tareaTAsoc =" . $tareaEncontrada["id_tareaTAsoc"]);

            $usuariosProyectos = $stmt->fetchAll();

            $tareaEncontrada["nombresUsuarios"] = $this->mostrarUsernamesTarea($usuariosProyectos);
        } else {
            $tareaEncontrada = null;
        }

        return $tareaEncontrada;
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
                . "LEFT JOIN etiquetas et ON ta.id_etiqueta=et.id_etiqueta WHERE ta.id_proyecto = ? ORDER BY ta.id_etiqueta ASC");
        $stmt->execute([$idProyecto]);
        return $stmt->fetchAll();
    }

    /**
     * Añade la tarea a la base de datos
     * @param string $nombreTarea el nombre de la tarea
     * @param string|null $fechaLimite la fecha límite de la tarea
     * @param string $idColorTarea el color de la tarea
     * @param string $idProyecto el id del proyecto
     * @param array|null $idUsuariosAsociados los usuarios asociados
     * @param string $descripcionTarea la descripción de la tarea
     * @param string $idEtiqueta la descripcion de la tarea
     * @return bool Devuelve true si añade la tarea o false si no
     */
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
            $this->addUsuarioTarea((int) $_SESSION["usuario"]["id_usuario"], (int) $idTarea);
            if (!empty($idUsuariosAsociados)) {
                foreach ($idUsuariosAsociados as $idUsuario) {
                    $this->addUsuarioTarea((int) $idUsuario, (int) $idTarea);
                }
            }

            if (!empty($_FILES["imagen_tarea"]["name"])) {
                $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
                $modeloFiles->guardarImagen("tareas", "tarea", (int) $idTarea);
            }

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Creada la tarea con el id $idTarea", $_SESSION["usuario"]["id_usuario"]);
            return true;
        }
        return false;
    }

    /**
     * Añade un usuario a la tarea
     * @param int $idUsuario el id del usuario
     * @param int $idTarea el id de la tarea
     * @return void
     */
    public function addUsuarioTarea(int $idUsuario, int $idTarea): void {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios_tareas "
                . "(id_usuarioTAsoc, id_tareaTAsoc) "
                . "VALUES(?, ?)");
        $stmt->execute([$idUsuario, $idTarea]);
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

            $stmt = $this->pdo->prepare("SELECT * FROM tareas WHERE id_tarea=?");
            $stmt->execute([$idTarea]);

            $idUsuarioTareaProp = (int) $stmt->fetch()["id_usuario_tarea_prop"];

            if (!empty($idUsuariosAsociados)) {
                array_push($idUsuariosAsociados, $idUsuarioTareaProp);
                $this->editarUsuariosTareas($idUsuariosAsociados, $idTarea);
            } else {
                $this->editarUsuariosTareas([$idUsuarioTareaProp], $idTarea);
            }

            if (!empty($_FILES["imagen_tarea"]["name"])) {
                $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
                $modeloFiles->actualizarImagen("tareas", "tarea", $idTarea);
            }

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Editada la tarea con el id $idTarea", $_SESSION["usuario"]["id_usuario"]);
            return true;
        }
        return false;
    }

    private function editarUsuariosTareas(array $idUsuarios, int $idTarea) {
        // Elimina todos los usuarios de la tarea
        $stmt = $this->pdo->prepare("DELETE FROM usuarios_tareas WHERE id_tareaTAsoc=?");
        $stmt->execute([$idTarea]);

        // Selecciona la tarea
        // $stmt = $this->pdo->prepare("SELECT * FROM tareas WHERE id_tarea=?");
        // $stmt->execute([$idTarea]);
        //$idUsuarioProp = $stmt->fetch()["id_usuario_tarea_prop"];
        //$this->addUsuarioTarea($idUsuarioProp, $idTarea);

        foreach ($idUsuarios as $idUsuario) {
            $this->addUsuarioTarea((int) $idUsuario, $idTarea);
        }
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tareas");
        return $stmt->fetchColumn();
    }

    public function contadorPorUsuario(int $idUsuario): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tareas ta LEFT JOIN usuarios_tareas ut "
                . "ON ta.id_tarea=ut.id_tareaTAsoc WHERE ut.id_usuarioTAsoc = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    public function contadorPorUsuarioPropietario(int $idUsuario): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tareas ta WHERE ta.id_usuario_tarea_prop = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    public function contadorTareasPorEtiqueta(string $idEtiqueta): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas as et "
                . "ON ta.id_etiqueta=et.id_etiqueta WHERE ta.id_etiqueta=? AND (us.id_usuario = ? OR ut.id_usuarioTAsoc = ?) GROUP BY ut.id_tareaTAsoc");

        $stmt->execute([$idEtiqueta, $_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);

        return $stmt->fetchAll();
    }

    public function deleteTarea(int $idTarea): bool {
        $valorDevuelto = false;

        if (!is_null($this->buscarTareaPorId($idTarea))) {
            $stmt = $this->pdo->prepare("DELETE FROM tareas WHERE id_tarea = ?");
            $stmt->execute([$idTarea]);
            $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
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
