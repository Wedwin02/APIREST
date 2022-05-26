<?php


class respuestas{

    public $response = [
        'status'=>'Ok',
        'result'=>array()
    ];

    public function error_405(){
        $this->response['status']="error";
        $this->response['result'] = array(
            "error_id" => "405",
            "error_msg" => "Metodo no permitido"
        );
        return $this->response;
    }

    public function error_200($query= "Datos incorrectos"){
        $this->response['status']="error";
        $this->response['result'] = array(
            "error_id" => "200",
            "error_msg" => $query
        );
        return $this->response;
    }
    
    public function error_400(){
        $this->response['status']="error";
        $this->response['result'] = array(
            "error_id" => "400",
            "error_msg" =>"Datos enviados incompletos ò con formato incorrecto"
        );
        return $this->response;
    }
    public function error_500($query= "Datos Error interno del servidor"){
        $this->response['status']="error";
        $this->response['result'] = array(
            "error_id" => "500",
            "error_msg" => $query
        );
        return $this->response;
    }
    public function error_401($query= "No autorizado"){
        $this->response['status']="error";
        $this->response['result'] = array(
            "error_id" => "401",
            "error_msg" => $query
        );
        return $this->response;
    }

}






?>