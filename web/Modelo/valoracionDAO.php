<?php
require_once("conexion.php");

class valoracionDAO extends Conexion {
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
     * Función que obtiene todas las valoraciones de un mismo vendedor
     *
     * @param  int $id_vendedor
     * @return array|false
     */
    public function obtiene_valoraciones($id_vendedor){
        try {
            $sql = "SELECT usuarios.nom_usuario, valoraciones.comentario, valoraciones.valor FROM valoraciones 
            INNER JOIN usuarios
                ON(valoraciones.id_cliente = usuarios.id_usuario)
            WHERE id_vendedor=?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_vendedor);
            if($stmt->execute()){
                $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            return $registros;
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }    
    }

    
    /**
     * Función que calcula la media de todas las valoraciones que ha recibido un usuario
     *
     * @param  int $id_vendedor
     * @return float|false
     */
    public function media_vendedor($id_vendedor){
        try {
            $sql = "SELECT AVG(valor) AS media FROM valoraciones WHERE id_vendedor=?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_vendedor);
            if($stmt->execute()){
                $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return $registro['media']; //se devuelve directamente el número
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que imprime estrellas en función de la media que tiene un usuario
     *
     * @param  float $media
     * @return string Devuelve el html para imprimir dichas estrellas
     */
    public function pinta_estrellas($media) {
        $html = "";
        $entero = intval($media);
        $decimal = $media - $entero;
        for ($i = 0; $i < $entero; $i++) {
            $html .= '<i class="bi bi-star-fill"></i>';
        }
        if ($decimal >= 0.5) {
            $html .= '<i class="bi bi-star-half"></i>';
        }
        $vacias = 5 - $entero - ceil($decimal);
        for ($i = 0; $i < $vacias; $i++) {
            $html .= '<i class="bi bi-star"></i>';
        }
        $html .= "(".round($media, 2).")";
        return $html;
    }

        
    /**
     * Función para borrar una valoración
     *
     * @param  int $id id de valoración
     * @return true|false
     */
    public function borrar_valoracion($id) {
        try {
            $sql = "DELETE FROM valoraciones WHERE id_valoracion = ?;";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(1, $id);
            if($stm->execute()){
                return true;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        } 
    }

        
    /**
     * Función que comprueba si ya existe una valoración de un usuario concreto a otro
     *
     * @param  int $id_vendedor
     * @param  int $id_cliente
     * @return array|false Devuelve un array con los datos de la valoración si existe y false si no existe
     */
    public function existe_valoracion($id_vendedor, $id_cliente) {
        try {
            $sql = "SELECT * FROM valoraciones WHERE id_vendedor = ? AND id_cliente = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id_vendedor);
            $stmt->bindParam(2, $id_cliente);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            return $registro;
        } catch (PDOException $e) {
            return false;
        }
    }

        
    /**
     * Función que inserta en la base de datos una valoración nueva.
     * Comprueba previamente si existe o no
     *
     * @param  object $valoracion
     * @return true|false
     */
    public function insertar_valoracion($valoracion){
        try {
            if(!self::existe_valoracion($valoracion->id_vendedor, $valoracion->id_cliente)){
                $sql = "INSERT INTO valoraciones(valor, comentario, id_vendedor, id_cliente)
                VALUES (?,?,?,?);";

                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(1, $valoracion->valor);
                $stmt->bindParam(2, $valoracion->comentario);
                $stmt->bindParam(3, $valoracion->id_vendedor);
                $stmt->bindParam(4, $valoracion->id_cliente);

                return $stmt->execute();
            } else return false;
        } catch (PDOException $e) {
            return false;
        }
    }

        
    /**
     * Función que edita una valoración ya existente
     *
     * @param  object $valoracion
     * @return true|false
     */
    public function editar_valoracion($valoracion) {
        try {
            $sql = "UPDATE valoraciones SET valor = ?, comentario = ? WHERE id_vendedor = ? AND id_cliente = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $valoracion->valor);
            $stmt->bindParam(2, $valoracion->comentario);
            $stmt->bindParam(3, $valoracion->id_vendedor);
            $stmt->bindParam(4, $valoracion->id_cliente);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }
}