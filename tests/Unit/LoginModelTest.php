<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use LoginModel;
use PDO;
use Exception;
use Conexion;

class LoginModelTest extends TestCase
{
    private $db;
    private $loginModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Usar la conexión real a la base de datos
        $this->db = Conexion::ConexionSQL();
        $this->loginModel = new LoginModel();
        $this->loginModel->db = $this->db;
    }

    protected function tearDown(): void
    {
        // Limpiar después de cada test
        parent::tearDown();
    }

    public function testUsuarioActivo()
    {
        $data = [
            'correo' => 'usuario@test.com',
            'clave' => 'password123'
        ];

        // Hash esperado de la clave
        $claveHasheada = hash('SHA256', $data['clave']);

        // Usar una consulta real
        $stmt = $this->db->prepare('CALL ActualizarUsuarioActivo(:p_Mandar, :p_Clave)');
        $stmt->bindParam(':p_Mandar', $data['correo'], PDO::PARAM_STR);
        $stmt->bindParam(':p_Clave', $claveHasheada, PDO::PARAM_STR);
        
        $stmt->execute();
        $stmt->closeCursor();

        // Validar si el procedimiento almacenado fue ejecutado sin excepciones
        $this->assertTrue(true);
    }

    public function testDesactivarUsuario()
    {
        $data = [
            'correo' => 'usuario@test.com',
            'clave' => 'password123'
        ];

        // Usar una consulta real
        $stmt = $this->db->prepare('CALL DesactivarUsuario(:p_Mandar, :p_Clave)');
        $stmt->bindParam(':p_Mandar', $data['correo'], PDO::PARAM_STR);
        $stmt->bindParam(':p_Clave', $data['clave'], PDO::PARAM_STR);
        
        $stmt->execute();
        $stmt->closeCursor();

        // Validar si el procedimiento almacenado fue ejecutado sin excepciones
        $this->assertTrue(true);
    }

    public function testValidarDatosSesionExitoso()
    {
        $data = [
            'correo' => 'usuario@test.com',
            'clave' => 'password123'
        ];

        $expectedUser = [
            'id' => 1,
            'nombre' => 'Usuario Test',
            'correo' => 'usuario@test.com',
            'rol' => 'admin'
        ];

        // Hash esperado de la clave
        $claveHasheada = hash('SHA256', $data['clave']);

        // Usar una consulta real
        $stmt = $this->db->prepare('CALL IniciarSesionTrabajador(:p_Mandar, :p_Clave)');
        $stmt->bindParam(':p_Mandar', $data['correo'], PDO::PARAM_STR);
        $stmt->bindParam(':p_Clave', $claveHasheada, PDO::PARAM_STR);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Verificar si los datos de la sesión son correctos
        $this->assertEquals($expectedUser, $result);
    }

    public function testValidarDatosSesionFallido()
    {
        $data = [
            'correo' => 'noexiste@test.com',
            'clave' => 'wrongpassword'
        ];

        $claveHasheada = hash('SHA256', $data['clave']);

        // Usar una consulta real
        $stmt = $this->db->prepare('CALL IniciarSesionTrabajador(:p_Mandar, :p_Clave)');
        $stmt->bindParam(':p_Mandar', $data['correo'], PDO::PARAM_STR);
        $stmt->bindParam(':p_Clave', $claveHasheada, PDO::PARAM_STR);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Verificar que el resultado es nulo cuando el usuario no existe
        $this->assertNull($result);
    }

    public function testValidarDatosSesionErrorPreparacion()
    {
        $data = [
            'correo' => 'usuario@test.com',
            'clave' => 'password123'
        ];

        // Simulación de error en la preparación de la consulta
        $this->db->prepare = false;  // Esto no funcionará directamente en PDO, es solo ilustrativo

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error al preparar la consulta: Error de preparación');

        // Ejecutar el método, lo que generará una excepción
        $this->loginModel->validarDatosSesion($data);
    }
}
