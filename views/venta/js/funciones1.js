function buscarClienteDNI(valor){
    $.ajax({
        url : 'index.php?c=ClienteController&a=buscarClienteDNI',
        method : 'POST',
        data : {dni_encontrar: valor},
        async : true,
        success: function(response){
            var respuesta = JSON.parse(response);
            if (respuesta.success){
                var datos = respuesta.resultado;
                $("[name='idCliente']").val(datos["ClienteID"]);
                $("[name='nombresApellidos']").val(datos["NombreCompleto"]);
            } 
            else {
                Swal.fire({
                    icon : 'error',
                    title : 'Error',
                    text : respuesta.resultado
                });
                $("[name='dniSearch']").val(''); 
                $("[name='idCliente']").val('');
                $("[name='nombresApellidos']").val('');
            }   
        } 
    });
}

function validarCliente(id) {
    var id_encontrado = null;
    $.ajax({
        url: 'index.php?c=ClienteController&a=validarTipoCliente',
        method: 'POST',
        data: { tipoCliente: id },
        async: false, 
        success: function(response) {
            var respuesta = JSON.parse(response);
            if (respuesta.success) {
                id_encontrado = respuesta.id;
            } else {
                id_encontrado = null;
            }
        },
        error: function() {
            id_encontrado = null;
        }
    });
    return id_encontrado;
}

function agregarFila(valor){
    var valores = valor.split('  -  ');
    $.ajax({
        url: 'index.php?c=PlatoController&a=agregarTabla',
        method : 'POST',
        data : {nombre: valores[0], precio: valores[1]},
        async : true,
        success: function(response){
            var respuesta = JSON.parse(response);
            if (respuesta.success){
                var platos = respuesta.filas;
                var tbody = $('#tabla-productosvender tbody');
                $.each(platos,function(index, plato){
                    var fila = '<tr>' +
                        '<td>' + plato[0] + '</td>' +
                        '<td>' + plato[1] + '</td>' +
                        '<td>' + plato[2] + '</td>' +
                        '<td><input type="number" class="form-control" value="0"></td>' + 
                        '<td>' + plato[3] + '</td>' +
                        '<td>' +
                            '<button class="btn btn-danger" id="eliminarPlatoTabla"><i class="fas fa-trash-alt mr-2"></i>Eliminar</button>'+ 
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
        }
    });
}

function enviarVentaCaja(pedido, detallepedido) {
    $.ajax({
        url: 'index.php?c=PedidoController&a=agregarPedido',
        method: 'POST',
        data: { valores_pedido: JSON.stringify(pedido), valores_detalle_pedido: JSON.stringify(detallepedido)},
        async : true,
        success: function(response) {
            var respuesta = JSON.parse(response);
            console.log(respuesta);
            if (respuesta.success) {
                console.log("La venta se ha enviado a caja correctamente");
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: respuesta.mensaje,
                    showConfirmButton: false,
                    timer: 2500
                }).then(() => {
                    window.location.href = "index.php?c=TrabajadorController&a=verPerfil";
                });
            } else {
                console.log("Error: " + respuesta.mensaje);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: respuesta.mensaje
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX: " + error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error en la solicitud. Por favor, inténtalo de nuevo.'
            });
        }
    });
}


function tablaEstaVacia() {
    var filas = $('#tabla-productosvender tbody tr').length;
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
            agregarFila(valor); 
        }
    });

    $('#tipocliente').change(function() {
        var id_encontrado = null;
        var dni = $("[name='dniSearch']"); 
        var idCliente = $("[name='idCliente']");
        var nombreApellidos = $("[name='nombresApellidos']");
        var selectedValue = $(this).val();
        if (selectedValue == '1') { 
            dni.val("");
            idCliente.val("");
            nombreApellidos.val("");
            dni.removeAttr('readonly');
        }
        else{
            id_encontrado = validarCliente(selectedValue);
            idCliente.val(id_encontrado);
            dni.attr('readonly', true);
        }
    });

    $("[name='dniSearch']").on('keydown', function(event) {
        if (event.key === "Enter") {
            var dni = $("[name='dniSearch']").val().trim();
            if (dni === '') {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, ingrese un DNI'
                });
            } else {
                buscarClienteDNI(dni);
            }
        }
    });
    

    $('#tabla-productosvender').on('click', '#eliminarPlatoTabla', function() {
        $(this).closest('tr').remove();
        Swal.fire({
            icon : 'success',
            title : 'Plato eliminado',
            showConfirmButton: false,
            timer : 2500
        });
    });

    $('#vaciarTabla').on('click', function() {
        $('#tabla-productosvender tbody').empty();
        Swal.fire({
            icon : 'success',
            title : 'Tabla vacia',
            showConfirmButton: false,
            timer : 2500
        });
    });    
  

    $("#enviarPedido").off('click').on('click', function(event){
        if (tablaEstaVacia()){
            Swal.fire({
                icon : 'error',
                title: '¡Error!',
                text : "La tabla está vacía"
            });
        }
        else{
            var suma = 0; 
            var valoresTabla = []; 
            var dni = $('[name="dniSearch"]').val().trim(); 
            var algunValorCero = false;
            $('#tabla-productosvender tbody tr').each(function(index, fila) {
                var filaValores = []; 
                $(fila).find('td:not(:last-child)').each(function() { 
                    var valor;
                    var input = $(this).find('input');
                    if (input.length > 0) {
                        valor = parseFloat(input.val());
                        if (valor === 0) {
                            algunValorCero = true;
                            return false; 
                        }
                    } else {
                        valor = $(this).text();
                    }
                    filaValores.push(valor);
                });

                if (algunValorCero) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Uno o más valores en la tabla de platos son cero. Por favor, verifique.'
                    });
                    return false; 
                }
                var valorQuintoTD = parseFloat($(fila).find('td:eq(4)').text().trim());
                var producto = filaValores[3] * valorQuintoTD;
                suma += producto;
                valoresTabla.push(filaValores);
            });

            var valorTipoCliente = $('#tipocliente').val();
            var trabajadorMozoID = document.querySelector("#mozo").value;

            if (dni === '' && valorTipoCliente === '1'){
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: '¡Atención!',
                    text: 'Por favor, ingrese un DNI'
                });
            }
            else if (!algunValorCero) { 
                let valoresDNIyTotal = [$(".mesa").val(),$('[name="idCliente"]').val(), trabajadorMozoID, suma];
                console.log(valoresDNIyTotal);                
                enviarVentaCaja(valoresDNIyTotal,valoresTabla);
            }
        }
    });
});

