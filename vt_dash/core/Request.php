<?php
namespace core;

use src\Config;

class Request {
    public mixed $user = null;

    public static function getUrl() {
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        $url = str_replace(Config::BASE_DIR, '', $url);
        $url = explode('?', $url)[0];
        return '/' . trim($url, '/');
    }

    public static function getMethod() {
        return strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
    }
}
