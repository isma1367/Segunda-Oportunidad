<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
    
    require_once("../Vista/cabecera.php") ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuracion</title>
    <link rel="stylesheet" href="../Vista/css/configuracion.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Configuración</h5>
                    </div>
                    <div class="list-group list-group-flush" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account" role="tab">
                            Perfil
                        </a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#password" role="tab">
                            Contraseña
                        </a>
                        <a class="list-group-item list-group-item-action"  data-toggle="list" href="#delete" role="tab">
                            Eliminar Cuenta &nbsp;<i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-xl-8">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="account" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="../Controlador/controlador_productos.php" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <!-- Nombre -->
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" name="nom_usuario" value="<?= $_SESSION['perfil']['nom_usuario'] ?>" required />
                                            </div>

                                            <!-- Apellidos -->
                                            <div class="form-group">
                                                <label>Apellidos</label>
                                                <input type="text" class="form-control" name="apellidos" value="<?= $_SESSION['perfil']['apellidos'] ?>" required />
                                            </div>

                                            <!-- Teléfono -->
                                            <div class="form-group col-md-6">
                                                <label>Teléfono</label>
                                                <input type="text" class="form-control" name="telefono" pattern="[0-9]{9}" title="El numero de telefono debe tener 9 caracteres numéricos" value="<?= $_SESSION['perfil']['telefono'] ?>">
                                            </div>

                                            <!-- Fecha nacimiento -->
                                            <div class="form-group col-md-6">
                                                <label>Fecha de Nacimiento</label>
                                                <input type="date" class="form-control" name="fecha_nacimiento" value="<?= $_SESSION['perfil']['fecha_nacimiento'] ?>" readonly>
                                            </div>

                                            <!-- Correo -->
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="correo" value="<?= $_SESSION['perfil']['correo'] ?>" readonly>
                                            </div>

                                        </div>

                                        <!-- Avatar -->
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <?php
                                                $html = "";
                                                if (isset($_SESSION['perfil']['foto'])) {
                                                    $html .= '<img alt="Foto" 
                                                        src="data:image/png;base64,' . $_SESSION['perfil']['foto'] . '"
                                                        class="rounded-circle img-responsive mt-2" width="128" height="128">';
                                                } else {
                                                    $html .= '<img alt="Foto"
                                                        src="../Vista/img/avatar_default.jpg"
                                                        class="rounded-circle img-responsive mt-2" width="128" height="128">';
                                                }
                                                echo $html;
                                                ?>
                                                <div class="mt-2">
                                                    <input type="file" name="foto_usuario" accept="image/*" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="editar_usuario"/>
                                    <button type="submit" name="editar_usuario" class="btn">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Seguridad</h5>
                                <form action="../Controlador/controlador_productos.php" onsubmit="return comprobar()" method="post">
                                    
                                    <!-- Contraseña actual -->
                                    <div class="form-group">
                                        <label for="inputPasswordCurrent">Contraseña actual</label>
                                        <input type="password" name="actual" class="form-control" required>
                                    </div>

                                    <!-- Contraseña nueva 1 -->
                                    <div class="form-group">
                                        <label for="inputPasswordNew">Nueva contraseña</label>
                                        <input type="password" name="clave1" class="form-control" id="p1" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Debe contener al menos 8 caracteres, mayúsculas, minúsculas y números" required>
                                    </div>

                                    <!-- Contraseña nueva 2 -->
                                    <div class="form-group">
                                        <label for="inputPasswordNew2">Verificar contraseña</label>
                                        <input type="password" name="clave2" class="form-control" id="p2" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Debe contener al menos 8 caracteres, mayúsculas, minúsculas y números" required>
                                    </div>
                                    <button type="submit" name="configuracion_clave" class="btn">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="delete" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <form onsubmit="return confirm('¿Deseas eliminar tu cuenta? Con ella, perderás todos tus productos y contactos.');" action="../Controlador/controlador_productos.php" method="POST">
                                    <input type="hidden" name="eliminar_cuenta" />
                                    <button class="btn" type="submit">Eliminar Cuenta</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><script src='../Vista/js/notificaciones.js'></script>
    <script>
function comprobar(){
    if($("#p1").val() != $("#p2").val()){
        agregarAviso({tipo: 'warning', titulo: 'Aviso', desc: 'La contraseña 1 y la 2 deben coincidir'})
        return false;
    }
    
    return true;
}
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <?php
    if (isset($aviso)) {
        echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
    }
    ?>
</body>

</html>