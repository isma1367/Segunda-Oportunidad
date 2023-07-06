<?php
require_once("conexion.php");

class UsuarioDAO extends Conexion { 
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
     * Función que comprueba si existe un usuario con correo especificado.
     *
     * @param  string $correo Correo del usuario
     * @return array|false Devuelve un array de los datos de ese usuario, o false si no existe.
     */
    public function existe_email($correo) {
        try {
            $sql = "SELECT id_usuario, 
                nom_usuario,
                apellidos, 
                fecha_nacimiento, 
                telefono, 
                correo, 
                clave, 
                TO_BASE64(foto) AS foto 
            FROM usuarios WHERE correo = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $correo);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            return $registro;
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }


    /**
     * Función que comprueba si existe un usuario con un ID especificado.
     *
     * @param  int $id ID del usuario
     * @return array|false Devuelve un array de los datos de ese usuario, o false si no existe.
     */
    public function existe_ID($id) {
        try {
            $sql = "SELECT id_usuario, 
                nom_usuario, 
                apellidos, 
                fecha_nacimiento, 
                telefono, 
                correo, 
                clave, 
                TO_BASE64(foto) AS foto 
            FROM usuarios WHERE id_usuario = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
            return $registro;
        } catch (PDOException $e) {
            // echo "Excepcion: ".$e;
            return false;
        }
    }

    
    /**
     * Función que inserta un usuario nuevo en la base de datos
     *
     * @param  object $usuario
     * @return true|false
     */
    public function insertar_usuario($usuario) {
        $clave_cifrada = password_hash($usuario->clave, PASSWORD_DEFAULT);
        try {
            $sql = "INSERT INTO usuarios(nom_usuario, apellidos, fecha_nacimiento, telefono, correo, clave) VALUES (?,?,?,?,?,?);";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $usuario->nom_usuario);
            $stmt->bindParam(2, $usuario->apellidos);
            $stmt->bindParam(3, $usuario->fecha_nacimiento); //Y-m-d
            $stmt->bindParam(4, $usuario->telefono);
            $stmt->bindParam(5, $usuario->correo);
            $stmt->bindParam(6, $clave_cifrada);

            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    
    /**
     * Función que se usa cuando se inicia sesión.
     * Comprueba que el correo introducido existe y que la contraseña es la correcta.
     *
     * @param  string $correo
     * @param  string $clave
     * @return true|false
     */
    public function login($correo, $clave) {
        try {
            if (self::existe_email($correo) != false) {
                // print_r(self::existe_email($correo));
                if (password_verify($clave, self::existe_email($correo)['clave'])) 
                    return true;
                else
                    return false;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            return false;
        }
    }

    
    /**
     * Función que genera un código aleatorio de 6 cifras.
     * Este código es el que emplea el usuario para recuperar la contraseña.
     *
     * @return int
     */
    public function generar_codigo_aleatorio() {
        $cad = "";
        for ($i = 0; $i < 6; $i++) {
            $cad .= rand(0, 9);
        }
        return $cad;
    }

    
    /**
     * Función que manda un correo al usuario cuando este indica que quiere recuperar la contraseña.
     * Para ello, llama a "send.php" que se encarga de mandar el mensaje.
     * Para mandarlo, primero verifica que existe el correo que se ha introducido.
     * Tambiñen introduce en la base de datos el correo y el código de recuperación cifrado, 
     * comprobando previamente si ya existe ese correo en "codigos_recuperacion"
     *
     * @param  string $correo
     * @return array $aviso Devuelve un aviso dependiendo de si ha sido un éxito o qué errores ha encontrado
     */
    public function enviar_recuperar_pssw($correo) {
        try {
            //Aviso por defecto
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Ha habido un problema al enviar el código."
            );
            // Se inerta el código en la tabla de contraseñas temporales
            $sql = "SELECT codigo,
                minute(timediff(now(),hora_envio)) AS 'tiempo' 
            FROM codigos_recuperacion WHERE correo = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $correo);
            if ($stmt->execute()) {
                $fila = $stmt->fetch(PDO::FETCH_ASSOC);
                if(isset($fila["codigo"]) && ($fila["tiempo"] >= 15) || !isset($fila["codigo"])){
                    //Primero borramos el codigo (en el caso de que tenga uno)
                    $stm = $this->con->prepare("DELETE FROM codigos_recuperacion WHERE correo = ?");
                    $stm->bindParam(1,$correo);
                    $stm->execute();

                    //Metemos el nuevo codigo
                    $sql = "INSERT INTO codigos_recuperacion (correo, codigo) VALUES(?, ?);";
                    $codigo = self::generar_codigo_aleatorio();
                    $codigo_hash = password_hash($codigo, PASSWORD_DEFAULT);
                    $stmt = $this->con->prepare($sql);
                    $stmt->bindParam(1, $correo);
                    $stmt->bindValue(2, $codigo_hash);

                    if ($stmt->execute()) {
                        require_once("../Modelo/send.php");
                        $aviso = array(
                            "tipo" => "exito",
                            "titulo" => "¡GENIAL!",
                            "mensaje" => "El código de verificación se ha mandado correctamente a tu correo. Por favor, revisa la carpeta de SPAM."
                        );
                    }
                } else
                    $aviso = array(
                        "tipo" => "warning",
                        "titulo" => "AVISO",
                        "mensaje" => "Ya hemos mandado un código de recuperación a esta cuenta. Por favor, revisa la carpeta de SPAM."
                    );
            }
            return $aviso;
        } catch (Exception $e) {
            $aviso = array(
                "tipo" => "error",
                "titulo" => "ERROR",
                "mensaje" => "Error de servidor."
            );
            return $aviso;
        }
    }

    
    /**
     * Función que verifica si el código enviado al correo y el introducido por el usuario es el mismo.
     * Se utiliza para la recuperación de contraseña
     *
     * @param  string $correo
     * @param  int $codigo
     * @return true|false
     */
    public function verificar_codigo($correo, $codigo) {
        try {
            $sql = "SELECT codigo FROM codigos_recuperacion WHERE correo = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $correo);

            if ($stmt->execute())
                return password_verify($codigo, $stmt->fetch(PDO::FETCH_ASSOC)["codigo"]);
            else
                return false;
        } catch (PDOException $e) {
            return false;
        }
    }

        
    /**
     * Función que cambia la clave de acceso.
     * Se utiliza una vez verificado el código de recuperación.
     *
     * @param  string $correo
     * @param  string $contrasena
     * @return true|false
     */
    public function cambiar_contrasena($correo, $contrasena) {
        try {
            $cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET clave = ? WHERE correo = ?;";
            $stm = $this->con->prepare($sql);
            $stm->bindParam(1, $cifrada);
            $stm->bindParam(2, $correo);

            return $stm->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    
    /**
     * Función que modifica la foto del usuario en la base de datos
     *
     * @param  int $id
     * @param  mixed $foto
     * @return true|false
     */
    public function cambiar_avatar($id, $foto) {
        try {
            $sql = "UPDATE usuarios SET foto = ? WHERE id_usuario = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $foto);
            $stmt->bindParam(2, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            // echo $e;
            return false;
        }
    }

    
    /**
     * Función que coge las coordenadas de un usuario
     *
     * @return array
     */
    public function get_ubicacion_usuario() {
        // Desarrollo
        $coordenadas["lat"] = 40.524357;
        $coordenadas["lon"] = -3.682811;

        //Produccion    
        // $location = json_decode(file_get_contents("https://api.geoapify.com/v1/ipinfo?ip=".$_SERVER['REMOTE_ADDR']."&apiKey=fe245a628dd5432aa2047727cf89a15b"))->location;
        // $coordenadas["lat"] = $location->latitude;
        // $coordenadas["lon"] = $location->longitude;
        
        return $coordenadas;
    }

    
    /**
     * Función que cambia la contraseña por una nueva en el apartado de configuración del usuario
     *
     * @param  int $id id de usuario
     * @param  string $actual contraseña actual
     * @param  string $clave1 contraseña nueva
     * @param  string $clave2 verificación de contraseña nueva
     * @return true|false
     */
    public function configuracion_clave($id, $actual, $clave1, $clave2) {
        try {
            $usuario = self::existe_ID($id);
            // Comprobar que la contraseña actual es igual a la que introduce
            if (password_verify($actual, $usuario['clave'])) {
                // Si son iguales, primero verifico que las dos nuevas son iguales
                if ($clave1 == $clave2) {
                    // Si son iguales, la introduzco en la bd ENCRIPTADA
                    $clave1 = password_hash($clave1, PASSWORD_DEFAULT);
                    $sql = "UPDATE usuarios SET clave = ? WHERE id_usuario = ?;";
                    $stmt = $this->con->prepare($sql);
                    $stmt->bindParam(1, $clave1);
                    $stmt->bindParam(2, $id);
                    if ($stmt->execute()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    // echo "Las claves no son iguales";
                    return false;
                }
            } else {
                // echo "Tu contraseña actual no corresponde con la introducida";
                return false;
            }
        } catch (Exception $e) {
            // echo $e;
            return false;
        }
    }

    
    /**
     * Función que borra el usuario de la base de datos
     * CUIDADO: al borrar un usuario también se borran los productos que ha subido junto con sus fotos,
     * productos favoritos, mensajes y valoraciones
     * Se utiliza para eliminar la cuenta en configuración del usuario
     *
     * @param  int $id
     * @return true|false
     */
    public function borrar_usuario($id) {
        try {
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);

            return $stmt->execute();
        } catch (Exception $e) {
            // echo $e;
            return false;
        }
    }

        
    /**
     * Función que borras los datos recogidos en cookies y sesión al cerrar la cuenta.
     *
     * @return void
     */
    public function borrar_datos_session_usuario()  {
        try {
            session_destroy();
            unset($_SESSION["perfil"]);
            setcookie("es_logeado", "", time() - 10, "/");
            unset($_COOKIE["es_logeado"]);
        } catch (\Throwable $th) {
            echo $th;
        }
    }

    
    /**
     * Función que modifica los datos de un usuario
     *
     * @param  array $datos
     * @param  int $id
     * @return true|false
     */
    public function editar_usuario($datos, $id) {
        try {
            $sql = "UPDATE usuarios SET";
            foreach ($datos as $dato => $valor) {
                $sql .= " $dato = '$valor',";
            }
            $sql = substr($sql, 0, strlen($sql) - 1);
            $sql .= " WHERE id_usuario = ?;";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
