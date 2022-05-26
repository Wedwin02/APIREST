<?php

require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';
class pacientes extends conexion{
    private $table = "Pacientes";
    private $pacienteid= "";
    private $dni= "";
    private $nombre= "";
    private $direccion= "";
    private $codigoP= "";
    private $telefono= "";
    private $genero= "";
    private $fechaNacimiento= "0000-00-00";
    private $correo= "";
    private $token = "";
    //c1b8b004b4a094532eadbe83db385a6d

    public function ListaPacientes($pagina=1){
        
        $inicio = 0;
        $cantidad = 10;
        if($pagina >1){
            $inicio = ($cantidad *($pagina-1))+1;
            $cantidad = $cantidad*$pagina;
        }
        $query = "SELECT PacienteId,DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo FROM ". $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return($datos);

    }
    public function ObtenerPaciente($id){
        $query= "SELECT * FROM ". $this->table . " WHERE PacienteId = '$id'";
        return parent::obtenerDatos($query);
    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);
        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{           
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken){
            if(!isset($datos['dni']) || !isset($datos['nombre']) || !isset($datos['direccion']) 
            || !isset($datos['codigop']) || !isset($datos['telefono']) || !isset($datos['genero'])
            ||!isset($datos['fechaNacimiento'])||!isset($datos['correo'])){
                return $_respuestas->error_400();
            }else{
                $this->dni = $datos['dni'];
                $this->nombre = $datos['nombre'];
                $this->direccion = $datos['direccion'];
                $this->codigoP = $datos['codigop'];
                $this->telefono = $datos['telefono'];
                $this->genero = $datos['genero'];
                $this->fechaNacimiento = $datos['fechaNacimiento'];
                $this->correo = $datos['correo'];
                $resp = $this->insertarPaciente();
                if($resp){
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "pacienteId"=> $resp
                    );
                    return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }

            }            }else{
                    return $_respuestas->error_401("Token invalido ò ha caducado");
                }
            }      
    
    }
      
    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken){
                if(!isset($datos['pacienteid']) ){
                    return $_respuestas->error_400();
                }else{
                    $this->pacienteid = $datos['pacienteid'];
                    $this->dni = $datos['dni'];
                    $this->nombre = $datos['nombre'];
                    $this->direccion = $datos['direccion'];
                    $this->codigoP = $datos['codigop'];
                    $this->telefono = $datos['telefono'];
                    $this->genero = $datos['genero'];
                    $this->fechaNacimiento = $datos['fechaNacimiento'];
                    $this->correo = $datos['correo'];
                    $resp = $this->ModificarPaciente();
                    
                    if($resp){
                       $respuesta = $_respuestas->response;
                       $respuesta["result"] = array(
                          "PacienteId"=> $this->pacienteid
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
        
                }

            }else{
                return $_respuestas->error_401("Token invalido ò ha caducado");
            }
        }
  
    } 
    
    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken = $this->buscarToken();
            if ($arrayToken){
                if(!isset($datos['pacienteid']) ){
                    return $_respuestas->error_400();
                }else{
                    $this->pacienteid = $datos['pacienteid'];           
                    $resp = $this->EliminarPaciente();
                    
                    if($resp){
                       $respuesta = $_respuestas->response;
                       $respuesta["result"] = array(
                          "PacienteId"=> $this->pacienteid
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
        
                }

            }else{
                return $_respuestas->error_401("Token invalido ò ha caducado");
            }
        }
        
        
    }
    private function EliminarPaciente(){
        $query = "DELETE FROM ". $this->table . " WHERE PacienteId = '". $this->pacienteid ."'"; 
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
          }else{
            return 0;
          }

    }

    private function ModificarPaciente(){
        $query = " UPDATE " . $this->table . " SET DNI = '".$this->dni."',Nombre = '".$this->nombre."', Direccion = '".$this->direccion."' , CodigoPostal = '"
        .$this->codigoP."', Telefono = '".$this->telefono."', Genero = '".$this->genero."', FechaNacimiento = '".$this->fechaNacimiento."' , correo = '".
         $this->correo ."' WHERE PacienteId = '". $this->pacienteid. "';";   

        $resp = parent::nonQuery($query);

        if($resp >= 1){
          return $resp;
        }else{
          return 0;
        }
    }

    private function insertarPaciente(){
        $query = "INSERT INTO " . $this->table . "(DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo) 
        VALUE
        ('".$this->dni ."','".$this->nombre ."','".$this->direccion ."','".$this->codigoP."','".$this->telefono."','".$this->genero."','".$this->fechaNacimiento."','".$this->correo."')";
        $resp = parent::nonQueryId($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }  

    private function buscarToken(){
        $query = "SELECT TokenId,UsuarioId,Estado FROM usuarios_token WHERE Token = '".$this->token."' AND Estado = 'Activo'";
        $resp= parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid'";
        $resp = parent::nonQuery($query);
        if($resp>=1){
            return $resp;
         }else{
            return 0;
        }

    }


}






?>