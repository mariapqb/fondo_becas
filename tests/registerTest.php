<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../backend/register.php';

class registerTest extends TestCase {
    private $conn;
    private $register;

    protected function setUp(): void {
        $this->conn = $this->createMock(mysqli::class);
        $this->register = new Register($this->conn);
    }

    public function testCamposVacios() {
        $resultado = $this->register->registerUser("", "", "", "");
        $this->assertEquals("Todos los campos son obligatorios.", $resultado);
    }

    public function testContrasenasNoCoinciden() {
        $resultado = $this->register->registerUser("CC", "12345678", "password123", "password456");
        $this->assertEquals("Las contraseñas no coinciden.", $resultado);
    }

    public function testUsuarioYaRegistrado() {
        // Crear un mock de mysqli_result
        $mock_result = $this->createMock(mysqli_result::class);
        $mock_result->method("fetch_assoc")->willReturn(['numero_documento' => '12345678']); // Simular que el usuario existe

        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method("execute")->willReturn(true);
        $stmtMock->method("get_result")->willReturn($mock_result); // Devolver el mock con datos

        $this->conn->method("prepare")->willReturn($stmtMock);

        $resultado = $this->register->registerUser("CC", "12345678", "password123", "password123");
        $this->assertEquals("El usuario ya está registrado.", $resultado);
    }


    public function testRegistroExitoso() {
        // Crear un mock de mysqli_result vacío (sin usuarios)
        $mock_result = $this->createMock(mysqli_result::class);
        $mock_result->method("fetch_all")->willReturn([]); // Simula que no hay usuarios

        $stmtCheckMock = $this->createMock(mysqli_stmt::class);
        $stmtCheckMock->method("execute")->willReturn(true);
        $stmtCheckMock->method("get_result")->willReturn($mock_result);

        // Simular la inserción exitosa
        $stmtInsertMock = $this->createMock(mysqli_stmt::class);
        $stmtInsertMock->method("execute")->willReturn(true);

        $this->conn->method("prepare")->willReturnOnConsecutiveCalls($stmtCheckMock, $stmtInsertMock);

        $resultado = $this->register->registerUser("CC", "12345678", "password123", "password123");
        $this->assertEquals("Registro exitoso.", $resultado);
    }
}
