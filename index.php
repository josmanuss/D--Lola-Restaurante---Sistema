<?php 

date_default_timezone_set("America/Lima");
require_once "config/Conexion.php";
require_once "config/config.php";
require_once "routes/routes.php";
if(isset($_GET["c"])){
    $controlador = validarControlador($_GET["c"]);
    if(isset($_GET["a"])){ 
        if(isset($_GET["id"])){ 
            validarAccion($controlador, $_GET['a'], $_GET['id']);
        }else{
            validarAccion($controlador, $_GET['a']);
        }
    }else{
        validarAccion($controlador, ACCION_DEFAULT);
    }
}else{
    $control = validarControlador(CONTROLADOR_DEFAULT);
    $accionTMP = ACCION_DEFAULT;
    $control->$accionTMP();
}
// asdfasdf