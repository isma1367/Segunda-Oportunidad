<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Vista/css/chat.css">
    <title>Chat</title>
</head>

<body>
<?php require_once("../Vista/cabecera.php") ?>
    <div class="container">
        <div class="row fila">
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-3">
                            <h5 class="text-center m-4">Tus Contactos</h5>
                            <ul id="lista_contactos">
                                <?php
                                //Recojo los datos del otro
                                $nom_otro = $el_otro['nom_usuario'];
                                $foto_otro = $el_otro['foto'];

                                if (!empty($colegas)) {
                                    $html = "";
                                    foreach ($colegas as $colega) {
                                        $html .= '<li class="colegon ';
                                        if ($id_otro == $colega['id_usuario'])
                                            $html .= 'active" />';
                                        else
                                            $html .= '" />';

                                        if ($colega['foto'] != null)
                                            $html .= "<img src='data:image/png;base64," . $colega['foto'] . "' alt='avatar' />";
                                        else
                                            $html .= '<img src="../Vista/img/avatar_default.jpg" alt="avatar" />';

                                        $html .= '
                                        
                                                <span class="nombre">' . $colega['nom_usuario'] . '</span>
                                            
                                        <form action="../Controlador/controlador_productos.php" method="post">
                                            <input type="hidden" name="id_otro" value="' . $colega['id_usuario'] . '" />
                                            <input type="hidden" name="chat" />
                                        </form>
                                        </li>';
                                    }
                                    echo $html;
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="col-9">
                            <div class="row cabecera_perfil">
                                <div class="datos_perfil">
                                <?php
                                        $html = "";
                                        if ($foto_otro == null) {
                                            $html .= '<img src="../Vista/img/avatar_default.jpg" alt="avatar">';
                                        } else {
                                            $html .= "<img src='data:image/png;base64," . $foto_otro . "' alt='avatar'>";
                                        }
                                        echo $html;
                                        ?>
                                        <h5><?= $nom_otro ?></h5>
                                </div>
                                <form id="perfil_usuario" action="../Controlador/controlador_productos.php" method="post">
                                    <input type="hidden" name="id_usuario" value="<?= $id_otro ?>" />
                                    <input type="hidden" name="ver_usuario" />
                                </form>
                            </div>
                            <div class="row" id="contenedor_chat">
                                <ul id="contenedor_mensajes">

                                </ul>
                            </div>
                            <div class="row" id="footer">
                                <div class="w-50">
                                    <input type="text" class="form-control w-75 d-inline" id="mensaje" placeholder="Escribe aquí...">
                                    <input type="hidden" id="id_otro" value="<?= isset($id_otro) ? $id_otro : '' ?>" />
                                    <button class="btn" id="enviar_mensaje">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../Vista/js/chat.js"></script>
    <?php require_once("../Vista/footer.html") ?>
</body>

</html>