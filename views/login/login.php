<?php if ( session_status() == PHP_SESSION_NONE ):
   session_start();
   if (isset($_SESSION["trabajador"])): 
      require_once ERROR404;
      exit;
   endif;
endif;
?>     

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link rel="stylesheet" href="views/login/css/bootstrap.css">
   <link rel="stylesheet" type="text/css" href="views/login/css/style.css">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <link rel="shortcut icon" type="image/x-icon" src="https://josmanuss.github.io/Restaurante-D-Lola-Cix/img/logo-dlola2.jpg">
   <title><?php echo $data["titulo"];?></title>
</head>
<body>
   <img class="wave" src="">
   <div class="container">
      <div class="img">
         <img src="views/login/img/logo-d-lola.jpg">
      </div>
      <div class="login-content">
         <!-- <form action="index.php?c=LoginController&a=validarUsuario" method="post" id="login-form" class="login-form" name="login-form"> -->
         <!-- <form id="login-form" class="login-form" name="login-form"> -->
         <form action="index.php?c=LoginController&a=validarUsuario" method="post" id="login-form" class="login-form" name="login-form">
            <img src="views/login/img/avatar.svg">
            <h2 class="title">BIENVENIDO</h2>
            <?php if ( isset($_SESSION["mensajeError"])):?>
               <div id="alert-msj" class="<?php echo $_SESSION["mensajeError"]["clase"];?>" role="alert">
                  <strong><?php echo $_SESSION["mensajeError"]["nombre"]?></strong>
               </div>
               <script>
                   setTimeout(function() {
                       $('#alert-msj').fadeOut('fast');
                   }, 3000);
               </script>
            <?php unset($_SESSION["mensajeError"]); endif; ?>

            <div id="mensaje" style="display:none"></div>
            <div class="input-div one">
               <div class="i">
                  <i class="fas fa-user"></i>
               </div>
               <div class="div">
                  <h5>Correo Electronico</h5>
                  <input id="usuario" type="text" class="input" name="usuario">
               </div>
            </div>
            <div class="input-div pass">
               <div class="i">
                  <i class="fas fa-lock"></i>
               </div>
               <div class="div">
                  <h5>Contraseña</h5>
                  <input type="password" id="input" class="input" name="password">
               </div>
            </div>
            <div class="view">
               <div class="fas fa-eye verPassword" onclick="vista()" id="verPassword"></div>
            </div>
            <input name="btnIngresar" id="btnIngresar" class="btn" type="submit" value="Iniciar Sesion">
            <!-- <button name="btningresar" id="btnIngresar" class="btn" type="submit">Iniciar sesion</button> -->
         </form>
      </div>
   </div>
   <script src="views/login/js/fontawesome.js"></script>
   <script src="views/login/js/main.js"></script>
   <script src="views/login/js/main2.js"></script>
   <script src="views/login/js/jquery.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
   <script src="views/login/js/bootstrap.js"></script>
   <script src="views/login/js/bootstrap.bundle.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {  
         $("#login-form").submit(function(event) {
            var usuario = $("#usuario").val();
            var input = $("#input").val();
            var mensaje = "";
            if (usuario === "" && input === "") {
                  mensaje = "Completar campos";
            } 
            else if (usuario === "") {
                  mensaje = "Completar correo";
            } 
            else if (input === "") {
                  mensaje = "Completar clave";
            }
            if (mensaje !== "") {
                  event.preventDefault();
                  $("#mensaje").css({"display": "block"}).attr("class", "alert alert-warning text-center font-weight-bold").html(mensaje);
            }
         });
      });
   </script>
</body>
</html>