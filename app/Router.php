<?php

namespace App;

class Router
{

    protected $routes = [];


    public function get($uri, $handler)
    {

        $this->routes['GET'][$uri] = $handler;
    }

    public function post($uri, $handler)
    {
        $this->routes['POST'][$uri] = $handler;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            if (is_callable($handler)) {
                $handler();
            } elseif (is_array($handler) && count($handler) === 2) {
                $controller = new $handler[0]();
                $method = $handler[1];
                $controller->$method();
            } else {
                http_response_code(404);
                echo "Erro 404: Página não encontrada.";
            }
        } else {
            http_response_code(404);
            echo "Erro 404: Página não encontrada.";
        }
    }
}
