<?php
namespace core;

class Response {
    private $headers = [];
    private $statusCode = 200;

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
    }

    public function setStatusCode($code) {
        $this->statusCode = $code;
    }

    public function send($content) {
        // Define o status code
        http_response_code($this->statusCode);

        // Define os headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Envia o conteúdo
        echo $content;
    }
}
