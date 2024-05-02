<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class UsuarioModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarUsuarios(): array {
        $stmt = $this->pdo->query("SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color");
        return $stmt->fetchAll();
    }

    public function buscarUsuarioPorId(int $idUsuario): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);

        $usuarioEncontrado = $stmt->fetch();

        if ($usuarioEncontrado) {
            return $usuarioEncontrado;
        } else {
            return null;
        }
    }

    public function buscarUsuarioPorUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color WHERE username = ?");
        $stmt->execute([$username]);

        $usuarioEncontrado = $stmt->fetch();

        if ($usuarioEncontrado) {
            return $usuarioEncontrado;
        } else {
            return null;
        }
    }

    public function buscarUsuarioPorEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color WHERE email = ?");
        $stmt->execute([$email]);

        $usuarioEncontrado = $stmt->fetch();

        if ($usuarioEncontrado) {
            return $usuarioEncontrado;
        } else {
            return null;
        }
    }

    /**
     * Comprueba si los datos introducidos en el login existen en un usuario y hace login en caso afirmativo
     * @param string $email El email introducido
     * @param string $password La contraseña introducida
     * @return bool Retorna true si se hace el login o false si no
     */
    public function procesarLogin(string $email, string $password): bool {
        $usuarioEncontrado = $this->buscarUsuarioPorEmail($email);

        if (!is_null($usuarioEncontrado)) {
            if ($email == $usuarioEncontrado["email"] && password_verify($password, $usuarioEncontrado["password"])) {
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
    public function addUsuario(string $username, string $contrasena, string $email, $idRol, string $fechaNacimiento, ?string $descripcionUsuario, string $idColor): bool {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (username, password, email, id_rol, fecha_nacimiento, fecha_login, 
            descripcion_usuario, id_color_favorito) VALUES (?, ?, ?, ?, ?, NULL, ?, ?)");

        if ($stmt->execute([$username, password_hash($contrasena, '2y'), $email, $idRol, $fechaNacimiento, $descripcionUsuario, $idColor])) {
            return true;
        }
        return false;
    }

    public function editUsuario(string $username, string $contrasena, string $email, string $idRol, string $fechaNacimiento, string $descripcionUsuario, string $idColor, int $idUsuario): bool {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET username=?, password=?, email=?, id_rol=?, fecha_nacimiento=?, "
                . "fecha_login=current_timestamp(), descripcion_usuario=?, id_color_favorito=? WHERE id_usuario=?");

        if ($stmt->execute([$username, password_hash($contrasena, '2y'), $email, $idRol, $fechaNacimiento, $descripcionUsuario, $idColor, $idUsuario])) {
            return true;
        }
        return false;
    }

    public function crearAvatar(string $username): void {
        $directorio = "./assets/img/usuarios/";

        // Si la carpeta no existe se crea
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios us WHERE us.username = ?");
        $stmt->execute([$username]);

        $usuarioId = $stmt->fetch()["id_usuario"];

        if (!empty($_FILES["avatar"]["name"])) {
            // Si la imagen es subida la extension puede ser jpg o png
            $directorioArchivo = $directorio . "avatar-" . $usuarioId . "." . pathinfo($_FILES["avatar"]["name"])["extension"];
        } else {
            // Si la imagen es por defecto la extension es jpg
            $directorioArchivo = $directorio . "avatar-" . $usuarioId . ".jpg";
        }

        if (!empty($_FILES["avatar"]["name"])) {
            // La imagen subida se mueve al directorio y se llama con el id del usuario
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $directorioArchivo);
        } else {
            // La imagen por defecto se copia con el id del usuario
            copy($directorio . "avatar-default.jpg", $directorioArchivo);
        }
    }

    public function updateAvatar(int $idUsuario): bool {
        $directorio = "./assets/img/usuarios/";

        $imagen = $directorio . "avatar-" . $idUsuario . ".";

        // Para obtener la extension de la imagen se comprueba si es png o jpg
        file_exists($imagen . "png") ? $extension = "png" : $extension = "jpg";

        $imagenRuta = $imagen . $extension;

        // Si se puede escribir o borrar la imagen
        if (is_writable($directorio)) {
            if (file_exists($imagenRuta)) {
                // Se borra la imagen
                unlink($imagenRuta);
            }

            move_uploaded_file($_FILES["avatar"]["tmp_name"], $imagenRuta);
            return true;
        }

        return false;
    }

    public function eliminarAvatar(int $idUsuario): bool {
        $directorio = "./assets/img/usuarios/";

        $imagen = $directorio . "avatar-" . $idUsuario . ".";

        // Para obtener la extension de la imagen se comprueba si es png o jpg
        file_exists($imagen . "png") ? $extension = "png" : $extension = "jpg";

        $imagenRuta = $imagen . $extension;

        // Si se puede escribir o borrar la imagen
        if (is_writable($imagenRuta)) {
            // Se borra la imagen
            unlink($imagenRuta);
            return true;
        }

        return false;
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios");
        return $stmt->fetchColumn();
    }

    public function deleteUsuario(int $idUsuario): bool {
        if (!is_null($this->buscarUsuarioPorId($idUsuario))) {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$idUsuario]);
            if ($this->eliminarAvatar($idUsuario)) {
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
