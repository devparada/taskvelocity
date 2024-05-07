<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class FilesModel extends \Com\TaskVelocity\Core\BaseModel {

    public function guardarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): void {
        $directorio = "./assets/img/$nombreDirectorio/";

        // Si la carpeta no existe se crea
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        if (!empty($_FILES["avatar"]["name"])) {
            $directorioArchivo = $directorio . "$nombreArchivo-" . $id . "." . pathinfo($_FILES["avatar"]["name"])["extension"];
        } else {
            $directorioArchivo = $directorio . "$nombreArchivo-" . $id . ".jpg";
        }

        if ($nombreDirectorio == "usuarios") {
            if (!isset($_FILES["avatar"])) {
                // La imagen subida se mueve al directorio y se llama con el id del usuario
                move_uploaded_file($_FILES["avatar"]["tmp_name"], $directorioArchivo);
            } else {
                if ($nombreDirectorio == "usuarios") {
                    // La imagen por defecto se copia con el id del usuario
                    copy($directorio . "avatar-default.jpg", $directorioArchivo);
                }
            }
        } else {
            move_uploaded_file($_FILES["imagen_$nombreArchivo"]["tmp_name"], $directorioArchivo);
        }
    }

    public function actualizarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): bool {
        $directorio = "./assets/img/$nombreDirectorio/";

        $imagen = $directorio . "$nombreArchivo-" . $id . ".";

        // Para obtener la extension de la imagen se comprueba si es png o jpg
        file_exists($imagen . "png") ? $extension = "png" : $extension = "jpg";

        $imagenRuta = $imagen . $extension;

        // Si se puede escribir o borrar la imagen
        if (is_writable($directorio)) {
            if (file_exists($imagenRuta)) {
                // Se borra la imagen
                unlink($imagenRuta);
            }
            move_uploaded_file($_FILES["imagen_$nombreArchivo"]["tmp_name"], $imagenRuta);

            return true;
        }

        return false;
    }

    public function buscarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): bool {
        $imagenRuta = "./assets/img/$nombreDirectorio/$nombreArchivo-" . $id . ".";
        if (file_exists($imagenRuta . "png") || file_exists($imagenRuta . "jpg")) {
            return true;
        }

        return false;
    }

    public function eliminarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): bool {
        $directorio = "./assets/img/$nombreDirectorio/";

        $imagen = $directorio . "$nombreArchivo-" . $id . ".";

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
}
