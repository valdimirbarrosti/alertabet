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
        $uri = parse_url($_SERVER['REQUEST_URI']);
        // Verifica se a rota possui parâmetros
        if (!$uri['query']) {
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
        } else {
            if (isset($this->routes[$method][$uri['path']])) {
                $handler = $this->routes[$method][$uri['path']];
                if (is_callable($handler)) {
                    debug($handler);
                    $handler();
                } elseif (is_array($handler) && count($handler) === 2) {
                    $this->parseQueryString($uri['query']);
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
    public  function parseQueryString($queryString)
    {
        if (empty($queryString)) {
            return;
        }
        $queryParams = explode('&', $queryString);

        foreach ($queryParams as $param) {
            // Divide o par chave-valor
            list($key, $value) = explode('=', $param);

            // Decodifica a chave e o valor
            $key = urldecode($key);
            $value = urldecode($value);

            // Define as variáveis globais $_GET
            $_GET[$key] = $value;
        }
    }
}
