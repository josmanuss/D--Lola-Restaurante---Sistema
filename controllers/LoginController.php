<?php
class LoginController{
    public $login;
    public $errores;
    public $validaciones;
//hola mundo
    public function __construct(){
        require_once "models/LoginModel.php";
        $this->login = new LoginModel();
    }

    public function index() : void{
        $data["titulo"] = "Inicio de sesión";
        require "views/login/login.php";
    }

    public function validarUsuario() : void {
        try{
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = isset($_POST["usuario"]) ? $_POST["usuario"] : "";
                $clave = isset($_POST["password"]) ? $_POST["password"] : "";
                $data = ["autenticar" => ["correo" => $email, "clave" => $clave]];
                $datos["tE"] = $this->login->validarDatosSesion($data["autenticar"]);
                if ( session_status() == PHP_SESSION_NONE){
                    session_start();
                }
                $_SESSION["trabajador"] = $datos["tE"];
                $this->login->usuarioActivo($data["autenticar"]);
                $route = match ($_SESSION["trabajador"]["iCarID"]) {
                    "1" => "Administrador",
                    "2" => "Pedido",
                };
                header("location: index.php?c={$route}Controller");
            }
        } catch (Exception $e) {
            if ( session_status() == PHP_SESSION_NONE){
                session_start();
            }
            $_SESSION["mensajeError"] = array(
                "clase" => "alert alert-danger text-center font-weight-bold",
                "nombre" => $e->getMessage()
            );
            header("location: index.php");
        } 
    }
    

    public function bloquearUsuario( $data ) : void {
        $sql = $this->login->db->prepare("CALL BloquearUsuario(?,?)");
        $sql->bind_param("ss",$data["correo"],$data["clave"]);
        $sql->execute();
    }

    public function salir(): void{
        session_start();
        $data = array(
            "correo" => $_SESSION["trabajador"]["cPerCorreo"],
            "clave" => $_SESSION["trabajador"]["cUserClave"]
        );
        $this->login->desactivarUsuario($data);
        session_destroy();
        header('Location: index.php');
    }
    
}