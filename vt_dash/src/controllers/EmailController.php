<?php

namespace src\controllers;

use core\Controller;
use src\handlers\EmailHandler;

class EmailController extends Controller
{

    public function send() {
        $email = new EmailHandler();

        $email->setVars([
            'body.title' => 'Bem-vindo ao site!',
            'body.text'  => 'Seu cadastro foi realizado com sucesso.',
            'button.text' => 'Clique aqui para acessar'
        ]);
        
        $enviado = $email->send('ph.roriz09@email.com', 'Cadastro Realizado');
        
        if ($enviado === true) {
            echo 'Enviado com sucesso!';
        } else {
            echo $enviado; // erro
        }

    }
}
