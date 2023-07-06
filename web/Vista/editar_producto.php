<?php
if (!isset($productoDAO))
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
    <link rel="stylesheet" href="../Vista/css/editar_producto.css">
</head>

<body>
    <main>
        <form action="../Controlador/controlador_productos.php" method='POST' enctype="multipart/form-data">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-6 mb-4 border border-primary" id="contenedor_fotos">
                        <div class="row m-2 mb-4">
                            <?php
                            if ($fotos != false) {
                                echo "<p>Selecciona las fotos para borrarlas</p>";
                                foreach ($fotos as $foto) {
                                    echo "<img class='d-inline-block m-2 foto_producto' src='data:image/jpg;base64," . $foto['imagen'] . "' alt='imagen' />";
                                    echo "<input type='checkbox' class='d-none' name='ids_fotos[]' value='" . $foto["id_foto"] . "'/>";
                                }
                            }
                            ?>
                        </div>
                        <div class="row">
                            <input type="file" name="fotos[]" multiple="multiple" />
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="p-4">
                            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>" />
                            <input type="text" maxlength="50" name="nombre_producto" class="form-control mb-3" id="nombre_producto" placeholder="Nombre del Producto" value="<?= $producto['nom_producto'] ?>" />
                            <p class="lead">
                                <input type="number" class="form-control mb-3 w-25 d-inline" name="precio" value="<?= $producto['precio'] ?>"> €
                            </p>
                            <strong>
                                <p id="descripcion">Descripción</p>
                            </strong>
                            <textarea class="form-control" name="desc_producto" id="" cols="30" rows="4"><?= $producto['desc_producto'] ?></textarea><br>
                            <div class="contenedor_ubicacion" id="autocomplete-container">
                                <input maxlength="50" type="text" class="form-control mb-3 w-100" placeholder="Ubicacion" name="ubicacion_producto" value="<?= $producto["ubicacion"] ?>" id="ubicacion_producto" required />
                                <div class="clear-button" id="boton_borrar">
                                    <i class="bi bi-x"></i>
                                </div>
                                <div class="autocomplete-items" id="direcciones_sugeridas"></div>
                            </div>
                            <select name="id_categoria" id="categoria">
                                <?php
                                foreach ($categorias as $categoria)
                                    if ($categoria["id_categoria"] == $producto["id_categoria"])
                                        echo "<option selected value='" . $categoria["id_categoria"] . "'>" . $categoria["nom_categoria"] . "</option>";
                                    else
                                        echo "<option value='" . $categoria["id_categoria"] . "'>" . $categoria["nom_categoria"] . "</option>";

                                ?>
                            </select>
                        </div>
                        <div class="row d-flex justify-content-end align-items-center">
                            <button type="submit" class="btn btn-primary m-1 w-25" name="modificar_producto">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script src="../Vista/js/script_ubicacion.js"></script>
    <script src="../Vista/js/editar_producto.js"></script>
    <?php require_once("../Vista/footer.html") ?>
</body>

</html>