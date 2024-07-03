$(document).ready(function () {
  $("#total").hide();

  function mostrarFilas(record_id) {
      $.ajax({
          url: "index.php?c=PedidoController&a=verDetallePedido",
          method: "POST",
          data: {
              record_id: record_id,
          },
          async: true,
          success: function (response) {
              var respuesta = JSON.parse(response);
              if (respuesta.success) {
                  var detalleV = respuesta.detalle;
                  var tbody = $("#tbl-DetallePlatos tbody");
                  var totalSum = 0;
                  $.each(detalleV, function (index, detalle) {
                      var fila =
                          "<tr>" +
                          "<td>" +
                          detalle["Categoria"] +
                          "</td>" +
                          "<td>" +
                          detalle["Categoria"] +
                          "</td>" +
                          "<td>" +
                          detalle["NombrePlato"] +
                          "</td>" +
                          "<td>" +
                          detalle["Cantidad"] +
                          "</td>" +
                          "<td>" +
                          detalle["Precio"] +
                          "</td>" +
                          "</tr>";
                      tbody.append(fila);
                      totalSum += parseFloat(detalle[5]) * parseFloat(detalle[4]);
                  });

                  var filaTotal =
                      "<tr>" +
                      '<td colspan="4" style="text-align: center;">SUB-TOTAL:</td>' +
                      '<td id="sub-total">S/.' +
                      "</td>" +
                      "</tr>";
                  tbody.append(filaTotal);
                  $("#op-gravadas").append((totalSum - totalSum * 0.18).toFixed(2));
                  $("#igv").append((totalSum * 0.18).toFixed(2));
                  $("#sub-total, #sub-total-pagar").append(totalSum.toFixed(2));
                  $("#vuelto").append(0);
                  
              } 
              else {
                  alert("No existe ese detalle de venta según el id a buscar");
              }
          },
          error: function (xhr, status, error) {
              alert("Error en la solicitud AJAX: " + error);
          },
      });
  }

  function pagarVenta(venta, detalleVenta, detallePagos) {
      $.ajax({
          url: "index.php?c=PedidoController&a=metodoPagarPedido",
          method: "POST",
          data: {
              datos_venta: JSON.stringify(venta),
              datos_detalleventa : JSON.stringify(detalleVenta),
              datos_detallepagos : JSON.stringify(detallePagos)
          },
          async: true,
          success: function (response) {
              var respuesta = JSON.parse(response);
              if (respuesta.success) {
                  Swal.fire({
                      icon: "success",
                      title: "Éxito",
                      text: respuesta.mensaje,
                      showConfirmButton: false,
                      timer: 2500,
                  }).then(() => {
                      window.location.href = "index.php?c=PedidoController";
                  });
              } else {
                  Swal.fire({
                      icon: "error",
                      title: "ERROR",
                      text: respuesta.mensaje,
                  });
              }
          },
          error: function (xhr, status, error) {
              console.error(xhr.responseText);
          },
      });
  }

  var div = $("<div></div>");
  div.attr("id", "idVenta");
  div.text(record_id);
  div.css({
      display: "none",
  });
  $("body").append(div);
  mostrarFilas(record_id);

  $(document).on("input keyup", "#input-number, #pagosContainer input", function () {
    var totalEfectivo = 0;

    $("#pagosContainer input").each(function () {
        var valor = $(this).val().replace(/[^0-9.,-]/g, "");  // Allow negative sign temporarily for validation
        valor = valor.replace(',', '.');  
        var numero = parseFloat(valor);
        if (!isNaN(numero)) {
            if (numero < 0) {
                $(this).val("0");
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se permiten números negativos.'
                });
                return false;
            }
            totalEfectivo += numero;
        }
    });
    
    
    $(".totalPagar").text("TOTAL A PAGAR: S/." + totalEfectivo.toFixed(2));

    var totalTexto = $("#sub-total").text();
    var separarTotal = totalTexto.match(/\d+(\.\d+)?/);
    var totalNumerico = parseFloat(separarTotal[0]);
    var vuelto = totalEfectivo - totalNumerico;

    if (vuelto >= 0) {
        $("#vuelto").removeAttr("class");
        $("#vuelto").text("VUELTO RECIBIDO: S/." + vuelto.toFixed(2));
    } else {
        $("#vuelto").attr("class", "text text-danger");
        $("#vuelto").text("VUELTO RECIBIDO: S/.0");
    }
  });



  $(document).on("change", "#dinero-exacto", function () {
      var checkeado = $(this).is(":checked");
      var totalTexto = $("#sub-total").text();
      var separarTotal = totalTexto.match(/\d+(\.\d+)?/);
      var totalNumerico = parseFloat(separarTotal[0]);
      if (checkeado) {
          $("#input-number").val(totalNumerico);
      }
  });

  $("#pagosContainer").on("click", ".duplicate-btn", function (event) {
      if (!$("#dinero-exacto").is(":checked")) {
          const newPago = $(this).closest(".detallePagos").clone();
          newPago.find("input").val("");
          newPago.find(".dinero-exacto").parent().hide();
          newPago
              .find("button")
              .removeClass("duplicate-btn btn-primary")
              .html(
                  "<div>" +
                  '<button type="button" class="btn btn-sm btn-danger remove-btn">' +
                  '<i class="fas fa-minus"></i>' +
                  "</button>" +
                  "</div>"
              );
          $("#pagosContainer").append(newPago);
      } else {
          event.preventDefault();
          Swal.fire({
              icon: "warning",
              title: "ALERTA",
              text: "No necesitas agregar otro metodo si tienes el pago completo",
          });
      }
  });

  $("#pagosContainer").on("click", ".remove-btn", function () {
      $(this).closest(".detallePagos").remove();
      $("#input-number").trigger("input");
  });


 $("#botonPagar").on("click", function(event) {
     let textoTotalPagar = $(".totalPagar").text();
     let numeroTotalPagar = parseFloat(textoTotalPagar.match(/\d+(\.\d+)?/)?.[0]);
     let valorSubTotalPagar = parseFloat($("#sub-total-pagar").text().match(/\d+(\.\d+)?/)?.[0]);
     if (numeroTotalPagar < valorSubTotalPagar) {
         event.preventDefault();
         Swal.fire({
             icon: 'error',
             title: 'ERROR',
             text: 'Total a pagar insuficiente'
         });
         return;
     }

     let venta = [{
        idPedido : record_id,
        cajero: $("#cajero").val(), 
        comprobante : $("#select-documento").val(), 
        monto: numeroTotalPagar
     }];
     
     let detalleVenta = obtenerDetallesPlatos();
     let detallePagos = obtenerDetallePagos();
     pagarVenta(venta,detalleVenta,detallePagos);
 });


  
  function obtenerDetallesPlatos() {
      var detallesPlatos = [];
      $("#tbl-DetallePlatos tbody tr").not(":last").each(function () {
          var detalle = {};
          detalle.idPlato = $(this).find("td:eq(0)").text(); 
          detalle.cantidad = $(this).find("td:eq(3)").text(); 
          detallesPlatos.push(detalle);
      });
      return detallesPlatos;
  }

  function obtenerDetallePagos(){
      var detallePagos = [];
      $("#pagosContainer .detallePagos").each(function(){
            var detalle = {};
            detalle.tipoPago = $(this).find("#select-pago").val();
            detalle.totalPagado = $(this).find("#input-number").val();
            detallePagos.push(detalle);
      });
      return detallePagos;
  }


});
