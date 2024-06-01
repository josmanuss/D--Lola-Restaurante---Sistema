<style>
    .detallePagos .remove-btn {
        position: absolute;
        right: 10px;
        top: 10px;
    }

</style>

<div class="content-wrapper">
    <div class ="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0"><?php echo $data["titulo"];?></h1>
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
                                        <?php foreach ( $data["comprobante"] as $comprobante ):?>
                                            <option value="<?php echo $comprobante["iTipoComID"]?>"><?php echo $comprobante["tTipoComNombre"]?></option> 
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
                                                        <?php foreach ($data["tipoPago"] as $tipoPago): ?>
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
                                                    <input type="checkbox" id="dinero-exacto">&nbsp;Dinero exacto
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
                                    <p id="sub-total">SUB TOTAL: S/.</p>
                                    <p id="vuelto">VUELTO RECIBIDO: S/.</p> 
                                    <p id="total">TOTAL: S/.</p>
                                    <!-- <p>Ingrese efectivo:</p>
                                    <input type="number" class="form-control" id="efectivo"> -->
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
<script type="text/javascript">



$(document).ready(function(){
    function mostrarFilas(record_id){
        $.ajax({
            url :"index.php?c=VentaController&a=verDetallePedido",
            method : "POST",
            data : {record_id: record_id},
            async : true,
            success : function(response){
                var respuesta = JSON.parse(response);
                if (respuesta.success){
                    var detalleV = respuesta.detalle;
                    var tbody = $("#tbl-DetallePlatos tbody");
                    var totalSum = 0; 
                    $.each(detalleV, function(index,detalle){
                        var fila = 
                        '<tr>'+
                            '<td>' + detalle[0] +'</td>'+
                            '<td>' + detalle[2] +'</td>'+
                            '<td>' + detalle[3] +'</td>'+
                            '<td>' + detalle[4] +'</td>'+
                            '<td>' + detalle[5] +'</td>'+
                        '</tr>';    
                        tbody.append(fila);
                        totalSum += parseFloat(detalle[5]) * parseFloat(detalle[4]);
                    });
                    $("#op-gravadas").append((totalSum-(totalSum*0.18)).toFixed(2));
                    $("#igv").append((totalSum*0.18).toFixed(2));
                    $("#sub-total").append(totalSum.toFixed(2));
                    $("#vuelto").append(0);
                    $("#total").append(totalSum.toFixed(2));
                    
                    //console.log("Total de la sumatoria de multiplicación de la tercera y cuarta columna:", totalSum);
                }
                else{
                    alert("No existe ese detalle de venta según el id a buscar");
                }
            },
            error: function(xhr, status, error) {
                alert("Error en la solicitud AJAX: " + error);
            }
        });
    }

    function pagarVenta(valorPagar) {
        $.ajax({
            url: 'index.php?c=VentaController&a=metodoPagarPedido',
            method: 'POST',
            data: { id_pedido: $("#idVenta").text(), monto_pagado: valorPagar },
            async: true,
            success: function(response) {
                var respuesta = JSON.parse(response);
                if ( respuesta.success ){
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: respuesta.mensaje,
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => {
                        window.location.href = "index.php?c=VentaController";
                    });
                }
                else{
                    Swal.fire({
                        'icon': 'error',
                        'title': 'ERROR',
                        'text': respuesta.mensaje
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    var div = $('<div></div>');
    div.attr("id", "idVenta");
    var record_id = "<?php echo $record_id; ?>";
    div.text(record_id); 
    div.css({"display":"none"});
    $("body").append(div);
    console.log(div.val());
    mostrarFilas(record_id);
    
    // $("#input-number").on('input keyup', function() {

    // });

        $(this).on('input keyup',"#input-number", function(){
            var valor = $(this).val().replace(/[^0-9.,]/g, '');
            if (!isNaN(valor) && valor !== "") {
                var efectivo = parseFloat(valor);
                var totalTexto = $('#total').text();
                var separarTotal = totalTexto.match(/\d+(\.\d+)?/);
                var totalNumerico = parseFloat(separarTotal[0]);

                $.each()

                if (efectivo >= totalNumerico) {
                    var vuelto = efectivo - totalNumerico;
                    if (vuelto === 0) {
                        $('#vuelto').attr('class','text text-danger')
                        alert($(this).val());
                        $("#vuelto").text("VUELTO RECIBIDO: S/." + vuelto.toFixed(2));
                    } else {
                        $('#vuelto').removeAttr('class');
                        $("#vuelto").text("VUELTO RECIBIDO: S/." + vuelto.toFixed(2));
                    }
                } else {
                    $('#vuelto').removeAttr('class');
                    $("#vuelto").text("VUELTO RECIBIDO: S/.0");
                }
            } else {
                $(this).val('');
                $('#vuelto').removeAttr('class');
                $("#vuelto").text("VUELTO RECIBIDO: S/.");
            }
        });

    $("#botonPagar").on('click', function(event){
        var montoPagado = $("#efectivo").val();
        var totalTexto = $('#total').text();
        var totalNumerico = parseFloat(totalTexto.match(/\d+(\.\d+)?/)[0]);
        if ( montoPagado >= totalNumerico ){
            pagarVenta(montoPagado);
        }
        else{
            Swal.fire({
                icon: 'error',
                title: 'ERROR',
                text: 'El efectivo debe ser mayor o igual al total'
            });
        }
    });

    $('#pagosContainer').on('click', '.duplicate-btn', function() {
        const newPago = $(this).closest('.detallePagos').clone();
        newPago.find('input').val('');
        newPago.find('button').removeClass('duplicate-btn btn-primary')
                   .html('<div>' +
                            '<button type="button" class="btn btn-sm btn-danger remove-btn">' +
                                '<i class="fas fa-minus"></i>' +
                            '</button>' +
                        '</div>');
        $('#pagosContainer').append(newPago);
    });

    $('#pagosContainer').on('click', '.remove-btn', function() {
        $(this).closest('.detallePagos').remove();
    });



});
</script>