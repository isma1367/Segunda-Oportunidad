<?php
class Valoracion {
    private $id_valoracion;
    private $valor;
    private $comentario;
    private $id_vendedor;
    private $id_cliente;
    
    /**
     * Constructor de la clase.
     *
     * @param  int $val
     * @param  string $com
     * @param  int $ven
     * @param  int $cli
     * @return void
     */
    public function __construct($val, $com, $ven, $cli) {
        $this->valor = $val;
        $this->comentario = $com;
        $this->id_vendedor = $ven;
        $this->id_cliente = $cli;     
    }

    /**
     * @param string $propiedad
     * @return mixed
     */
    public function &__get($propiedad){
        return $this->$propiedad;
    }


    /**
     * Asigna un valor a una propiedad dinámicamente.
     *
     * @param string $propiedad El nombre de la propiedad.
     * @param mixed $valor El valor a asignar.
     * @return void
     */
    public function __set($propiedad, $valor){
        $this->$propiedad = $valor;
    }
}
?>