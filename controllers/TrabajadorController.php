<?php

class TrabajadorController{
    protected $trabajador;
    
    protected $errores;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once "models/TrabajadorModel.php";
        $this->trabajador = new TrabajadorModel();
        
        $this->errores = [];
    }

    public function index()
    {
        $data["titulo"] = "GESTIÓN DE TRABAJADORES";
        $data["resultado"] = $this->trabajador->getTrabajador();
        $data["cargo"] = $this->trabajador->getCargo();
        $data["tipoDocumento"] = $this->trabajador->getTipoDocumento();
        $data["contenido"] = "views/trabajador/trabajador.php";
        require_once TEMPLATE;
    }

    public function nuevo()
    {
        $data["titulo"] = "FORMULARIO DE REGISTRO DE TRABAJADOR";
        $data["contenido"] = "views/trabajador/trabajador_nuevo.php";
        require_once TEMPLATE;
    }

    public function verPerfil(){
        $data["titulo"] = "Perfil de usuario";
        $data["contenido"] = "views/trabajador/trabajador_perfil.php";
        require_once TEMPLATE;
    }
    public function registrar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["btnEnviar"])) {
            $registrar = array(
                "nombre" => $_POST["txtNombres"] ?? "",
                "apellido" => $_POST["txtApellido"] ?? "",
                "fecha_nac" => $_POST["fechaNacimiento"] ?? "",
                "tipodocumento" => $_POST["cbTipoDoc"] ?? "",
                "numerodoc" => $_POST["txtDNI"] ?? "",
                "correo" => $_POST["txtEmail"] ?? "",
                "genero" => $_POST["selectGenero"] ?? "",
                "pais" => $_POST["txtPais"] ?? "",
                "cargo" => $_POST["selectCargo"] ?? "",
                "n_usuario" => $_POST["txtNUsuario"] ?? "",
                //"clave" => password_hash($_POST["txtContra"], PASSWORD_DEFAULT) ?? "",
                "clave" => hash("SHA256",$_POST["txtContra"]) ?? "",
                "sueldo" => $_POST["numberSueldo"] ?? ""
            );
    
            $exitoso = $this->trabajador->save($registrar);
            if ($exitoso) {
                $_SESSION["mensaje"] = "TRABAJADOR REGISTRADO CON ÉXITO!";
                header("location: index.php?c=TrabajadorController");
            } else {
                echo "ERROR DE REGISTRO"; exit();
            }
        } else {
            require_once ERROR404;
            exit();
        }
    }

    public function verTrabajador($id)
    {
        $data["titulo"] = "ACTUALIZAR DATOS DE CLIENTE / trabajador";
        $data["resultado_consulta"] = $this->trabajador->getTrabajadorID($id);
        $data["cargo"] = $this->trabajador->getCargo();
        $data["tipoDocumento"] = $this->trabajador->getTipoDocumento();
        $data["contenido"] = "views/trabajador/trabajador_actualizar.php";
        require_once TEMPLATE;
    }

    public function actualizar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $actualizar = array(
                "idpersona" => $_POST["idPersona"],
                "nombre" => $_POST["txtNombres"],
                "apellido" => $_POST["txtApellido"],
                "fecha_nac" => $_POST["fechaNacimiento"],
                "tipodocumento" => $_POST["cbTipoDoc"],
                "numerodoc" => $_POST["txtDNI"],
                "correo" => $_POST["txtEmail"],
                "genero" => $_POST["selectGenero"],
                "pais" => $_POST["txtPais"],
                "cargo" => $_POST["selectCargo"],
                "n_usuario" => $_POST["txtNUsuario"],
                "clave" => hash("SHA256",$_POST["txtContra"]) ?? "",
                "sueldo" => $_POST["numberSueldo"]
            );

            $exitoso = $this->trabajador->updateWorker($actualizar);
            if ( $exitoso ){
                $_SESSION["mensaje"] = "TRABAJADOR ACTUALIZADO CON EXITO!";
                header("location: index.php?c=TrabajadorController");
            }
            else{
                echo "ERROR DE REGISTRO"; exit();
            }
        } else {
            require_once ERROR404;
            exit();
        }
    }

    public function eliminarTrabajador($id): void{
        $exitoso = $this->trabajador->delete($id);
        if ( $exitoso == TRUE ){
            $_SESSION["mensaje"] = "TRABAJADOR ELIMINADO CORRECTAMENTE";
            header("location: index.php?c=TrabajadorController");
        }
        else{
            echo "NO SE PUDO ELIMINAR EL TRABAJADOR"; 
            exit();
        }

    }

    public function mostrarCantidadTrabajadorCargo(){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $trabajadores = $this->trabajador->reportWorker();
            if ( isset($trabajadores)){
                echo json_encode(["success"=>true, "trabajadores"=>$trabajadores]);
            }
            else{
                echo json_encode(["success"=>false]);
            }
        }
    }
}
