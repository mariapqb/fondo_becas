<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../backend/PQRS.php';

class pqrsTest extends TestCase {
    private $conn;
    private $pqrs;

    protected function setUp(): void {
        $this->conn = $this->createMock(mysqli::class);
        $this->pqrs = new PQRS($this->conn);
    }

    public function testCamposObligatoriosVacios() {
        $resultado = $this->pqrs->registrarPQRS("", "", "", "", "", "", "");
        $this->assertEquals("Los campos obligatorios no pueden estar vacíos.", $resultado);
    }

    public function testEmailInvalido() {
        $resultado = $this->pqrs->registrarPQRS("Juan Pérez", "12345678", "correo_mal", "Calle 123", "Queja", "Descripción", "Solicitud específica");
        $this->assertEquals("Correo electrónico no válido.", $resultado);
    }

    public function testRegistroExitoso() {
        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method("execute")->willReturn(true);

        $this->conn->method("prepare")->willReturn($stmtMock);

        $resultado = $this->pqrs->registrarPQRS("Juan Pérez", "12345678", "juan@example.com", "Calle 123", "Queja", "Descripción", "Solicitud específica");
        $this->assertEquals("PQRS registrado exitosamente.", $resultado);
    }

    public function testErrorEnBaseDeDatos() {
        $this->conn->method("prepare")->willReturn(false); // Simula un error en la base de datos

        $resultado = $this->pqrs->registrarPQRS("Juan Pérez", "12345678", "juan@example.com", "Calle 123", "Queja", "Descripción", "Solicitud específica");
        $this->assertEquals("Error en la base de datos.", $resultado);
    }
}
