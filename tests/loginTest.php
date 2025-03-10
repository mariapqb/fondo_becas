<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../backend/Login.php';

class loginTest extends TestCase {
    private $conn;
    private $login;

    protected function setUp(): void {
        $this->conn = $this->createMock(mysqli::class);
        $this->login = new Login($this->conn);
    }

    public function testCamposVacios() {
        $resultado = $this->login->authenticateUser("", "");
        $this->assertEquals("Todos los campos son obligatorios.", $resultado);
    }

    public function testUsuarioNoEncontrado() {
        $mock_result = $this->createMock(mysqli_result::class);
        $mock_result->method("fetch_assoc")->willReturn(null);

        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method("execute")->willReturn(true);
        $stmtMock->method("get_result")->willReturn($mock_result);

        $this->conn->method("prepare")->willReturn($stmtMock);

        $resultado = $this->login->authenticateUser("12345678", "password123");
        $this->assertEquals("Usuario no encontrado.", $resultado);
    }

    public function testContrasenaIncorrecta() {
        $mock_result = $this->createMock(mysqli_result::class);
        $mock_result->method("fetch_assoc")->willReturn(["contraseña" => hash("sha256", "otra_clave")]);

        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method("execute")->willReturn(true);
        $stmtMock->method("get_result")->willReturn($mock_result);

        $this->conn->method("prepare")->willReturn($stmtMock);

        $resultado = $this->login->authenticateUser("12345678", "password123");
        $this->assertEquals("Contraseña incorrecta.", $resultado);
    }

    public function testInicioSesionExitoso() {
        $mock_result = $this->createMock(mysqli_result::class);
        $mock_result->method("fetch_assoc")->willReturn(["contraseña" => hash("sha256", "password123")]);

        $stmtMock = $this->createMock(mysqli_stmt::class);
        $stmtMock->method("execute")->willReturn(true);
        $stmtMock->method("get_result")->willReturn($mock_result);

        $this->conn->method("prepare")->willReturn($stmtMock);

        $resultado = $this->login->authenticateUser("12345678", "password123");
        $this->assertEquals("Inicio de sesión exitoso.", $resultado);
    }
}
