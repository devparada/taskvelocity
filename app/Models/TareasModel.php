<?php

declare(strict_types=1);

namespace Com\Daw2\Models;

class TareasModel extends \Com\Daw2\Core\BaseModel {

    public function mostrarTareas(): array {
        $stmt = $this->pdo->query("SELECT *, COUNT(id_usuarioTAsoc) FROM tareas ta JOIN proyectos p ON ta.id_proyecto=p.id_proyecto LEFT JOIN usuarios us "
                . "ON ta.id_usuario_tarea_prop=us.id_usuario LEFT JOIN colores c ON ta.id_color_tarea = c.id_color LEFT JOIN usuarios_tareas ut "
                . "ON ta.id_tarea=ut.id_tareaTAsoc GROUP BY ut.id_tareaTAsoc");
        $datos = $stmt->fetchAll();

        for ($i = 0; $i < count($datos); $i++) {
            for ($j = 0; $j < $datos[$i]["COUNT(id_usuarioTAsoc)"]; $j++) {
                $stmt = $this->pdo->query("SELECT * FROM usuarios_tareas JOIN usuarios ON usuarios_tareas.id_usuarioTAsoc = usuarios.id_usuario "
                        . "WHERE id_tareaTAsoc =" . $datos[$i]["id_tareaTAsoc"]);

                $usuariosTareas = $stmt->fetchAll();

                $datos[$i]["nombresUsuarios"] = $this->mostrarUsernamesTarea($usuariosTareas, $datos);
            }
        }
        return $datos;
    }

    private function mostrarUsernamesTarea(array $usuariosTareas): string {
        $usuarios = "";

        foreach ($usuariosTareas as $usuariosTarea) {
            $usuarios .= $usuariosTarea["username"] . " ";
        }

        return $usuarios;
    }
}
