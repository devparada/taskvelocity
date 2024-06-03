<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class UsuarioModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * Consulta base para algunos métodos de esta clase
     */
    private const baseConsulta = "SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color ";

    public function mostrarUsuarios(): array {
        $stmt = $this->pdo->query(self::baseConsulta);
        return $stmt->fetchAll();
    }

    /**
     * Muestra los nombres de los usuarios de forma asincrona cuando se busca en el select2
     * @return array los nombres de los usuarios en un array
     */
    public function buscarUsuariosAsync(): array {
        $resultado = "";

        if (!empty($_GET['q'])) {
            $search = $_GET['q'];
            $stmt = $this->pdo->prepare("SELECT id_usuario, username FROM usuarios us WHERE us.username LIKE :search "
                    . "AND (us.id_rol != 1 AND us.id_usuario !=" . $_SESSION["usuario"]["id_usuario"] . ")");
            $stmt->execute(['search' => "$search%"]);

            $usuarios = array();
            while ($row = $stmt->fetch($this->pdo::FETCH_ASSOC)) {
                $usuarios[] = ['id' => $row['id_usuario'], 'text' => $row['username']];
            }

            $resultado = ['results' => $usuarios];
        }

        return $resultado;
    }

    public function mostrarUsuariosFormulario(): array {
        $stmt = $this->pdo->query(self::baseConsulta . "WHERE NOT us.id_rol = 1 XOR us.id_usuario = " . $_SESSION["usuario"]["id_usuario"]);
        return $stmt->fetchAll();
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
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE username = ?");
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
        $stmt = $this->pdo->prepare(self::baseConsulta . "WHERE email = ?");
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
        if (!is_null($this->buscarUsuarioPorId($idUsuario))) {
            $modeloLog = new \Com\TaskVelocity\Models\LogModel();
            $modeloLog->crearLog("Eliminado el usuario con el id $idUsuario", $_SESSION["usuario"]["id_usuario"]);
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$idUsuario]);
            $modeloFiles = new \Com\TaskVelocity\Models\FileModel();
            if ($modeloFiles->eliminarImagen("usuarios", "avatar", $idUsuario)) {
                return true;
            }
        }

        return false;
    }

    public function comprobarUsuariosNumero(array $idUsuarios): bool {
        foreach ($idUsuarios as $idUsuario) {
            if (!filter_var($idUsuario, FILTER_VALIDATE_INT)) {
                return false;
            }
        }
        return true;
    }

    public function comprobarUsuarios(array $idUsuarios): bool {
        foreach ($idUsuarios as $idUsuario) {
            if (is_null($this->buscarUsuarioPorId((int) $idUsuario))) {
                return false;
            }
        }
        return true;
    }
}
