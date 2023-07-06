<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
?>
<?php require_once("../Vista/cabecera.php") ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css">
    <link rel="stylesheet" href="../Vista/css/mapa_productos.css">

    <title>Mapa Productos</title>
</head>

<body style="overflow-x: hidden;">
<input type="hidden" id="lat" value="<?= $_SESSION["ubicacion_usuario"]["lat"] ?>">
<input type="hidden" id="lon" value="<?= $_SESSION["ubicacion_usuario"]["lon"] ?>">
    <div class="row h-75 px-4">
            <div class="col-9 my-2">
                <div id="my-map"></div>
            </div>
            <div class="col-3 d-flex justify-content-center align-items-center" id="producto">
                <img src="../Vista/img/ubicacion.png" width="350px" />
            </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="../Vista/js/mapa_productos.js"></script>
    <?php require_once("../Vista/footer.html") ?>
</body>

</html>