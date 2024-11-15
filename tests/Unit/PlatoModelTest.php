<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PlatoModel;
use PDO;

class PlatoModelTest extends TestCase
{
    private $pdo;
    private $platoModel;

    protected function setUp(): void
    {
        parent::setUp();

        // Establecer la conexión a la base de datos real
        $this->pdo = new PDO("mysql:host=localhost;port=3306;dbname=d_lola", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Inicializar la clase modelo y pasarle la conexión PDO real
        $this->platoModel = new PlatoModel();
        $this->platoModel->db = $this->pdo;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testGetPlato()
    {
        // Insertar datos de prueba en la base de datos
        $this->pdo->exec("INSERT INTO platos (cCatID, cPlaImagen, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion) 
                          VALUES (1, NULL, 'Lomo Saltado', '25.00', 10, 'Plato típico peruano')");

        // Ejecutar la función que estamos probando
        $result = $this->platoModel->getPlato();

        // Datos esperados
        $expectedData = [
            [
                'cPlaID' => 1,
                'cCatID' => 1,
                'cPlaNombre' => 'Lomo Saltado',
                'cPlaPrecio' => '25.00',
                'cPlaCantidad' => 10,
                'cPlaDescripcion' => 'Plato típico peruano',
                'cPlaImagen' => null  // La imagen debe ser NULL
            ]
        ];

        // Comparar el resultado con los datos esperados
        $this->assertEquals($expectedData, $result);
    }

    public function testGetPlatoID()
    {
        // Insertar datos de prueba en la base de datos
        $this->pdo->exec("INSERT INTO platos (cCatID, cPlaImagen, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion) 
                          VALUES (1, NULL, 'Lomo Saltado', '25.00', 10, 'Plato típico peruano')");

        $platoId = 1;
        $expectedData = [
            [
                'cPlaID' => 1,
                'cPlaImagen' => null, // La imagen debe ser NULL
                'cCatID' => 1,
                'cPlaNombre' => 'Lomo Saltado',
                'cPlaPrecio' => '25.00',
                'cPlaCantidad' => 10,
                'cPlaDescripcion' => 'Plato típico peruano'
            ]
        ];

        // Ejecutar la función que estamos probando
        $result = $this->platoModel->getPlatoID($platoId);

        // Comparar el resultado con los datos esperados
        $this->assertEquals($expectedData, $result);
    }

    public function testSave()
    {
        // Datos de prueba para insertar
        $plato = [
            'categoriaPlato' => 1,
            'imagen' => null,  // Usamos NULL para la imagen
            'txtNombres' => 'Nuevo Plato',
            'spinnerPrecio' => '30.00',
            'spinnerCantidad' => 15,
            'txtDescripcion' => 'Nueva descripción'
        ];

        // Ejecutar la función de guardar
        $result = $this->platoModel->save($plato);

        // Verificar que la inserción fue exitosa
        $this->assertTrue($result);

        // Verificar que el plato se haya insertado correctamente en la base de datos
        $stmt = $this->pdo->query("SELECT * FROM platos WHERE cPlaNombre = 'Nuevo Plato'");
        $platoInDb = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($platoInDb);
        $this->assertEquals('Nuevo Plato', $platoInDb['cPlaNombre']);
        $this->assertNull($platoInDb['cPlaImagen']);  // Verificar que la imagen sea NULL
    }

    public function testDelete()
    {
        // Insertar un plato para poder eliminarlo
        $this->pdo->exec("INSERT INTO platos (cCatID, cPlaImagen, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion) 
                          VALUES (1, NULL, 'Plato para Eliminar', '30.00', 10, 'Descripción del plato')");

        // Obtener el ID del plato insertado
        $stmt = $this->pdo->query("SELECT cPlaID FROM platos WHERE cPlaNombre = 'Plato para Eliminar'");
        $plato = $stmt->fetch(PDO::FETCH_ASSOC);
        $platoId = $plato['cPlaID'];

        // Ejecutar la función de eliminación
        $result = $this->platoModel->delete(['idEliminar' => $platoId]);

        // Verificar que la eliminación fue exitosa
        $this->assertTrue($result);

        // Verificar que el plato ha sido eliminado de la base de datos
        $stmt = $this->pdo->query("SELECT * FROM platos WHERE cPlaID = $platoId");
        $deletedPlato = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($deletedPlato);  // Verificar que el plato ya no existe
    }
}

?>