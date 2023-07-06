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
    <title>Producto</title>
    <link rel="stylesheet" href="../Vista/css/nuevo_producto.css">
</head>

<body>
    <main>
        <div class="container mt-5">
            <form action="../Controlador/controlador_productos.php" method='POST' enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6 mb-4 border border-primary d-flex justify-content-center align-items-center">
                        <input type="file" name="fotos[]" id="fileToUpload" multiple="multiple" />
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="p-4">
                            <input type="text" minlength="4" maxlength="50" name="nombre_producto" required="required" placeholder="Nombre del Producto" class="form-control mb-3" id="nombre_producto" />
                            <p class="lead">
                                Precio: <input type="number" required="required" min="0" max="99999" class="form-control mb-3 w-25 d-inline" name="precio"> €
                            </p>
                            <strong>
                                <p id="descripcion">Descripción</p>
                            </strong>
                            <textarea class="form-control" name="desc_producto" id="" cols="30" rows="4"></textarea><br>
                            <div class="contenedor_ubicacion" id="autocomplete-container">
                                <input type="text" maxlength="50" class="form-control mb-3 w-100" placeholder="Ubicacion" name="ubicacion_producto" id="ubicacion_producto" required />
                                <div class="clear-button" id="boton_borrar">
                                    <i class="bi bi-x"></i>
                                </div>
                                <div class="autocomplete-items" id="direcciones_sugeridas"></div>
                            </div>Categoria: 
                            <select name="id_categoria" required id="categoria">
                                <?php
                                foreach ($categorias as $categoria)
                                        echo "<option value='" . $categoria["id_categoria"] . "'>" . $categoria["nom_categoria"] . "</option>";

                                ?>
                            </select>
                        </div>
                        <div class="row d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary m-1 w-25" name="nuevo_producto">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <script src="../Vista/js/script_ubicacion.js"></script>
    <?php require_once("../Vista/footer.html") ?>
</body>

</html>