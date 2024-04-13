<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class ProyectoModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarProyectos(): array {
        $stmt = $this->pdo->query("SELECT *,  COUNT(id_usuarioPAsoc) FROM proyectos pr LEFT JOIN usuarios us"
                . " ON pr.id_usuario_proyecto_prop = us.id_usuario LEFT JOIN usuarios_proyectos up"
                . " ON pr.id_proyecto = up.id_proyectoPAsoc GROUP BY id_proyectoPAsoc");
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
            $usuarios[$index] = $usuariosProyectos[$index]["username"] . " ";
        }

        return $usuarios;
    }
}
