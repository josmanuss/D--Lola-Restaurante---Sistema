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
                            <a href="" class="btn btn-danger"><i class="fas fa-file-pdf mr-1"></i>Generar reportes</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#registroModal">NUEVO REGISTRO</a>
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
                                <table class="table table-striped table-hover" id="tbl-TipoDocumento">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data["resultado"] as $i => $tipoDocumento) : ?>
                                        <tr>
                                            <td><?php echo $tipoDocumento["iTipoDocID"]; ?></td>
                                            <td><?php echo $tipoDocumento["tTipoDocNombre"]; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-warning botonActualizar" data-toggle="modal" data-target="#ActualizarModal" data-index="<?php echo $i; ?>"><i class="fas fa-user-edit"></i></a>
                                                <a href="#" class="btn btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $tipoDocumento["iTipoDocID"]; ?>"><i class="fas fa-trash"></i></a>
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


    <div class="modal fade" id="registroModal" tabindex="-1" role="dialog" aria-labelledby="registroModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Registro de Tipo de Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="registroForm" action="index.php?c=TipoDocumentoController&a=registrar" method="POST" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tipoDocumento">Tipo de Documento:</label>
                            <input type="text" value="" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el tipo de documento">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary btnGuardar">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ActualizarModal" tabindex="-1" role="dialog" aria-labelledby="ActualizarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ActualizarModalLabel">Actualizar de Tipo de Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="ActualizarForm" action="index.php?c=TipoDocumentoController&a=actualizar" method="POST" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="idTipoDocumento">ID:</label>
                            <input type="hidden" class="form-control" id="id" name="id">
                            <label for="tipoDocumento">Tipo de Documento:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el tipo de documento">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btnActualizar">Actualizar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<script type="text/javascript">
    $(document).ready(function(){

        $("#registroForm").submit(function(event){
            var nombre = $(this).find("[name='nombre']").val();
            if ( nombre === ''){
                event.preventDefault();
                Swal.fire({
                    icon : 'warning',
                    title: 'Alerta',
                    text: 'Completar campos'
                });
                return;                
            }
        });

        $("#ActualizarForm").submit(function(event){
            var nombre = $(this).find("[name='nombre']").val();
            if ( nombre === ''){
                event.preventDefault();
                Swal.fire({
                    icon : 'warning',
                    title: 'Alerta',
                    text: 'Completar campos'
                });
                return;                
            }
        });

        $(".botonActualizar").click(function(event){
            let tipoDocumento = <?php echo json_encode($data["resultado"]); ?>;
            var index = $(this).data('index');
            if (index >= 0 && index < tipoDocumento.length) {
                $("[name='id']").val(tipoDocumento[index]["iTipoDocID"]);
                $("[name='nombre']").val(tipoDocumento[index]["tTipoDocNombre"]);
            } else {
                event.preventDefault();
                console.error("Índice fuera de los límites del array tipoDocumento");
                return;
            }
        });
    });
</script>
