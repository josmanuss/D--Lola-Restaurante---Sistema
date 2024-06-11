<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
class TipoDocumentoController{
    protected $tipoDocumento;
    protected $validaciones;
    protected $errores;
    
    public function __construct(){
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        require_once "models/TipoDocumentoModel.php";
        require_once "controllers/ValController.php";
        $this->tipoDocumento = new TipoDocumentoModel();
        $this->validaciones = new ValController();
        $this->errores = [];
    }

    public function index(){
        $data["titulo"] = "GESTION DE TIPO DE DOCUMENTO";
        $data["resultado"] = $this->tipoDocumento->getTipoDocumento();
        $data["contenido"] = "views/tipodocumento/tipo_documento.php";
        require_once TEMPLATE;
    }

    public function registrar(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $nombre = $_POST["nombre"];
            $exitoso = $this->tipoDocumento->save($nombre);
            if ( $exitoso ){
                $_SESSION["mensaje"] = "REGISTRO EXITOSO";
                header("location: index.php?c=TipoDocumentoController");   
            }
            else{
                header("location: index.php?c=TipoDocumentoController");
            }
        }
    }

    public function actualizar(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = $_POST["id"];
            $nombre = $_POST["nombre"];
            $exitoso = $this->tipoDocumento->update($id,$nombre);
            if ( $exitoso ){
                $_SESSION["mensaje"] = "ACTUALIZACION EXITOSA";
                header("location: index.php?c=TipoDocumentoController");
            }
            else{
                header("location: index.php?c=TipoDocumentoController");
            }
        }
    }
}