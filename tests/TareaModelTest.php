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
    }

    public function testcontadorTareasPorEtiqueta() {
        $model = new \Com\TaskVelocity\Models\TareaModel();

        $this->assertNull($model->buscarTareaPorId(1));
    }

    public function testcontadorPorUsuarioPropietario() {
        $model = new \Com\TaskVelocity\Models\TareaModel();

        $this->assertNull($model->buscarTareaPorId(122));
    }
}
