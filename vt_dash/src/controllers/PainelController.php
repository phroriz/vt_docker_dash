<?php

namespace src\controllers;

use core\Controller;
use src\handlers\PowerBiHandler;
use src\models\PowerBi;

class PainelController extends Controller
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
    public function home()
    {
        $this->renderLayout('painel', 'index');
       
    }
}
