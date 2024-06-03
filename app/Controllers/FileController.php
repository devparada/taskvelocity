<?php

namespace Com\TaskVelocity\Controllers;

class FileController extends \Com\TaskVelocity\Core\BaseController {

    public function procesarBorrarImagen(int $idImagen) {
        $modeloFile = new \Com\TaskVelocity\Models\FileModel();
        $modeloFile->eliminarImagenProyectoTarea($idImagen);
    }
}
