$(document).ready(function(){
    $("#table_contratos_index").DataTable(
        {
            select: true,
            "columnDefs": [
                {"targets": 0, "searchable": false, "orderable": false, "visible": false},
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

    /*function RemplazarComaPorPunto(texto){
        return texto.replace(",",".");
    }
    function RemplazarPuntoPorComa(texto){
        return texto.replace(".",",");
    }
    function CalculoValoresCup(){
        var valores_autocalculados = $("#contrato_valorContratoInicialCup").val() + $("#contrato_ejecucionContratoCup").val();        
        
        if(valores_autocalculados.indexOf(",") == -1){ //Si no esta en espannol 
            valores_autocalculados = Number.parseFloat($("#contrato_valorContratoInicialCup").val())-Number.parseFloat($("#contrato_ejecucionContratoCup").val());
        }else{
            var contrato_valorContratoInicialCup = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoInicialCup").val()));
            var contrato_ejecucionContratoCup = Number.parseFloat(RemplazarComaPorPunto($("#contrato_ejecucionContratoCup").val()));

            var resultado = contrato_valorContratoInicialCup - contrato_ejecucionContratoCup;
            alert(parseText(resultado));
            valores_autocalculados = RemplazarPuntoPorComa(Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoInicialCup").val()))-Number.parseFloat(RemplazarComaPorPunto($("#contrato_ejecucionContratoCup").val())));
        }

        $("#contrato_valorContratoTotalCup").val(valores_autocalculados);
        $("#contrato_saldoCup").val(valores_autocalculados); 
    }*/

    $("#contrato_valorContratoInicialCup").keyup(function(){
        $("#contrato_valorContratoTotalCup").val($("#contrato_valorContratoInicialCup").val());
        $("#contrato_saldoCup").val($("#contrato_valorContratoInicialCup").val());
    });
    $("#contrato_valorContratoInicialCuc").keyup(function(){
        $("#contrato_valorContratoTotalCuc").val($("#contrato_valorContratoInicialCuc").val());
        $("#contrato_saldoCuc").val($("#contrato_valorContratoInicialCuc").val());
    });

});