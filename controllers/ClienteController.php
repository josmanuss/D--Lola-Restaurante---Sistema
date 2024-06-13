<?php

class ClienteController{
    protected $cliente;
    
    protected $errores;

    public function __construct()
    {
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        require_once "models/ClienteModel.php";
        $this->cliente = new ClienteModel();
        $this->errores = [];
    }

    public function index()
    {
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        else if ( isset($_SESSION["trabajador"]["iCarID"]) && $_SESSION["trabajador"]["iCarID"] === intval("1")){
            $data["titulo"] = "GESTIÓN DE CLIENTES";
            $data["resultado"] = $this->cliente->getCliente();
            $data["tipoDocumento"] = $this->cliente->getTipoDocumento();
            $data["contenido"] = "views/cliente/cliente.php";
            require_once TEMPLATE;
        }
        else{
            require_once ERROR404;
        }
    }
    
    
    public function registrar()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $registrar = array(
                "nombre" => $_POST["txtNombres"],
                "apellido" => $_POST["txtApellido"],
                "fecha_nac" => $_POST["fechaNacimiento"],
                "tipodocumento" => $_POST["cbTipoDoc"],
                "numerodoc" => $_POST["txtDNI"],
                "correo" => $_POST["txtEmail"],
                "genero" => $_POST["selectGenero"],
                "pais" => $_POST["txtPais"]
            );           

            $exitoso = $this->cliente->save($registrar);
            if ($exitoso){
                $_SESSION["mensaje"] = "CLIENTE ACTUALIZADO CON EXITO";
                header("location: index.php?c=ClienteController");
            }
            else{
                echo "Error de guardado de cliente"; exit();
            }
        } else {
            require_once ERROR404;
            exit();
        }
    }
    
    public function verCliente($id)
    {
        if ($id != null && (isset($_SESSION["trabajador"]["iCarID"]) && $_SESSION["trabajador"]["iCarID"] === intval("1"))) {
            $data["titulo"] = "ACTUALIZAR DATOS DE CLIENTE / Cliente";
            $data["consulta"] = $this->cliente->getClienteID($id);
            $data["tipoDocumento"] = $this->cliente->getTipoDocumento();
            $data["contenido"] = "views/cliente/cliente_actualizar.php";
            require_once TEMPLATE;
        }
        else{
            require_once ERROR404;
            exit();
        }
        
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
                "habilitado" => isset($_POST["chkHabilitado"]) ? $_POST["chkHabilitado"] : "0"
            );           

            $exitoso = $this->cliente->updateUser($actualizar);
            if ($exitoso){
                $_SESSION["mensaje"] = "CLIENTE ACTUALIZADO CON EXITO";
                header("location: index.php?c=ClienteController");
            }
            else{
                echo "Error de actualizacion de cliente"; exit();
            }
        } else {
            require_once ERROR404;
            exit();
        }
    }

    public function validarTipoCliente(){
        if($_SERVER["REQUEST_METHOD"]==="POST"){

            //echo '<pre>';print_r($_POST);'</pre>'; exit();

            $id = $this->cliente->validateCustomerType($_POST["tipoCliente"]);
            if (isset($id)){
                echo json_encode(["success"=>true, "id"=>$id]);
            }
            else{
                echo json_encode(["success"=>false]);
            }
        }
        else{
            echo json_encode(["success"=>false]);
        }
    }


    // public function validarTipoCliente($id){
    //     $id_cliente = $this->cliente->validateCustomerType($id);
    //     if (isset($id)){
    //         echo json_encode(["success"=>true, "id"=>$id_cliente]);
    //     }
    //     else{
    //         echo json_encode(["success"=>false]);
    //     }
    // }



    public function eliminar($id)
    {

        exit();
    }

    public function buscarClienteDNI(){
        if ( $_SERVER["REQUEST_METHOD"] === "POST"){
            $dni = isset($_POST["dni_encontrar"]) ? $_POST["dni_encontrar"] : '';
            $data["resultado"] = $this->cliente->getClienteDNI($dni);
            if ($data["resultado"] != null ){
                echo json_encode(["success" => true, "resultado" => $data["resultado"]]);
            }
            else{
                echo json_encode(["success" => false, "resultado" => "No se ha encontrado un cliente con ese DNI"]);
            }
        }
    }

}
