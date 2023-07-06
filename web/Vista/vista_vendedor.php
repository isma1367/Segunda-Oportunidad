<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
?>
<?php require_once("../Vista/cabecera.php") ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../Vista/css/vista_vendedor.css">

</head>

<body>
    <div class="container w-100">
        <div class="row mb-4 border border-secondary" id="cabecera_perfil">
            <div class="col-3 d-flex justify-content-center align-items-center">
                <?php
                if ($vendedor["foto"] == NULL)
                    echo "<img id='foto_perfil' src='../Vista/img/avatar_default.jpg' alt='Foto Perfil'>";
                else
                    echo "<img id='foto_perfil' src='data:image/png;base64," . $vendedor["foto"] . "' alt='Foto Perfil'>";
                ?>

            </div>
            <div class="col-6">
                <h2 class="m-3"><?= $vendedor["nom_usuario"] ?> <?= $vendedor["apellidos"] ?></h2>
                <p class="m-3"><?= $estrellas ?></p>
            </div>
            <div class="col-3 d-flex justify-content-center align-items-center">
                <form action="../Controlador/controlador_productos.php" method="post">
                    <input type="hidden" name="id_otro" value="<?= $vendedor["id_usuario"] ?>">
                    <input type="hidden" name="chat"><br>
                    <button class="btn" type="submit">Enviar Mensaje</button><br>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-3">
                <div class="card">
                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#productos" role="tab">
                            Productos en Venta
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#valoraciones" role="tab">
                            Valoraciones
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-xl-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="productos" role="tabpanel">
                        <div class="row">
                            <?php
                            $html = "";

                            foreach ($productos as $producto) {
                                $html .= "<div class='col-lg-4 col-md-6 col-sm-6 d-flex'><div class='card w-100 my-2 shadow-2-strong producto'>";
                                if ($producto["imagen"] == null) {
                                    $html .= "<img src='../Vista/img/default.png' class='card-img-top' />";
                                } else {
                                    $html .= "<img src='data:image/png;base64," . $producto["imagen"] . "' class='card-img-top' />";
                                }
                                $html .= "<div class='card-body d-flex flex-column'>";
                                $html .=   "<div class='d-flex justify-content-between flex-row'>";
                                $html .=  "<h5 class='mb-2'>" . $producto["nom_producto"] . "</h5>";
                                $html .=  "<h5 class='mb-2 text-secondary'>" . $producto["precio"] . " €</h5>";
                                $html .=  "</div>";
                                $html .= "<p class='card-text'>" . $producto["desc_producto"] . "</p>";

                                $html .= "<div class='card-footer h-25 d-flex justify-content-between'>";
                                $html .= "<p><i class='bi bi-geo-alt-fill text-danger'></i>" . $producto["ubicacion"] . "</p>";

                                $html .= "<a class='corazon'><i class='bi bi-heart'></i></a>";

                                $html .= "</div>
                </div>
                <form action='../Controlador/controlador_productos.php' method='POST'><input type='hidden' name='ver_producto' value='" . $producto["id_producto"] . "' /></form>
                </div>
                </div>";
                            }
                            echo $html;
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="valoraciones" role="tabpanel">
                        <div class="row">
                            <div class="col-8"></div>
                            <div class="col-4 d-flex justify-content-start">
                            <button type="submit" class="btn" data-toggle="modal" data-target="#anadir_valoracion">
                                Añadir Valoración
                            </button>
                            </div>
                        </div>
                        <?php
                        $html = "";
                        foreach ($valoraciones as $valoracion) {
                            $html .=
                                "<div class='row valoracion'>
                                    <div class='col-2 d-flex justify-content-center align-items-center'>{$valoracion["nom_usuario"]}</div>
                                    <div class='col-6 d-flex justify-content-center align-items-center'>{$valoracion["comentario"]}</div>
                                    <div class='col-4 d-flex justify-content-center align-items-center'>{$valoracionDAO->pinta_estrellas($valoracion["valor"])}</div>
                                </div>";
                        }
                        echo $html;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../Vista/js/vista_vendedor.js"></script>
    </body>

</html>








<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="anadir_valoracion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="../Controlador/controlador_productos.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Valoración</h5>
                    <button type="button" class="close" data-dismiss="modal" style="border-radius: 5px;" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <td>1<span class="bi bi-star-fill" style="color: yellow;"></span></td>
                            <td>2<span class="bi bi-star-fill" style="color: yellow;"></span></td>
                            <td>3<span class="bi bi-star-fill" style="color: yellow;"></span></td>
                            <td>4<span class="bi bi-star-fill" style="color: yellow;"></span></td>
                            <td>5<span class="bi bi-star-fill" style="color: yellow;"></span></td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <input id="range_valoracion" type="range" name="valor" min="1" max="5" id="valor_valocion" required />
                            </td>
                        </tr>
                    </table>
                        <textarea name="comentario" id="comentario_valoracion" cols="40" rows="5" placeholder="Quieres dejar un comentario ?"></textarea>
                        <input type="hidden" name="id_vendedor" value="<?= $vendedor["id_usuario"] ?>">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="anadir_valoracion" />
                    <button type="submit" class="btn">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    if (isset($aviso)) {
        echo "<script src='../Vista/js/notificaciones.js'></script>";
        echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
    }
    ?>