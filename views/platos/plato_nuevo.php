<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Categoria</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="categoriaPlato">
                                    <option value="<?php echo $_REQUEST["categoria"] ?? ''; ?>">SELECCIONE UNA CATEGORIA</option>
                                    <?php foreach ( $data["categorias"] as $categoria ): ?>
                                        <option value="<?=$categoria["cCatNombre"]?>"><?php echo $categoria["cCatNombre"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($data["errores"]["categoria"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["categoria"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Nombre del plato</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="txtNombrePlato" value="<?php echo $_REQUEST["txtNombrePlato"] ?? ''; ?>">
                                <?php if (isset($data["errores"]["nombrePlato"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["nombrePlato"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Cantidad</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="spinCantidadPlato" value="<?php echo $_REQUEST["spinCantidadPlato"] ?? ''; ?>">
                                <?php if (isset($data["errores"]["cantidadPlato"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["cantidadPlato"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Precio</label>
                            <div class="col-sm-7">
                                <input type="number" class="form-control" name="spinPrecioPlato" value="<?php echo $_REQUEST["spinPrecioPlato"] ?? ''; ?>">
                                <?php if (isset($data["errores"]["spinPrecioPlato"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["spinPrecioPlato"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Descripcion</label>
                            <div class="col-sm-7">
                                <textarea class="form-control" name="txtDescripcion" value="<?php echo $_REQUEST["txtDescripcion"] ?? ''; ?>"></textarea>
                                <?php if (isset($data["errores"]["descripcionPlato"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["descripcionPlato"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-7">
                                <input type="submit" value="Registrar Plato" class="btn btn-block btn-success" name="btnEnviar">
                                <a href="index.php?c=CategoriaController" class="btn btn-block btn-secondary">Cancelar Registro</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
