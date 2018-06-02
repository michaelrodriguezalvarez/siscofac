$(document).ready(function(){
    $("#btn_cerrar_modal").click(function () {
        $( "#combo_proveedores" ).load(window.location.href + " #combo_proveedores" );
    });
});