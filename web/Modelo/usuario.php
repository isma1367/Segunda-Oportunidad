<?php

class Usuario {
    private $id_usuario;
    private $nom_usuario;
    private $apellidos;
    private $correo;
    private $telefono;
    private $fecha_nacimiento;
    private $clave;
    
    /**
     * Constructor de la clase.
     * Valida los campos para que se puedan introducir en la base de datos
     *
     * @param  string $nom
     * @param  string $ape
     * @param  string $nac
     * @param  string $tel
     * @param  string $cor
     * @param  string $cla
     * @return void
     */
    public function __construct($nom, $ape, $nac, $tel, $cor, $cla) {
        $this->nom_usuario = $nom;
        $this->apellidos = $ape;
        $this->correo = $cor;
        $this->telefono = $tel;
        $this->fecha_nacimiento = $nac;
        $this->clave = $cla;

        if(strlen($nom) > 30 && strlen($nom) < 2 && ctype_alpha($nom))
            $this->es_valido = false;
        if(strlen($ape) > 50 && strlen($ape) < 4 && ctype_alpha($ape))
            $this->es_valido = false;
        if(strlen($tel) != 9 && ctype_digit($tel))
            $this->es_valido = false;
            
            $this->valida_clave();
            $this->validar_fecha_nac();
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

    
    /**
     * Función que comprueba que la clave introducida por el usuario es válida siguiendo
     *  - 8 caracteres o más
     *  - Contiene mayúsculas
     *  - Contiene minúsculas
     *  - Contiene números
     *
     * @return void
     */
    public function valida_clave(){
        $clave = $this->clave;
        if(strlen($clave) < 8) {
            $this->es_valido = false;
        }
        if(preg_match("/[A-Z]/", $clave) == 0) {
            $this->es_valido = false;
        }
        if(preg_match("/[a-z]/", $clave) == 0) {
            $this->es_valido = false;
        }
        if(preg_match("/[0-9]/", $clave) == 0) {
            $this->es_valido = false;
        }
    }

    
    /**
     * Función que comprueba que un usuario es mayor de edad para poder utilizar la aplicación
     *
     * @return void
     */
    public function validar_fecha_nac(){
        $fecha_nacimiento = $this->fecha_nacimiento;
        $nacimiento = new DateTime($fecha_nacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);
        
        $this->es_valido = ($diferencia->format("%y") > 18);
    }
}
?>