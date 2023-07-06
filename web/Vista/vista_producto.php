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
    <link rel="stylesheet" href="../Vista/css/producto.css">
    <link rel="stylesheet" href="../Vista/css/notificaciones.css">
</head>

<body>
    <main>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php
                            if (empty($fotos))
                                echo "<button type='button' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='0' aria-current='true' aria-label='Slide 0'></button>";
                            else
                                for ($i = 0; $i < count($fotos); $i++) {
                                    echo "<button type='button' class='btn' data-bs-target='#carouselExampleIndicators' data-bs-slide-to='{$i}' aria-current='true' aria-label='Slide {$i}'></button>";
                                }
                            ?>
                        </div>
                        <div class="carousel-inner">
                            <?php
                            if (empty($fotos))
                                echo "<div class='carousel-item'>
                                <img src='../Vista/img/default.png' class='d-block w-100' alt='0'>
                            </div>";
                            else
                                for ($i = 0; $i < count($fotos); $i++) {
                                    echo "<div class='carousel-item'>
                                <img src='data:image/png;base64,{$fotos[$i]["imagen"]}' class='d-block w-100' alt='{$i}'>
                            </div>";
                                }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <script>
                        // Añadir clase 'active' al primer elemento de (carousel-indicators,carousel-item)
                        //console.log();
                        document.querySelector(".carousel-indicators button").classList.add("active");
                        document.querySelector(".carousel-item").classList.add("active");
                    </script>
                </div>
                <div class="col-md-6 mb-4 px-4">

                    <div class="row" id="fila1">
                        <div id="nom_producto" class="col d-flex justify-content-start align-items-center"><?= $producto['nom_producto'] ?></div>

                    </div>

                    <div class="row d-flex justify-content-start align-items-center" id="fila2">
                        <div id="precio"><?= $producto['precio'] ?> €</div>
                    </div>
                    <div class="row">
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <form action="../Controlador/controlador_productos.php" method="POST">
                                <input type="hidden" name="id_usuario" value="<?= $producto['id_usuario'] ?>" />
                                <button id="contenedor_usuario" type="submit" name="ver_usuario" class="btn btn-light">
                                    <i class="bi bi-person-circle"></i>&nbsp;
                                    <?= $producto["nom_usuario"] ?>
                                </button>
                            </form>
                        </div>
                        <div class="col-6 d-flex justify-content-start align-items-center">
                            <form action="../Controlador/controlador_productos.php" method="POST">
                                <input type="hidden" name="id_otro" value="<?= $producto['id_usuario'] ?>" />
                                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>" />
                                <button id="contenedor_chat" type="submit" name="chat" class="btn btn-light"><i class="bi bi-envelope"></i> Enviar mensaje</button>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div id="descripcion">
                            <?= $producto['desc_producto'] ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div id="nom_categoria" class="col-6 d-flex justify-content-start align-items-center">
                            <div id="contenedor_categoria" class="d-flex justify-content-center align-items-center px-2 py-1">
                                <i class="bi bi-car"></i>
                                <?= $producto['nom_categoria'] ?>
                            </div>
                        </div>
                        <div id="contenedor_num_visitas" class="col-6 d-flex justify-content-end align-items-center">
                            <i class="bi bi-eye"></i>&nbsp;<div id="num_visitas"><?= $producto['num_visitas'] ?> visitas</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 d-flex justify-content-center align-items-center">
                            <div id="contenedor_ubicacion">
                            <i class='bi bi-geo-alt-fill text-danger'></i>&nbsp;
                                <?= $producto['ubicacion'] ?>
                            </div>
                        </div>
                        <div id="fecha" class="col-6 d-flex justify-content-end align-items-center">
                            <?= $producto['fecha_publicacion'] ?>
                        </div>
                    </div>
                </div>
            </div>
    </main>
    <?php
    if (isset($aviso)) {
        echo "<script src='../Vista/js/notificaciones.js'></script>";
        echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
    }
    ?>
    <script>
        $(".carousel-control-prev").mouseleave(function(){
           this.style.opacity = 0.6;
        })
        $(".carousel-control-prev").mouseenter(function(){
           this.style.opacity = 0.8;
        })
        $(".carousel-control-next").mouseleave(function(){
           this.style.opacity = 0.6;
        })
        $(".carousel-control-next").mouseenter(function(){
           this.style.opacity = 0.8;
        })
        
    </script>
    <?php require_once("../Vista/footer.html") ?>
</body>

</html>