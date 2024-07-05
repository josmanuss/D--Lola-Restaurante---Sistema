<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                    <form action="index.php?c=PlatoController&a=actualizar" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Categorias</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="idPlato" value="<?php echo $data["consulta"][0]["cPlaID"];?>">
                                <select class="form-control" name="categoriaPlato">
                                    <option value="<?php echo $_REQUEST["categoriaPlato"] ?? ''; ?>">SELECCIONE UNA CATEGORIA</option>
                                    <?php foreach ( $data["categorias"] as $categoria ): ?>
                                        <option value="<?=$categoria["cCatID"]?>"><?php echo $categoria["cCatNombre"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <script>
                                    const comboBox = document.getElementsByName("categoriaPlato")[0];
                                    var valorCategoria = '<?php echo $data["consulta"][0]["cCatID"];?>';
                                    for (var i = 0; i < comboBox.options.length; i++) {
                                        if (valorCategoria == comboBox.options[i].value) {
                                            comboBox.selectedIndex = i;
                                            comboBox.focus();
                                            break;
                                        }
                                    }
                                </script>    
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Subir Imagen de Plato</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="imagen" id="imagen" onchange="previewImage(event)" >
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3 col-form-label"></div>
                            <div class="col-sm-9">
                                <img id="imagePreview" 
                                    src="data:image/jpeg;base64,<?php echo $data['consulta'][0]['cPlaImagen']; ?>" 
                                    alt="Vista Previa de la Imagen" 
                                    class="img-fluid" 
                                    style="max-height: 300px; display: block;">
                            </div>
                        </div>   
                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nombres</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtNombres" value="<?php echo $data["consulta"][0]["cPlaNombre"] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Precio</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="spinnerPrecio" value="<?php echo $data["consulta"][0]["cPlaPrecio"] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Cantidad</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="spinnerCantidad" value="<?php echo $data["consulta"][0]["cPlaCantidad"] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Descripcion</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="txtDescripcion" value="<?php echo $data["consulta"][0]["cPlaDescripcion"] ?? ''; ?>"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <input type="submit" value="Actualizar Plato" class="btn btn-block btn-success" name="btnActualizar">
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
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var imagePreview = document.getElementById('imagePreview'); 
            imagePreview.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>