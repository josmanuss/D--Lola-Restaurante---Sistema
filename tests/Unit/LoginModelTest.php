<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use LoginModel;
use PDO;
use PDOStatement;
use Mockery;
use Exception;

class LoginModelTest extends TestCase
{
    private $mockPDO;
    private $mockStatement;
    private $loginModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockPDO = Mockery::mock(PDO::class);
        $this->mockStatement = Mockery::mock(PDOStatement::class);
        
        $this->loginModel = new LoginModel();
        $this->loginModel->db = $this->mockPDO;
    }

    protected function tearDown(): void
    {
        Mockery::close();
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

        $this->mockPDO->shouldReceive('prepare')
            ->with('CALL ActualizarUsuarioActivo(:p_Mandar, :p_Clave)')
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Mandar', $data['correo'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Clave', $claveHasheada, PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('closeCursor')->once();

        // Ejecutar el método - no debería lanzar excepciones
        $this->loginModel->usuarioActivo($data);
        
        // Si llegamos aquí sin excepciones, la prueba es exitosa
        $this->assertTrue(true);
    }

    public function testDesactivarUsuario()
    {
        $data = [
            'correo' => 'usuario@test.com',
            'clave' => 'password123'
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with('CALL DesactivarUsuario(:p_Mandar, :p_Clave)')
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Mandar', $data['correo'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Clave', $data['clave'], PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('closeCursor')->once();

        // Ejecutar el método
        $this->loginModel->desactivarUsuario($data);
        
        // Si llegamos aquí sin excepciones, la prueba es exitosa
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

        $this->mockPDO->shouldReceive('prepare')
            ->with('CALL IniciarSesionTrabajador(:p_Mandar, :p_Clave)')
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Mandar', $data['correo'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Clave', $claveHasheada, PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($expectedUser);
        $this->mockStatement->shouldReceive('closeCursor')->once();

        $result = $this->loginModel->validarDatosSesion($data);
        
        $this->assertEquals($expectedUser, $result);
    }

    public function testValidarDatosSesionFallido()
    {
        $data = [
            'correo' => 'noexiste@test.com',
            'clave' => 'wrongpassword'
        ];

        $claveHasheada = hash('SHA256', $data['clave']);

        $this->mockPDO->shouldReceive('prepare')
            ->with('CALL IniciarSesionTrabajador(:p_Mandar, :p_Clave)')
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Mandar', $data['correo'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':p_Clave', $claveHasheada, PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('fetch')
            ->with(PDO::FETCH_ASSOC)
            ->andReturn(false);
        $this->mockStatement->shouldReceive('closeCursor')->once();

        $result = $this->loginModel->validarDatosSesion($data);
        
        $this->assertNull($result);
    }

    public function testValidarDatosSesionErrorPreparacion()
    {
        $data = [
            'correo' => 'usuario@test.com',
            'clave' => 'password123'
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with('CALL IniciarSesionTrabajador(:p_Mandar, :p_Clave)')
            ->andReturn(false);

        $this->mockPDO->shouldReceive('errorInfo')
            ->andReturn([null, null, 'Error de preparación']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error al preparar la consulta: Error de preparación');

        $this->loginModel->validarDatosSesion($data);
    }
}