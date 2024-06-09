<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class TareaModelUnitariasTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        // ! Sólo desde la máquina real (en la virtual va bien)
        $_ENV["db.host"] = "localhost:33006";

        // Inicia la variable $_SESSION
        $_SESSION = [];
    }

    protected function tearDown(): void {
        // Elimina la variable $_SESSION después de cada prueba
        $_SESSION = [];
    }

    public function testmostrarTareas() {
        $modelTarea = new \Com\TaskVelocity\Models\TareaModel();
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(2);
        $this->assertIsArray($modelTarea->mostrarTareas());

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(4);
        $this->assertEmpty($modelTarea->mostrarTareas());
    }
}
