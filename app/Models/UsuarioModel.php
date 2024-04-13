<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class UsuarioModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarUsuarios(): array {
        $stmt = $this->pdo->query("SELECT * FROM usuarios us JOIN roles r ON us.id_rol = r.id_rol JOIN colores c ON us.id_color_favorito = c.id_color");
        return $stmt->fetchAll();
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

    public function addUsuario(string $username, string $contrasena, string $email, string $idRol, string $fechaNacimiento, string $descripcionUsuario, string $idColorFav): bool {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (id_usuario, username, password, email, id_rol, fecha_nacimiento, fecha_login, 
            descripcion_usuario, id_color_favorito) VALUES (0, ?, ?, ?, ?, ?, NULL, ?, ?)");

        if ($stmt->execute([$username, password_hash($contrasena, '2y'), $email, $idRol, $fechaNacimiento, $descripcionUsuario, $idColorFav])) {
            return true;
        }
        return false;
    }

    public function crearAvatar(string $email): void {
        $directorio = "./assets/img/users/";

        // Si la carpeta no existe se crea
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuarios us WHERE us.email = ?");
        $stmt->execute([$email]);

        $usuarioId = $stmt->fetch()["id_usuario"];

        if (!empty($_FILES["avatar"])) {
            // Si la imagen es subida la extension puede ser jpg o png
            $directorioArchivo = $directorio . "avatar-" . $usuarioId . "." . pathinfo($_FILES["avatar"]["name"])["extension"];
        } else {
            // Si la imagen es por defecto la extension es jpg
            $directorioArchivo = $directorio . "avatar-" . $usuarioId . ".jpg";
        }

        if (!empty($_FILES["avatar"])) {
            // La imagen subida se mueve al directorio y se llama con el id del usuario
            move_uploaded_file($_FILES["avatar"]["tmp_name"], $directorioArchivo);
        } else {
            // La imagen por defecto se copia con el id del usuario
            copy($directorio . "avatar-default.jpg", $directorioArchivo);
        }
    }

    public function contador(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios");
        return $stmt->fetchColumn();
    }

    public function deleteUsuario(int $idUsuario) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$idUsuario]);
    }
}
