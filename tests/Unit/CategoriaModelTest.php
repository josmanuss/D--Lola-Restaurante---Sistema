<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use CategoriaModel;
use PDO;
use PDOStatement;
use Mockery;

class CategoriaModelTest extends TestCase
{
    private $mockPDO;
    private $mockStatement;
    private $categoriaModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockPDO = Mockery::mock(PDO::class);
        $this->mockStatement = Mockery::mock(PDOStatement::class);
        
        $this->categoriaModel = new CategoriaModel();
        $this->categoriaModel->db = $this->mockPDO;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetCategoria()
    {
        $expectedData = [
            [
                'cCatID' => 1,
                'cCatImagen' => 'imagen_base64',
                'cCatNombre' => 'Platos Principales'
            ],
            [
                'cCatID' => 2,
                'cCatImagen' => 'imagen_base64_2',
                'cCatNombre' => 'Postres'
            ]
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with("SELECT * FROM categoria")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($expectedData);

        $result = $this->categoriaModel->getCategoria();
        
        $this->assertEquals(
            array_map(function($categoria) {
                $categoria['cCatImagen'] = base64_encode($categoria['cCatImagen']);
                return $categoria;
            }, $expectedData),
            $result
        );
    }

    public function testGetCategoriaID()
    {
        $categoriaId = 1;
        $expectedData = [
            [
                'cCatID' => 1,
                'cCatImagen' => 'imagen_base64',
                'cCatNombre' => 'Platos Principales'
            ]
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with("SELECT * FROM categoria WHERE cCatID = ?")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('execute')
            ->with([$categoriaId])
            ->once();
        $this->mockStatement->shouldReceive('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->andReturn($expectedData);

        $result = $this->categoriaModel->getCategoriaID($categoriaId);
        
        $this->assertEquals(
            array_map(function($categoria) {
                $categoria['cCatImagen'] = base64_encode($categoria['cCatImagen']);
                return $categoria;
            }, $expectedData),
            $result
        );
    }

    public function testIdCategoria()
    {
        $data = ['categoria' => 'Aji de gallina'];
        $expectedId = 1;

        $this->mockPDO->shouldReceive('prepare')
            ->with("SELECT cCatID FROM categoria WHERE cCatNombre = ?")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('execute')
            ->with([$data['categoria']])
            ->once();
        $this->mockStatement->shouldReceive('fetchColumn')
            ->andReturn($expectedId);

        $result = $this->categoriaModel->idCategoria($data);
        
        $this->assertEquals($expectedId, $result);
    }

    public function testGetPlatoIDCategoria()
    {
        $categoriaId = 1;
        $expectedPlatos = [
            [1, 1, 'Lomo Saltado', 10, '25.00'],
            [2, 1, 'Ají de Gallina', 15, '22.00']
        ];

        $this->mockPDO->shouldReceive('prepare')
            ->with(\Mockery::pattern('/^SELECT p.cPlaID, p.cCatID/'))
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':id', $categoriaId, PDO::PARAM_INT);
        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('fetchAll')
            ->with(PDO::FETCH_NUM)
            ->andReturn($expectedPlatos);

        $result = $this->categoriaModel->getPlatoIDCategoria($categoriaId);
        
        $this->assertEquals($expectedPlatos, $result);
    }

    public function testSave()
    {
        $imagen = 'imagen_test';
        $nombre = 'Nueva Categoría';

        $this->mockPDO->shouldReceive('prepare')
            ->with("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (:cCatImagen, :cCatNombre)")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatImagen', $imagen, PDO::PARAM_LOB)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatNombre', $nombre, PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('rowCount')->once()->andReturn(1);

        $result = $this->categoriaModel->save($imagen, $nombre);
        $this->assertTrue($result);
    }

    public function testUpdate()
    {
        $id = 1;
        $imagen = 'imagen_actualizada';
        $nombre = 'Categoría Actualizada';

        $this->mockPDO->shouldReceive('prepare')
            ->with("UPDATE categoria SET cCatImagen = :cCatImagen, cCatNombre = :cCatNombre WHERE cCatID = :cCatID")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatImagen', $imagen, PDO::PARAM_LOB)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatNombre', $nombre, PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatID', $id, PDO::PARAM_INT)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('rowCount')->once()->andReturn(1);

        $result = $this->categoriaModel->update($id, $imagen, $nombre);
        $this->assertTrue($result);
    }

    public function testSaveWithNullImage()
    {
        $imagen = null;
        $nombre = 'Categoría Sin Imagen';

        $this->mockPDO->shouldReceive('prepare')
            ->with("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (:cCatImagen, :cCatNombre)")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindValue')
            ->with(':cCatImagen', null, PDO::PARAM_NULL)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatNombre', $nombre, PDO::PARAM_STR)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('rowCount')->once()->andReturn(1);

        $result = $this->categoriaModel->save($imagen, $nombre);
        $this->assertTrue($result);
    }

    public function testUpdateWithNullImage()
    {
        $id = 1;
        $imagen = null;
        $nombre = 'Categoría Actualizada Sin Imagen';

        $this->mockPDO->shouldReceive('prepare')
            ->with("UPDATE categoria SET cCatImagen = :cCatImagen, cCatNombre = :cCatNombre WHERE cCatID = :cCatID")
            ->andReturn($this->mockStatement);

        $this->mockStatement->shouldReceive('bindValue')
            ->with(':cCatImagen', null, PDO::PARAM_NULL)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatNombre', $nombre, PDO::PARAM_STR)
            ->once();
        $this->mockStatement->shouldReceive('bindParam')
            ->with(':cCatID', $id, PDO::PARAM_INT)
            ->once();

        $this->mockStatement->shouldReceive('execute')->once();
        $this->mockStatement->shouldReceive('rowCount')->once()->andReturn(1);

        $result = $this->categoriaModel->update($id, $imagen, $nombre);
        $this->assertTrue($result);
    }
}