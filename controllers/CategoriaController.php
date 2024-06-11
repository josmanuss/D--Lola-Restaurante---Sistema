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
            $data["titulo"] = "GESTION DEE CATEGORIAS DE APERITIVOS";
            $data["resultado"] = $this->categorias->getCategoria();
            //echo '<pre>';print_r($data);'</pre>'; exit();
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

        public function verCategoria(){
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                $id_categoria = $_POST["id_categoria"];
                $data["consultar"] = $this->categorias->getCategoriaID($id_categoria);
                $data["contenido"] = "views/categorias/categoria_actualizar.php";
                require_once TEMPLATE;
            }
            else{
                require_once ERROR404;
            }
        }

        public function actualizar()
        {
            echo "xd";
            echo "tu vieja"; 
            echo "phpmuyadmin";
            echo "holaaaa";
        }
        
    }