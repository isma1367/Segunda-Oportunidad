<?php
require_once("conexion.php");

class favoritoDAO extends Conexion {
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
     * Función que te dice si un usuario tiene en "favoritos" un producto
     *
     * @param  int $id_usuario
     * @param  int $id_producto
     * @return true|false Devuelve true si existe el producto favorito, o false si no.
     */
    public function existe_favorito($id_usuario, $id_producto){
        try {
            $sql = "SELECT * FROM productos_favoritos WHERE id_usuario=? AND id_producto=?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_usuario);
            $stmt->bindParam(2, $id_producto);
            if($stmt->execute()){
                $registro = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!empty($registro)){
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
        
    }
    
    
    /**
     * Función que introduce la combinación de id_usuario e id_producto en la tabla "productos_favoritos"
     *
     * @param  int $id_usuario
     * @param  int $id_producto
     * @return true|false Devuelve true o false dependiendo de si la ejecución ha sido exitosa.
     */
    public function dar_like($id_usuario, $id_producto){
        try {
            $sql = "INSERT INTO productos_favoritos(id_usuario, id_producto) VALUES (?, ?);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_usuario);
            $stmt->bindParam(2, $id_producto);
            return $stmt->execute();
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
    
    
    /**
     * Función que elimina la combinación de id_usuario e id_producto en la tabla "productos_favoritos"
     *
     * @param  int $id_usuario
     * @param  int $id_producto
     * @return true|false Devuelve true o false dependiendo de si la ejecución ha sido exitosa.
     */
    public function quitar_like($id_usuario, $id_producto){
        try {
            $sql = "DELETE FROM productos_favoritos WHERE id_usuario=? AND id_producto=?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_usuario);
            $stmt->bindParam(2, $id_producto);
            return $stmt->execute();
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
    
    /**
     * Función que devuelve todos los productos que un usuario tiene como favoritos
     *
     * @param  int $id_usuario
     * @return array|false Devuelve un array con todos los productos favoritos de un usuario, o false si no hay ninguno
     */
    public function productos_favoritos_usuario($id_usuario){
        try {
            $sql = "SELECT productos.id_producto,
            nom_producto,
            desc_producto,
            ciudad,
            precio,
            TO_BASE64(imagen) AS imagen
            FROM productos
            INNER JOIN productos_favoritos 
                ON (productos.id_producto = productos_favoritos.id_producto)
            LEFT OUTER JOIN fotos
                ON (productos.id_producto = fotos.id_producto)
            WHERE productos_favoritos.id_usuario = ?
            GROUP BY (productos.id_producto);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_usuario);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($registros) > 0){
                return $registros;
            } else {
                return false;
            }     
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
}
