<?php
require_once("conexion.php");

class mensajeDAO extends Conexion {
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
     * Función que devuelve la conversación entre dos usuarios
     *
     * @param  int $remitente
     * @param  int $receptor
     * @return array|false
     */
    public function obtiene_mensajes($remitente, $receptor) {
        try {
            $sql = "SELECT * FROM mensajes 
            WHERE (id_remitente=? AND id_receptor=?) 
                OR (id_remitente=? AND id_receptor=?);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $remitente);
            $stmt->bindParam(2, $receptor);
            $stmt->bindParam(3, $receptor);
            $stmt->bindParam(4, $remitente);
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
     * Función que inserta un nuevo mensaje en la bd
     *
     * @param  object $mensaje
     * @return true|false
     */
    public function insertar_mensaje($mensaje) {
        try {
            $sql = "INSERT INTO mensajes(contenido, fecha, hora, id_remitente, id_receptor)
            VALUES (?,?,?,?,?);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $mensaje->contenido);
            $stmt->bindParam(2, $mensaje->fecha);
            $stmt->bindParam(3, $mensaje->hora); 
            $stmt->bindParam(4, $mensaje->id_remitente);
            $stmt->bindParam(5, $mensaje->id_receptor);

            return $stmt->execute();
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

        
    /**
     * Función que obtiene todos los datos de las personas que hayan hablado con el usuario
     *
     * @param  int $id
     * @return array|false Devuelve un array de colegas, o false si no hay.
     */
    public function obtiene_colegas($id){
        try {
            $colegas = [];
            $sql = "SELECT id_remitente, id_receptor FROM mensajes WHERE (id_remitente=?) OR (id_receptor=?);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->bindParam(2, $id);
            if($stmt->execute()){
                $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach($registros as $usuario){
                    if($usuario['id_remitente'] == $id){
                        array_push($colegas, $usuario['id_receptor']);
                    } else {
                        array_push($colegas, $usuario['id_remitente']);
                    }
                }
            }
            $colegas = array_unique($colegas); //array de id de gente que ha mantenido conversación con el id
    
            if(count($colegas) > 0){
                $sql = $sql = "SELECT id_usuario, nom_usuario, TO_BASE64(foto) AS foto FROM usuarios WHERE ";
                foreach($colegas AS $colega => $valor){
                    $sql .= "id_usuario = $valor OR ";
                }
                $sql = substr($sql, 0, -3);
                // echo $sql;
                $stmt = $this->con->prepare($sql);
                $stmt->execute();
                $info_colegas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(count($info_colegas) > 0)
                    return $info_colegas;
                else
                    return false;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }        
    }
}

?>