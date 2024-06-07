<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "../app/Models/TareaModel.php";

class TareaModelTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable('../');
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        $_ENV["db.host"] = "localhost:33006";

        // Inicia la variable $_SESSION
        $_SESSION = [];
    }

    protected function tearDown(): void {
        // Elimina la variable $_SESSION después de cada prueba
        $_SESSION = [];
    }

    public function testmostrarUsuariosPorTarea() {
        $modelTarea = new \Com\TaskVelocity\Models\TareaModel();
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(1);

        $this->assertIsArray($modelTarea->procesarUsuariosPorTarea(1));
        $this->assertNull($modelTarea->procesarUsuariosPorTarea(-1));
    }
    
    public function testmostrarTareas() {
        $modelTarea = new \Com\TaskVelocity\Models\TareaModel();
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(2);

        $this->assertIsArray($modelTarea->mostrarTareas());
    }

    public function testbuscarTareaPorId() {
        $modelTarea = new \Com\TaskVelocity\Models\TareaModel();

        $this->assertNull($modelTarea->buscarTareaPorId(-1));
        $this->assertIsArray($modelTarea->buscarTareaPorId(1));
    }
}
