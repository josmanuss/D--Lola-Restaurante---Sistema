<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                    <form action="index.php?c=CategoriaController&a=actualizar" method="POST" autocomplete="off" enctype="multipart/form-data">
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
                                    style="max-height: 300px; display: block;">
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
    $(document).ready(function() {
        $('#fileInput').on('change', function(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var dataURL = reader.result;
                var imagePreview = $('#imagePreview');
                imagePreview.attr('src', dataURL);
                imagePreview.show();
            };
            reader.readAsDataURL(input.files[0]);
        });
    });
</script>
