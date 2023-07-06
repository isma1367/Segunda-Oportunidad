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
  <title>Segunda Oportunidad</title>
 <link rel="stylesheet" href="../Vista/css/index.css">
</head>

<body>
  <!-- CUERPO -->
  <section>
    <div class="container">
      <div class="row">
        <!-- sidebar -->
        <div class="col-lg-3">
          <div class="card d-lg-block mb-3">
            <div class="accordion" id="acordion">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button" >
                    <b>Categorías</b>
                  </button>
                </h2>
                <div id="collapseOne" class="show" aria-labelledby="headingOne" data-bs-parent="#accordion">
                  <div class="accordion-body">
                    <ul class="list-unstyled">
                    <?php
                      // print_r($categorias);
                      $html = "";
                      foreach($categorias AS $categoria){
                        $html .= "<input type='checkbox' class='checkbox_categoria' value='".$categoria['id_categoria']."' /> ";
                        $html .= $categoria['nom_categoria']."<br/>";
                      }
                      $html .= "";
                      echo $html;
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                  <button class="accordion-button fw-bold">
                    Precio
                  </button>
                </h2>
                <div id="collapseTwo" class="show" aria-labelledby="headingTwo" data-bs-parent="#accordion">
                  <div class="accordion-body">
                    <div class="range">
                      <input type="range" class="form-range" max="300" min="0" id="range_precio" />
                    </div>
                    <div class="row mb-3">
                      <div class="col-6">
                        <p class="mb-0">
                          Min
                        </p>
                        <div class="form-outline">
                          <input type="number" id="precio_min" class="form-control" />
                          <span class="label label-info">EUR</span>
                        </div>
                      </div>
                      <div class="col-6">
                        <p class="mb-0">
                          Max
                        </p>
                        <div class="form-outline">
                          <!-- IMPORTANTE  -->
                          <input type="number" id="precio_max" min="0" max="999999" class="form-control" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-center align-items-center">
             <form action="../Controlador/controlador_productos.php" method="post">
                <input type="hidden" name="mapa_productos">
                <button class="btn" type="submit">Ver en el Mapa</button>
              </form>
          </div>
         
        </div>
        <!-- sidebar -->
        <!-- content -->
        <div class="col-lg-9">
          <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
          <h3>¡Tenemos <b><?php print_r($productos_totales) ?></b> productos a tu disposición!</h3>
            <div class="ms-auto">
              <select class="form-select d-inline-block w-auto border pt-1" id="ordenar_por">
                <option selected="selected" value="fecha_desc">Mas Recientes Primero</option>
                <option value="fecha_asc">Mas Antiguos Primero</option>
                <option value="precio_desc">Mas caros Primero</option>
                <option value="precio_asc">Mas baratos Primero</option>
              </select>
            </div>
          </header>

          <div class="row" id="contenedor_productos">
            <!-- Aquí se cargan los productos con ajax -->
          </div>

          <hr />

          <nav aria-label="Page navigation example" class="d-flex justify-content-center mt-3">
            <ul class="pagination" id="paginacion">
              <!-- Aquí cargamos la paginación con ajax -->
            </ul>
          </nav>
        </div>
      </div>
      <div class="row">

      </div>
    </div>
  </section>

  <script src="../Vista/js/index.js"></script>
  <script src="../Vista/js/notificaciones.js"></script>
  <?php
  if (isset($aviso)) {
    echo "<script>agregarAviso({tipo: '" . $aviso['tipo'] . "', titulo: '" . $aviso['titulo'] . "', desc: '" . $aviso['mensaje'] . "'})</script>";
  }
  ?>
  <?php require_once("../Vista/footer.html") ?>
</body>

</html>