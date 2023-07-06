<?php
require_once("conexion.php");

class fotoDAO extends Conexion {
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
     * Función que devuelve todas las imágenes de un producto
     *
     * @param  int $id_producto
     * @return array|false Devuelve las fotos si hay, o false si no hay ninguna
     */
    public function fotos_por_producto($id_producto) {
        try {
            $sql = "SELECT TO_BASE64(imagen) AS imagen, id_foto FROM fotos WHERE id_producto = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_producto);
            $stmt->execute();
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($registros) > 0)
                return $registros;
            else
                return false; 
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
    

    /**
     * Función que introduce una o varias fotos en la bd
     *
     * @param  int $id_producto
     * @param  array $ficheros
     * @return true|false Devuelve true o false dependiendo de si la ejecución ha sido exitosa.
     */
    public function subir_fotos_producto($id_producto, $ficheros) {
        try {
            if (count($ficheros) > 0) {
                $sql = "INSERT INTO fotos(id_producto,imagen) VALUES(?,?)";
                for ($i = 1; $i < count($ficheros); $i++) {
                    $sql .= ",(?,?)";
                }
                $sql .= ";";
                $stmt = $this->con->prepare($sql);
                for ($i = 1, $f = 0; $f < count($ficheros); $i++, $i++, $f++) {
                    $stmt->bindParam($i, $id_producto);
                    $stmt->bindValue($i + 1, file_get_contents($ficheros[$f]));
                }
                return $stmt->execute();
            } 
        } catch (PDOException $e) {
             echo "Excepcion: ".$e;
            return false;
        }
    }

        
    /**
     * Función que borra las fotos de uno o varios productos
     *
     * @param  array $ids_fotos
     * @return true|false
     */
    public function borrar_fotos($ids_fotos) {
        try {
            $sql = "DELETE FROM fotos WHERE id_foto IN (" . implode(",", $ids_fotos) . ");";
            $stmt = $this->con->prepare($sql);
            return $stmt->execute();
        } catch (Exception) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }
}
