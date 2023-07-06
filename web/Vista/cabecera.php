<?php
  if(!isset($productoDAO))
    header("location: ../Controlador/controlador_productos.php");
?>
<link rel="stylesheet" href="../Vista/css/cabecera.css">
<link rel="stylesheet" href="../Vista/css/notificaciones.css">
<link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

<header id="cabecera" class="border-bottom mb-4 d-flex justify-content-center align-items-center">
  <div class="container">
    <div class="row">
      <div id="e1"  class="align-items-center justify-content-start">
        <a href="../Controlador/controlador_productos.php?index"><img src="../Vista/img/logo.png" id="logo" alt="Logo"></a>
      </div>
      <div id="e2"  class="align-items-center justify-content-center">
        <input type="text" disabled="disabled" class="form-control" id="buscador" placeholder="Buscar...">
        <i id="btn_buscador" class="bi bi-search"></i>
      </div>
      <div id="e3"  class="justify-content-center align-items-center">
        <div class="dropdown">
          <?php
          $html = "";
          if (isset($_SESSION['perfil'])) {
            require_once("../Modelo/mensajeDAO.php");
            $mensajeDAO = new mensajeDAO();
            $colegas = $mensajeDAO->obtiene_colegas($_SESSION['perfil']['id_usuario']); 
            
            $html .= "<button class='btn dropdown-toggle' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                Hola {$_SESSION["perfil"]["nom_usuario"]}
              </button>
              <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
                <li><a class='dropdown-item' href='../Controlador/controlador_productos.php?configuracion'><i class='bi bi-gear'></i> Configuración</a></li>
                <li><a class='dropdown-item' href='../Controlador/controlador_productos.php?tus_productos'><i class='bi bi-bag'></i> Tus productos</a></li>
                <li><a class='dropdown-item' href='../Controlador/controlador_productos.php?nuevo_producto'><i class='bi bi-arrow-bar-up'></i> Subir productos</a></li>
                <li><a class='dropdown-item' href='../Controlador/controlador_productos.php?tus_favoritos'><i class='bi bi-heart'></i> Tus Favoritos</a></li>";
            if(!empty($colegas)) {
              $html .= "<li><a class='dropdown-item' href='../Controlador/controlador_productos.php?chat'><i class='bi bi-chat-dots'></i> Mensajes</a></li>";
            }
            $html .= "<li><a class='dropdown-item' href='../Controlador/controlador_productos.php?cerrar_sesion'><i class='bi bi-box-arrow-right'></i> Cerrar Sesión</a></li>
              </ul>";
          } else {
            $html .= " <button class='btn dropdown-toggle' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                ENTRAR / REGISTRARTE
              </button>
              <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
              <li><a class='dropdown-item' href='../Controlador/controlador_productos.php?login'><i class='bi bi-box-arrow-in-right'></i> ENTRAR</a></li>
                            <li><a class='dropdown-item' href='../Controlador/controlador_productos.php?registro'><i class='bi bi-person-plus'></i> REGISTRARSE</a></li>
              </ul>";
          }
          echo $html;
          ?>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- SCRIPTS PARA TODAS LAS VISTAS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
  function distribuirCabecera(){
    var e1 = document.getElementById("e1");
    var e2 = document.getElementById("e2");
    var e3 = document.getElementById("e3");
// console.log(window.innerWidth)
    if(window.innerWidth > 1200){
      e1.setAttribute("class","")
      e1.classList.add("d-flex","col-3");
      e2.setAttribute("class","")
      e2.classList.add("d-flex","col-6");
      e3.setAttribute("class","")
      e3.classList.add("d-flex","col-3");

    }else if(window.innerWidth > 750){
      e1.setAttribute("class","")
      e1.classList.add("d-flex","col-6");
      e2.setAttribute("class","")
      e2.classList.add("d-none");
      e3.setAttribute("class","")
      e3.classList.add("d-flex","col-6");
     

    }else{ e1.setAttribute("class","")
      e1.classList.add("d-flex","col-12");
      e2.setAttribute("class","")
      e2.classList.add("d-none");
      e3.setAttribute("class","")
      e3.classList.add("d-none");
     
    }
    e1.classList.add("justify-content-center","align-items-center");
    e2.classList.add("justify-content-center","align-items-center");
    e3.classList.add("justify-content-center","align-items-center");
  
  }
  window.visualViewport.addEventListener("resize",distribuirCabecera);
  window.addEventListener("load",distribuirCabecera);

</script>