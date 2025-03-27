<?php
namespace core;

class Router extends RouterBase {
    public function get($endpoint, $callback, $middlewares = []) {
        $this->addRoute('get', $endpoint, $callback, $middlewares);
    }

    public function post($endpoint, $callback, $middlewares = []) {
        $this->addRoute('post', $endpoint, $callback, $middlewares);
    }

    public function put($endpoint, $callback, $middlewares = []) {
        $this->addRoute('put', $endpoint, $callback, $middlewares);
    }

    public function delete($endpoint, $callback, $middlewares = []) {
        $this->addRoute('delete', $endpoint, $callback, $middlewares);
    }

    public function getRoutes() {
        return $this->routes; // Retorna as rotas registradas
    }
}
