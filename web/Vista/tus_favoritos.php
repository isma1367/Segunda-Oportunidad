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
    <title>Tus Favoritos</title>
    <link rel="stylesheet" href="../vista/css/tus_productos.css">
</head>

<body>
        <div class="container">
            <div class="row">
            <div class="row" id="contenedor_favoritos">
               <!-- AQUÍ AÑADIMOS LOS PRODUCTOS CON AJAX -->
                  <div class="col-2 d-none d-lg-block"></div>
            </div>
        </div>
        </div>
        <script src="../Vista/js/notificaciones.js"></script>
        <script src="../Vista/js/tus_favoritos.js"></script>
        <?php require_once("../Vista/footer.html") ?>
</body>

</html>