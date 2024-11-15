<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use CategoriaModel;
use PDO;
use PDOStatement;
use Mockery;

class CategoriaModelTest extends TestCase
{
    private $pdo;
    private $mockStatement;
    private $categoriaModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Conectar a una base de datos real (por ejemplo, MySQL)
        $this->pdo = new PDO("mysql:host=localhost;port=3306;dbname=d_lola","root","",[ 
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        
        // Inicializar la clase modelo y pasarle la conexión PDO real
        $this->categoriaModel = new CategoriaModel();
        $this->categoriaModel->db = $this->pdo;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testGetCategoria()
    {
        // Insertar datos de prueba en la base de datos usando NULL en lugar de 'null' o ''
        $this->pdo->exec("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (NULL, 'Platos Principales')");
        $this->pdo->exec("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (NULL, 'Postres')");

        // Ejecutar la función que estamos probando
        $result = $this->categoriaModel->getCategoria();

        // Datos esperados, ahora con NULL en lugar de 'null' o imágenes en base64
        $expectedData = [
            [
                'cCatID' => 1,
                'cCatImagen' => null,  // Imagen debe ser NULL
                'cCatNombre' => 'Platos Principales'
            ],
            [
                'cCatID' => 2,
                'cCatImagen' => null,  // Imagen debe ser NULL
                'cCatNombre' => 'Postres'
            ]
        ];

        // Comparar el resultado con los datos esperados
        $this->assertEquals($expectedData, $result);
    }

    public function testGetCategoriaID()
    {
        // Insertar datos de prueba usando NULL para la imagen
        $this->pdo->exec("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (NULL, 'Platos Principales')");

        // Obtener el ID de la categoría
        $categoriaId = 1;
        $expectedData = [
            [
                'cCatID' => 1,
                'cCatImagen' => null,  // Imagen debe ser NULL
                'cCatNombre' => 'Platos Principales'
            ]
        ];

        $result = $this->categoriaModel->getCategoriaID($categoriaId);

        // Verificar que el resultado sea el esperado
        $this->assertEquals($expectedData, $result);
    }

    public function testIdCategoria()
    {
        // Insertar datos de prueba con NULL en la imagen
        $this->pdo->exec("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (NULL, 'Platos Principales')");

        $data = ['categoria' => 'Platos Principales'];
        $expectedId = 1;

        $result = $this->categoriaModel->idCategoria($data);

        // Verificar que el ID devuelto es el esperado
        $this->assertEquals($expectedId, $result);
    }

    public function testGetPlatoIDCategoria()
    {
        // Insertar una categoría de prueba
        $categoriaId = 1;
        $this->pdo->exec("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (NULL, 'Platos Principales')");

        $expectedPlatos = [
            [1, 1, 'Lomo Saltado', 10, '25.00'],
            [2, 1, 'Ají de Gallina', 15, '22.00']
        ];

        // Insertar platos de prueba
        $stmt = $this->pdo->prepare("INSERT INTO plato (cPlaID, cCatID, cPlaNombre, cPlaStock, cPlaPrecio) VALUES (1, 1, 'Lomo Saltado', 10, 25.00)");
        $stmt->execute();

        $stmt = $this->pdo->prepare("INSERT INTO plato (cPlaID, cCatID, cPlaNombre, cPlaStock, cPlaPrecio) VALUES (2, 1, 'Ají de Gallina', 15, 22.00)");
        $stmt->execute();

        // Ejecutar la función de prueba
        $result = $this->categoriaModel->getPlatoIDCategoria($categoriaId);

        $this->assertEquals($expectedPlatos, $result);
    }

    public function testSave()
    {
        $imagen = 'imagen_test';
        $nombre = 'Nueva Categoría';

        // Ejecutar la función de guardar
        $result = $this->categoriaModel->save($imagen, $nombre);

        // Verificar que la inserción fue exitosa
        $this->assertTrue($result);

        // Verificar que la categoría se haya insertado correctamente
        $stmt = $this->pdo->query("SELECT * FROM categoria WHERE cCatNombre = 'Nueva Categoría'");
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($categoria);
        $this->assertEquals('Nueva Categoría', $categoria['cCatNombre']);
    }

    public function testUpdate()
    {
        // Insertar una categoría para actualizar
        $this->pdo->exec("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES ('imagen_base64', 'Platos Principales')");
        $id = 1;
        $imagen = 'imagen_actualizada';
        $nombre = 'Categoría Actualizada';

        // Ejecutar la función de actualización
        $result = $this->categoriaModel->update($id, $imagen, $nombre);

        // Verificar que la actualización fue exitosa
        $this->assertTrue($result);

        // Verificar que los datos se han actualizado en la base de datos
        $stmt = $this->pdo->query("SELECT * FROM categoria WHERE cCatID = $id");
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Categoría Actualizada', $categoria['cCatNombre']);
    }

    public function testSaveWithNullImage()
    {
        $imagen = null;  // Pasar NULL como valor para imagen
        $nombre = 'Categoría Sin Imagen';

        // Ejecutar la función de guardar con imagen null
        $result = $this->categoriaModel->save($imagen, $nombre);

        // Verificar que la inserción fue exitosa
        $this->assertTrue($result);

        // Verificar que la categoría se haya insertado correctamente con imagen null
        $stmt = $this->pdo->query("SELECT * FROM categoria WHERE cCatNombre = 'Categoría Sin Imagen'");
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($categoria);
        $this->assertEquals('Categoría Sin Imagen', $categoria['cCatNombre']);
        $this->assertNull($categoria['cCatImagen']);  // Verificar que la imagen sea NULL
    }

    public function testUpdateWithNullImage()
    {
        $id = 1;
        $imagen = null;  // Pasar NULL como valor para imagen
        $nombre = 'Categoría Actualizada Sin Imagen';

        // Ejecutar la función de actualización con imagen null
        $result = $this->categoriaModel->update($id, $imagen, $nombre);

        // Verificar que la actualización fue exitosa
        $this->assertTrue($result);

        // Verificar que los datos se han actualizado en la base de datos
        $stmt = $this->pdo->query("SELECT * FROM categoria WHERE cCatID = $id");
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Categoría Actualizada Sin Imagen', $categoria['cCatNombre']);
        $this->assertNull($categoria['cCatImagen']);  // Verificar que la imagen sea NULL
    }
}

?>