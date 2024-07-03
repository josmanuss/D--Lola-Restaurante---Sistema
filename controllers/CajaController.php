<?php
class CajaController{

    protected $movimientos;
    
    public function __construct(){

    }   

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION["trabajador"]) && isset($_SESSION["trabajador"]["iCarID"]) && $_SESSION["trabajador"]["iCarID"] === intval("2")) {
            $data["titulo"] = "Administración de caja";
            $data["contenido"] = "views/caja/caja.php";
            require_once TEMPLATE;
        } else {
            require_once ERROR404;
            exit();
        }
    }
    
}