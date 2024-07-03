<?php
class CargoController{
    protected $cargos;
    
    public function __construct(){
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        require_once "models/CargoModel.php";
        $this->cargos = new CargoModel();
    }

    public function index(): void{
        if ( session_status() === PHP_SESSION_NONE ){
            session_start();
        }
        else if ( $_SESSION["trabajador"]["iCarID"] === intval("1") ){
            $data["titulo"] = "GESTION DE CARGOS";
            $data["resultado"] = $this->cargos->getCargo();
            $data["contenido"] = "views/cargo/cargo.php";
            require_once TEMPLATE;
        }
        else{
            require_once ERROR404;
        }
    }

    public function verCargo(){
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $id = $_POST["id_cargo"];
            $data["consulta"] = $this->cargos->getCargoID(intval($id));
            if ( $data["consulta"] != null ){
                echo json_encode(["success"=>true,"mensaje"=>"Cargo encontrado","cargo"=>$data["consulta"]]);
            }
            else{
                echo json_encode(["success"=>false,"mensaje"=>"Cargo no encontrado"]);
            }
        }
    }

    public function registrarCargo(): void{
        if ( $_SERVER["REQUEST_METHOD"] == "POST"){
            $nombre = isset($_POST["nombreCargo"]) ? $_POST["nombreCargo"] : '';
            if ( $this->cargos->save($nombre) ){
                $_SESSION['mensaje'] = "CARGO REGISTRADO CON EXITO";
                header("location: index.php?c=CargoController");
            }
            else{
                exit("No se pudo registrar correctamente el cargo");
            }
        }
        else{
            exit("Solicitud no permitida");
        }
    }

    public function actualizar(): void{
        if( $_SERVER["REQUEST_METHOD"] === "POST" ){
            $actualizar = array(
                'id' => $_POST["idCargo"],
                'nombre' => $_POST["nombreCargo"]
            );
            if ( $this->cargos->update($actualizar) ){
                $_SESSION['mensaje'] = "CARGO ACTUALIZADO CON EXITO";
                header("location: index.php?=CargoController");
            }
            else{
                exit("ERROR DE ACTUALIZACION");
            }
        }
    }

    public function eliminarCargo($id): void{
        if ( $this->cargos->delete($id) ){
            $_SESSION["mensaje"] = "CARGO ELIMINADO CORRECTAMENTE";
            header("location: index.php?c=CargoController");
        }
        else{
            exit("NO SE PUDO ELIMINAR EL CARGO");
        }
    }

}