<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0"><?php echo $data["titulo"]; ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Informacion del pedido/ Platos</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tbl-DetallePlatos">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>ID Categoria</th>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Documento</p>
                                    <select class="form-control btn-block" id="select-documento">
                                        <?php foreach ($data["comprobante"] as $comprobante) : ?>
                                            <option value="<?php echo $comprobante["iTipoComID"] ?>"><?php echo $comprobante["tTipoComNombre"] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p>Metodo de pago</p>
                                    <div id="pagosContainer">
                                        <div class="form-group detallePagos">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <select name="tipoPago" id="select-pago" class="form-control btn-block">
                                                        <?php foreach ($data["tipoPago"] as $tipoPago) : ?>
                                                            <option value="<?php echo $tipoPago["cPagoID"]; ?>"><?php echo $tipoPago["cPagoTipo"]; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 botonAgregar">
                                                    <button type="button" class="btn btn-sm btn-primary duplicate-btn">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <input type="number" class="form-control" id="input-number" placeholder="Ingrese un número">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <input type="checkbox" class="dinero-exacto" id="dinero-exacto">&nbsp;Dinero exacto
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <p id="op-gravadas">OP. GRAVADAS: S/.</p>
                                    <p id="igv">IGV(18%): S/.</p>
                                    <p id="sub-total-pagar">SUB TOTAL: S/.</p>
                                    <p id="vuelto">VUELTO RECIBIDO: S/.</p>
                                    <p class="totalPagar" >TOTAL A PAGAR: S/.0.00</p>
                                    <input type="hidden" name="cajero" class="cajero" id="cajero" value="<?php echo $_SESSION["trabajador"]["cTraID"];?>">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-block" id="botonPagar">Pagar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript"> var record_id = "<?php echo $record_id; ?>";</script>
<script src="views/pedido/js/funciones4.js"></script>