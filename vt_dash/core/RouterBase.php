<?php
namespace core;

use src\Config;

class RouterBase {
    private $globalMiddlewares = [];
    private $routeMiddlewares = [];
    protected $routes = []; // Propriedade declarada

    public function use($middleware) {
        $this->globalMiddlewares[] = $middleware;
    }

    public function addRoute($method, $endpoint, $callback, $middlewares = []) {
        $this->routes[$method][$endpoint] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function run() {
        $method = Request::getMethod();
        $url = Request::getUrl();

        $controller = Config::ERROR_CONTROLLER;
        $action = Config::DEFAULT_ACTION;
        $args = [];
        $middlewares = $this->globalMiddlewares;

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $data) {
                $pattern = preg_replace('(\{[a-z0-9]{1,}\})', '([a-z0-9-]{1,})', $route);

                if (preg_match('#^(' . $pattern . ')*$#i', $url, $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);

                    $itens = [];
                    if (preg_match_all('(\{[a-z0-9]{1,}\})', $route, $m)) {
                        $itens = preg_replace('(\{|\})', '', $m[0]);
                    }

                    foreach ($matches as $key => $match) {
                        $args[$itens[$key]] = $match;
                    }

                    $middlewares = array_merge($middlewares, $data['middlewares']);
                    $callbackSplit = explode('@', $data['callback']);
                    $controller = $callbackSplit[0];
                    if (isset($callbackSplit[1])) {
                        $action = $callbackSplit[1];
                    }

                    break;
                }
            }
        }

        $this->applyMiddlewares($controller, $action, $args, $middlewares);
    }

    private function applyMiddlewares($controller, $action, $args, $middlewares) {
        $next = function () use ($controller, $action, $args) {
            // Instancia o controlador apenas no final do fluxo de middlewares
            $controllerInstance = "\src\controllers\\$controller";
            $definedController = new $controllerInstance();
            $definedController->$action($args);
        };

        while ($middleware = array_pop($middlewares)) {
            $next = function () use ($middleware, $next) {
                (new $middleware())->handle(new Request(), new Response(), $next);
            };
        }

        $next();
    }
}
