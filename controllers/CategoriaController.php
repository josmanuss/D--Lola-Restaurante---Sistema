<?php
    class CategoriaController{
        protected $categorias;
        
        protected $errores;
        public function __construct(){
            if ( session_status() == PHP_SESSION_NONE){
                session_start();
            }
            require_once "models/CategoriaModel.php";
            $this->categorias = new CategoriaModel();
            $this->errores = [];
        }
        public function index() : void 
        {
            $data["titulo"] = "GESTION DEE CATEGORIAS DE APERITIVOS";
            $data["resultado"] = $this->categorias->getCategoria();
            $data["contenido"] = "views/categorias/categoria.php";
            require_once TEMPLATE;
        }

        public function registrar(): void
        {
            if ($_SERVER["REQUEST_METHOD"] === "POST"){
                $nombre = $_POST["nombres"];
                $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
                if ($this->categorias->save($imagen, $nombre)){
                    header("Location: index.php?c=CategoriaController");
                }
                else{
                    echo "ERROR DE REGISTRO"; exit();
                }
            }
        }

        public function actualizar()
        {
            if ($_SERVER["REQUEST_METHOD"] === "POST"){
                $id = $_POST["id-categoria"];
                $nombre = $_POST["txtNombres"];
                $nombreimagen = $_FILES['imagen']['name'];
                $tipoimagen = $_FILES['imagen']['type'];
                $imagen = file_get_contents($_FILES['imagenCategoria']['tmp_name']);
                if ($this->categorias->update($id,$imagen, $nombre)){
                    header("Location: index.php?c=CategoriaController");
                }
                else{
                    echo "ERROR DE ACTUALIZACION"; exit();
                }
            }
        }
        
        public function platosCategoria(): void {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = $_POST["id"];
                $data["resultado"] = $this->categorias->getPlatoIDCategoria($id);
                if (isset($data["resultado"])) {
                    echo json_encode(["success" => true, "mensaje" => "PLATOS ENCONTRADOS", "platos" => $data["resultado"]]);
                } else {
                    echo json_encode(["success" => false, "mensaje" => "No se encontraron platos para esta categoría"]);
                }
            }
        }

        public function verCategoria(){
            if($_SERVER["REQUEST_METHOD"] === "POST"){
                $id_categoria = $_POST["record_id"];
                $data["consultar"] = $this->categorias->getCategoriaID($id_categoria);
                $data["titulo"] = "Actualizar Categoria";
                $data["contenido"] = "views/categorias/categoria_actualizar.php";
                require_once TEMPLATE;
            }
            else{
                require_once ERROR404;
            }
        }

    }