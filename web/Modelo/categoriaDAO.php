<?php
require_once("conexion.php");

class categoriaDAO extends Conexion {
    private $con;

    function __construct() {
        $this->con = parent::get_conexion();
    }
    
    /**
     * Obtiene el nombre y el id de todas las categorías de la bd
     *
     * @return array|false Un array de datos si hay resultados, o false si no hay datos.
     */
    function obtiene_todas_categorias(){
        try {
            $sql = "SELECT id_categoria, nom_categoria FROM categorias";
            $stmt = $this->con->prepare($sql);
            if($stmt->execute()){
                $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return $registros;
        } catch (PDOException $e) {
            // echo $e;
            return false;
        }
    }

}

?>