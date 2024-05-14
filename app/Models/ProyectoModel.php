<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class ProyectoModel extends \Com\TaskVelocity\Core\BaseModel {

    public function mostrarProyectos(): array {
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $stmt = $this->pdo->query("SELECT *, COUNT(id_usuarioPAsoc) FROM proyectos pr LEFT JOIN usuarios us "
                    . "ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up "
                    . "ON pr.id_proyecto = up.id_proyectoPAsoc GROUP BY up.id_proyectoPAsoc "
                    . "ORDER BY pr.id_proyecto desc");
        } else {
            $stmt = $this->pdo->prepare("SELECT *, COUNT(id_usuarioPAsoc) FROM proyectos pr LEFT JOIN usuarios us "
                    . "ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up "
                    . "ON pr.id_proyecto = up.id_proyectoPAsoc "
                    . "WHERE id_usuario_proyecto_prop = ? "
                    . "OR up.id_usuarioPAsoc = ? GROUP BY up.id_proyectoPAsoc "
                    . "ORDER BY pr.id_proyecto desc");
            $stmt->execute([$_SESSION["usuario"]["id_usuario"], $_SESSION["usuario"]["id_usuario"]]);
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
            $usuarios[$index] = $usuariosProyectos[$index]["username"];
        }

        return $usuarios;
    }

    /**
     * Muestra los usuarios asociados a un proyecto por el id del proyecto
     * @param int $idProyecto el id del proyecto
     * @return array Retorna un array con los usuarios del proyecto asociado
     */
    public function mostrarUsuariosPorProyecto(int $idProyecto): array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios u JOIN usuarios_proyectos up ON u.id_usuario =up.id_usuarioPAsoc "
                . "WHERE up.id_proyectoPAsoc  = ?");
        $stmt->execute([$idProyecto]);
        return $stmt->fetchAll();
    }

    /**
     * Busca el proyecto a partir de su id y devuelve los datos del proyecto
     * @param int $idProyecto el id del proyecto
     * @return array|null los datos del proyecto si lo encontra o null si no lo encuentra
     */
    public function buscarProyectoPorId(int $idProyecto): ?array {
        $stmt = $this->pdo->prepare("SELECT *, COUNT(id_usuarioPAsoc) FROM proyectos pr LEFT JOIN usuarios us"
                . " ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up"
                . " ON pr.id_proyecto = up.id_proyectoPAsoc WHERE id_proyecto = ? GROUP BY up.id_proyectoPAsoc");
        $stmt->execute([$idProyecto]);

        $proyectoEncontrado = $stmt->fetch();

        if (!empty($proyectoEncontrado)) {
            for ($j = 0; $j < $proyectoEncontrado["COUNT(id_usuarioPAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_proyectos JOIN usuarios"
                        . " ON usuarios_proyectos.id_usuarioPAsoc = usuarios.id_usuario"
                        . " WHERE id_proyectoPAsoc =" . $proyectoEncontrado["id_proyectoPAsoc"]);

                $usuariosProyectos = $stmt->fetchAll();

                $proyectoEncontrado["nombresUsuarios"] = $this->mostrarUsernameProyecto($usuariosProyectos);
            }
        }

        return ($proyectoEncontrado) ? $proyectoEncontrado : null;
    }

    public function addProyecto(string $nombreProyecto, ?string $descripcionProyecto, ?string $fechaLimiteProyecto, ?array $idUsuariosAsociados): bool {
        $stmt = $this->pdo->prepare("INSERT INTO proyectos "
                . "(nombre_proyecto, descripcion_proyecto, fecha_limite_proyecto, id_usuario_proyecto_prop) "
                . "VALUES(?, ?, ?, ?)");

        // Si la fecha límite del proyecto no se especifica se cambia por null
        if ($fechaLimiteProyecto == "") {
            $fechaLimiteProyecto = null;
        }

        if ($stmt->execute([$nombreProyecto, $descripcionProyecto, $fechaLimiteProyecto, $_SESSION["usuario"]["id_usuario"]])) {
            // Se consigue el id del proyecto debido a que es la última tarea insertada
            $idProyecto = $this->pdo->lastInsertId();
            $this->añadirPropietario($_SESSION["usuario"]["id_usuario"], (int) $idProyecto);
            if (!empty($idUsuariosAsociados)) {
                $this->addProyectoUsuarios($idUsuariosAsociados, $idProyecto);
            }

            if (!empty($_FILES["imagen_proyecto"]["name"])) {
                $modeloFiles = new \Com\TaskVelocity\Models\FilesModel();
                $modeloFiles->guardarImagen("proyectos", "proyecto", (int) $idProyecto);
            }

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Creado el proyecto con el id $idProyecto", $_SESSION["usuario"]["id_usuario"]);
            return true;
        }
        return false;
    }

    /**
     * Crea el proyecto personal cuando el usuario se registra creando un proyecto no editable
     * @param int $idUsuario el id del usuario
     * @param string $username el nombre de usuario
     * @return int Retorna el id del proyecto personal creado
     */
    public function crearProyectoPersonal(int $idUsuario, string $username): int {
        $stmt = $this->pdo->prepare("INSERT INTO proyectos "
                . "(nombre_proyecto, descripcion_proyecto, fecha_limite_proyecto, id_usuario_proyecto_prop, editable) "
                . "VALUES(?, ?, ?, ?, ?)");
        $stmt->execute(["Personal $username", "Personal $username", null, $idUsuario, 0]);

        $idProyectoPersonal = $this->pdo->lastInsertId();
        $this->añadirPropietario((int) $idUsuario, (int) $idProyectoPersonal);

        $modeloLog = new \Com\TaskVelocity\Models\LogModel();
        $modeloLog->crearLog("Creado el proyecto personal con el id $idProyectoPersonal", (int) $idUsuario);
        return (int) $idProyectoPersonal;
    }

    private function addProyectoUsuarios(array $idUsuarios, string $idProyecto): void {
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_proyectos "
                    . "(id_usuarioPAsoc, id_proyectoPAsoc) "
                    . "VALUES(?, ?)");

            $stmt->execute([$idUsuario, $idProyecto]);

            $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
            // $tareasProyecto = $modeloTarea->mostrarTareasPorProyecto($idProyecto);
        }
    }

    private function añadirPropietario(int $idUsuario, int $idProyecto): void {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios_proyectos "
                . "(id_usuarioPAsoc, id_proyectoPAsoc) "
                . "VALUES(?, ?)");

        $stmt->execute([$idUsuario, $idProyecto]);
    }

    /**
     * Edita el proyecto en la base de datos a partir de los datos pasados
     * @param string $nombreProyecto el nombre del proyecto
     * @param string|null $fechaLimiteProyecto la fecha limite del proyecto
     * @param array|null $idUsuariosAsociados los ids de los usuarios
     * @param string|null $descripcionProyecto la descripcion del proyecto
     * @param int $idProyecto es el id del proyecto
     * @return bool Retorna true si se edito correctamente el proyecto o false si no
     */
    public function editProyecto(string $nombreProyecto, ?string $fechaLimiteProyecto, ?array $idUsuariosAsociados, ?string $descripcionProyecto, int $idProyecto): bool {
        $stmt = $this->pdo->prepare("UPDATE proyectos "
                . "SET nombre_proyecto=?, descripcion_proyecto=?, fecha_limite_proyecto=? "
                . "WHERE id_proyecto=?");

        if ($stmt->execute([$nombreProyecto, $descripcionProyecto, $fechaLimiteProyecto, $idProyecto])) {
            // Se consigue el id del proyecto debido a que es la última tarea insertada
            if (!empty($idUsuariosAsociados)) {
                $this->editarUsuariosProyectos($idUsuariosAsociados, $idProyecto);
            }

            if (!empty($_FILES["imagen_proyecto"]["name"])) {
                $modeloFiles = new \Com\TaskVelocity\Models\FilesModel();
                $modeloFiles->actualizarImagen("proyectos", "proyecto", (int) $idProyecto);
            }

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Editado el proyecto con el id $idProyecto", $_SESSION["usuario"]["id_usuario"]);
            return true;
        }
        return false;
    }

    private function editarUsuariosProyectos(array $idUsuarios, int $idProyecto) {
        // Elimina todos los usuarios del proyecto
        $stmt = $this->pdo->prepare("DELETE FROM usuarios_proyectos "
                . "WHERE id_proyectoPAsoc=?");

        $stmt->execute([$idProyecto]);

        $stmt = $this->pdo->prepare("SELECT * FROM proyectos "
                . "WHERE id_proyecto=?");

        $stmt->execute([$idProyecto]);

        $idUsuarioProp = $stmt->fetch()["id_usuario_proyecto_prop"];
        $this->añadirPropietario($idUsuarioProp, $idProyecto);

        $this->addProyectoUsuarios($idUsuarios, $idProyecto);
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM proyectos");
        return $stmt->fetchColumn();
    }

    public function contadorPorUsuario(int $idUsuario): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM proyectos pr LEFT JOIN usuarios_proyectos up"
                . " ON pr.id_proyecto = up.id_proyectoPAsoc LEFT JOIN usuarios u"
                . " ON up.id_usuarioPAsoc = u.id_usuario WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchColumn();
    }

    /**
     * Borra el proyecto de la base de datos si no es nulo y es editable
     * @param int $idProyecto el id del proyecto a borrar
     * @return bool Retorna true si consiga borrar el poryecto si no false
     */
    public function deleteProyecto(int $idProyecto): bool {
        $valorDevuelto = false;

        if (!is_null($this->buscarProyectoPorId($idProyecto)) && $this->buscarProyectoPorId($idProyecto)["editable"] == 1) {
            $stmt = $this->pdo->prepare("DELETE FROM proyectos WHERE id_proyecto = ?");
            $stmt->execute([$idProyecto]);
            $modeloFiles = new \Com\TaskVelocity\Models\FilesModel();
            if (!$modeloFiles->buscarImagen("proyectos", "proyecto", $idProyecto) || $modeloFiles->eliminarImagen("proyectos", "proyecto", $idProyecto)) {
                $modeloLog = new \Com\TaskVelocity\Models\LogModel();
                $modeloLog->crearLog("Eliminado el proyecto con el id $idProyecto", $_SESSION["usuario"]["id_usuario"]);
                $valorDevuelto = true;
            } else {
                $valorDevuelto = false;
            }
        }

        return $valorDevuelto;
    }
}
