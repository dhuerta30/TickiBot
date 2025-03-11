<?php

namespace App\core;

class RequestApi
{
    private $data = [];
    private $method;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Si la solicitud es GET, obtiene los datos de la URL (query string)
        if ($this->method === 'POST') {
            $this->data = $this->getContentFromJson();
        }
    }

    public function post($key)
    {
        // Solo permite obtener datos de $_POST si la solicitud es un POST
        return ($this->method === 'POST' && isset($this->data[$key])) ? $this->data[$key] : null;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function all()
    {
        return $this->data;
    }

    public function getContentFromJson()
    {
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }
}
