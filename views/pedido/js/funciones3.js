document.addEventListener("DOMContentLoaded", function() {
    $(document).on("click", ".btnDetail", function() {
        $('#tablaModal').modal('show');
        var record_id = $(this).data('recordid');
        $("#tablaModalLabel").html("Detalle de pedido: "+record_id);
        $.ajax({
            url: "index.php?c=PedidoController&a=verDetallePedido",
            method: "POST",
            data: { record_id: record_id },
            async: true,
            success: function(response) {
                var respuesta = JSON.parse(response);
                if (respuesta.success) {
                    var detalleV = respuesta.detalle;
                    var tbody = $("#tabla-detalle tbody");
                    tbody.empty();
                    $.each(detalleV, function(index, detalle) {
                        var fila = 
                        '<tr>' +
                            '<td>' + detalle.IDPlato + '</td>' +
                            '<td>' + detalle.Categoria + '</td>' +
                            '<td>' + detalle.NombrePlato + '</td>' +
                            '<td>' + detalle.Cantidad + '</td>' +
                            '<td>' + detalle.Precio + '</td>' +
                        '</tr>';
                        tbody.append(fila);
                    });
                } else {
                    alert("No existe ese detalle de venta según el ID a buscar");
                    return;
                }
            },
            error: function(xhr, status, error) {
                alert("Error en la solicitud AJAX: " + error);
            }
        });
    });
    
    
    document.querySelectorAll(".payBtn").forEach(function(btn) {
        btn.addEventListener("click", function(event) {
            var recordId = event.target.dataset.recordid;
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?c=PedidoController&a=pagarPedido';
            var record_id_input = document.createElement('input');
            record_id_input.type = 'hidden';
            record_id_input.name = 'record_id';
            record_id_input.value = recordId;
            form.appendChild(record_id_input);
            document.body.appendChild(form);
            form.submit();
        });
    });
});
