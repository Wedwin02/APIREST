<?php
require_once 'class/respuestas.class.php';
require_once 'class/pacientes.class.php';


$_respuestas = new respuestas;
$_pacientes = new pacientes;
header('Access-Control-Allow-Origin:  *');   
header('Access-Control-Allow-Headers:  Content-Type'); 
header('Access-Control-Allow-Methods:  POST,GET,PUT,DELETE'); 
if($_SERVER['REQUEST_METHOD']== "GET"){
   if(isset($_GET['page'])){
       $pagina = $_GET['page'];
       $ListaPacientes = $_pacientes->ListaPacientes($pagina);

       header('content-Type: application/json');
       echo json_encode($ListaPacientes);
       http_response_code(200);

   }else if(isset($_GET['id'])){
       $pacienteId = $_GET['id'];
       
       $datosPacienteId = $_pacientes->ObtenerPaciente($pacienteId);
       header('content-Type: application/json');
       echo json_encode($datosPacienteId);
       http_response_code(200);

   }

}else if($_SERVER['REQUEST_METHOD']== "POST"){
    
    //recibir datos 
    $postBody = file_get_contents("php://input");
    //enviar al manejador
    $datosArray = $_pacientes->post($postBody);

    //devolvemos una respuesta
    header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);



}else if($_SERVER['REQUEST_METHOD']== "PUT"){
    //utilizado para modificar informacion 
    //recibir datos 
    $postBody = file_get_contents("php://input");
    //enviar datos 
    $datosArray = $_pacientes->put($postBody);
    //devolvemos una respuesta
    header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD']== "DELETE"){
    
    $headers = getallheaders();
    if(isset($headers["pacienteid"]) && isset($headers["token"])){
        //datos enviados por el header
        $send = [
            "pacienteid" => $headers["pacienteid"],
            "token" => $headers["token"]
            
        ];
        $postBody = json_encode($send);
    }else{
        //utilizado para eliminar 
        //recibir datos 
        $postBody = file_get_contents("php://input");
    }
     
    //enviar datos 
    $datosArray = $_pacientes->delete($postBody);
    //devolvemos una respuesta
    header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
}else{
    header('content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
