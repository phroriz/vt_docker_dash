<?php
namespace src\middlewares;

abstract class Middleware {
    public abstract function handle($request, $response, $next);
}
