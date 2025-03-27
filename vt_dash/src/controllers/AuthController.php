<?php

namespace src\controllers;

use core\Controller;
use src\models\User;

class AuthController extends Controller
{
    /*private $loggedUser;
    public function __construct()
    {
        // Inicializa o usuário logado no construtor
        $this->loggedUser = UserHandler::checkLogin();
    }
    /**
     * Endpoint para receber o webhook e salvar a ação na fila.
     */
    public function signin()
    {
       $this->render('auth/signin');
        /*  Passar os dados para a View
        $this->renderLayout('painel', 'projects', [
            'mergedProjects' => $mergedProjects
        ], []);
        */
    }

    public function login()
    {
        header('Content-Type: application/json');

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode([
                'status' => 'error',
                'title' => 'Erro',
                'message' => 'Preencha todos os campos.'
            ]);
            return;
        }

        $user = User::select()
            ->where('email', $email)
            ->where('pass', md5($password))
            ->one();

        if ($user) {
            $_SESSION['user'] = $user['id'];
            echo json_encode([
                'status' => 'success',
                'title' => 'Login realizado',
                'message' => 'Redirecionando...',
                'redirect' => '/painel'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'title' => 'Login inválido',
                'message' => 'Email ou senha incorretos.'
            ]);
        }
    }
}
