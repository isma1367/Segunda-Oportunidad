<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../Vista/css/login.css">
    <link rel="stylesheet" href="../Vista/css/notificaciones.css">
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <title>Codigo</title>
</head>

<body>
    <!-- Backgrounds -->
    <div id="login-bg" class="container-fluid">
        <div class="bg-img" id="bg-img"></div>
        <div class="bg-color" id="bg-color"></div>
    </div>

    <div class="container" id="login">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="login">
                    <h1>Introducir Código</h1>
                    <form action="../Controlador/controlador_productos.php" method="POST">
                        <!-- Email input -->
                        <div class="form-group">
                            <input type="text" name="codigo" class="form-control" placeholder="Código de 6 dígitos" />
                        </div>
                        </br>
                        <!-- Submit button -->
                        <input type="submit" class="btn btn-primary btn-block mb-4" name="verificar_codigo" value="Verificar Código" />
                    </form>
                </div>
                <div class="col-3"></div>
            </div>
        </div>
        <?php
        if (isset($aviso)) {
            echo "<script src='../Vista/js/notificaciones.js'></script>";
            echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
        }
        ?>
        <script src="../Vista/js/login.js"></script>
</body>

</html>