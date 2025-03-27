<?php

namespace src\controllers;

use core\Controller;

class ErrorController extends Controller
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
    public function index()
    {
       echo "Pagina nao econtrada";
        /*  Passar os dados para a View
        $this->renderLayout('painel', 'projects', [
            'mergedProjects' => $mergedProjects
        ], []);
        */
    }
}
