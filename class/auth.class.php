 <?php
  require_once 'conexion/conexion.php';
  require_once 'respuestas.class.php';


  class auth extends conexion{

    //metodo login 
    public function login($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['usuario']) || !isset($datos['password'] )){
            return $_respuestas->error_400();
        }else{
            //Todo bueno
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = parent::encriptar($password);
            $datos = $this->obtenerDatosUsuarios($usuario);
            if($datos){
                //verificar si la contraseña son iguales 
                    if($password == $datos[0]['Password']){
                        if($datos[0]['Estado']=='Activo'){
                            //Crear TOKEN
                            $verificarToken = $this->insertarToken($datos[0]['UsuarioId']);
                            if($verificarToken){
                                //Se guardo el token 
                                $result = $_respuestas->response;
                                $result["result"] = array(
                                    "token" => $verificarToken
                                );
                                return $result;

                            }else{
                                //Error al guardar el token
                                return $_respuestas->error_500(); 
                            }
                        }else{
                            return $_respuestas->error_200("El usuario esta inactivo"); 
                        }
                    }else{
                        return $_respuestas->error_200("El password es invalido"); 
                    }
            }else{
                //No existe el usuario
                return $_respuestas->error_200("El usuario $usuario no existe"); 
            }
        }

    }

    private  function obtenerDatosUsuarios($correo){
        $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$correo'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
        }else{
            return 0;
        }
    }

    private function insertarToken($usuarioId){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)Value('$usuarioId','$token','$estado','$date')";
        $verificar = parent::nonQuery($query);
        if($verificar){
                return $token;
        }else{
            return 0;
        }








    }



  }
 
 
 
 
 
 ?>