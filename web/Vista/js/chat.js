const baseURL = "http://localhost/segunda_oportunidad/web";

$(document).ready(function () {
    recargar_mensajes();
});

// Al darle a la tecla "espacio" el mensaje debe mandarse
$("#mensaje").keyup(function(e) {
    if (e.keyCode === 13) { //código de la tecla de espacio
        enviar_mensaje();
    }
})

// Botón de enviar --> me inserta el mensaje en la bd
$("#enviar_mensaje").click(function (e) { 
    enviar_mensaje()
});

function enviar_mensaje(){
    var mensaje = $("#mensaje").val();
    var id_otro = $("#id_otro").val();
    // console.log(mensaje);

    url = baseURL+"/Controlador/controlador_ajax.php?mensaje="+mensaje+"&id_otro="+id_otro
    $.ajax(
        {url: url,
        success: function(result){
            recargar_mensajes()
    }});

    $("#mensaje").val("")
}

function recargar_mensajes(){
    var id_otro = $("#id_otro").val();
    url = baseURL+"/Controlador/controlador_ajax.php?recargar_mensajes&id_otro="+id_otro;
    
    $.ajax(
        {url: url,
        success: function(result){
            var html = "";
            // console.log(result);
            JSON.parse(result).forEach(function(e){
            //   console.log(e.id_remitente);

            html += '<li>';
            if(e.id_remitente != id_otro){
                html += '<div class="mensaje mi_mensaje">';
                html += "<div class='contenido'>"+e.contenido + '</div><div class="fecha">'+ e.fecha + ' - '+e.hora;
                html += '</div></div></li>';
            }
            else{
                html += '<div class="mensaje mensaje_otro">';
                html += "<div class='contenido'>"+e.contenido + '</div><div class="fecha">'+ e.fecha + ' - '+e.hora;
                html += '</div></div></li>';
            }
            });
            $("#contenedor_mensajes").html(html);
    }});   
}

document.querySelectorAll(".colegon").forEach((e)=>{
    e.addEventListener("click", () => {
        e.querySelector("form").submit()
    })
})

document.querySelectorAll(".datos_perfil").forEach((e)=>{
    e.addEventListener("click", () => {
        var form = document.getElementById("perfil_usuario");
        form.submit()
    })
})