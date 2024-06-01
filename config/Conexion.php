<?php 

class Conexion{

    public static function Conexion(){
        $conexion = new mysqli("localhost:3306", "root", "", "d_lola");
        if($conexion->connect_errno){
            die("Error inesperado en la conexión a base de datos: ". $conexion->connect_errno);
        }else{
            return $conexion; 
        }
    }
}



?>