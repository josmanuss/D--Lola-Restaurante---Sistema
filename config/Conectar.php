<?php
    //Coenexion de base de datos


    

    $mysqli = new mysqli("localhost:3306","root","","examen_2");

    if($mysqli->connect_error){
        die("Erroe en la conexión" .$mysqli->connect_error);
    }
    
?>