<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../backend/Login.php';// Incluir la clase Login

/**
 * Clase de prueba para la autenticación de usuarios en el sistema de login.
 */
class loginTest extends TestCase {
    private $conn; // Simulación de conexión a la base de datos
    private $login; // Instancia de la clase Login


    /**
     * Configuración inicial antes de cada prueba.
     */
    protected function setUp(): void {
        $this->conn = $this->createMock(mysqli::class);// Mock de la conexión a MySQL
        $this->login = new Login($this->conn); // Instancia de Login con la conexión simulada
    }

    /**
     * Prueba para verificar el comportamiento cuando los campos están vacíos.
     */
    public function testCamposVacios() {
        $resultado = $this->login->authenticateUser("", "");
        $this->assertEquals("Todos los campos son obligatorios.", $resultado);
    }

    /**
     * Prueba para verificar el caso en que el usuario no es encontrado en la base de datos.
     */
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

    /**
     * Prueba para verificar el caso en que la contraseña es incorrecta.
     */
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

    /**
     * Prueba para verificar un inicio de sesión exitoso con credenciales correctas.
     */
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
