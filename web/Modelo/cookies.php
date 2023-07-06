<?php
$contador_tarjetas = 0;
$temporal = [];

if(isset($_SESSION["perfil"]["id_usuario"]))
    $id_usuario = $_SESSION["perfil"]["id_usuario"];
else
    $id_usuario = 0;

if(isset($_COOKIE["id_ultima_categoria_".$id_usuario])){
    $temporal[$contador_tarjetas]["productos"] = $productoDAO->obtiene_productos_categoria($_COOKIE["id_ultima_categoria_".$id_usuario]);
    $temporal[$contador_tarjetas]["nombre"]  = "Podría interesarte...";
    $contador_tarjetas++;
}
    
if(isset($_COOKIE["ultimos_productos_".$id_usuario]) && (count(unserialize($_COOKIE["ultimos_productos_".$id_usuario])) > 3)){
    $temporal[$contador_tarjetas]["productos"] = $productoDAO->obtener_productos_de_id(unserialize($_COOKIE["ultimos_productos_".$id_usuario]));
    $temporal[$contador_tarjetas]["nombre"]  = "Lo último que has visto...";
    $contador_tarjetas++;
}

$temporal[$contador_tarjetas]["productos"] = $productoDAO->obtener_mas_vistos();
$temporal[$contador_tarjetas]["nombre"]  = "Lo más visto...";
$contador_tarjetas++;

if(isset($_SESSION["ubicacion_usuario"])){
    $temporal[$contador_tarjetas]["productos"] = $productoDAO->obtener_productos_cerca();
    $temporal[$contador_tarjetas]["nombre"]  = "Cerca de ti...";
    $contador_tarjetas++;
}


$random_keys=array_rand($temporal,2);
$tarjetas = [$temporal[$random_keys[0]],$temporal[$random_keys[1]]];
