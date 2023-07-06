<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="../Vista/css/login.css">
  <link rel="stylesheet" type="text/css" href="../Vista/css/notificaciones.css">

  <title>Registro</title>
</head>

<body>

  <!-- Backgrounds -->

  <div id="login-bg" class="container-fluid">
    <div class="bg-img" id="bg-img"></div>
    <div class="bg-color" id="bg-color"></div>
  </div>

  <!-- End Backgrounds -->

  <div class="container" id="registro">
    <div class="row justify-content-center">
      <div class="col-lg-8 ">
        <div class="registro">

          <h1>Registro</h1>

          <!-- Formulario de registro -->
          <form action="../Controlador/controlador_productos.php" onsubmit="return comprobar_mayor_edad();" method="post">
            <!-- Nombre -->
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Nombre*" name="nom_usuario" maxlength="30" minlength="2" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="El nombre debe tener entre 2 y 30 caracteres alfabéticos" required>
            </div>

            <!-- Apellidos -->
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Apellidos*" name="apellidos" maxlength="50" minlength="4" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Los apellidos deben tener entre 4 y 50 caracteres alfabéticos" required>
            </div>

            <!-- Fecha de nacimiento -->
            <div class="form-group">
              <input type="date" class="form-control" id="fecha_nac" name="fecha_nacimiento" required>
            </div>

            <!-- Teléfono -->
            <div class="form-group">
              <input type="text" pattern="[0-9]{9}" title="El numero de telefono debe tener 9 caracteres numéricos" class="form-control" placeholder="Número teléfono" name="telefono">
            </div>

            <!-- Correo -->
            <div class="form-group">
              <input type="email" class="form-control" placeholder="Correo*" name="correo" required>
            </div>

            <!-- Clave -->
            <div class="form-group">
              <input type="password" class="form-control" placeholder="Clave*" name="clave" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Debe contener al menos 8 caracteres, mayúsculas, minúsculas y números" required>
            </div>

            <br>
            <input type="submit" name="registro" class="btn btn-lg btn-block btn-success" value="REGISTRARSE" />
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (isset($aviso)) {
    echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
  }
  ?>
  <script src="../Vista/js/notificaciones.js"></script>
  <script src="../Vista/js/login.js"></script>
  
</body>

</html>