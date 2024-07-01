function agregarNuevaFila(valor){
    var valores = valor.split('  -  ');
    $.ajax({
        url: 'index.php?c=PedidoController&a=agregarNuevaFila',
        method : 'POST',
        data : {nombre: valores[0], precio: valores[1]},
        async : true,
        success: function(response){
            var respuesta = JSON.parse(response);
            if (respuesta.success){
                var platos = respuesta.filas;
                var tbody = $('#tabla-productosactualizar tbody');
                $.each(platos, function(index, plato){
                    var fila = '<tr>' +
                        '<td>' + plato[0] + '</td>' +
                        '<td>' + plato[1] + '</td>' +
                        '<td>' + plato[2] + '</td>' +
                        '<td><input type="number" class="form-control" value="0"></td>' + 
                        '<td>' + plato[3] + '</td>' +
                        '<td>' +
                            '<button class="btn btn-xs btn-warning" id="actualizarCantidad"><i class="fas fa-user-edit mr-2"></i></button>'+ 
                            '<button class="btn btn-xs btn-danger" id="eliminarDetalle"><i class="fas fa-trash mr-2"></i></button>'+ 
                        '</td>' + 
                    '</tr>';
                    $('#buscarPlato').val('');
                    tbody.append(fila);
                });

                $('input[type="number"]', tbody).last().on('input', function() {
                    var valor = parseFloat($(this).val());
                    if (valor < 0 || isNaN(valor)) {
                        $(this).val(0);
                        Swal.fire({
                            icon: 'error',
                            title: 'Alerta',
                            text: 'No se puede indicar una cantidad menor a 0'
                        });
                    }
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error: ' + textStatus + ' - ' + errorThrown
            });
        }
    });
}



function tablaEstaVacia() {
    var filas = $('#tabla-productosactualizar tbody tr').length;
    return filas === 0;
}

$(document).ready(function() {

    $("#buscarPlato").on('keydown', function(event) {
        if (event.key === "Enter") {
            var valor = $("#buscarPlato").val().trim();
            if ( valor === '' ){
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, ingrese datos del plato'
                });
            }
            agregarNuevaFila(valor); 
        }
    });

    
    $('#vaciarTabla-actualizar').on('click', function() {
        // $('#tabla-productosactualizar tbody').empty();
        // Swal.fire({
        //     icon : 'success',
        //     title : 'Tabla vacia',
        //     showConfirmButton: false,
        //     timer : 2500
        // });
    });    

});

