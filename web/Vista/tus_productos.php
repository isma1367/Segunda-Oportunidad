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
  <title>Tus Productos</title>
  <link rel="stylesheet" href="../Vista/css/tus_productos.css">
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-5 col-xl-4">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Tus productos</h5>
          </div>
          <div class="list-group list-group-flush" role="tablist">
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#recaudado" role="tab">
              Recaudado
            </a>
            <a class="list-group-item list-group-item-action active" id="btn_enventa" data-toggle="list" href="#en_venta" role="tab">
              Tus productos en venta
            </a>
            <a class="list-group-item list-group-item-action" data-toggle="list" id="btn_vendidos" href="#vendidos" role="tab">
              Tus productos vendidos
            </a>
          </div>
        </div>
      </div>
      <div class="col-md-7 col-xl-8">
        <div class="tab-content">
          <div class="tab-pane fade" id="recaudado" role="tabpanel">
            <div class="card">
              <div class="row p-4">
                <h4>¡Bienvenido a Tus Productos!</h4>
                <p>En esta sección podrás ver todos los productos que tienes a la venta y los que ya has vendido</p>
                <?php
                $html = "";
                if ($recaudado != 0) {
                  $html .= "<h5>¡Enhorabuena!</h5>
                    <h6>Gracias a <i>Segunda Oprtunidad</i> has conseguido recaudar " . round($recaudado, 2) . " €</h6>";
                } else {
                  $html = "<h5>¡Oh vaya!</h5>
                    <p>Parece que aún no has recaudado nada &#x1F615; <br/>
                    ¡No te preocupes! Puedes empezar subiendo tu producto aquí: <p>
                    <a href='../Controlador/controlador_productos.php?nuevo_producto'>Nuevo Producto</a>";
                }
                echo $html;
                ?>
              </div>
            </div>
          </div>
          <div class="tab-pane fade show active" id="en_venta" role="tabpanel">
            <div class="row" id="contenedor_enventa">
              <!---------- PRODUCTOS EN VENTA ---------->
              <?php
              $html = "";
              // print_r($tus_productos);
              foreach ($tus_productos as $prod) {
                if ($prod['es_vendido'] == 0) {

                  $html .= "<div class='col-lg-4 col-md-6 col-sm-6 d-flex producto'><div class='card w-100 my-2 shadow-2-strong'>";
                  if ($prod['imagen'] == NULL) {
                    $html .= "<img src='../Vista/img/default.png' class='card-img-top' />";
                  } else {
                    $html .= "<img src='data:image/jpg;base64," . $prod['imagen'] . "' class='card-img-top' />";
                  }
                  $html .= "<div class='card-body d-flex flex-column'>
                  <div class='d-flex justify-content-between flex-row'>
                  <h5 class='mb-2 nom_producto'>" . $prod['nom_producto'] . "</h5>
                  </div>
                  <p class='card-text'>" . $prod['desc_producto'] . "</p>
                  <div class='card-footer d-flex align-items-center justify-content-center pt-3 px-0 pb-0 mt-auto'>";
                  $html .= "<form onsubmit=`return confirm('¿Está seguro de que desea realizar esta operación?');` action='../Controlador/controlador_productos.php' method='POST'>
                  <input type='hidden' name='id_producto' value='" . $prod['id_producto'] . "' />
                  <input type='submit' class='btn btn-primary shadow-0 me-1 editar_producto' name='editar_producto' value='Editar' />
                  </form>";
                  $html .= "<form onsubmit='return confirm(`¿Quieres BORRAR este producto? No habrá vuelta atrás.`);' action='../Controlador/controlador_productos.php' method='POST'>
                  <input type='hidden' name='id_producto' value='" . $prod['id_producto'] . "' />
                  <input type='submit' class='btn btn-danger shadow-0 me-1 borrar_producto' name='borrar_producto' value='Borrar' />
                  </form>";
                  $html .= "<form onsubmit='return confirm(`¿Quieres VENDER este producto? No habrá vuelta atrás.`);' action='../Controlador/controlador_productos.php' method='POST'>
                  <input type='hidden' name='id_producto' value='" . $prod['id_producto'] . "' />
                  <input type='submit' class='btn btn-secondary shadow-0 me-1 vender_producto' name='vender_producto' value='Vendido' />
                  </form>
                  
                  </div>
                  </div>
                  </div>
                  </div>";
                }
              }
              echo $html;
              ?>
            </div>
          </div>
          <!-- ------------------------------------------------------------------------------------- -->
          <div class="tab-pane fade" id="vendidos" role="tabpanel">
            <div class="row" id="contenedor_vendidos">
              <!---------- PRODUCTOS VENDIDOS ---------->
              <?php
              // print_r($tus_productos);
              $html = "";
              foreach ($tus_productos as $prod) {
                // echo $prod['es_vendido'];
                if ($prod['es_vendido'] == 1) {
                  // print_r($prod);
                  $html .= "<div class='col-lg-4 col-md-6 col-sm-6 d-flex'><div class='card w-100 my-2 shadow-2-strong producto'>";
                  if ($prod['imagen'] == NULL) {
                    $html .= "<img src='../Vista/img/default.png' class='card-img-top' />";
                  } else {
                    $html .= "<img src='data:image/jpg;base64," . $prod['imagen'] . "' class='card-img-top' />";
                  }
                  $html .= "<div class='card-body d-flex flex-column'>
                  <div class='d-flex justify-content-between flex-row'>
                  <h5 class='mb-2'>" . $prod['nom_producto'] . "</h5>
                  </div>
                  <p class='card-text'>" . $prod['desc_producto'] . "</p>
                  </div>
                  </div></div>";
                }
              }
              echo $html;
              ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (!empty($avisos_varios)) {
    echo "<script src='../Vista/js/notificaciones.js'></script>";
    foreach ($avisos_varios as $aviso) {
      echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
    }
  }
  ?>
 </body>

</html>