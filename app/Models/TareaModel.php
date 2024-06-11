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
     * @param int $numeroPagina parametro opcional para la paginación (por defecto es 0)
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
     * Recoge las tareas de los que el usuario es propietario
     * @param int $idUsuario el id del usuario
     * @return array Devuelve las tareas de los que el usuario es propietario
     */
    public function mostrarTareasPorIdUsuario(int $idUsuario): array {
        $stmt = $this->pdo->prepare("SELECT id_tarea FROM tareas ta WHERE ta.id_usuario_tarea_prop = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
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

    /**
     * Recoge los idUsuario y idTarea de las tareas
     * @param array $tareas las tareas
     * @return array Devuelve las tareas con los idUsuario y idTarea
     */
    private function recogerIdsUsuariosTarea(array $tareas): array {
        $tareasDefinitivas = [];
        foreach ($tareas as $tarea) {
            for ($j = 0; $j < $tarea["COUNT(id_usuarioTAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas JOIN usuarios"
                        . " ON usuarios_tareas.id_usuarioTAsoc = usuarios.id_usuario"
                        . " WHERE id_tareaTAsoc =" . $tarea["id_tareaTAsoc"]);

                $usuariosTareas = $stmt->fetchAll();

                $tarea["nombresUsuarios"] = $this->mostrarUsernamesTarea($usuariosTareas);
            }
            $tareasDefinitivas[] = $tarea;
        }
        return $tareasDefinitivas;
    }

    /**
     * Añade los nombres de los usuarios en la tarea recibida
     * @param array $tareaIdUsuarioTarea el array de la tarea con los idUsuario y idTarea
     * @return array Devuelve un array con los nombres de los usuarios del proyecto
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
                // Añade la tarea al array del nombre_proyecto y esté se añade a su vez en un array llamado tareasAgrupadasPorProyecto
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
            $tarea = $this->buscarTareaPorNombre($idTarea);

            if (!is_null($tarea)) {
                $stmt = $this->pdo->prepare("UPDATE tareas SET id_proyecto = ? WHERE id_tarea = ?");
                $stmt->execute([$idProyecto, $idTarea]);
            } else {
                // Si la tarea no existe se crea
                $this->addTarea((string) $idTarea, null, (string) $_SESSION["usuario"]["id_color_favorito"], (string) $idProyecto, null, "", (string) 1);
            }

            // Vuelve a buscar la tarea para tener su id
            $tarea = $this->buscarTareaPorNombre($idTarea);

            $stmt = $this->pdo->prepare("SELECT id_usuarioPAsoc FROM usuarios_proyectos up "
                    . "JOIN proyectos pr ON up.id_proyectoPAsoc = pr.id_proyecto WHERE id_proyecto = ?");
            $stmt->execute([$idProyecto]);

            $idUsuarios = $stmt->fetchAll();

            // Borra los usuario para volverlos a añadir
            $stmt = $this->pdo->prepare("DELETE FROM usuarios_tareas WHERE id_tareaTAsoc = ?");
            $stmt->execute([$tarea["id_tarea"]]);

            foreach ($idUsuarios as $idUsuario) {
                $this->addUsuarioTarea((int) $idUsuario["id_usuarioPAsoc"], (int) $tarea["id_tarea"]);
            }
        }
    }

    /**
     * Muestra las tareas de las cuál el usuario es propietario (se utiliza en administracion)
     * @param type $idUsuario el id del usuario
     * @param type $numeroPagina el número de la pagina
     * @return array Devuelve las tareas de las que el id del usuario es propietario
     */
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

    /**
     * Comprueba si el usuario logeado es propietario de la tarea
     * @param int $idTarea el id de la tarea
     * @return bool Devuelve true si es propietario y si no
     */
    public function esPropietario(int $idTarea): bool {
        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $tareaEncontrada = $modeloTarea->buscarTareaPorId($idTarea);

        return ($tareaEncontrada["id_usuario_tarea_prop"] == $_SESSION["usuario"]["id_usuario"]) ? true : false;
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
     * Busca la información de una tarea específica por el id pasado como parámetro
     * @param int $idTarea el id de la tarea
     * @return array|null Devuelve la información de la tarea si la encuentra o null
     */
    public function buscarTareaPorNombre(string $idTarea): ?array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE nombre_tarea = ?");
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
     * Muestra las tareas asociadas a un proyecto por el id del proyecto
     * @param int $idProyecto el id del proyecto
     * @return array Retorna un array con las tareas del proyecto asociado
     */
    public function mostrarTareasPorProyecto(int $idProyecto): array {
        $stmt = $this->pdo->prepare("SELECT * FROM tareas ta JOIN proyectos p "
                . "ON ta.id_proyecto = p.id_proyecto "
                . "LEFT JOIN etiquetas et ON ta.id_etiqueta LIKE et.id_etiqueta "
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

    /**
     * Elimina los usuarios de las tareas para volverlos a añadir
     * @param array $idUsuarios los ids de los usuarios
     * @param int $idTarea el id de la tarea
     */
    private function editarUsuariosTareas(array $idUsuarios, int $idTarea) {
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios_tareas WHERE id_tareaTAsoc=? AND id_usuarioTAsoc=?");
            $stmt->execute([$idTarea, $idUsuario]);
            $this->addUsuarioTarea((int) $idUsuario, $idTarea);
        }
    }

    /**
     * Devuelve cuántas tareas hay en la base de datos (se utiliza en administración)
     * @return int Devuelve cuántas tareas hay
     */
    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tareas");
        return $stmt->fetchColumn();
    }

    /**
     * Muestra cuántas tareas tiene asignado el usuario
     * @param int $idUsuario el id del usuario
     * @return int Devuelve el número de tareas
     */
    public function contadorPorUsuario(int $idUsuario): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tareas ta LEFT JOIN usuarios_tareas ut "
                . "ON ta.id_tarea=ut.id_tareaTAsoc WHERE ut.id_usuarioTAsoc = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    /**
     * Muestra cuántas tareas es propietario el usuario
     * @param int $idUsuario el id del usuario
     * @return int Devuelve el número de tareas
     */
    public function contadorPorUsuarioPropietario(int $idUsuario): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tareas ta WHERE ta.id_usuario_tarea_prop = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    /**
     * Muestra las tareas que hay por etiqueta del usuario
     * @param int $idEtiqueta el id de la etiqueta
     * @param int $idUsuario el id del usuario
     * @return array Devuelve las tareas
     */
    public function contadorTareasPorEtiqueta(int $idEtiqueta, int $idUsuario): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "LEFT JOIN etiquetas as et "
                . "ON ta.id_etiqueta=et.id_etiqueta WHERE ta.id_etiqueta=? AND (us.id_usuario = ? OR ut.id_usuarioTAsoc = ?) "
                . "GROUP BY ut.id_tareaTAsoc");

        $stmt->execute([$idEtiqueta, $idUsuario, $idUsuario]);

        return $stmt->fetchAll();
    }

    /**
     * Borra la tarea y la imagen asociada (si la hay) del almacenamiento y de la base de datos
     * @param int $idTarea el id de la tarea
     * @return bool Devuelve true si elimino la tarea o false si no
     */
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
