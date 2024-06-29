<?php 
if ( session_status() == PHP_SESSION_NONE ):
  session_start();
endif;

if ( !isset($_SESSION["trabajador"]) ):
  header("location: index.php");
  exit();
endif;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>D' Lola Restaurante &copy;</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="views/dashboard/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="views/dashboard/dist/css/adminlte.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css" />
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="views/dashboard/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="views/dashboard/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="views/dashboard/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="views/dashboard/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="views/dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="views/dashboard/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="views/dashboard/plugins/summernote/summernote-bs4.min.css">
  <link rel="shortcut icon" type="image/x-icon" src="https://josmanuss.github.io/Restaurante-D-Lola-Cix/img/logo-dlola2.jpg">
  <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center"> -->
    <!-- <img class="animation__shake" src="views/dashboard/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60"> -->
  <!-- </div> -->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>


    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #2a0000;">
    
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="views/dashboard/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">D' Lola Restaurante &copy;</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 row justify-content-center">
        <div class="image col-12 text-center">
          <img src="views/dashboard/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info col-12 text-center">
          <h5 class="text-white">Bienvenido:</h1>
          <a href="index.php?c=TrabajadorController&a=verPerfil" class="d-block"><?php echo $_SESSION["trabajador"]["cUserNUsuario"]?></a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <?php if ( $_SESSION["trabajador"]["iCarID"] == intval("1")) { ?>
        <nav class="mt-2" id="admin-menubar">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">Menú de navegación</li>
            <li class="nav-item">
              <a href="index.php?c=AdministradorController" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
              <a href="index.php?c=TrabajadorController&a=verPerfil" class="nav-link">
                <span class="fas fa-user nav-icon"></span>
                <p>Perfil</p>
              </a>
            </li> 
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                  <p>Tablas<i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" class="nav-link small m-1">
                    <i class="nav-icon fas fa-user-circle"></i>
                      <p>Personas<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item" id="cliente-menu">
                      <a href="index.php?c=ClienteController" class="nav-link small m-1">
                        <i class="fas fa-user nav-icon"></i>
                        <p>Clientes</p>
                      </a>
                    </li>
                    <li class="nav-item" id="trabajador-menu">
                      <a href="index.php?c=TrabajadorController" class="nav-link small m-1">
                        <i class="fas fa-hard-hat nav-icon"></i>
                        <p>Trabajadores</p>
                      </a>
                    </li>
                    <li class="nav-item" id="tipodocumento-menu">
                      <a href="index.php?c=TipoDocumentoController" class="nav-link small m-1">
                        <i class="fas fa-passport nav-icon"></i>
                        <p>Tipo de Documento</p>
                      </a>
                    </li>
                    <li class="nav-item" id="cargo-menu">
                      <a href="index.php?c=CargoController" class="nav-link small m-1">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>Cargo - Trabajador</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link small m-1">
                    <i class="nav-icon fas fa-utensils"></i>
                      <p>Platos - Ventas<i class="right fas fa-angle-left"></i></p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="index.php?c=CategoriaController" class="nav-link small m-1">
                        <i class="fas fa-shopping-cart nav-icon"></i>
                        <p>Categorias</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="index.php?c=PlatoController" class="nav-link small m-1">
                        <i class="fas fa-wine-glass nav-icon"></i>
                        <p>Platos</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="index.php?c=VentaController" class="nav-link small m-1">
                        <i class="fas fa-shopping-bag nav-icon"></i>
                        <p>Ventas</p>
                      </a>
                    </li>
                  </ul>
                </li>                  
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-utensils"></i>
                <p>Informacion<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item small m-1">
                  <a href="#" class="nav-link">
                    <p>Sobre la empresa.</p>
                  </a>
                </li>
                <li class="nav-item small m-1">
                  <a href="https://josmanuss.github.io/Restaurante-D-Lola-Cix/" target="_blank" class="nav-link">
                    <p>Visita nuestro sitio web</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <hr style="border-bottom:1px solid #4f5962;">
            </li>
            <li class="nav-item menu-open ">
              <a href="index.php?c=LoginController&a=salir" class="nav-link">
                <i class="nav-icon  fas fa-sign-out-alt"></i>
                <p>Cerrar sesión</p>
              </a>
            </li>
          </ul>
        </nav>
        <?php } else if ( $_SESSION["trabajador"]["iCarID"] == intval("2")){  ?>
          <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
          <li class="nav-header">Menú de navegación</li>
            <li class="nav-item">
              <a href="index.php?c=TrabajadorController&a=verPerfil" class="nav-link">
                <span class="fas fa-user nav-icon"></span>
                <p>Perfil</p>
              </a>
            </li> 
            <li class="nav-item">
              <a href="index.php?c=CajaController" class="nav-link">
                <span class="fas fa-cash-register nav-icon"></span>
                <p>Caja</p>
              </a>
            </li> 
            <li class="nav-item">
              <a href="index.php?c=PedidoController" class="nav-link">
                <i class="fas fa-shopping-bag nav-icon"></i>
                <p>Pedidos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?c=VentaController" class="nav-link">
                <i class="fas fa-shopping-bag nav-icon"></i>
                <p>Ventas</p>
              </a>
            </li>
            <li class="nav-item">
              <hr style="border-bottom:1px solid #4f5962;">
            </li>
            <li class="nav-item menu-open ">
              <a href="index.php?c=LoginController&a=salir" class="nav-link">
                <i class="nav-icon  fas fa-sign-out-alt"></i>
                <p>
                  Cerrar sesión
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <?php } else{ ?>
          <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
          <li class="nav-header">Menú de navegación</li>
            <li class="nav-item">
              <a href="index.php?c=TrabajadorController&a=verPerfil" class="nav-link">
                <span class="fas fa-user nav-icon"></span>
                <p>Perfil</p>
              </a>
            </li> 
            <li class="nav-item">
              <a href="index.php?c=PedidoController" class="nav-link">
                <i class="fas fa-shopping-bag nav-icon"></i>
                  <p>Nuevo Pedido</p>
              </a>
            </li>
            <li class="nav-item">
              <hr style="border-bottom:1px solid #4f5962;">
            </li>
            <li class="nav-item menu-open ">
              <a href="index.php?c=LoginController&a=salir" class="nav-link">
                <i class="nav-icon  fas fa-sign-out-alt"></i>
                <p>
                  Cerrar sesión
                </p>
              </a>
            </li>
          </ul>
        </nav>          
        <?php } ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->

  <?php require_once $data["contenido"]; ?>

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      D' Lola Restaurante &copy;
    </div>
    <strong>Copyright &copy; D' Lola Restaurante</strong> Todos los derechos reservados 2024 &copy;.
  </footer>

</div>

<script src="views/dashboard/plugins/jquery/jquery.min.js"></script>
<script src="views/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="views/dashboard/dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#tbl-Categorias, #tbl-Clientes, #tbl-Trabajador, #tbl-Platos, #tbl-Cargos, #tbl-TipoDocumento, #tbl-VentasMozo, #tbl-VentasCajero, #tbl-VentasAdmin, #tbl-PedidosAdmin').DataTable(
      {
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
      }
    );
    window.addEventListener('beforeunload', function (e) {
      window.location.href = 'index.php?c=LoginController&a=salir'
    });




  });
</script>

</body>
</html>
