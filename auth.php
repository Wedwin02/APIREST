<?php

    require_once 'class/auth.class.php';
    require_once 'class/respuestas.class.php';

    $_auth = new auth;
    $_respuestas = new respuestas;  


     
    header('Access-Control-Allow-Origin:  *');   
    header('Access-Control-Allow-Headers:  Content-Type'); 
    header('Access-Control-Allow-Methods:  POST,GET,PUT,DELETE'); 
    if($_SERVER['REQUEST_METHOD'] =='POST'){

        //Resibir datos 
        $postBody = file_get_contents("php://input");

        //Envair datos al manejador
        $datosArray = $_auth->login($postBody);

        //Respuesta
        header('Content-Type: application/json');
        if(isset($datosArray["result"]["error_id"])){
                $responseCode = $datosArray["result"]["error_id"];
                http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }






?>