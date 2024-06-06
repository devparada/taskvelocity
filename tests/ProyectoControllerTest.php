<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "../app/Controllers/UsuarioController.php";

class ProyectoControllerTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable('../');
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        $_ENV["db.host"] = "localhost:33006";
        $_ENV["db.schema"] = "proxecto";

        // Inicia la variable $_SESSION
        $_SESSION = [];

        // Crea la conexión de la base de datos usando las variables $_ENV
        $this->pdo = new PDO('mysql:host=' . $_ENV["db.host"] . ';dbname=' . $_ENV["db.schema"], $_ENV["db.user"], $_ENV["db.pass"]);
    }

    protected function tearDown(): void {
        // Elimina la variable $_SESSION después de cada prueba
        $_SESSION = [];
    }

    public function testmostrarProyectos() {
        // Simular una solicitud al controlador ProyectoController
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/proyectos';

        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(2);

        $modelProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $proyectos = $modelProyecto->mostrarProyectos();

        foreach ($proyectos as $proyecto) {
            $this->assertStringContainsString((string) 1, (string) $proyecto["editable"]);
            $this->assertStringNotContainsString((string) $_SESSION["usuario"]["id_proyecto_personal"], (string) $proyecto["id_proyecto"]);
        }
    }
}
