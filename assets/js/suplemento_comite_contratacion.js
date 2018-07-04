$(document).ready(function(){
    $("#table_suplemento_comite_contratacion_index").DataTable(
        {
            select: true,
            "columnDefs": [
                //{"targets": 0, "searchable": false, "orderable": false, "visible": false},
            ],
            "language": {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        }
    );

    $('#suplemento_comite_contratacion_contrato_numero').keyup(function () {
            CargarProveedor();
    });

    $('#suplemento_comite_contratacion_contrato_anno').change(function () {
        CargarProveedor();
    });

    function CargarProveedor() {
        var numero_contrato = $('#suplemento_comite_contratacion_contrato_numero').val();
        var anno_contrato  = $('#suplemento_comite_contratacion_contrato_anno').val();
        var ruta = $('#suplemento_comite_contratacion_ruta').val();
        var token = $('#suplemento_comite_contratacion__token').val();
        $.ajax({
            type: "POST",
            url: ruta,
            data: {
                numero_contrato: numero_contrato,
                anno_contrato: anno_contrato,
                _token: token,
            },
            dataType: "json",
            success: function(data) {
                var encontrado = data['encontrado'];
                var id_contrato = data['id_contrato'];
                var proveedor = data['proveedor'];

                if(encontrado == "Si"){
                    $('#suplemento_comite_contratacion_contrato').val(id_contrato);
                    $('#suplemento_comite_contratacion_contrato_proveedor').val(proveedor);
                }else{
                    $('#suplemento_comite_contratacion_contrato').val(0);
                    $('#suplemento_comite_contratacion_contrato_proveedor').val("Registro no encontrado");
                }
            },
            error: function() {
                alert("Error al procesar");
            },
            complete: function() {

            }
        });
    }
});