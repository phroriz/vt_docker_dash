<?php

namespace src\controllers;

use core\Controller;
use src\handlers\CommentHandler;
use src\handlers\DashHandler;
use src\handlers\NpsHandler;

class DashboardController extends Controller
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
    public function view($args)
    {
        $hash = $args['hash'];
        $dash = DashHandler::getByHash($hash);
        $comments = CommentHandler::get($dash->id);
        $score = NpsHandler::score($dash->id);
        $checkNps = NpsHandler::checkQuest($dash->id, $dash->qtd_access);


        
        $this->renderLayout('painel', 'dashboard/view', [
            'dash' => $dash,
            'comments' =>$comments,
            'score'    => $score,
            'checkNps' => $checkNps
        ], []);
    
    }


    public function nps(){
        $input = json_decode(file_get_contents('php://input'), true);
        $remove = NpsHandler::new($input);
        return $remove;
    }
}
