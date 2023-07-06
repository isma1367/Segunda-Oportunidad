<?php

class Producto {
    private $id_producto;
    private $nom_producto;
    private $desc_producto;
    private $ubicacion;
    private $num_visitas;
    private $precio;
    private $fecha_publicacion;
    private $id_categoria;
    private $id_usuario;

    /**
     * Constructor de la clase.
     *
     * @param  int $id
     * @param  string $nom
     * @param  string $des
     * @param  string $ubi
     * @param  float $pre
     * @param  string $cat
     * @param  int $usu
     * @return void
     */
    public function __construct($id,$nom, $des, $ubi, $pre, $cat, $usu) {
        $this->id_producto = $id;
        $this->nom_producto = $nom;
        $this->desc_producto = $des;
        $this->ubicacion = $ubi;
        $this->precio = $pre;       
        $this->id_categoria = $cat;        
        $this->id_usuario = $usu;        
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