<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class ProyectoModelUnitariasTest extends TestCase {

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

    public function testmostrarProyectos() {
        $modelProyecto = new \Com\TaskVelocity\Models\ProyectoModel();
        $modelUsuario = new \Com\TaskVelocity\Models\UsuarioModel();

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(1);
        $this->assertIsArray($modelProyecto->mostrarProyectos());

        $_SESSION["usuario"] = $modelUsuario->buscarUsuarioPorId(7);
        $this->assertEmpty($modelProyecto->mostrarProyectos());
    }
}
