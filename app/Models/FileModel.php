<?php

declare(strict_types=1);

namespace Com\TaskVelocity\Models;

class FileModel extends \Com\TaskVelocity\Core\BaseModel {

    /**
     * El valor de 1MB en bytes
     */
    public const MB = 1048576;

    /**
     * Guarda la imagen en el servidor
     * @param string $nombreDirectorio el nombre de la carpeta
     * @param string $nombreArchivo el nombre del archivo hasta el guión
     * @param int $id el id que va despues del $nombreArchivo
     * @return void
     */
    public function guardarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): void {
        $directorio = "./assets/img/$nombreDirectorio/";

        // Si la carpeta no existe se crea
        if (!file_exists($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $directorioArchivo = $directorio . "$nombreArchivo-" . $id . ".jpg";

        // Si la carpeta es usuarios
        if ($nombreDirectorio == "usuarios") {
            if (!empty($_FILES["imagen_avatar"]["name"])) {
                // La imagen subida se mueve al directorio y se llama con el id del usuario
                move_uploaded_file($_FILES["imagen_avatar"]["tmp_name"], $directorioArchivo);
            } else {
                // La imagen por defecto se copia con el id del usuario
                copy($directorio . "avatar-default.jpg", $directorioArchivo);
            }
        } else {
            move_uploaded_file($_FILES["imagen_$nombreArchivo"]["tmp_name"], $directorioArchivo);
        }

        if (empty($_FILES["imagen_$nombreArchivo"]["name"])) {
            copy($directorio . "$nombreArchivo-default.jpg", $directorioArchivo);
        }
    }

    /**
     * Actuializa la imagen en el servidor
     * @param string $nombreDirectorio el nombre de la carpeta
     * @param string $nombreArchivo el nombre del archivo hasta el guión
     * @param int $id el id que va despues del $nombreArchivo
     * @return bool Retorna true si fue actualizada bien o false si no
     */
    public function actualizarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): bool {
        $directorio = "./assets/img/$nombreDirectorio/";

        $imagenRuta = $directorio . "$nombreArchivo-" . $id . ".jpg";

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

    /**
     * Busca la imagen en el servidor
     * @param string $nombreDirectorio el nombre de la carpeta
     * @param string $nombreArchivo el nombre del archivo hasta el guión
     * @param int $id el id que va despues del $nombreArchivo
     * @return bool Retorna true si fue encontrada la imagen o false si no
     */
    public function buscarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): bool {
        $imagenRuta = "./assets/img/$nombreDirectorio/$nombreArchivo-" . $id . ".";
        if (file_exists($imagenRuta . "jpg")) {
            return true;
        }

        return false;
    }

    /**
     * Elimina la imagen en el servidor
     * @param string $nombreDirectorio el nombre de la carpeta
     * @param string $nombreArchivo el nombre del archivo hasta el guión
     * @param int $id el id que va despues del $nombreArchivo
     * @return bool Retorna true si fue eliminada la imagen o false si no
     */
    public function eliminarImagen(string $nombreDirectorio, string $nombreArchivo, int $id): bool {
        $directorio = "./assets/img/$nombreDirectorio/";

        $imagenRuta = $directorio . "$nombreArchivo-" . $id . ".jpg";

        // Si se puede escribir o borrar la imagen
        if (is_writable($imagenRuta)) {
            // Se borra la imagen
            unlink($imagenRuta);
            return true;
        }

        return false;
    }
}
