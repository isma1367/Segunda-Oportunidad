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
  <link rel="stylesheet" type="text/css" href="../Vista/css/login.css">
  <link rel="stylesheet" type="text/css" href="../Vista/css/notificaciones.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
  <title>Login</title>
</head>

<body>

  <!-- Backgrounds -->

  <div id="login-bg" class="container-fluid">
    <div class="bg-img" id="bg-img"></div>
    <div class="bg-color" id="bg-color"></div>
  </div>

  <!-- End Backgrounds -->

  <div class="container" id="login">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="login">

          <h1>Login</h1>

          <!-- Loging form -->
          <form action="../Controlador/controlador_productos.php" method="post">
            <div class="form-group">
              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Correo" name="correo" required>
            </div>
            <div class="form-group">
              <span class="icon-eye"><i class="bi bi-eye-slash-fill"></i></span>
              <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Clave" name="clave" required>
            </div>

            <div class="form-check">

              <label class="switch">
                <input type="checkbox" name="recordar_sesion" />
                <span class="slider round"></span>
              </label>
              <label class="form-check-label" for="exampleCheck1">Recordar Sesión</label>

              <label class="forgot-password">
                <a href="../Controlador/controlador_productos.php?abrir_recuperar_clave">Recuperar clave<a>
              </label>

            </div>

            <br>
            <input type="submit" name="login" class="btn btn-lg btn-block btn-success" value="ENTRAR" />
          </form>
          <!-- End Loging form -->
          <?php
          if (isset($aviso)) {
            echo "<script src='../Vista/js/notificaciones.js'></script>";
            echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <script src="../Vista/js/login.js"></script>
</body>

</html>