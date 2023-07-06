const baseURL = "http://localhost/segunda_oportunidad/web";
$(document).ready(function () {
    anadir_eventos()
});

function recargar_en_venta(){
    
    url = baseURL + "/Controlador/controlador_ajax.php?tus_productos",

    $.ajax(
        {url: url,
        success: function(result){
            var html = ""
            // console.log(result)
            JSON.parse(result).forEach(function(e){
                // console.log(e)
                if(e.es_vendido == 0) {
                    html += "<div class='card-body'>"
                    html += "<div class='card w-100 my-2 shadow-2-strong producto'>"
                    if(e.imagen == null){
                        html += "<img src='../Vista/img/default.png' class='card-img-top' />"
                    } else {
                        html += "<img src='data:image/"+e.tipo_foto+";base64," +e.imagen+"' class='card-img-top' />"
                    }
                    html += "<div class='card-body d-flex flex-column'>"
                    html += "<div class='d-flex justify-content-between flex-row'>"
                    html += "<h5 class='mb-2'>"+e.nom_producto+"</h5>"
                    html += "<h5 class='mb-2 text-secondary'>"+e.precio+" €</h5>"
                    html += "</div>"
                    html += "<p class='card-text'>"+e.desc_producto+"</p>"
                    html += "<div class='card-footer d-flex align-items-center justify-content-center pt-3 px-0 pb-0 mt-auto'>"
                    html += "<form action='../Controlador/controlador_productos.php' method='POST'>"
                    html += "<input type='hidden' name='id_producto' value='"+e.id_producto+"' />"
                    html += "<input type='submit' class='btn btn-primary shadow-0 me-1' name='editar_producto' value='Editar' />"
                    html += "<input type='submit' class='btn btn-danger shadow-0 me-1 borrar_producto' name='borrar_producto' value='Borrar' />"
                    html += "<input type='submit' class='btn btn-secondary shadow-0 me-1 vender_producto' name='vender_producto' value='Vendido' />"
                    html += "</form>"
                    html += "</div>"
                    html += "</div>"
                    html += "</div>"
                }
            });
            $("#contenedor_enventa").html(html);
            $('.borrar_producto').click(function (e){
                confirm("¿Estás seguro de que quieres borrar este producto? \nNo podrás recuperarlo");
            })
            $('.vender_producto').click(function (e){
                confirm("¿Has vendido este producto?");
            })
        }}
    )
}

function recargar_vendidos(){
    // var id_usuario = $("#id_otro").val();
    url = baseURL + "/Controlador/controlador_ajax.php?tus_productos";
    
    $.ajax(
        {url: url,
        success: function(result){
            var html = "";
            //console.log(result);
            JSON.parse(result).forEach(function(e){
            //   console.log(e);
            if(e.es_vendido == 1){
                html += "<div class='card-body'>"
                html += "<div class='card w-100 my-2 shadow-2-strong producto'>"
                if(e.imagen == null){
                    html += "<img src='../Vista/img/default.png' class='card-img-top' />"
                } else {
                    html += "<img src='data:image/"+e.tipo_foto+";base64,"+e.imagen+"' class='card-img-top' />"
                }
                html += "<div class='card-body d-flex flex-column'>"
                html += "<div class='d-flex justify-content-between flex-row'>"
                html += "<h5 class='mb-2'>"+e.nom_producto+"</h5>"
                html += "<h5 class='mb-2 text-secondary'>"+e.precio+" €</h5>"
                html += "</div>"
                html += "<p class='card-text'>"+e.desc_producto+"</p>"
                html += "</div>"
                html += "</div>"
                html += "</div>"
            }
            });
            $("#contenedor_vendidos").html(html);
    }});   
}

function anadir_eventos(){
    $('#btn_enventa').click(function (e){
        recargar_en_venta()
    })  
    if ($('#btn_enventa').hasClass('active')) {
        recargar_en_venta()
    }
        
    $('#btn_vendidos').click(function (e){
        recargar_vendidos()
    })  
    if ($('#btn_vendidos').hasClass('active')) {
        recargar_vendidos()
    }

}