<?php

namespace App\core;

class ArtifyRouter {
    private $routes = [];
    private $namedRoutes = [];
    private $currentRouteKey;

    public function get($uri, $action) {
        return $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        return $this->addRoute('POST', $uri, $action);
    }

    private function addRoute($method, $uri, $action) {
        if (!in_array($method, ['GET', 'POST'])) {
            throw new \InvalidArgumentException("Invalid HTTP method: $method");
        }
        
        $routeKey = count($this->routes); // Guarda el índice de la ruta actual
        $this->routes[$routeKey] = [
            'method' => $method,
            'uri' => $this->formatUri($uri),
            'action' => $action
        ];
        
        $this->currentRouteKey = $routeKey; // Establece la ruta actual
        return $this; // Permite encadenar métodos
    }

    // Método para asignar nombre a la última ruta agregada
    public function name($name) {
        if ($this->currentRouteKey === null) {
            throw new \LogicException("No route available to name.");
        }

        $this->namedRoutes[$name] = $this->routes[$this->currentRouteKey]['uri'];
        return $this; // Permite seguir encadenando si es necesario
    }

    // Método para obtener la URL de una ruta por su nombre
    public function url($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            throw new \InvalidArgumentException("No route named '$name' found.");
        }

        $uri = $this->namedRoutes[$name];

        // Reemplazar parámetros en la URI, si es necesario
        foreach ($params as $key => $value) {
            $uri = preg_replace('/\{' . preg_quote($key, '/') . '\}/', $value, $uri);
        }

        return '/' . $uri; // Retorna la URI completa
    }

    private function formatUri($uri) {
        return trim($uri, '/');
    }

    public function dispatch(Request $request) {
        $requestMethod = $request->getMethod();
        $requestUri = $this->formatUri(str_replace($_ENV["BASE_URL"], '', $_SERVER['REQUEST_URI']));
    
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($this->convertToRegex($route['uri']), $requestUri, $matches)) {
                // Combina parámetros de ruta y solicitud en un solo array
                $params = array_merge($matches, $request->all());
                
                // Evita duplicados: solo añade `paramX` si no hay una clave de nombre equivalente
                foreach ($matches as $key => $value) {
                    if (is_int($key) && $key > 0 && !isset($params["param{$key}"])) {
                        $params["param{$key}"] = $value;
                    }

                    if(isset($params["Restp"])){
                        if (count($matches) === 3) {
                            // Ruta con {tabla} y {token}
                            $params['tabla'] = $matches[1];
                            $params['token'] = $matches[2];
                        } elseif (count($matches) === 4) {
                            // Ruta con {tabla}, {filtro_url} y {token}
                            $params['tabla'] = $matches[1];
                            $params['filtro_url'] = $matches[2];
                            $params['token'] = $matches[3];
                        }
            
                        // Eliminar índices numéricos innecesarios
                        unset($params[0], $params[1], $params[2], $params[3], $params["param1"], $params["param2"], $params["param3"]);
                    }
                }

                // Aquí crea un nuevo objeto Request con los parámetros
                $newRequest = new Request($requestMethod, $_SERVER['REQUEST_URI']);
                $newRequest->initialize($params);
    
                // Ejecuta la acción del controlador
                $this->executeAction($route['action'], $newRequest);
                return;
            }
        }
    
        // Manejo de error 404 si no se encuentra ninguna ruta
        http_response_code(404);
        Redirect::to("error");
    }            

    private function convertToRegex($routeUri) {
        $routeUri = preg_replace('/\{(\w+)\}/', '([^/]+)', $routeUri);
        return '#^' . $routeUri . '$#';
    }

    private function executeAction($action, Request $request) {
        list($controller, $method) = explode('@', $action);
        $controller = "App\\Controllers\\$controller";
    
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $method)) {
                // Llama al método del controlador pasando el objeto `Request`
                $controllerInstance->$method($request);
            } else {
                http_response_code(404);
                Redirect::to("error");
            }
        } else {
            http_response_code(404);
            Redirect::to("error");
        }
    }
}
