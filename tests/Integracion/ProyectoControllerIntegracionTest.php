<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ProyectoControllerIntegracionTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        // ! Sólo desde la máquina real (en la virtual va bien)
        // $_ENV["db.host"] = "localhost:33006";
    }

    protected function tearDown(): void {
        // Elimina la variable $_SESSION después de cada prueba
        $_SESSION = [];
    }

    public function testmostrarProyectosAsync() {
        // Simular una solicitud al controlador ProyectoController
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/proyectos-ajax';

        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $modelProyecto = new \Com\TaskVelocity\Models\ProyectoModel();

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(2);

        // ! Estás variables se usan para que la vista proyectos-ajax tengo todas las variables necesarias
        $proyectos = $modelProyecto->mostrarProyectos();
        $usuarios = $modelUsuario->mostrarUsuarios();

        // Muestra la salida del controlador (en formato HTML)
        ob_start();
        require __DIR__ . "/../../app/Views/public/proyectos-ajax.view.php";
        // Guarda la salida del controlador y la elimina para futuras pruebas
        $salida = ob_get_clean();

        $this->assertNotNull($salida);
        $this->assertStringNotContainsString((string) $_SESSION["usuario"]["id_proyecto_personal"], (string) $salida);
    }
}
