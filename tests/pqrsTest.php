<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../backend/PQRS.php';// Incluir la clase PQRS

/**
 * Clase de prueba para la funcionalidad de registro de PQRS en el sistema.
 */
class pqrsTest extends TestCase {
    private $conn; // Simulación de conexión a la base de datos
    private $pqrs;// Instancia de la clase PQRS

    /**
     * Configuración inicial antes de cada prueba.
     */
    protected function setUp(): void {
        $this->conn = $this->createMock(mysqli::class);
        $this->pqrs = new PQRS($this->conn);
    }

    /**
     * Prueba para verificar el comportamiento cuando los campos obligatorios están vacíos.
     */
    public function testCamposObligatoriosVacios() {
        $resultado = $this->pqrs->registrarPQRS("", "", "", "", "", "", "");
        $this->assertEquals("Los campos obligatorios no pueden estar vacíos.", $resultado);
    }

    /**
     * Prueba para validar que el sistema detecta un correo electrónico no válido.
     */
    public function testEmailInvalido() {
        $resultado = $this->pqrs->registrarPQRS("Juan Pérez", "12345678", "correo_mal", "Calle 123", "Queja", "Descripción", "Solicitud específica");
        $this->assertEquals("Correo electrónico no válido.", $resultado);
    }

    /**
     * Prueba para verificar un registro exitoso de PQRS.
     */
    public function testRegistroExitoso() {
        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method("execute")->willReturn(true);

        $this->conn->method("prepare")->willReturn($stmtMock);

        $resultado = $this->pqrs->registrarPQRS("Juan Pérez", "12345678", "juan@example.com", "Calle 123", "Queja", "Descripción", "Solicitud específica");
        $this->assertEquals("PQRS registrado exitosamente.", $resultado);
    }

    /**
     * Prueba para verificar el comportamiento cuando ocurre un error en la base de datos.
     */
    public function testErrorEnBaseDeDatos() {
        $this->conn->method("prepare")->willReturn(false); // Simula un error en la base de datos

        $resultado = $this->pqrs->registrarPQRS("Juan Pérez", "12345678", "juan@example.com", "Calle 123", "Queja", "Descripción", "Solicitud específica");
        $this->assertEquals("Error en la base de datos.", $resultado);
    }
}
