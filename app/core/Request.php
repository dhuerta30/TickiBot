<?php

namespace App\core;

class Request
{
    private $data = [];
    private $method;

    public function __construct($method = null, $uri = null)
    {
        $this->method = $method ?? $_SERVER['REQUEST_METHOD'];

        if ($this->method === 'POST') {
            $this->data = $_POST;
        } else {
            $uri = $uri ?? $_SERVER['REQUEST_URI'];
            $this->data = $this->parseUrlSegments($uri);
        }
    }

    private function parseUrlSegments($requestUri)
    {
        $requestUri = str_replace($_ENV["BASE_URL"], '', $requestUri);
        $segments = explode('/', trim($requestUri, '/'));

        $params = [];
        $numSegments = count($segments);

        for ($i = 0; $i < $numSegments; $i += 2) {
            $key = $segments[$i] ?? null;
            $value = $segments[$i + 1] ?? null;

            if ($key !== null && $value !== null) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    public function post($key)
    {
        return ($this->method === 'POST' && isset($this->data[$key])) ? $this->data[$key] : null;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function all()
    {
        return $this->data;
    }

    // Agrega este método para inicializar datos adicionales en `Request`
    public function initialize(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }
}
