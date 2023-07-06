<?php
require_once("conexion.php");

class productoDAO extends Conexion {
    private $con;

    /**
     * Constructor de la clase.
     * Establece la conexión a la base de datos.
     *
     * @return void
     */
    public function __construct() {
        $this->con = parent::get_conexion();
    }

    
    /**
     * Función que devuelve el número de productos que están disponibles para comprar en la bd
     *
     * @return int|false Devuelve el número de productos o false
     */
    public function productos_totales() {
        try {
            $sql = "SELECT count(id_producto) AS total FROM productos WHERE es_vendido = 0;";
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que obtiene los productos para mostrarlos en el mapa (en forma de iconito)
     *
     * @return array|false Devuelve un array con los productos, o false si no hay.
     */
    public function obtiene_productos_mapa() {
        try {
            $sql = "SELECT productos.id_producto, latitud, longitud FROM productos WHERE es_vendido=0;";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute())
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            else 
                return false;
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que devuelve todos los datos de un producto espeificando el id del mismo
     *
     * @param  int $id
     * @return array|false Devuelve los datos, o false.
     */
    public function obtiene_producto_id($id) {
        try {
            $sql = "SELECT productos.id_producto, 
                nom_producto, 
                desc_producto, 
                ubicacion, 
                ciudad, 
                num_visitas, 
                precio, 
                DATE_FORMAT(fecha_publicacion,'%d/%m/%Y') AS fecha_publicacion, 
                productos.id_categoria, 
                nom_categoria, 
                productos.id_usuario, 
                nom_usuario, 
                TO_BASE64(imagen) AS imagen
            FROM productos
            LEFT OUTER JOIN categorias
                ON (productos.id_categoria = categorias.id_categoria)
            LEFT OUTER JOIN usuarios
                ON (productos.id_usuario = usuarios.id_usuario)
            LEFT OUTER JOIN fotos
                ON (productos.id_producto = fotos.id_producto)
            WHERE productos.id_producto = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            if ($stmt->execute()) {
                $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $registro = false;
            }
            return $registro;
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que inserta un nuevo producto en la bd
     * También calcula la latitud, longitud y ciudad a partir de la ubicación
     * NO gestiona las fotos
     *
     * @param  object $producto
     * @return true|false
     */
    public function insertar_producto($producto) {
        try {
            $location = json_decode(file_get_contents("https://api.geoapify.com/v1/geocode/search?text=" . $producto->ubicacion . "&apiKey={Introduce tu key aquí}"))->features;

            if (!empty($location)) {
                $location = $location[0]->properties;
                $coordenadas["lat"] = $location->lat;
                $coordenadas["lon"] = $location->lon;
                $ciudad = $location->city;
            } else {
                $coordenadas = $_SESSION["ubicacion_usuario"];
            }

            $sql = "INSERT INTO productos(nom_producto, desc_producto, ubicacion,fecha_publicacion, precio, id_categoria, id_usuario, latitud, longitud,ciudad)
                    VALUES (?,?,?,now(),?,?,?,?,?,?);";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $producto->nom_producto);
            $stmt->bindParam(2, $producto->desc_producto);
            $stmt->bindParam(3, $producto->ubicacion);
            $stmt->bindParam(4, $producto->precio);
            $stmt->bindParam(5, $producto->id_categoria);
            $stmt->bindParam(6, $producto->id_usuario);
            $stmt->bindParam(7, $coordenadas["lat"]);
            $stmt->bindParam(8, $coordenadas["lon"]);
            $stmt->bindParam(9, $ciudad);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que devuelve el último producto que ha subido un usuario
     *
     * @param  int $id_usuario
     * @return int|false
     */
    public function ultimo_producto($id_usuario) {
        try {
            $sql = "SELECT max(id_producto) AS id_producto FROM productos WHERE id_usuario=?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_usuario);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['id_producto'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Funci´n que borra un producto en la bd
     * CUIDADO: se borran en cascada las fotos y los productos favoritos también
     *
     * @param  int $id
     * @return true|false
     */
    public function borrar_producto($id) {
        try {
            $sql = "DELETE FROM productos WHERE id_producto = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            } 
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que devuelve los productos (y fotos) del usuario que los ha subido
     *
     * @param  int $id
     * @return array|false
     */
    public function obtiene_productos_de_usuario($id) {
        try {
            $sql = "SELECT productos.id_producto, 
                nom_producto, 
                desc_producto, 
                ubicacion, 
                num_visitas, 
                precio, 
                fecha_publicacion, 
                TO_BASE64(imagen) AS imagen, 
                es_vendido 
            FROM productos 
            LEFT OUTER JOIN fotos 
                ON (productos.id_producto = fotos.id_producto)
                 WHERE id_usuario = ?
            GROUP BY productos.id_producto;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que obtiene la suma de los precios de los objetos que ha vendido un usuario
     *
     * @param  int $id
     * @return float|false
     */
    public function precio_total_vendidos($id) {
        try {
            $sql = "SELECT sum(precio) AS total FROM productos WHERE (id_usuario=?) AND (es_vendido = 1);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
    

    /**
     * Función que modifica un producto
     *
     * @param  object $producto
     * @return true|false
     */
    public function modificar_producto($producto) {
        try {
            $location = json_decode(file_get_contents("https://api.geoapify.com/v1/geocode/search?text=" . $producto->ubicacion . "&apiKey={Introduce tu key aquí}"))->features;

            if (!empty($location)) {
                $location = $location[0]->properties;
                $coordenadas["lat"] = $location->lat;
                $coordenadas["lon"] = $location->lon;
                $ciudad = $location->city;
            } else {
                $coordenadas = $_SESSION["ubicacion_usuario"];
            }

            $sql = "UPDATE productos
            SET nom_producto = ?,
            desc_producto = ?,
            ubicacion = ?,
            precio = ?,
            id_categoria = ?,
            latitud = ?,
            longitud = ?,
            ciudad = ?
            WHERE id_producto = ?;";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $producto->nom_producto);
            $stmt->bindParam(2, $producto->desc_producto);
            $stmt->bindParam(3, $producto->ubicacion);
            $stmt->bindParam(4, $producto->precio);
            $stmt->bindParam(5, $producto->id_categoria);
            $stmt->bindParam(6, $coordenadas["lat"]);
            $stmt->bindParam(7, $coordenadas["lon"]);
            $stmt->bindParam(8, $ciudad);
            $stmt->bindParam(9, $producto->id_producto);

            return $stmt->execute();
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

        
    /**
     * FUNCIÓN PRINCIPAL
     * Función que obtiene todos los productos disponibles para comprar en la bd.
     * Se utiliza en el index y se le pueden aplicar filtros
     *
     * @param  object $filtros
     * @return array|false
     */
    public function obtener_productos_con_filtros($filtros) {

        $ordenar_por = [
            "fecha_asc" => "fecha_publicacion ASC",
            "fecha_desc" => "fecha_publicacion DESC",
            "precio_asc" => "precio ASC",
            "precio_desc" => "precio DESC"
        ];

        $sql = "SELECT productos.id_producto, 
            nom_producto, 
            desc_producto, 
            ciudad, 
            num_visitas, 
            precio, 
            fecha_publicacion, 
            TO_BASE64(imagen) AS imagen";
        // Marcar favoritos
        if (isset($_SESSION['perfil'])) {
            $sql .= ", EXISTS(
                SELECT 1 FROM productos_favoritos 
                    WHERE (productos_favoritos.id_producto = productos.id_producto) AND 
                        (productos_favoritos.id_usuario = :id_usuario)) AS favorito";
        }
        // Rango de precios
        $sql .= " FROM productos 
            LEFT OUTER JOIN fotos 
                ON (productos.id_producto = fotos.id_producto) 
            WHERE (precio BETWEEN :precio1 AND :precio2)";
        // Filtros categorías
        if (count($filtros->ids_categorias) > 0) {
            $sql .= " AND (id_categoria IN (";
            foreach ($filtros->ids_categorias as $id_categoria) {
                $sql .= "$id_categoria, ";
            }
            $sql = substr($sql, 0, -2);
            $sql .= "))";
        }
        // Buscador
        if ($filtros->buscador != NULL) {
            $sql .= " AND 
                ((nom_producto LIKE '%" . $filtros->buscador . "%') OR
                (desc_producto LIKE '%" . $filtros->buscador . "%'))";
        }

        // Productos en venta
        $sql .= " AND (es_vendido = 0)";

        // Productos del usuario que está viendo el catálogo
        if(isset($_SESSION['perfil']))
            $sql .= " AND (productos.id_usuario != ".$_SESSION['perfil']['id_usuario'].")";
        

        $sql .= " GROUP BY productos.id_producto 
        ORDER BY " . $ordenar_por[$filtros->ordenar_por] . " 
        LIMIT " . ($filtros->num_pag * 12) . ",12;";

        $stmt = $this->con->prepare($sql);

        if (isset($_SESSION['perfil'])) {
            $id_usuario = $_SESSION['perfil']['id_usuario'];
            $stmt->bindParam(":id_usuario", $id_usuario);
        }

        if (isset($filtros->precio_min) and isset($filtros->precio_max)) {
            $stmt->bindValue(":precio1", $filtros->precio_min);
            $stmt->bindValue(":precio2", $filtros->precio_max);
        } else {
            $stmt->bindValue(":precio1", 0);
            $stmt->bindValue(":precio2", 9999999999);
        }
        try {
            // echo $sql;
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $registros;
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que suma una visita cada vez que se accede a la vista del producto
     *
     * @param  int $id_producto
     * @return true|false
     */
    public function anade_visita($id_producto) {
        try {
            $sql = "UPDATE productos SET num_visitas = num_visitas + 1 WHERE id_producto=?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_producto);
            $stmt->execute();
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que actualiza el campo de es_vendido a true para indicar que se ha vendido el producto
     *
     * @param  int $id id del producto
     * @return true|false
     */
    public function vender_producto($id) {
        try {
            $sql = "UPDATE productos
                SET es_vendido = 1
                WHERE id_producto = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);

            return $stmt->execute();
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que obtiene todos los productos de una sola categoría
     * Utilizado para las tarjetas de recomendaciones
     *
     * @param  int $id id de categoría
     * @return array|false
     */
    public function obtiene_productos_categoria($id) {
        try {
            $sql = "SELECT productos.id_producto, 
                nom_producto, 
                desc_producto, 
                ciudad, 
                num_visitas, 
                precio, 
                fecha_publicacion, 
                TO_BASE64(imagen) AS imagen 
            FROM productos
            LEFT OUTER JOIN fotos 
                ON (productos.id_producto = fotos.id_producto)
            WHERE productos.id_categoria = ?
            GROUP BY productos.id_producto
            ORDER BY RAND()
            LIMIT 9;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            if ($stmt->execute())
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que obtiene los datos a partir de un array con los ids de varios productos
     * Se utiliza para las tarjetas de recomendaciones. Se le pasa un array con los ids de productos que
     * ha visto el usuario
     *
     * @param  array $array
     * @return array|false
     */
    public function obtener_productos_de_id($array) {
        try {
            $sql = "SELECT productos.id_producto,
                nom_producto, 
                desc_producto, 
                ciudad, 
                num_visitas, 
                precio, 
                fecha_publicacion, 
                TO_BASE64(imagen) AS imagen 
            FROM productos
            LEFT OUTER JOIN fotos 
                ON (productos.id_producto = fotos.id_producto)
            WHERE productos.id_producto IN (" . implode(",", $array) . ")
            GROUP BY productos.id_producto;";
            $stmt = $this->con->prepare($sql);
            if ($stmt->execute())
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que obtiene los productos más cercanos al usuario con sesión iniciada
     * Se utiliza para las tarjetas de recomendaciones.
     *
     * @return array|false
     */
    public function obtener_productos_cerca() {
        $coordenadas = $_SESSION["ubicacion_usuario"];
        try {
            $sql = "SELECT productos.id_producto, 
                nom_producto, 
                desc_producto, 
                latitud,
                longitud, 
                num_visitas, 
                precio,ciudad, 
                fecha_publicacion, 
                TO_BASE64(imagen) AS imagen,
            abs((abs(" . $coordenadas["lat"] . ") - abs(latitud)))  + abs(abs(" . $coordenadas["lon"] . ") - abs(longitud)) AS 'calc'
            FROM productos
            LEFT OUTER JOIN fotos 
                ON (productos.id_producto = fotos.id_producto)";
            if(isset($_SESSION["perfil"]))
                $sql .= " WHERE id_usuario != ".$_SESSION["perfil"]["id_usuario"];

            $sql .=" GROUP BY productos.id_producto
            ORDER BY calc ASC
            LIMIT 9;";

            $stmt = $this->con->prepare($sql);
            if ($stmt->execute())
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
    
    
    /**
     * Función que obtiene los nueve roductos más vistos de la base de datos.
     * Se utiliza para las tarjetas de recomendaciones.
     *
     * @return array|false
     */
    public function obtener_mas_vistos() {
        try {
            $sql = "SELECT productos.id_producto, 
                nom_producto, 
                desc_producto, 
                ciudad, 
                num_visitas, 
                precio, 
                fecha_publicacion, 
                TO_BASE64(imagen) AS imagen
            FROM productos 
            LEFT OUTER JOIN fotos 
                ON (productos.id_producto = fotos.id_producto)";
                         if(isset($_SESSION["perfil"]))
                            $sql .= " WHERE id_usuario != ".$_SESSION["perfil"]["id_usuario"];

            $sql .=" GROUP BY productos.id_producto
            ORDER BY num_visitas DESC
            LIMIT 9;";

            $stmt = $this->con->prepare($sql);
            if ($stmt->execute())
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
}

?>