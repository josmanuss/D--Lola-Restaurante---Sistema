<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PlatoModel;
use PDO;
use PDOStatement;
use Mockery;

class PlatoModelTest extends TestCase
{
    private $mockPDO;
    private $mockStatement;
    private $platoModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockPDO = Mockery::mock(PDO::class);
        $this->mockStatement = Mockery::mock(PDOStatement::class);
        
        $this->platoModel = new PlatoModel();
        $this->platoModel->db = $this->mockPDO;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetPlato()
    {
        $expectedData = [
            [
                'cPlaID' => 1,
                'cCatID' => 1,
                'cPlaNombre' => 'Lomo Saltado',
                'cPlaPrecio' => '25.00',
                'cPlaCantidad' => 10,
                'cPlaDescripcion' => 'Plato típico peruano'
            ]
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion FROM platos")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($expectedData);

        $result = $this->platoModel->getPlato();
        
        $this->assertEquals($expectedData, $result);
    }

    public function testGetPlatoID()
    {
        $platoId = 1;
        $expectedData = [
            [
                'cPlaID' => 1,
                'cPlaImagen' => 'imagen_base64',
                'cCatID' => 1,
                'cPlaNombre' => 'Lomo Saltado',
                'cPlaPrecio' => '25.00',
                'cPlaCantidad' => 10,
                'cPlaDescripcion' => 'Plato típico peruano'
            ]
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with("SELECT cPlaID,cPlaImagen, cCatID, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion FROM platos WHERE cPlaID = :cPlaID")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cPlaID', $platoId, PDO::PARAM_INT);
        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($expectedData);

        $result = $this->platoModel->getPlatoID($platoId);
        
        $this->assertEquals(
            array_map(function($plato) {
                $plato['cPlaImagen'] = base64_encode($plato['cPlaImagen']);
                return $plato;
            }, $expectedData),
            $result
        );
    }

    public function testSave()
    {
        $plato = [
            'categoriaPlato' => 1,
            'imagen' => 'imagen_test',
            'txtNombres' => 'Nuevo Plato',
            'spinnerPrecio' => '30.00',
            'spinnerCantidad' => 15,
            'txtDescripcion' => 'Nueva descripción'
        ];

        // La consulta SQL exactamente como está en el modelo
        $expectedQuery = "INSERT INTO platos (cCatID, cPlaImagen ,cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion) VALUES (:categoriaPlato, :imagen ,:txtNombres, :spinnerPrecio, :spinnerCantidad, :txtDescripcion)";

        $this->mockPDO->shouldReceive('prepare')
            ->with(\Mockery::pattern('/^INSERT INTO platos/'))
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':categoriaPlato', $plato['categoriaPlato'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':imagen', $plato['imagen'], PDO::PARAM_LOB)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':txtNombres', $plato['txtNombres'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':spinnerPrecio', $plato['spinnerPrecio'], PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':spinnerCantidad', $plato['spinnerCantidad'], PDO::PARAM_INT)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':txtDescripcion', $plato['txtDescripcion'], PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')
            ->once()
            ->andReturn(true);

        $result = $this->platoModel->save($plato);
        $this->assertTrue($result);
    }

    public function testDelete()
    {
        $datos = ['idEliminar' => 1];

        $this->mockPDO->shouldReceive('prepare')
            ->with("DELETE platos WHERE cPlaID = :cPlaID")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cPlaID', $datos['idEliminar'], PDO::PARAM_INT);
        $this->mockStatement->shouldReceive('execute')->once();

        $this->platoModel->delete($datos);
        $this->assertTrue(true);
    }
}