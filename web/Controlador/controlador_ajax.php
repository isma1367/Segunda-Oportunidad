<?php
    session_start();
    require "../Modelo/productoDAO.php";
    require "../Modelo/favoritoDAO.php";
    require "../Modelo/mensajeDAO.php";
    require "../Modelo/mensaje.php";
    $productoDAO = new productoDAO();
    $favoritoDAO = new favoritoDAO();
    $mensajeDAO = new mensajeDAO();
    
    if(isset($_GET["tiene_sesion"]))
        if(isset($_SESSION["perfil"]))
            echo "true";
        else
            echo "false";

    if(isset($_GET["filtros"]))
        print_r(json_encode($productoDAO->obtener_productos_con_filtros(json_decode($_GET["filtros"]))));
   
    if(isset($_GET["vista_mapa"]))
        print_r(json_encode($productoDAO->obtiene_productos_mapa()));

    if(isset($_GET["anadir_favoritos"]) && isset($_SESSION["perfil"])){
        $id_producto = $_GET['id_producto'];
        if($favoritoDAO->existe_favorito($_SESSION['perfil']['id_usuario'], $id_producto)){
            $favoritoDAO->quitar_like($_SESSION['perfil']['id_usuario'], $id_producto);
        } else {
            $favoritoDAO->dar_like($_SESSION['perfil']['id_usuario'], $id_producto);
        }  
    }

    if(isset($_GET["mensaje"])){
        if (!preg_match('/^[\s\r\n]+$/', $_GET["mensaje"]) && !empty($_GET["mensaje"])) {
            $mensaje = new Mensaje($_GET["mensaje"], $_SESSION['perfil']['id_usuario'], $_GET["id_otro"]);
            // print_r($mensaje);
            $mensajeDAO->insertar_mensaje($mensaje);
        }
    }

    if(isset($_GET['recargar_mensajes'])){
       
        $id_otro = $_GET['id_otro'];
        $conversacion = $mensajeDAO->obtiene_mensajes($_SESSION['perfil']['id_usuario'], $id_otro);
        print_r(json_encode($conversacion));
    }

    if(isset($_GET['tus_favoritos'])){
        $id_usuario = $_SESSION['perfil']['id_usuario'];
        $productos_favoritos = $favoritoDAO->productos_favoritos_usuario($id_usuario);
        print_r(json_encode($productos_favoritos));
    }

    if(isset($_GET["get_tarjetas"])){
        require "../Modelo/cookies.php";
        print_r(json_encode($tarjetas??[]));
   }

   // Esto es para el mapa
   if(isset($_GET["id_producto"])){
    print_r(json_encode($productoDAO->obtiene_producto_id($_GET["id_producto"])));
   }

   if (isset($_GET['tus_productos'])) {
    $tus_productos = $productoDAO->obtiene_productos_de_usuario($_SESSION['perfil']['id_usuario']);
    print_r(json_encode($tus_productos));
   }
