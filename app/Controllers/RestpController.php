<?php

namespace App\Controllers;

use App\core\DB;
use App\core\RequestApi;
use App\core\Request;

class RestpController
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *"); // Permite cualquier dominio (cambiar * por un dominio específico si es necesario)
        header("Access-Control-Allow-Methods: *"); // Métodos permitidos
        header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Encabezados permitidos
        header("Access-Control-Allow-Credentials: true"); // Permitir credenciales si es necesario
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    public function generarToken()
    {
        $request = new RequestApi();
        $tabla = $request->post("tabla");
        $email = $request->post("email");
        $clave = $request->post("password");

        $postData = [
            'data' => [
                'email' => $email,
                'password' => $clave
            ]
        ];
    
        $jsonData = json_encode($postData);

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $_ENV["DOMINIO"].$_ENV["BASE_URL"].'api/'.$tabla.'/?op=jwtauth',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $jsonData,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        $responseArray = json_decode($response, true);

        $token = $responseArray['data'];
        echo json_encode(['token' => $token]);
    }

    public function listar(Request $request)
    {
        $tabla = $request->get('tabla');

        $filtrar = $request->get('filtro_url');

        $filtro_url = isset($filtrar) ? $filtrar : null;
         //print_r($tabla);
        $token = $request->get('token');
        //print_r($token);
      
        //$all = $request->all();
        //print_r($all);
        // Parámetros para WHERE
       
        // Construir la URL base para la API
        $url = $_ENV["DOMINIO"] . $_ENV["BASE_URL"] . 'api/' . $tabla;

        // Agregar WHERE si está presente
        if ($filtro_url) {
            // Si contiene tanto 'orderby' como 'where', agrega '/?'
            if (strpos($filtro_url, 'where') !== false) {
                $url .= '/?' . $filtro_url; // Usar '/?' para combinar filtros
            }
            // Si contiene solo 'orderby', agrega '/'
            else if (strpos($filtro_url, 'orderby') !== false) {
                $url .= '/?' . $filtro_url; // Usar '/' para orderby
            }

            else if (strpos($filtro_url, 'groupby') !== false) {
                $url .= '/?' . $filtro_url; // Usar '/' para groupby
            }

            else if (strpos($filtro_url, 'limit') !== false) {
                $url .= '/?' . $filtro_url; // Usar '/' para limit
            }

            else if (strpos($filtro_url, 'columns') !== false) {
                $url .= '/?' . $filtro_url; // Usar '/?' para columns
            } 
            // Otros casos (where u otros filtros)
            else {
                $url .= '/' . $filtro_url; // Usar '/?' por defecto
            }
        }

        // Debug: Mostrar URL generada y parámetros
        //print_r($all);
        //echo $url;
        //die();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '. $token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $responseArray = json_decode($response, true);
        $data = isset($responseArray['data']) ? $responseArray['data'] : [];

        if ($data && $token) {
            echo json_encode(['data' => $data]);
        } else {
            echo json_encode(['error' => 'Token no encontrado en el encabezado Authorization']);
        }
    }
 

    public function insertar(){
        $request = new RequestApi();
        $tabla = $request->post("tabla");
        $token = $request->post('token');
        $datos = $request->all();
        //print_r($datos);

        if (!$tabla) {
            echo json_encode(['error' => 'Falta la tabla']);
            return;
        }

        unset($datos['tabla']);
        unset($datos['token']);

        $postData = [
            'data' => $datos
        ];
    
        $jsonData = json_encode($postData);
        $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV["DOMINIO"] . $_ENV["BASE_URL"] . 'api/'.$tabla,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '. $token
            ),
        ));

        $response = curl_exec($curl);

        $responseArray = json_decode($response, true);
        $data = $responseArray['data'];
        if($data && $token){
            echo json_encode(['mensaje' => 'Dato Insertado con éxito', 'id' => $data]);
        } else {
            echo json_encode(['error' => 'Token no encontrado en el encabezado Authorization']);
        }
    }

    public function actualizar(){
        $request = new RequestApi();
        $tabla = $request->post("tabla");
        $token = $request->post('token');
        $pk = $request->post("pk");
        $datos = $request->all();

        if (!$tabla) {
            echo json_encode(['error' => 'Falta la tabla']);
            return;
        }

        unset($datos['tabla']);
        unset($datos['token']);
        unset($datos['pk']);

        $postData = [
            'data' => $datos
        ];

        $jsonData = json_encode($postData);

        $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV["DOMINIO"] . $_ENV["BASE_URL"] . 'api/'.$tabla.'/'.$pk,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        
        $responseArray = json_decode($response, true);
        $data = $responseArray['data'];
        if($data && $token){
            echo json_encode(['mensaje' => 'Dato Actualizado con éxito', 'id' => $data]);
        } else {
            echo json_encode(['error' => 'Token no encontrado en el encabezado Authorization']);
        }
    }

    public function eliminar(){
        $request = new RequestApi();
        $tabla = $request->post("tabla");
        $token = $request->post('token');
        $pk = $request->post("pk");
        $datos = $request->all();

        if (!$tabla) {
            echo json_encode(['error' => 'Falta la tabla']);
            return;
        }

        unset($datos['tabla']);
        unset($datos['token']);
        unset($datos['pk']);

        $postData = [
            'data' => $datos
        ];

        $jsonData = json_encode($postData);

        $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $_ENV["DOMINIO"] . $_ENV["BASE_URL"] . 'api/'.$tabla.'/'.$pk,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);
        
        $responseArray = json_decode($response, true);
        $data = $responseArray['data'];
        if($data && $token){
            echo json_encode(['mensaje' => 'Dato Eliminado con éxito', 'id' => $data]);
        } else {
            echo json_encode(['error' => 'Token no encontrado en el encabezado Authorization']);
        }
    }
}
