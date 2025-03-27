<?php
namespace src\middlewares;

use src\handlers\UserHandler;

class AuthApiMiddleware extends Middleware
{
    private static $loggedUser = null; // Propriedade estática para armazenar o usuário autenticado

    public function handle($request, $response, $next)
    {

        // Obtém o cabeçalho Authorization
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? '';

        // Verifica se o cabeçalho Authorization está presente e inicia com "Basic"
        if (stripos($authHeader, 'Basic ') !== 0) {
            return $this->unauthorizedResponse($response, 'Cabeçalho Authorization ausente ou inválido.');
        }

        // Extrai o token Base64
        $base64Token = trim(str_ireplace('Basic', '', $authHeader));

        // Decodifica o Base64
        $decodedToken = base64_decode($base64Token);

        // Verifica se o token contém o formato esperado (email:token)
        if (strpos($decodedToken, ':') === false) {
            return $this->unauthorizedResponse($response, 'Formato do token inválido. Use "email:token" codificado em Base64.');
        }

        // Separa o email e o token
        [$email, $token] = explode(':', $decodedToken, 2);

        // Valida o email e o token
        if (empty($email) || empty($token)) {
            return $this->unauthorizedResponse($response, 'Email ou token ausente no cabeçalho Authorization.');
        }

        // Verifica se o usuário existe e se o token é válido
        $user = UserHandler::authenticateApiToken($email, $token);
        if (!$user) {
            return $this->unauthorizedResponse($response, 'Usuário não encontrado ou token inválido.');
        }

        // Armazena o usuário autenticado na propriedade estática
        self::$loggedUser = $user;

        // Permite a execução do próximo middleware ou rota
        return $next($request, $response);
    }

    /**
     * Retorna o usuário autenticado.
     *
     * @return mixed|null
     */
    public static function getLoggedUser()
    {
        return self::$loggedUser;
    }

    /**
     * Retorna uma resposta de erro 401 Unauthorized.
     *
     * @param $response O objeto de resposta
     * @param string $message Mensagem de erro para ser retornada
     * @return mixed A resposta com status 401
     */
    private function unauthorizedResponse($response, string $message)
    {
        http_response_code(401); // Define o código de status HTTP como 401
        echo json_encode([
            'error' => $message
        ]);
        exit; // Finaliza a execução
    }
}
