<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class RolModelUnitariasTest extends TestCase {

    protected function setUp(): void {
        // Carga las variables env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        // Aquí va con el puerto debido a que no se reenvia correctamente el puerto 3306 -> 33006
        // ! Sólo desde la máquina real (en la virtual va bien)        
        // $_ENV["db.host"] = "localhost:33006";
    }

    public function testComprobarRol() {
        $modelRol = new \Com\TaskVelocity\Models\RolModel();
        $this->assertTrue($modelRol->comprobarRol("2"));
        $this->assertFalse($modelRol->comprobarRol("33"));
    }
}
