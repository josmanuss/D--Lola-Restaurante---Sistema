<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0"><?php echo $data["titulo"]; ?></h1>
                </div>
                <div class="col-sm-4">
                    <?php if (isset($_SESSION["mensaje"])) : ?>
                        <div id="alert-msj" class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?php echo $_SESSION["mensaje"]; ?></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <script>
                            setTimeout(function() {
                                $('#alert-msj').fadeOut('fast');
                            }, 3000);
                        </script>
                        <?php unset($_SESSION["mensaje"]);
                    endif; ?>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalRegistroCategoria">NUEVO REGISTRO</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tbl-Categorias">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th></th>
                                            <th>Nombre de categoria</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data["resultado"] as $i => $categorias) : ?>
                                            <tr>
                                                <td><?php echo $categorias["cCatID"]; ?></td>                             
                                                <td><img src="data:image/jpeg;base64,<?php echo $categorias["cCatImagen"]; ?>" width="200px" height="100px"></td>
                                                <td><?php echo $categorias["cCatNombre"]; ?></td>
                                                <td>
                                                    <a href="#" class="btn btn-warning updatebtn" data-toggle="modal" data-target="#actualizar" data-indice="<?php echo $i; ?>"><i class="fas fa-user-edit"></i></a>
                                                    <a href="#" class="btn btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $categorias["cCatID"]; ?>"><i class="fas fa-trash"></i></a>
                                                    <a href="" class="btn btn-success viewPlatosBtn" data-toggle="modal" data-target="#modalTablaPlatos" data-recordid="<?php echo $categorias["cCatID"];?>"><i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalRegistroCategoria" tabindex="-1" role="dialog" aria-labelledby="modalRegistroCategoriaLabel" aria-hidden="true">    
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalRegistroPlatoLabel">Formulario de registro de nueva categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nombres</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtNombres" value="<?php echo $_REQUEST["txtNombres"] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <input type="submit" value="Registrar Categoria" class="btn btn-block btn-success" name="btnEnviar">
                                <button type="button" class="btn btn-block btn-secondary mb-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    


    <div class="modal fade" id="actualizar" tabindex="-1" role="dialog" aria-labelledby="actualizarLabel" aria-hidden="true">    
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="actualizarLabel">Actualizar categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">ID</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtIDActualizar" value="<?php echo $_REQUEST["txtIDActualizar"] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nombres</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtNombresActualizar" value="<?php echo $_REQUEST["txtNombresActualizar"] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <input type="submit" value="Actualizar Categoria" class="btn btn-block btn-success" name="btnEnviar">
                                <button type="button" class="btn btn-block btn-secondary mb-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>    

    <div class="modal fade" id="modalTablaPlatos" tabindex="-1" role="dialog" aria-labelledby="modalTablaPlatosLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tbl-Platos-Modal">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contenido de la tabla -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a id="deleteRecordBtn" href="#" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>


<script>
$(document).ready(function() {
    // $('.updateBtn').on('click', function(){
    //     let categorias = <-?php echo json_encode($data["resultado"]); ?>;
    //     var index = $(this).data('indice');
    //     console.log(index);
    //     $('[name="txtIDActualizar"]').val(categorias[index]["cCatID"]);
    //     $('[name="txtNombresActussalizar"]').val(categorias[index]["cCatNombre"]);
    // });

    $('.deleteBtn').on('click', function() {
        var userId = $(this).data('userid'); 
        var deleteUrl = 'index.php?c=CategoriaController&a=eliminar&id=' + userId;
        $('#deleteRecordBtn').attr('href', deleteUrl);
    });

    $('.viewPlatosBtn').on('click', function(){
        var categoriaID = $(this).data("recordid");
        $.ajax({
            url: 'index.php?c=CategoriaController&a=platosCategoria',
            type: 'POST',
            data: {id: categoriaID},
            success: function(response){
                var respuesta = JSON.parse(response);
                if (respuesta.success) {
                    var platos = respuesta.platos;
                    var tbody = $('#tbl-Platos-Modal tbody');
                    tbody.empty();
                    $.each(platos, function(index, plato) {
                        var fila = '<tr>' +
                            '<td>' + plato[0] + '</td>' + // ID
                            '<td>' + plato[3] + '</td>' + // Nombre
                            '<td>' + plato[4] + '</td>' + // Cantidad
                            '<td>' + 'S/.' + plato[5] + '</td>' + // Precio
                            '</tr>';
                        tbody.append(fila);
                    });
                } else {
                    alert("Error: " + respuesta.mensaje);
                }
            },
            error: function(xhr, status, error) {
                alert("Error en la solicitud AJAX: " + error);
            }
        });
    });
});

</script>