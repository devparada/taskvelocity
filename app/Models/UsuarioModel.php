<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class UsuarioModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Consulta base para algunos métodos de esta clase
     */
    private const baseConsulta = "SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color ";

    /**
     * El id del usuario admin
     */
    private const ID_USUARIO_ADMIN = 1;

    /**
     * El rol admin
     */
    private const ROL_ADMIN_USUARIOS = \Com\TaskVelocity\Controllers\UsuarioController::ROL_ADMIN;

    /**
     * Muestra los usuarios por paginación del numeroPagina
     * @param int $numeroPagina el número de la página
     * @return array Devuelve los usuarios
     */
    public function mostrarUsuarios(int $numeroPagina = 0): array {
        $stmt = $this->pdo->query(self::baseConsulta . " ORDER BY id_usuario DESC LIMIT " . $numeroPagina * $_ENV["tabla.filasPagina"] . "," . $_ENV["tabla.filasPagina"]);
        return $stmt->fetchAll();
    }

    /**
     * Muestra los usuarios que tienen logs
     * @return array Devuelve los usuarios que tienen logs
     */
    public function mostrarUsuariosFiltrosLogs(): array {
        $stmt = $this->pdo->query(self::baseConsulta . " RIGHT JOIN logs as l ON us.id_usuario = l.id_usuario_prop GROUP by us.id_usuario");
        return $stmt->fetchAll();
    }

    /**
     * Muestra las tareas que tienen tareas
     * @return array Devuelve los usuarios que tienen tareas
     */
    public function mostrarUsuariosFiltrosTareas(): array {
        $stmt = $this->pdo->query(self::baseConsulta . " RIGHT JOIN tareas as ta ON us.id_usuario = ta.id_usuario_tarea_prop GROUP by us.id_usuario");
        return $stmt->fetchAll();
    }

    /**
     * Muestra los usuarios que tienen proyectos
     * @return array Devuelve los usuario que tienen proyectos
     */
    public function mostrarUsuariosFiltrosProyectos(): array {
        $stmt = $this->pdo->query(self::baseConsulta . " RIGHT JOIN proyectos as pr ON us.id_usuario = pr.id_usuario_proyecto_prop GROUP by us.id_usuario");
        return $stmt->fetchAll();
    }

    /**
     * Muestra los usuarios según el idRol selecionado
     * @param int $idRol el id rol selecionado
     * @param int $numeroPagina el número de la pagina
     * @return array Devuelve los usuarios del id rol
     */
    public function filtrarPorRol(int $idRol, int $numeroPagina): array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE us.id_rol = ? LIMIT " . $numeroPagina * $_ENV["tabla.filasPagina"] . "," . $_ENV["tabla.filasPagina"]);
        $stmt->execute([$idRol]);
        return $stmt->fetchAll();
    }

    /**
     * Muestra los nombres de los usuarios de forma asincrona cuando se busca en el select2
     * @return array los nombres de los usuarios en un array
     */
    public function buscarUsuariosAsync(): array {
        $resultado = "";

        if (!empty($_GET['usuarios'])) {
            $search = $_GET['usuarios'];
            $stmt = $this->pdo->prepare("SELECT id_usuario, username FROM usuarios us WHERE us.username LIKE :search "
                    . "AND (us.id_rol != 1 AND us.id_usuario !=" . $_SESSION["usuario"]["id_usuario"] . ")");
            $stmt->execute(['search' => "$search%"]);

            $usuarios = [];
            while ($row = $stmt->fetch()) {
                $usuarios[] = [
                    'id' => $row['id_usuario'],
                    'text' => $row['username']];
            }

            $resultado = [
                'results' => $usuarios
            ];
        }

        return $resultado;
    }

    /**
     * Muestra los usuarios seleccionables en los select
     * @return array Devuelve los usuarios seleccionables
     */
    public function mostrarUsuariosFormulario(): array {
        $stmt = $this->pdo->query(self::baseConsulta . "WHERE NOT (us.id_rol = " . self::ROL_ADMIN_USUARIOS . ") "
                . "OR us.id_usuario = " . $_SESSION["usuario"]["id_usuario"]);
        return $stmt->fetchAll();
    }

    public function obtenerPaginas(): float {
        $numeroPaginas = ceil($this->contador() / $_ENV["tabla.filasPagina"]);
        return $numeroPaginas;
    }

    /**
     * Busca un usuario en la base de datos por su id
     * @param int $idUsuario el id del usuario
     * @return array|null Retorna al usuario si existe si no retorna null
     */
    public function buscarUsuarioPorId(int $idUsuario): ?array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);

        $usuarioEncontrado = $stmt->fetch();

        return ($usuarioEncontrado) ? $usuarioEncontrado : null;
    }

    /**
     * Busca un usuario en la base de datos por su username
     * @param string $username el nombre del usuario
     * @return array|null Retorna al usuario si existe si no retorna null
     */
    public function buscarUsuarioPorUsername(string $username): ?array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE username LIKE ?");
        $stmt->execute([$username]);

        $usuarioEncontrado = $stmt->fetch();

        return ($usuarioEncontrado) ? $usuarioEncontrado : null;
    }

    /**
     * Busca un usuario en la base de datos por su email
     * @param string $email el email del usuario
     * @return array|null Retorna al usuario si existe si no retorna null
     */
    public function buscarUsuarioPorEmail(string $email): ?array {
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE email LIKE ?");
        $stmt->execute([$email]);

        $usuarioEncontrado = $stmt->fetch();

        return ($usuarioEncontrado) ? $usuarioEncontrado : null;
    }

    /**
     * Comprueba si los datos introducidos en el login existen en un usuario y hace login en caso afirmativo
     * @param string $emailUsername El email o username introducido
     * @param string $password La contraseña introducida
     * @return bool Retorna true si se hace el login o false si no
     */
    public function procesarLogin(string $emailUsername, string $password): bool {
        if (str_contains($emailUsername, "@")) {
            $usuarioEncontrado = $this->buscarUsuarioPorEmail($emailUsername);
        } else {
            $usuarioEncontrado = $this->buscarUsuarioPorUsername($emailUsername);
        }

        if (!is_null($usuarioEncontrado)) {
            if ($emailUsername == $usuarioEncontrado["email"] || $emailUsername == $usuarioEncontrado["username"] && password_verify($password, $usuarioEncontrado["password"])) {
                $this->actualizarFechaLogin($usuarioEncontrado["id_usuario"]);
                return true;
            }
        }
        return false;
    }

    /**
     * Actualiza la fecha del login cuándo el usuario inicia sesión
     * @param int $idUsuario el id del usuario
     * @return void
     */
    private function actualizarFechaLogin(int $idUsuario): void {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET fecha_login = current_timestamp() WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
    }

    /**
     * Añade el usuario a la base de datos con los datos pasados por $_POST (son strings)
     * @param string $username el nombre de usuario
     * @param string $contrasena la contraseña
     * @param string $email el email
     * @param $idRol el id del rol
     * @param string $fechaNacimiento la fecha de nacimiento
     * @param string $descripcionUsuario la descripción del usuario (opcional)
     * @param string $idColor el id del color
     * @return bool Devuelve true si se añade correctamente o false si no
     */
    public function addUsuario(string $username, string $contrasena, string $email, $idRol, ?string $fechaNacimiento, ?string $descripcionUsuario, string $idColor): bool {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (username, password, email, id_rol, fecha_nacimiento, fecha_login, 
            descripcion_usuario, id_color_favorito, fecha_usuario_creado) VALUES (?, ?, ?, ?, ?, NULL, ?, ?, current_timestamp())");

        if ($fechaNacimiento == "") {
            $fechaNacimiento = null;
        }

        if ($stmt->execute([$username, password_hash($contrasena, '2y'), $email, $idRol, $fechaNacimiento, $descripcionUsuario, $idColor],)) {
            $idUsuario = $this->pdo->lastInsertId();
            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
            $idProyectoPersonal = $modeloProyecto->crearProyectoPersonal((int) $idUsuario, $username);

            $stmt = $this->pdo->prepare("UPDATE usuarios SET id_proyecto_personal = ? WHERE id_usuario = ?");
            $stmt->execute([$idProyectoPersonal, $idUsuario]);

            $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
            $modeloFiles->guardarImagen("usuarios", "avatar", (int) $idUsuario);

            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Creado el usuario con el id $idUsuario", (int) $idUsuario);
            return true;
        }
        return false;
    }

    /**
     * Edita el usuario en la base de datos
     * @param string $username el nombre de usuario
     * @param string|null $contrasena la contraseña
     * @param string $email el email
     * @param string $idRol el id del rol
     * @param string $fechaNacimiento la fecha de nacimiento
     * @param string $descripcionUsuario la descripcion del usuario
     * @param string $idColor el id del color del usuario
     * @param int $idUsuario el id del usuario a modificar
     * @return bool Retorna true si el usuario se edita correctamente
     */
    public function editUsuario(string $username, ?string $contrasena, string $email, string $idRol, string $fechaNacimiento, string $descripcionUsuario, string $idColor, int $idUsuario): bool {

        if (empty($fechaNacimiento)) {
            $fechaNacimiento = null;
        }

        // Si el parámetro contrasena es nulo se actualiza el usuario sin cambiar la contraseña
        if (is_null($contrasena)) {
            $stmt = $this->pdo->prepare("UPDATE usuarios "
                    . "SET username=?, email=?, id_rol=?, fecha_nacimiento=?, descripcion_usuario=?, id_color_favorito=? "
                    . "WHERE id_usuario=?");
            if (!$stmt->execute([$username, $email, $idRol, $fechaNacimiento, $descripcionUsuario, $idColor, $idUsuario])) {
                return false;
            }
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios "
                    . "SET username=?, password=?, email=?, id_rol=?, fecha_nacimiento=?, descripcion_usuario=?, id_color_favorito=? "
                    . "WHERE id_usuario=?");
            if (!$stmt->execute([$username, password_hash($contrasena, '2y'), $email, $idRol, $fechaNacimiento, $descripcionUsuario, $idColor, $idUsuario])) {
                return false;
            }
        }

        if ($_SESSION["usuario"]["id_rol"] == \Com\TaskVelocity\Controllers\UsuarioController::ROL_USUARIO && $_SESSION["usuario"]["username"] != $username) {
            $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
            $proyectoPersonal = $modeloProyecto->buscarProyectoPorId($_SESSION["usuario"]["id_proyecto_personal"]);

            if (strpos($proyectoPersonal["nombre_proyecto"], "Personal") !== false) {
                $stmt = $this->pdo->prepare("UPDATE proyectos SET nombre_proyecto = ? WHERE id_proyecto = ?");
                $stmt->execute(["Personal " . $username, $proyectoPersonal["id_proyecto"]]);
            }
        }

        // Se sobrescribe el usuario con los nuevos datos en la sesión
        $modeloUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $_SESSION["usuario"] = $modeloUsuario->buscarUsuarioPorId($_SESSION["usuario"]["id_usuario"]);

        $modeloLog = new \Com\TaskVelocity\Models\LogModel();
        $modeloLog->crearLog("Editado el usuario con el id $idUsuario", $_SESSION["usuario"]["id_usuario"]);

        if (!empty($_FILES["imagen_avatar"]["name"])) {
            $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
            return $modeloFiles->actualizarImagen("usuarios", "avatar", (int) $idUsuario) ? true : false;
        } else {
            return true;
        }
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios");
        return $stmt->fetchColumn();
    }

    /**
     * Borra el usuario y el avatar de la base de datos
     * @param int $idUsuario el id del usuario
     * @return bool Retorna true si borra el usuario y el avatar y si no false
     */
    public function deleteUsuario(int $idUsuario): bool {
        $modeloProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $modeloTarea = new \Com\TaskVelocity\Models\TareaModel();
        $modeloFile = new \Com\TaskVelocity\Models\FileModel();
        if (!is_null($this->buscarUsuarioPorId($idUsuario)) && $idUsuario != self::ID_USUARIO_ADMIN) {
            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            if ($_SESSION["usuario"]["id_rol"] == self::ROL_ADMIN_USUARIOS) {
                $modeloLog->crearLog("Eliminado el usuario con el id $idUsuario", self::ID_USUARIO_ADMIN);
                $modeloLog->crearLog("Eliminados los proyectos y las tareas del usuario con el id $idUsuario", self::ID_USUARIO_ADMIN);
            } else {
                $modeloLog->crearLog("Eliminado el usuario con el id $idUsuario", $idUsuario);
                $modeloLog->crearLog("Eliminados los proyectos y las tareas del usuario con el id $idUsuario", $idUsuario);
            }

            $proyectosIdEncontrados = $modeloProyecto->mostrarProyectosPorIdUsuario($idUsuario);
            foreach ($proyectosIdEncontrados as $proyectoId) {
                $modeloFile->eliminarImagen("proyectos", "proyecto", $proyectoId["id_proyecto"]);
            }

            $tareasIdEncontrados = $modeloTarea->mostrarTareasPorIdUsuario($idUsuario);
            foreach ($tareasIdEncontrados as $tareasId) {
                $modeloFile->eliminarImagen("tareas", "tarea", $tareasId["id_tarea"]);
            }

            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$idUsuario]);
            $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
            if ($modeloFiles->eliminarImagen("usuarios", "avatar", $idUsuario)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Comprueba si los idUusarios son números
     * @param array $idUsuarios los ids de los usuarios
     * @return bool Devuelve true si todos son números o si no false
     */
    public function comprobarUsuariosNumero(array $idUsuarios): bool {
        foreach ($idUsuarios as $idUsuario) {
            if (!filter_var($idUsuario, FILTER_VALIDATE_INT)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Comprueba que los idUsuarios existan en la base de datos
     * @param array $idUsuarios los ids de los usuarios
     * @return bool Devuelve true si todos los usuarios existen o si no false
     */
    public function comprobarUsuarios(array $idUsuarios): bool {
        foreach ($idUsuarios as $idUsuario) {
            if (is_null($this->buscarUsuarioPorId((int) $idUsuario))) {
                return false;
            }
        }
        return true;
    }
}
