<?php
    class PlatoController{
        protected $platos;
        protected $categorias;
        protected $validaciones;
        protected $errores;

        public function __construct(){
            if ( session_status() == PHP_SESSION_NONE){
                session_start();
            }
            require_once "models/CategoriaModel.php";
            require_once "models/PlatoModel.php";
            require_once "controllers/ValController.php";
            $this->categorias = new CategoriaModel();
            $this->platos = new PlatoModel();
            $this->validaciones = new ValController();
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

        public function getPlatoJSON(){
            $data["contenido"] = $this->platos->getPlato();
        }

        public function envioRegistrar(){
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $txtNombrePlato = $_POST["txtNombrePlato"] ?? '';
                $categoriaPlato = $_POST["categoriaPlato"] ?? '';
                $spinCantidadPlato = $_POST["spinCantidadPlato"] ?? '';
                $spinPrecioPlato = $_POST["spinPrecioPlato"] ?? '';
                $txtDescripcion = $_POST["txtDescripcion"] ?? '';
            }
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
                if ( $array != null ){
                    echo json_encode(["success" => true, "filas"=> $array]);
                }
                else{
                    echo json_encode(["success" => false]);
                }
            } 
        }
        
    }
?>