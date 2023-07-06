const baseURL = "http://localhost/segunda_oportunidad/web";
$(document).ready(function () {
    recargar_favoritos();
});



function recargar_favoritos() {
    // var id_usuario = $("#id_otro").val();
    url = baseURL + "/Controlador/controlador_ajax.php?tus_favoritos";

    $.ajax(
        {
            url: url,
            success: function (result) {
                var html = "";
                if (result == "false") {
                    html += "<img src='../Vista/img/sin_favoritos.png' />"
                } else {
                    JSON.parse(result).forEach(function (e) {
                        //   console.log(result);
                        html += "<div class='col-3 d-flex producto'>";
                        html += "<div class='card w-100 my-2 shadow-2-strong'>";
                        if (e.imagen == null) {
                            html += "<img src='../Vista/img/default.png' class='card-img-top' />";
                        } else {
                            html += "<img src='data:image/jpeg;base64," + e.imagen + "' class='card-img-top' />";
                        }
                        html += "<div class='card-body d-flex flex-column'>";
                        html += "<div class='row mb-2'>"
                        html += "<div class='col-8 nom_producto'>" + e.nom_producto + "</div>"
                        html += "<div class='col-4 text-secondary'>" + e.precio + " €</div>"
                        html += "</div>"
                        html += "<p class='card-text'>" + e.desc_producto + "</p>"
                        html += "<div class='card-footer d-flex align-items-center justify-content-between pt-3 px-2 pb-0 mt-auto'>"
                        html += "<p><i class='bi bi-geo-alt-fill text-danger'></i>" + e.ciudad + "</p>"
                        html += "<a class='corazon'><i class='bi bi-heart-fill'></i></a>"
                        html += "</div>"
                        html += " </div>"
                        html += " <form action='../Controlador/controlador_productos.php' method='POST'><input type='hidden' name='ver_producto' value='" + e.id_producto + "' /></form>"
                        html += "</div>"
                        html += "</div>";
                    });
                }

                $("#contenedor_favoritos").html(html);
                anadir_eventos()
            }
        });
}

function anadir_eventos() {
    document.querySelectorAll(".producto").forEach((e) => {

        e.querySelector("img").addEventListener("click", () => {
            e.querySelector("form").submit();
        })
    })

    $(".corazon").click(function (e) {
        if ($(this).children("i").hasClass("bi-heart-fill")) {
            agregarAviso({ tipo: 'info', titulo: '¡No me gusta!', desc: 'Este producto se ha quitado de "Tus favoritos".&#128148;' })
        }
        // id_producto seleccionado 
        var id_producto = $(this).parent().parent().parent().find("form input").val()
        // console.log(id_producto)
        $.ajax(
            {
                url: baseURL + "/Controlador/controlador_ajax.php?anadir_favoritos&id_producto=" + id_producto,
                success: function (result) {
                    // console.log($(this).children("i"));
                    if ($(this).children("i").hasClass("bi-heart")) {
                        $(this).children("i").removeClass("bi-heart")
                        $(this).children("i").addClass("bi-heart-fill")
                    } else {
                        $(this).children("i").removeClass("bi-heart-fill")
                        $(this).children("i").addClass("bi-heart")
                    }
                }.bind(this)
            })
        recargar_favoritos()
    })
}