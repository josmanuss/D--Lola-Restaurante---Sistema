<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                    <form action="index.php?c=CategoriaController&a=actualizar" id="formulario-actualizar-categoria" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <input type="hidden" name="id-categoria" class="form-control" value="<?php echo $data["consultar"][0]["cCatID"];?>">
                            <label class="col-sm-3 col-form-label">Nombres</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtNombres" value="<?php echo $data["consultar"][0]["cCatNombre"]; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Subir Imagen</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="imagenCategoria" id="imagenCategoria" onchange="previewImage(event)">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3 col-form-label"></div>
                            <div class="col-sm-9">
                                <img id="imagePreview" 
                                    src="data:image/jpeg;base64,<?php echo $data['consultar'][0]['cCatImagen']; ?>" 
                                    alt="Vista Previa de la Imagen" 
                                    class="img-fluid" 
                                    style="max-height: 1000px; display: block;">
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
</div>

<script>

    $(document).ready(function(){
        function validarFormulario(formulario){
            formulario.submit(function(event){
                var nombreCategoria = $("[name='txtNombres']");
                var imagenCategoria = $("[name='imagenCategoria']");
                if ( nombreCategoria.val().trim() === ''){
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: "",
                        text: 'FALTA NOMBRES'    
                    });
                    return;
                }
                if ( imagenCategoria[0].files.lenght <= 0 ){
                    event.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "",
                        text: "FALTA IMAGEN"
                    });
                    return;
                }
            });
            validarFormulario($("#formulario-actualizar-categoria"));
        }
    });

    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imagePreview = document.getElementById('imagePreview'); 
            imagePreview.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
