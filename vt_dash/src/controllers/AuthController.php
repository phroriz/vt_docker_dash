<?php

namespace src\controllers;

use core\Controller;
use src\handlers\UserHandler;
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

    public function resetPass()
    {
        if(empty($_SESSION['user_id'])){
            $this->redirect('/auth/signin');
        } else {
            $this->render('auth/reset');
        }

    }
    public function resetPassLogin()
    {

        $input = json_decode(file_get_contents('php://input'), true);
        $update = UserHandler::resetPass($input);
        return $update;
       // $this->redirect('/painel');

    }
    public function logoff()
    {
        UserHandler::logoff();
        $this->redirect('/auth/signin');
    }

    public function login()
    {
       UserHandler::auth();
    }
}
