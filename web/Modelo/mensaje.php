<?php
class Mensaje {
    private $id_mensaje;
    private $contenido;
    private $fecha; //Y-m-d
    private $hora; //h-m-s
    private $id_remitente;
    private $id_receptor;

    /**
     * Constructor de la clase.
     * Define el contenido, el id_remitente y el id_receptor del mesaje. Asigna automáticamente la fecha y la hora
     *
     * @param string $con El contenido del mensaje.
     * @param int $remitente El ID del remitente.
     * @param int $receptor El ID del receptor.
     */
    public function __construct($con, $remitente, $receptor) {
        $this->contenido = $con;
        $this->fecha = date("Y-m-d");
        $this->hora = date("H:i:s");
        $this->id_remitente = $remitente;
        $this->id_receptor = $receptor;
    }


    /**
     * @param string $propiedad
     * @return mixed
     */
    public function &__get($propiedad) {
        return $this->$propiedad;
    }


    /**
     * Asigna un valor a una propiedad dinámicamente.
     *
     * @param string $propiedad El nombre de la propiedad.
     * @param mixed $valor El valor a asignar.
     * @return void
     */
    public function __set($propiedad, $valor) {
        $this->$propiedad = $valor;
    }
}
