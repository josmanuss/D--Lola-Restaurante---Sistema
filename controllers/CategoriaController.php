<?php
    class CategoriaController{
        protected $categorias;
        protected $validaciones;
        protected $errores;
        public function __construct(){
            if ( session_status() == PHP_SESSION_NONE){
                session_start();
            }
            require_once "models/CategoriaModel.php";
            require_once "controllers/ValController.php";
            $this->categorias = new CategoriaModel();
            $this->validaciones = new ValController();
            $this->errores = [];
        }
        public function index() : void 
        {
            $data["titulo"] = "GESTION DE CATEGORIAS DE APERITIVOS";
            $data["resultado"] = $this->categorias->getCategoria();
            echo '<pre>';print_r($data);'</pre>'; exit();
            $data["contenido"] = "views/categorias/categoria.php";
            require_once TEMPLATE;
        }
        public function platosCategoria(): void {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = $_POST["id"];
                $data["resultado"] = $this->categorias->getPlatoIDCategoria($id);
                if ($data["resultado"] != null) {
                    echo json_encode(["success" => true, "mensaje" => "PLATOS ENCONTRADOS", "platos" => $data["resultado"]]);
                } else {
                    echo json_encode(["success" => false, "mensaje" => "No se encontraron platos para esta categoría"]);
                }
            }
        }
        
        public function actualizar()
        {
            echo "tu vieja"; 

            echo "hola";
        }
        
    }
?>