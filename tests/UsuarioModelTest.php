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

        $model = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertTrue($model->procesarLogin("admin", "TaskVelocity1"));
        $this->assertFalse($model->procesarLogin("karpiña@personal.com", "asds"));
        $this->assertFalse($model->procesarLogin("tractor", "password1"));
        $this->assertFalse($model->procesarLogin("rauliño@personal.com", "TaskVelocity1"));
    }

    public function testbuscarUsuarioPorId() {
        $model = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertIsArray($model->buscarUsuarioPorId(1));
        $this->assertNull($model->buscarUsuarioPorId(100));
    }

    public function testbuscarUsuarioPorUsername() {
        $model = new \Com\TaskVelocity\Models\UsuarioModel();

        $this->assertFalse($model->comprobarUsuariosNumero([1, 2, 3, "usuario1"]));
        $this->assertTrue($model->comprobarUsuariosNumero([1, 2, 3, 4]));
    }
}
