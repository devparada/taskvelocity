<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once "../app/Models/RolModel.php";

class RolModelTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable('../');
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        $_ENV["db.host"] = "localhost:33006";
    }

    public function testmostrarRoles() {
        $model = new \Com\TaskVelocity\Models\RolModel();

        $this->assertIsArray($model->mostrarRoles());
    }

    public function testcomprobarRol() {
        $model = new \Com\TaskVelocity\Models\RolModel();

        $this->assertTrue($model->comprobarRol("1"));
        $this->assertFalse($model->comprobarRol("-1"));
    }
}
