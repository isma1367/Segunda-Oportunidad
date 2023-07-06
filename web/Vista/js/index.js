const baseURL = "http://localhost/segunda_oportunidad/web";

//BARRA BUSCADOR
$("#buscador").removeAttr('disabled');

// EVENTOS de filtrado de productos
// precio
$("#range_precio").change((e) => {
    $("#precio_max").val($("#range_precio").val());
    cargar_productos();
})
$("#range_precio").on("input", (e) => {
    if ($("#precio_min").val() == "")
        $("#precio_min").val(0)
    $("#precio_max").val($("#range_precio").val());
})

// BUSCADOR
$("#btn_buscador").click(function (e) {
    cargar_productos();
})
// BUSCADOR -> ejecutar al dar enter
$('#buscador').keydown(function(event) {
    if (event.keyCode === 13) {
        cargar_productos();
    }
});

// ORDENAR
$(".checkbox_categoria").change(function (e) {
    cargar_productos();
});
$("#precio_min").focusout(function (e) {
    cargar_productos();
});
$("#precio_max").focusout(function (e) {
    $("#range_precio").val($("#precio_max").val())
    cargar_productos();
});

$("#ordenar_por").change(function (e) {
    cargar_productos();
});
$(document).ready(function () {
    cargar_productos();
});

// FUNCIONES
function anadir_eventos() {

    //IMPORTANTE
    // tener en cuenta el orden

    // PAGINACIÓN
    $("#paginacion li").click(function (e) {
        $("#paginacion li").removeClass("active");
        $(this).addClass("active");
        cargar_productos();
        window.scrollTo(0, 0);
    });

    // RECOMENDACIONES
    let items = document.querySelectorAll('.carousel .carousel-item')
    items.forEach((el) => {
        const minPerSlide = 4
        let next = el.nextElementSibling
        for (var i = 1; i < minPerSlide; i++) {
            if (!next) {
                // wrap carousel by using first child
                next = items[0]
            }
            let cloneChild = next.cloneNode(true)
            el.appendChild(cloneChild.children[0])
            next = next.nextElementSibling
        }
    })

    document.querySelectorAll(".producto").forEach((e) => {

        e.querySelector("img").addEventListener("click", () => {
            
            e.querySelector("form").submit();
        })
    })

    // FAVORITOS
    $(".corazon").click(function (e) {
        var este = $(this);
        $.ajax(
            {
                url: baseURL + "/Controlador/controlador_ajax.php?tiene_sesion",
                success: function (result) {
                    
                    if(result == "true"){
                        
            if(este.children("i").hasClass("bi-heart")){
                agregarAviso({tipo: 'info', titulo: '¡Me gusta!', desc: 'Este producto se ha guardado en "Tus favoritos".&#128150;'})
            } else {
                agregarAviso({tipo: 'info', titulo: '¡No me gusta!', desc: 'Este producto se ha quitado de "Tus favoritos".&#128148;'})
            }
            // id_producto seleccionado 
            var id_producto = este.parent().parent().parent().find("form input").val()
            // console.log(id_producto)
            $.ajax(
                {
                    url: baseURL + "/Controlador/controlador_ajax.php?anadir_favoritos&id_producto=" + id_producto,
                    success: function (result) {
                        if (este.children("i").hasClass("bi-heart")) {
                            este.children("i").removeClass("bi-heart")
                            este.children("i").addClass("bi-heart-fill")
                        } else {
                            este.children("i").removeClass("bi-heart-fill")
                            este.children("i").addClass("bi-heart")
                        }
                    }.bind(this)
                })
                    }else
                    agregarAviso({tipo: 'info', titulo: 'Inicia Sesión', desc: 'Debes iniciar sesión antes'})
           
                }
            })
           
       
        
    })
}

// FILTROS
var precio_min_por_defecto = 0;
var precio_max_por_defecto = 99999999;

function get_filtros() {
    //get_numero_pagina
    if ($("#paginacion li.active a").text() > 0) {
        var num_pag = $("#paginacion li.active a").text() - 1;
    } else {
        var num_pag = 0;
    }

    //get_precio_min
    var precio_min = $("#precio_min").val() == "" ? precio_min_por_defecto : $("#precio_min").val();
    //get_precio_max
    var precio_max = $("#precio_max").val() == "" ? precio_max_por_defecto : $("#precio_max").val();
    //get ordenar_por
    var ordenar_por = $("#ordenar_por option:selected").val();

    // CATEGORÍAS
    var ids_categorias = []
    var categorias_seleccionadas = $(".checkbox_categoria")
    categorias_seleccionadas.each(function () {
        if ($(this).is(':checked')) {
            ids_categorias.push($(this).val());
        }
    })

    // BUSCADOR
    var buscador = $("#buscador").val()


    var filtros = {
        "num_pag": num_pag,
        "precio_min": precio_min,
        "precio_max": precio_max,
        "ordenar_por": ordenar_por,
        "ids_categorias": ids_categorias,
        "buscador": buscador
    }
    // console.log(filtros);
    return filtros;
}

// 
function cargar_productos() {
    var filtros = get_filtros()

    var tarjetas = [];
    if ((filtros.num_pag == 0)
        && (filtros.precio_min == precio_min_por_defecto)
        && (filtros.precio_max == precio_max_por_defecto)
        && (filtros.ids_categorias.length == 0)
        && (filtros.buscador == "")
        && (filtros.ordenar_por == "fecha_desc")) {
        $.ajax(
            {
                url: baseURL + "/Controlador/controlador_ajax.php?get_tarjetas",
                type: 'GET',
                async: false,
                cache: false,
                timeout: 3000,
                success: function (result) {
                    // console.log(result);
                    try {
                         tarjetas = JSON.parse(result)
                    } catch (error) {
                        console.log(result);
                    }
                   
                }
            })
    }

    var url = baseURL + "/Controlador/controlador_ajax.php?filtros=" + JSON.stringify(filtros);
    $.ajax(
        {
            url: url,
            type: 'GET',
            async: false,
            cache: false,
            timeout: 3000,
            success: function (result) {
                var productos = JSON.parse(result);
                // console.log(result);
                var html = "";
                var contador_tarjetas = 0;
                var posiciones_tarjetas = [];

                if (tarjetas.length > 0) {

                    var posiciones_tarjetas = [3 * Math.floor((Math.random() * 3))];

                    for (let i = 1; i < tarjetas.length; i++)
                        posiciones_tarjetas.push(posiciones_tarjetas[i - 1] + 3)
                }

                for (let i = 0; i < productos.length; i++) {
                    if (posiciones_tarjetas.includes(i)) {

                        html += "<div class='row d-flex justify-content-center my-2 tarjeta'>\
                    <h3 class='align-self-end'>"+ tarjetas[contador_tarjetas]["nombre"] + "</h3>\
                    <div id='carousel"+ contador_tarjetas + "' class='carousel justify-content-center' data-interval='false'>\
                        <div class='carousel-inner w-100 d-flex justify-content-between' role='listbox'>";
                        for (let j = 0; j < tarjetas[contador_tarjetas]["productos"].length; j++) {
                            if (j == 0)
                                html += "<div class='carousel-item active m-2'>";
                            else
                                html += "<div class='carousel-item m-2'>";
                            html += "<div class='col-md-3 d-flex justify-content-center align-items-start'>\
                                    <div class='card shadow-2-strong w-100 producto'>";
                            if (tarjetas[contador_tarjetas]["productos"][j]["imagen"] == null) {
                                html += "<img src='../Vista/img/default.png' class='card-img-top' />";
                            } else {
                                html += "<img src='data:image/png;base64," + tarjetas[contador_tarjetas]["productos"][j]["imagen"] + "'class='card-img-top' />";
                            }
                            html += "<div class='card-body d-flex flex-column'>\
                                            <div class='row mb-2' >\
                                                <div style='font-weight:bold;' class='col-8'>"+ tarjetas[contador_tarjetas]["productos"][j]["nom_producto"] + "</div>\
                                                <div class='col-4 text-secondary'>"+ tarjetas[contador_tarjetas]["productos"][j]["precio"] + " €</div>\
                                            </div>\
                                            <p class='card-text'>"+ tarjetas[contador_tarjetas]["productos"][j]["desc_producto"] + "</p>\
                                            \</div>\
                                            <div class='card-footer h-25 d-flex justify-content-start align-items-center'>\
                                                <p style='font-size:15px;'>"+ tarjetas[contador_tarjetas]["productos"][j]["ciudad"] + " <i class='bi bi-geo-alt-fill text-danger'></i></p>\
                                            </div>\
                                            <form action='../Controlador/controlador_productos.php' method='POST'><input type='hidden' name='ver_producto' value='"+ tarjetas[contador_tarjetas]["productos"][j]["id_producto"] + "' /></form>\
                                </div>\
                                        </div>\
                            </div>";
                        }
                        html += "\
                        </div>\
                        <a class='carousel-control-prev bg-transparent w-aut' href='#carousel"+ contador_tarjetas + "' role='button' data-bs-slide='prev'>\
                            <span class='carousel-control-prev-icon' aria-hidden='true'></span>\
                        </a>\
                        <a class='carousel-control-next bg-transparent w-aut' href='#carousel"+ contador_tarjetas + "' role='button' data-bs-slide='next'>\
                            <span class='carousel-control-next-icon' aria-hidden='true'></span>\
                        </a>\
                    </div>\
                </div>";
                        contador_tarjetas++;
                    }

                    html += "<div class='col-lg-4 col-md-6 col-sm-6 d-flex'><div class='card w-100 my-2 shadow-2-strong producto'>";
                    if (productos[i].imagen == null) {
                        html += "<img src='../Vista/img/default.png' class='card-img-top' />";
                    } else {
                        html += "<img src='data:image/" + productos[i].tipo_foto + ";base64," + productos[i].imagen + "'class='card-img-top' />";
                    }
                    html += "<div class='card-body d-flex flex-column'>";
                    html += "<div class='d-flex justify-content-between row mb-3'>";
                    html += "<div id='nom_producto' class='col-8'>" + productos[i].nom_producto + "</div>";
                    html += "<div id='precio' class='col-4 text-secondary'>" + productos[i].precio + " €</div>";
                    html += "</div>";
                    html += "<p class='card-text'>" + productos[i].desc_producto + "</p>";
                    html += "</div>"
                    html += "<div class='card-footer d-flex justify-content-between align-items-center'>";
                    html += "<span class='d-inline-block ml-2'>" + productos[i].ciudad + " <i class='bi bi-geo-alt-fill text-danger' style='font-size:20px;'></i></span>";
                    if (productos[i].favorito == 1) {
                        html += "<a class='corazon'><i class='bi bi-heart-fill'></i></a>"
                    } else {
                        html += "<a class='corazon'><i class='bi bi-heart'></i></a>"
                    }

                    
                    html += "</div>"
                    html += "<form action='../Controlador/controlador_productos.php' method='POST'><input type='hidden' name='ver_producto' value='" + productos[i].id_producto + "' /></form>";
                    html += "</div>"
                    html += "</div>";

                }
                if (productos.length < 12) {
                    //console.log(filtros.num_pag);
                    imprimir_botones_paginacion(filtros.num_pag, filtros.num_pag + 1)
                }

                else
                    imprimir_botones_paginacion(filtros.num_pag)

                $("#contenedor_productos").html(html);

                anadir_eventos();

            }
        });
}

// IMPRIME LOS BOTONES DE PAGINACIÓN (dinámicamente)
function imprimir_botones_paginacion(num_pag = 0, limite = null) {
    num_pag++;
    var html = "";
    //console.log(1 < (limite??100));
    if (num_pag <= 2)
        for (let index = 1; ((index <= 3) && (index <= (limite ?? 100))); index++) {
            if (num_pag == index)
                html += "<li class='page-item activo'><a class='page-link'>" + index + "</a></li>";
            else
                html += "<li class='page-item'><a class='page-link'>" + index + "</a></li>";
        }
    else
        for (let index = num_pag - 1; (index <= (num_pag + 1)) && (index <= (limite ?? 100)); index++) {
            if (num_pag == index)
                html += "<li class='page-item activo'><a class='page-link'>" + index + "</a></li>";
            else
                html += "<li class='page-item'><a class='page-link'>" + index + "</a></li>";
        }

    $("#paginacion").html(html)
}