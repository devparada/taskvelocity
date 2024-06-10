<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class TareaModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Consulta base para los métodos que recogen datos
     */
    private const baseConsulta = "SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p "
            . "ON ta.id_proyecto = p.id_proyecto LEFT JOIN usuarios us "
            . "ON ta.id_usuario_tarea_prop = us.id_usuario LEFT JOIN colores c "
            . "ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
            . "ON ta.id_tarea = ut.id_tareaTAsoc ";

    /**
     * Recoge el valor del id de rol de admin de UsuarioController
     */
    private const ROL_ADMIN_USUARIOS = \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN;

    /**
     * Muestra las tareas según seas administrador o usuario
     * @param int $numero pagina parametro opcional para la paginación (por defecto es 0)
     * @return array Devuelve un array con las tareas
     */
    public function mostrarTareas(int $numeroPagina = 0): array {
        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            $stmt = $this->pdo->query(self::baseConsulta . "GROUP BY ut.id_tareaTAsoc "
                    . "LIMIT " . $numeroPagina * $_ENV["tabla.filasPagina"] . "," . $_ENV["tabla.filasPagina"]);
        } else {
            $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas et ON ta.id_etiqueta = et.id_etiqueta "
                    . "WHERE us.id_usuario = ? OR ut.id_usuarioTAsoc = ? GROUP BY ut.id_tareaTAsoc "
                    . "ORDER BY ta.id_etiqueta ASC, ta.nombre_tarea DESC");

            $stmt->execute([$_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);
        }

        $tareas = $stmt->fetchAll();

        $tareasConUsuarios = $this->recogerIdsUsuariosTarea($tareas);

        if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
            return $tareasConUsuarios;
        } else {
            $tareasAgrupadasProyecto = $this->agruparTareaProyecto($tareasConUsuarios);
            return $tareasAgrupadasProyecto;
        }
    }

    /**
     * Obtiene las tareas que no están en el mismo proyecto
     * @param int $idProyecto el id del proyecto
     * @return array Devuelve el array con las tareas
     */
    public function mostrarTareasAddProyecto(int $idProyecto): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE ta.id_proyecto != ? AND (us.id_usuario = ? OR ut.id_usuarioTAsoc = ?) "
                . "GROUP BY ut.id_tareaTAsoc "
                . "ORDER BY ta.id_etiqueta ASC, ta.nombre_tarea DESC");

        $stmt->execute([$idProyecto, $_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);
        $tareas = $stmt->fetchAll();
        return $tareas;
    }

    private function recogerIdsUsuariosTarea(array $tarea) {
        for ($i = 0; $i < count($tarea); $i++) {
            for ($j = 0; $j < $tarea[$i]["COUNT(id_usuarioTAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas JOIN usuarios"
                        . " ON usuarios_tareas.id_usuarioTAsoc = usuarios.id_usuario"
                        . " WHERE id_tareaTAsoc =" . $tarea[$i]["id_tareaTAsoc"]);

                $usuariosTareas = $stmt->fetchAll();

                $tarea[$i]["nombresUsuarios"] = $this->mostrarUsernamesTarea($usuariosTareas);
            }
        }
        return $tarea;
    }

    /**
     * Añade los nombres de los usuarios en la tarea recibida
     * @param array $tareaIdUsuarioTarea el array de la tarea con los idUsuario y idTarea
     * @return array
     */
    private function mostrarUsernamesTarea(array $tareaIdUsuarioTarea): array {
        $usuarios = [];

        foreach ($tareaIdUsuarioTarea as $tareaUsuario) {
            $usuarios[] = $tareaUsuario["username"];
        }

        return $usuarios;
    }

    /**
     * Agrupa las tareas por proyecto
     * @param array $tareas las tareas
     * @return array Devuelve un array con arrays de cada proyecto y dentro las tareas
     */
    private function agruparTareaProyecto(array $tareas): array {
        $tareasAgrupadasPorProyecto = [];

        if (!empty($tareas)) {
            foreach ($tareas as $tarea) {
                $nombreProyecto = $tarea["nombre_proyecto"];
                // Añade la tarea al array del nombre_proyecto y esté se añade a su vez en un array llamado tareasGrupo
                $tareasAgrupadasPorProyecto[$nombreProyecto][] = $tarea;
            }
        }

        return $tareasAgrupadasPorProyecto;
    }

    /**
     * Añade tareas al proyecto
     * @param string $idTareas los valores del select2 (pueden ser los ids o un stirng que se utiliza como nombre de la tarea)
     * @param string $idProyecto el id del proyecto
     * @return void
     */
    public function addTareasProyecto(array $idTareas, int $idProyecto): void {
        foreach ($idTareas as $idTarea) {
            $tarea = $this->buscarTareaPorId((int) $idTarea);

            if (!is_null($tarea)) {
                $stmt = $this->pdo->prepare("UPDATE tareas SET id_proyecto = ? WHERE id_tarea = ?");
                $stmt->execute([$idProyecto, $idTarea]);
            } else {
                // Si la tarea no existe se crea
                $this->addTarea((string) $idTarea, null, (string) $_SESSION["usuario"]["id_color_favorito"], (string) $idProyecto, null, "", (string) 1);
            }
        }
    }

    public function filtrarPorPropietario($idUsuario, $numeroPagina): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE ta.id_usuario_tarea_prop = ? GROUP BY ut.id_tareaTAsoc"
                . " LIMIT " . $numeroPagina * $_ENV["tabla.filasPagina"] . "," . $_ENV["tabla.filasPagina"]);
        $stmt->execute([$idUsuario]);

        $tareas = $stmt->fetchAll();
        $tareasConUsuarios = $this->recogerIdsUsuariosTarea($tareas);

        return $tareasConUsuarios;
    }

    /**
     * Obtiene el número de páginas contando todas las tareas
     * @return float Retorna un floor por el ceil (redondea hacia arriba)
     */
    public function obtenerPaginas(): float {
        $numeroPaginas = ceil($this->contador() / $_ENV["tabla.filasPagina"]);
        return $numeroPaginas;
    }

    public function esPropietario(int $idTarea): bool {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas t "
                . "WHERE t.id_tarea = ? AND t.id_usuario_tarea_prop = ?");
        $stmt->execute([$idTarea, $_SESSION["usuario"]["id_usuario"]]);

        return !is_null($stmt->fetch());
    }

    /**
     * Muestra las tareas filtradas por etiquetas
     * @param string $idEtiqueta el id de la etiqueta
     * @return array Retorna un array con las tareas filtradas
     */
    public function mostrarTareasPorEtiqueta(string $idEtiqueta): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas as et "
                . "ON ta.id_etiqueta=et.id_etiqueta "
                . "WHERE ta.id_etiqueta=? AND (us.id_usuario = ? OR ut.id_usuarioTAsoc = ?) "
                . "GROUP BY ut.id_tareaTAsoc");

        $stmt->execute([$idEtiqueta, $_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);

        $tareas = $stmt->fetchAll();
        $tareasIdsUsuariosTareas = $this->recogerIdsUsuariosTarea($tareas);
        $tareasPorProyecto = $this->agruparTareaProyecto($tareasIdsUsuariosTareas);

        return $tareasPorProyecto;
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
     * @return array|null Retorna un array con los usuarios de la tarea asociada o null
     */
    public function procesarUsuariosPorTarea(int $idTarea): ?array {
        $tarea = $this->buscarTareaPorId($idTarea);

        if (!is_null($tarea)) {
            $stmt = $this->pdo->prepare("SELECT id_usuario,username FROM usuarios u JOIN usuarios_tareas ut "
                    . "ON u.id_usuario = ut.id_usuarioTAsoc "
                    . "WHERE ut.id_tareaTAsoc  = ? AND ut.id_usuarioTAsoc != ? AND ut.id_usuarioTAsoc != ?");
            $stmt->execute([$idTarea, $_SESSION["usuario"]["id_usuario"], $tarea["id_usuario_tarea_prop"]]);
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    /**
     * Muestra las tareas asociadas a un proyecto por el id del proyecto
     * @param int $idProyecto el id del proyecto
     * @return array Retorna un array con las tareas del proyecto asociado
     */
    public function mostrarTareasPorProyecto(int $idProyecto): array {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas ta JOIN proyectos p "
                . "ON ta.id_proyecto=p.id_proyecto "
                . "LEFT JOIN etiquetas et ON ta.id_etiqueta=et.id_etiqueta "
                . "WHERE ta.id_proyecto = ? ORDER BY ta.id_etiqueta ASC");
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
            $tarea = $this->buscarTareaPorId($idTarea);
            $idUsuarioTareaProp = (int) $tarea["id_usuario_tarea_prop"];

            if (!empty($idUsuariosAsociados)) {
                array_push($idUsuariosAsociados, $idUsuarioTareaProp);
                $this->editarUsuariosTareas($idUsuariosAsociados, $idTarea);
            } else {
                $this->editarUsuariosTareas([$idUsuarioTareaProp], $idTarea);
            }

            $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas WHERE id_tareaTAsoc=" . $idTarea);

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
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios_tareas WHERE id_tareaTAsoc=? AND id_usuarioTAsoc=?");
            $stmt->execute([$idTarea, $idUsuario]);
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

    public function contadorTareasPorEtiqueta(int $idEtiqueta, int $idUsuario): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas as et "
                . "ON ta.id_etiqueta=et.id_etiqueta WHERE ta.id_etiqueta=? AND (us.id_usuario = ? OR ut.id_usuarioTAsoc = ?) "
                . "GROUP BY ut.id_tareaTAsoc");

        $stmt->execute([$idEtiqueta, $idUsuario, $idUsuario]);

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
