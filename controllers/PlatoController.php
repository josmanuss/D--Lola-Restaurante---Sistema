<?php
    class PlatoController{
        protected $platos;
        protected $categorias;
        
        protected $errores;

        public function __construct(){
            if ( session_status() == PHP_SESSION_NONE){
                session_start();
            }
            require_once "models/CategoriaModel.php";
            require_once "models/PlatoModel.php";
            $this->categorias = new CategoriaModel();
            $this->platos = new PlatoModel();
            $this->errores = [];
        }
        public function index() : void 
        {
            $data["titulo"] = "GESTION DE PLATOS DE RESTAURANTE";
            $data["resultado"] = $this->platos->getPlato();
            $data["categorias"] = $this->categorias->getCategoria();
            $data["contenido"] = "views/platos/platos.php";
            require_once TEMPLATE;
        }

        public function nuevo(): void{
            $data["titulo"] = "REGISTRO DE PLATO NUEVO:";
            $data["categorias"] = $this->categorias->getCategoria();
            $data["contenido"] = "views/platos/plato_nuevo.php";
            require_once TEMPLATE;
        }

        public function guardar() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $categoriaPlato = $_POST['categoriaPlato'] ?? '';
                $txtNombres = $_POST['txtNombres'] ?? '';
                $spinnerPrecio = $_POST['spinnerPrecio'] ?? '';
                $imagen = file_get_contents($_FILES['imagen']["tmp_name"]);
                $spinnerCantidad = $_POST['spinnerCantidad'] ?? '';
                $txtDescripcion = $_POST['txtDescripcion'] ?? '';
                $plato = array(
                    "categoriaPlato" => $categoriaPlato,
                    "txtNombres" => $txtNombres,
                    "spinnerPrecio" => $spinnerPrecio,
                    "imagen" => $imagen,
                    "spinnerCantidad" => $spinnerCantidad,
                    "txtDescripcion" => $txtDescripcion
                );
                if ( $this->platos->save($plato)){
                    header("location: index.php?c=PlatoController");
                }
                else{
                    exit("No se registro el plato");
                }
            }
        }

        public function actualizar() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {



                $idPlato = $_POST['idPlato'] ?? '';
                $categoriaPlato = $_POST['categoriaPlato'] ?? '';
                $txtNombres = $_POST['txtNombres'] ?? '';
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $imagen = file_get_contents($_FILES['imagen']["tmp_name"]);
                } else {
                    $imagen = null; 
                }
                $spinnerPrecio = $_POST['spinnerPrecio'] ?? '';
                $spinnerCantidad = $_POST['spinnerCantidad'] ?? '';
                $txtDescripcion = $_POST['txtDescripcion'] ?? '';
                $plato = array(
                    "idPlato" => $idPlato,
                    "categoriaPlato" => $categoriaPlato,
                    "txtNombres" => $txtNombres,
                    "imagen" => $imagen,
                    "spinnerPrecio" => $spinnerPrecio,
                    "spinnerCantidad" => $spinnerCantidad,
                    "txtDescripcion" => $txtDescripcion
                );
                if ($this->platos->updateProduct($plato)) {
                    header("location: index.php?c=PlatoController");
                } else {
                    exit("No se registró el plato");
                }
            }
        }
        


        public function verPlatoEditar($id){
            $data["titulo"] = "ACTUALIZAR PLATO";
            $data["categorias"] = $this->categorias->getCategoria();
            $data["consulta"] = $this->platos->getPlatoID($id);
            $data["contenido"] = "views/platos/plato_actualizar.php";
            require_once TEMPLATE;
        }

        public function todos(){
            if ($_SERVER["REQUEST_METHOD"] === "POST"){
                $array = $this->platos->getPlatoNombrePrecio();
                if ($array != null ){
                    echo json_encode(["success" => true, "platos" => $array]);
                }
                else{
                    echo json_encode(["success" => false]);
                }
            }
        }

        public function agregarTabla(){
            if ($_SERVER["REQUEST_METHOD"] === "POST"){
                $data = array(
                    'nombre' => $_POST["nombre"],
                    'precio'=> $_POST["precio"]
                );
                $array = $this->platos->getPlatoPorNombrePrecio($data);
                if ($array != null ){
                    echo json_encode(["success" => true, "filas"=> $array]);
                }
                else{
                    echo json_encode(["success" => false]);
                }
            } 
        }

        
    }
?>