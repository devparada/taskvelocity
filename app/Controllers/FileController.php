<?php

namespace Com\TaskVelocity\Controllers;

class FileController extends \Com\TaskVelocity\Core\BaseController {

    public function procesarBorrarImagen(int $idImagen) {
        $modeloFile = new \Com\TaskVelocity\Models\FileModel();
        $modeloFile->eliminarImagenProyectoTarea($idImagen);
        switch ($_SESSION["historial"]) {
            case strpos(ltrim($_SESSION["historial"]), "/proyectos") !== false:
                header("location: /proyectos/editar/$idImagen");
                break;
            case strpos(ltrim($_SESSION["historial"]), "/tareas") !== false:
                header("location: /tareas/editar/$idImagen");
                break;
        }
    }
}
