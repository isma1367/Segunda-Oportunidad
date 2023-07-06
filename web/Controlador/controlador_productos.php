<link rel="icon" type="image/x-icon" href="../Vista/img/icono.ico">
<?php
session_start();

require_once("../Modelo/productoDAO.php");
require_once("../Modelo/categoriaDAO.php");
require_once("../Modelo/fotoDAO.php");
require_once("../Modelo/usuarioDAO.php");
require_once("../Modelo/valoracionDAO.php");
require_once("../Modelo/mensajeDAO.php");
require_once("../Modelo/favoritoDAO.php");

$productoDAO = new productoDAO();
$categoriaDAO = new categoriaDAO();
$fotoDAO = new fotoDAO();
$usuarioDAO = new usuarioDAO();
$valoracionDAO = new valoracionDAO();
$mensajeDAO = new mensajeDAO();
$favoritoDAO = new favoritoDAO();

$productos_totales = $productoDAO->productos_totales();

if (!isset($_SESSION["ubicacion_usuario"])) {
    $_SESSION["ubicacion_usuario"] = $usuarioDAO->get_ubicacion_usuario();
}

if (!isset($_SESSION['perfil']) && !isset($_COOKIE["es_logeado"])) {
   
    if (isset($_POST["ver_producto"])) {
        //COOKIES
        $producto = $productoDAO->obtiene_producto_id($_POST["ver_producto"]);
        setcookie("id_ultima_categoria_0", $producto["id_categoria"], time() + (86400 * 30), "/"); // 86400 = 1 day
        if (isset($_COOKIE["ultimos_productos_0"]))
            $ultimos_productos = unserialize($_COOKIE["ultimos_productos_0"]);
        else
            $ultimos_productos = [];
        if (count($ultimos_productos) >= 9) {
            array_shift($ultimos_productos);
            array_push($ultimos_productos, $producto["id_producto"]);
        } else {
            array_push($ultimos_productos, $producto["id_producto"]);
        }
        setcookie("ultimos_productos_0", serialize($ultimos_productos), time() + (86400 * 30), "/"); // 86400 = 1 day
        $fotos = $fotoDAO->fotos_por_producto($_POST["ver_producto"]);
        $productoDAO->anade_visita($_POST["ver_producto"]);
        require_once("../Vista/vista_producto.php");


    } else if (isset($_GET["index"])) {
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/index.php");


    } else if (isset($_POST['login'])) {
        // print_r($_POST);
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];
        if ($usuarioDAO->login($correo, $clave)) {
            if(isset($_POST["recordar_sesion"]))
                setcookie("es_logeado", $correo, time() + (86400 * 30), "/"); // 86400 = 1 day

            $categorias = $categoriaDAO->obtiene_todas_categorias();
            $usuario = $usuarioDAO->existe_email($correo);
            $_SESSION['perfil'] = $usuario;
            $aviso = array(
                "tipo" => "info",
                "titulo" => "¡Bienvenid@ a Segunda Oportunidad!",
                "mensaje" => "Esperamos que disfrutes de la aplicación que hemos creado para ti.&#128525;"
            );
            require_once("../Vista/index.php");
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "La contraseña o el correo no son correctos. Inténtelo de nuevo."
            );
            require_once("../Vista/login.php");
        }


    } else if (isset($_POST["enviar_codigo"])) {
        if ($usuarioDAO->existe_email($_POST["correo"])) {
            $aviso = $usuarioDAO->enviar_recuperar_pssw($_POST["correo"]);
            if($aviso['tipo'] == "exito" || $aviso['tipo'] == "warning"){
                $_SESSION['correo'] = $_POST['correo'];
                require_once("../Vista/recuperar_contrasena2.php");
            } else {           
                require_once("../Vista/recuperar_contrasena1.php");
            }
            
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Este correo no existe en nuestra base de datos."
            );
            require_once("../Vista/recuperar_contrasena1.php");
        }


    } else if (isset($_POST['registro'])) {
        require_once("../Modelo/usuario.php");
        if(empty($_POST['telefono'])) $telefono = "";
        else $telefono = $_POST['telefono'];
        $usuario = new Usuario($_POST['nom_usuario'], $_POST['apellidos'], $_POST['fecha_nacimiento'], $telefono, $_POST['correo'], $_POST['clave']);
        if ($usuario->es_valido && $usuarioDAO->insertar_usuario($usuario)) {
            $usuario = $usuarioDAO->existe_email($_POST['correo']);
            $_SESSION['perfil'] = $usuario;
            $aviso = array(
                "tipo" => "exito",
                "titulo" => "¡Bienvenid@ a Segunda Oportunidad!",
                "mensaje" => "Tus datos se han guardado con éxito"
            );
            $categorias = $categoriaDAO->obtiene_todas_categorias();
            require_once("../Vista/index.php");
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Ha habido un problema al registrar sus datos"
            );
            require_once("../Vista/registro.php");
        }

        
    } else if (isset($_GET['login'])) {
        require_once("../Vista/login.php");


    } else if (isset($_POST['ver_usuario'])) {
        $id_vendedor = $_POST['id_usuario'];
        $vendedor = $usuarioDAO->existe_ID($id_vendedor);
        $media = $valoracionDAO->media_vendedor($id_vendedor);
        $valoraciones = $valoracionDAO->obtiene_valoraciones($id_vendedor);
        $productos = $productoDAO->obtiene_productos_de_usuario($id_vendedor);
        $estrellas = $valoracionDAO->pinta_estrellas($media);
        require_once("../Vista/vista_vendedor.php");


    } else if (isset($_GET['registro'])) {
        require_once("../Vista/registro.php");


    } else if (isset($_POST["mapa_productos"])) {
        require_once("../Vista/mapa_productos.php");


    } else if (isset($_GET["abrir_recuperar_clave"])) {
        require "../Vista/recuperar_contrasena1.php";


    } else if (isset($_POST["verificar_correo"])) {
        if ($usuarioDAO->existe_email($_POST["correo"])) {
            $_SESSION["correo"] = $_POST["correo"];
            $usuarioDAO->enviar_recuperar_pssw($_SESSION["correo"]);
            require "../Vista/recuperar_contrasena2.php";
        } else {
            $error = "El correo no está registrado en la web  :(";
            require "../Vista/recuperar_contrasena1.php";
        }


    } else if (isset($_POST["verificar_codigo"])) {
        if ($usuarioDAO->verificar_codigo($_SESSION["correo"], $_POST["codigo"])){
            $aviso = array(
                "tipo" => "exito",
                "titulo" => "¡GENIAL!",
                "mensaje" => "El código es correcto. Solo falta que cambies tu contraseña.&#128522;"
            );
            require_once("../Vista/recuperar_contrasena3.php");
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "El código no es correcto."
            );
            require_once("../Vista/recuperar_contrasena2.php");
        }


    } else if (isset($_POST["nueva_pssw"])) {
        if ($_POST["contrasena1"] == $_POST["contrasena2"]) {
            if ($usuarioDAO->cambiar_contrasena($_SESSION["correo"], $_POST["contrasena1"])) {
                $aviso = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Tu contraseña ha sido actualizada."
                );
                require_once("../Vista/login.php");
            } else {
                $aviso = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "No se ha podido cambiar tu contraseña."
                );
                require_once("../Vista/recuperar_contrasena3.php");
            }
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Las dos contraseñas no coinciden."
            );
            require_once "../Vista/recuperar_contrasena3.php";
        }

    
    } else if (isset($_POST['chat'])) {
        $aviso = array(
            "tipo" => "warning",
            "titulo" => "AVISO",
            "mensaje" => "Tienes que iniciar sesión para hablar con el usuario.",
        );
        if(isset($_POST['id_producto'])) {
            $producto = $productoDAO->obtiene_producto_id($_POST["id_producto"]);
            $fotos = $fotoDAO->fotos_por_producto($_POST["id_producto"]);
            $productoDAO->anade_visita($_POST["id_producto"]);
            require_once("../Vista/vista_producto.php");
        } else {
            $id_vendedor = $_POST['id_otro'];
            $vendedor = $usuarioDAO->existe_ID($id_vendedor);
            $media = $valoracionDAO->media_vendedor($id_vendedor);
            $valoraciones = $valoracionDAO->obtiene_valoraciones($id_vendedor);
            $productos = $productoDAO->obtiene_productos_de_usuario($id_vendedor);
            $estrellas = $valoracionDAO->pinta_estrellas($media);
            require_once("../Vista/vista_vendedor.php");
        }

    
    } else if(isset($_POST['anadir_valoracion'])){
        $aviso = array(
            "tipo" => "warning",
            "titulo" => "AVISO",
            "mensaje" => "Tienes que iniciar sesión para escribir una valoración.",
        );
        $id_vendedor = $_POST['id_vendedor'];
        $vendedor = $usuarioDAO->existe_ID($id_vendedor);
        $media = $valoracionDAO->media_vendedor($id_vendedor);
        $valoraciones = $valoracionDAO->obtiene_valoraciones($id_vendedor);
        $productos = $productoDAO->obtiene_productos_de_usuario($id_vendedor);
        $estrellas = $valoracionDAO->pinta_estrellas($media);
        require_once("../Vista/vista_vendedor.php");


    } else {
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/index.php");
    }
    
}
else{


// CON SESION ---------------------------------------------------------------------------------------------------------
if(isset($_COOKIE["es_logeado"]) && !isset($_SESSION["perfil"])){
    
    $_SESSION["perfil"] = $usuarioDAO->existe_email($_COOKIE["es_logeado"]);
    //echo "es logeado";
    // print_r($_SESSION["perfil"]);
}

if (isset($_SESSION['perfil'])) {
   
    if (isset($_GET['cerrar_sesion'])) {
        $usuarioDAO->borrar_datos_session_usuario();
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/index.php");


    }else if(isset($_POST["eliminar_cuenta"])){
        if($usuarioDAO->borrar_usuario($_SESSION["perfil"]["id_usuario"])){
           $usuarioDAO->borrar_datos_session_usuario();
            $aviso = array(
            "tipo" => "warning",
            "titulo" => "AVISO",
            "mensaje" => "Su cuenta ha sido eliminada."
            );
            $categorias = $categoriaDAO->obtiene_todas_categorias();
            require_once("../Vista/index.php");
        } else {
        $aviso = array(
            "tipo" => "error",
            "titulo" => "ERROR",
            "mensaje" => "Ha habido un error al eliminar su cuenta"
            );
            require_once("../Vista/configuracion.php");
        }
        

    } else if (isset($_POST['configuracion_clave'])) {
        if ($usuarioDAO->configuracion_clave($_SESSION['perfil']['id_usuario'], $_POST['actual'], $_POST['clave1'], $_POST['clave2'])) {
            $aviso = array (
                "tipo" => "exito",
                "titulo" => "¡GENIAL!",
                "mensaje" => "Tu contraseña nueva se ha guardado correctamente."
            );
        } else {
            $aviso = array (
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Tu contraseña nueva no se ha podido guardar correctamente."
            );
        }
        require_once("../Vista/configuracion.php");


    } else if (isset($_GET["tus_productos"])) {
        $recaudado = $productoDAO->precio_total_vendidos($_SESSION['perfil']['id_usuario']);
        $tus_productos = $productoDAO->obtiene_productos_de_usuario($_SESSION['perfil']['id_usuario']);
        require_once("../Vista/tus_productos.php");


    } else if (isset($_GET["tus_favoritos"])) {
        require_once("../Vista/tus_favoritos.php");


    } else if (isset($_GET["configuracion"])) {
        require_once("../Vista/configuracion.php");


    // } else if (isset($_POST['actualizar_foto'])) {
    //     if($usuarioDAO->cambiar_avatar($_SESSION['perfil']['id_usuario'], file_get_contents($_FILES['foto_usuario']['tmp_name']))){
    //         $_SESSION['perfil'] = $usuarioDAO->existe_ID($_SESSION['perfil']['id_usuario']);
    //         $aviso = array(
    //             "tipo" => "exito",
    //             "titulo" => "¡GENIAL!",
    //             "mensaje" => "Tu foto de perfil ha sido modificada con éxito."
    //         );
    //     } else {
    //         $aviso = array(
    //             "tipo" => "error",
    //             "titulo" => "ERROR",
    //             "mensaje" => "Ha habido un problema al cargar tu foto de perfil. Por favor, inténtelo de nuevo"
    //         );
    //     }
    //     require_once("../Vista/configuracion.php");


    } else if(isset($_POST['editar_usuario'])){
        $avisos_varios = [];
        $datos = array(
            "nom_usuario" => $_POST['nom_usuario'],
            "apellidos" => $_POST['apellidos'],
            "telefono" => $_POST['telefono'],
            "fecha_nacimiento" => $_POST['fecha_nacimiento'],
            "correo" => $_POST['correo']
        );
        //Gestión de datos
        if($usuarioDAO->editar_usuario($datos, $_SESSION['perfil']['id_usuario'])) {
            $aviso = array(
                "tipo" => "exito",
                "titulo" => "¡GENIAL!",
                "mensaje" => "Tus datos han sido modificados con éxito."
            );
            $_SESSION['perfil'] = $usuarioDAO->existe_email($_POST['correo']);
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Ha habido un problema al modificar tus datos."
            );
        }
        array_push($avisos_varios, $aviso);
        // Gestión de avatar
        if($_FILES['foto_usuario']['error'] != 4) {
            if($usuarioDAO->cambiar_avatar($_SESSION['perfil']['id_usuario'], file_get_contents($_FILES['foto_usuario']['tmp_name']))){
                $_SESSION['perfil'] = $usuarioDAO->existe_ID($_SESSION['perfil']['id_usuario']);
                $aviso = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Tu foto de perfil ha sido modificada con éxito."
                );
            } else {
                $aviso = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "Ha habido un problema al cargar tu foto de perfil. Por favor, inténtelo de nuevo"
                );
            }
            array_push($avisos_varios, $aviso);
        }
        require_once("../Vista/configuracion.php");


    } else if (isset($_GET["nuevo_producto"])) {
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/nuevo_producto.php");


    } else if (isset($_POST["editar_producto"])) {
        $producto = $productoDAO->obtiene_producto_id($_POST["id_producto"]);
        $fotos = $fotoDAO->fotos_por_producto($_POST["id_producto"]);
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/editar_producto.php");


    } else if (isset($_POST["modificar_producto"])) {
        $avisos_varios = [];
        require_once "../Modelo/producto.php";
        $producto = new Producto($_POST["id_producto"], $_POST["nombre_producto"], $_POST["desc_producto"], $_POST["ubicacion_producto"], $_POST["precio"], $_POST["id_categoria"], $_SESSION["perfil"]["id_usuario"]);
        /****** GESTIÓN DE FOTOS ******/
        // Borrar las fotos de un producto
        if (isset($_POST["ids_fotos"])){
            if ($fotoDAO->borrar_fotos($_POST["ids_fotos"])){
                $aviso = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Se han podido borrar tus fotos correctamente."
                );
            } else {
                $aviso = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "No se han podido borrar tus fotos correctamente."
                );
            }
            array_push($avisos_varios, $aviso);
        }
        // Añadir las fotos de un producto SIN ERROR
        if ($_FILES["fotos"]["error"][0] != 4) {
            if($fotoDAO->subir_fotos_producto($producto->id_producto, $_FILES["fotos"]["tmp_name"])){
                $aviso = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Se han podido subir tus fotos correctamente."
                );
            } else {
                $aviso = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "No se han podido subir tus fotos correctamente."
                );
            }
            array_push($avisos_varios, $aviso);
        }
        /****** GESTIÓN DE DATOS ******/
        if ($productoDAO->modificar_producto($producto))
            $aviso = array(
                "tipo" => "exito",
                "titulo" => "¡GENIAL!",
                "mensaje" => "Se ha podido modificar tu producto correctamente."
            );
        else
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "No se ha podido modificar tu producto correctamente."
            );
        array_push($avisos_varios, $aviso);
        $recaudado = $productoDAO->precio_total_vendidos($_SESSION['perfil']['id_usuario']);
        $tus_productos = $productoDAO->obtiene_productos_de_usuario($_SESSION['perfil']['id_usuario']);
        require_once("../Vista/tus_productos.php");


    } else if (isset($_POST["vender_producto"])) {
        $avisos_varios = [];
        if ($productoDAO->vender_producto($_POST['id_producto'])) {
            $aviso = array(
                "tipo" => "warning",
                "titulo" => "AVISO",
                "mensaje" => "Has vendido tu producto. Lo encontrarás en <i>Tus productos vendidos</i>",
            );
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Ha habido un problema al vender tu producto",
            );
        }
        array_push($avisos_varios, $aviso);
        $recaudado = $productoDAO->precio_total_vendidos($_SESSION['perfil']['id_usuario']);
        $tus_productos = $productoDAO->obtiene_productos_de_usuario($_SESSION['perfil']['id_usuario']);
        require_once("../Vista/tus_productos.php");


    } else if (isset($_POST["borrar_producto"])) {
        $avisos_varios = [];
        if ($productoDAO->borrar_producto($_POST["id_producto"])){
            $aviso = array(
                "tipo" => "warning",
                "titulo" => "AVISO",
                "mensaje" => "Has borrado tu producto",
            );
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Ha habido un problema al borrar tu producto",
            );
        }
        array_push($avisos_varios, $aviso);
        $recaudado = $productoDAO->precio_total_vendidos($_SESSION['perfil']['id_usuario']);
        $tus_productos = $productoDAO->obtiene_productos_de_usuario($_SESSION['perfil']['id_usuario']);
        require_once("../Vista/tus_productos.php");


    } else if (isset($_POST["nuevo_producto"])) {
        $avisos_varios = [];
        /******** GESTIÓN DE DATOS *********/
        require_once "../Modelo/producto.php";
        $producto = new Producto(null, $_POST["nombre_producto"], $_POST["desc_producto"], $_POST["ubicacion_producto"], $_POST["precio"], $_POST["id_categoria"], $_SESSION['perfil']['id_usuario']);
        if ($productoDAO->insertar_producto($producto)) {
            $id_producto = $productoDAO->ultimo_producto($_SESSION['perfil']['id_usuario']);
            $aviso = array(
                "tipo" => "exito",
                "titulo" => "¡GENIAL!",
                "mensaje" => "Tu producto se ha puesto a la venta.",
            );
        } else {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "No se ha podido poner tu producto a la venta.",
            );
        }
        array_push($avisos_varios, $aviso);
        /******** GESTIÓN DE FOTOS *********/
        if ($_FILES["fotos"]["error"][0] != 4){
            if($fotoDAO->subir_fotos_producto($id_producto, $_FILES["fotos"]["tmp_name"])){
                $aviso_foto = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Tus fotos se han cargado con éxito.",
                );
            } else {
                $aviso_foto = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "Ha habido un error en cargar tus fotos.",
                );
            }
            array_push($avisos_varios, $aviso_foto);
        }
        $recaudado = $productoDAO->precio_total_vendidos($_SESSION['perfil']['id_usuario']);
        $tus_productos = $productoDAO->obtiene_productos_de_usuario($_SESSION['perfil']['id_usuario']);
        require_once("../Vista/tus_productos.php");


    } else if (isset($_GET["index"])) {
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/index.php");


    } else if (isset($_POST["mapa_productos"])) {
        require_once("../Vista/mapa_productos.php");


    } else if (isset($_POST["ver_producto"])) {
        //COOKIES
        $producto = $productoDAO->obtiene_producto_id($_POST["ver_producto"]);
        setcookie("id_ultima_categoria_".$_SESSION["perfil"]["id_usuario"], $producto["id_categoria"], time() + (86400 * 30), "/"); // 86400 = 1 day
        if (isset($_COOKIE["ultimos_productos_".$_SESSION["perfil"]["id_usuario"]]))
            $ultimos_productos = unserialize($_COOKIE["ultimos_productos_".$_SESSION["perfil"]["id_usuario"]]);
        else
            $ultimos_productos = [];

        if (count($ultimos_productos) >= 9) {
            array_shift($ultimos_productos);
            array_push($ultimos_productos, $producto["id_producto"]);
        } else {
            array_push($ultimos_productos, $producto["id_producto"]);
        }
        setcookie("ultimos_productos_".$_SESSION["perfil"]["id_usuario"], serialize(array_unique($ultimos_productos)), time() + (86400 * 30), "/"); // 86400 = 1 day
        $fotos = $fotoDAO->fotos_por_producto($_POST["ver_producto"]);
        $productoDAO->anade_visita($_POST["ver_producto"]);
        require_once("../Vista/vista_producto.php");


    } else if (isset($_POST['chat']) || isset($_GET['chat'])) { //redirige al chat
            $colegas = $mensajeDAO->obtiene_colegas($_SESSION['perfil']['id_usuario']);
            if(isset($_POST['id_otro'])) {
                $id_otro = $_POST['id_otro'];
            } else {
                $id_otro = $colegas[0]['id_usuario'];
            }    
            $el_otro = $usuarioDAO->existe_ID($id_otro);
            require_once("../Vista/chat.php");


    } else if (isset($_POST['ver_usuario'])) {
        $id_vendedor = $_POST['id_usuario'];
        $vendedor = $usuarioDAO->existe_ID($id_vendedor);
        $media = $valoracionDAO->media_vendedor($id_vendedor);
        $valoraciones = $valoracionDAO->obtiene_valoraciones($id_vendedor);
        $productos = $productoDAO->obtiene_productos_de_usuario($id_vendedor);
        $estrellas = $valoracionDAO->pinta_estrellas($media);
        require_once("../Vista/vista_vendedor.php");


    } else if(isset($_POST['anadir_valoracion'])){
        require_once("../Modelo/valoracion.php");
        $valoracion = new Valoracion($_POST['valor'], $_POST['comentario'], $_POST['id_vendedor'], $_SESSION['perfil']['id_usuario']);
        if($valoracionDAO->existe_valoracion($_POST['id_vendedor'], $_SESSION['perfil']['id_usuario'])){
            if($valoracionDAO->editar_valoracion($valoracion))
                $aviso = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Tu valoración se ha modificado correctamente.",
                );
            else 
                $aviso = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "Ha habido un problema al modificar tu valoración.",
                );
        } else {
            if($valoracionDAO->insertar_valoracion($valoracion))
                $aviso = array(
                    "tipo" => "exito",
                    "titulo" => "¡GENIAL!",
                    "mensaje" => "Tu valoración se ha añadido correctamente.",
                );
            else 
                $aviso = array(
                    "tipo" => "error",
                    "titulo" => "ERROR",
                    "mensaje" => "Ha habido un problema al añadir tu valoración.",
                );
        }
        $id_vendedor = $_POST['id_vendedor'];
        $vendedor = $usuarioDAO->existe_ID($id_vendedor);
        $media = $valoracionDAO->media_vendedor($id_vendedor);
        $valoraciones = $valoracionDAO->obtiene_valoraciones($id_vendedor);
        $productos = $productoDAO->obtiene_productos_de_usuario($id_vendedor);
        $estrellas = $valoracionDAO->pinta_estrellas($media);
        require_once("../Vista/vista_vendedor.php");

        
    } else {
        $categorias = $categoriaDAO->obtiene_todas_categorias();
        require_once("../Vista/index.php");
    }
}
}

?>
