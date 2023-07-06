// The Leaflet map Object
const baseURL = "http://localhost/segunda_oportunidad/web";
var myAPIKey = "{Introduce tu key aquí}";

var lat = $("#lat").val();
var lon = $("#lon").val();

var map = L.map('my-map', {
  //maxZoom: 1,
  minZoom: 6,
  maxBounds: [
    //south west
    [34.843626, -15.295749],
    //north east
    [43.572760, 5.624749]
  ],
  attributionControl: false
}).setView([lat, lon], 10);

// The API Key provided is restricted to JSFiddle website
// Get your own API Key on https://myprojects.geoapify.com


// Retina displays require different mat tiles quality
var isRetina = L.Browser.retina;

var baseUrl = "https://maps.geoapify.com/v1/tile/osm-carto/{z}/{x}/{y}.png?apiKey=" + myAPIKey;
var retinaUrl = "https://maps.geoapify.com/v1/tile/osm-carto/{z}/{x}/{y}@2x.png?apiKey=" + myAPIKey;

// Add map tiles layer. Set 20 as the maximal zoom and provide map data attribution.
L.tileLayer(isRetina ? retinaUrl : baseUrl, {
  apiKey: myAPIKey,
  maxZoom: 20,
  id: 'osm-carto',
}).addTo(map);
var marcadores = []
var url = baseURL + "/Controlador/controlador_ajax.php?vista_mapa";
$.ajax(
  {
    url: url,
    success: function (result) {
      //  console.log(result);
      JSON.parse(result).forEach((e) => {
        const cloudIcono = L.icon({
          iconUrl: "../Vista/img/pin.png",
          iconSize: [12, 15], // size of the icon
          iconAnchor: [15, 42], // point of the icon which will correspond to marker's location
        });

        const cloudMarker = L.marker([e.latitud, e.longitud], {
          icon: cloudIcono
        }).addTo(map).on("click", (event) => {
          //console.log(event);
          cargar_producto(e.id_producto);
          $(".leaflet-marker-icon").each((index, elemento) => {
            elemento.src = "../Vista/img/pin.png"
            elemento.style.width = "12px"
            elemento.style.height = "15px"
            elemento.style.marginLeft = "-15px"
            elemento.style.heigh = "-42px"

          });


          var icono = cloudMarker.options.icon;
          if (icono.options.iconUrl != "../Vista/img/pinSeleccionado.png") {
            icono.options.iconSize = [30, 30];
            icono.options.iconAnchor = [23, 52];
            icono.options.iconUrl = "../Vista/img/pinSeleccionado.png"
          }
          cloudMarker.setIcon(icono);

        }).on("focusout", () => {
          console.log("hey");
        });
        marcadores.push(cloudMarker);

      })
    }
  });

function cargar_producto(id_producto) {
  var url = baseURL + "/Controlador/controlador_ajax.php?id_producto=" + id_producto;
  var html = "";
  $.ajax(
    {
      url: url,
      type: 'GET',
      async: false,
      cache: false,
      timeout: 3000,
      success: function (result) {
        // console.log(result);
        var producto = JSON.parse(result);

        html += "<div class='card w-75 my-2 shadow-2-strong'>";
        if (producto.imagen == null) {
          html += "<img src='../Vista/img/default.png' class='card-img-top' />";
        } else {
          html += "<img style='max-height:400px;' src='data:image/jpeg;base64," + producto.imagen + "' class='card-img-top' />";
        }
        html += "<div class='card-body d-flex flex-column'>";
        html += "<div class='d-flex justify-content-between flex-row'>";
        html += "<h5 class='mb-2'>" + producto.nom_producto + "</h5>";
        html += "<h5 class='mb-2 text-secondary'>" + producto.precio + " €</h5>";
        html += "</div>";
        html += "<p style='max-height:70px;overflow:hidden;' class='card-text'>" + producto.desc_producto + "</p>";

        html += "<div class='card-footer h-25 d-flex justify-content-between'>";
        html += "<p><i class='bi bi-geo-alt-fill text-danger'></i>" + producto.ubicacion + "</p>";
        if (producto.favorito == 0) {
          html += "<a class='corazon'><i class='bi bi-heart'></i></a>"
        } else {
          html += "<a class='corazon'><i class='bi bi-heart-fill'></i></a>"
        }

        html += "</div>"
        html += "</div>"
        html += "<form action='../Controlador/controlador_productos.php' method='POST'><input type='hidden' name='ver_producto' value='" + producto.id_producto + "' /></form>";
        html += "</div>"
        $("#producto").html(html);

        $("#producto img").click((e) => {
          $("#producto form").submit();
        });
      }
    })
}