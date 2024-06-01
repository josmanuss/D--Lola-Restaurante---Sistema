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
                            <label class="col-sm-2 col-form-label">Nombres</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="txtNombreCat" value="<?php echo $_REQUEST["txtNombreCat"] ?? ''; ?>">
                                <?php if (isset($data["errores"]["nombreCat"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["nombreCat"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-7">
                                <input type="submit" value="Registrar Categoria" class="btn btn-block btn-success" name="btnEnviar">
                                <a href="index.php?c=CategoriaController" class="btn btn-block btn-secondary">Cancelar Registro</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
