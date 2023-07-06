<?php
class Conexion {
    private $server = "localhost";
    private $db = "segunda_oportunidad";
    private $user = "root";
    private $pssw = "";

   
    /**
     * Esta función hace la conexión a la base de datos de MySQL y la retorna
     *
     * @return PDO|string Retorna un objeto PDO si la conexión es exitosa, o un mensaje de error.
     */
    function get_conexion(){
        try {
            $conn = new PDO('mysql:host='.$this->server.'; dbname='.$this->db,$this->user,$this->pssw);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            return $conn;
        } catch(PDOException $e) {
            echo "Excepcion: ".$e;
            return $e->getMessage();
        }
    }
}
?>