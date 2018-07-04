$(document).ready(function(){
    $("#table_contratos_index").DataTable(
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
    //Gestion de Elementos Autocalculables
    switch (IdentificarEscenario()){
        case "Adicionar":
            DesabilitarElementosAutocalculables();
            EstablecerValoresAutocalculablesEnAdicionar();
            break;
        case "Modificar":
            //DesabilitarElementosIniciales();
            DesabilitarElementosAutocalculables();
            EstablecerValoresAutocalculablesEnModificar();
            break;
        default:
            break;
    }

    function IdentificarEscenario() {
        var ruta = window.location.href;
        if(ruta.indexOf("new") >= 0){
            return "Adicionar";
        }else{
            if(ruta.indexOf("edit") >= 0){
                return "Modificar";
            }else{
                if(ruta.indexOf("aprobar")>=0){
                    return "Modificar";
                }else{
                    return "Otro";
                }
            }
        }
    }
    //function DesabilitarElementosIniciales() {
    //    $("#contrato_valorContratoInicialCup").attr("readonly", true);
    //    $("#contrato_valorContratoInicialCuc").attr("readonly", true);
    //}
    function DesabilitarElementosAutocalculables() {
        $('#contrato_valorContratoTotalCup').attr("readonly", true);
        $('#contrato_valorContratoTotalCuc').attr("readonly", true);
        $('#contrato_ejecucionContratoCup').attr("readonly", true);
        $('#contrato_ejecucionContratoCuc').attr("readonly", true);
        $('#contrato_saldoCup').attr("readonly", true);
        $('#contrato_saldoCuc').attr("readonly", true);
    }
    function EstablecerValoresAutocalculablesEnAdicionar() {
        $("#contrato_valorContratoInicialCup").val(0);
        $("#contrato_valorContratoInicialCuc").val(0);
        $("#contrato_valorContratoTotalCup").val(0);
        $("#contrato_valorContratoTotalCuc").val(0);
        $("#contrato_ejecucionContratoCup").val(0);
        $("#contrato_ejecucionContratoCuc").val(0);
        $("#contrato_saldoCup").val(0);
        $("#contrato_saldoCuc").val(0);

        $("#contrato_valorContratoInicialCup").keyup(function(){
            $("#contrato_valorContratoTotalCup").val($("#contrato_valorContratoInicialCup").val());
            $("#contrato_saldoCup").val($("#contrato_valorContratoInicialCup").val());
        });
        $("#contrato_valorContratoInicialCuc").keyup(function(){
            $("#contrato_valorContratoTotalCuc").val($("#contrato_valorContratoInicialCuc").val());
            $("#contrato_saldoCuc").val($("#contrato_valorContratoInicialCuc").val());
        });
    }

    function EstablecerValoresAutocalculablesEnModificar() {
        if($("#contrato_valorContratoInicialCup").val().indexOf(",")>=0){
            var valor_inicial_antiguo_cup = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoInicialCup").val()));
            var valor_total_antiguo_cup = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoTotalCup").val()));
            var valor_saldo_antiguo_cup = Number.parseFloat(RemplazarComaPorPunto($("#contrato_saldoCup").val()));

            var valor_inicial_antiguo_cuc = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoInicialCuc").val()));
            var valor_total_antiguo_cuc = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoTotalCuc").val()));
            var valor_saldo_antiguo_cuc = Number.parseFloat(RemplazarComaPorPunto($("#contrato_saldoCuc").val()));

            $("#contrato_valorContratoInicialCup").keyup(function(){
                var valor_nuevo_cup = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoInicialCup").val()));

                if(valor_nuevo_cup > valor_inicial_antiguo_cup){
                    $("#contrato_valorContratoTotalCup").val(RemplazarPuntoPorComa(valor_total_antiguo_cup+(valor_nuevo_cup-valor_inicial_antiguo_cup)));
                    $("#contrato_saldoCup").val(RemplazarPuntoPorComa(valor_saldo_antiguo_cup+(valor_nuevo_cup-valor_inicial_antiguo_cup)));
                }else{
                    if(valor_nuevo_cup < valor_inicial_antiguo_cup){
                        $("#contrato_valorContratoTotalCup").val(RemplazarPuntoPorComa(valor_total_antiguo_cup-(valor_inicial_antiguo_cup-valor_nuevo_cup)));
                        $("#contrato_saldoCup").val(RemplazarPuntoPorComa(valor_saldo_antiguo_cup-(valor_inicial_antiguo_cup-valor_nuevo_cup)));
                    }else{}
                }
            });

            $("#contrato_valorContratoInicialCuc").keyup(function(){
                var valor_nuevo_cuc = Number.parseFloat(RemplazarComaPorPunto($("#contrato_valorContratoInicialCuc").val()));

                if(valor_nuevo_cuc > valor_inicial_antiguo_cuc){
                    $("#contrato_valorContratoTotalCuc").val(RemplazarPuntoPorComa(valor_total_antiguo_cuc+(valor_nuevo_cuc-valor_inicial_antiguo_cuc)));
                    $("#contrato_saldoCuc").val(RemplazarPuntoPorComa(valor_saldo_antiguo_cuc+(valor_nuevo_cuc-valor_inicial_antiguo_cuc)));
                }else{
                    if(valor_nuevo_cuc < valor_inicial_antiguo_cuc){
                        $("#contrato_valorContratoTotalCuc").val(RemplazarPuntoPorComa(valor_total_antiguo_cuc-(valor_inicial_antiguo_cuc-valor_nuevo_cuc)));
                        $("#contrato_saldoCuc").val(RemplazarPuntoPorComa(valor_saldo_antiguo_cuc-(valor_inicial_antiguo_cuc-valor_nuevo_cuc)));
                    }else{}
                }
            });
        }else{
            var valor_inicial_antiguo_cup = Number.parseFloat($("#contrato_valorContratoInicialCup").val());
            var valor_total_antiguo_cup = Number.parseFloat($("#contrato_valorContratoTotalCup").val());
            var valor_saldo_antiguo_cup = Number.parseFloat($("#contrato_saldoCup").val());

            var valor_inicial_antiguo_cuc = Number.parseFloat($("#contrato_valorContratoInicialCuc").val());
            var valor_total_antiguo_cuc = Number.parseFloat($("#contrato_valorContratoTotalCuc").val());
            var valor_saldo_antiguo_cuc = Number.parseFloat($("#contrato_saldoCuc").val());

            $("#contrato_valorContratoInicialCup").keyup(function(){
                var valor_nuevo_cup = Number.parseFloat($("#contrato_valorContratoInicialCup").val());

                if(valor_nuevo_cup > valor_inicial_antiguo_cup){
                    $("#contrato_valorContratoTotalCup").val(valor_total_antiguo_cup+(valor_nuevo_cup-valor_inicial_antiguo_cup));
                    $("#contrato_saldoCup").val(valor_saldo_antiguo_cup+(valor_nuevo_cup-valor_inicial_antiguo_cup));
                }else{
                    if(valor_nuevo_cup < valor_inicial_antiguo_cup){
                        $("#contrato_valorContratoTotalCup").val(valor_total_antiguo_cup-(valor_inicial_antiguo_cup-valor_nuevo_cup));
                        $("#contrato_saldoCup").val(valor_saldo_antiguo_cup-(valor_inicial_antiguo_cup-valor_nuevo_cup));
                    }else{}
                }
            });

            $("#contrato_valorContratoInicialCuc").keyup(function(){
                var valor_nuevo_cuc = Number.parseFloat($("#contrato_valorContratoInicialCuc").val());

                if(valor_nuevo_cuc > valor_inicial_antiguo_cuc){
                    $("#contrato_valorContratoTotalCuc").val(valor_total_antiguo_cuc+(valor_nuevo_cuc-valor_inicial_antiguo_cuc));
                    $("#contrato_saldoCuc").val(valor_saldo_antiguo_cuc+(valor_nuevo_cuc-valor_inicial_antiguo_cuc));
                }else{
                    if(valor_nuevo_cuc < valor_inicial_antiguo_cuc){
                        $("#contrato_valorContratoTotalCuc").val(valor_total_antiguo_cuc-(valor_inicial_antiguo_cuc-valor_nuevo_cuc));
                        $("#contrato_saldoCuc").val(valor_saldo_antiguo_cuc-(valor_inicial_antiguo_cuc-valor_nuevo_cuc));
                    }else{}
                }
            });

        }


    }

    function RemplazarComaPorPunto(texto){
        return texto.replace(",",".");
    }
    function RemplazarPuntoPorComa(texto){
        texto = texto.toString();
        return texto.replace(".",",");
    }
});