<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class UsuarioModelUnitariasTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        // ! Sólo desde la máquina real (en la virtual la siguiente línea tiene que estar comentada)
        // $_ENV["db.host"] = "localhost:33006";
    }

    protected function tearDown(): void {
        // Elimina la variable $_SESSION después de cada prueba
        $_SESSION = [];
    }

    public function testprocesarLogin() {
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertTrue($modelUsuario->procesarLogin("admin", "TaskVelocity1"));
        $this->assertFalse($modelUsuario->procesarLogin("karpiña@personal.com", "asds"));
        $this->assertFalse($modelUsuario->procesarLogin("tractor", "password1"));
        $this->assertFalse($modelUsuario->procesarLogin("rauliño@a.com", "TaskVelocity1"));
    }

    public function testMostrarUsuarios() {
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();
        $this->assertIsArray($modelUsuario->mostrarUsuarios());
        $this->assertNotNull($modelUsuario->mostrarUsuarios());
    }
}
