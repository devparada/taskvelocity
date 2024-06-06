<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "../app/Models/UsuarioModel.php";

class UsuarioModelTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable('../');
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        $_ENV["db.host"] = "localhost:33006";
    }

    public function testprocesarLogin() {
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertTrue($modelUsuario->procesarLogin("admin", "TaskVelocity1"));
        $this->assertFalse($modelUsuario->procesarLogin("karpiña@personal.com", "asds"));
        $this->assertFalse($modelUsuario->procesarLogin("tractor", "password1"));
        $this->assertFalse($modelUsuario->procesarLogin("rauliño@a.com", "TaskVelocity1"));
    }

    public function testbuscarUsuarioPorId() {
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertIsArray($modelUsuario->buscarUsuarioPorId(1));
        $this->assertNull($modelUsuario->buscarUsuarioPorId(100));
    }

    public function testbuscarUsuarioPorUsername() {
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertFalse($modelUsuario->comprobarUsuariosNumero([1, 2, 3, "usuario1"]));
        $this->assertTrue($modelUsuario->comprobarUsuariosNumero([1, 2, 3, 4]));
    }
}
