<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class TareaModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarTareas(): array {
        if ($_SESSION["usuario"]["id_rol"] == 1) {
            $stmt = $this->pdo->query("SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
                    . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
                    . "ON ta.id_tarea=ut.id_tareaTAsoc GROUP BY ut.id_tareaTAsoc");
        } else {
            $stmt = $this->pdo->prepare("SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
                    . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
                    . "ON ta.id_tarea=ut.id_tareaTAsoc WHERE us.id_usuario = ? OR ut.id_usuarioTAsoc = ? GROUP BY ut.id_tareaTAsoc");
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

    public function addTarea(string $nombreTarea, ?string $fechaLimite, string $idColorTarea, string $idProyecto, array $id_usuarios_asociados, string $descripcionTarea): bool {
        $stmt = $this->pdo->prepare("INSERT INTO tareas "
                . "(nombre_tarea, id_color_tarea, descripcion_tarea, fecha_limite, id_usuario_tarea_prop, id_proyecto) "
                . "VALUES (?, ?, ?, ?, ?, ?)");

        // Si la fecha límite no se especifica se cambia por null
        if ($fechaLimite == "") {
            $fechaLimite = null;
        }

        if ($stmt->execute([$nombreTarea, $idColorTarea, $descripcionTarea, $fechaLimite, $_SESSION["usuario"]["id_usuario"], $idProyecto])) {
            // Se consigue el id de la tarea debido a que es la última tarea insertada
            $idTarea = $this->pdo->lastInsertId();
            $this->addTareaUsuarios($id_usuarios_asociados, (int) $idTarea);
            $this->crearImagen((int) $idTarea);
            return true;
        }
        return false;
    }

    private function addTareaUsuarios(array $idUsuarios, int $idTarea): void {
        foreach ($idUsuarios as $idUsuario) {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios_tareas "
                    . "(id_usuarioTAsoc, id_tareaTAsoc) VALUES(?, ?)");

            $stmt->execute([$idUsuario, $idTarea]);
        }
    }

    private function crearImagen(int $idTarea): void {
        $directorio = "./assets/img/tareas/";

        // Si la carpeta no existe se crea
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        if (!empty($_FILES["imagen_tarea"]["name"])) {
            // Si la imagen es subida la extension puede ser jpg o png
            $directorioArchivo = $directorio . "tarea-" . $idTarea . "." . pathinfo($_FILES["imagen_tarea"]["name"])["extension"];
        } else {
            // Si la imagen es por defecto la extension es jpg
            $directorioArchivo = $directorio . "tarea-" . $idTarea . ".jpg";
        }

        if (!empty($_FILES["imagen_tarea"]["name"])) {
            // La imagen subida se mueve al directorio y se llama con el id de la tarea
            move_uploaded_file($_FILES["imagen_tarea"]["tmp_name"], $directorioArchivo);
        }
    }

    private function buscarImagen(int $idTarea): bool {
        $imagenRuta = "./assets/img/tareas/tarea-" . $idTarea . ".";
        if (file_exists($imagenRuta . "png") || file_exists($imagenRuta . "jpg")) {
            return true;
        }

        return false;
    }

    private function eliminarImagen(int $idTarea): bool {
        $directorio = "./assets/img/tareas/";

        $imagen = $directorio . "tarea-" . $idTarea . ".";

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
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM tareas");
        return $stmt->fetchColumn();
    }

    public function deleteTarea(int $idTarea): bool {
        $valorDevuelto = false;

        if (!is_null($this->buscarTareaPorId($idTarea))) {
            $stmt = $this->pdo->prepare("DELETE FROM tareas WHERE id_tarea = ?");
            $stmt->execute([$idTarea]);
            if (!$this->buscarImagen($idTarea) || $this->eliminarImagen($idTarea)) {
                $valorDevuelto = true;
            } else {
                $valorDevuelto = false;
            }
        }

        return $valorDevuelto;
    }
}
